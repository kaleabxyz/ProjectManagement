<?php

namespace App\Listeners;

use App\Events\InviteSent;
use App\Models\Notification;

class SendInvitationNotification
{
    public function handle(InviteSent $event)
    {
        // Insert a new notification for the invited user
        Notification::create([
            'user_id' => $event->user->id,
            'icon' => 'fa-envelope', // Example icon
            'body' => "You have been invited by {$event->inviter->name}",
            'action_url' => '/invitations', // URL for action
            'created_at' => now(),
            'read' => false,
        ]);
    }
}

