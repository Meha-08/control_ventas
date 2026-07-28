<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, FileText, Key, Mail, MapPin, Phone, Shield, Sparkles, User, UserPen } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';

type UserForm = {
    id: number;
    name: string;
    nombre_u: string;
    a_paterno: string;
    a_materno: string | null;
    email: string;
    role: string | null;
    active: boolean;
    direccion?: string | null;
    ci?: string | null;
    fecha_ingreso?: string | null;
    fecha_fin?: string | null;
    telefono?: string | null;
};

type UserStats = {
    admins: number;
    vendedores: number;
    clientes: number;
};

type Props = {
    user?: UserForm;
    roles: string[];
    userStats?: UserStats;
};

const props = defineProps<Props>();

const isEditing = computed(() => !!props.user);

const active = ref(props.user?.active ?? true);
const selectedRole = ref(props.user?.role ?? undefined);

const roleLabels: Record<string, { label: string; sublabel: string }> = {
    admin: { label: 'Administrador', sublabel: 'admin' },
    vendedor: { label: 'Vendedor', sublabel: 'vendedor' },
    cliente: { label: 'Cliente', sublabel: 'cliente' },
};

defineOptions({
    layout: (routeProps: { user?: UserForm }) => ({
        breadcrumbs: [
            {
                title: 'Usuarios',
                href: '/usuarios',
            },
            {
                title: routeProps.user ? routeProps.user.name : 'Nuevo usuario',
                href: routeProps.user ? `/usuarios/${routeProps.user.id}/edit` : '/usuarios/create',
            },
        ],
    }),
});
</script>

