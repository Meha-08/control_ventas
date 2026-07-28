<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineOptions({
    layout: {
        title: 'Crear una cuenta',
        description: 'Ingresa tus datos para crear tu cuenta',
    },
});
</script>

<template>
    <Head title="Registro" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="grid gap-2">
                    <Label for="nombre_u">Nombre(s)</Label>
                    <Input
                        id="nombre_u"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        name="nombre_u"
                        placeholder="Nombres"
                    />
                    <InputError :message="errors.nombre_u" />
                </div>
                <div class="grid gap-2">
                    <Label for="a_paterno">Apellido paterno</Label>
                    <Input
                        id="a_paterno"
                        type="text"
                        required
                        :tabindex="2"
                        name="a_paterno"
                        placeholder="Apellido paterno"
                    />
                    <InputError :message="errors.a_paterno" />
                </div>
                <div class="grid gap-2">
                    <Label for="a_materno">Apellido materno</Label>
                    <Input
                        id="a_materno"
                        type="text"
                        required
                        :tabindex="3"
                        name="a_materno"
                        placeholder="Apellido materno"
                    />
                    <InputError :message="errors.a_materno" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="email">Correo electrónico</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="4"
                    autocomplete="email"
                    name="email"
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Contraseña</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="5"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Contraseña"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirmar contraseña</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="6"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirmar contraseña"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                tabindex="7"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Crear cuenta
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            ¿Ya tienes una cuenta?
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="6"
                >Iniciar sesión</TextLink
            >
        </div>
    </Form>
</template>

