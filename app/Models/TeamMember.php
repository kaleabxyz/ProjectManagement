<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'member',
        'team',
        'role',
    ];

    /**
     * Get the user associated with the team member.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'member');
    }

    /**
     * Get the board associated with the team member.
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team');
    }
}
