<script lang="ts">
    import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
    import type { User } from '@/types';
    import {UserRound} from '@lucide/svelte';

    interface Props {
        user: User;
        showEmail?: boolean;
    }

    let { user, showEmail = false }: Props = $props();

    let showAvatar = $derived(user.avatar && user.avatar !== '');
</script>

<Avatar class="h-8 w-8 overflow-hidden rounded-full">
    {#if showAvatar}
        <AvatarImage src={user.avatar} alt={user.name} />
    {:else}
        <AvatarFallback class="rounded-lg p-1.5">
            <UserRound />
        </AvatarFallback>
    {/if}
</Avatar>

<div class="grid flex-1 text-left text-sm leading-tight">
    <span class="truncate font-medium">{user.name}</span>

    {#if showEmail}
        <span class="truncate text-xs text-muted-foreground">{user.email}</span>
    {/if}
</div>
