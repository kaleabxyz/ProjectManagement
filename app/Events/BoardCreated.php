<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BoardCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $board;
    public $manager;

    public function __construct($board, $manager)
    {
        $this->board = $board;
        $this->manager = $manager;
    }

    public function broadcastOn()
    {
        return new Channel('manager.' . $this->manager->id);
    }

    public function broadcastWith()
    {
        return [
            'board' => $this->board,
        ];
    }
}
