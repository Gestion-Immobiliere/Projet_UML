<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BienImmobilier extends Model
{
    //
    protected $table = 'bien_immobiliers';
    public $timestamps = false;
    protected $fillable = [
        "idImmobilier",
        "titre",
        "description",
        "image",
        "localisation",
        "statut",
        "adresse",
        "montant",
        "type",
        "datePublication",
        "surface",
        "nombreChambres",
        "nombreSalleBains",
        "idAgent",
        "idAdmin",
        "idContrat"
    ];
}
