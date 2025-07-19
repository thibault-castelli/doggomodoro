<script lang="ts">
    import { Toaster } from '@/components/ui/sonner';
    import AppLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import type { BreadcrumbItemType } from '@/types';
    import type { Snippet } from 'svelte';
    import { page } from '@inertiajs/svelte';
    import { toast } from 'svelte-sonner';

    interface Props {
        title?: string;
        breadcrumbs?: BreadcrumbItemType[];
        children?: Snippet;
    }

    let { title = '', breadcrumbs = [], children }: Props = $props();

    let successFlashMessage = $derived($page.props.flash.success);
    let errorFlashMessage = $derived($page.props.flash.error);
    $effect(() => {
        if (successFlashMessage) {
            toast.success(successFlashMessage);
            successFlashMessage = '';
        } else if (errorFlashMessage) {
            toast.error(errorFlashMessage, { duration: 5000 });
            errorFlashMessage = '';
        }
    });
</script>

<svelte:head>
    <title>{title ? title + ' | Doggomodoro' : 'Doggomodoro'}</title>
</svelte:head>

<AppLayout {breadcrumbs}>
    <Toaster richColors closeButton position="bottom-right" />
    {@render children?.()}
</AppLayout>
