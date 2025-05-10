<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    //
    protected $fillable = [
        'idFavori',
        'idUser',
        'idImmobilier'
    ];
}
