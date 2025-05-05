<?php

namespace App\Http\Controllers;

use App\Models\BienImmobilier;
use Illuminate\Http\Request;

class BienImmobilierController extends Controller
{
    // Afficher tous les biens (avec pagination)
    public function index(Request $request)
    {
        $biens = BienImmobilier::paginate(10);
        return response()->json($biens);
    }

    // Ajouter un bien immobilier (réservé aux agents/admins)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'localisation' => 'required|string',
            'statut' => 'required|in:disponible,reservé,vendu',
            'adresse' => 'required|string',
            'montant' => 'required|numeric',
            'type' => 'required|string',
            'surface' => 'required|numeric',
            'nombreChambres' => 'required|integer',
            'nombreSalleBains' => 'required|integer',
            'idAgent' => 'required|integer',
            'idAdmin' => 'nullable|integer',
        ]);

        $bien = BienImmobilier::create(array_merge($validated, [
            'datePublication' => now()
        ]));

        return response()->json($bien, 201);
    }

    // Afficher un bien spécifique
    public function show($id)
    {
        $bien = BienImmobilier::find($id);

        if (!$bien) {
            return response()->json(['message' => 'Bien introuvable'], 404);
        }

        return response()->json($bien);
    }

    // Mettre à jour un bien immobilier
    public function update(Request $request, $id)
    {
        $bien = BienImmobilier::find($id);

        if (!$bien) {
            return response()->json(['message' => 'Bien introuvable'], 404);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'nullable|string',
            'localisation' => 'sometimes|string',
            'statut' => 'sometimes|in:disponible,reservé,vendu',
            'adresse' => 'sometimes|string',
            'montant' => 'sometimes|numeric',
            'type' => 'sometimes|string',
            'surface' => 'sometimes|numeric',
            'nombreChambres' => 'sometimes|integer',
            'nombreSalleBains' => 'sometimes|integer',
        ]);

        $bien->update($validated);

        return response()->json($bien);
    }

    // Supprimer un bien immobilier
    public function destroy($id)
    {
        $bien = BienImmobilier::find($id);

        if (!$bien) {
            return response()->json(['message' => 'Bien introuvable'], 404);
        }

        $bien->delete();

        return response()->json(['message' => 'Bien supprimé avec succès']);
    }
}
