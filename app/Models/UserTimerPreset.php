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
     * @return Collection<int, UserTimerPreset>
     */
    public static function forUser($userId): Collection
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
     * @return ?UserTimerPreset
     */
    public static function forUserSingle($userId, $presetId): ?UserTimerPreset
    {
        if (!$userId)
            return self::defaultPreset();

        if (!is_numeric($userId) || $userId <= 0 || !is_numeric($presetId) || $presetId <= 0)
            throw new InvalidArgumentException('Invalid user ID.');

        return self::where(['user_id' => $userId, 'id' => $presetId])->firstOrFail();
    }

    public static function forUserCount($userId): int
    {
        if (!$userId)
            return 0;

        return self::where('user_id', $userId)->count();
    }

    public static function defaultPreset(): UserTimerPreset
    {
        return new self([
            'name' => 'Default Timer Preset',
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
