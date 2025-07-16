<script lang="ts">
    import type { UserTimerSettings, BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import SettingsLayout from '@/layouts/settings/Layout.svelte';
    import HeadingSmall from '@/components/HeadingSmall.svelte';
    import { useForm } from '@inertiajs/svelte';
    import Label from '@/components/ui/label/label.svelte';
    import Input from '@/components/ui/input/input.svelte';
    import InputError from '@/components/InputError.svelte';
    import Checkbox from '@/components/ui/checkbox/checkbox.svelte';
    import Button from '@/components/ui/button/button.svelte';
    import { fade } from 'svelte/transition';
    import { z } from 'zod';
    import { mapZodErrosToFormErrors } from '@/lib/formValidationUtils';

    interface Props {
        timerSettings: UserTimerSettings;
    }

    let { timerSettings }: Props = $props();

    let canSubmitForm = $state(true);

    const breadcrumbItems: BreadcrumbItem[] = [
        {
            title: 'Settings',
            href: '/settings',
        },
        {
            title: 'Timer settings',
            href: '/settings/timer',
        },
    ];

    const timerSettingsSchema = z.object({
        work_duration: z.number().int('blabla').min(1).max(60),
        break_duration: z.number().int().min(1).max(60),
        long_break_duration: z.number().int().min(1).max(60),
        long_break_interval: z.number().int().min(1).max(10),
        auto_play: z.boolean(),
    });

    const form = useForm({
        work_duration: timerSettings.work_duration,
        break_duration: timerSettings.break_duration,
        long_break_duration: timerSettings.long_break_duration,
        long_break_interval: timerSettings.long_break_interval,
        auto_play: timerSettings.auto_play,
    });

    const validateForm = () => {
        const formatedData = {
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

        $form.put(route('timer.update'), {
            preserveScroll: true,
        });
    };
</script>

<AppLayout title="Timer Settings" breadcrumbs={breadcrumbItems}>
    <SettingsLayout>
        <div class="space-y-6">
            <HeadingSmall title="Timer Settings" description="Configure your timer settings for optimal productivity." />
            <form onsubmit={submit} class="space-y-6">
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
                    <Input
                        id="longBreakDuration"
                        type="number"
                        class="mt-1 block w-full"
                        bind:value={$form.long_break_duration}
                        onblur={validateForm}
                    />
                    <InputError class="mt-2" message={$form.errors.long_break_duration} />
                </div>

                <div class="grid gap-2">
                    <Label for="longBreakInterval">Long Break Interval</Label>
                    <Input
                        id="longBreakInterval"
                        type="number"
                        class="mt-1 block w-full"
                        bind:value={$form.long_break_interval}
                        onblur={validateForm}
                    />
                    <InputError class="mt-2" message={$form.errors.long_break_interval} />
                </div>

                <div class="grid gap-2">
                    <Label for="autoPlay">Auto Play</Label>
                    <Checkbox id="autoPlay" class="mt-1 block h-4 w-4" bind:checked={$form.auto_play} />
                </div>

                <div class="flex items-center gap-4">
                    <Button type="submit" disabled={$form.processing || !canSubmitForm}>Save</Button>

                    {#if $form.recentlySuccessful}
                        <p class="text-sm text-neutral-600" transition:fade={{ duration: 150 }}>Saved.</p>
                    {/if}
                </div>
            </form>
        </div>
    </SettingsLayout>
</AppLayout>
