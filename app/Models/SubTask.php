<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'selectStatus',
        'selectOwner',
        'selectPriority',
        'status',
        'priority',
        'due_date',
        'board_id',
        'assigned_to',
        'budget',
        'notes',
        'is_trashed',
        'trashed_at',
        'trashed_by',
        'column',
        'task_id',
    ];

    /**
     * Get the task that owns the sub-task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    /**
     * Get the user who trashed the sub-task.
     */
    public function trashedBy()
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }

    /**
     * Get the user to whom the sub-task is assigned.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
