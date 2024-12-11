<?php

namespace App\Jobs;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
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

class StoreConsignmentMaterialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $supplier, $serial, $part_no, $part_qty, $container_id;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Obtener el artículo
        $item = Item::where('item_number', 'LIKE', $this->part_no . '%')->firstOrFail();

        // Obtener el contenedor
        $container = Container::findOrFail($this->container_id);

        // Obtener tipo de transacción
        $transactionType = TransactionType::where('code', 'U3')->firstOrFail();

        // Obtener ubicación
        $location = Location::where('code', 'LIKE', 'L60%')->firstOrFail();

        // Crear ConsignmentInstruction
        ConsignmentInstruction::create([
            'supplier' => $this->supplier,
            'serial' => $this->serial,
            'part_qty' => $this->part_qty,
            'part_no' => $this->part_no,
            'location' => 'L60',
            'flag' => true,
            'container_id' => $this->container_id,
            'user_id' => Auth::id(),
        ]);

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
            'user_id' => Auth::id()
        ]);

        try {
            // Insertar en YH003
            YH003::insert([
                'H3CONO' => $container->id ?? '',
                'H3DDTE' => Carbon::parse($container->arrival_date)->format('Ymd'),
                'H3DTIM' => Carbon::parse($container->arrival_time)->format('His'),
                'H3PROD' => $item->item_number,
                'H3SUCD' => $this->supplier,
                'H3SENO' => $this->serial,
                'H3RQTY' => $this->part_qty,
                'H3CUSR' => Auth::user()->user_infor ?? '',
                'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
                'H3RTIM' => Carbon::parse($input->created_at)->format('His')
            ]);
            dump('Entro');
        } catch (\Exception $e) {
            // En caso de error, guardar los datos en la tabla de fallos
            YH003Failure::create([
                'H3CONO' => $container->id ?? '',
                'H3DDTE' => Carbon::parse($container->arrival_date)->format('Ymd'),
                'H3DTIM' => Carbon::parse($container->arrival_time)->format('His'),
                'H3PROD' => $item->item_number,
                'H3SUCD' => $this->supplier,
                'H3SENO' => $this->serial,
                'H3RQTY' => $this->part_qty,
                'H3CUSR' => Auth::user()->user_infor ?? '',
                'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
                'H3RTIM' => Carbon::parse($input->created_at)->format('His'),
                'status' => true
            ]);

            // \Log::error("Error al insertar en YH003: " . $e->getMessage());
        }

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
    }
}
