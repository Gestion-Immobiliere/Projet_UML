<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthUtilisateursController;
use App\Http\Controllers\UtilisateursController;
use App\Http\Controllers\PasswordResetController;

Route::middleware('auth:sanctum')->post('message', [ChatController::class, 'store']);

// Authentification pour Utilisateurs
Route::prefix('utilisateurs')->group(function () {
    Route::post('/register', [AuthUtilisateursController::class, 'register']);
    Route::post('/login', [AuthUtilisateursController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthUtilisateursController::class, 'logout']);
        Route::get('/profile', [UtilisateursController::class, 'profile']);
        Route::put('/profile', [UtilisateursController::class, 'updateProfile']);
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

//Routes de réinitialisation du mot de passe 
Route::post('/utilisateurs/forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('/utilisateurs/reset-password', [PasswordResetController::class, 'reset']);


Route::post('login', [UserController::class, 'login']);