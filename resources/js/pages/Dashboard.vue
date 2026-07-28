<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    Activity,
    AlertTriangle,
    ArrowRight,
    BarChart3,
    CalendarDays,
    ChevronRight,
    CircleUser,
    Clock,
    Package,
    ShoppingCart,
    Sparkles,
    Shield,
    Target,
    TrendingUp,
    User,
    UserPlus,
    Users,
    DollarSign,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { useAnimatedCounter } from '@/composables/useAnimatedCounter';
import clientesRoutes from '@/routes/clientes';
import type { Cliente, User as UserType } from '@/types';

type Stats = {
    totalClientes: number;
    totalUsuarios: number;
    clientesHoy: number;
    usuariosActivos: number;
};

type ActivityItem = {
    type: string;
    message: string;
    time: string;
    created_at: string;
};

type Props = {
    stats: Stats;
    recentClientes: Cliente[];
    recentUsuarios: UserType[];
    chartLabels: string[];
    chartData: number[];
    recentActivity: ActivityItem[];
};

const props = defineProps<Props>();

const page = usePage();
const auth = page.props.auth;
const authUser = auth?.user;
const userName = authUser?.name ?? 'Usuario';

const roleLabels: Record<string, string> = {
    admin: 'Administrador',
    vendedor: 'Vendedor',
    cliente: 'Cliente',
};
const userRole = auth?.roles?.[0]
    ? roleLabels[auth.roles[0]] ?? auth.roles[0]
    : '';

