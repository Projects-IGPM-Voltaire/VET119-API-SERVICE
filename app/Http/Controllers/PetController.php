<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use Exception;

class PetController extends Controller
{

    public function store(Request $request)
    {
        $pet = null;
        try {
            $payload = $request->all();
            $pet = Pet::create([
                'name' => $payload['name'],
                'species' => $payload['species'],
                'breed' => $payload['breed'] ?? null,
                'user_id' => $payload['user_id'] ?? null,
            ]);

            return customResponse()
                ->data($pet)
                ->message('Create request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data([])
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }
}
