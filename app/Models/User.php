<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Profil Agent
    public function agentProfile()
    {
        return $this->hasOne(AgentImmobilier::class, 'user_id');
    }

    // Profil Locataire
    public function locataireProfile()
    {
        return $this->hasOne(Locataire::class, 'user_id');
    }
}
