<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class PasswordResetController extends Controller
{
    public function reset(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|confirmed:newpassword|min:6',
        ]);
        $personalToken = PersonalAccessToken::findToken($token);
        if (!$personalToken) {
            return response()->json(['message' => 'Token invalide.'], 400);
        }
        $user = $personalToken->tokenable;
        if ($user) {
            $user->update([
                'motDePasse' => bcrypt($request->password)
            ]);
            $personalToken->delete();
            return response()->json([
                'message' => 'Mot de passe réinitialisé avec succès.',
                'nom' => $user->nom
            ]);
        }
        return response()->json([
            'message' => 'Utilisateur non autorisé à réinitialiser ce mot de passe.'
        ]);  
    }
}
