<?php

namespace App\Http\Controllers\Timer;

use App\Http\Controllers\Controller;
use App\Models\DailySessionCount;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class DailySessionCountController extends Controller
{
    public function get(string $from, ?string $to = null)
    {
        $fromDate = \DateTime::createFromFormat('Y-m-d', $from);
        $toDate = null;
        if ($to) {
            $toDate = \DateTime::createFromFormat('Y-m-d', $to);
        }

        try {
            $dailySessionCount = DailySessionCount::forUser(Auth::id(), $fromDate, $toDate);

            return response()->json(['dailySessionsCount' => $dailySessionCount]);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve daily sessions count: ' . $e->getMessage()], 500);
        }
    }
}
