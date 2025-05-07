<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;

class EvaluateController extends Controller
{
    //
    public function evaluate(Request $request) {
        $request->validate([
            'id_bien' => 'required',
            'note' => 'required',
            'commentaire' => 'required'
        ]);
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Connectez vous'
            ]);
        }
        $avis = Avis::create([
            'idLocataire' => $user->idUser,
            'idImmobilier' => $request->id_bien,
            'note' => $request->note,
            'commentaire' => $request->commentaire
        ]);
        return response()->json([
            'message' => $avis
        ]);
    }
}
