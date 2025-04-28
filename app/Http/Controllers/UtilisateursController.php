<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilisateursController extends Controller
{
    // Voir son profil
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // Mettre à jour son profil
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nom' => 'sometimes|string|max:50',
            'prenom' => 'sometimes|string|max:50',
            'numTel' => 'sometimes|string',
        ]);

        $user->update($data);

        return response()->json(['message' => 'Profil mis à jour', 'user' => $user]);
    }
}
