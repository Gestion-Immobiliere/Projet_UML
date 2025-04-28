<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthUtilisateursController;
use App\Http\Controllers\UtilisateursController;

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

Route::post('login', [UserController::class, 'login']);