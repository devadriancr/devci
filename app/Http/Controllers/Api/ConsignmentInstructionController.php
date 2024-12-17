<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;

class ConsignmentInstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkMaterial(Request $request)
    {

        // Validar que el container_id esté presente y sea válido
        $request->validate([
            'container_id' => 'required|exists:containers,id',
        ]);

        // Obtener el contenedor directamente y verificar su estado
        $container = Container::find($request->container_id);

        if (!$container->status) {
            return response()->json([
                'message' => 'El contenedor no está activo.'
            ], 404);
        }

        // Obtener las consignaciones relacionadas
        $consignments = ConsignmentInstruction::query()
            ->where('container_id', $container->id)
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        // Obtener los envíos relacionados
        $shipments = ShippingInstruction::query()
            ->where([
                ['container', '=', $container->code],
                ['arrival_date', '=', $container->arrival_date],
                ['arrival_time', '=', $container->arrival_time],
                ['status', '=', true]
            ])
            ->orderBy('serial', 'ASC')
            ->get();

        // Crear arreglo con seriales de consignaciones
        $consignmentSerials = $consignments->map(function ($consignment) {
            return $consignment->supplier . $consignment->serial;
        })->toArray();

        // Identificar los envíos que no están en las consignaciones
        $notFound = $shipments->filter(function ($shipping) use ($consignmentSerials) {
            return !in_array($shipping->serial, $consignmentSerials);
        })->map(function ($shipping) {
            return [
                'serial' => $shipping->serial,
                'part_no' => $shipping->part_no,
                'part_qty' => $shipping->part_qty,
            ];
        });

        return response()->json([
            'materialNotFound' => $notFound->values(), // Convertir a un arreglo indexado
            'quantityNotFound' => $notFound->count(),
            'container_id' => $container->id,
        ]);
    }
}
