<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter',
        'invited',

        'team',
        'role',
        'board',
        'status',
        'email',
        'token',
    ];

    /**
     * Get the user associated with the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class,'inviter');
    }
    public function invited()
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
    public function board()
    {
        return $this->belongsTo(Board::class,'board');
    }
    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
}
