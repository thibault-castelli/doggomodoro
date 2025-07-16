<script lang="ts">
    import AppLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import type { BreadcrumbItemType } from '@/types';
    import type { Snippet } from 'svelte';

    interface Props {
        title?: string;
        breadcrumbs?: BreadcrumbItemType[];
        children?: Snippet;
    }

    let { title = '', breadcrumbs = [], children }: Props = $props();
</script>

<svelte:head>
    <title>{title ? title + ' | Doggomodoro' : 'Doggomodoro'}</title>
</svelte:head>

<AppLayout {breadcrumbs}>
    {@render children?.()}
</AppLayout>
