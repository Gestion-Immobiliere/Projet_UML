<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUtilisateursController extends Controller
{
    // Enregistrement (inscription)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'adresseMail' => 'required|email|unique:utilisateurs,adresseMail',
            'motDePasse' => 'required|string|min:6|confirmed', // motDePasse_confirmation requis dans la requête
            'numTel' => 'nullable|string',
            'role' => 'required|in:locataire,agent_immobilier,admin'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'adresseMail' => $request->adresseMail,
            'motDePasse' => bcrypt($request->motDePasse),
            'numTel' => $request->numTel,
            'role' => $request->role,
        ]);

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Connexion (login)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'adresseMail' => 'required|email',
            'motDePasse' => 'required'
        ]);

        $utilisateur = Utilisateur::where('adresseMail', $request->adresseMail)->first();

        if (!$utilisateur || !Hash::check($request->motDePasse, $utilisateur->motDePasse)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Déconnexion (logout)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
