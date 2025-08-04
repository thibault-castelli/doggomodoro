<?php

namespace App\Models;

use Database\Factories\UserTimerStatsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class UserTimerStats extends Model
{
    /** @use HasFactory<UserTimerStatsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_work_time',
        'total_break_time',
        'total_rounds_completed',
        'total_sessions_completed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get timer stats for a specific user.
     *
     * @throws InvalidArgumentException if user id is not a number or is less than or equal to zero.
     */
    public static function forUser(int $userId): UserTimerStats
    {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new InvalidArgumentException('Invalid user ID.');
        }

        return self::where('user_id', $userId)->firstOrFail();
    }
}
