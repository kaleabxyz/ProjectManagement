<?php

// app/Models/Attachment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'task_id',
        'user_id',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'caption', // Include the caption
    ];

    public function board()
    {
        return $this->belongsTo(Board::class,'board_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id'); // To get the user who uploaded the file
    }
}

