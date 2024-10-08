<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'board_id',
    ];

    /**
     * Get the task associated with the update.
     */
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    /**
     * Get the user who made the update.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    

    /**
     * Get the board associated with the update.
     */
    public function board()
    {
        return $this->belongsTo(Board::class,'board_id');
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
    public function readers()
    {
        return $this->belongsToMany(User::class, 'user_update_read_status')
                    ->withPivot('is_read')
                    ->withTimestamps();
    }
}
