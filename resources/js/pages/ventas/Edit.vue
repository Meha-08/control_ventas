<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Plus, ShoppingCart, Trash2, User, FileText, Package } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import ventasRoutes from '@/routes/ventas';

type Props = {
    clientes: { id: number; name: string; email?: string; phone?: string }[];
    productos: { id: number; nombre: string; precio: string; stock: number; category?: { nombre: string } }[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Ventas',
                href: ventasRoutes.index(),
            },
            {
                title: 'Nueva venta',
                href: ventasRoutes.create(),
            },
        ],
    },
});

type ItemData = {
    product_id: number | null;
    product_name: string;
    cantidad: number;
    precio_unitario: number;
    subtotal: number;
};

const clienteId = ref('');
const tipoComprobante = ref('Boleta');
const nroComprobante = ref('');
const errors = ref<Record<string, string>>({});
const processing = ref(false);

const items = reactive<ItemData[]>([]);

const total = computed(() => {
    return items.reduce((sum, item) => sum + item.subtotal, 0);
});

const addItem = () => {
    items.push({
        product_id: null,
        product_name: '',
        cantidad: 1,
        precio_unitario: 0,
        subtotal: 0,
    });
};

const removeItem = (index: number) => {
    items.splice(index, 1);
};

const onProductSelect = (index: number, productId: string) => {
    const product = props.productos.find((p) => String(p.id) === productId);

    if (product) {
        items[index].product_id = product.id;
        items[index].product_name = product.nombre;
        items[index].precio_unitario = Number(product.precio);
        items[index].cantidad = 1;
        items[index].subtotal = Number(product.precio) * 1;
    }
};

const updateSubtotal = (index: number) => {
    const item = items[index];
    item.subtotal = item.cantidad * item.precio_unitario;
};

