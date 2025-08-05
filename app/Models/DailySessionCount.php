<?php

namespace App\Models;

use Database\Factories\DailySessionCountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class DailySessionCount extends Model
{
    /** @use HasFactory<DailySessionCountFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'sessions_completed'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get daily session counts for user at given date.
     *
     * @throws InvalidArgumentException if user id is not a number or is less than or equal to zero. Also, if date from is greater than date to.
     */
    public static function forUser(int $userId, \DateTime $from, ?\DateTime $to): Collection
    {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new InvalidArgumentException('Invalid user ID.');
        }

        $from->setTime(0, 0, 0);

        if ($to !== null) {
            $to->setTime(23, 59, 59);
            if ($from > $to) {
                throw new InvalidArgumentException('From cannot be less than To.');
            }

            return self::where('user_id', $userId)->whereBetween('created_at', [$from, $to])->get();
        }

        return self::where('user_id', $userId)->where('created_at', '>=', $from)->get();
    }
}
