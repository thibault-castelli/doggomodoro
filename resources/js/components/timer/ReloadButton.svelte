<script lang="ts">
    import Rotate from '@lucide/svelte/icons/rotate-ccw';
    import * as Tooltip from '../ui/tooltip';
    import { buttonVariants } from '../ui/button';

    let { timer } = $props();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        <Tooltip.Trigger onclick={timer.resetSession} class={`${buttonVariants({ variant: 'outline', size: 'sm' })} p-5`}>
            <Rotate class="size-5" />
        </Tooltip.Trigger>
        <Tooltip.Content side="bottom">
            <p>Reload Session</p>
        </Tooltip.Content>
    </Tooltip.Root>
</Tooltip.Provider>
