<script lang="ts">
    import type { UserTimerPreset, BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import TimerPresetSelect from '@/components/timer/TimerPresetSelect.svelte';
    import Heading from '@/components/Heading.svelte';
    import { router } from '@inertiajs/svelte';
    import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.svelte';
    import { Plus, Pen } from 'lucide-svelte';
    import ButtonWithTooltip from '@/components/ButtonWithTooltip.svelte';
    import * as Table from '@/components/ui/table';

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
        if (selectedPresetIndex === 0) selectedPresetId = timerPresets[1].id.toString();
        else selectedPresetId = timerPresets[0].id.toString();
    };
</script>

<AppLayout title="Presets" breadcrumbs={breadcrumbItems}>
    <div class="px-4 py-6">
        <Heading title="Timer Presets" description="Configure your timer presets for optimal productivity." />

        <div class="flex flex-col gap-5 mb-5 sm:items-end sm:flex-row">
            <div class="grow w-full sm:max-w-[50%]">
                <TimerPresetSelect bind:value={selectedPresetId} {timerPresets} />
            </div>
            <div class="flex justify-around gap-2 grow sm:justify-start">
                <ButtonWithTooltip action={goToCreatePresetPage} tooltipText="Create Preset"><Plus /></ButtonWithTooltip>
                <ButtonWithTooltip action={goToEditPresetPage} tooltipText="Edit Selected Preset"><Pen /></ButtonWithTooltip>
                {#if timerPresets.length > 1}
                    <ConfirmDeleteDialog
                        title={`Delete '${selectedPreset.name}' Preset?`}
                        action={goToDeletePresetPage}
                        tooltipText="Delete Selected Preset"
                    />
                {/if}
            </div>
        </div>

        <Table.Root>
            <Table.Caption>Your preset informations.</Table.Caption>
            <Table.Body>
                <Table.Row>
                    <Table.Head>Name</Table.Head>
                    <Table.Cell>{selectedPreset.name}</Table.Cell>
                </Table.Row>
                <Table.Row>
                    <Table.Head>Work Duration</Table.Head>
                    <Table.Cell>{selectedPreset.work_duration} minutes</Table.Cell>
                </Table.Row>
                <Table.Row>
                    <Table.Head>Break Duration</Table.Head>
                    <Table.Cell>{selectedPreset.break_duration} minutes</Table.Cell>
                </Table.Row>
                <Table.Row>
                    <Table.Head>Long Break Duration</Table.Head>
                    <Table.Cell>{selectedPreset.long_break_duration} minutes</Table.Cell>
                </Table.Row>
                <Table.Row>
                    <Table.Head>Long Break Interval</Table.Head>
                    <Table.Cell>{selectedPreset.long_break_interval}</Table.Cell>
                </Table.Row>
                <Table.Row>
                    <Table.Head>Auto Play</Table.Head>
                    <Table.Cell>{selectedPreset.auto_play ? 'Yes' : 'No'}</Table.Cell>
                </Table.Row>
                <Table.Row>
                    <Table.Head>Notifications</Table.Head>
                    <Table.Cell>{selectedPreset.notifications ? 'Yes' : 'No'}</Table.Cell>
                </Table.Row>
            </Table.Body>
        </Table.Root>
    </div>
</AppLayout>
