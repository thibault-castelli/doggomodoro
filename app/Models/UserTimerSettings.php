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

    /**
     * Get the timer settings for a specific user.
     *
     * @param int|null $userId
     * @return UserTimerSettings
     */
    public static function forUser($userId)
    {
        if (!$userId)
            return new self(self::defaultSettings());

        return self::where('user_id', $userId)->firstOrFail();
    }

    private static function defaultSettings()
    {
        return [
            'work_duration' => 25,
            'break_duration' => 5,
            'long_break_duration' => 15,
            'long_break_interval' => 4,
            'notifications_enabled' => true,
            'auto_play' => false,
        ];
    }
}
