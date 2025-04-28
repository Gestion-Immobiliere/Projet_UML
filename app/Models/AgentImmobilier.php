<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentImmobilier extends Model
{
    //

    public $timestamps = false;
    protected $fillable = [
        "idAgent",
        "adressemail",
        "numTel"
    ];
}
