<script lang="ts">
    import * as Tooltip from './ui/tooltip';
    import { buttonVariants } from './ui/button';
    import type { Snippet } from 'svelte';

    interface Props {
        action: () => void;
        tooltipText: string;
        children?: Snippet;
    }

    let { action, tooltipText, children }: Props = $props();
</script>

<Tooltip.Provider>
    <Tooltip.Root>
        <Tooltip.Trigger onclick={action} class={`${buttonVariants({ variant: 'default', size: 'lg' })}`}>
            {@render children?.()}
        </Tooltip.Trigger>
        <Tooltip.Content side="bottom">
            {tooltipText}
        </Tooltip.Content>
    </Tooltip.Root>
</Tooltip.Provider>
