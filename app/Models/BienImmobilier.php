<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BienImmobilier extends Model
{
    protected $table = 'bien_immobiliers'; // nom exact de la table
    protected $primaryKey = 'idImmobilier'; // ta clé primaire personnalisée
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true; // car tu as created_at et updated_at dans ta table

    protected $fillable = [
        'titre',
        'description',
        'image',
        'localisation',
        'statut',
        'adresse',
        'montant',
        'type',
        'datePublication',
        'surface',
        'nombreChambres',
        'nombreSalleBains',
        'idAgent',
        'idAdmin',
        
    ];
}
