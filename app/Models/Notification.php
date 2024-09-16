<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 
        'icon', 
        'body', 
        'notifiable_id',
        'notifiable_type',
        'invitation_id', 
        'read'
    ];

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function notifiable()
    {
        return $this->morphTo();
    }
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
