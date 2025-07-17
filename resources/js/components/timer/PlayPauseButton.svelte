<script lang="ts">
    import Play from '@lucide/svelte/icons/play';
    import Pause from '@lucide/svelte/icons/pause';
    import * as Tooltip from '../ui/tooltip';
    import { buttonVariants } from '../ui/button';

    let { timer } = $props();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        {#if !timer.isTimerOn}
            <Tooltip.Trigger onclick={timer.startTimer} class={`${buttonVariants({ variant: 'default', size: 'lg' })} size-24 py-8`}>
                <Play class="size-8" />
            </Tooltip.Trigger>
            <Tooltip.Content side="bottom">
                <p>Play</p>
            </Tooltip.Content>
        {:else}
            <Tooltip.Trigger onclick={timer.pauseTimer} class={`${buttonVariants({ variant: 'default', size: 'lg' })} size-24 py-8`}>
                <Pause class="size-8" />
            </Tooltip.Trigger>
            <Tooltip.Content side="bottom">
                <p>Pause</p>
            </Tooltip.Content>
        {/if}
    </Tooltip.Root>
</Tooltip.Provider>
