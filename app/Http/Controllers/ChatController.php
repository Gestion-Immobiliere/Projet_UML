<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\Utilisateur;
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
        'conversation_id' => 'required'
    ]);
    $user = $request->user();
    $message = ChatMessage::create([
        'user_id' => $request->user_id, 
        'from_id' => $user->idUser,    
        'message' => $request->message,
        'conversation_id' => $request->conversation_id
    ]);
    $receiver = Utilisateur::findOrFail($request->user_id);
    $sender = Utilisateur::findOrFail($user->idUser);

    broadcast(new MessageSent($receiver, $sender, $message))->toOthers();

    return response()->json([
        'message' => $message,
        'id' => $message->id,
        'sender' => $user->idUser
    ]);
}

public function index(Request $request)
{
    $request->validate([
        'conversation_id' => 'required'
    ]);
    $conversationId = $request->conversation_id;
    $user = $request->user();
    // $messages = ChatMessage::where(function($query) use ($user, $conversationId) {
    //     $query->where('user_id', $user->idUser)
    //           ->where('conversation_id', $conversationId);
    // })
    // ->orWhere(function($query) use ($user, $conversationId) {
    //     $query->where('from_id', $user->idUser)
    //           ->where('conversation_id', $conversationId);
    // })
    // ->get();
    $messages = ChatMessage::where(function ($query) use ($user, $conversationId) {
        $query->where('from_id', $user->idUser) // Condition 1
          ->where('conversation_id', $conversationId);
}) 
->orWhere(function ($query) use ($user, $conversationId) {
    $query->where('conversation_id', $user->idUser) 
          ->where('from_id', $conversationId);
})
->get();
     $messages = $messages->map(function ($message) {
        return [
            'id' => $message->id,
            'message' => $message->message,
            'from_id' => $message->from_id,
            'createdAt' => $message->created_at,  // Retourne la date au format ISO
            'updatedAt' => $message->updated_at->toISOString(),  // Si tu veux aussi retourner la date de mise à jour
        ];
    });
    return response()->json( $messages, 200);
}
}
