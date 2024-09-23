<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WorkspaceBoard extends Pivot
{
    protected $table = 'workspace_board';
    
    protected $fillable = ['workspace_id', 'board_id'];
    
    // Define the relationship with the Workspace model
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    // Define the relationship with the Board model
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
