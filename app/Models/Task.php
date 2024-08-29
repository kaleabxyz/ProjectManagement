<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_name',
        
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
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function trashedBy()
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }
}


