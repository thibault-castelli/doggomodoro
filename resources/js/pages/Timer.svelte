<script lang="ts">
    import PlayPauseButton from '@/components/timer/PlayPauseButton.svelte';
    import StopReloadButton from '@/components/timer/StopReloadButton.svelte';
    import TimerDisplay from '@/components/timer/TimerDisplay.svelte';
    import { setTimerContext } from '@/contexts/timerContext.svelte';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { Timer } from '@/contexts/timerLogic.svelte';
    import type { UserTimerSettings, BreadcrumbItem } from '@/types';
    import { onDestroy } from 'svelte';

    interface Props {
        timerSettings: UserTimerSettings;
    }

    let { timerSettings }: Props = $props();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Timer',
            href: '/timer',
        },
    ];

    const timer = new Timer(timerSettings);
    setTimerContext(timer);

    onDestroy(() => {
        timer.pauseTimer();
    });
</script>

<AppLayout title="Timer" {breadcrumbs}>
    <TimerDisplay />
    <PlayPauseButton />
    <StopReloadButton />
</AppLayout>
