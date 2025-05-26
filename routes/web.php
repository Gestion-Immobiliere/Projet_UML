<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/paiement', function () {
    return view('paiement');
});


require __DIR__.'/auth.php';
