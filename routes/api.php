<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\EvaluateController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\VerifyMailController;
use App\Http\Controllers\UtilisateursController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\BienImmobilierController;
use App\Http\Controllers\AuthUtilisateursController;

// Public property routes
Route::get('/biens-publics', [BienImmobilierController::class, 'publicIndex']);
Route::get('/biens-publics/filter', [BienImmobilierController::class, 'filter']); // Moved before {id}
Route::get('/biens-publics/{id}', [BienImmobilierController::class, 'publicShow']); // Only one {id} route

// Messages
Route::middleware('auth:sanctum')->post('message', [ChatController::class, 'store']);
Route::middleware('auth:sanctum')->post('chat', [ChatController::class, 'index']);

// Users
Route::prefix('utilisateurs')->group(function () {
    Route::post('/register', [AuthUtilisateursController::class, 'register']);
    Route::post('/login', [AuthUtilisateursController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthUtilisateursController::class, 'logout']);
        Route::get('/profile', [UtilisateursController::class, 'profile']);
        Route::put('/update-profile', [UtilisateursController::class, 'updateProfile']);
        Route::middleware('checkRole:locataire')->get('agent_immobilier', [UtilisateursController::class, 'getAgent']);
        Route::middleware('checkRole:agent_immobilier')->get('locataire', [UtilisateursController::class, 'getLocataire']);
    });
});

// Admin
Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Admin']);
    });
});

// Agent
Route::middleware(['auth:sanctum', 'checkRole:agent_immobilier'])->group(function () {
    Route::get('/agent/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Agent Immobilier']);
    });
});

// Tenant
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->group(function () {
    Route::get('/locataire/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Locataire']);
    });
});

// Email verification
Route::post('verify-mail', [VerifyMailController::class, 'verify']);

// Password reset
Route::post('/utilisateurs/forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('/utilisateurs/reset-password/{token}', [PasswordResetController::class, 'reset']);

// Reviews
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->post('evaluate', [EvaluateController::class, 'evaluate']);

// Reservations
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->post('reserve', [ReserveController::class, 'reserve']);

// Authenticated property routes
Route::middleware(['auth:sanctum'])->prefix('BienImmobilier')->group(function () {
    Route::get('/get-biens', [BienImmobilierController::class, 'index'])->middleware('checkRole:agent_immobilier,admin');
    Route::get('/{id}', [BienImmobilierController::class, 'show']);
    Route::post('/store', [BienImmobilierController::class, 'store'])->middleware('checkRole:agent_immobilier,admin');
    Route::put('/{id}', [BienImmobilierController::class, 'update'])->middleware('checkRole:agent_immobilier,admin');
    Route::delete('/{id}', [BienImmobilierController::class, 'destroy'])->middleware('checkRole:admin');
});

// Favorites
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->group(function () {
    Route::post('/add-favoris', [FavorisController::class, 'add']);
    Route::get('/get-favoris', [FavorisController::class, 'index']);
    Route::delete('/delete-favoris', [FavorisController::class, 'destroy']);
});

// Contracts
Route::middleware(['auth:sanctum', 'checkRole:agent_immobilier,admin'])->post('/contrats', [ContratController::class, 'store']);
Route::middleware(['auth:sanctum', 'checkRole:locataire'])->put('/contrats/{id}/accepter', [ContratController::class, 'accepter']);
Route::middleware(['auth:sanctum', 'checkRole:admin,agent_immobilier,locataire'])->get('/contrats/{id}/telecharger', [ContratController::class, 'telecharger']);

// Payment
Route::post('/payment', [PaiementController::class, 'processPayment']);