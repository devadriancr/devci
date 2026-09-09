<?php

namespace App\Jobs;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\ShippingInstruction;
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
use Illuminate\Support\Facades\Log;

class ScannedMaterialJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $data;

    /**
     * Número de segundos que el lock de unicidad permanece activo como máximo.
     * Sirve como red de seguridad si la cola se atrasa; en condiciones normales
     * el lock se libera en cuanto el job termina.
     *
     * @var int
     */
    public $uniqueFor = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Llave de unicidad: evita procesar el mismo material (proveedor + serial +
     * contenedor) más de una vez mientras ya hay una instancia en cola/ejecución.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->data['supplier'] . '-' . $this->data['serial'] . '-' . $this->data['container_id'];
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $part_no = $this->data['part_no'];
        $part_qty = $this->data['part_qty'];
        $supplier = $this->data['supplier'];
        $serial = $this->data['serial'];
        $container_id = $this->data['container_id'];

        $data = ConsignmentInstruction::where(
            [
                ['supplier', $supplier],
                ['serial', $serial],
                ['part_no', 'LIKE', $part_no . '%'],
                ['container_id', $container_id]
            ]
        )->first();

        if (is_null($data)) {
            Log::info("ScannedMaterialJob: ", [
                'supplier' => $supplier,
                'serial' => $serial,
                'part_no' => $part_no,
                'container_id' => $container_id
            ]);
            CheckQRCodeRegistrationJob::dispatch($supplier, $serial, $part_no, $part_qty, $container_id);
        } else {
            Log::alert("Material ya registrado: ", ['data' => $data]);
        }
    }
}
