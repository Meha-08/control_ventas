<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Tag, Tags } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import categoriasRoutes from '@/routes/categorias';

type Category = {
    id: number;
    nombre: string;
    descripcion: string | null;
    active: boolean;
};

type Props = {
    category?: Category;
};

const props = defineProps<Props>();

const isEditing = computed(() => !!props.category);

const active = ref(props.category?.active ?? true);

defineOptions({
    layout: (pageProps: { category?: Category }) => ({
        breadcrumbs: [
            {
                title: 'Categorías',
                href: categoriasRoutes.index(),
            },
            {
                title: pageProps.category ? pageProps.category.nombre : 'Nueva categoría',
                href: pageProps.category ? categoriasRoutes.edit(pageProps.category.id) : categoriasRoutes.create(),
            },
        ],
    }),
});
</script>

<template>
    <Head :title="isEditing && category ? `Editar ${category.nombre}` : 'Nueva categoría'" />

    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <Heading
                variant="small"
                :title="isEditing && category ? `Editar ${category.nombre}` : 'Nueva categoría'"
                :description="isEditing ? 'Actualiza los datos de la categoría' : 'Ingresa los datos de la nueva categoría'"
            />

            <Button variant="ghost" size="sm" as-child>
                <Link :href="categoriasRoutes.index()">
                    <ArrowLeft class="h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <div class="max-w-2xl">
            <Card class="bg-gradient-to-br from-purple-50 to-purple-50/30 dark:from-purple-950/20 dark:to-purple-900/10">
                <CardHeader class="border-b">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white text-sm font-bold shadow-sm">
                            <Tags class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold">
                                Información de la categoría
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                {{ isEditing ? 'Modifica los campos que desees actualizar' : 'Completa todos los campos obligatorios' }}
                            </p>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-6">
                    <Form
                        :key="category?.id ?? 'new'"
                        v-bind="isEditing && category ? categoriasRoutes.update.form(category.id) : categoriasRoutes.store.form()"
                        class="space-y-6"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid gap-2">
                            <Label for="nombre" class="flex items-center gap-2">
                                <Tag class="h-4 w-4 text-purple-500" />
                                Nombre <span class="text-destructive">*</span>
                            </Label>
                            <Input
                                id="nombre"
                                name="nombre"
                                :default-value="category?.nombre"
                                required
                                placeholder="Nombre de la categoría"
                                class="focus-visible:ring-purple-500"
                            />
                            <InputError :message="errors.nombre" />
                        </div>

                        <Separator />

                        <div class="grid gap-2">
                            <Label for="descripcion" class="flex items-center gap-2">
                                <Tag class="h-4 w-4 text-purple-500" />
                                Descripción
                            </Label>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                :default-value="category?.descripcion ?? undefined"
                                placeholder="Descripción de la categoría (opcional)"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:border-ring focus-visible:ring-purple-500/30 transition-colors"
                                rows="3"
                            />
                            <InputError :message="errors.descripcion" />
                        </div>

                        <Separator />

                        <div v-if="isEditing" class="flex items-center gap-3 p-4 rounded-xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-100/50 dark:border-purple-900/20">
                            <input type="hidden" name="active" :value="active ? '1' : '0'" />
                            <Checkbox id="active" v-model:checked="active" value="1" class="h-5 w-5" />
                            <Label for="active" class="cursor-pointer text-sm font-medium">
                                Categoría activa
                            </Label>
                            <Badge :variant="active ? 'default' : 'outline'" class="ml-auto">
                                {{ active ? 'Activa' : 'Inactiva' }}
                            </Badge>
                        </div>

                        <Separator />

                        <div class="flex items-center gap-4 pt-2">
                            <Button type="submit" :disabled="processing" class="min-w-[140px]">
                                {{ processing ? 'Guardando...' : isEditing ? 'Guardar cambios' : 'Crear categoría' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="categoriasRoutes.index()">
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

