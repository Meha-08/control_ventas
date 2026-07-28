<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Ban, Check, CheckCheck, Shield, X } from 'lucide-vue-next';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import rolesRoutes from '@/routes/roles';
import type { Role } from '@/types';

type PermissionGroup = Record<string, { nombre: string; label: string }[]>;

type Props = {
    role?: Role & { permissions: { nombre: string }[] };
    permissionGroups: PermissionGroup;
};

const props = defineProps<Props>();

const isEditing = !!props.role;

const form = useForm({
    nombre: props.role?.nombre ?? '',
    descripcion: props.role?.descripcion ?? '',
    permissions: props.role?.permissions.map((p) => p.nombre) ?? [],
});

const groupLabels: Record<string, string> = {
    panel: 'Panel',
    clientes: 'Clientes',
    productos: 'Productos',
    categorias: 'Categorías',
    ventas: 'Ventas',
    inventario: 'Inventario',
    reportes: 'Reportes',
    usuarios: 'Usuarios',
};

defineOptions({
    layout: (pageProps: { role?: Role & { permissions: { nombre: string }[] } }) => ({
        breadcrumbs: [
            {
                title: 'Roles',
                href: rolesRoutes.index(),
            },
            {
                title: pageProps.role ? pageProps.role.nombre : 'Nuevo rol',
                href: pageProps.role ? rolesRoutes.edit(pageProps.role.id) : rolesRoutes.create(),
            },
        ],
    }),
});

const checkedPermissions = computed(() => new Set(form.permissions));

function isChecked(permNombre: string): boolean {
    return checkedPermissions.value.has(permNombre);
}

function isGroupAllChecked(perms: { nombre: string }[]): boolean {
    return perms.length > 0 && perms.every((p) => form.permissions.includes(p.nombre));
}

function isGroupSomeChecked(perms: { nombre: string }[]): boolean {
    return perms.some((p) => form.permissions.includes(p.nombre)) && !isGroupAllChecked(perms);
}

function selectAllInGroup(perms: { nombre: string }[]): void {
    const names = perms.map((p) => p.nombre);
    const missing = names.filter((n) => !form.permissions.includes(n));
    form.permissions.push(...missing);
}

function deselectAllInGroup(perms: { nombre: string }[]): void {
    const names = new Set(perms.map((p) => p.nombre));
    form.permissions = form.permissions.filter((p) => !names.has(p));
}

const submit = () => {
    const url = isEditing
        ? rolesRoutes.update(props.role!.id).url
        : rolesRoutes.store().url;
    const method = isEditing ? 'patch' : 'post';
    form[method](url, { preserveScroll: true });
};
</script>

<template>
    <Head :title="isEditing && role ? `Editar ${role.nombre}` : 'Nuevo rol'" />

    <div class="flex flex-col space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 flex flex-col items-center">
                <div class="flex items-center justify-center mb-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/20 ring-4 ring-purple-100 dark:ring-purple-900/30">
                        <Shield class="h-7 w-7" />
                    </div>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-center">
                    {{ isEditing && role ? `Editar ${role.nombre}` : 'Nuevo rol' }}
                </h2>
                <p class="text-sm text-muted-foreground text-center mt-1 max-w-md">
                    {{ isEditing ? 'Actualiza los datos y permisos del rol' : 'Crea un nuevo rol con sus permisos' }}
                </p>
                <div class="mt-3 flex items-center gap-2">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-purple-300 dark:to-purple-700"></span>
                    <span class="flex h-1.5 w-1.5 rounded-full bg-purple-400"></span>
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-purple-300 dark:to-purple-700"></span>
                </div>
            </div>

            <Button variant="ghost" size="sm" as-child class="shrink-0">
                <Link :href="rolesRoutes.index()">
                    <ArrowLeft class="h-4 w-4" /> Volver
                </Link>
            </Button>
        </div>

        <form :key="role?.id ?? 'new'" @submit.prevent="submit" class="flex flex-col gap-6">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <Shield class="h-5 w-5" /> Información del rol
                    </CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="nombre">Nombre del rol</Label>
                        <Input
                            id="nombre"
                            v-model="form.nombre"
                            required
                            placeholder="ej: supervisor"
                        />
                        <InputError :message="form.errors.nombre" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="descripcion">Descripción</Label>
                        <Input
                            id="descripcion"
                            v-model="form.descripcion"
                            placeholder="Breve descripción del rol"
                        />
                        <InputError :message="form.errors.descripcion" />
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <Shield class="h-5 w-5" /> Permisos
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="(perms, group) in permissionGroups"
                            :key="group"
                            class="space-y-3"
                        >
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-foreground">
                                    {{ groupLabels[group] || group }}
                                </h4>
                                <div class="flex items-center gap-1">
                                    <button
                                        type="button"
                                        :class="[
                                            'flex items-center gap-1 rounded px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider transition-all',
                                            isGroupAllChecked(perms)
                                                ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400'
                                                : 'bg-muted text-muted-foreground hover:text-foreground'
                                        ]"
                                        @click="selectAllInGroup(perms)"
                                        :title="'Seleccionar todos'"
                                    >
                                        <CheckCheck class="h-3 w-3" />
                                        Todos
                                    </button>
                                    <button
                                        type="button"
                                        :class="[
                                            'flex items-center gap-1 rounded px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider transition-all',
                                            isGroupAllChecked(perms)
                                                ? 'text-muted-foreground hover:text-foreground bg-muted'
                                                : 'bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-400'
                                        ]"
                                        @click="deselectAllInGroup(perms)"
                                        :title="'Deseleccionar todos'"
                                    >
                                        <X class="h-3 w-3" />
                                        Ninguno
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    v-for="perm in perms"
                                    :key="perm.nombre"
                                    :class="[
                                        'flex cursor-pointer items-start gap-3 rounded-lg border p-3 transition-all duration-150',
                                        isChecked(perm.nombre)
                                            ? 'border-primary/40 bg-primary/5 shadow-xs'
                                            : 'border-muted-foreground/20 bg-transparent opacity-70 hover:opacity-100'
                                    ]"
                                >
                                    <Checkbox
                                        :value="perm.nombre"
                                        :checked="isChecked(perm.nombre)"
                                        @update:checked="(checked) => {
                                            if (checked) {
                                                form.permissions.push(perm.nombre);
                                            } else {
                                                form.permissions = form.permissions.filter(p => p !== perm.nombre);
                                            }
                                        }"
                                        class="mt-0.5"
                                    />
                                    <div class="flex flex-1 items-center justify-between gap-2">
                                        <div class="grid gap-0.5">
                                            <span class="text-sm font-medium leading-none">{{ perm.label }}</span>
                                            <span class="text-xs text-muted-foreground">{{ perm.nombre }}</span>
                                        </div>
                                        <div
                                            :class="[
                                                'flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold',
                                                isChecked(perm.nombre)
                                                    ? 'bg-primary/10 text-primary'
                                                    : 'bg-muted text-muted-foreground/50'
                                            ]"
                                        >
                                            <Check v-if="isChecked(perm.nombre)" class="h-3 w-3" />
                                            <Ban v-else class="h-3 w-3" />
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <InputError :message="form.errors.permissions" />
                </CardContent>
            </Card>

            <div class="flex items-center gap-3">
                <Button type="submit" :disabled="form.processing">
                    {{ isEditing ? 'Actualizar rol' : 'Crear rol' }}
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="rolesRoutes.index()">Cancelar</Link>
                </Button>
            </div>
        </form>
    </div>
</template>

