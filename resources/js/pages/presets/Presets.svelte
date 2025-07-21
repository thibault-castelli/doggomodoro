<script lang="ts">
    import type { UserTimerPreset, BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import TimerPresetSelect from '@/components/timer/TimerPresetSelect.svelte';
    import Heading from '@/components/Heading.svelte';
    import { router } from '@inertiajs/svelte';
    import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.svelte';
    import { Plus, Pen } from 'lucide-svelte';
    import ButtonWithTooltip from '@/components/ButtonWithTooltip.svelte';

    interface Props {
        timerPresets: UserTimerPreset[];
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

    const goToCreatePresetPage = () => {
        router.get('/presets/create');
    };

    const goToEditPresetPage = () => {
        router.get('/presets/' + selectedPresetId);
    };

    const goToDeletePresetPage = () => {
        if (timerPresets.length <= 1) return;

        router.delete('/presets/' + selectedPresetId);
        resetSelectedPresetIdAfterDelete();
    };

    const resetSelectedPresetIdAfterDelete = () => {
        const selectedPresetIndex = timerPresets.findIndex((preset) => preset.id.toString() === selectedPresetId);
        selectedPresetIndex === 0 ? (selectedPresetId = timerPresets[1].id.toString()) : (selectedPresetId = timerPresets[0].id.toString());
    };
</script>

<AppLayout title="Presets" breadcrumbs={breadcrumbItems}>
    <div class="px-4 py-6">
        <Heading title="Timer Presets" description="Configure your timer presets for optimal productivity." />

        <div class="flex flex-col gap-4 mb-4 sm:items-end sm:flex-row">
            <div class="grow">
                <TimerPresetSelect bind:value={selectedPresetId} {timerPresets} />
            </div>
            <div class="flex justify-around gap-2 grow sm:justify-start">
                <ButtonWithTooltip action={goToCreatePresetPage} tooltipText="Create Preset"><Plus /></ButtonWithTooltip>
                <ButtonWithTooltip action={goToEditPresetPage} tooltipText="Edit Selected Preset"><Pen /></ButtonWithTooltip>
                {#if timerPresets.length > 1}
                    <ConfirmDeleteDialog title={`Delete '${selectedPreset.name}' Preset?`} action={goToDeletePresetPage} />
                {/if}
            </div>
        </div>

        <p>Preset Name: <span class="font-bold">{selectedPreset.name}</span></p>

        <p>Work Duration (minutes): <span class="font-bold">{selectedPreset.work_duration}</span></p>

        <p>Break Duration (minutes): <span class="font-bold">{selectedPreset.break_duration}</span></p>

        <p>Long Break Duration (minutes): <span class="font-bold">{selectedPreset.long_break_duration}</span></p>

        <p>Long Break Interval: <span class="font-bold">{selectedPreset.long_break_interval}</span></p>

        <p>Auto Play:<span class="font-bold">{selectedPreset.auto_play ? 'Yes' : 'No'}</span></p>
    </div>
</AppLayout>
