<script lang="ts">
    import { Trash } from 'lucide-svelte';
    import Button, { buttonVariants } from './ui/button/button.svelte';
    import * as Dialog from './ui/dialog';
    import * as Tooltip from './ui/tooltip';

    interface Props {
        title: string;
        action: () => void;
        description?: string;
        tooltipText?: string;
    }

    let { title, action, tooltipText = 'Delete', description = 'This action cannot be undone.' }: Props = $props();
</script>

<Dialog.Root>
    <Tooltip.Provider>
        <Tooltip.Root>
            <Tooltip.Trigger>
                <Dialog.Trigger class={buttonVariants({ variant: 'destructive', size: 'lg' })}><Trash /></Dialog.Trigger>
            </Tooltip.Trigger>
            <Tooltip.Content side="bottom">{tooltipText}</Tooltip.Content>
        </Tooltip.Root>
    </Tooltip.Provider>

    <Dialog.Content class="sm:max-w-[425px]">
        <Dialog.Header>
            <Dialog.Title>{title}</Dialog.Title>
            <Dialog.Description>{description}</Dialog.Description>
        </Dialog.Header>
        <Dialog.Footer>
            <Dialog.Close>
                <Button>Cancel</Button>
            </Dialog.Close>
            <Dialog.Close>
                <Button variant="destructive" onclick={action}>Delete</Button>
            </Dialog.Close>
        </Dialog.Footer>
    </Dialog.Content>
</Dialog.Root>
