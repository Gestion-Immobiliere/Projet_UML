<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    //

    public $timestamps = false;
    protected $fillable = [
        "idContrat",
        "dateCreation",
        "dateDebut",
        "dateFin",
        "dateMaj",
        "idLocataire",
        "idAgent"
    ];
}
