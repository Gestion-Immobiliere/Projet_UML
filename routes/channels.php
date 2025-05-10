<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

// Broadcast::channel('chat.{idUser}', function ($utilisateur, $idUser) {
//     return (int) $utilisateur->idUser === (int) $idUser;
// });

Broadcast::channel('chat.{user1}.{user2}', function ($utilisateur, $user1, $user2) {
    return (int)$utilisateur->idUser === (int)$user1 || (int)$utilisateur->idUser === (int)$user2;
});
