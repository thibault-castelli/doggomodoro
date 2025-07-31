<script lang="ts">
    import PlayPauseButton from '@/components/timer/PlayPauseButton.svelte';
    import StopReloadButton from '@/components/timer/StopButton.svelte';
    import TimerDisplay from '@/components/timer/TimerDisplay.svelte';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { Timer } from '@/lib/timerLogic.svelte';
    import type { UserTimerPreset, BreadcrumbItem } from '@/types';
    import { onDestroy } from 'svelte';
    import TimerPresetSelect from '@/components/timer/TimerPresetSelect.svelte';
    import { Link } from '@inertiajs/svelte';
    import { page } from '@inertiajs/svelte';
    import ReloadButton from '@/components/timer/ReloadButton.svelte';

    interface Props {
        timerPresets: UserTimerPreset[];
    }

    const { timerPresets }: Props = $props();

    const user = $derived($page.props.auth.user);

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Timer',
            href: route('timer'),
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
        <ReloadButton {timer} />
        <PlayPauseButton {timer} />
        <StopReloadButton {timer} />
    </div>

    {#if user}
        <div class="mt-12 w-1/2 m-auto">
            <TimerPresetSelect bind:value={selectedPresetId} {timerPresets} />
            <p class="mt-3 italic text-sm">Need a new preset? <Link href={route('presets.create')} class="underline">Create one here!</Link></p>
        </div>
    {:else}
        <div class="mb-4 w-full text-center absolute bottom-0">
            <p class="mt-3 italic text-sm">
                <Link href={route('login')} class="underline">Login</Link> or <Link href={route('register')} class="underline">Create an account</Link
                > to create your own pomodoro presets, track your progress, and much more!
            </p>
        </div>
    {/if}
</AppLayout>
