<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password as FacadesPassword;

class Password extends Controller
{

    public function forgot_password(Request $request)
    {
        try {
            $request->validate([
                'email' => 'string|email:rfc,dns|max:40|required'
            ]);

            $status = FacadesPassword::sendResetLink(
                $request->only('email')
            );

            return $status === FacadesPassword::RESET_LINK_SENT ?
                response()->json([
                    'message'   => 'Reset password link sent'
                ]) :
                response()->json([
                    'message'   => 'Something went wrong'
                ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function reset_password(Request $request)
    {
        try {
            $request->validate([
                'token'             => 'required',
                'password'          => 'string|min:8|max:40|required',
                'confirm_password'  => 'string|min:8|max:40|required',
                'email'             => 'string|email:rfc,dns|max:40|required'
            ]);

            $status = FacadesPassword::reset(
                $request->only('email', 'password', 'confirm_password', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password'  => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === FacadesPassword::PASSWORD_RESET ?
                response()->json([
                    'message'   => 'Password successfully changed'
                ]) :
                response()->json([
                    'message'   => 'Something went wrong'
                ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