const submit = () => {
    errors.value = {};
    processing.value = true;

    if (items.length === 0) {
        errors.value.items = 'Debe agregar al menos un producto.';
        processing.value = false;

        return;
    }

    router.post(ventasRoutes.store().url, {
        cliente_id: clienteId.value || undefined,
        tipo_comprobante: tipoComprobante.value,
        nro_comprobante: nroComprobante.value || undefined,
        total: total.value,
        items: items.map((item) => ({
            product_id: item.product_id,
            product_name: item.product_name,
            cantidad: item.cantidad,
            precio_unitario: item.precio_unitario,
            subtotal: item.subtotal,
        })),
    }, {
        preserveScroll: true,
        onError: (errs) => {
            errors.value = errs as Record<string, string>;
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<template>
    <Head title="Nueva venta" />

    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <Heading
                variant="small"
                title="Nueva venta"
                description="Registra una nueva venta con sus productos"
            />

            <Button variant="ghost" size="sm" as-child>
                <Link :href="ventasRoutes.index()">
                    <ArrowLeft class="h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <Card class="bg-gradient-to-br from-purple-50 to-purple-100/30 dark:from-purple-950/20 dark:to-purple-900/10">
                <CardHeader class="border-b">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white text-sm font-bold shadow-sm">
                            <ShoppingCart class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold">Información de la venta</h3>
                            <p class="text-sm text-muted-foreground">Datos generales de la venta</p>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-6 space-y-5">
                    <div class="grid gap-2">
                        <Label for="cliente_id" class="flex items-center gap-2">
                            <User class="h-4 w-4 text-blue-500" />
                            Cliente
                        </Label>
                        <Select v-model="clienteId">
                            <SelectTrigger class="w-full focus-visible:ring-purple-500">
                                <SelectValue placeholder="Seleccionar cliente (opcional)" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="">Sin cliente</SelectItem>
                                <SelectItem
                                    v-for="cliente in clientes"
                                    :key="cliente.id"
                                    :value="String(cliente.id)"
                                >
                                    {{ cliente.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.cliente_id" />
                    </div>

                    <Separator />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="tipo_comprobante" class="flex items-center gap-2">
                                <FileText class="h-4 w-4 text-purple-500" />
                                Tipo comprobante
                            </Label>
                            <Select v-model="tipoComprobante">
                                <SelectTrigger class="w-full focus-visible:ring-purple-500">
                                    <SelectValue placeholder="Seleccionar tipo" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Boleta">Boleta</SelectItem>
                                    <SelectItem value="Factura">Factura</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.tipo_comprobante" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="nro_comprobante" class="flex items-center gap-2">
                                <FileText class="h-4 w-4 text-amber-500" />
                                Nro. comprobante
                            </Label>
                            <Input
                                id="nro_comprobante"
                                v-model="nroComprobante"
                                placeholder="001-001-0000001"
                                class="focus-visible:ring-amber-500"
                            />
                            <InputError :message="errors.nro_comprobante" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-sm">
                                <Package class="h-5 w-5" />
                            </div>
                            <div>
                                <h3 class="text-base font-semibold">Productos</h3>
                                <p class="text-sm text-muted-foreground">Agrega los productos de la venta</p>
                            </div>
                        </div>
                        <Button type="button" variant="outline" size="sm" @click="addItem">
                            <Plus class="h-4 w-4" /> Agregar producto
                        </Button>
                    </div>
                </CardHeader>

                <CardContent class="pt-6 space-y-4">
                    <div
                        v-for="(item, index) in items"
                        :key="index"
                        class="rounded-lg border border-border/60 p-4 bg-muted/30"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold text-muted-foreground">Producto #{{ index + 1 }}</span>
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="h-7 w-7 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/30"
                                @click="removeItem(index)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </div>

                        <div class="grid gap-3">
                            <div class="grid gap-2">
                                <Label :for="'producto_' + index" class="text-xs font-semibold text-muted-foreground">Producto</Label>
                                <Select
                                    :model-value="String(item.product_id ?? '')"
                                    @update:model-value="(val) => onProductSelect(index, val)"
                                >
                                    <SelectTrigger class="w-full" :id="'producto_' + index">
                                        <SelectValue placeholder="Seleccionar producto" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">Seleccionar producto</SelectItem>
                                        <SelectItem
                                            v-for="producto in productos"
                                            :key="producto.id"
                                            :value="String(producto.id)"
                                        >
                                            {{ producto.nombre }} — Bs {{ producto.precio }} (Stock: {{ producto.stock }})
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="grid gap-2">
                                    <Label :for="'cantidad_' + index" class="text-xs font-semibold text-muted-foreground">Cantidad</Label>
                                    <Input
                                        :id="'cantidad_' + index"
                                        type="number"
                                        min="1"
                                        :max="productos.find(p => p.id === item.product_id)?.stock ?? 999999"
                                        :model-value="item.cantidad"
                                        @update:model-value="(val) => { item.cantidad = Number(val); updateSubtotal(index); }"
                                        class="h-9"
                                    />
                                </div>
                                <div class="grid gap-2">
                                    <Label class="text-xs font-semibold text-muted-foreground">Precio Unit.</Label>
                                    <div class="h-9 flex items-center px-3 rounded-md border border-input bg-muted/50 text-sm text-muted-foreground">
                                        Bs {{ item.precio_unitario.toFixed(2) }}
                                    </div>
                                </div>
                                <div class="grid gap-2">
                                    <Label class="text-xs font-semibold text-muted-foreground">Subtotal</Label>
                                    <div class="h-9 flex items-center px-3 rounded-md border border-purple-200 bg-purple-50 dark:bg-purple-950/20 dark:border-purple-800/40 text-sm font-semibold text-purple-700 dark:text-purple-400">
                                        Bs {{ item.subtotal.toFixed(2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="items.length === 0"
                        class="flex flex-col items-center justify-center py-10 text-center border-2 border-dashed border-muted-foreground/20 rounded-lg"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-muted mb-3">
                            <Package class="h-6 w-6 text-muted-foreground" />
                        </div>
                        <p class="text-sm font-medium text-muted-foreground">No hay productos agregados</p>
                        <p class="text-xs text-muted-foreground/70 mt-1">Haz clic en "Agregar producto" para comenzar</p>
                    </div>

                    <InputError :message="errors.items" />
                </CardContent>
            </Card>

            <div class="flex items-center justify-end gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-muted-foreground">Total:</span>
                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400 tabular-nums">Bs {{ total.toFixed(2) }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <Button type="submit" :disabled="processing" class="min-w-[160px]">
                    {{ processing ? 'Guardando...' : 'Registrar venta' }}
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="ventasRoutes.index()">Cancelar</Link>
                </Button>
            </div>
        </form>
    </div>
</template>

