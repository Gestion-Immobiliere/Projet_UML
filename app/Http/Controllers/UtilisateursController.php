<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateursController extends Controller
{
    // Voir son profil
    public function profile(Request $request)
    {
        $user = $request->user();
        $user->load('biens');
        $properties = $user->biens->count();
        return response()->json([
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->adresseMail,
            'telephone' => $user->numTel,
            'propriete' => $properties
        ]);
    }

    // Mettre à jour son profil
    public function updateProfile(Request $request){
        $user = $request->user();
        $request->validate([
            'email' => 'sometimes|string|max:50',
            'phone' => 'sometimes|string|max:50',
        ]);
        $user->update([
            'adresseMail' => $request->email,
            'numTel' => $request->phone
        ]);
        return response()->json( [
            'message' => 'Profil mis à jour', 
            'email' => $user->adresseMail,
            'telephone' => $user->numTel
        ]);
    }

    public function getAgent() {
        $agents = Utilisateur::where('role', 'agent_immobilier')->get();
        return response()->json($agents);
    }

     public function getLocataire() {
        $locataires = Utilisateur::where('role', 'locataire')->get();
        return response()->json($locataires);
    }
}
