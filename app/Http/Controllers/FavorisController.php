<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use Illuminate\Http\Request;

class FavorisController extends Controller
{
    //

    public function add(Request $request) {
        $request->validate([
            'idImmobilier' => 'required'
        ]);
        $user = $request->user();
        $favori = Favori::create([
            'idUser' => $user->idUser,
            'idImmobilier' => $request->idImmobilier,
        ]);
        return response()->json([
            'message' => 'Favori ajouté avec succès',
            'favori' => $favori
        ]);
    }

    public function index(Request $request) {
        $user = $request->user();
        $favoris = Favori::where('idUser', $user->idUser)->get();

        return response()->json($favoris);
    }

    public function destroy(Request $request) {
        $request->validate([
            'idFavori' => 'required'
        ]);
        
        $favori = Favori::find($request->idFavori);

        if (!$favori) {
            return response()->json([
                'message' => 'Favori non trouvé'
            ], 404);
        }

        $favori->delete();

        return response()->json([
            'message' => 'Favori supprimé avec succès'
        ]);
    }
}
