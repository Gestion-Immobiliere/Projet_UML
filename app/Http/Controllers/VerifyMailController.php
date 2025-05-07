<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMail;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyMailController extends Controller
{
    //
    public function verify (Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = Utilisateur::where('adresseMail', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'L\'adresse mail est invalide'
            ]);
        $user->tokens()->delete();
        $token = $user->createToken('mail-token')->plainTextToken;
        $url = env('FRONTEND_URL') . '/auth/forgot-password?token=' . $token;
        Mail::to($user->adresseMail)->send(new VerifyMail($user->nom, $user->prenom, $url));
        return response()->json([
            'message' => 'Un mail a ete envoye a votre adresse',
            'token' => $token
        ]);
    }
}
