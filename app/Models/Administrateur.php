<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Administrateur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'administrateurs';
    protected $primaryKey = 'idAdmin';
    public $timestamps = false;

    protected $fillable = [
        'adresseMail',
        'motDePasse',
        'numTel',
    ];

    protected $hidden = [
        'motDePasse',
        'remember_token',
    ];

    /**
     * Indique à Laravel d'utiliser la colonne motDePasse comme password.
     */
    public function getAuthPassword()
    {
        return $this->motDePasse;
    }
}
