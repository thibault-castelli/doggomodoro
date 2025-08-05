import axios from 'axios';

const updateTimerStats = async (totalWorkTime: number, totalBreakTime: number, totalRoundsCompleted: number) => {
    await axios.put(route('timerStats.update'), {
        total_work_time: totalWorkTime,
        total_break_time: totalBreakTime,
        total_rounds_completed: totalRoundsCompleted,
    });

    await axios.post(route('dailySessionCount.createOrUpdate'));
};

export { updateTimerStats };
