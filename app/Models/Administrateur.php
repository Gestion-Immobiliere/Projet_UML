<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'idAdmin',
        'adresseMail',
        'numTel',
        'motDePasse',
    ];
    
}
