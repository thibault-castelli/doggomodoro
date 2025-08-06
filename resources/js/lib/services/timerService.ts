import { dateToString } from '@/lib/utils/timeConverter';
import type { DailySessionCount } from '@/types';
import axios from 'axios';

const updateTimerStats = async (totalWorkTime: number, totalBreakTime: number, totalRoundsCompleted: number) => {
    try {
        await axios.put(route('timerStats.update'), {
            total_work_time: totalWorkTime,
            total_break_time: totalBreakTime,
            total_rounds_completed: totalRoundsCompleted,
        });

        await axios.post(route('dailySessionCount.createOrUpdate'));
    } catch (error) {
        console.error(error);
    }
};

const getDailySessionsCount = async (from: Date) => {
    const formattedFrom = dateToString(from);

    try {
        const response = await axios.get<{ dailySessionsCount: DailySessionCount[] }>(route('dailySessionCount', { from: formattedFrom }));
        return response.data.dailySessionsCount;
    } catch (error) {
        console.error(error);
        return [];
    }
};

export { getDailySessionsCount, updateTimerStats };
