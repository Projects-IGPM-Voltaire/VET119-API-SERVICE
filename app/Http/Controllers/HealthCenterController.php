<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthCenterController extends Controller
{
    public function store(Request $request)
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
                ->success()
                ->generate();
        }
    }
}
