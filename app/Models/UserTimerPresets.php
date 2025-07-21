<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

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
     * Get all timer presets for a specific user.
     *
     * @param int|null $userId
     * @return Collection<int, UserTimerPresets>
     */
    public static function forUser($userId): Collection
    {
        if (!$userId)
            return collect([new self(self::defaultPreset())]);

        if (!is_numeric($userId) || $userId <= 0)
            throw new InvalidArgumentException('Invalid user ID.');

        return self::where('user_id', $userId)->get();
    }

    /**
     * Get one timer preset for a specific user.
     * @param mixed $userId
     * @param mixed $presetId
     * @return ?UserTimerPresets
     */
    public static function forUserSingle($userId, $presetId): ?UserTimerPresets
    {
        if (!$userId)
            return new self(self::defaultPreset());

        if (!is_numeric($userId) || $userId <= 0 || !is_numeric($presetId) || $presetId <= 0)
            throw new InvalidArgumentException('Invalid user ID.');

        return self::where(['user_id' => $userId, 'id' => $presetId])->firstOrFail();
    }

    public static function forUserCount($userId): int
    {
        if (!$userId)
            return 0;

        return self::where('user_id', operator: $userId)->count();
    }

    public static function defaultPreset()
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
