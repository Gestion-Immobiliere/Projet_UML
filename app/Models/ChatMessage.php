<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    //
    protected $fillable = [
        'user_id', 
        'from_id', 
        'message',
        'conversation_id' 
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
