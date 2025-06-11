<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
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
//fonction pour recuperer les biens sans connexion
    public function publicIndex()
{
    $biens = BienImmobilier::with('images')->get();
    return response()->json($biens);
}

//fonctions pour recuperer les details des biens publiquement
public function publicShow($id)
{
    $bien = BienImmobilier::with('images')->find($id);

    if (!$bien) {
        return response()->json(['message' => 'Bien introuvable'], 404);
    }

    return response()->json($bien);
}


    // Afficher un bien immobilier par ID
  public function show($id)
{
    $bien = BienImmobilier::with(['images', 'agent', 'admin'])->find($id);

    if (!$bien) {
        return response()->json(['message' => 'Bien immobilier non trouvé'], 404);
    }

    // On détermine automatiquement l'auteur du bien (agent ou admin)
    $bien->auteur = $bien->agent ?? $bien->admin;

    return response()->json($bien);
}

public function showPublic($id)
{
    $bien = BienImmobilier::with(['images', 'agent', 'admin'])->find($id);

    if (!$bien) {
        return response()->json(['message' => 'Bien immobilier non trouvé'], 404);
    }

    $data = $bien->toArray();
    $data['auteur'] = $bien->agent ?? $bien->admin;

    return response()->json($data);
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

   // Filtrer les biens immobiliers par type, statut, localisation, nombre de chambres et prix minimum
 public function filter(Request $request)
{
    \Log::info('Filter Request Parameters:', $request->all());
    try {
        $query = BienImmobilier::with('images');

        if ($request->has('type') && $request->input('type')) {
            $query->where('type', $request->input('type'));
            \Log::info('Applied type filter:', ['type' => $request->input('type')]);
        }
        if ($request->has('statut') && $request->input('statut')) {
            $query->where('statut', $request->input('statut'));
            \Log::info('Applied statut filter:', ['statut' => $request->input('statut')]);
        }
        if ($request->has('localisation') && $request->input('localisation')) {
            $query->where('localisation', 'like', '%' . $request->input('localisation') . '%');
            \Log::info('Applied localisation filter:', ['localisation' => $request->input('localisation')]);
        }
        if ($request->has('nombreChambres') && is_numeric($request->input('nombreChambres')) && $request->input('nombreChambres') > 0) {
            $nombreChambres = (int) $request->input('nombreChambres');
            $query->where('nombreChambres', '>=', $nombreChambres);
            \Log::info('Applied nombreChambres filter:', ['nombreChambres' => $nombreChambres]);
        }
        if ($request->has('montant') && is_numeric($request->input('montant')) && $request->input('montant') > 0) {
            $montant = (float) $request->input('montant');
            $query->where('montant', '>=', $montant);
            \Log::info('Applied montant filter:', ['montant' => $montant]);
        }

        $results = $query->get();
        \Log::info('Filter Query Results:', ['count' => $results->count(), 'data' => $results->toArray()]);
        return response()->json($results);
    } catch (\Exception $e) {
        \Log::error('Filter Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['message' => 'Erreur serveur'], 500);
    }
}
}
