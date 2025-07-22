import { toast } from 'svelte-sonner';

export const showRoundEndNotification = (roundType: 'work' | 'shortBreak' | 'longBreak') => {
    switch(roundType) {
        case 'work':
            toast.message('Break time is over! 🐕', {description: 'Time to get back to work. You\'ve got this!', duration: 5000});
            break;
        case 'shortBreak':
            toast.message('Time for a break! 🎉', {description: 'Take a break and recharge.', duration: 5000});
            break;
        case 'longBreak':
            toast.message('Session Complete! 🎊', {description: 'Congratulations! You\'ve completed your full pomodoro session.', duration: 5000});
            break;
    }
};