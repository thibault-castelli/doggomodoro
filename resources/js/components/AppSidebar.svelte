<script lang="ts">
    import NavFooter from '@/components/NavFooter.svelte';
    import NavMain from '@/components/NavMain.svelte';
    import NavUser from '@/components/NavUser.svelte';
    import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
    import { type NavItem } from '@/types';
    import { Link } from '@inertiajs/svelte';
    import { Github, LayoutGrid, Clock, Settings2 } from 'lucide-svelte';
    import AppLogo from './AppLogo.svelte';

    const mainNavItems: NavItem[] = [
        {
            title: 'Timer',
            href: '/timer',
            icon: Clock,
        },
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
        },
        {
            title: 'Presets',
            href: '/presets',
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
        <NavMain items={mainNavItems} />
    </SidebarContent>

    <SidebarFooter>
        <NavFooter items={footerNavItems} class="mt-auto" />
        <NavUser />
    </SidebarFooter>
</Sidebar>
