<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, FileText, MapPin, Pencil, Plus, Search, Shield, ShieldAlert, ShieldCheck, ToggleLeft, ToggleRight, Trash2, User, UserCheck, Users, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { usePermissions } from '@/composables/usePermissions';

type UserItem = {
    id: number;
    name: string;
    nombre_u: string;
    a_paterno: string;
    a_materno: string | null;
    email: string;
    email_verified_at: string | null;
    active: boolean;
    role: string | null;
    telefono: string | null;
    direccion: string | null;
    ci: string | null;
    fecha_ingreso: string | null;
    fecha_fin: string | null;
    created_at: string;
};

type UserStats = {
    admins: number;
    vendedores: number;
    clientes: number;
};

type Props = {
    users: {
        data: UserItem[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number | null;
        to: number | null;
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: {
        search?: string;
        active?: string;
    };
    userStats?: UserStats;
};

const props = defineProps<Props>();
const { can } = usePermissions();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Usuarios',
                href: '/usuarios',
            },
        ],
    },
});

const search = ref(props.filters.search ?? '');
const selectedUser = ref<UserItem | null>(null);

const searchUsers = () => {
    router.get('/usuarios', { search: search.value || undefined, active: props.filters.active }, {
        preserveState: true,
        replace: true,
    });
};

const clearSearch = () => {
    search.value = '';
    router.get('/usuarios', { active: props.filters.active }, {
        preserveState: true,
        replace: true,
    });
};

const toggleFilter = (value: string | undefined) => {
    router.get('/usuarios', { search: search.value || undefined, active: value }, {
        preserveState: true,
        replace: true,
    });
};

const confirmToggle = (user: UserItem) => {
    const action = user.active ? 'desactivar' : 'activar';
    if (confirm(`¿${action === 'desactivar' ? 'Desactivar' : 'Activar'} al usuario "${user.name}"?`)) {
        router.patch(`/usuarios/${user.id}/toggle-active`, {}, {
            preserveScroll: true,
        });
    }
};

const confirmDelete = (user: UserItem) => {
    if (confirm(`¿Eliminar al usuario "${user.name}"? Esta acción no se puede deshacer.`)) {
        router.delete(`/usuarios/${user.id}`, {
            preserveScroll: true,
        });
    }
};

const roleLabels: Record<string, string> = {
    admin: 'Administrador',
    vendedor: 'Vendedor',
    cliente: 'Cliente',
};

const roleBadgeVariant = (role: string | null): 'default' | 'secondary' | 'destructive' | 'outline' => {
    const variants: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
        admin: 'destructive',
        vendedor: 'default',
        cliente: 'secondary',
    };
    return variants[role ?? ''] ?? 'outline';
};

const statCards = [
    { label: 'Administradores', key: 'admins' as const, icon: Shield, gradient: 'from-[#7DD3FC] to-red-500' },
    { label: 'Vendedores', key: 'vendedores' as const, icon: UserCheck, gradient: 'from-[#7DD3FC] to-[#60A5FA]' },
    { label: 'Clientes', key: 'clientes' as const, icon: Users, gradient: 'from-[#C084FC] to-[#A78BFA]' },
];
</script>

