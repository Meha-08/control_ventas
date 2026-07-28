<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Mail, MapPin, Pencil, Phone, Plus, Search, Trash2, User, Users, X, Building2, CalendarDays } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import clientesRoutes from '@/routes/clientes';
import type { Cliente } from '@/types';

type Props = {
    clientes: {
        data: Cliente[];
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
    };
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Clientes',
                href: clientesRoutes.index(),
            },
        ],
    },
});

const search = ref(props.filters.search ?? '');

const searchClients = () => {
    router.get(clientesRoutes.index(), { search: search.value || undefined }, {
        preserveState: true,
        replace: true,
    });
};

const clearSearch = () => {
    search.value = '';
    router.get(clientesRoutes.index(), {}, {
        preserveState: true,
        replace: true,
    });
};

const confirmDelete = (cliente: Cliente) => {
    if (confirm(`¿Eliminar a "${cliente.name}"? Esta acción no se puede deshacer.`)) {
        router.delete(clientesRoutes.destroy(cliente.id), {
            preserveScroll: true,
        });
    }
};

const cardGradients = [
    { icon: 'from-[#7DD3FC] to-[#C084FC]', border: 'rgba(125, 211, 252,0.3)' },
    { icon: 'from-[#7DD3FC] to-[#60A5FA]', border: 'rgba(125,211,252,0.3)' },
    { icon: 'from-[#C084FC] to-[#A78BFA]', border: 'rgba(192,132,252,0.3)' },
];

const getGradient = (name: string) => cardGradients[name.charCodeAt(0) % cardGradients.length];

const formatDate = (date: string) => {
    const d = new Date(date);
    return d.toLocaleDateString('es-MX', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Clientes" />

    <div class="flex flex-col space-y-6 pb-8">
        <!-- Hero -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-scale-in-bounce" style="background: linear-gradient(135deg, #7DD3FC, #60A5FA, #9333EA, #7E22CE)">
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
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Clientes</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Gestiona tus clientes</p>
                        </div>
                    </div>
<Button as-child class="shrink-0 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30 hover:shadow-xl hover:shadow-[#9333EA]/40 transition-all duration-300 hover:-translate-y-0.5">
                        <Link :href="clientesRoutes.create()">
                            <Plus class="h-4 w-4" /> Nuevo cliente
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-2" style="background: linear-gradient(135deg, #7DD3FC, #C084FC)">
            <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1 max-w-md">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7DD3FC]" />
                        <Input
                            v-model="search"
                            placeholder="Buscar clientes por nombre, email o teléfono..."
                            class="pl-10 pr-10 h-11 rounded-xl border-[#7DD3FC]/20 focus:border-[#7DD3FC] focus:ring-[#7DD3FC]/20 bg-white/80 dark:bg-gray-800/80"
                            @keyup.enter="searchClients"
                        />
                        <button
                            v-if="search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#7DD3FC] transition-colors"
                            @click="clearSearch"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
<Button size="sm" class="h-11 rounded-xl bg-gradient-to-r from-[#C084FC] to-[#A78BFA] text-white shadow-md shadow-[#C084FC]/20 hover:shadow-lg hover:shadow-[#C084FC]/30" @click="searchClients">
                        Buscar
                    </Button>
                </div>
            </div>
        </div>

        <!-- Client Cards -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div
                v-for="(cliente, index) in clientes.data"
                :key="cliente.id"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="'stagger-' + ((index % 8) + 1)"
                :style="{ background: `linear-gradient(135deg, ${getGradient(cliente.name).icon.split(' ')[0].replace('from-', '')}, ${getGradient(cliente.name).icon.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl p-5 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-13 w-13 shrink-0 items-center justify-center rounded-xl text-lg font-bold text-white shadow-lg ring-2 ring-white/50"
                                :class="'bg-gradient-to-br ' + getGradient(cliente.name).icon"
                            >
                                {{ cliente.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[140px]">
                                    {{ cliente.name }}
                                </p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <Building2 class="h-3 w-3 text-gray-400" />
                                    <p class="text-[11px] text-gray-500 font-medium">Cliente</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5">
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8 rounded-xl text-gray-500 hover:text-[#7DD3FC] hover:bg-[#7DD3FC]/10" as-child>
                                            <Link :href="clientesRoutes.edit(cliente.id)">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Editar cliente</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 rounded-xl text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30"
                                            @click="confirmDelete(cliente)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Eliminar cliente</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </div>
                    </div>

                    <div class="space-y-2.5 border-t border-[#7DD3FC]/10 dark:border-gray-800 pt-4">
                        <div v-if="cliente.email" class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC]/20 to-[#7DD3FC]/10">
                                <Mail class="h-4 w-4 text-[#7DD3FC]" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-[#7DD3FC] uppercase tracking-wider">Email</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ cliente.email }}</p>
                            </div>
                        </div>

                        <div v-if="cliente.phone" class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#C084FC]/20 to-[#C084FC]/10">
                                <Phone class="h-4 w-4 text-[#C084FC]" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-[#C084FC] uppercase tracking-wider">Teléfono</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ cliente.phone }}</p>
                            </div>
                        </div>

                        <div v-if="cliente.address" class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC]/20 to-[#7DD3FC]/10">
                                <MapPin class="h-4 w-4 text-[#7DD3FC]" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-[#7DD3FC] uppercase tracking-wider">Dirección</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ cliente.address }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900">
                                <CalendarDays class="h-4 w-4 text-gray-400" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Registro</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(cliente.created_at) }}</p>
                            </div>
                        </div>

                        <p v-if="!cliente.email && !cliente.phone && !cliente.address" class="text-sm text-gray-400 italic text-center py-1">
                            Sin información adicional
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="clientes.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-4">
                <User class="h-8 w-8 text-[#7DD3FC]" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay clientes</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm">
                Comienza agregando tu primer cliente para verlo aquí.
            </p>
<Button as-child class="mt-4 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30">
                <Link :href="clientesRoutes.create()">
                    <Plus /> Agregar cliente
                </Link>
            </Button>
        </div>

        <!-- Pagination -->
        <div
            v-if="clientes.data.length > 0 && clientes.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
        >
            <p class="text-sm text-gray-500">
                Mostrando {{ clientes.from }}–{{ clientes.to }} de {{ clientes.total }}
            </p>
            <div class="flex items-center gap-1">
                <Button
                    v-for="link in clientes.links"
                    :key="link.label"
                    variant="ghost"
                    size="sm"
                    :disabled="!link.url"
                    :class="{ 'bg-gradient-to-r from-[#7DD3FC]/20 to-[#60A5FA]/20 font-medium text-[#7DD3FC]': link.active }"
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

