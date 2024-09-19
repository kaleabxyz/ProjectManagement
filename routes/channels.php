<?php

use Illuminate\Support\Facades\Broadcast;

// Define your broadcast channel
Broadcast::channel('invites-channel', function ($user) {
    return true; // Authorize all users to join this channel
});


