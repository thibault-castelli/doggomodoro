<script lang="ts">
    import Square from '@lucide/svelte/icons/square';
    import Rotate from '@lucide/svelte/icons/rotate-ccw';
    import * as Tooltip from '../ui/tooltip';
    import { buttonVariants } from '../ui/button';
    import { getTimerContext } from '@/contexts/timerContext.svelte';

    const timer = getTimerContext();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        {#if timer.isTimerOn}
            <Tooltip.Trigger onclick={timer.stopTimer} class={buttonVariants({ variant: 'outline' })}>
                <Square />
            </Tooltip.Trigger>
            <Tooltip.Content>
                <p>Stop</p>
            </Tooltip.Content>
        {:else}
            <Tooltip.Trigger onclick={timer.resetSession} class={buttonVariants({ variant: 'outline' })}>
                <Rotate />
            </Tooltip.Trigger>
            <Tooltip.Content>
                <p>Reload</p>
            </Tooltip.Content>
        {/if}
    </Tooltip.Root>
</Tooltip.Provider>
