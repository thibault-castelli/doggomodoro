<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTimerSettings extends Model
{
    protected $fillable = [
        'user_id',
        'work_duration',
        'break_duration',
        'long_break_duration',
        'long_break_interval',
        'notifications_enabled',
        'auto_play',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
