<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //

    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required',  
        'from'    => 'required',  
        'message' => 'required',
    ]);

    $message = ChatMessage::create([
        'user_id' => $request->user_id, 
        'from_id' => $request->from,    
        'message' => $request->message,
    ]);
    
    $receiver = User::findOrFail($request->user_id);
    $sender = User::findOrFail($request->from);

    broadcast(new MessageSent($receiver, $sender, $request->message));

    return response()->json([
        'message' => $message
    ]);
}

public function index()
{
    $userId = 1;
    
    $messages = ChatMessage::where('user_id', $userId)
                           ->orWhere('from_id', $userId)
                           ->get();

    return view('chat', compact('messages'));
}

}
