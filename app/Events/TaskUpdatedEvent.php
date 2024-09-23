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
    public $updateId;
    public $eventType; // Will differentiate between task and update

    public function __construct($boardId, $taskId = null, $updateId = null)
    {
        $this->boardId = $boardId;
        $this->taskId = $taskId;
        $this->updateId = $updateId;
        $this->eventType = $taskId ? 'task_updated' : 'new_update';
    }

    public function broadcastOn()
    {
        return new Channel('board.' . $this->boardId);
    }

    public function broadcastWith()
    {
        return [
            'event_type' => $this->eventType,
            'task_id' => $this->taskId,
            'update_id' => $this->updateId,
        ];
    }
}
