<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    //
    protected $table = 'message';

    protected $fillable = [
        'user_id', 
        'from_id', 
        'message', 
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
