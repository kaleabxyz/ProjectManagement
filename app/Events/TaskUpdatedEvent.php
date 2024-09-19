<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Task;

class TaskUpdatedEvent implements ShouldBroadcast
{
    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function broadcastOn()
    {
        return new Channel('tasks-channel');
    }

    public function broadcastAs()
    {
        return 'task-updated';
    }
}
