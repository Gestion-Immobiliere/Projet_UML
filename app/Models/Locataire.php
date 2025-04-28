<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    protected $table = 'locataires';
    public $primaryKey = 'idLocataire';
    public $timestamps = false;

    protected $fillable = [
        'adresseMail',
        'numTel',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
