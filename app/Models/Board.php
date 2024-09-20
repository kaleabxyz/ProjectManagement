<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
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
    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_board', 'board_id', 'workspace_id')->withPivot('created_at', 'workspace_id', 'board_id');;
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
        return $this->belongsTo(Team::class,'team');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function discussions2()
    {
        return $this->hasMany(Update::class);
    }
    public function discussions()
    {
        return $this->hasMany(Update::class)
                    ->with(['readers' => function ($query) {
                        $query->where('user_id', Auth::id()); // Filter by current user
                    },'board:id,board_name']);
    }
    /**
     * Get the user who trashed the board.
     */
   

}
