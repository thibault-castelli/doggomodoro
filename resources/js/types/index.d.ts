import '@inertiajs/svelte';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: any;
    isActive?: boolean;
}

export interface Flash {
    success?: string;
    error?: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    [key: string]: unknown;
    ziggy: Config & { location: string };
    flash: Flash;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface UserTimerPreset {
    id: number;
    user_id: number;
    name: string;
    work_duration: number;
    break_duration: number;
    long_break_duration: number;
    long_break_interval: number;
    auto_play: boolean;
    notifications: boolean;
}

export interface UserTimerStats {
    id: number;
    user_id: number;
    total_work_time: number;
    total_break_time: number;
    total_rounds_completed: number;
    total_sessions_completed: number;
}

export interface DailySessionCount {
    id: number;
    user_id: number;
    sessions_completed: number;
    created_at: Date;
}

export type BreadcrumbItemType = BreadcrumbItem;
