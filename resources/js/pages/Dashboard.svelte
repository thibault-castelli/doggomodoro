<script lang="ts">
    import AppLayout from '@/layouts/AppLayout.svelte';
    import type { UserTimerStats, BreadcrumbItem } from '@/types';
    import Heading from '@/components/headings/Heading.svelte';
    import { router } from '@inertiajs/svelte';
    import TimerStatsTable from '@/components/dashboard/TimerStatsTable.svelte';
    import DailySessionCountCharts from '@/components/dashboard/DailySessionCountCharts.svelte';

    interface Props {
        timerStats: UserTimerStats;
    }

    let { timerStats }: Props = $props();
    // TODO: find why when reloading Timer page and going to dashboard page, the timerStats props is undefined
    if (!timerStats) router.get(route('dashboard'));

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
        },
    ];
</script>

<AppLayout title="Dashboard" {breadcrumbs}>
    <div class="space-y-4 px-4 pt-4">
        <Heading title="Dashboard" description="View your dashboard and timer statistics." />

        <DailySessionCountCharts />
        <TimerStatsTable timerStats={timerStats} />
    </div>
</AppLayout>
