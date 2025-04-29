<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function forgot(Request $request)
    {
        $request->validate([
            'adresseMail' => 'required|email|exists:utilisateurs,adresseMail',
        ]);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->adresseMail],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Lien de réinitialisation (simulé ici)
        $resetLink = url("/reset-password?token=$token&email=" . urlencode($request->adresseMail));

        // Envoi de l'e-mail (simple pour le test)
        Mail::raw("Cliquez ici pour réinitialiser votre mot de passe : $resetLink", function ($message) use ($request) {
            $message->to($request->adresseMail);
            $message->subject('Réinitialisation de mot de passe');
        });

        return response()->json(['message' => 'Email envoyé avec le lien de réinitialisation.']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'adresseMail' => 'required|email|exists:utilisateurs,adresseMail',
            'token' => 'required',
            'motDePasse' => 'required|confirmed|min:6',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->adresseMail)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json(['message' => 'Token invalide ou expiré.'], 400);
        }

        $user = Utilisateur::where('adresseMail', $request->adresseMail)->first();
        $user->update([
            'motDePasse' => bcrypt($request->motDePasse)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->adresseMail)->delete();

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
    }
}
