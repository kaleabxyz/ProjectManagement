<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InviteSent
{
    use Dispatchable, SerializesModels;

    public $user;
    public $inviter;

    public function __construct($user, $inviter)
    {
        $this->user = $user;
        $this->inviter = $inviter;
    }
}

