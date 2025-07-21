<script lang="ts">
    import * as Select from '@/components/ui/select';
    import Label from '@/components/ui/label/label.svelte';
    import type { UserTimerPreset } from '@/types';

    let { value = $bindable(), ...props } = $props();

    let currentPresetName = $derived(props.timerPresets.find((preset: UserTimerPreset) => preset.id.toString() === value)?.name || 'Select Preset');
</script>

<div>
    <Label class="mb-4">Select Timer Preset</Label>
    <Select.Root type="single" bind:value>
        <Select.Trigger>{currentPresetName}</Select.Trigger>
        <Select.Content>
            {#each props.timerPresets as preset (preset.id)}
                <Select.Item value={preset.id.toString()}>{preset.name}</Select.Item>
            {/each}
        </Select.Content>
    </Select.Root>
</div>
