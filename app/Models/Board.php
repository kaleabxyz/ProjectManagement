<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'folder_id',
        'team',
        'owner',
        'created_by',
        'is_favorite',
        'is_archived',
        'board_name',
        'is_trashed',
        'trashed_at',
        'trashed_by',
    ];

    public function owner()
{
    return $this->belongsTo(User::class, 'owner');
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function trashedBy()
{
    return $this->belongsTo(User::class, 'trashed_by');
}
    /**
     * Get the workspace that owns the board.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    /**
     * Get the folder that owns the board.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class,'folder_id');
    }

    /**
     * Get the team that owns the board.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user who trashed the board.
     */
   

}
