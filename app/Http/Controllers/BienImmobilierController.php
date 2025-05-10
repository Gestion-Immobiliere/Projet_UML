<?php

namespace App\Http\Controllers;

use App\Models\BienImmobilier;
use Illuminate\Http\Request;

class BienImmobilierController extends Controller
{
    // Lister tous les biens immobiliers
    public function index()
    {
        return response()->json(BienImmobilier::all());
    }

    // Afficher un bien immobilier par ID
    public function show($id)
    {
        $bien = BienImmobilier::find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien immobilier non trouvé'], 404);
        }
        return response()->json($bien);
    }

    // Ajouter un bien immobilier (Agent/Admin uniquement)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'localisation' => 'required|string',
            'statut' => 'required|string',
            'address' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'datePublication' => 'required|date',
            'area' => 'required|numeric',
            'bedRooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'idAgent' => 'required|integer',
            'idAdmin' => 'required|integer',
            'idContrat' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = "/storage/" . $path;
        }

        $bien = BienImmobilier::create($validated);
        return response()->json($bien, 201);
    }

    // Modifier un bien immobilier (Agent/Admin uniquement)
    public function update(Request $request, $id)
    {
        $bien = BienImmobilier::find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien immobilier non trouvé'], 404);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'localisation' => 'sometimes|string',
            'statut' => 'sometimes|string',
            'adresse' => 'sometimes|string',
            'montant' => 'sometimes|numeric',
            'type' => 'sometimes|string',
            'datePublication' => 'sometimes|date',
            'surface' => 'sometimes|numeric',
            'nombreChambres' => 'sometimes|integer',
            'nombreSalleBains' => 'sometimes|integer',
            'idAgent' => 'sometimes|integer',
            'idAdmin' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = "/storage/" . $path;
        }

        $bien->update($validated);
        return response()->json($bien);
    }

    // Supprimer un bien immobilier (Admin uniquement)
    public function destroy($id)
    {
        $bien = BienImmobilier::find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien immobilier non trouvé'], 404);
        }

        $bien->delete();
        return response()->json(['message' => 'Bien immobilier supprimé avec succès']);
    }

    // Filtrer les biens immobiliers par type, statut ou localisation
    public function filter(Request $request)
    {
        $query = BienImmobilier::query();

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->has('localisation')) {
            $query->where('localisation', 'like', '%' . $request->input('localisation') . '%');
        }

        return response()->json($query->get());
    }
}
