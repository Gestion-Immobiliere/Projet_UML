<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\BienImmobilier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContratController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idLocataire' => 'required|integer|exists:utilisateurs,idUser',
            'idBien' => 'required|integer|exists:bien_immobiliers,idImmobilier',
        ]);

        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'agent_immobilier'])) {
            return response()->json(['message' => 'Accès interdit. Seuls les agents ou les admins peuvent créer un contrat.'], 403);
        }

        // Vérifie si un contrat existe déjà pour ce bien
        if (Contrat::where('idBien', $validated['idBien'])->exists()) {
            return response()->json(['message' => 'Un contrat existe déjà pour ce bien.'], 409);
        }

        // Créer le contrat
        $contrat = Contrat::create([
            'dateCreation' => Carbon::now()->toDateString(),
            'idAgent' => $user->id,
            'idLocataire' => $validated['idLocataire'],
            'idBien' => $validated['idBien'],
            'accepte' => false,
            'cheminPdf' => null
        ]);

        // Charger les relations
        $contrat->load(['locataire', 'bien']);

        try {
            // Générer le PDF
            $pdf = Pdf::loadView('pdf.contrat', ['contrat' => $contrat]);
            $filename = 'contrat_' . $contrat->idContrat . '.pdf';
            $path = 'contrats/' . $filename;

            Storage::disk('public')->put($path, $pdf->output());

            $contrat->cheminPdf = '/storage/' . $path;
            $contrat->save();

        } catch (\Throwable $e) {
            \Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Contrat créé avec succès',
            'contrat' => $contrat
        ], 201);
    }

    public function accepter($id)
    {
        $contrat = Contrat::find($id);

        if (!$contrat) {
            return response()->json(['message' => 'Contrat introuvable'], 404);
        }

        $user = auth()->user();

        if ($contrat->idLocataire !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $contrat->accepte = true;
        $contrat->save();

        return response()->json(['message' => 'Contrat accepté avec succès.']);
    }

    public function telecharger($id)
    {
        $contrat = Contrat::find($id);

        if (!$contrat || !$contrat->cheminPdf) {
            return response()->json(['message' => 'Contrat ou PDF introuvable.'], 404);
        }

        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'agent_immobilier', 'locataire']) ||
            ($user->role === 'locataire' && $contrat->idLocataire !== $user->id) ||
            ($user->role === 'agent_immobilier' && $contrat->idAgent !== $user->id)) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $filePath = str_replace('/storage/', '', $contrat->cheminPdf);
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'Fichier PDF non trouvé.'], 404);
        }

        return response()->download($fullPath);
    }
}
