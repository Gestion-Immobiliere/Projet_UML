<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BienImmobilier extends Model
{
    //

    public $timestamps = false;
    protected $fillable = [
        "idImmobilier",
        "adresse",
        "montant",
        "type",
        "datePublication",
        "surface",
        "nombreChambres",
        "nombreSalleBains",
        "idAgent"
    ];
}
