<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    // Envoi du lien de réinitialisation
    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => __($status)
            ]);
        }

        return response()->json([
            'message' => 'Lien de réinitialisation envoyé'
        ]);
    }

    // Réinitialisation du mot de passe
    public function reset(Request $request)
    {
        $data = $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'token'                 => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'token' => [__($status)]
            ]);
        }

        return response()->json([
            'message' => 'Mot de passe réinitialisé'
        ]);
    }
}
