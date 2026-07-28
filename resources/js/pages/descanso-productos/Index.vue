<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Bed, BedDouble, BedSingle, Pencil, Plus, Search, Trash2, X, Grid3X3, List } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import descansoProductosRoutes from '@/routes/descanso-productos';

type DescansoProducto = {
    id: number;
    nombre_producto: string;
    tipo_producto: string;
    descripcion: string | null;
    tamaño: string | null;
    material: string | null;
    color: string | null;
    precio_venta: string;
    stock: number;
    garantia: string | null;
    imagen: string | null;
    imagen_url: string | null;
    estado: 'Disponible' | 'No disponible';
    fecha_registro: string;
};

type Props = {
    productos: {
        data: DescansoProducto[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number | null;
        to: number | null;
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: { search?: string; tipo?: string; estado?: string };
    categorias?: { id: number; nombre: string }[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Productos para el Descanso',
                href: descansoProductosRoutes.index(),
            },
        ],
    },
});

const viewMode = ref<'grid' | 'list'>('list');
const zoomedImage = ref<string | null>(null);

const search = ref(props.filters.search ?? '');

const searchProductos = () => {
    router.get(descansoProductosRoutes.index(), {
        search: search.value || undefined,
        tipo: props.filters.tipo,
        estado: props.filters.estado,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearSearch = () => {
    search.value = '';
    router.get(descansoProductosRoutes.index(), {
        tipo: props.filters.tipo,
        estado: props.filters.estado,
    }, {
        preserveState: true,
        replace: true,
    });
};

const filterByTipo = (value: string) => {
    router.get(descansoProductosRoutes.index(), {
        search: search.value || undefined,
        tipo: value || undefined,
        estado: props.filters.estado,
    }, {
        preserveState: true,
        replace: true,
    });
};

const filterByEstado = (value: string | undefined) => {
    router.get(descansoProductosRoutes.index(), {
        search: search.value || undefined,
        tipo: props.filters.tipo,
        estado: value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const confirmDelete = (producto: DescansoProducto) => {
    if (confirm(`¿Eliminar el producto "${producto.nombre_producto}"? Esta acción no se puede deshacer.`)) {
        router.delete(descansoProductosRoutes.destroy(producto.id), {
            preserveScroll: true,
        });
    }
};

const stockVariant = (stock: number): 'default' | 'secondary' | 'destructive' | 'outline' => {
    if (stock > 10) return 'default';
    if (stock > 0) return 'secondary';
    return 'destructive';
};

const formatPrice = (price: string | number) => {
    const num = typeof price === 'string' ? Number.parseFloat(price) : price;
    if (Number.isNaN(num)) return 'Bs 0.00';
    return 'Bs ' + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
};

const tipoIcon = (tipo: string) => {
    if (tipo.toLowerCase().includes('colch')) return BedDouble;
    if (tipo.toLowerCase().includes('somier')) return Bed;
    if (tipo.toLowerCase().includes('cama')) return BedDouble;
    return BedSingle;
};

const cardGradients = [
    { icon: 'from-[#7DD3FC] to-[#C084FC]' },
    { icon: 'from-[#C084FC] to-[#A78BFA]' },
    { icon: 'from-[#7DD3FC] to-[#60A5FA]' },
];

const getGradient = (name: string) => cardGradients[name.charCodeAt(0) % cardGradients.length];
</script>

<template>
    <Head title="Productos para el Descanso" />

    <div class="flex flex-col space-y-6 pb-8">
        <!-- Hero -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-scale-in-bounce" style="background: linear-gradient(135deg, #C084FC, #A78BFA, #9333EA, #7E22CE)">
            <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl px-6 py-5 sm:px-8 sm:py-6">
                <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-[22px]">
                    <div class="absolute -left-20 -top-20 h-60 w-60 animate-blob rounded-full bg-[#C084FC]/15 blur-3xl"></div>
                    <div class="animation-delay-2000 absolute -bottom-20 -right-20 h-60 w-60 animate-blob rounded-full bg-[#7DD3FC]/15 blur-3xl"></div>
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-4">
<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-lg shadow-[#9333EA]/30">
                            <BedDouble class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Productos para el Descanso</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Gestiona tu línea de colchones, somieres y espaldares</p>
                        </div>
                    </div>
<Button as-child class="shrink-0 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30 hover:shadow-xl hover:shadow-[#9333EA]/40 transition-all duration-300 hover:-translate-y-0.5">
                        <Link :href="descansoProductosRoutes.create()">
                            <Plus class="h-4 w-4" /> Nuevo producto
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-2" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
            <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4 sm:p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#C084FC]" />
                        <Input
                            v-model="search"
                            placeholder="Buscar por nombre o descripción..."
                            class="pl-10 pr-10 h-11 rounded-xl border-[#C084FC]/20 focus:border-[#C084FC] focus:ring-[#C084FC]/20 bg-white/80 dark:bg-gray-800/80"
                            @keyup.enter="searchProductos"
                        />
                        <button
                            v-if="search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#C084FC] transition-colors"
                            @click="clearSearch"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <Button size="sm" class="h-11 rounded-xl bg-gradient-to-r from-[#C084FC] to-[#A78BFA] text-white shadow-md shadow-[#C084FC]/20" @click="searchProductos">
                        Buscar
                    </Button>
                    <Select
                        :model-value="filters.tipo ?? ''"
                        @update:model-value="filterByTipo"
                    >
                        <SelectTrigger class="w-[180px] h-11 rounded-xl border-[#C084FC]/20">
                            <SelectValue placeholder="Todos los tipos" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem :value="''">
                                Todos los tipos
                            </SelectItem>
                            <SelectItem v-for="cat in categorias" :key="cat.id" :value="cat.nombre">
                                {{ cat.nombre }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div class="flex gap-1 bg-white/50 dark:bg-gray-800/50 rounded-xl p-1">
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg text-xs"
                            :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#A78BFA]/20 text-[#C084FC] font-semibold shadow-sm': !filters.estado }"
                            @click="filterByEstado(undefined)"
                        >
                            Todos
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg text-xs"
                            :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#A78BFA]/20 text-[#C084FC] font-semibold shadow-sm': filters.estado === 'Disponible' }"
                            @click="filterByEstado('Disponible')"
                        >
                            Disponibles
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg text-xs"
                            :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#A78BFA]/20 text-[#C084FC] font-semibold shadow-sm': filters.estado === 'No disponible' }"
                            @click="filterByEstado('No disponible')"
                        >
                            No disponibles
                        </Button>
                    </div>
                    <div class="flex gap-1 rounded-xl bg-white/50 dark:bg-gray-800/50 p-0.5">
                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <Button variant="ghost" size="sm" class="h-9 w-9 rounded-lg p-0" :class="{ 'bg-[#C084FC]/15 text-[#C084FC]': viewMode === 'grid' }" @click="viewMode = 'grid'">
                                        <Grid3X3 class="h-4 w-4" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>Vista cuadrícula</TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <Button variant="ghost" size="sm" class="h-9 w-9 rounded-lg p-0" :class="{ 'bg-[#C084FC]/15 text-[#C084FC]': viewMode === 'list' }" @click="viewMode = 'list'">
                                        <List class="h-4 w-4" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>Vista lista</TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="productos.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#C084FC]/20 to-[#7DD3FC]/10 mb-4">
                <BedDouble class="h-8 w-8 text-[#C084FC]" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay productos</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm">
                No se encontraron productos con los criterios de búsqueda.
            </p>
<Button as-child class="mt-4 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30">
                <Link :href="descansoProductosRoutes.create()">
                    <Plus /> Agregar producto
                </Link>
            </Button>
        </div>

        <!-- GRID VIEW -->
        <div v-else-if="viewMode === 'grid'" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div
                v-for="producto in productos.data"
                :key="producto.id"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="['stagger-' + ((productos.data.indexOf(producto) % 8) + 1), { 'opacity-60': producto.estado === 'No disponible' }]"
                :style="{ background: `linear-gradient(135deg, ${getGradient(producto.nombre_producto).icon})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl overflow-hidden h-full">
                    <div
                        class="relative h-44 overflow-hidden cursor-pointer"
                        :class="producto.imagen_url ? '' : 'bg-gradient-to-br from-[#C084FC]/20 to-[#A78BFA]/10'"
                        @click="producto.imagen_url ? (zoomedImage = producto.imagen_url) : null"
                    >
                        <img
                            v-if="producto.imagen_url"
                            :src="producto.imagen_url"
                            :alt="producto.nombre_producto"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                        />
                        <div v-else class="flex h-full items-center justify-center">
                            <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-[#C084FC]/30 to-[#A78BFA]/20 shadow-inner">
                                <BedDouble class="h-12 w-12 text-[#C084FC]" />
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <div class="absolute top-3 right-3 flex gap-1">
                            <Badge :variant="producto.estado === 'Disponible' ? 'default' : 'outline'" class="rounded-lg text-[10px] px-2 py-0.5 backdrop-blur-sm shadow-sm">
                                {{ producto.estado }}
                            </Badge>
                            <Badge class="bg-[#C084FC]/80 text-white border-0 rounded-lg text-[10px] px-2 py-0.5 backdrop-blur-sm">
                                <component :is="tipoIcon(producto.tipo_producto)" class="h-3 w-3 mr-1" />
                                {{ producto.tipo_producto }}
                            </Badge>
                        </div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white truncate">{{ producto.nombre_producto }}</p>
                            <p v-if="producto.descripcion" class="text-xs text-gray-500 truncate mt-0.5">{{ producto.descripcion }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <span v-if="producto.material">{{ producto.material }}</span>
                            <span v-if="producto.material && producto.color" class="text-gray-300">·</span>
                            <span v-if="producto.color">{{ producto.color }}</span>
                            <span v-if="!producto.material && !producto.color" class="text-gray-400">—</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold tabular-nums text-gray-900 dark:text-white">{{ formatPrice(producto.precio_venta) }}</span>
                            <Badge :variant="stockVariant(producto.stock)" class="tabular-nums rounded-lg text-[10px]">
                                {{ producto.stock }} uds.
                            </Badge>
                        </div>
                        <div v-if="producto.garantia" class="text-xs text-gray-500">
                            Garantía: {{ producto.garantia }}
                        </div>
                        <div class="flex items-center justify-end gap-1 pt-1 border-t border-[#C084FC]/10 dark:border-gray-800">
                            <Button variant="ghost" size="sm" class="h-8 rounded-lg text-xs text-gray-500 hover:text-[#C084FC] hover:bg-[#C084FC]/10" as-child>
                                <Link :href="descansoProductosRoutes.edit(producto.id)">
                                    <Pencil class="h-3.5 w-3.5 mr-1" /> Editar
                                </Link>
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-8 w-8 rounded-lg p-0 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30"
                                @click="confirmDelete(producto)"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LIST VIEW -->
        <div v-else class="overflow-x-auto rounded-[24px] p-[2px] animate-slide-up-fade stagger-4" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
            <div class="rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl min-w-fit">
                <div class="min-w-[1000px]">
                    <div class="grid grid-cols-[0.5fr_0.6fr_1.2fr_0.8fr_1fr_0.7fr_0.7fr_0.6fr] gap-4 px-5 py-4 border-b border-[#C084FC]/10 dark:border-gray-800 text-xs uppercase tracking-wider font-semibold text-[#C084FC]">
                        <div>Tipo</div>
                        <div>Imagen</div>
                        <div>Nombre</div>
                        <div>Material / Color</div>
                        <div>Precio</div>
                        <div>Stock</div>
                        <div>Estado</div>
                        <div class="text-right">Acciones</div>
                    </div>
                    <div
                        v-for="producto in productos.data"
                        :key="producto.id"
                        class="grid grid-cols-[0.5fr_0.6fr_1.2fr_0.8fr_1fr_0.7fr_0.7fr_0.6fr] gap-4 px-5 py-4 border-b border-gray-100 dark:border-gray-800/50 transition-all hover:bg-gradient-to-r hover:from-[#C084FC]/5 hover:to-[#7DD3FC]/5 items-center animate-slide-up-fade"
                        :class="['stagger-' + ((productos.data.indexOf(producto) % 8) + 1), { 'opacity-60': producto.estado === 'No disponible' }]"
                    >
                        <div>
                            <Badge class="bg-[#C084FC]/10 text-[#C084FC] border-0 rounded-lg gap-1.5 whitespace-nowrap">
                                <component :is="tipoIcon(producto.tipo_producto)" class="h-3.5 w-3.5" />
                                {{ producto.tipo_producto }}
                            </Badge>
                        </div>
                        <div>
                            <img
                                v-if="producto.imagen_url"
                                :src="producto.imagen_url"
                                :alt="producto.nombre_producto"
                                class="h-11 w-11 rounded-xl object-cover border border-[#C084FC]/20 shadow-sm cursor-pointer transition-transform hover:scale-110"
                                @click="zoomedImage = producto.imagen_url"
                            />
                            <div
                                v-else
                                class="h-11 w-11 rounded-xl bg-gradient-to-br from-[#C084FC]/20 to-[#A78BFA]/10 flex items-center justify-center"
                            >
                                <BedDouble class="h-5 w-5 text-[#C084FC]" />
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ producto.nombre_producto }}</p>
                            <p v-if="producto.descripcion" class="text-xs text-gray-500 truncate mt-0.5">{{ producto.descripcion }}</p>
                        </div>
                        <div class="min-w-0 text-sm">
                            <p v-if="producto.material" class="truncate text-gray-700 dark:text-gray-300">{{ producto.material }}</p>
                            <p v-if="producto.color" class="text-xs text-gray-500 truncate">{{ producto.color }}</p>
                            <span v-if="!producto.material && !producto.color" class="text-gray-400">—</span>
                        </div>
                        <div class="min-w-0">
                            <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ formatPrice(producto.precio_venta) }}</span>
                            <p v-if="producto.garantia" class="text-xs text-gray-500">
                                Garantía: {{ producto.garantia }}
                            </p>
                        </div>
                        <div>
                            <Badge :variant="stockVariant(producto.stock)" class="tabular-nums rounded-lg">
                                {{ producto.stock }} uds.
                            </Badge>
                        </div>
                        <div>
                            <Badge :variant="producto.estado === 'Disponible' ? 'default' : 'outline'" class="rounded-lg">
                                {{ producto.estado }}
                            </Badge>
                        </div>
                        <div class="flex items-center justify-end gap-1">
                            <Button variant="ghost" size="icon" class="h-9 w-9 rounded-xl text-gray-500 hover:text-[#C084FC] hover:bg-[#C084FC]/10" as-child>
                                <Link :href="descansoProductosRoutes.edit(producto.id)">
                                    <Pencil class="h-4 w-4" />
                                </Link>
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-9 w-9 rounded-xl text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30"
                                @click="confirmDelete(producto)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Zoom Modal -->
        <Teleport to="body">
            <div
                v-if="zoomedImage"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
                @click="zoomedImage = null"
            >
                <div class="relative max-w-3xl w-full max-h-[90vh] animate-scale-in-bounce" @click.stop>
                    <img
                        :src="zoomedImage"
                        alt="Imagen ampliada"
                        class="w-full h-auto rounded-2xl shadow-2xl"
                    />
                    <button
                        class="absolute -top-3 -right-3 flex h-9 w-9 items-center justify-center rounded-full bg-white shadow-lg text-gray-700 hover:text-red-500 transition-colors"
                        @click="zoomedImage = null"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </Teleport>

        <!-- Pagination -->
        <div
            v-if="productos.data.length > 0 && productos.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
        >
            <p class="text-sm text-gray-500">
                Mostrando {{ productos.from }}–{{ productos.to }} de {{ productos.total }}
            </p>
            <div class="flex items-center gap-1">
                <Button
                    v-for="link in productos.links"
                    :key="link.label"
                    variant="ghost"
                    size="sm"
                    :disabled="!link.url"
                    :class="{ 'bg-gradient-to-r from-[#C084FC]/20 to-[#A78BFA]/20 font-medium text-[#C084FC]': link.active }"
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

