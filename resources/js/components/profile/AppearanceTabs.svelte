<script lang="ts">
    import type { Appearance } from '@/hooks/useAppearance.svelte';
    import { useAppearance } from '@/hooks/useAppearance.svelte';
    import { Monitor, Moon, Sun } from 'lucide-svelte';

    interface Props {
        class?: string;
    }

    const appearanceManager = useAppearance();
    // Create a local reactive variable that tracks the hook's value
    let currentAppearance = $state(appearanceManager.appearance);

    // Update the local state when the appearance changes
    function handleUpdateAppearance(value: Appearance) {
        appearanceManager.updateAppearance(value);
        // Immediately update local state to ensure UI reflects the change
        currentAppearance = value;
    }

    const tabs = [
        { value: 'light', Icon: Sun, label: 'Light' },
        { value: 'dark', Icon: Moon, label: 'Dark' },
        { value: 'system', Icon: Monitor, label: 'System' },
    ] as const;

    let { class: className }: Props = $props();
</script>

<div class="inline-flex flex-col gap-1 bg-transparent w-full {className}">
    {#each tabs as { value, Icon, label } (value)}
        <button
            onclick={() => handleUpdateAppearance(value)}
            class="flex items-center rounded-md px-3.5 py-1.5 transition-colors
            {currentAppearance === value
                ? 'bg-primary shadow-xs text-background'
                : 'text-muted-foreground hover:bg-secondary hover:text-primary  '}"
        >
            <Icon class="-ml-1 h-5 w-5" />
            <span class="ml-1.5">{label}</span>
        </button>
    {/each}
</div>
