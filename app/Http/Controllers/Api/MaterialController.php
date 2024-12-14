<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\FormatQRCodeJob;
use App\Jobs\ScannedMaterialJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
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
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'part_no' => 'required|string',
            'part_qty' => 'required',
            'supplier' => 'required|string',
            'serial' => 'required|string',
            'container_id' => 'required|integer|exists:containers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Enviar los datos al job
        $data = $request->only(['part_no', 'part_qty', 'supplier', 'serial', 'container_id']);

        ScannedMaterialJob::dispatch($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Datos enviados al job para procesamiento.',
        ], 200);
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
}
