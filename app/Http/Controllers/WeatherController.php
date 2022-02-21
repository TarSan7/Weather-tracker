<?php

namespace App\Http\Controllers;

use App\Imports\WeatherImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WeatherController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function import(Request $request)
    {

        Excel::import(new WeatherImport, $request->file('files'));

        return redirect('/')->with('success', 'All good!');

    }
}
