<?php

namespace App\Http\Controllers;

use App\Models\BienImmobilier;
use App\Models\Image;
use Illuminate\Http\Request;

class BienImmobilierController extends Controller
{
    // Lister tous les biens immobiliers
    public function index(Request $request){
        $user = $request->user();
        $biens = BienImmobilier::with('images')->where(function ($query) use ($user) {
            $query->where('idAgent', $user->idUser)
                  ->orWhere('idAdmin', $user->idUser);
        })->get();
        return response()->json($biens);
    } 

    // Afficher un bien immobilier par ID
    public function show($id)
    {
        $bien = BienImmobilier::with('images')->find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien immobilier non trouvé'], 404);
        }
        return response()->json($bien);
    }

    // Ajouter un bien immobilier (Agent/Admin uniquement)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'localisation' => 'required|string',
            'statut' => 'required|string',
            'adresse' => 'required|string',
            'montant' => 'required|numeric',
            'type' => 'required|string',
            'surface' => 'required|numeric',
            'nombreChambres' => 'required|integer',
            'nombreSalleBains' => 'required|integer',
        ]);

        // $user = auth()->user();
        $user = $request->user();

        if ($user->role === 'admin') {
            $validated['idAdmin'] = $user->idUser;
        } elseif ($user->role === 'agent_immobilier') {
            $validated['idAgent'] = $user->idUser;
        }

        if (!isset($validated['idAdmin']) && !isset($validated['idAgent'])) {
            return response()->json(['message' => 'Seuls les agents ou les admins peuvent créer un bien.'], 403);
        }
        $validated['datePublication'] = now();
        $bien = BienImmobilier::create($validated);

        // Gérer les images multiples
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $bien->images()->create(['chemin' => '/storage/' . $path]);
            }
        }

        return response()->json($bien->load('images'), 201);
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
            'localisation' => 'sometimes|string',
            'statut' => 'sometimes|string',
            'adresse' => 'sometimes|string',
            'montant' => 'sometimes|numeric',
            'type' => 'sometimes|string',
            'datePublication' => 'sometimes|date',
            'surface' => 'sometimes|numeric',
            'nombreChambres' => 'sometimes|integer',
            'nombreSalleBains' => 'sometimes|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $bien->update($validated);

        // Ajouter de nouvelles images si présentes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $bien->images()->create(['chemin' => '/storage/' . $path]);
            }
        }

        return response()->json($bien->load('images'));
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
        $query = BienImmobilier::with('images');

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
