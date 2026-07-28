<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Shield, ShieldCheck, ShieldAlert, Trash2, Users } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { usePermissions } from '@/composables/usePermissions';
import rolesRoutes from '@/routes/roles';
import type { Role } from '@/types';

type Props = {
    roles: {
        data: (Role & { users_count: number })[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number | null;
        to: number | null;
        links: { url: string | null; label: string; active: boolean }[];
    };
};

defineProps<Props>();
const { can } = usePermissions();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Roles',
                href: rolesRoutes.index(),
            },
        ],
    },
});

const roleGradients: Record<string, string> = {
    admin: 'from-[#7DD3FC] to-[#C084FC]',
    vendedor: 'from-[#7DD3FC] to-[#60A5FA]',
    cliente: 'from-[#C084FC] to-[#A78BFA]',
};

const roleIcons: Record<string, any> = {
    admin: ShieldCheck,
    vendedor: Shield,
    cliente: ShieldAlert,
};

const defaultGradient = 'from-[#C084FC] to-[#A78BFA]';

const confirmDelete = (role: Role & { users_count: number }) => {
    const warn = role.users_count > 0
        ? ` Tiene ${role.users_count} usuario${role.users_count === 1 ? '' : 's'} asignado${role.users_count === 1 ? '' : 's'} que se desasignarán.`
        : '';

    if (confirm(`¿Eliminar el rol "${role.nombre}"?${warn}`)) {
        router.delete(rolesRoutes.destroy(role.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Roles" />

    <div class="flex flex-col space-y-8 pb-8">
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
                            <Shield class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Roles</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Gestiona los roles y sus permisos</p>
                        </div>
                    </div>

<Button v-if="can('usuarios.gestionar')" as-child class="shrink-0 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30 hover:shadow-xl hover:shadow-[#9333EA]/40 transition-all duration-300 hover:-translate-y-0.5">
                        <Link :href="rolesRoutes.create()">
                            <Plus class="h-4 w-4" /> Nuevo rol
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Role Cards Grid -->
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="role in roles.data"
                :key="role.id"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="'stagger-' + ((roles.data.indexOf(role) % 8) + 1)"
                :style="{ background: `linear-gradient(135deg, ${roleGradients[role.nombre] || defaultGradient})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl p-6 h-full">
                    <div class="pointer-events-none absolute -right-16 -top-16 h-32 w-32 rounded-full opacity-0 group-hover:opacity-30 transition-all duration-700 group-hover:scale-150 blur-3xl"
                        :style="{ background: (roleGradients[role.nombre] || defaultGradient).split(' ')[0].replace('from-', '') }">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-5">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-2xl shadow-lg transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl"
                                :class="'bg-gradient-to-br ' + (roleGradients[role.nombre] || defaultGradient)"
                            >
                                <component :is="roleIcons[role.nombre] || Shield" class="h-8 w-8 text-white" />
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize mb-1">{{ role.nombre }}</h3>
                        <p class="text-sm text-gray-500 mb-5">{{ role.descripcion || 'Sin descripción' }}</p>

                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-5 py-2 px-3 rounded-xl bg-gradient-to-r from-[#7DD3FC]/10 to-[#C084FC]/10 dark:from-[#7DD3FC]/5 dark:to-[#C084FC]/5">
                            <Users class="h-4 w-4 text-[#7DD3FC]" />
                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ role.users_count }}</span>
                            {{ role.users_count === 1 ? 'usuario' : 'usuarios' }}
                        </div>

                        <div class="flex items-center gap-2" v-if="can('usuarios.gestionar')">
                            <Button variant="outline" size="sm" as-child class="border-[#7DD3FC]/20 text-[#7DD3FC] hover:bg-[#7DD3FC]/10 hover:text-[#7DD3FC]">
                                <Link :href="rolesRoutes.edit(role.id)">
                                    <Pencil class="h-3.5 w-3.5" /> Editar
                                </Link>
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                class="text-red-500 border-red-200 hover:bg-red-50 dark:border-red-800 dark:hover:bg-red-950/30"
                                @click="confirmDelete(role)"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="roles.data.length === 0"
            class="flex flex-col items-center justify-center py-16 text-center"
        >
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-4">
                <Shield class="h-8 w-8 text-[#7DD3FC]" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay roles</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm">
                Crea tu primer rol para comenzar a gestionar permisos.
            </p>
<Button v-if="can('usuarios.gestionar')" as-child class="mt-4 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30">
                <Link :href="rolesRoutes.create()">
                    <Plus /> Crear rol
                </Link>
            </Button>
        </div>

        <!-- Pagination -->
        <div
            v-if="roles.data.length > 0 && roles.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
        >
            <p class="text-sm text-gray-500">
                Mostrando {{ roles.from }}–{{ roles.to }} de {{ roles.total }}
            </p>
            <div class="flex items-center gap-1">
                <Button
                    v-for="link in roles.links"
                    :key="link.label"
                    variant="ghost"
                    size="sm"
                    :disabled="!link.url"
                    :class="{ 'bg-gradient-to-r from-[#7DD3FC]/20 to-[#C084FC]/20 font-medium text-[#7DD3FC]': link.active }"
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

