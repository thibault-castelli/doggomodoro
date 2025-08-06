<script lang="ts">
    import type { DailySessionCount } from '@/types';
    import { onMount } from 'svelte';
    import * as Chart from "@/components/ui/chart/index.js";
    import * as Card from "@/components/ui/card/index.js";
    import * as Select from "@/components/ui/select/index.js";
    import { scaleUtc } from "d3-scale";
    import { AreaChart } from "layerchart";
    import { curveNatural } from "d3-shape";
    import ChartContainer from "../ui/chart/chart-container.svelte";
    import { getDailySessionsCount } from '@/lib/services/timerService';

    let dailySessions: DailySessionCount[] = $state([]);

    onMount(async () => {
        const threeMonthAgo = new Date();
        threeMonthAgo.setDate(threeMonthAgo.getDate() - 90);

        dailySessions = await getDailySessionsCount(threeMonthAgo)
    })

    let timeRange = $state("90d");

    const selectedLabel = $derived.by(() => {
        switch (timeRange) {
            case "90d":
                return "Last 3 months";
            case "30d":
                return "Last 30 days";
            case "7d":
                return "Last 7 days";
            default:
                return "Last 3 months";
        }
    });

    const fromDate = $derived.by(() => {
        const fromDate = new Date();

        let daysToSubtract = 90;
        if (timeRange === "30d") {
            daysToSubtract = 30;
        } else if (timeRange === "7d") {
            daysToSubtract = 7;
        }

        fromDate.setDate(fromDate.getDate() - daysToSubtract);
        return fromDate;
    })

    const filteredData = $derived(
        dailySessions
            .filter((item) =>  new Date(item.created_at) >= fromDate)
            .map((item) => {
                return {date: new Date(item.created_at), sessionsCompleted: item.sessions_completed};
            })
    );

    const chartConfig = {
        completedSessions: { label: "Completed Sessions", color: "var(--primary)" },
    } satisfies Chart.ChartConfig;

</script>

<Card.Root class="w-full bg-transparent">
    <Card.Header class="flex items-center gap-2 space-y-0 border-b py-5 sm:flex-row">
        <div class="grid flex-1 gap-1 text-center sm:text-left">
            <Card.Title>Completed Sessions Chart</Card.Title>
            <Card.Description>
                Showing completed sessions for the last {timeRange === '90d' ? '3 months' : timeRange === '30d' ? '30 days': '7 days'}
            </Card.Description>
        </div>
        <Select.Root type="single" bind:value={timeRange}>
            <Select.Trigger class="w-[160px] rounded-lg sm:ml-auto" aria-label="Select a value">
                {selectedLabel}
            </Select.Trigger>
            <Select.Content class="rounded-xl">
                <Select.Item value="90d" class="rounded-lg">Last 3 months</Select.Item>
                <Select.Item value="30d" class="rounded-lg">Last 30 days</Select.Item>
                <Select.Item value="7d" class="rounded-lg">Last 7 days</Select.Item>
            </Select.Content>
        </Select.Root>
    </Card.Header>
    <Card.Content>
        <ChartContainer config={chartConfig} class="aspect-auto h-[250px] w-full">
            <AreaChart
                legend
                data={filteredData}
                x="date"
                xScale={scaleUtc()}
                series={[
                    {
                        key: "sessionsCompleted",
                        label: "Completed Sessions",
                        color: chartConfig.completedSessions.color,
                    },
                ]}
                seriesLayout="stack"
                props={{
                    area: {
                        curve: curveNatural,
                        "fill-opacity": 0.4,
                        line: { class: "stroke-1" },
                        motion: "tween",
                    },
                    xAxis: {
                        ticks: timeRange === "7d" ? 7 : undefined,
                        format: (v) => {
                            return v.toLocaleDateString("en-US", {
                                month: "short",
                                day: "numeric",
                            });
                        },
                    },
                    yAxis: { format: () => "" },
                }}
            >
                {#snippet tooltip()}
                    <Chart.Tooltip
                        labelFormatter={(v: Date) => {
                            return v.toLocaleDateString("en-US", {
                                day: "numeric",
                                month: "long",
                            });
                        }}
                        indicator="line"
                    />
                {/snippet}
            </AreaChart>
        </ChartContainer>
    </Card.Content>
    <Card.Footer>
        <div class="flex w-full items-start gap-2 text-sm">
            <div class="grid gap-2">
                <div class="text-muted-foreground flex items-center gap-2 leading-none">
                    {fromDate.toLocaleDateString("en-US", {
                        month: "short",
                        year: "numeric",
                    })} - {new Date().toLocaleDateString("en-US", {
                    month: "short",
                    year: "numeric",
                })}
                </div>
            </div>
        </div>
    </Card.Footer>
</Card.Root>
