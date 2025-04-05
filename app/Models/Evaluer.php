<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluer extends Model
{
    //

    protected $fillable = [
        "idLocataire",
        "idAnnonce",
        "note"
    ];
}
