<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $payload = $request->all();
            if (
                !Auth::attempt([
                    'email' => $payload['email'],
                    'password' => $payload['password'],
                ])
            ) {
                return customResponse()
                    ->data([])
                    ->message('Invalid credentials.')
                    ->unathorized()
                    ->generate();
            }
            $accessToken = Auth::user()->createToken('authToken')->accessToken;
            $user = User::find(Auth::id());

            if (!isset($user->email_verified_at))
            {
                return customResponse()
                    ->data([])
                    ->message('Please click the link sent to your email to verify.')
                    ->unathorized()
                    ->generate();
            }

            return customResponse()
                ->data([
                    'access_token' => $accessToken,
                    'user' => $user,
                ])
                ->message('Done')
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

    public function register(RegisterRequest $request)
    {
        try {
            $payload = $request->all();
            $user = User::create([
                'first_name' => trim($payload['first_name']),
                'last_name' => trim($payload['last_name']),
                'mobile_number' => trim($payload['mobile_number']),
                'password' => bcrypt($payload['password']),
                'level' => $payload['level'],
                'email' => $payload['email'],
            ]);
            event(new Registered($user));
            return customResponse()
                ->data($user)
                ->message('Operation success.')
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
