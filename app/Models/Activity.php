<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Define the table name if it's different from the default 'activities'
    protected $table = 'activities';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'task_id',
        'user_id',
        'action',
        'description',
    ];

    /**
     * Get the task that this activity is related to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    /**
     * Get the user who made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
