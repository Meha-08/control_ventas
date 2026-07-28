<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    ArrowDown,
    ArrowUp,
    BarChart3,
    CalendarDays,
    DollarSign,
    Package,
    ShoppingCart,
    TrendingUp,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useAnimatedCounter } from '@/composables/useAnimatedCounter';
import reportesRoutes from '@/routes/reportes';

type Props = {
    totalVentas: number;
    cantidadVentas: number;
    productosMasVendidos: { product_name: string; total_vendido: number; total_ingresos: number }[];
    ventasPorDia: { fecha: string; cantidad: number; total: number }[];
    productosStock: { id: number; nombre: string; stock: number; precio: string; category_id: number | null; active: boolean; category: { id: number; nombre: string } | null }[];
    filters: { fecha_desde?: string; fecha_hasta?: string };
    startDate: string;
    endDate: string;
};

const props = defineProps<Props>();

const fechaDesde = ref(props.filters.fecha_desde ?? props.startDate ?? '');
const fechaHasta = ref(props.filters.fecha_hasta ?? props.endDate ?? '');

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Reportes',
                href: reportesRoutes.index(),
            },
        ],
    },
});

const { count: totalVentasCount } = useAnimatedCounter(props.totalVentas, 2000);
const { count: cantidadVentasCount } = useAnimatedCounter(props.cantidadVentas, 2200);

const productosEnStock = computed(() => props.productosStock.length);

const diasEnRango = computed(() => {
    if (!props.startDate || !props.endDate) return 0;
    const inicio = new Date(props.startDate);
    const fin = new Date(props.endDate);
    const diff = fin.getTime() - inicio.getTime();
    return Math.max(0, Math.round(diff / (1000 * 60 * 60 * 24)) + 1);
});

const maxVenta = computed(() => {
    if (props.ventasPorDia.length === 0) return 1;
    return Math.max(...props.ventasPorDia.map((d) => d.total), 1);
});

const formatMoney = (value: number) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
    }).format(value);
};

const formatDayLabel = (fecha: string) => {
    const d = new Date(fecha + 'T12:00:00');
    return d.toLocaleDateString('es-MX', { weekday: 'short', day: 'numeric' });
};

const applyFilters = () => {
    router.get(
        reportesRoutes.index.url(),
        {
            fecha_desde: fechaDesde.value || undefined,
            fecha_hasta: fechaHasta.value || undefined,
        },
        { preserveScroll: true, preserveState: true },
    );
};

const stockBadgeVariant = (stock: number) => {
    if (stock <= 3) return 'destructive';
    if (stock <= 10) return 'default';
    if (stock <= 20) return 'secondary';
    return 'outline';
};

const kpiCards = [
    {
        label: 'Total Ventas',
        value: 'totalVentas',
        icon: DollarSign,
        gradient: 'from-[#7DD3FC] to-[#C084FC]',
        format: 'money',
        count: totalVentasCount,
    },
    {
        label: 'Cantidad Ventas',
        value: 'cantidadVentas',
        icon: ShoppingCart,
        gradient: 'from-[#7DD3FC] to-[#60A5FA]',
        format: 'number',
        count: cantidadVentasCount,
    },
    {
        label: 'Productos en Stock',
        value: 'productosEnStock',
        icon: Package,
        gradient: 'from-[#C084FC] to-[#A78BFA]',
        format: 'number',
        count: productosEnStock,
    },
    {
        label: 'Días en Rango',
        value: 'diasEnRango',
        icon: CalendarDays,
        gradient: 'from-[#7DD3FC] to-[#C084FC]',
        format: 'number',
        count: diasEnRango,
    },
];
</script>

