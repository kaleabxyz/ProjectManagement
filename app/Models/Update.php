<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
        'reply',
        'has_reply',
        'parent_id',
        'read',
        'board_id',
    ];

    /**
     * Get the task associated with the update.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who made the update.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the board associated with the update.
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Get the replies for this update.
     */
    public function replies()
    {
        return $this->hasMany(Update::class, 'parent_id');
    }

    /**
     * Get the parent update if this is a reply.
     */
    public function parent()
    {
        return $this->belongsTo(Update::class, 'parent_id');
    }
}
