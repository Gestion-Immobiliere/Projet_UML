<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Paiement;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    //
    public function processPayment(Request $request) {
        // Valider la requête
        $request->validate([
            'amount' => 'required',  
            'idLocataire' => 'required', 
            'idImmobilier' => 'required', 
            'dureeValidite' => 'required', 
        ]);

        // Configuration de Stripe avec la clé secrète
        Stripe::setApiKey(env('STRIPE_SECRET'));

            // Créer un PaymentIntent avec le montant en cents
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100,  // Stripe attend le montant en centimes
            'currency' => 'eur',  // Devise, ajuste si nécessaire
        ]);

            // Insertion dans la table 'paiements' après le succès du PaymentIntent
        Paiement::create([
            'datePaiement' => now(),
            'montant' => $request->amount,
            'dureeValidite' => $request->dureeValidite,
            'idLocataire' => $request->idLocataire,
            'idImmobilier' => $request->idImmobilier,
        ]);

            // Retourner le client_secret pour finaliser le paiement côté client
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,  // Donnée retournée au client
        ]);
    }
}