<template>
    <Head title="Reportes" />

    <div class="flex flex-col space-y-6 pb-8">
        <!-- Hero -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-scale-in-bounce" style="background: linear-gradient(135deg, #7DD3FC, #C084FC, #9333EA, #7E22CE)">
            <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl px-6 py-5 sm:px-8 sm:py-6">
                <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-[22px]">
                    <div class="absolute -left-20 -top-20 h-60 w-60 animate-blob rounded-full bg-[#7DD3FC]/15 blur-3xl"></div>
                    <div class="animation-delay-2000 absolute -bottom-20 -right-20 h-60 w-60 animate-blob rounded-full bg-[#7DD3FC]/15 blur-3xl"></div>
                </div>
                <div class="relative">
                    <div class="flex items-center gap-4">
<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#9333EA] to-[#7E22CE] shadow-lg shadow-[#9333EA]/30">
                            <BarChart3 class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Reportes</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Resumen ejecutivo de ventas y stock</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-1" style="background: linear-gradient(135deg, #7DD3FC, #C084FC)">
            <div class="relative rounded-[22px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-4 sm:p-5">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="space-y-1.5">
                        <Label for="fecha_desde" class="text-xs font-semibold text-gray-500">Fecha desde</Label>
                        <Input
                            id="fecha_desde"
                            v-model="fechaDesde"
                            type="date"
                            class="h-10 w-full sm:w-44 rounded-xl border-[#7DD3FC]/20 focus:border-[#7DD3FC] focus:ring-[#7DD3FC]/20 bg-white/80 dark:bg-gray-800/80"
                        />
                    </div>
                    <div class="space-y-1.5">
                        <Label for="fecha_hasta" class="text-xs font-semibold text-gray-500">Fecha hasta</Label>
                        <Input
                            id="fecha_hasta"
                            v-model="fechaHasta"
                            type="date"
                            class="h-10 w-full sm:w-44 rounded-xl border-[#7DD3FC]/20 focus:border-[#7DD3FC] focus:ring-[#7DD3FC]/20 bg-white/80 dark:bg-gray-800/80"
                        />
                    </div>
<Button size="sm" class="h-10 gap-1.5 rounded-xl bg-gradient-to-r from-[#C084FC] to-[#A78BFA] text-white shadow-md shadow-[#C084FC]/20 hover:shadow-lg" @click="applyFilters">
                        <CalendarDays class="h-4 w-4" /> Filtrar
                    </Button>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div
                v-for="(kpi, index) in kpiCards"
                :key="kpi.label"
                class="group relative overflow-hidden rounded-[24px] p-[2px] transition-all duration-500 hover:-translate-y-1.5 animate-slide-up-fade"
                :class="'stagger-' + (index + 2)"
                :style="{ background: `linear-gradient(135deg, ${kpi.gradient.split(' ')[0].replace('from-', '')}, ${kpi.gradient.split(' ')[1].replace('to-', '')})` }"
            >
                <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl p-5 h-full">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-13 w-13 shrink-0 items-center justify-center rounded-xl shadow-lg transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl"
                            :class="'bg-gradient-to-br ' + kpi.gradient"
                        >
                            <component :is="kpi.icon" class="h-6 w-6 text-white" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ kpi.label }}</p>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5 tabular-nums">
                                <template v-if="kpi.format === 'money'">{{ formatMoney(Number(kpi.count ?? 0)) }}</template>
                                <template v-else>
                                    {{ kpi.count ?? 0 }}
                                </template>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-7">
            <div class="lg:col-span-4 space-y-6">
                <!-- Top Selling Products -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-6" style="background: linear-gradient(135deg, #7DD3FC, #C084FC)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#7DD3FC]/10 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC] to-[#60A5FA] shadow-md">
                                    <TrendingUp class="h-4 w-4 text-white" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Productos Más Vendidos</h3>
                                    <p class="text-xs text-gray-500">Por cantidad de ventas</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div v-if="productosMasVendidos.length > 0" class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-800 text-xs text-gray-500">
                                            <th class="text-left px-6 py-3 font-medium w-10">#</th>
                                            <th class="text-left px-6 py-3 font-medium">Producto</th>
                                            <th class="text-right px-6 py-3 font-medium">Cantidad</th>
                                            <th class="text-right px-6 py-3 font-medium">Ingresos</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                                        <tr
                                            v-for="(item, index) in productosMasVendidos"
                                            :key="item.product_name"
                                            class="hover:bg-gradient-to-r hover:from-[#7DD3FC]/5 hover:to-[#C084FC]/5 transition-all duration-200"
                                        >
                                            <td class="px-6 py-3.5 tabular-nums text-gray-500 font-medium">{{ index + 1 }}</td>
                                            <td class="px-6 py-3.5 font-semibold text-gray-900 dark:text-white">{{ item.product_name }}</td>
                                            <td class="px-6 py-3.5 text-right tabular-nums">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-[#7DD3FC]/15 to-[#C084FC]/15 px-2.5 py-0.5 text-xs font-bold text-[#7DD3FC]">
                                                    <ShoppingCart class="h-3 w-3" />
                                                    {{ item.total_vendido }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-right tabular-nums font-bold text-[#7DD3FC]">
                                                {{ formatMoney(item.total_ingresos) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="flex flex-col items-center px-6 py-12 text-center">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-3">
                                    <TrendingUp class="h-6 w-6 text-[#7DD3FC]" />
                                </div>
                                <p class="text-sm font-medium text-gray-500">Sin ventas en el período</p>
                                <p class="text-xs text-gray-400 mt-0.5">No hay productos vendidos para mostrar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Sales Chart -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-7" style="background: linear-gradient(135deg, #7DD3FC, #C084FC)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#7DD3FC]/10 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[#7DD3FC] to-[#C084FC] shadow-md">
                                    <BarChart3 class="h-4 w-4 text-white" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Ventas por Día</h3>
                                    <p class="text-xs text-gray-500">Ingresos diarios en el rango</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <div v-if="ventasPorDia.length > 0" class="flex items-end justify-between gap-1.5 h-44">
                                <div
                                    v-for="item in ventasPorDia"
                                    :key="item.fecha"
                                    class="group/chart relative flex flex-1 flex-col items-center gap-1"
                                >
                                    <div
                                        class="w-full rounded-lg transition-all duration-500 hover:opacity-80 cursor-pointer"
                                        :style="{
                                            height: Math.max((item.total / maxVenta) * 130, 4) + 'px',
                                            background: `linear-gradient(to top, ${item.total === Math.max(...ventasPorDia.map(d => d.total)) ? '#7DD3FC' : '#C084FC'}, ${item.total === Math.max(...ventasPorDia.map(d => d.total)) ? '#D8B4FE' : '#D8B4FE'})`,
                                            boxShadow: item.total === Math.max(...ventasPorDia.map(d => d.total)) ? '0 4px 12px rgba(125, 211, 252,0.3)' : 'none',
                                        }"
                                    >
                                        <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent opacity-0 group-hover/chart:opacity-100 transition-opacity"></div>
                                    </div>
                                    <div
                                        v-if="item.total > 0"
                                        class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-bold text-gray-700 dark:text-gray-300 opacity-0 group-hover/chart:opacity-100 transition-opacity whitespace-nowrap bg-white/80 dark:bg-gray-800/80 px-2 py-0.5 rounded-lg backdrop-blur-sm"
                                    >
                                        {{ formatMoney(item.total) }}
                                    </div>
                                    <span class="text-[10px] text-gray-500 font-medium mt-1">{{ formatDayLabel(item.fecha) }}</span>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center py-12 text-center">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#7DD3FC]/20 to-[#C084FC]/10 mb-3">
                                    <BarChart3 class="h-6 w-6 text-[#7DD3FC]" />
                                </div>
                                <p class="text-sm font-medium text-gray-500">Sin datos de ventas</p>
                                <p class="text-xs text-gray-400 mt-0.5">No hay ventas registradas en el rango seleccionado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <!-- Low Stock Products -->
                <div class="relative overflow-hidden rounded-[24px] p-[2px] animate-slide-up-fade stagger-8" style="background: linear-gradient(135deg, #C084FC, #7DD3FC)">
                    <div class="relative rounded-[22px] bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#C084FC]/10 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[#C084FC] to-[#A78BFA] shadow-md">
                                    <Package class="h-4 w-4 text-white" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Productos con Stock Bajo</h3>
                                    <p class="text-xs text-gray-500">Ordenados por cantidad ascendente</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div v-if="productosStock.length > 0" class="divide-y divide-gray-100 dark:divide-gray-800/50">
                                <div
                                    v-for="producto in productosStock"
                                    :key="producto.id"
                                    class="flex items-center gap-3.5 px-6 py-3.5 hover:bg-gradient-to-r hover:from-[#C084FC]/5 hover:to-[#7DD3FC]/5 transition-all duration-200"
                                >
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white shadow-md"
                                        :class="producto.stock <= 3
                                            ? 'bg-gradient-to-br from-[#7DD3FC] to-red-500'
                                            : producto.stock <= 10
                                                ? 'bg-gradient-to-br from-[#C084FC] to-[#A78BFA]'
                                                : 'bg-gradient-to-br from-[#7DD3FC] to-[#60A5FA]'"
                                    >
                                        {{ producto.nombre.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ producto.nombre }}</p>
                                        <p class="truncate text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                            <span v-if="producto.category" class="font-medium">{{ producto.category.nombre }}</span>
                                            <template v-else>
                                                <span class="text-gray-400">Sin categoría</span>
                                            </template>
                                            <span class="text-gray-300 mx-1">·</span>
                                            <span>{{ formatMoney(Number(producto.precio)) }}</span>
                                        </p>
                                    </div>
                                    <Badge :variant="stockBadgeVariant(producto.stock)" class="tabular-nums shrink-0 rounded-lg gap-1">
                                        <template v-if="producto.stock <= 3">
                                            <ArrowDown class="h-3 w-3" />
                                        </template>
                                        <template v-else-if="producto.stock <= 10">
                                            <Package class="h-3 w-3" />
                                        </template>
                                        <template v-else>
                                            <ArrowUp class="h-3 w-3" />
                                        </template>
                                        {{ producto.stock }}
                                    </Badge>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center px-6 py-12 text-center">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#C084FC]/20 to-[#7DD3FC]/10 mb-3">
                                    <Package class="h-6 w-6 text-[#C084FC]" />
                                </div>
                                <p class="text-sm font-medium text-gray-500">Sin productos</p>
                                <p class="text-xs text-gray-400 mt-0.5">No hay productos registrados en el sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

