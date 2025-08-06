<script lang="ts">
import { minutesToTime } from '@/lib/utils/timeConverter';
import HeadingSmall from '@/components/headings/HeadingSmall.svelte';
import type { UserTimerStats } from '@/types';
import * as Table from '@/components/ui/table';

interface Props {
    timerStats: UserTimerStats;
}

let { timerStats }: Props = $props();
</script>

<section class="my-5 border rounded-2xl p-5">
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
