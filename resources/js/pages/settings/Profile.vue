<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Configuración de perfil',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Configuración de perfil" />

    <h1 class="sr-only">Configuración de perfil</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Información del perfil"
            description="Actualiza tu nombre y correo electrónico"
        />

        <Form
            v-bind="ProfileController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="grid gap-2">
                                <Label for="nombre_u">Nombre(s)</Label>
                                <Input
                                    id="nombre_u"
                                    type="text"
                                    required
                                    autofocus
                                    tabindex="1"
                                    name="nombre_u"
                                    :default-value="user.nombre_u"
                                    placeholder="Nombres"
                                />
                                <InputError class="mt-2" :message="errors.nombre_u" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="a_paterno">Ap. Paterno</Label>
                                <Input
                                    id="a_paterno"
                                    type="text"
                                    required
                                    tabindex="2"
                                    name="a_paterno"
                                    :default-value="user.a_paterno"
                                    placeholder="Apellido paterno"
                                />
                                <InputError class="mt-2" :message="errors.a_paterno" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="a_materno">Ap. Materno</Label>
                                <Input
                                    id="a_materno"
                                    type="text"
                                    tabindex="3"
                                    name="a_materno"
                                    :default-value="user.a_materno ?? undefined"
                                    placeholder="Apellido materno"
                                />
                                <InputError class="mt-2" :message="errors.a_materno" />
                            </div>
                        </div>

            <div class="grid gap-2">
                <Label for="email">Correo electrónico</Label>
                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    name="email"
                    :default-value="user.email"
                    required
                    autocomplete="username"
                    placeholder="correo@ejemplo.com"
                />
                <InputError class="mt-2" :message="errors.email" />
            </div>

            <div v-if="mustVerifyEmail && !user.email_verified_at">
                <p class="-mt-4 text-sm text-muted-foreground">
                    Tu correo electrónico no está verificado.
                    <Link
                        :href="send()"
                        as="button"
                        class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                    >
                        Haz clic aquí para reenviar el correo de verificación.
                    </Link>
                </p>

                <div
                    v-if="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-purple-600"
                >
                    Se ha enviado un nuevo enlace de verificación a tu correo.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="processing" data-test="update-profile-button"
                    >Guardar</Button
                >
            </div>
        </Form>
    </div>

    <DeleteUser />
</template>

