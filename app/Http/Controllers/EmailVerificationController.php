<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
    }

    public function verifyEmail($id, $hash, Request $request)
    {

        try {
            $user = User::find($id);
            $user->markEmailAsVerified();

            return customResponse()
                ->data($request->all())
                ->message('success')
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
