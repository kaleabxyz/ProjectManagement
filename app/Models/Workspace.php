<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_name',
        'is_favorite',
        'is_archived',
        'is_trashed',
        'trashed_at',
        'trashed_by',
        'created_by',
    ];

    /**
     * Get the user who trashed the workspace.
     */
    public function trashedBy()
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    

    /**
     * Get the boards associated with the workspace.
     */
    public function boards()
    {
        return $this->belongsToMany(Board::class, 'workspace_board', 'workspace_id', 'board_id')
                    ->withPivot('created_at', 'updated_at','id');
    }
    
    public function folders()
    {
        return $this->hasMany(Folder::class,'workspace_id');
    }
}
