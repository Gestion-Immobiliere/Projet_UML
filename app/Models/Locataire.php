<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    //

    protected $fillable = [
        "idLocataire",
        "adresseMail",
        "numTel"
    ];
}