<template>
    <Head :title="isEditing && user ? `Editar ${user.name}` : 'Nuevo usuario'" />

    <div class="flex flex-col space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 flex flex-col items-center">
                <div class="flex items-center justify-center mb-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/20 ring-4 ring-purple-100 dark:ring-purple-900/30">
                        <component :is="isEditing ? UserPen : User" class="h-7 w-7" />
                    </div>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-center">
                    {{ isEditing ? `Editar ${user?.name}` : 'Nuevo usuario' }}
                </h2>
                <p class="text-sm text-muted-foreground text-center mt-1 max-w-md">
                    {{ isEditing ? 'Actualiza los datos del usuario' : 'Ingresa los datos del nuevo usuario' }}
                </p>
                <div class="mt-3 flex items-center gap-2">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-purple-300 dark:to-purple-700"></span>
                    <span class="flex h-1.5 w-1.5 rounded-full bg-purple-400"></span>
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-purple-300 dark:to-purple-700"></span>
                </div>
            </div>

            <Button variant="ghost" size="sm" as-child class="shrink-0">
                <Link :href="'/usuarios'">
                    <ArrowLeft class="h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <div class="w-full">
            <Card class="shadow-lg border-purple-100 dark:border-purple-900/30">
                <CardHeader class="border-b border-purple-100 dark:border-purple-900/30">
                    <h3 class="text-lg font-bold tracking-tight">
                        Información del usuario
                    </h3>
                </CardHeader>

                <CardContent class="pt-8">
                    <Form
                        :key="user?.id ?? 'new'"
                        v-bind="isEditing && user
                            ? { action: `/usuarios/${user.id}`, method: 'patch' }
                            : { action: '/usuarios', method: 'post' }"
                        autocomplete="off"
                        class="space-y-8"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div class="grid gap-2">
                                <Label for="nombre_u" class="flex items-center gap-2 text-sm font-semibold">
                                    <User class="h-4 w-4 text-purple-500" />
                                    Nombre(s) <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="nombre_u"
                                    name="nombre_u"
                                    :default-value="user?.nombre_u"
                                    required
                                    placeholder="Nombres"
                                    class="h-10 focus-visible:ring-purple-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.nombre_u" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="a_paterno" class="flex items-center gap-2 text-sm font-semibold">
                                    <User class="h-4 w-4 text-purple-500" />
                                    Ap. Paterno <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="a_paterno"
                                    name="a_paterno"
                                    :default-value="user?.a_paterno"
                                    required
                                    placeholder="Apellido paterno"
                                    class="h-10 focus-visible:ring-purple-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.a_paterno" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="a_materno" class="flex items-center gap-2 text-sm font-semibold">
                                    <User class="h-4 w-4 text-purple-500" />
                                    Ap. Materno
                                </Label>
                                <Input
                                    id="a_materno"
                                    name="a_materno"
                                    :default-value="user?.a_materno ?? undefined"
                                    placeholder="Apellido materno"
                                    class="h-10 focus-visible:ring-purple-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.a_materno" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="ci" class="flex items-center gap-2 text-sm font-semibold">
                                    <FileText class="h-4 w-4 text-indigo-500" />
                                    CI / Cédula de Identidad
                                </Label>
                                <Input
                                    id="ci"
                                    name="ci"
                                    :default-value="user?.ci ?? undefined"
                                    placeholder="1234567"
                                    class="h-10 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.ci" />
                            </div>
                        </div>

                        <Separator class="bg-purple-100/50 dark:bg-purple-900/20" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div class="grid gap-2">
                                <Label for="email" class="flex items-center gap-2 text-sm font-semibold">
                                    <Mail class="h-4 w-4 text-blue-500" />
                                    Email <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="new-email"
                                    :readonly="!isEditing"
                                    :default-value="user?.email"
                                    @focus="(e) => { if (!isEditing) (e.target as HTMLInputElement).removeAttribute('readonly') }"
                                    required
                                    placeholder="usuario@ejemplo.com"
                                    class="h-10 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="telefono" class="flex items-center gap-2 text-sm font-semibold">
                                    <Phone class="h-4 w-4 text-sky-500" />
                                    Teléfono
                                </Label>
                                <Input
                                    id="telefono"
                                    name="telefono"
                                    :default-value="user?.telefono ?? undefined"
                                    placeholder="999 999 999"
                                    class="h-10 focus-visible:ring-sky-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.telefono" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="direccion" class="flex items-center gap-2 text-sm font-semibold">
                                    <MapPin class="h-4 w-4 text-purple-500" />
                                    Dirección
                                </Label>
                                <Input
                                    id="direccion"
                                    name="direccion"
                                    :default-value="user?.direccion ?? undefined"
                                    placeholder="Calle, Nro, Zona, Ciudad"
                                    class="h-10 focus-visible:ring-purple-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.direccion" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="fecha_ingreso" class="flex items-center gap-2 text-sm font-semibold">
                                    <Calendar class="h-4 w-4 text-purple-500" />
                                    Fecha de ingreso
                                </Label>
                                <Input
                                    id="fecha_ingreso"
                                    name="fecha_ingreso"
                                    type="date"
                                    :default-value="user?.fecha_ingreso ?? undefined"
                                    class="h-10 focus-visible:ring-purple-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.fecha_ingreso" />
                            </div>
                        </div>

                        <Separator class="bg-purple-100/50 dark:bg-purple-900/20" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div class="grid gap-2">
                                <Label for="fecha_fin" class="flex items-center gap-2 text-sm font-semibold">
                                    <Calendar class="h-4 w-4 text-red-500" />
                                    Fecha de fin
                                </Label>
                                <Input
                                    id="fecha_fin"
                                    name="fecha_fin"
                                    type="date"
                                    :default-value="user?.fecha_fin ?? undefined"
                                    class="h-10 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.fecha_fin" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password" class="flex items-center gap-2 text-sm font-semibold">
                                    <Key class="h-4 w-4 text-amber-500" />
                                    Contraseña <span v-if="!isEditing" class="text-destructive">*</span>
                                    <span v-else class="text-xs text-muted-foreground font-normal">(dejar vacío para mantener)</span>
                                </Label>
                                <Input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    :required="!isEditing"
                                    :readonly="!isEditing"
                                    @focus="(e) => { if (!isEditing) (e.target as HTMLInputElement).removeAttribute('readonly') }"
                                    placeholder="Mínimo 8 caracteres"
                                    class="h-10 focus-visible:ring-amber-500 focus-visible:ring-offset-2"
                                />
                                <InputError :message="errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="role" class="flex items-center gap-2 text-sm font-semibold">
                                    <Shield class="h-4 w-4 text-purple-500" />
                                    Rol <span class="text-destructive">*</span>
                                </Label>
                                <input type="hidden" name="role" :value="selectedRole" />
                                <Select v-model="selectedRole">
                                    <SelectTrigger class="w-full h-10 focus-visible:ring-purple-500 focus-visible:ring-offset-2">
                                        <SelectValue placeholder="Seleccionar rol" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="role in roles" :key="role" :value="role">
                                                {{ roleLabels[role]?.label ?? role }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="errors.role" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 rounded-xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-100/50 dark:border-purple-900/20">
                            <input type="hidden" name="active" :value="active ? '1' : '0'" />
                            <Checkbox id="active" v-model:checked="active" value="1" class="h-5 w-5" />
                            <Label for="active" class="cursor-pointer text-sm font-medium">
                                Usuario activo
                            </Label>
                            <Badge :variant="active ? 'default' : 'outline'" class="ml-auto">
                                {{ active ? 'Activo' : 'Inactivo' }}
                            </Badge>
                        </div>

                        <Separator class="bg-purple-100/50 dark:bg-purple-900/20" />

                        <div class="flex items-center justify-center gap-4 pt-2">
                            <Button type="submit" :disabled="processing" class="min-w-[160px] h-11 font-semibold shadow-md hover:shadow-lg transition-all">
                                {{ processing ? 'Guardando...' : isEditing ? 'Guardar cambios' : 'Crear usuario' }}
                            </Button>
                            <Button variant="outline" class="h-11" as-child>
                                <Link :href="'/usuarios'">
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

