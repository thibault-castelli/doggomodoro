<script lang="ts">
    import Play from '@lucide/svelte/icons/play';
    import Pause from '@lucide/svelte/icons/pause';
    import * as Tooltip from '../ui/tooltip';
    import { buttonVariants } from '../ui/button';
    import { getTimerContext } from '@/contexts/timerContext.svelte';

    const timer = getTimerContext();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        {#if !timer.isTimerOn}
            <Tooltip.Trigger onclick={timer.startTimer} class={buttonVariants({ variant: 'outline' })}>
                <Play />
            </Tooltip.Trigger>
            <Tooltip.Content>
                <p>Play</p>
            </Tooltip.Content>
        {:else}
            <Tooltip.Trigger onclick={timer.pauseTimer} class={buttonVariants({ variant: 'outline' })}>
                <Pause />
            </Tooltip.Trigger>
            <Tooltip.Content>
                <p>Pause</p>
            </Tooltip.Content>
        {/if}
    </Tooltip.Root>
</Tooltip.Provider>
