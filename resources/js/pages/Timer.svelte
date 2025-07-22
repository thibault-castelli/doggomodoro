<script lang="ts">
    import PlayPauseButton from '@/components/timer/PlayPauseButton.svelte';
    import StopReloadButton from '@/components/timer/StopReloadButton.svelte';
    import TimerDisplay from '@/components/timer/TimerDisplay.svelte';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { Timer } from '@/lib/timerLogic.svelte';
    import type { UserTimerPreset, BreadcrumbItem } from '@/types';
    import { onDestroy } from 'svelte';
    import TimerPresetSelect from '@/components/timer/TimerPresetSelect.svelte';
    import { Link } from '@inertiajs/svelte';
    import { page } from '@inertiajs/svelte';

    interface Props {
        timerPresets: UserTimerPreset[];
    }

    let { timerPresets }: Props = $props();
    console.log(timerPresets);

    const user = $derived($page.props.auth.user);

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Timer',
            href: '/timer',
        },
    ];

    let selectedPresetId = $state(timerPresets[0].id.toString());
    let currentPreset = $derived(timerPresets.find((preset) => preset.id.toString() === selectedPresetId) || timerPresets[0]);

    const timer = $derived(new Timer(currentPreset));

    onDestroy(() => {
        timer.pauseTimer();
    });
</script>

<AppLayout title="Timer" {breadcrumbs}>
    <TimerDisplay {timer} />
    <div class="flex items-center justify-center gap-4">
        <PlayPauseButton {timer} />
        <StopReloadButton {timer} />
    </div>

    {#if user}
        <div class="mt-12 w-1/2 m-auto">
            <TimerPresetSelect bind:value={selectedPresetId} {timerPresets} />
            <p class="mt-3 italic text-sm">Need a new preset? <Link href={route('presets.create')} class="underline">Create one here</Link></p>
        </div>
    {/if}
</AppLayout>
