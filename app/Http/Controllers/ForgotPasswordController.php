<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;


class ForgotPasswordController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {

        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return response(['message' => trans('passwords.sent')], 200);
    }

    public function reset (Request $request)
    {
        try {
            $payload = $request->validate([
                'code' => 'required',
                'password' => 'required',
            ]);

            $code = ResetCodePassword::where('code', $payload['code'])->first();

            $user = User::where('email', $code->email)->first();
            $user->password = bcrypt($payload['password']);
            $user->save();

            ResetCodePassword::where('email', $code->email)->delete();

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
