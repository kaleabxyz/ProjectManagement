<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_name',
        'team_id',
        'workspace_id',
        'is_favorite',
        'is_archived',
        'is_trashed',
        'trashed_at',
        'trashed_by',
    ];

    /**
     * Get the team associated with the folder.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the workspace associated with the folder.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the user who trashed the folder.
     */
    public function trashedBy()
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }
}
