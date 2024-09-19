<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewInviteEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  
    public $notification;
    public $invitation;
    public $inviter;

    public function __construct($notification,$invitation,$inviter)
    {
        
        $this->notification = $notification;
        $this->invitation = $invitation;
        $this->inviter = $inviter;
    }

    public function broadcastOn()
    {
       
        return new Channel('invites-channel');
    }

    public function broadcastAs()
    {
        return 'new-invite';
    }
    public function broadcastWith()
    {
        return [
            'notification' => $this->notification->toArray(),
            'invitation' => $this->invitation->toArray(),
            'inviter' => $this->inviter->toArray(),
        ];
    }
}

