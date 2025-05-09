<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contrat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RegenerateContractPdfs extends Command
{
    protected $signature = 'contrat:regen-pdf';
    protected $description = 'Régénère les fichiers PDF pour les contrats sans PDF';

    public function handle()
    {
        $contrats = Contrat::with(['locataire', 'bien'])
            ->whereNull('cheminPdf')
            ->get();

        if ($contrats->isEmpty()) {
            $this->info("Tous les contrats ont déjà un PDF.");
            return;
        }

        foreach ($contrats as $contrat) {
            $pdf = Pdf::loadView('pdf.contrat', ['contrat' => $contrat]);

            $filename = 'contrat_' . $contrat->idContrat . '.pdf';
            $path = 'contrats/' . $filename;

            Storage::disk('public')->put($path, $pdf->output());

            $contrat->cheminPdf = '/storage/' . $path;
            $contrat->save();

            $this->info("PDF généré pour contrat #{$contrat->idContrat}");
        }

        $this->info("✅ Génération terminée !");
    }
}
