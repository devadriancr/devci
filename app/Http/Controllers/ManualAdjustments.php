<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
class ManualAdjustments extends Controller
{
    public function index()
    {

        $locations=location::where('code','Like','L61%')->orwhere('code','Like','L60%')->orwhere('code','Like','L12%')->get();
        return view('manualadjustment.index',['locations'=>$locations]);

    }
    public function insert(Request $request)
    {

        $locations=location::where('code','Like','L61%')->orwhere('code','Like','L60%')->orwhere('code','Like','L12%')->get();
        return view('manualadjustment.index',['locations'=>$locations]);

    }


}
