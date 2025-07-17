<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTimerPresets extends Model
{
    protected $fillable = [
        'user_id',
        'name',
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
     * @return \Illuminate\Support\Collection<int, UserTimerPresets>
     */
    public static function forUser($userId)
    {
        if (!$userId)
            return collect([new self(self::defaultPreset())]);

        return self::where('user_id', $userId)->get();
    }

    private static function defaultPreset()
    {
        return [
            'name' => 'Default Timer Preset',
            'work_duration' => 25,
            'break_duration' => 5,
            'long_break_duration' => 15,
            'long_break_interval' => 4,
            'notifications_enabled' => true,
            'auto_play' => false,
        ];
    }
}
