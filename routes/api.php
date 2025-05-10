<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\EvaluateController;
use App\Http\Controllers\VerifyMailController;
use App\Http\Controllers\UtilisateursController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\BienImmobilierController;
use App\Http\Controllers\AuthUtilisateursController;
use App\Http\Controllers\ContratController;

//Route pour les messages
Route::middleware('auth:sanctum')->post('message', [ChatController::class, 'store']);
Route::middleware('auth:sanctum')->post('chat', [ChatController::class, 'index']);

// Route pour les Utilisateurs
Route::prefix('utilisateurs')->group(function () {
    Route::post('/register', [AuthUtilisateursController::class, 'register']);
    Route::post('/login', [AuthUtilisateursController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthUtilisateursController::class, 'logout']);
        Route::get('/profile', [UtilisateursController::class, 'profile']);
        Route::put('/profile', [UtilisateursController::class, 'updateProfile']);
        Route::middleware('checkRole:locataire')->get('agent_immobilier', [UtilisateursController::class, 'getAgent']);
        Route::middleware('checkRole:agent_immobilier')->get('locataire', [UtilisateursController::class, 'getLocataire']);
    });
});

// Routes réservées aux Admins uniquement
Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Admin']);
    });
});

// Routes réservées aux Agents immobiliers uniquement
Route::middleware(['auth:sanctum', 'checkRole:agent_immobilier'])->group(function () {
    Route::get('/agent/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Agent Immobilier']);
    });
});

// Routes réservées aux Locataires uniquement
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->group(function () {
    Route::get('/locataire/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Locataire']);
    });
});

//Route pour verifier l'adresse mail
Route::post('verify-mail', [VerifyMailController::class, 'verify']);

//Routes de réinitialisation du mot de passe 
Route::post('/utilisateurs/forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('/utilisateurs/reset-password/{token}', [PasswordResetController::class, 'reset']);

//Route pour la gestion des avis
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->post('evaluate', [EvaluateController::class, 'evaluate']);

//Route pour la reservation
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->post('reserve', [ReserveController::class, 'reserve']);

//Routes pour la gestion des biens immobiliers 
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('BienImmobilier')->group(function () {
        Route::get('/', [BienImmobilierController::class, 'index']);
        Route::get('/{id}', [BienImmobilierController::class, 'show']);
        Route::post('/', [BienImmobilierController::class, 'store'])->middleware('checkRole:agent_immobilier,admin');
        Route::put('/{id}', [BienImmobilierController::class, 'update'])->middleware('checkRole:agent_immobilier,admin');
        Route::delete('/{id}', [BienImmobilierController::class, 'destroy'])->middleware('checkRole:admin');
        Route::get('/filter', [BienImmobilierController::class, 'filter']);
    });
});

//Route pour la gestion des favoris
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->post('/add-favoris', [FavorisController::class, 'add']);
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->get('/get-favoris', [FavorisController::class, 'index']); 
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->delete('/delete-favoris', [FavorisController::class, 'destroy']); 

//Routes pour les contrats
Route::middleware(['auth:sanctum', 'checkRole:agent_immobilier,admin'])->post('/contrats', [ContratController::class, 'store']);
//Validation du contrat (case à cocher)
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->put('/contrats/{id}/accepter', [ContratController::class, 'accepter']);
//Route pour telecharger le pdf
Route::middleware(['auth:sanctum', 'checkRole:admin,agent_immobilier,locataire'])
    ->get('/contrats/{id}/telecharger', [ContratController::class, 'telecharger']);
