<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'status',
        'email',
        'token',
    ];

    /**
     * Get the user associated with the invitation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team associated with the invitation.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
