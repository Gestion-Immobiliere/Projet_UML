<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->post('message', [ChatController::class, 'store']);



Route::post('login', [UserController::class, 'login']);