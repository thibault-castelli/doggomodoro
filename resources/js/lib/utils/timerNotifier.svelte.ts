import { toast } from 'svelte-sonner';

// Request notification permission on first load
if (typeof window !== 'undefined' && 'Notification' in window) {
    if (Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

const sendBrowserNotification = (title: string, body: string, icon?: string) => {
    // Check if notifications are supported and permitted
    if (typeof window === 'undefined' || !('Notification' in window)) {
        return;
    }

    if (Notification.permission === 'granted') {
        const notification = new Notification(title, {
            body,
            icon: icon || '/favicon.ico',
            badge: '/favicon.ico',
            tag: 'doggomodoro-timer',
            requireInteraction: false,
            silent: false,
        });

        setTimeout(() => {
            notification.close();
        }, 5000);

        notification.onclick = () => {
            window.focus();
            notification.close();
        };
    } else if (Notification.permission === 'default') {
        // Try to request permission again
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                sendBrowserNotification(title, body, icon);
            }
        });
    }
};

const sendToastNotification = (title: string, body: string) => {
    toast.message(title, { description: body, duration: 5000 });
};

const sendNotification = (title: string, body: string) => {
    sendToastNotification(title, body);
    sendBrowserNotification(title, body);
};

export const showRoundEndNotification = (passedRoundType: 'work' | 'shortBreak' | 'longBreak'): void => {
    switch (passedRoundType) {
        case 'work':
            sendNotification('Break time is over! 🐕', "Time to get back to work. You've got this!");
            break;
        case 'shortBreak':
            sendNotification('Time for a break! 🎉', 'Take a break and recharge.');
            break;
        case 'longBreak':
            sendNotification('Session Complete! 🎊', "Congratulations! You've completed your full pomodoro session.");
            break;
    }
};