const formatDate = (date: string) => {
    const d = new Date(date);
    return d.toLocaleDateString('es-MX', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};

const quickActions = [
    {
        title: 'Nueva Venta',
        href: '/ventas/create',
        icon: ShoppingCart,
        gradient: 'from-[#9333EA] to-[#7E22CE]',
        description: 'Registrar una venta',
    },
    {
        title: 'Nuevo Cliente',
        href: clientesRoutes.create(),
        icon: UserPlus,
        gradient: 'from-[#9333EA] to-[#C084FC]',
        description: 'Agregar cliente',
    },
    {
        title: 'Nuevo Producto',
        href: '/productos/create',
        icon: Package,
        gradient: 'from-[#C084FC] to-[#A78BFA]',
        description: 'Registrar producto',
    },
    {
        title: 'Reportes',
        href: '/reportes',
        icon: BarChart3,
        gradient: 'from-[#7DD3FC] to-[#C084FC]',
        description: 'Ver informes',
    },
];

const { count: totalClientesCount } = useAnimatedCounter(props.stats.totalClientes, 2000);
const { count: totalUsuariosCount } = useAnimatedCounter(props.stats.totalUsuarios, 2200);
const { count: clientesHoyCount } = useAnimatedCounter(props.stats.clientesHoy, 1800);
const { count: usuariosActivosCount } = useAnimatedCounter(props.stats.usuariosActivos, 2400);

const statCards = [
    {
        label: 'Clientes',
        count: totalClientesCount,
        icon: Users,
        gradient: 'from-[#9333EA] to-[#7E22CE]',
        accent: 'text-sky-600 dark:text-sky-400',
        chartColor: 'sky',
        description: 'Total registrados',
    },
    {
        label: 'Usuarios',
        count: totalUsuariosCount,
        icon: User,
        gradient: 'from-[#C084FC] to-[#A78BFA]',
        accent: 'text-purple-600 dark:text-purple-400',
        chartColor: 'lilac',
        description: 'En el sistema',
    },
    {
        label: 'Clientes Hoy',
        count: clientesHoyCount,
        icon: Target,
        gradient: 'from-[#7DD3FC] to-[#60A5FA]',
        accent: 'text-sky-600 dark:text-sky-400',
        chartColor: 'celeste',
        description: 'Registrados hoy',
    },
    {
        label: 'Usuarios Activos',
        count: usuariosActivosCount,
        icon: Activity,
        gradient: 'from-[#9333EA] to-[#C084FC]',
        accent: 'text-sky-600 dark:text-sky-400',
        chartColor: 'sky',
        description: 'Activos ahora',
    },
];

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: '/dashboard',
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col space-y-8 pb-8">
        <!-- Welcome Hero -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-scale-in-bounce" style="background: linear-gradient(135deg, #7DD3FC, #C084FC, #9333EA, #7E22CE)">
            <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl px-6 py-6 sm:px-8 sm:py-8">
                <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-[22px]">
                    <div class="absolute -left-20 -top-20 h-60 w-60 animate-blob rounded-full bg-[#7DD3FC]/10 blur-3xl"></div>
                    <div class="animation-delay-2000 absolute -bottom-20 -right-20 h-60 w-60 animate-blob rounded-full bg-[#C084FC]/10 blur-3xl"></div>
                    <div class="animation-delay-4000 absolute left-1/3 top-1/2 h-40 w-40 animate-blob rounded-full bg-[#7DD3FC]/8 blur-3xl"></div>
                </div>
                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-lg shadow-[#9333EA]/30">
                            <Sparkles class="h-8 w-8 text-white" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                                ¡Bienvenido, {{ userName }}!
                            </h1>
                            <div class="flex items-center gap-2 mt-1.5">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Panel de control</p>
                                <span v-if="userRole" class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-[#7DD3FC]/20 to-[#C084FC]/20 px-3 py-0.5 text-xs font-semibold text-[#C084FC] backdrop-blur-sm ring-1 ring-[#7DD3FC]/20">
                                    <Shield class="h-3 w-3" />
                                    {{ userRole }}
                                </span>
                            </div>
                        </div>
                    </div>
<div class="flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-[#7DD3FC]/10 to-[#C084FC]/10 px-4 py-2.5 backdrop-blur-sm ring-1 ring-[#7DD3FC]/15">
    <CalendarDays class="h-4 w-4 text-[#9333EA]" />
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ new Date().toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="(card, index) in statCards"
                :key="card.label"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1 animate-slide-up-fade"
                :class="'stagger-' + (index + 1)"
                :style="{ background: `linear-gradient(135deg, ${card.gradient.replace('from-', '').split(' ')[0]}, ${card.gradient.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-5 h-full">
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl shadow-lg transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl hover:-translate-y-0.5"
                            :class="'bg-gradient-to-br ' + card.gradient"
                        >
                            <component :is="card.icon" class="h-5 w-5 text-white" />
                        </div>
                        <div class="flex items-center gap-1.5 rounded-full bg-gradient-to-r from-[#7DD3FC]/10 to-[#C084FC]/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider" :class="card.accent">
                            <TrendingUp class="h-3 w-3" />
                            <span>+{{ Math.floor(Math.random() * 20) + 5 }}%</span>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ card.label }}</p>
                    <p class="text-3xl font-extrabold tracking-tight mt-0.5 tabular-nums text-gray-900 dark:text-white">
                        {{ card.count }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                        <span class="inline-block h-1.5 w-1.5 rounded-full" :class="card.accent"></span>
                        {{ card.description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <Link
                v-for="(action, index) in quickActions"
                :key="action.title"
                :href="action.href"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="'stagger-' + (index + 5)"
                :style="{ background: `linear-gradient(135deg, ${action.gradient.replace('from-', '').split(' ')[0]}, ${action.gradient.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4">
                    <div class="pointer-events-none absolute -right-12 -top-12 h-28 w-28 rounded-full opacity-0 group-hover:opacity-40 transition-all duration-700 group-hover:scale-150 blur-2xl"
                        :style="{ background: action.gradient.split(' ')[0].replace('from-', '') }">
                    </div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl shadow-md transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl hover:-translate-y-0.5"
                            :class="'bg-gradient-to-br ' + action.gradient">
                            <component :is="action.icon" class="h-5 w-5 text-white" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ action.title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ action.description }}</p>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-[#7DD3FC]/10 to-[#C084FC]/10 group-hover:from-[#7DD3FC]/20 group-hover:to-[#C084FC]/20 transition-all duration-300">
                            <ChevronRight class="h-4 w-4 text-gray-400 group-hover:text-[#7DD3FC] group-hover:translate-x-0.5 transition-all duration-200" />
                        </div>
                    </div>
                </div>
            </Link>
        </div>

        <div class="grid gap-6 lg:grid-cols-7">
            <div class="lg:col-span-4 space-y-6">
                <!-- Chart: Clientes Registrados -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-9" style="background: linear-gradient(135deg, #7DD3FC, #C084FC, #9333EA)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-[#7DD3FC]/10 blur-3xl"></div>
<div class="flex items-center justify-between px-6 py-4 border-b border-[#9333EA]/10 dark:border-gray-800">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-md">
            <BarChart3 class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Clientes Registrados</h3>
                                    <p class="text-xs text-gray-500">Últimos 7 días</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 rounded-full bg-gradient-to-r from-[#7DD3FC]/10 to-[#C084FC]/10 px-3 py-1.5 text-xs font-medium text-[#C084FC] backdrop-blur-sm">
                                <TrendingUp class="h-3.5 w-3.5" />
                                <span>Semanal</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-end justify-between gap-2 h-40">
                                <div
                                    v-for="(value, index) in chartData"
                                    :key="index"
                                    class="group/chart relative flex flex-1 flex-col items-center gap-1.5"
                                    :style="{ animationDelay: (0.1 * index) + 's' }"
                                >
                                    <div
                                        v-if="value > 0"
                                        class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs font-bold text-[#9333EA] opacity-0 group-hover/chart:opacity-100 transition-all duration-300"
                                    >
                                        {{ value }}
                                    </div>
                                    <div
                                        class="w-full rounded-lg transition-all duration-700 hover:opacity-80 cursor-pointer relative overflow-hidden animate-scale-in"
                                        :style="{
                                            height: Math.max(value * 8, 4) + 'px',
                                            animationDelay: (0.08 * index) + 's',
                                            background: value === Math.max(...chartData)
                                                ? 'linear-gradient(to top, #9333EA, #7E22CE)'
                                                : 'linear-gradient(to top, rgba(125, 211, 252,0.5), rgba(192,132,252,0.3))',
                                        }"
                                    >
                                        <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent opacity-0 group-hover/chart:opacity-100 transition-opacity pointer-events-none"></div>
                                        <div v-if="value === Math.max(...chartData)" class="absolute -top-1 left-1/2 -translate-x-1/2 h-2 w-2 rounded-full bg-[#9333EA] animate-pulse-soft"></div>
                                    </div>
                                    <span class="text-[10px] text-gray-500 font-medium mt-2">{{ chartLabels[index] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-10" style="background: linear-gradient(135deg, #7DD3FC, #9333EA)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-[#7DD3FC]/10 blur-3xl"></div>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#7DD3FC]/10 dark:border-gray-800">
                            <div class="flex items-center gap-3">
<div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-md">
    <Activity class="h-5 w-5 text-white" />
                                </div>
                                <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Actividad Reciente</h3>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute left-9 top-0 bottom-0 w-px bg-gradient-to-b from-[#9333EA] via-[#C084FC] to-transparent dark:opacity-30"></div>
                            <div
                                v-for="(activity, index) in recentActivity"
                                :key="index"
                                class="relative flex items-start gap-4 px-6 py-3.5 hover:bg-gradient-to-r hover:from-[#7DD3FC]/5 hover:to-[#C084FC]/5 transition-all duration-200"
                            >
                                <div
                                    class="relative z-10 mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl shadow-md ring-2 ring-white/50"
                                    :class="activity.type === 'cliente' ? 'bg-gradient-to-br from-[#9333EA] to-[#7E22CE]' : 'bg-gradient-to-br from-[#C084FC] to-[#A78BFA]'"
                                >
                                    <component
                                        :is="activity.type === 'cliente' ? Users : User"
                                        class="h-4 w-4 text-white"
                                    />
                                </div>
                                <div class="min-w-0 flex-1 pt-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ activity.message }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                                        <Clock class="h-3 w-3" />
                                        {{ activity.time }}
                                    </p>
                                </div>
                            </div>
                            <div
                                v-if="recentActivity.length === 0"
                                class="flex flex-col items-center px-6 py-12 text-center"
                            >
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-3">
                                    <Activity class="h-6 w-6 text-[#7DD3FC]" />
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Sin actividad reciente</p>
                                <p class="text-xs text-gray-500 mt-1">La actividad aparecerá aquí cuando haya movimiento</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <!-- Recent Clients -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-11" style="background: linear-gradient(135deg, #9333EA, #C084FC)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-[#7DD3FC]/10 blur-3xl"></div>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#7DD3FC]/10 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC] to-[#C084FC] shadow-md">
                                    <Users class="h-5 w-5 text-white" />
                                </div>
                                <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Clientes Recientes</h3>
                            </div>
                            <Button variant="ghost" size="sm" as-child class="gap-1 text-[#9333EA] hover:text-[#7E22CE] hover:bg-[#9333EA]/10">
                                <Link :href="clientesRoutes.index()">
                                    Ver todos <ArrowRight class="h-3.5 w-3.5" />
                                </Link>
                            </Button>
                        </div>
                        <div class="divide-y divide-[#7DD3FC]/10 dark:divide-gray-800">
                            <div
                                v-for="(cliente, index) in recentClientes"
                                :key="cliente.id"
                                class="flex items-center gap-4 px-6 py-3.5 hover:bg-gradient-to-r hover:from-[#7DD3FC]/5 hover:to-[#C084FC]/5 transition-all duration-200"
                            >
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#7DD3FC] to-[#C084FC] text-sm font-bold text-white shadow-md ring-2 ring-white/50"
                                >
                                    {{ (cliente.name?.charAt(0) ?? '?').toUpperCase() }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-bold text-gray-900 dark:text-white">{{ cliente.name }}</p>
                                    <p v-if="cliente.email" class="truncate text-xs text-gray-500 mt-0.5">{{ cliente.email }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 text-xs text-gray-500 bg-gradient-to-r from-[#7DD3FC]/10 to-[#C084FC]/10 px-2.5 py-1 rounded-full">
                                    <Clock class="h-3 w-3" />
                                    {{ formatDate(cliente.created_at) }}
                                </div>
                            </div>
                            <div v-if="recentClientes.length === 0" class="flex flex-col items-center px-6 py-12 text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-3">
                                    <Users class="h-6 w-6 text-[#7DD3FC]" />
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">No hay clientes registrados</p>
                                <p class="text-xs text-gray-500 mt-1">Comienza agregando tu primer cliente</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-12" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -left-10 -top-10 h-40 w-40 rounded-full bg-[#C084FC]/10 blur-3xl"></div>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#C084FC]/10 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#C084FC] to-[#A78BFA] shadow-md">
                                    <CircleUser class="h-5 w-5 text-white" />
                                </div>
                                <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Usuarios Recientes</h3>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gradient-to-r from-[#C084FC]/10 to-[#7DD3FC]/10 px-2.5 py-1 rounded-full">{{ recentUsuarios.length }} nuevos</span>
                        </div>
                        <div class="divide-y divide-[#C084FC]/10 dark:divide-gray-800">
                            <div
                                v-for="user in recentUsuarios"
                                :key="user.id"
                                class="flex items-center gap-4 px-6 py-3.5 hover:bg-gradient-to-r hover:from-[#C084FC]/5 hover:to-[#7DD3FC]/5 transition-all duration-200"
                            >
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#C084FC] to-[#A78BFA] text-sm font-bold text-white shadow-md ring-2 ring-white/50"
                                >
                                    {{ (user.name?.charAt(0) ?? '?').toUpperCase() }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-bold text-gray-900 dark:text-white">{{ user.name }}</p>
                                    <p class="truncate text-xs text-gray-500 mt-0.5">{{ user.email }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 text-xs text-gray-500 bg-gradient-to-r from-[#C084FC]/10 to-[#7DD3FC]/10 px-2.5 py-1 rounded-full">
                                    <Clock class="h-3 w-3" />
                                    {{ formatDate(user.created_at) }}
                                </div>
                            </div>
                            <div v-if="recentUsuarios.length === 0" class="flex flex-col items-center px-6 py-12 text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#C084FC]/20 to-[#7DD3FC]/10 mb-3">
                                    <CircleUser class="h-6 w-6 text-[#C084FC]" />
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">No hay usuarios registrados</p>
                                <p class="text-xs text-gray-500 mt-1">Los usuarios aparecerán aquí cuando se registren</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Alerts -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-13" style="background: linear-gradient(135deg, #9333EA, #7E22CE)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-[#7DD3FC]/15 blur-3xl"></div>
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-[#7DD3FC]/10 dark:border-gray-800">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-md">
                                <AlertTriangle class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Alertas de Inventario</h3>
                                <p class="text-xs text-[#9333EA] dark:text-[#9333EA]">Productos con stock bajo</p>
                            </div>
                        </div>
                        <div class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#7DD3FC]/10">
                                    <Package class="h-6 w-6 text-[#7DD3FC]" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Módulo de inventario próximamente</p>
                                    <p class="text-xs text-gray-500 mt-1">Estamos trabajando en esta funcionalidad</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

