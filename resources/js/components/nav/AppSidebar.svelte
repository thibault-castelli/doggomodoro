<script lang="ts">
    import NavFooter from '@/components/nav/NavFooter.svelte';
    import NavMain from '@/components/nav/NavMain.svelte';
    import NavUser from '@/components/nav/NavUser.svelte';
    import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
    import { type NavItem } from '@/types';
    import { Link } from '@inertiajs/svelte';
    import { Github, LayoutGrid, Clock, Settings2, User, BookCheck } from 'lucide-svelte';
    import AppLogo from '../icons/AppLogo.svelte';
    import { page } from '@inertiajs/svelte';

    const user = $derived($page.props.auth.user);

    const mainNavItemsGuest: NavItem[] = [
        {
            title: 'Timer',
            href: route('timer'),
            icon: Clock,
        },
        {
            title: 'Login',
            href: route('login'),
            icon: User,
        },
        {
            title: 'Register',
            href: route('register'),
            icon: BookCheck,
        },
    ];

    const mainNavItems: NavItem[] = [
        {
            title: 'Timer',
            href: route('timer'),
            icon: Clock,
        },
        {
            title: 'Dashboard',
            href: route('dashboard'),
            icon: LayoutGrid,
        },
        {
            title: 'Presets',
            href: route('presets'),
            icon: Settings2,
        },
    ];

    const footerNavItems: NavItem[] = [
        {
            title: 'Made by codeintheshell',
            href: 'https://github.com/thibault-castelli/doggomodoro',
            icon: Github,
        },
    ];
</script>

<Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton size="lg">
                    <Link href={route('timer')}>
                        <AppLogo />
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
        <NavMain items={user ? mainNavItems : mainNavItemsGuest} />
    </SidebarContent>

    <SidebarFooter>
        <NavFooter items={footerNavItems} class="mt-auto" />

        {#if user}
            <NavUser />
        {/if}
    </SidebarFooter>
</Sidebar>
