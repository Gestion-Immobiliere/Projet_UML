<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Administrateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Inscription d'un administrateur
    public function register(Request $request)
    {
        $data = $request->validate([
            'adresseMail'   => 'required|email|unique:administrateurs,adresseMail',
            'numTel'        => 'required|string',
            'motDePasse'    => 'required|string|min:8|confirmed',
        ]);

        $data['motDePasse'] = Hash::make($data['motDePasse']);

        Administrateur::create($data);

        return response()->json([
            'message' => 'Administrateur créé avec succès'
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'adresseMail' => 'required|email',
            'motDePasse'  => 'required|string',
        ]);

        $admin = Administrateur::where('adresseMail', $credentials['adresseMail'])->first();

        if (! $admin || ! Hash::check($credentials['motDePasse'], $admin->motDePasse)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        // on cible explicitement le guard "admin"
        $request->user('admin')->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    // Profil courant
    public function me(Request $request)
    {
        $admin = $request->user('admin');

        return response()->json([
            'admin' => [
                'idAdmin'    => $admin->idAdmin,
                'adresseMail'=> $admin->adresseMail,
                'numTel'     => $admin->numTel,
            ],
        ]);
    }
}
