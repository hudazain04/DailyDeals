<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use function Symfony\Component\String\u;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($user,$message)
    {
        $this->user=$user;
        $this->message=$message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */


    public function broadcastOn(): array
    {
        return [
            'chat'
        ];
    }
//    public function broadcastAs()
//    {
//        return [
//            'message'=>MessageResource::make($this->message),
//        ];
//    }

    public function broadcastAs()
    {
        return 'my-event';
    }

    public function broadcastWith(){
        return [
            'message' => $this->message
        ];
    }
}
