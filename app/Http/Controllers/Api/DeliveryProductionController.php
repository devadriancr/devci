<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryProduction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryProductionController extends Controller
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

    /**
     *
     */
    public function registerControlNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'control_number' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'El número de nomina debe ser un número.',
                'errors' => $validator->errors()
            ], 400); // Código de respuesta 400 por solicitud incorrecta
        }

        try {
            // Intentamos crear el registro
            $delivery = DeliveryProduction::create([
                'control_number' => $request->control_number
            ]);

            return response()->json([
                'message' => 'Empleado registrado exitosamente.',
                'data' => $delivery
            ], 201); // Código de respuesta 201 por creación exitosa

        } catch (Exception $e) {
            // En caso de error
            return response()->json([
                'message' => 'Error al registrar el empleado.',
                'error' => $e->getMessage()
            ], 500); // Código de respuesta 500 por error del servidor
        }
    }
}
