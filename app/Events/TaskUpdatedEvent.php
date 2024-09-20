<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TaskUpdatedEvent implements ShouldBroadcast
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

