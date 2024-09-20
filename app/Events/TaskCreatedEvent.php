<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedEvent
{
    use InteractsWithSockets, SerializesModels;

    public $boardId;
    public $taskId;

    public function __construct($boardId, $taskId)
    {
        $this->boardId = $boardId;
        $this->taskId = $taskId;
    }

    public function broadcastOn()
    {
        return new Channel('board.' . $this->boardId);
    }

    public function broadcastWith()
    {
        return [
            'task_id' => $this->taskId,
        ];
    }
}
