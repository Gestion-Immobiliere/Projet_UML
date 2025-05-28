<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['bien_id', 'chemin'];

    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_id');
    }
}
