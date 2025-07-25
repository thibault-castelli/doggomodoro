<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class UserTimerPreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'work_duration',
        'break_duration',
        'long_break_duration',
        'long_break_interval',
        'auto_play',
        'notifications',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all timer presets for a specific user.
     *
     * @param int|null $userId
     * @throws \InvalidArgumentException if user id is not a number or is less than or equal to zero.
     * @return Collection<int, UserTimerPreset>
     */
    public static function forUser(int|null $userId): Collection
    {
        if (!$userId)
            return collect([self::defaultPreset()]);

        if (!is_numeric($userId) || $userId <= 0)
            throw new InvalidArgumentException('Invalid user ID.');

        return self::where('user_id', $userId)->get();
    }

    /**
     * Get one timer preset for a specific user.
     * @param mixed $userId
     * @param mixed $presetId
     * @throws \InvalidArgumentException if user id or preset id is not a number or is less than or equal to zero.
     * @return ?UserTimerPreset
     */
    public static function forUserSingle(int|null $userId, int|null $presetId): ?UserTimerPreset
    {
        if (!$userId)
            return self::defaultPreset();

        if (!is_numeric($userId) || $userId <= 0 || !is_numeric($presetId) || $presetId <= 0)
            throw new InvalidArgumentException('Invalid user ID.');

        return self::where(['user_id' => $userId, 'id' => $presetId])->firstOrFail();
    }

    /**
     * Get timer preset count for a specific user.
     * @param mixed $userId
     * @return int
     */
    public static function forUserCount(int|null $userId): int
    {
        if (!$userId)
            return 0;

        return self::where('user_id', $userId)->count();
    }

    /**
     * Get a default timer preset
     * @return UserTimerPreset
     */
    public static function defaultPreset(): UserTimerPreset
    {
        return new self([
            'id' => 0,
            'name' => 'Default Timer Preset',
            'user_id' => -1,
            'work_duration' => 25,
            'break_duration' => 5,
            'long_break_duration' => 15,
            'long_break_interval' => 4,
            'notifications_enabled' => true,
            'auto_play' => false,
            'notifications' => false,
        ]);
    }
}
