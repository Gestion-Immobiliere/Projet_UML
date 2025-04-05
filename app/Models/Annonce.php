<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    //

    public $timestamps = false;
    protected $fillable = [
        "idAnnonce",
        "libelle",
        "idAgent"
    ];
}
