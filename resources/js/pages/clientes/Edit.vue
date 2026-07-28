<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Mail, MapPin, Phone, User } from 'lucide-vue-next';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import clientesRoutes from '@/routes/clientes';
import type { Cliente } from '@/types';

type Props = {
    cliente?: Cliente;
};

const props = defineProps<Props>();

const isEditing = computed(() => !!props.cliente);

defineOptions({
    layout: (props: { cliente?: Cliente }) => ({
        breadcrumbs: [
            {
                title: 'Clientes',
                href: clientesRoutes.index(),
            },
            {
                title: props.cliente ? props.cliente.name : 'Nuevo cliente',
                href: props.cliente ? clientesRoutes.edit(props.cliente.id) : clientesRoutes.create(),
            },
        ],
    }),
});
</script>

<template>
    <Head :title="isEditing && cliente ? `Editar ${cliente.name}` : 'Nuevo cliente'" />

    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <Heading
                variant="small"
                :title="isEditing && cliente ? `Editar ${cliente.name}` : 'Nuevo cliente'"
                :description="isEditing ? 'Actualiza los datos del cliente' : 'Ingresa los datos del nuevo cliente'"
            />

            <Button variant="ghost" size="sm" as-child>
                <Link :href="clientesRoutes.index()">
                    <ArrowLeft class="h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <div class="max-w-2xl">
            <Card class="bg-gradient-to-br from-blue-50 to-blue-100/30 dark:from-blue-950/20 dark:to-blue-900/10">
                <CardHeader class="border-b">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white text-sm font-bold shadow-sm">
                            <User class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold">
                                Información del cliente
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                {{ isEditing ? 'Modifica los campos que desees actualizar' : 'Completa todos los campos obligatorios' }}
                            </p>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-6">
                    <Form
                        :key="cliente?.id ?? 'new'"
                        v-bind="isEditing && cliente ? clientesRoutes.update.form(cliente.id) : clientesRoutes.store.form()"
                        class="space-y-6"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid gap-2">
                            <Label for="name" class="flex items-center gap-2">
                                <User class="h-4 w-4 text-blue-500" />
                                Nombre <span class="text-destructive">*</span>
                            </Label>
                            <Input
                                id="name"
                                name="name"
                                :default-value="cliente?.name"
                                required
                                placeholder="Nombre completo"
                                class="focus-visible:ring-blue-500"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <Separator />

                        <div class="grid gap-2">
                            <Label for="email" class="flex items-center gap-2">
                                <Mail class="h-4 w-4 text-purple-500" />
                                Email
                            </Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                :default-value="cliente?.email ?? undefined"
                                placeholder="cliente@ejemplo.com"
                                class="focus-visible:ring-purple-500"
                            />
                            <InputError :message="errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="phone" class="flex items-center gap-2">
                                <Phone class="h-4 w-4 text-violet-500" />
                                Teléfono
                            </Label>
                            <Input
                                id="phone"
                                name="phone"
                                :default-value="cliente?.phone ?? undefined"
                                placeholder="+51 999 999 999"
                                class="focus-visible:ring-violet-500"
                            />
                            <InputError :message="errors.phone" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="address" class="flex items-center gap-2">
                                <MapPin class="h-4 w-4 text-amber-500" />
                                Dirección
                            </Label>
                            <Input
                                id="address"
                                name="address"
                                :default-value="cliente?.address ?? undefined"
                                placeholder="Av. Principal 123"
                                class="focus-visible:ring-amber-500"
                            />
                            <InputError :message="errors.address" />
                        </div>

                        <Separator />

                        <div class="flex items-center gap-4 pt-2">
                            <Button type="submit" :disabled="processing" class="min-w-[140px]">
                                {{ processing ? 'Guardando...' : isEditing ? 'Guardar cambios' : 'Crear cliente' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="clientesRoutes.index()">
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

