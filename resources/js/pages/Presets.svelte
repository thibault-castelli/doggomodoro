<script lang="ts">
    import type { UserTimerPresets, BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import HeadingSmall from '@/components/HeadingSmall.svelte';
    import { useForm, page } from '@inertiajs/svelte';
    import Label from '@/components/ui/label/label.svelte';
    import Input from '@/components/ui/input/input.svelte';
    import InputError from '@/components/InputError.svelte';
    import Checkbox from '@/components/ui/checkbox/checkbox.svelte';
    import Button from '@/components/ui/button/button.svelte';
    import { z } from 'zod';
    import { mapZodErrosToFormErrors } from '@/lib/formValidationUtils';
    import TimerPresetSelect from '@/components/timer/TimerPresetSelect.svelte';
    import { toast } from 'svelte-sonner';

    interface Props {
        timerPresets: UserTimerPresets[];
    }

    let success = $derived($page.props.flash.success);
    let error = $derived($page.props.flash.error);
    $effect(() => {
        if (success) {
            toast.success(success);
            success = '';
        } else if (error) {
            toast.error(error, { duration: 5000 });
            error = '';
        }
    });

    let { timerPresets }: Props = $props();

    let canSubmitForm = $state(true);

    const breadcrumbItems: BreadcrumbItem[] = [
        {
            title: 'Presets',
            href: '/presets',
        },
    ];

    let selectedPresetId = $state(timerPresets[0].id.toString());
    let selectedPreset = $derived(timerPresets.find((preset) => preset.id.toString() === selectedPresetId) || timerPresets[0]);

    const timerSettingsSchema = z.object({
        work_duration: z.number().int('blabla').min(1).max(60),
        break_duration: z.number().int().min(1).max(60),
        long_break_duration: z.number().int().min(1).max(60),
        long_break_interval: z.number().int().min(1).max(10),
        auto_play: z.boolean(),
    });

    const form = $derived(
        useForm({
            name: selectedPreset.name,
            work_duration: selectedPreset.work_duration,
            break_duration: selectedPreset.break_duration,
            long_break_duration: selectedPreset.long_break_duration,
            long_break_interval: selectedPreset.long_break_interval,
            auto_play: selectedPreset.auto_play,
        }),
    );

    const validateForm = () => {
        const formatedData = {
            name: $form.name,
            work_duration: Number($form.work_duration),
            break_duration: Number($form.break_duration),
            long_break_duration: Number($form.long_break_duration),
            long_break_interval: Number($form.long_break_interval),
            auto_play: Boolean($form.auto_play),
        };

        const validation = timerSettingsSchema.safeParse(formatedData);
        if (!validation.success) {
            $form.errors = mapZodErrosToFormErrors(validation.error);
            canSubmitForm = false;
            return false;
        }

        $form.errors = {};
        canSubmitForm = true;
        return true;
    };

    const submit = (e: Event) => {
        e.preventDefault();

        if (!validateForm()) return;

        $form.put(route('presets.update', selectedPresetId), {
            preserveScroll: true,
        });
    };
</script>

<AppLayout title="Presets" breadcrumbs={breadcrumbItems}>
    <TimerPresetSelect bind:value={selectedPresetId} {timerPresets} />
    <div class="space-y-6">
        <HeadingSmall title="Timer Settings" description="Configure your timer settings for optimal productivity." />
        <form onsubmit={submit} class="space-y-6">
            <div class="grid gap-2">
                <Label for="name">Preset Name</Label>
                <Input id="name" type="text" class="mt-1 block w-full" bind:value={$form.name} onblur={validateForm} />
                <InputError class="mt-2" message={$form.errors.name} />
            </div>

            <div class="grid gap-2">
                <Label for="workDuration">Work Duration (minutes)</Label>
                <Input id="workDuration" type="number" class="mt-1 block w-full" bind:value={$form.work_duration} onblur={validateForm} />
                <InputError class="mt-2" message={$form.errors.work_duration} />
            </div>

            <div class="grid gap-2">
                <Label for="breakDuration">Break Duration (minutes)</Label>
                <Input id="breakDuration" type="number" class="mt-1 block w-full" bind:value={$form.break_duration} onblur={validateForm} />
                <InputError class="mt-2" message={$form.errors.break_duration} />
            </div>

            <div class="grid gap-2">
                <Label for="longBreakDuration">Long Break Duration (minutes)</Label>
                <Input id="longBreakDuration" type="number" class="mt-1 block w-full" bind:value={$form.long_break_duration} onblur={validateForm} />
                <InputError class="mt-2" message={$form.errors.long_break_duration} />
            </div>

            <div class="grid gap-2">
                <Label for="longBreakInterval">Long Break Interval</Label>
                <Input id="longBreakInterval" type="number" class="mt-1 block w-full" bind:value={$form.long_break_interval} onblur={validateForm} />
                <InputError class="mt-2" message={$form.errors.long_break_interval} />
            </div>

            <div class="grid gap-2">
                <Label for="autoPlay">Auto Play</Label>
                <Checkbox id="autoPlay" class="mt-1 block h-4 w-4" bind:checked={$form.auto_play} />
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" disabled={$form.processing || !canSubmitForm}>Save</Button>
            </div>
        </form>
    </div>
</AppLayout>
