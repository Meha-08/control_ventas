<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Bed, BedDouble, BedSingle, Calendar, DollarSign, Image, Ruler, ShieldCheck, ShoppingBag, Tag, Upload, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';

import descansoProductosRoutes from '@/routes/descanso-productos';

type DescansoProductoForm = {
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
    estado: string;
    fecha_registro: string;
};

type Props = {
    product?: DescansoProductoForm;
    categorias?: { id: number; nombre: string }[];
};

const props = defineProps<Props>();

const isEditing = computed(() => !!props.product);

const imagePreview = ref<string | null>(props.product?.imagen_url ?? null);
const fileInput = ref<HTMLInputElement | null>(null);

const onFileSelected = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const clearImage = () => {
    imagePreview.value = null;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

defineOptions({
    layout: (pageProps: { product?: DescansoProductoForm }) => ({
        breadcrumbs: [
            {
                title: 'Productos para el Descanso',
                href: descansoProductosRoutes.index(),
            },
            {
                title: pageProps.product ? pageProps.product.nombre_producto : 'Nuevo producto',
                href: pageProps.product ? descansoProductosRoutes.edit(pageProps.product.id) : descansoProductosRoutes.create(),
            },
        ],
    }),
});
</script>

<template>
    <Head :title="isEditing && product ? `Editar ${product.nombre_producto}` : 'Nuevo producto de descanso'" />

    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <Heading
                variant="small"
                :title="isEditing && product ? `Editar ${product.nombre_producto}` : 'Nuevo producto de descanso'"
                :description="isEditing ? 'Actualiza los datos del producto' : 'Completa los datos del producto'"
            />

            <Button variant="ghost" size="sm" as-child>
                <Link :href="descansoProductosRoutes.index()">
                    <ArrowLeft class="h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <div class="max-w-2xl">
            <Card class="bg-gradient-to-br from-sky-50 to-indigo-50/30 dark:from-sky-950/20 dark:to-indigo-900/10">
                <CardHeader class="border-b">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-500 text-white text-sm font-bold shadow-sm">
                            <BedDouble class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold">
                                Información del producto
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                {{ isEditing ? 'Modifica los campos que desees actualizar' : 'Completa todos los campos obligatorios' }}
                            </p>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-6">
                    <Form
                        :key="product?.id ?? 'new'"
                        v-bind="isEditing && product ? descansoProductosRoutes.update.form(product.id) : descansoProductosRoutes.store.form()"
                        class="space-y-6"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid gap-2">
                            <Label for="nombre_producto" class="flex items-center gap-2">
                                <Tag class="h-4 w-4 text-sky-500" />
                                Nombre del producto <span class="text-destructive">*</span>
                            </Label>
                            <Input
                                id="nombre_producto"
                                name="nombre_producto"
                                :default-value="product?.nombre_producto"
                                required
                                placeholder="Ej: Colchón Ortopédico Premium"
                                class="focus-visible:ring-sky-500"
                            />
                            <InputError :message="errors.nombre_producto" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tipo_producto" class="flex items-center gap-2">
                                <BedDouble class="h-4 w-4 text-indigo-500" />
                                Tipo de producto <span class="text-destructive">*</span>
                            </Label>
                            <select
                                id="tipo_producto"
                                name="tipo_producto"
                                :default-value="product?.tipo_producto"
                                required
                                class="flex h-10 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <option value="" disabled>Seleccionar tipo</option>
                                <option v-for="cat in categorias" :key="cat.id" :value="cat.nombre">
                                    {{ cat.nombre }}
                                </option>
                            </select>
                            <InputError :message="errors.tipo_producto" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="descripcion" class="flex items-center gap-2">
                                <ShoppingBag class="h-4 w-4 text-purple-500" />
                                Descripción
                            </Label>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                :default-value="product?.descripcion ?? undefined"
                                placeholder="Describe las características del producto..."
                                class="flex min-h-[100px] w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            ></textarea>
                            <InputError :message="errors.descripcion" />
                        </div>

                        <Separator />

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="grid gap-2">
                                <Label for="tamaño" class="flex items-center gap-2">
                                    <Ruler class="h-4 w-4 text-purple-500" />
                                    Tamaño
                                </Label>
                                <Input
                                    id="tamaño"
                                    name="tamaño"
                                    :default-value="product?.tamaño ?? undefined"
                                    placeholder="Ej: 1.50 x 1.90 m"
                                    class="focus-visible:ring-purple-500"
                                />
                                <InputError :message="errors.tamaño" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="material" class="flex items-center gap-2">
                                    <ShoppingBag class="h-4 w-4 text-amber-500" />
                                    Material
                                </Label>
                                <Input
                                    id="material"
                                    name="material"
                                    :default-value="product?.material ?? undefined"
                                    placeholder="Ej: Espuma viscoelástica"
                                    class="focus-visible:ring-amber-500"
                                />
                                <InputError :message="errors.material" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="color" class="flex items-center gap-2">
                                    <BedSingle class="h-4 w-4 text-sky-500" />
                                    Color
                                </Label>
                                <Input
                                    id="color"
                                    name="color"
                                    :default-value="product?.color ?? undefined"
                                    placeholder="Ej: Blanco"
                                    class="focus-visible:ring-sky-500"
                                />
                                <InputError :message="errors.color" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="precio_venta" class="flex items-center gap-2">
                                    <DollarSign class="h-4 w-4 text-purple-500" />
                                    Precio de venta <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="precio_venta"
                                    name="precio_venta"
                                    type="number"
                                    step="0.01"
                                    :default-value="product?.precio_venta"
                                    required
                                    placeholder="0.00"
                                    class="focus-visible:ring-purple-500"
                                />
                                <InputError :message="errors.precio_venta" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="stock" class="flex items-center gap-2">
                                    <ShoppingBag class="h-4 w-4 text-blue-500" />
                                    Stock <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="stock"
                                    name="stock"
                                    type="number"
                                    :default-value="product?.stock"
                                    required
                                    placeholder="0"
                                    class="focus-visible:ring-blue-500"
                                />
                                <InputError :message="errors.stock" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="garantia" class="flex items-center gap-2">
                                    <ShieldCheck class="h-4 w-4 text-orange-500" />
                                    Garantía
                                </Label>
                                <Input
                                    id="garantia"
                                    name="garantia"
                                    :default-value="product?.garantia ?? undefined"
                                    placeholder="Ej: 5 años"
                                    class="focus-visible:ring-orange-500"
                                />
                                <InputError :message="errors.garantia" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="fecha_registro" class="flex items-center gap-2">
                                    <Calendar class="h-4 w-4 text-purple-500" />
                                    Fecha de registro
                                </Label>
                                <Input
                                    id="fecha_registro"
                                    name="fecha_registro"
                                    type="date"
                                    :default-value="product?.fecha_registro ?? new Date().toISOString().split('T')[0]"
                                    class="focus-visible:ring-purple-500"
                                />
                                <InputError :message="errors.fecha_registro" />
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label class="flex items-center gap-2">
                                <Image class="h-4 w-4 text-purple-500" />
                                Imagen del producto
                            </Label>

                            <div class="flex flex-col gap-3">
                                <div
                                    v-if="imagePreview"
                                    class="relative w-48 h-48 rounded-xl overflow-hidden border border-sidebar-border/60 bg-muted/30"
                                >
                                    <img
                                        :src="imagePreview"
                                        alt="Vista previa"
                                        class="w-full h-full object-cover"
                                    />
                                    <button
                                        type="button"
                                        class="absolute top-2 right-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
                                        @click="clearImage"
                                    >
                                        <X class="h-4 w-4" />
                                    </button>
                                </div>

                                <label
                                    class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-sidebar-border/60 px-6 py-8 cursor-pointer hover:border-sky-400 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 transition-colors"
                                    :class="{ 'border-sky-400 bg-sky-50/50 dark:bg-sky-950/20': imagePreview }"
                                >
                                    <Upload class="h-8 w-8 text-muted-foreground" />
                                    <div class="text-center">
                                        <p class="text-sm font-medium">
                                            Haz clic para seleccionar una imagen
                                        </p>
                                        <p class="text-xs text-muted-foreground mt-1">
                                            PNG, JPG o WebP - Máx 2 MB
                                        </p>
                                    </div>
                                    <input
                                        ref="fileInput"
                                        id="imagen"
                                        name="imagen"
                                        type="file"
                                        accept="image/jpeg,image/png,image/webp"
                                        class="sr-only"
                                        @change="onFileSelected"
                                    />
                                </label>
                            </div>
                            <InputError :message="errors.imagen" />
                        </div>

                        <Separator />

                        <div class="grid gap-2">
                            <Label for="estado" class="flex items-center gap-2">
                                <Bed class="h-4 w-4 text-sky-500" />
                                Estado <span class="text-destructive">*</span>
                            </Label>
                            <select
                                id="estado"
                                name="estado"
                                :default-value="product?.estado ?? 'Disponible'"
                                required
                                class="flex h-10 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <option value="Disponible">Disponible</option>
                                <option value="No disponible">No disponible</option>
                            </select>
                            <InputError :message="errors.estado" />
                        </div>

                        <Separator />

                        <div class="flex items-center gap-4 pt-2">
                            <Button type="submit" :disabled="processing" class="min-w-[160px]">
                                {{ processing ? 'Guardando...' : isEditing ? 'Guardar cambios' : 'Crear producto' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="descansoProductosRoutes.index()">
                                    Cancelar
                                </Link>
                            </Button>
                        </div>
                    </Form>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

