<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    protected $table = 'contrats';
    protected $primaryKey = 'idContrat';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'dateCreation',
        'idAgent',
        'idLocataire',
        'idBien',
        'accepte',
        'cheminPdf',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Utilisateur::class, 'idAgent');
    }

    public function locataire()
    {
        return $this->belongsTo(Utilisateur::class, 'idLocataire');
    }

    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'idBien');
    }
}
