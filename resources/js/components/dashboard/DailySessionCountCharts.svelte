<script lang="ts">
    import type { DailySessionCount } from '@/types';
    import { onMount } from 'svelte';
    import axios from 'axios';
    // import TrendingUpIcon from "@lucide/svelte/icons/trending-up";
    import * as Chart from "@/components/ui/chart/index.js";
    import * as Card from "@/components/ui/card/index.js";
    import * as Select from "@/components/ui/select/index.js";
    import { scaleUtc } from "d3-scale";
    import { Area, AreaChart, ChartClipPath } from "layerchart";
    import { curveNatural } from "d3-shape";
    import ChartContainer from "../ui/chart/chart-container.svelte";
    import { cubicInOut } from "svelte/easing";
    import { dateToString } from '@/lib/utils/timeConverter';

    let dailySessions: DailySessionCount[] = $state([]);

    onMount(async () => {
        const threeMonthAgo = new Date();
        threeMonthAgo.setDate(threeMonthAgo.getDate() - 90);
        const formattedThreeMonthAgo = dateToString(threeMonthAgo);

        dailySessions = (await axios.get<{ dailySessionsCount: DailySessionCount[] }>(route('dailySessionCount', { from: formattedThreeMonthAgo }))).data.dailySessionsCount;
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
        dailySessions.filter((item) =>  new Date(item.created_at) >= fromDate)
            .map((item) => {
            return {date: new Date(item.created_at), sessionsCompleted: item.sessions_completed};
        })
    );

    const chartConfig = {
        completedSessions: { label: "Completed Sessions", color: "var(--chart-1)" },
    } satisfies Chart.ChartConfig;
</script>

<Card.Root>
    <Card.Header class="flex items-center gap-2 space-y-0 border-b py-5 sm:flex-row">
        <div class="grid flex-1 gap-1 text-center sm:text-left">
            <Card.Title>Completed Daily Sessions Chart</Card.Title>
            <Card.Description>Showing completed daily sessions for the last 3 months</Card.Description>
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
                {#snippet marks({ series, getAreaProps })}
                    <defs>
                        <linearGradient id="fillDesktop" x1="0" y1="0" x2="0" y2="1">
                            <stop
                                offset="5%"
                                stop-color="var(--color-desktop)"
                                stop-opacity={1.0}
                            />
                            <stop
                                offset="95%"
                                stop-color="var(--color-desktop)"
                                stop-opacity={0.1}
                            />
                        </linearGradient>
                        <linearGradient id="fillMobile" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="5%" stop-color="var(--color-mobile)" stop-opacity={0.8} />
                            <stop
                                offset="95%"
                                stop-color="var(--color-mobile)"
                                stop-opacity={0.1}
                            />
                        </linearGradient>
                    </defs>
                    <ChartClipPath
                        initialWidth={0}
                        motion={{
              width: { type: "tween", duration: 1000, easing: cubicInOut },
            }}
                    >
                        {#each series as s, i (s.key)}
                            <Area
                                {...getAreaProps(s, i)}
                                fill={s.key === "desktop"
                  ? "url(#fillDesktop)"
                  : "url(#fillMobile)"}
                            />
                        {/each}
                    </ChartClipPath>
                {/snippet}
                {#snippet tooltip()}
                    <Chart.Tooltip
                        labelFormatter={(v: Date) => {
              return v.toLocaleDateString("en-US", {
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
                    January - June 2024
                </div>
            </div>
        </div>
    </Card.Footer>
</Card.Root>
