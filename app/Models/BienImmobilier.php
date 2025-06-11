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
    public function images()
{
    return $this->hasMany(Image::class, 'bien_id');
}

 //pour recuperer l'agent ou l'admin dans propertiedetails dans le front
    public function agent()
{
    return $this->belongsTo(Utilisateur::class, 'idAgent', 'idUser');
}

public function admin()
{
    return $this->belongsTo(Utilisateur::class, 'idAdmin', 'idUser');
}
}
