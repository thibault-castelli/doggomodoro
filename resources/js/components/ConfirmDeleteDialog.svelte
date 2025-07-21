<script lang="ts">
    import { Trash } from 'lucide-svelte';
    import Button, { buttonVariants } from './ui/button/button.svelte';
    import * as Dialog from './ui/dialog';

    interface Props {
        title: string;
        description?: string;
        action: () => void;
    }

    let { title = 'Delete', description = 'This action cannot be undone.', action }: Props = $props();
</script>

<Dialog.Root>
    <Dialog.Trigger class={buttonVariants({ variant: 'destructive', size: 'lg' })}><Trash /></Dialog.Trigger>
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
