<script lang="ts">
    import AppLogo from '@/components/AppLogo.svelte';
    import { Link } from '@inertiajs/svelte';
    import type { Snippet } from 'svelte';

    interface Props {
        title: string;
        description?: string;
        children?: Snippet;
    }

    let { title, description, children }: Props = $props();
</script>

<div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
    <div class="w-full max-w-sm">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col items-center gap-4">
                <Link href={route('timer')} class="flex flex-col items-center gap-2 font-medium">
                    <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                        <AppLogo />
                    </div>
                    <span class="sr-only">{title}</span>
                </Link>
                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-medium">{title}</h1>
                    {#if description}
                        <p class="text-center text-sm text-muted-foreground">{description}</p>
                    {/if}
                </div>
            </div>
            {@render children?.()}
        </div>
    </div>
</div>
