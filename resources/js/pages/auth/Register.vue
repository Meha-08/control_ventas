<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    Mail,
    Lock,
    User,
    UserPlus,
    ArrowRight,
    Sparkles,
    Shield,
    CheckCircle,
} from 'lucide-vue-next';
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
            <div class="mb-2 flex items-center gap-2">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500/30 to-sky-400/30 ring-1 ring-white/30"
                >
                    <Sparkles class="h-4 w-4 text-white" />
                </div>
                <span class="text-sm font-bold text-white">Únete ahora</span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="grid gap-2">
                    <Label
                        for="nombre_u"
                        class="text-sm font-semibold text-white"
                    >
                        Nombre(s)
                    </Label>
                    <div class="group relative">
                        <div
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                        >
                            <User class="h-full w-full" />
                        </div>
                        <Input
                            id="nombre_u"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            name="nombre_u"
                            placeholder="Nombres"
                            class="h-11 rounded-xl border-white/20 bg-white/10 pl-10 font-medium text-white transition-all duration-300 placeholder:text-white/40 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                        />
                    </div>
                    <InputError :message="errors.nombre_u" />
                </div>
                <div class="grid gap-2">
                    <Label
                        for="a_paterno"
                        class="text-sm font-semibold text-white"
                    >
                        Apellido paterno
                    </Label>
                    <div class="group relative">
                        <div
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                        >
                            <User class="h-full w-full" />
                        </div>
                        <Input
                            id="a_paterno"
                            type="text"
                            required
                            :tabindex="2"
                            name="a_paterno"
                            placeholder="Apellido paterno"
                            class="h-11 rounded-xl border-white/20 bg-white/10 pl-10 font-medium text-white transition-all duration-300 placeholder:text-white/40 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                        />
                    </div>
                    <InputError :message="errors.a_paterno" />
                </div>
                <div class="grid gap-2">
                    <Label
                        for="a_materno"
                        class="text-sm font-semibold text-white"
                    >
                        Apellido materno
                    </Label>
                    <div class="group relative">
                        <div
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                        >
                            <User class="h-full w-full" />
                        </div>
                        <Input
                            id="a_materno"
                            type="text"
                            required
                            :tabindex="3"
                            name="a_materno"
                            placeholder="Apellido materno"
                            class="h-11 rounded-xl border-white/20 bg-white/10 pl-10 font-medium text-white transition-all duration-300 placeholder:text-white/40 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                        />
                    </div>
                    <InputError :message="errors.a_materno" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="email" class="text-sm font-semibold text-white">
                    Correo electrónico
                </Label>
                <div class="group relative">
                    <div
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                    >
                        <Mail class="h-full w-full" />
                    </div>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="4"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                        class="h-12 rounded-xl border-white/20 bg-white/10 pl-11 font-medium text-white transition-all duration-300 placeholder:text-white/40 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                    />
                </div>
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password" class="text-sm font-semibold text-white">
                    Contraseña
                </Label>
                <div class="group relative">
                    <div
                        class="absolute top-1/2 left-3 z-10 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                    >
                        <Lock class="h-full w-full" />
                    </div>
                    <PasswordInput
                        id="password"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Contraseña"
                        class="h-12 rounded-xl border-white/20 bg-white/10 pl-11 font-medium text-white transition-all duration-300 placeholder:text-white/40 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                    />
                </div>
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label
                    for="password_confirmation"
                    class="text-sm font-semibold text-white"
                >
                    Confirmar contraseña
                </Label>
                <div class="group relative">
                    <div
                        class="absolute top-1/2 left-3 z-10 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                    >
                        <CheckCircle class="h-full w-full" />
                    </div>
                    <PasswordInput
                        id="password_confirmation"
                        required
                        :tabindex="6"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirmar contraseña"
                        class="h-12 rounded-xl border-white/20 bg-white/10 pl-11 font-medium text-white transition-all duration-300 placeholder:text-white/40 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                    />
                </div>
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="group relative mt-2 flex h-12 w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-purple-600 via-purple-500 to-sky-500 font-bold text-white shadow-lg shadow-purple-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/40 hover:brightness-110"
                tabindex="7"
                :disabled="processing"
                data-test="register-user-button"
            >
                <span
                    class="absolute inset-0 bg-gradient-to-r from-purple-700 via-purple-600 to-sky-400 opacity-0 transition-opacity group-hover:opacity-100"
                ></span>
                <Spinner v-if="processing" />
                <UserPlus
                    v-else
                    class="relative z-10 h-4 w-4 transition-transform group-hover:translate-x-0.5"
                />
                <span class="relative z-10">Crear cuenta</span>
            </Button>
        </div>

        <div class="my-1 flex items-center gap-4">
            <div
                class="h-px flex-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"
            ></div>
            <span class="text-xs font-medium text-white/70">o</span>
            <div
                class="h-px flex-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"
            ></div>
        </div>

        <div class="text-center text-sm font-medium text-white/80">
            ¿Ya tienes una cuenta?
            <TextLink
                :href="login()"
                class="ml-1 font-bold text-white transition-all hover:text-sky-300 hover:underline"
                :tabindex="6"
            >
                <span class="flex items-center justify-center gap-1">
                    <ArrowRight
                        class="h-3 w-3 transition-transform group-hover:translate-x-0.5"
                    />
                    Iniciar sesión
                </span>
            </TextLink>
        </div>

        <div class="mt-2 flex items-center justify-center gap-2 text-white/60">
            <Shield class="h-3.5 w-3.5" />
            <span class="text-xs font-medium">Tus datos están protegidos</span>
        </div>
    </Form>
</template>
