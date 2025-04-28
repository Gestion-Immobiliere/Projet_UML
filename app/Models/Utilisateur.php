<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'idUser';
    public $timestamps = false;

    protected $fillable = [
        'nom', 'prenom', 'adresseMail', 'motDePasse', 'numTel', 'role',
    ];

    protected $hidden = [
        'motDePasse', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->motDePasse;
    }
}
