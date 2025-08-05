<?php

namespace App\Http\Controllers\Timer;

use App\Http\Controllers\Controller;
use App\Models\DailySessionCount;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class DailySessionCountController extends Controller
{
    public function get(string $from, ?string $to = null)
    {
        $fromDate = DateTime::createFromFormat('Y-m-d', $from);
        $toDate = null;
        if ($to) {
            $toDate = DateTime::createFromFormat('Y-m-d', $to);
        }

        try {
            $dailySessionCount = DailySessionCount::forUser(Auth::id(), $fromDate, $toDate);

            return response()->json(['dailySessionsCount' => $dailySessionCount]);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve daily sessions count: ' . $e->getMessage()], 500);
        }
    }

    public function createOrUpdate()
    {
        $startDay = (new DateTime)->setTime(0, 0, 0);
        $endDay = (new DateTime)->setTime(23, 59, 59);

        try {
            $dailySessionCount = DailySessionCount::where('user_id', Auth::id())->whereBetween('created_at', [$startDay, $endDay])->first();

            if ($dailySessionCount) {
                $dailySessionCount->sessions_completed = $dailySessionCount->sessions_completed + 1;
                $dailySessionCount->save();
            } else {
                DailySessionCount::create(['user_id' => Auth::id(), 'sessions_completed' => 1]);
            }

            return response()->json();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