<template>
    <Head title="Usuarios" />

    <div class="flex flex-col space-y-6 pb-8">
        <!-- Hero -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-scale-in-bounce" style="background: linear-gradient(135deg, #7DD3FC, #C084FC, #9333EA, #7E22CE)">
            <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl px-6 py-5 sm:px-8 sm:py-6">
                <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-[22px]">
                    <div class="absolute -left-20 -top-20 h-60 w-60 animate-blob rounded-full bg-[#7DD3FC]/15 blur-3xl"></div>
                    <div class="animation-delay-2000 absolute -bottom-20 -right-20 h-60 w-60 animate-blob rounded-full bg-[#C084FC]/15 blur-3xl"></div>
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-4">
<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-lg shadow-[#9333EA]/30">
                            <Users class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Usuarios</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Gestiona los usuarios del sistema</p>
                        </div>
                    </div>
<Button v-if="can('usuarios.gestionar')" as-child class="shrink-0 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30 hover:shadow-xl hover:shadow-[#9333EA]/40 transition-all duration-300 hover:-translate-y-0.5">
                        <Link :href="'/usuarios/create'">
                            <Plus class="h-4 w-4" /> Nuevo usuario
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div
                v-for="stat in statCards"
                :key="stat.key"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1 animate-slide-up-fade"
                :class="'stagger-' + (statCards.indexOf(stat) + 1)"
                :style="{ background: `linear-gradient(135deg, ${stat.gradient.split(' ')[0].replace('from-', '')}, ${stat.gradient.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl shadow-lg transition-all duration-500 group-hover:scale-110"
                            :class="'bg-gradient-to-br ' + stat.gradient">
                            <component :is="stat.icon" class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">{{ stat.label }}</p>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white tabular-nums">{{ userStats?.[stat.key] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-4" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
            <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4 sm:p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#C084FC]" />
                        <Input
                            v-model="search"
                            placeholder="Buscar usuarios..."
                            class="pl-10 pr-10 h-11 rounded-xl border-[#C084FC]/20 focus:border-[#C084FC] focus:ring-[#C084FC]/20 bg-white/80 dark:bg-gray-800/80"
                            @keyup.enter="searchUsers"
                        />
                        <button
                            v-if="search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#C084FC] transition-colors"
                            @click="clearSearch"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <Button size="sm" class="h-11 rounded-xl bg-gradient-to-r from-[#C084FC] to-[#A78BFA] text-white shadow-md shadow-[#C084FC]/20" @click="searchUsers">
                        Buscar
                    </Button>
                    <div class="flex gap-1 bg-white/50 dark:bg-gray-800/50 rounded-xl p-1">
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg text-xs"
                            :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#7DD3FC]/20 text-[#C084FC] font-semibold shadow-sm': !filters.active }"
                            @click="toggleFilter(undefined)"
                        >
                            Todos
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg text-xs"
                            :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#7DD3FC]/20 text-[#C084FC] font-semibold shadow-sm': filters.active === '1' }"
                            @click="toggleFilter('1')"
                        >
                            Activos
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg text-xs"
                            :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#7DD3FC]/20 text-[#C084FC] font-semibold shadow-sm': filters.active === '0' }"
                            @click="toggleFilter('0')"
                        >
                            Inactivos
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="users.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#C084FC]/20 to-[#7DD3FC]/10 mb-4">
                <User class="h-8 w-8 text-[#C084FC]" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay usuarios</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm">
                No se encontraron usuarios con los criterios de búsqueda.
            </p>
<Button v-if="can('usuarios.gestionar')" as-child class="mt-4 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30">
                <Link :href="'/usuarios/create'">
                    <Plus /> Agregar usuario
                </Link>
            </Button>
        </div>

        <!-- Users Table -->
        <div v-else class="overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-5" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
            <div class="rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#C084FC]/10 dark:border-gray-800">
                                <th class="text-left font-semibold px-5 py-4 text-xs uppercase tracking-wider text-[#C084FC]">Nombre</th>
                                <th class="hidden md:table-cell text-left font-semibold px-5 py-4 text-xs uppercase tracking-wider text-[#C084FC]">Email</th>
                                <th class="hidden md:table-cell text-left font-semibold px-5 py-4 text-xs uppercase tracking-wider text-[#C084FC]">Teléfono</th>
                                <th class="text-left font-semibold px-5 py-4 text-xs uppercase tracking-wider text-[#C084FC]">Rol</th>
                                <th class="text-left font-semibold px-5 py-4 text-xs uppercase tracking-wider text-[#C084FC]">Estado</th>
                                <th class="text-right font-semibold px-5 py-4 text-xs uppercase tracking-wider text-[#C084FC]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            <tr
                                v-for="user in users.data"
                                :key="user.id"
                                :class="`transition-all hover:bg-gradient-to-r hover:from-[#C084FC]/5 hover:to-[#7DD3FC]/5 ${!user.active ? 'opacity-60' : ''}`"
                            >
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white shadow-md ring-2 ring-white/50"
                                            :class="user.active ? 'bg-gradient-to-br from-[#7DD3FC] to-[#C084FC]' : 'bg-gradient-to-br from-gray-400 to-gray-500'">
                                            {{ user.name?.charAt(0)?.toUpperCase() ?? '?' }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate max-w-[180px]">{{ user.name }}</p>
                                            <p class="text-xs text-gray-500 truncate max-w-[180px]">
                                                {{ [user.nombre_u, user.a_paterno, user.a_materno].filter(Boolean).join(' ') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-5 py-3.5 text-gray-500">{{ user.email }}</td>
                                <td class="hidden md:table-cell px-5 py-3.5 text-gray-500">{{ user.telefono ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <Badge :variant="roleBadgeVariant(user.role)" class="rounded-lg">
                                        {{ roleLabels[user.role ?? ''] ?? 'Sin rol' }}
                                    </Badge>
                                </td>
                                <td class="px-5 py-3.5">
                                    <Badge :variant="user.active ? 'default' : 'outline'" class="rounded-lg gap-1">
                                        <ShieldCheck v-if="user.active" class="h-3 w-3" />
                                        <ShieldAlert v-else class="h-3 w-3" />
                                        {{ user.active ? 'Activo' : 'Inactivo' }}
                                    </Badge>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div v-if="can('usuarios.gestionar')" class="flex items-center justify-end gap-1">
                                        <Button variant="ghost" size="icon" class="h-9 w-9 rounded-xl text-gray-500 hover:text-[#C084FC] hover:bg-[#C084FC]/10" @click="selectedUser = user">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                        <Button variant="ghost" size="icon" class="h-9 w-9 rounded-xl text-gray-500 hover:text-[#C084FC] hover:bg-[#C084FC]/10" as-child>
                                            <Link :href="`/usuarios/${user.id}/edit`">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-9 w-9 rounded-xl"
                                            :class="user.active ? 'text-amber-500 hover:text-amber-600 hover:bg-amber-50' : 'text-[#C084FC] hover:text-[#A78BFA] hover:bg-[#C084FC]/10'"
                                            @click="confirmToggle(user)"
                                        >
                                            <component :is="user.active ? ToggleRight : ToggleLeft" class="h-4 w-4" />
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-9 w-9 rounded-xl text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30"
                                            @click="confirmDelete(user)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- View User Dialog -->
        <Dialog :open="!!selectedUser" @update:open="(v: boolean) => { if (!v) selectedUser = null }">
            <DialogContent class="sm:max-w-[500px]">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2 text-xl">
                        <User class="h-5 w-5 text-[#C084FC]" />
                        Datos del usuario
                    </DialogTitle>
                </DialogHeader>
                <div v-if="selectedUser" class="space-y-4">
                    <div class="flex items-center gap-4 pb-3 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl text-lg font-bold text-white shadow-md"
                            :class="selectedUser.active ? 'bg-gradient-to-br from-[#7DD3FC] to-[#C084FC]' : 'bg-gradient-to-br from-gray-400 to-gray-500'">
                            {{ selectedUser.name?.charAt(0)?.toUpperCase() ?? '?' }}
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ selectedUser.name }}</p>
                            <p class="text-sm text-gray-500">{{ [selectedUser.nombre_u, selectedUser.a_paterno, selectedUser.a_materno].filter(Boolean).join(' ') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white break-all">{{ selectedUser.email }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ selectedUser.telefono ?? '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">CI</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ selectedUser.ci ?? '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Dirección</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ selectedUser.direccion ?? '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rol</p>
                            <Badge :variant="roleBadgeVariant(selectedUser.role)" class="rounded-lg mt-1">
                                {{ roleLabels[selectedUser.role ?? ''] ?? 'Sin rol' }}
                            </Badge>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Estado</p>
                            <Badge :variant="selectedUser.active ? 'default' : 'outline'" class="rounded-lg gap-1 mt-1">
                                <ShieldCheck v-if="selectedUser.active" class="h-3 w-3" />
                                <ShieldAlert v-else class="h-3 w-3" />
                                {{ selectedUser.active ? 'Activo' : 'Inactivo' }}
                            </Badge>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Fecha Ingreso</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ selectedUser.fecha_ingreso ?? '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Fecha Fin</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ selectedUser.fecha_fin ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <DialogClose as-child>
                        <Button variant="outline" class="rounded-xl">Cerrar</Button>
                    </DialogClose>
                </div>
            </DialogContent>
        </Dialog>

        <!-- Pagination -->
        <div
            v-if="users.data.length > 0 && users.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
        >
            <p class="text-sm text-gray-500">
                Mostrando {{ users.from }}–{{ users.to }} de {{ users.total }}
            </p>
            <div class="flex items-center gap-1">
                <Button
                    v-for="link in users.links"
                    :key="link.label"
                    variant="ghost"
                    size="sm"
                    :disabled="!link.url"
                    :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#7DD3FC]/20 font-medium text-[#C084FC]': link.active }"
                    as-child
                >
                    <Link v-if="link.url" :href="link.url" preserve-scroll>
                        <span v-html="link.label" />
                    </Link>
                    <span v-else v-html="link.label" />
                </Button>
            </div>
        </div>
    </div>
</template>

