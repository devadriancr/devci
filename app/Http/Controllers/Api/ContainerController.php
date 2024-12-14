<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Exception;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Consulta los contenedores activos, ordenados por fecha y hora de llegada
            $containers = Container::query()
                ->where('status', '=', 1)
                ->orderBy('arrival_date', 'ASC')
                ->orderBy('arrival_time', 'ASC')
                ->get();

            // Verifica si hay resultados
            if ($containers->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No hay contenedores activos.',
                    'data' => []
                ], 200);
            }

            // Respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'message' => 'Contenedores activos obtenidos con éxito.',
                'data' => $containers
            ], 200);
        } catch (Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al obtener los contenedores.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function show(Container $container)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function edit(Container $container)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Container $container)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function destroy(Container $container)
    {
        //
    }
}
