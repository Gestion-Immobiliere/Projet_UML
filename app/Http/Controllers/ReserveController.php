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
            ]);
        } else {
            Paiement::create([
                'idImmobilier' => $request->id_bien,
                'idLocatire' => $user->idUser,
                'datePaiement' => now()
            ]);
        }
    }
}
