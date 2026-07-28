<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { CircleUserRound } from '@lucide/vue';
import {
    LayoutGrid,
    Users,
    Package,
    ShoppingCart,
    BookOpen,
    BarChart3,
    Tags,
    LifeBuoy,
    UserPlus,
    ClipboardList,
    Shield,
    ShoppingBag,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { usePermissions } from '@/composables/usePermissions';

import type { NavGroup, NavItem } from '@/types';

const { can } = usePermissions();

const dashboardUrl = computed(() => '/dashboard');

const allNavGroups = computed<NavGroup[]>(() => [
    {
        title: 'Principal',
        gradient: 'from-sky-400 to-sky-500',
        sectionColor: 'celeste',
        items: [
            {
                title: 'Dashboard',
                href: dashboardUrl.value,
                icon: LayoutGrid,
                iconColor: 'text-sky-400',
                permission: 'panel.ver',
            },
        ],
    },
    {
        title: 'Ventas',
        gradient: 'from-sky-400 to-cyan-500',
        sectionColor: 'celeste',
        items: [
            {
                title: 'Nueva Venta',
                href: '/ventas/create',
                icon: ShoppingCart,
                iconColor: 'text-sky-400',
                permission: 'ventas.gestionar',
            },
            {
                title: 'Historial',
                href: '/ventas',
                icon: ClipboardList,
                iconColor: 'text-sky-400',
                permission: 'ventas.ver',
            },
        ],
    },
    {
        title: 'Catálogo',
        gradient: 'from-purple-400 to-violet-500',
        sectionColor: 'lilac',
        items: [
            {
                title: 'Catálogo Público',
                href: '/menu',
                icon: ShoppingBag,
                iconColor: 'text-purple-400',
            },
            {
                title: 'Productos',
                href: '/descanso-productos',
                icon: Package,
                iconColor: 'text-purple-400',
                permission: 'productos.ver',
            },
            {
                title: 'Categorías',
                href: '/categorias',
                icon: Tags,
                iconColor: 'text-purple-400',
                permission: 'categorias.ver',
            },
        ],
    },
    {
        title: 'Clientes',
        gradient: 'from-blue-400 to-indigo-500',
        sectionColor: 'celeste',
        items: [
            {
                title: 'Clientes',
                href: '/clientes',
                icon: Users,
                iconColor: 'text-blue-400',
                permission: 'clientes.ver',
            },
        ],
    },
    {
        title: 'Reportes',
        gradient: 'from-sky-400 to-sky-500',
        sectionColor: 'celeste',
        items: [
            {
                title: 'Reportes',
                href: '/reportes',
                icon: BarChart3,
                iconColor: 'text-sky-400',
                permission: 'reportes.ver',
            },
        ],
    },
    {
        title: 'Administración',
        gradient: 'from-sky-400 to-purple-500',
        sectionColor: 'celeste',
        items: [
            {
                title: 'Usuarios',
                href: '/usuarios',
                icon: UserPlus,
                iconColor: 'text-sky-400',
                permission: 'usuarios.gestionar',
            },
            {
                title: 'Roles',
                href: '/roles',
                icon: Shield,
                iconColor: 'text-sky-400',
                permission: 'usuarios.gestionar',
            },
        ],
    },
]);

const mainNavItems = computed<NavGroup[]>(() =>
    allNavGroups.value
        .map((group) => ({
            ...group,
            items: group.items.filter((item) => !item.permission || can(item.permission)),
        }))
        .filter((group) => group.items.length > 0),
);

const footerNavItems: NavItem[] = [
    {
        title: 'Soporte',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: LifeBuoy,
    },
    {
        title: 'Documentación',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="border-r border-sidebar-border/50">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-[#7DD3FC]/20 via-[#C084FC]/12 to-[#9333EA]/20"></div>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-[#7DD3FC]/10 via-transparent to-[#7DD3FC]/12"></div>

        <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 animate-sidebar-glow rounded-full bg-[#9333EA]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-16 h-48 w-48 animate-sidebar-glow rounded-full bg-[#C084FC]/15 blur-3xl" style="animation-delay: 2s"></div>
        <div class="pointer-events-none absolute left-1/2 top-1/3 h-32 w-32 animate-sidebar-glow rounded-full bg-[#9333EA]/12 blur-3xl" style="animation-delay: 4s"></div>

        <div class="pointer-events-none absolute inset-x-0 top-0 h-px animate-sidebar-shimmer bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>

        <div class="pointer-events-none absolute bottom-24 left-4 h-1.5 w-1.5 animate-sidebar-float rounded-full bg-[#7DD3FC]/40"></div>
        <div class="pointer-events-none absolute bottom-32 right-8 h-1 w-1 animate-sidebar-float rounded-full bg-[#C084FC]/40" style="animation-delay: 1.5s"></div>
        <div class="pointer-events-none absolute bottom-40 left-12 h-1 w-1 animate-sidebar-float rounded-full bg-[#7DD3FC]/40" style="animation-delay: 3s"></div>

        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboardUrl">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>

<style scoped>
@keyframes sidebar-glow {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.15); }
}

@keyframes sidebar-shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes sidebar-float {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.3; }
    50% { transform: translateY(-12px) scale(1.5); opacity: 0.8; }
}

.animate-sidebar-glow { animation: sidebar-glow 4s ease-in-out infinite; }
.animate-sidebar-shimmer { animation: sidebar-shimmer 3s ease-in-out infinite; }
.animate-sidebar-float { animation: sidebar-float 3s ease-in-out infinite; }
</style>

