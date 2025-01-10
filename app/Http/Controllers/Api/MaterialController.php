<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\FormatQRCodeJob;
use App\Jobs\ScannedMaterialJob;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\output;
use App\Models\TransactionType;
use App\Models\YI007;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function materialExit(Request $request)
    {
        try {
            // Validación de parámetros recibidos
            $request->validate([
                'supplier' => 'required|string',
                'serial' => 'required|string',
                'part_no' => 'required|string',
                'part_qty' => 'required|integer',
                'container_id' => 'nullable|integer',
                'no_order' => 'nullable|string',
            ]);

            // Asignación de variables
            $supplier = $request->supplier;
            $serial = $request->serial;
            $part_no = $request->part_no;
            $part_qty = $request->part_qty;
            $container_id = $request->container_id ?? '';
            $no_order = $request->no_order ?? '';

            // Buscar el ítem
            $item = Item::where('item_number', 'LIKE', $part_no . '%')->first();

            if (!$item) {
                // Log de error si no se encuentra el item
                Log::error("Item no encontrado para el part_no: $part_no");

                return response()->json([
                    'message' => 'Item no encontrado',
                ], 404); // 404 Not Found
            }

            // Buscar Location External
            $location_external = Location::where('code', 'LIKE', 'L61%')->first();

            if (!$location_external) {
                Log::error("No se encontró la ubicación externa para el código L61%");

                return response()->json([
                    'message' => 'Ubicación externa no encontrada',
                ], 404); // 404 Not Found
            }

            // Buscar las ubicaciones para los movimientos
            $location_old = Location::with('warehouse')->where('code', 'like', 'L60%')->first();
            $location_new = Location::with('warehouse')->where('code', 'like', 'L12%')->first();

            if (!$location_old || !$location_new) {
                Log::error("No se encontraron las ubicaciones L60% o L12%");

                return response()->json([
                    'message' => 'Ubicaciones no encontradas',
                ], 404); // 404 Not Found
            }

            // Obtener el tipo de transacción
            $transaction_type = TransactionType::where('code', 'like', '%T%')->first();

            if (!$transaction_type) {
                Log::error("No se encontró el tipo de transacción");

                return response()->json([
                    'message' => 'Tipo de transacción no encontrado',
                ], 404); // 404 Not Found
            }

            // Verificar si el serial existe
            $serial_exist = Input::where([['serial', $serial], ['supplier', $supplier], ['item_id', $item->id], ['item_quantity', $part_qty]])
                ->orderby('id', 'desc')->first();

            // Lógica cuando el serial existe
            if ($serial_exist != null) {
                if ($serial_exist->location_id !=  $location_external->id) {
                    output::dispatchStorage($supplier, $serial, $item->id, $part_qty, $transaction_type->id, $location_old->id);
                    output::adjustInventoryOutput($item->id, $location_old->id, $part_qty);

                    $input = Input::materialReceived($supplier, $serial,  $item->id, $part_qty, $container_id, $transaction_type->id, $location_new->id, $no_order);
                    Input::adjustInventoryInput($item->id, $location_old->id, $part_qty);

                    YI007::storeYI007(
                        $item->item_number ?? '',
                        $serial ?? '',
                        'O',
                        Carbon::parse($input->arrival_date)->format('Ymd'),
                        Carbon::parse($input->arrival_time)->format('His'),
                        $part_qty ?? '',
                        $location_old->warehouse->code ?? '',
                        'YKMS',
                        Carbon::parse($input->created_at)->format('Ymd'),
                        Carbon::parse($input->created_at)->format('His')
                    );

                    YI007::storeYI007(
                        $item->item_number,
                        $serial ?? '',
                        'I',
                        Carbon::parse($input->arrival_date)->format('Ymd'),
                        Carbon::parse($input->arrival_time)->format('His'),
                        $part_no,
                        $location_new->warehouse->code,
                        'YKMS',
                        Carbon::parse($input->created_at)->format('Ymd'),
                        Carbon::parse($input->created_at)->format('His')
                    );
                }
            } else {
                // Lógica cuando no existe el serial en el inventario
                $input_new = Input::materialReceived($supplier, $serial,  $item->id, $part_qty, $container_id, $transaction_type->id, $location_old->id, $no_order);

                Input::adjustInventoryInput($item->id, $location_old->id, $part_qty);

                YI007::storeYI007(
                    $item->item_number ?? '',
                    $serial ?? '',
                    'A',
                    Carbon::parse($input_new->arrival_date)->format('Ymd'),
                    Carbon::parse($input_new->arrival_time)->format('His'),
                    $part_qty ?? '',
                    $location_old->warehouse->code ?? '',
                    'YKMS',
                    Carbon::parse($input_new->created_at)->format('Ymd'),
                    Carbon::parse($input_new->created_at)->format('His')
                );

                output::dispatchStorage($supplier, $serial, $item->id, $part_qty, $transaction_type->id, $location_old->id);
                output::adjustInventoryOutput($item->id, $location_old->id, $part_qty);

                $input = Input::materialReceived($supplier, $serial,  $item->id, $part_qty, $container_id, $transaction_type->id, $location_new->id, $no_order);
                Input::adjustInventoryInput($item->id, $location_old->id, $part_qty);

                YI007::storeYI007(
                    $item->item_number ?? '',
                    $serial ?? '',
                    'O',
                    Carbon::parse($input->arrival_date)->format('Ymd'),
                    Carbon::parse($input->arrival_time)->format('His'),
                    $part_qty ?? '',
                    $location_old->warehouse->code ?? '',
                    'YKMS',
                    Carbon::parse($input->created_at)->format('Ymd'),
                    Carbon::parse($input->created_at)->format('His')
                );

                YI007::storeYI007(
                    $item->item_number ?? '',
                    $serial ?? '',
                    'I',
                    Carbon::parse($input->arrival_date)->format('Ymd'),
                    Carbon::parse($input->arrival_time)->format('His'),
                    $part_no ?? '',
                    $location_new->warehouse->code ?? '',
                    'YKMS',
                    Carbon::parse($input->created_at)->format('Ymd'),
                    Carbon::parse($input->created_at)->format('His')
                );
            }

            // Respuesta exitosa
            return response()->json([
                'message' => 'Operación realizada correctamente.',
            ], 200); // 200 OK

        } catch (\Exception $e) {
            // Log de error en caso de excepciones
            Log::error('Error en la API materialExit: ' . $e->getMessage(), [
                'request' => $request->all(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Ocurrió un error en el procesamiento.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }


        // $supplier = $request->supplier;
        // $serial = $request->serial;
        // $part_no = $request->part_no;
        // $part_qty = $request->part_qty;
        // $container_id = $request->container_id ?? '';
        // $no_order = $request->no_order ?? '';

        // $item = Item::where('item_number', 'LIKE', $part_no . '%')->first();

        // if ($item  == null) {

        //     $location_external = Location::where('code', 'LIKE', 'L61%')->first();

        //     $serial_exist = Input::where([['serial', $serial], ['supplier', $supplier], ['item_id', $item->id], ['item_quantity', $part_qty]])->orderby('id', 'desc')->first();

        //     $location_old = location::with('warehouse')->where('code', 'like', 'L60%')->first();

        //     $location_new = location::with('warehouse')->where('code', 'like', 'L12%')->first();

        //     $transaction_type = TransactionType::where('code', 'like', '%T%')->first();

        //     if ($serial_exist != null) {
        //         if ($serial_exist->location_id !=  $location_external->id) {

        //             output::dispatchStorage($supplier, $serial, $item->id, $part_qty, $transaction_type->id, $location_old->id);

        //             output::adjustInventoryOutput($item->id, $location_old->id, $part_qty);

        //             $input = Input::materialReceived($supplier, $serial,  $item->id, $part_qty, $container_id, $transaction_type->id, $location_new->id, $no_order);

        //             Input::adjustInventoryInput($item->id, $location_old->id, $part_qty);

        //             YI007::storeYI007(
        //                 $item->item_number ?? '',
        //                 $serial ?? '',
        //                 'O',
        //                 Carbon::parse($input->arrival_date)->format('Ymd'),
        //                 Carbon::parse($input->arrival_time)->format('His'),
        //                 $part_qty ?? '',
        //                 $location_old->warehouse->code ?? '',
        //                 'YKMS',
        //                 Carbon::parse($input->created_at)->format('Ymd'),
        //                 Carbon::parse($input->created_at)->format('His')
        //             );


        //             YI007::storeYI007(
        //                 $item->item_number,
        //                 $serial ?? '',
        //                 'I',
        //                 Carbon::parse($input->arrival_date)->format('Ymd'),
        //                 Carbon::parse($input->arrival_time)->format('His'),
        //                 $part_no,
        //                 $location_new->warehouse->code,
        //                 'YKMS',
        //                 Carbon::parse($input->created_at)->format('Ymd'),
        //                 Carbon::parse($input->created_at)->format('His'),
        //             );
        //         }
        //     } else {
        //         $input_new = Input::materialReceived($supplier, $serial,  $item->id, $part_qty, $container_id, $transaction_type->id, $location_old->id, $no_order);

        //         Input::adjustInventoryInput($item->id, $location_old->id, $part_qty);

        //         YI007::storeYI007(
        //             $item->item_number ?? '',
        //             $serial ?? '',
        //             'A',
        //             Carbon::parse($input_new->arrival_date)->format('Ymd'),
        //             Carbon::parse($input_new->arrival_time)->format('His'),
        //             $part_qty ?? '',
        //             $location_old->warehouse->code ?? '',
        //             'YKMS',
        //             Carbon::parse($input_new->created_at)->format('Ymd'),
        //             Carbon::parse($input_new->created_at)->format('His'),

        //         );

        //         output::dispatchStorage($supplier, $serial, $item->id, $part_qty, $transaction_type->id, $location_old->id);

        //         output::adjustInventoryOutput($item->id, $location_old->id, $part_qty);

        //         $input = Input::materialReceived($supplier, $serial,  $item->id, $part_qty, $container_id, $transaction_type->id, $location_new->id, $no_order);

        //         Input::adjustInventoryInput($item->id, $location_old->id, $part_qty);

        //         YI007::storeYI007(
        //             $item->item_number ?? '',
        //             $serial ?? '',
        //             'O',
        //             Carbon::parse($input->arrival_date)->format('Ymd'),
        //             Carbon::parse($input->arrival_time)->format('His'),
        //             $part_qty ?? '',
        //             $location_old->warehouse->code ?? '',
        //             'YKMS',
        //             Carbon::parse($input->created_at)->format('Ymd'),
        //             Carbon::parse($input->created_at)->format('His'),
        //         );

        //         YI007::storeYI007(
        //             $item->item_number ?? '',
        //             $serial ?? '',
        //             'I',
        //             Carbon::parse($input->arrival_date)->format('Ymd'),
        //             Carbon::parse($input->arrival_time)->format('His'),
        //             $part_no ?? '',
        //             $location_new->warehouse->code ?? '',
        //             'YKMS',
        //             Carbon::parse($input->created_at)->format('Ymd'),
        //             Carbon::parse($input->created_at)->format('His'),
        //         );
        //     }
        // }
    }
}
