<script lang="ts">
	import { Calendar as CalendarPrimitive } from "bits-ui";
	import * as Calendar from "./index.js";
	import { cn, type WithoutChildrenOrChild } from "@/lib/utils.js";
    import { Dog } from 'lucide-svelte';

	let {
		ref = $bindable(null),
		value = $bindable(),
		placeholder = $bindable(),
		class: className,
		weekdayFormat = "short",
        highlightDates = [],
        footerText = "",
		...restProps
	} = $props();
</script>

<!--
Discriminated Unions + Destructing (required for bindable) do not
get along, so we shut typescript up by casting `value` to `never`.
-->
<CalendarPrimitive.Root
	bind:value={value as never}
	bind:ref
	bind:placeholder
	{weekdayFormat}
	class={cn("p-3", className)}
	{...restProps}
>
	{#snippet children({ months, weekdays })}
		<Calendar.Header>
			<Calendar.PrevButton />
			<Calendar.Heading />
			<Calendar.NextButton />
		</Calendar.Header>
		<Calendar.Months>
			{#each months as month (month)}
				<Calendar.Grid>
					<Calendar.GridHead>
						<Calendar.GridRow class="flex">
							{#each weekdays as weekday (weekday)}
								<Calendar.HeadCell>
									{weekday.slice(0, 2)}
								</Calendar.HeadCell>
							{/each}
						</Calendar.GridRow>
					</Calendar.GridHead>
					<Calendar.GridBody>
						{#each month.weeks as weekDates (weekDates)}
							<Calendar.GridRow class="mt-2 w-full">
								{#each weekDates as date (date)}
									<Calendar.Cell {date} month={month.value}>
                                        {#if highlightDates.find(highlightDate => highlightDate.year === date.year && highlightDate.month === date.month && highlightDate.day === date.day)}
                                            <div class="w-full h-full flex justify-center items-center">
                                                <Dog />
                                            </div>
                                        {:else}
                                            <Calendar.Day />
                                        {/if}
									</Calendar.Cell>
								{/each}
							</Calendar.GridRow>
						{/each}
					</Calendar.GridBody>
				</Calendar.Grid>
			{/each}
		</Calendar.Months>
        <p class="text-center mt-5 text-sm text-muted-foreground">{footerText}</p>
	{/snippet}
</CalendarPrimitive.Root>
