<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function __construct()
    {
    }

    public function sampleFunction(Request $request)
    {
        try {
            return customResponse()
                ->data($request->all())
                ->message('')
                ->success()
                ->generate();
        } catch (\Exception $e) {
            return customResponse()
                ->data([])
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }
}
