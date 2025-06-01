<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    //
    public function reserve(Request $request) {
        $request->validate([
            'id_bien' => 'required',
        ]);
        $user = $request->user();
        if (Paiement::where('idImmobilier', $request->id_bien)->exist()) {
            return response()->json([
                'message' => 'Ce bien est deja reserve'
            ], 400);
        } else {
            return response()->json([
                'message' => 'Nous allons vous rediriger vers la page de paiement'
            ],200);
        }
    }
}
