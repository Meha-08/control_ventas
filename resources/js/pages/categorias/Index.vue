<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Tag, Tags, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import categoriasRoutes from '@/routes/categorias';

type Category = {
    id: number;
    nombre: string;
    descripcion: string | null;
    active: boolean;
    products_count: number;
    created_at: string;
};

type Props = {
    categorias: {
        data: Category[];
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
                title: 'Categorías',
                href: categoriasRoutes.index(),
            },
        ],
    },
});

const search = ref(props.filters.search ?? '');

const searchCategories = () => {
    router.get(categoriasRoutes.index(), { search: search.value || undefined }, {
        preserveState: true,
        replace: true,
    });
};

const clearSearch = () => {
    search.value = '';
    router.get(categoriasRoutes.index(), {}, {
        preserveState: true,
        replace: true,
    });
};

const confirmDelete = (category: Category) => {
    if (confirm(`¿Eliminar "${category.nombre}"? Esta acción no se puede deshacer.`)) {
        router.delete(categoriasRoutes.destroy(category.id), {
            preserveScroll: true,
        });
    }
};

const cardGradients = [
    { icon: 'from-[#7DD3FC] to-[#C084FC]' },
    { icon: 'from-[#C084FC] to-[#A78BFA]' },
    { icon: 'from-[#7DD3FC] to-[#60A5FA]' },
];

const getGradient = (name: string) => cardGradients[name.charCodeAt(0) % cardGradients.length];
</script>

<template>
    <Head title="Categorías" />

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
                            <Tags class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Categorías</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Gestiona las categorías de productos</p>
                        </div>
                    </div>
<Button as-child class="shrink-0 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30 hover:shadow-xl hover:shadow-[#9333EA]/40 transition-all duration-300 hover:-translate-y-0.5">
                        <Link :href="categoriasRoutes.create()">
                            <Plus class="h-4 w-4" /> Nueva categoría
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-2" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
            <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1 max-w-md">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#C084FC]" />
                        <Input
                            v-model="search"
                            placeholder="Buscar categorías por nombre..."
                            class="pl-10 pr-10 h-11 rounded-xl border-[#C084FC]/20 focus:border-[#C084FC] focus:ring-[#C084FC]/20 bg-white/80 dark:bg-gray-800/80"
                            @keyup.enter="searchCategories"
                        />
                        <button
                            v-if="search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#C084FC] transition-colors"
                            @click="clearSearch"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <Button size="sm" class="h-11 rounded-xl bg-gradient-to-r from-[#C084FC] to-[#A78BFA] text-white shadow-md shadow-[#C084FC]/20 hover:shadow-lg" @click="searchCategories">
                        Buscar
                    </Button>
                </div>
            </div>
        </div>

        <!-- Category Cards -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div
                v-for="category in categorias.data"
                :key="category.id"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="'stagger-' + ((categorias.data.indexOf(category) % 8) + 1)"
                :style="{ background: `linear-gradient(135deg, ${getGradient(category.nombre).icon.split(' ')[0].replace('from-', '')}, ${getGradient(category.nombre).icon.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl p-5 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-white shadow-lg ring-2 ring-white/50"
                                :class="'bg-gradient-to-br ' + getGradient(category.nombre).icon"
                            >
                                <Tag class="h-6 w-6" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[140px]">
                                    {{ category.nombre }}
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    Categoría
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5">
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8 rounded-xl text-gray-500 hover:text-[#7DD3FC] hover:bg-[#7DD3FC]/10" as-child>
                                            <Link :href="categoriasRoutes.edit(category.id)">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Editar categoría</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 rounded-xl text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30"
                                            @click="confirmDelete(category)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Eliminar categoría</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </div>
                    </div>

                    <div class="space-y-3 border-t border-[#7DD3FC]/10 dark:border-gray-800 pt-4">
                        <p v-if="category.descripcion" class="text-sm text-gray-500 line-clamp-2">
                            {{ category.descripcion }}
                        </p>
                        <p v-else class="text-sm text-gray-400 italic">
                            Sin descripción
                        </p>

                        <div class="flex items-center gap-2 pt-1">
                            <Badge class="bg-gradient-to-r from-[#C084FC]/15 to-[#A78BFA]/15 text-[#C084FC] border-0 rounded-lg">
                                {{ category.products_count }} producto{{ category.products_count !== 1 ? 's' : '' }}
                            </Badge>
                            <Badge :variant="category.active ? 'default' : 'outline'" class="rounded-lg">
                                {{ category.active ? 'Activa' : 'Inactiva' }}
                            </Badge>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="categorias.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#C084FC]/20 to-[#7DD3FC]/10 mb-4">
                <Tag class="h-8 w-8 text-[#C084FC]" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay categorías</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm">
                Comienza agregando tu primera categoría para verla aquí.
            </p>
<Button as-child class="mt-4 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30">
                <Link :href="categoriasRoutes.create()">
                    <Plus /> Agregar categoría
                </Link>
            </Button>
        </div>

        <!-- Pagination -->
        <div
            v-if="categorias.data.length > 0 && categorias.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
        >
            <p class="text-sm text-gray-500">
                Mostrando {{ categorias.from }}–{{ categorias.to }} de {{ categorias.total }}
            </p>
            <div class="flex items-center gap-1">
                <Button
                    v-for="link in categorias.links"
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

