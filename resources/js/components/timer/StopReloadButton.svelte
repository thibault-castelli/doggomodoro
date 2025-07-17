<script lang="ts">
    import Square from '@lucide/svelte/icons/square';
    import Rotate from '@lucide/svelte/icons/rotate-ccw';
    import * as Tooltip from '../ui/tooltip';
    import { buttonVariants } from '../ui/button';

    let { timer } = $props();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        {#if timer.isTimerOn}
            <Tooltip.Trigger onclick={timer.stopTimer} class={`${buttonVariants({ variant: 'default', size: 'lg' })} size-24 py-8`}>
                <Square class="size-8" />
            </Tooltip.Trigger>
            <Tooltip.Content side="bottom">
                <p>Stop</p>
            </Tooltip.Content>
        {:else}
            <Tooltip.Trigger onclick={timer.resetSession} class={`${buttonVariants({ variant: 'default', size: 'lg' })} size-24 py-8`}>
                <Rotate class="size-8" />
            </Tooltip.Trigger>
            <Tooltip.Content side="bottom">
                <p>Reload</p>
            </Tooltip.Content>
        {/if}
    </Tooltip.Root>
</Tooltip.Provider>
