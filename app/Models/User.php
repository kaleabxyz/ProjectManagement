<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'profile_picture_url',
        'email',
        'password',
        'location',
        'birthday',
        'skype',
        'job_title',
        'phone',
        'mobile_phone',
        'working_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     *@var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'member');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'member', 'team');
    }
    public function boardsOwned()
    {
        return $this->hasMany(Board::class, 'owner');
    }

    public function boardsCreated()
    {
        return $this->hasMany(Board::class, 'created_by');
    }
    public function boardsTrashed()
    {
        return $this->hasMany(Board::class, 'trashed_by');
    }
    public function workspaces()
    {
        return $this->hasMany(Workspace::class, 'created_by');
    }
}
