<script lang="ts">
    import type { UserTimerPresets, BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import Label from '@/components/ui/label/label.svelte';
    import Button from '@/components/ui/button/button.svelte';
    import TimerPresetSelect from '@/components/timer/TimerPresetSelect.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Link, router } from '@inertiajs/svelte';
    import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.svelte';

    interface Props {
        timerPresets: UserTimerPresets[];
    }

    let { timerPresets }: Props = $props();

    const breadcrumbItems: BreadcrumbItem[] = [
        {
            title: 'Presets',
            href: '/presets',
        },
    ];

    let selectedPresetId = $state(timerPresets[0].id.toString());
    let selectedPreset = $derived(timerPresets.find((preset) => preset.id.toString() === selectedPresetId) || timerPresets[0]);

    const goToEditPresetPage = () => {
        router.get('/presets/' + selectedPresetId);
    };

    const goToDeletePresetPage = () => {
        router.delete('/presets/' + selectedPresetId);
        // TODO: Select available preset
        // TODO: Prevent deleting of only one preset left
    };
</script>

<AppLayout title="Presets" breadcrumbs={breadcrumbItems}>
    <div class="space-y-6 m-3 p-3 relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <Heading title="Timer Presets" description="Configure your timer presets for optimal productivity." />

        <div class="flex gap-3">
            <TimerPresetSelect bind:value={selectedPresetId} {timerPresets} />
            <Link href={route('presets.create')}><Button>Create New Preset</Button></Link>
            <Button onclick={goToEditPresetPage}>Edit Preset</Button>
            <ConfirmDeleteDialog triggerText="Delete Preset" title={`Delete '${selectedPreset.name}' Preset?`} action={goToDeletePresetPage} />
        </div>

        <div class="grid gap-2">
            <Label for="name">Preset Name</Label>
            <p>{selectedPreset.name}</p>
        </div>

        <div class="grid gap-2">
            <Label for="workDuration">Work Duration (minutes)</Label>
            <p>{selectedPreset.work_duration}</p>
        </div>

        <div class="grid gap-2">
            <Label for="breakDuration">Break Duration (minutes)</Label>
            <p>{selectedPreset.break_duration}</p>
        </div>

        <div class="grid gap-2">
            <Label for="longBreakDuration">Long Break Duration (minutes)</Label>
            <p>{selectedPreset.long_break_duration}</p>
        </div>

        <div class="grid gap-2">
            <Label for="longBreakInterval">Long Break Interval</Label>
            <p>{selectedPreset.long_break_interval}</p>
        </div>

        <div class="grid gap-2">
            <Label for="autoPlay">Auto Play</Label>
            <p>{selectedPreset.auto_play ? 'Yes' : 'No'}</p>
        </div>
    </div>
</AppLayout>
