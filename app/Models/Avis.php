<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    //
    protected $table = 'avis';

    protected $fillable = [
        "idAvis",
        "idLocataire",
        "idImmobilier",
        "commentaire", 
        "note"
    ];
}
