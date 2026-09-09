<?php

namespace App\Jobs;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\YH003;
use App\Models\YH003Failure;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreConsignmentMaterialJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $supplier, $serial, $part_no, $part_qty, $container_id;

    /**
     * @var int
     */
    public $uniqueFor = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($supplier, $serial, $part_no, $part_qty, $container_id)
    {
        $this->supplier = $supplier;
        $this->serial = $serial;
        $this->part_no = $part_no;
        $this->part_qty = $part_qty;
        $this->container_id = $container_id;
    }

    /**
     * Misma llave de unicidad que el resto de la cadena para el mismo material.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->supplier . '-' . $this->serial . '-' . $this->container_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Datos:', [
            'supplier' => $this->supplier,
            'serial' => $this->serial,
            'part_no' => $this->part_no,
            'container_id' => $this->container_id,
        ]);

        // Última barrera contra duplicados: si por algún motivo la cadena de
        // jobs se disparó más de una vez para el mismo material (p. ej. el
        // lock de unicidad expiró por un atraso largo en la cola), no se
        // vuelve a insertar.
        $yaRegistrado = ConsignmentInstruction::where([
            ['supplier', $this->supplier],
            ['serial', $this->serial],
            ['part_no', 'LIKE', $this->part_no . '%'],
            ['container_id', $this->container_id],
        ])->exists();

        if ($yaRegistrado) {
            Log::alert('StoreConsignmentMaterialJob: material ya registrado, se omite duplicado', [
                'supplier' => $this->supplier,
                'serial' => $this->serial,
                'part_no' => $this->part_no,
                'container_id' => $this->container_id,
            ]);
            return;
        }

        // Obtener el artículo
        $item = Item::where('item_number', 'LIKE', $this->part_no . '%')->firstOrFail();
        Log::info($item->item_number);

        // Obtener el contenedor
        $container = Container::findOrFail($this->container_id);
        Log::info($container->code);

        // Obtener tipo de transacción
        $transactionType = TransactionType::where('code', 'U3')->firstOrFail();

        // Obtener ubicación
        $location = Location::where('code', 'LIKE', 'L60%')->firstOrFail();

        // Crear ConsignmentInstruction
        $cons = ConsignmentInstruction::create([
            'supplier' => $this->supplier,
            'serial' => $this->serial,
            'part_qty' => $this->part_qty,
            'part_no' => $this->part_no,
            'location' => 'L60',
            'flag' => true,
            'container_id' => $this->container_id,
        ]);

        Log::info($cons->id);

        // Crear Input
        $input = Input::create([
            'supplier' => $this->supplier,
            'serial' => $this->serial,
            'item_id' => $item->id,
            'item_quantity' => $this->part_qty,
            'type_consignment' => 'MY',
            'container_id' => $container->id,
            'transaction_type_id' => $transactionType->id,
            'location_id' => $location->id,
        ]);

        Log::alert($input->id);

        try {
            // Insertar en YH003
            YH003::insert([
                'H3CONO' => $container->code ?? '',
                'H3DDTE' => Carbon::parse($container->arrival_date)->format('Ymd'),
                'H3DTIM' => Carbon::parse($container->arrival_time)->format('His'),
                'H3PROD' => $item->item_number,
                'H3SUCD' => $this->supplier,
                'H3SENO' => $this->serial,
                'H3RQTY' => $this->part_qty,
                'H3CUSR' => '',
                'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
                'H3RTIM' => Carbon::parse($input->created_at)->format('His')
            ]);
        } catch (\Exception $e) {
            // En caso de error, guardar los datos en la tabla de fallos
            YH003Failure::create([
                'H3CONO' => $container->code ?? '',
                'H3DDTE' => Carbon::parse($container->arrival_date)->format('Ymd'),
                'H3DTIM' => Carbon::parse($container->arrival_time)->format('His'),
                'H3PROD' => $item->item_number,
                'H3SUCD' => $this->supplier,
                'H3SENO' => $this->serial,
                'H3RQTY' => $this->part_qty,
                'H3CUSR' => '',
                'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
                'H3RTIM' => Carbon::parse($input->created_at)->format('His'),
                'status' => true
            ]);

            Log::error("Error al insertar en YH003: " . $e->getMessage());
        }

        Log::alert("Registrado en infor");

        // Obtener o crear la entrada en Inventory
        $itemInventory = Inventory::where([
            ['item_id', '=', $item->id],
            ['location_id', '=', $location->id]
        ])->first();

        $currentQuantity = $itemInventory->quantity ?? 0;
        $newQuantity = $currentQuantity + $this->part_qty;

        // Actualizar la cantidad en Inventory
        Inventory::updateOrCreate(
            ['item_id' => $item->id, 'location_id' => $location->id],
            ['quantity' => $newQuantity]
        );

        Log::info("OK");
    }
}
