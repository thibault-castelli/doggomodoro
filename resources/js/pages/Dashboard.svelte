<script lang="ts">
    import AppLayout from '@/layouts/AppLayout.svelte';
    import * as Table from '@/components/ui/table';
    import type { UserTimerStats, BreadcrumbItem } from '@/types';
    import { minutesToTime } from '@/lib/utils/timeConverter';
    import Heading from '@/components/headings/Heading.svelte';
    import HeadingSmall from '@/components/HeadingSmall.svelte';

    interface Props {
        timerStats: UserTimerStats;
    }

    const { timerStats }: Props = $props();
    console.log(timerStats);

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

        <section>
            <HeadingSmall title="Timer Statistics" />
            <Table.Root>
                <Table.Caption>Your timer statistics.</Table.Caption>
                <Table.Body>
                    <Table.Row>
                        <Table.Head>Total Work Time</Table.Head>
                        <Table.Cell>{minutesToTime(timerStats.total_work_time)}</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Head>Total Break Time</Table.Head>
                        <Table.Cell>{minutesToTime(timerStats.total_break_time)}</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Head>Total Rounds Completed</Table.Head>
                        <Table.Cell>{timerStats.total_rounds_completed}</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Head>Total Sessions Completed</Table.Head>
                        <Table.Cell>{timerStats.total_sessions_completed}</Table.Cell>
                    </Table.Row>
                </Table.Body>
            </Table.Root>
        </section>
    </div>
</AppLayout>
