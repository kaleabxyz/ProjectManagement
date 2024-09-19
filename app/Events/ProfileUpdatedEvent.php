<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

class ProfileUpdatedEvent implements ShouldBroadcast
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('profiles-channel');
    }

    public function broadcastAs()
    {
        return 'profile-updated';
    }
}
