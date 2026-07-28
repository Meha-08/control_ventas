<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Ban, Calendar, FileText, Printer, ShoppingCart, User } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
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
    venta: Venta;
};

const props = defineProps<Props>();

defineOptions({
    layout: (pageProps: { venta: Venta }) => ({
        breadcrumbs: [
            {
                title: 'Ventas',
                href: ventasRoutes.index(),
            },
            {
                title: `Venta #${pageProps.venta.nro_comprobante ?? pageProps.venta.id}`,
                href: ventasRoutes.show(pageProps.venta.id),
            },
        ],
    }),
});

const confirmAnular = () => {
    if (confirm(`¿Anular la venta "${props.venta.nro_comprobante ?? 'Sin comprobante'}"?`)) {
        router.delete(ventasRoutes.destroy(props.venta.id), {
            preserveScroll: true,
        });
    }
};

const formatDate = (dateStr: string) => {
    const date = new Date(dateStr);

    return date.toLocaleDateString('es-BO', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const printPage = () => {
    window.print();
};
</script>

<template>
    <Head :title="`Venta #${venta.nro_comprobante ?? venta.id}`" />

    <div class="flex flex-col space-y-6 print:space-y-4">
        <div class="flex items-center justify-between print:hidden">
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="ventasRoutes.index()">
                        <ArrowLeft class="h-4 w-4" /> Volver
                    </Link>
                </Button>
            </div>
            <div class="flex items-center gap-2">
                <Button variant="outline" size="sm" @click="printPage">
                    <Printer class="h-4 w-4" /> Imprimir
                </Button>
                <Button
                    variant="destructive"
                    size="sm"
                    :disabled="venta.estado === 'anulado'"
                    @click="confirmAnular"
                >
                    <Ban class="h-4 w-4" /> {{ venta.estado === 'anulado' ? 'Anulada' : 'Anular venta' }}
                </Button>
            </div>
        </div>

        <div class="flex items-center justify-center mb-2 print:mb-4">
            <div class="flex flex-col items-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/20 ring-4 ring-purple-100 dark:ring-purple-900/30 mb-3">
                    <ShoppingCart class="h-7 w-7" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-center">Detalle de venta</h2>
                <div class="mt-2 flex items-center gap-2">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-purple-300 dark:to-purple-700"></span>
                    <Badge :variant="venta.estado === 'completado' ? 'default' : 'destructive'" class="capitalize">
                        {{ venta.estado === 'completado' ? 'Completado' : 'Anulado' }}
                    </Badge>
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-purple-300 dark:to-purple-700"></span>
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <Card class="bg-gradient-to-br from-purple-50 to-purple-100/30 dark:from-purple-950/20 dark:to-purple-900/10">
                <CardHeader class="border-b pb-3">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <FileText class="h-4 w-4 text-purple-500" />
                        Información del comprobante
                    </h3>
                </CardHeader>
                <CardContent class="pt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Nro. comprobante</span>
                        <span class="font-medium">{{ venta.nro_comprobante ?? '—' }}</span>
                    </div>
                    <Separator />
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Tipo</span>
                        <span class="font-medium">{{ venta.tipo_comprobante ?? '—' }}</span>
                    </div>
                    <Separator />
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Fecha</span>
                        <span class="font-medium">{{ formatDate(venta.created_at) }}</span>
                    </div>
                    <Separator />
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Registrado por</span>
                        <span class="font-medium">{{ venta.user?.name ?? '—' }}</span>
                    </div>
                </CardContent>
            </Card>

            <Card class="bg-gradient-to-br from-blue-50 to-blue-100/30 dark:from-blue-950/20 dark:to-blue-900/10">
                <CardHeader class="border-b pb-3">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <User class="h-4 w-4 text-blue-500" />
                        Información del cliente
                    </h3>
                </CardHeader>
                <CardContent class="pt-4 space-y-3">
                    <div v-if="venta.cliente">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">Nombre</span>
                            <span class="font-medium">{{ venta.cliente.name }}</span>
                        </div>
                    </div>
                    <div v-else class="flex items-center justify-center py-4 text-sm text-muted-foreground">
                        <User class="h-4 w-4 mr-2" /> Sin cliente registrado
                    </div>
                </CardContent>
            </Card>
        </div>

        <Card>
            <CardHeader class="border-b pb-3">
                <h3 class="text-sm font-semibold flex items-center gap-2">
                    <ShoppingCart class="h-4 w-4 text-violet-500" />
                    Productos
                </h3>
            </CardHeader>
            <CardContent class="pt-4 p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border/50 bg-muted/50">
                                <th class="text-left font-semibold px-4 py-3 text-xs uppercase tracking-wider text-muted-foreground">#</th>
                                <th class="text-left font-semibold px-4 py-3 text-xs uppercase tracking-wider text-muted-foreground">Producto</th>
                                <th class="text-right font-semibold px-4 py-3 text-xs uppercase tracking-wider text-muted-foreground">Cantidad</th>
                                <th class="text-right font-semibold px-4 py-3 text-xs uppercase tracking-wider text-muted-foreground">Precio Unit.</th>
                                <th class="text-right font-semibold px-4 py-3 text-xs uppercase tracking-wider text-muted-foreground">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/40">
                            <tr
                                v-for="(item, index) in venta.items"
                                :key="item.id"
                                class="transition-colors hover:bg-muted/30"
                            >
                                <td class="px-4 py-3 text-muted-foreground">{{ index + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ item.product_name }}</td>
                                <td class="px-4 py-3 text-right">{{ item.cantidad }}</td>
                                <td class="px-4 py-3 text-right">Bs {{ item.precio_unitario }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-purple-600 dark:text-purple-400">Bs {{ item.subtotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end">
            <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100/50 dark:from-purple-950/30 dark:to-purple-900/20 border border-purple-200/50 dark:border-purple-800/30 min-w-[250px] sm:min-w-0">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-sm">
                    <ShoppingCart class="h-5 w-5" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-foreground tabular-nums">Bs {{ venta.total }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    @page {
        margin: 1cm;
    }
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

