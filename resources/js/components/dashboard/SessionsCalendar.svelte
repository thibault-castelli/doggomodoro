<script lang="ts">
    import { onMount } from 'svelte';
    import type { User } from '@/types';
    import { page } from '@inertiajs/svelte';
    import { CalendarDate } from '@internationalized/date';
    import { Calendar } from '@/components/ui/calendar';
    import { getDailySessionsCount } from '@/lib/services/timerService';

    const user = $page.props.auth.user as User;

    let completedSessionsCalendarDates: CalendarDate[] = $state([]);

    onMount( async () => {
        const dailySessions = await getDailySessionsCount(new Date(user.created_at));

        completedSessionsCalendarDates = dailySessions.map((dailySession) => {
            const dailySessionDate = new Date(dailySession.created_at);
            const year = dailySessionDate.getFullYear();
            const month = dailySessionDate.getMonth() + 1;
            const day = dailySessionDate.getDate();

            return new CalendarDate(year, month, day);
        })
    })

</script>

<Calendar
    type="single"
    class="rounded-md border shadow-sm mb-5 w-64 lg:mb-0 mx-auto"
    highlightDates={completedSessionsCalendarDates}
    footerText='Showing days with at least one session completed'
/>
