<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentImmobilier extends Model
{
    protected $table = 'agent_immobiliers';
    public $primaryKey = 'idAgent';
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
