<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ShoppingCart, EyeOff, Search, Plus, X, Eye, Ban, Calendar, FileText, User } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import ventasRoutes from '@/routes/ventas';

type User = { id: number; name: string };
type Cliente = { id: number; name: string };
type SaleItem = { id: number; product_name: string; cantidad: number; precio_unitario: string; subtotal: string };
type Venta = {
    id: number;
    user_id: number;
    cliente_id: number | null;
    total: string;
    estado: string;
    tipo_comprobante: string | null;
    nro_comprobante: string | null;
    created_at: string;
    user: User | null;
    cliente: Cliente | null;
    items: SaleItem[];
};

type Props = {
    ventas: { data: Venta[]; current_page: number; last_page: number; per_page: number; total: number; from: number | null; to: number | null; links: { url: string | null; label: string; active: boolean }[] };
    filters: { search?: string; estado?: string };
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Ventas',
                href: ventasRoutes.index(),
            },
        ],
    },
});

const search = ref(props.filters.search ?? '');
const estado = ref(props.filters.estado ?? '');

const searchVentas = () => {
    router.get(ventasRoutes.index(), {
        search: search.value || undefined,
        estado: estado.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearSearch = () => {
    search.value = '';
    estado.value = '';
    router.get(ventasRoutes.index(), {}, {
        preserveState: true,
        replace: true,
    });
};

const confirmAnular = (venta: Venta) => {
    if (confirm(`¿Anular la venta "${venta.nro_comprobante ?? 'Sin comprobante'}"?`)) {
        router.delete(ventasRoutes.destroy(venta.id), {
            preserveScroll: true,
        });
    }
};

const formatDate = (dateStr: string) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('es-BO', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const cardGradients = [
    { icon: 'from-[#7DD3FC] to-[#60A5FA]' },
    { icon: 'from-[#7DD3FC] to-[#C084FC]' },
    { icon: 'from-[#C084FC] to-[#A78BFA]' },
];

const getGradient = (id: number) => cardGradients[id % cardGradients.length];
</script>

<template>
    <Head title="Ventas" />

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
                            <ShoppingCart class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Ventas</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Historial de ventas realizadas</p>
                        </div>
                    </div>
<Button as-child class="shrink-0 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30 hover:shadow-xl hover:shadow-[#9333EA]/40 transition-all duration-300 hover:-translate-y-0.5">
                        <Link :href="ventasRoutes.create()">
                            <Plus class="h-4 w-4" /> Nueva venta
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-2" style="background: linear-gradient(135deg, #7DD3FC, #C084FC)">
            <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4 sm:p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7DD3FC]" />
                        <Input
                            v-model="search"
                            placeholder="Buscar por Nro. comprobante..."
                            class="pl-10 pr-10 h-11 rounded-xl border-[#7DD3FC]/20 focus:border-[#7DD3FC] focus:ring-[#7DD3FC]/20 bg-white/80 dark:bg-gray-800/80"
                            @keyup.enter="searchVentas"
                        />
                        <button
                            v-if="search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#7DD3FC] transition-colors"
                            @click="clearSearch"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <Select v-model="estado" @update:model-value="searchVentas">
                        <SelectTrigger class="w-[180px] h-11 rounded-xl border-[#7DD3FC]/20">
                            <SelectValue placeholder="Todos los estados" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="">Todos los estados</SelectItem>
                            <SelectItem value="completado">Completado</SelectItem>
                            <SelectItem value="anulado">Anulado</SelectItem>
                        </SelectContent>
                    </Select>
<Button size="sm" class="h-11 rounded-xl bg-gradient-to-r from-[#C084FC] to-[#A78BFA] text-white shadow-md shadow-[#C084FC]/20 hover:shadow-lg" @click="searchVentas">
                        Buscar
                    </Button>
                </div>
            </div>
        </div>

        <!-- Sales Cards -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div
                v-for="(venta, index) in ventas.data"
                :key="venta.id"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="'stagger-' + ((index % 8) + 1)"
                :style="{ background: `linear-gradient(135deg, ${getGradient(venta.id).icon.split(' ')[0].replace('from-', '')}, ${getGradient(venta.id).icon.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl p-5 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#7DD3FC] to-[#60A5FA] text-white shadow-md">
                                <FileText class="h-5 w-5" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[140px]">
                                    {{ venta.nro_comprobante ?? 'Sin comprobante' }}
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium truncate">
                                    {{ venta.tipo_comprobante ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <Badge
                            :variant="venta.estado === 'completado' ? 'default' : 'destructive'"
                            class="shrink-0 rounded-lg"
                        >
                            {{ venta.estado === 'completado' ? 'Completado' : 'Anulado' }}
                        </Badge>
                    </div>

                    <div class="space-y-3 border-t border-[#7DD3FC]/10 dark:border-gray-800 pt-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC]/20 to-[#7DD3FC]/10">
                                <User class="h-4 w-4 text-[#7DD3FC]" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-[#7DD3FC] uppercase tracking-wider">Cliente</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ venta.cliente?.name ?? 'Sin cliente' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#C084FC]/20 to-[#C084FC]/10">
                                <ShoppingCart class="h-4 w-4 text-[#C084FC]" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-[#C084FC] uppercase tracking-wider">Total</p>
                                <p class="text-sm font-bold text-[#C084FC]">Bs {{ venta.total }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC]/20 to-[#7DD3FC]/10">
                                <Calendar class="h-4 w-4 text-[#7DD3FC]" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold text-[#7DD3FC] uppercase tracking-wider">Fecha</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ formatDate(venta.created_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex border-t border-[#7DD3FC]/10 dark:border-gray-800 divide-x divide-[#7DD3FC]/10 dark:divide-gray-800">
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button variant="ghost" class="flex-1 h-9 rounded-none rounded-bl-[22px] text-gray-500 hover:text-[#7DD3FC] hover:bg-[#7DD3FC]/10" as-child>
                                    <Link :href="ventasRoutes.show(venta.id)">
                                        <Eye class="h-4 w-4 mr-1" /> Ver
                                    </Link>
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>Ver detalle de venta</TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button
                                    variant="ghost"
                                    class="flex-1 h-9 rounded-none rounded-br-[22px] text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30"
                                    :disabled="venta.estado === 'anulado'"
                                    @click="confirmAnular(venta)"
                                >
                                    <Ban class="h-4 w-4 mr-1" /> Anular
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>{{ venta.estado === 'anulado' ? 'Ya está anulada' : 'Anular venta' }}</TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="ventas.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-4">
                <EyeOff class="h-8 w-8 text-[#7DD3FC]" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay ventas</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm">
                Comienza registrando tu primera venta para verla aquí.
            </p>
<Button as-child class="mt-4 bg-gradient-to-r from-[#9333EA] to-[#7E22CE] text-white border-0 shadow-lg shadow-[#9333EA]/30">
                <Link :href="ventasRoutes.create()">
                    <Plus /> Nueva venta
                </Link>
            </Button>
        </div>

        <!-- Pagination -->
        <div
            v-if="ventas.data.length > 0 && ventas.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
        >
            <p class="text-sm text-gray-500">
                Mostrando {{ ventas.from }}–{{ ventas.to }} de {{ ventas.total }}
            </p>
            <div class="flex flex-wrap items-center gap-1">
                <Button
                    v-for="link in ventas.links"
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

