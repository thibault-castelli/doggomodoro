<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
class UserTimerStats extends Model
{
    /** @use HasFactory<\Database\Factories\UserTimerStatsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_work_time',
        'total_break_time',
        'total_rounds_completed',
        'total_sessions_completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get timer stats for a specific user.
     * @param int|null $userId
     * @throws \InvalidArgumentException if user id is not a number or is less than or equal to zero.
     * @return UserTimerStats
     */
    public function forUser(int|null $userId): UserTimerStats
    {
        if ($userId)
            return self::defaultTimerStats();

        if (!is_numeric($userId) || $userId <= 0)
            throw new InvalidArgumentException('Invalid user ID.');

        return self::where('user_id', $userId)->firstOrFail();
    }

    /**
     * Get a default timer stats
     * @return UserTimerStats
     */
    public static function defaultTimerStats(): UserTimerStats
    {
        return new self([
            'user_id' => -1,
            'total_work_time' => 0,
            'total_break_time' => 0,
            'total_rounds_completed' => 0,
            'total_sessions_completed' => 0
        ]);
    }
}
