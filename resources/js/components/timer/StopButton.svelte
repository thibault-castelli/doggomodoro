<script lang="ts">
    import Square from '@lucide/svelte/icons/square';
    import Rotate from '@lucide/svelte/icons/rotate-ccw';
    import * as Tooltip from '../ui/tooltip';
    import { buttonVariants } from '../ui/button';

    let { timer } = $props();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        <Tooltip.Trigger onclick={timer.stopTimer} class={`${buttonVariants({ variant: 'outline', size: 'sm' })} p-5`}>
            <Square class="size-5" />
        </Tooltip.Trigger>
        <Tooltip.Content side="bottom">
            <p>Reset Round</p>
        </Tooltip.Content>
    </Tooltip.Root>
</Tooltip.Provider>
