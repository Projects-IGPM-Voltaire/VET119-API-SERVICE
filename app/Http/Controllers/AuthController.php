<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $payload = $request->all();
            if (
                !Auth::attempt([
                    'mobile_number' => $payload['username'],
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
            $user = User::with([
                'health_center_member',
                'health_center_member.center',
            ])->find(Auth::id());
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
                ->success()
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
                'birthday' => $payload['birthday'],
                'mobile_number' => trim($payload['mobile_number']),
                'password' => bcrypt($payload['password']),
                'level' => $payload['level'],
            ]);
            if (isset($payload['city_code'])) {
                $user->address()->create([
                    'city_code' => $payload['city_code'],
                    'barangay_code' => $payload['barangay_code'] ?? null,
                    'house_number' => $payload['house_number'] ?? null,
                    'street' => $payload['street'] ?? null,
                ]);
            }
            if (
                isset($payload['health_center_id']) &&
                $payload['level'] === 'guest'
            ) {
                $user->health_center_member()->create([
                    'user_id' => $user->id,
                    'health_center_id' => $payload['health_center_id'],
                    'position' => 'patient',
                ]);
            }

            return customResponse()
                ->data($user)
                ->message('Operation success.')
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
