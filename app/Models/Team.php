<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'owner_id',
    ];

    /**
     * Get the user who owns the team.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

   /**
     * Get the board associated with the team.
     */
    public function board()
    {
        return $this->belongsTo(Board::class, 'board');
    }
}
