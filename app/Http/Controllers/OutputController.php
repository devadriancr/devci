<?php

namespace App\Http\Controllers;

use App\Models\Output;
use App\Models\item;
use App\Models\input;
use App\Models\transactiontype;
use App\Models\travel;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OutputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $error = 0;
        $location = location::find($request->location_id);
        $cadena = explode(",", $request->serial);
        if (count($cadena) != 27) {
            $error = 1;
            $message = 'ESCANEO INCORRECTO';
        }
        if ($error == 0) {
            $item = DB::table('items')->where('item_number', 'like', '%' . $cadena[7] . '%')->first();
            if ($item == false) {
                $error = 2;
                $message = 'Item no existe';
            }
        }
        if ($error == 0) {
            $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();

            if ($location->code == 'L61       ') {
                $serial_exist = output::where('serial', $cadena[13])->where('travel_id', $request->travel_id)->first();
            } else {
                $serial_exist = input::where('serial', $cadena[13])->where('travel_id', $request->travel_id)->first();
            }
            if ($serial_exist != false) {

                $error = 3;
                $message = 'serial ya fue  escaneado';
            }
        }
        if ($error == 0) {
            $ultimaEnt = input::where('serial', $cadena[13])->where('travel_id','!=', $request->travel_id)->orderby('id','desc')->first();
            $ultimaSal = output::where('serial', $cadena[13])->where('travel_id', '!=',$request->travel_id)->orderby('id','desc')->first();

            if ($ultimaEnt != null && $ultimaSal != null) {
                if ($ultimaEnt->created_at > $ultimaSal->created_at) {
                    $ulm = $ultimaEnt;
                } else {
                    $ulm = $ultimaSal;
                }
            } else {
                if ($ultimaEnt == null && $ultimaSal == null)
                {
                }else
                {
                    if ($ultimaEnt != false) {
                        $ulm = $ultimaEnt;

                    }
                     if ($ultimaSal != false) {
                        $ulm = $ultimaSal;

                    }

                }


            }

            if ($ulm->location_id == $request->location_id) {
                $error = 4;
                $message = 'serial ya existente en el almacen actual';
            }

        }

        if ($error == 0) {
            if ($location->code == 'L61       ') {
                Output::create([
                    'supplier' =>  $cadena[11],
                    'serial' => $cadena[13],
                    'item_id' => $item->id,
                    'item_quantity' =>  $cadena[10],
                    'transaction_type_id' => $Transaction_type->id,
                    'travel_id' => $request->travel_id,
                    'location_id' => $request->location_id
                ]);


            } else {
                input::create([
                    'supplier' =>  $cadena[11],
                    'serial' => $cadena[13],
                    'item_id' => $item->id,
                    'item_quantity' =>  $cadena[10],
                    'transaction_type_id' => $Transaction_type->id,
                    'travel_id' => $request->travel_id,
                    'location_id' => $request->location_id
                ]);
            }

            $message = 'serial capturado exitosamente';
        }





        $travels = travel::find($request->travel_id);
        if ($location->code == 'L61       ') {
            $scan  = output::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        } else {
            $scan  = input::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        }
        $locations = location::find($request->location_id);
        return view('Output.index', ['travels' => $travels, 'scan' => $scan, 'msg' => $message, 'location_id' => $locations]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $travel = Travel::with('location')->find($request->travel_id);
        if ($travel->location->code == 'L61       ') {
            $scan  = output::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        } else {
            $scan  = input::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        }
        $msg = '';
        $locations = location::get();
        return view('Output.index', ['travels' => $travel, 'scan' => $scan, 'msg' => $msg, 'locations' => $locations]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function show(Output $output)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function edit(Output $output)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Output $output)
    {
        Travel::updateOrCreate(
            ['id' => $request->travel_id],
            ['finish' => 1]

        );

        return redirect()->action([TravelController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function destroy(Output $output)
    {
        //
    }
}
