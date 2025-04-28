<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\AdminAuthController;

Route::post('register',        [AuthController::class, 'register']);
Route::post('login',           [AuthController::class, 'login']);
Route::post('forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('reset-password',  [PasswordResetController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);
});

// Pour générer un token JSON (API-only) et satisfaire le route() du mailer
Route::get('password/reset/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');

Route::prefix('admin')->group(function () {
    // Enregistrement / connexion
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login',    [AdminAuthController::class, 'login']);

    // Routes protégées (guard "admin")
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me',      [AdminAuthController::class, 'me']);
    });
});
