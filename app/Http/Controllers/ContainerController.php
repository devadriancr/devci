<?php

namespace App\Http\Controllers;

use App\Models\Container;
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
        $containers = Container::where('status', '=', 1)->orderBy('arrival_date', 'DESC')->orderBy('arrival_time', 'DESC')->paginate(10);

        return view('container.index', ['containers' => $containers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('container.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:11', 'min:11'],
            'arrival_date' => ['required', 'date_format:Y-m-d'],
            'arrival_time' => ['required', 'date_format:H:i'],
        ]);


        Container::storeContainer($request->code, $request->arrival_date, $request->arrival_time);

        return redirect('container');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Container $container)
    {
        return view('container.edit', ['container' => $container]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Container $container)
    {
        $request->validate([
            'code' => ['string', 'max:11', 'min:11'],
            'arrival_date' => ['date_format:Y-m-d'],
            'arrival_time' => ['date_format:H:i'],
        ]);

        $container->fill($request->all());

        if ($container->isDirty()) {
            $container->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Container $container)
    {
        $container->update(['status' => 0]);
        return redirect()->back();
    }
}
