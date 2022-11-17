<?php

namespace App\Http\Controllers;

use App\Models\Container;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\Promise\all;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $containers = Container::where('status', '=', 1)->orderBy('date', 'DESC')->orderBy('time', 'DESC')->paginate(10);

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
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $container = Container::create([
            'code' => $request->code,
            'date' => Carbon::parse($request->date)->format('Ymd'),
            'time' => Carbon::parse($request->time)->format('His'),
            'user_id' => Auth::id(),
        ]);

        return redirect('container');
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
        $data = $request->except('date', 'time');

        if (!empty($request->date)) {
            $data['date'] = Carbon::parse($request->date)->format('Ymd');
        }
        if (!empty($request->time)) {
            $data['time'] = Carbon::parse($request->time)->format('His');
        }

        $container->fill($data);

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
