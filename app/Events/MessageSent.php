<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $receiver;
    public $sender;
    public $message;
     
    public function __construct($receiver, $sender, $message)
    {
        //
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
{
    $minId = min($this->sender->idUser, $this->receiver->idUser);
    $maxId = max($this->sender->idUser, $this->receiver->idUser);
    return [
        new PrivateChannel("chat.$minId.$maxId"),
    ];
}

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,  // ID du message
            'message' => $this->message->message,  // Le contenu du message
            'sender' => [
                'id' => $this->sender->idUser,  // ID de l'expéditeur
                'name' => $this->sender->nom,  // Nom de l'expéditeur
            ],
            'receiver' => [
                'id' => $this->receiver->idUser
            ],
            'createdAt' => $this->message->created_at,  // Date et heure de création
        ];
    }

      public function broadcastAs()
    {
        return 'MessageSent'; // Sans namespace
    }
    
    
}
