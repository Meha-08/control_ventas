import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export type BreadcrumbItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
};

export type NavItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    iconColor?: string;
    permission?: string;
    badge?: number | string;
    badgeColor?: string;
    children?: NavItem[];
};

export type NavGroup = {
    title: string;
    items: NavItem[];
    gradient?: string;
    icon?: LucideIcon;
};
