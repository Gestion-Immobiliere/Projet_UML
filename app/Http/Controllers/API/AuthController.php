<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentImmobilier;
use App\Models\Locataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name'            => 'required|string|max:50',
            'last_name'             => 'required|string|max:50',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'nullable|string|max:20',
            'role'                  => ['required', Rule::in(['agent','locataire'])],
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if ($data['role'] === 'agent') {
            AgentImmobilier::create([
                'adresseMail' => $user->email,
                'numTel'      => $user->phone,
                'user_id'     => $user->id,
            ]);
        } else {
            Locataire::create([
                'adresseMail' => $user->email,
                'numTel'      => $user->phone,
                'user_id'     => $user->id,
            ]);
        }

        return response()->json([
            'message' => 'Inscription réussie'
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    // Profil courant
    public function me(Request $request)
    {
        $user = $request->user();
        $profile = $user->role === 'agent'
            ? $user->agentProfile
            : $user->locataireProfile;

        return response()->json([
            'user'    => $user->makeHidden(['password','remember_token']),
            'profile' => $profile,
        ]);
    }
}
