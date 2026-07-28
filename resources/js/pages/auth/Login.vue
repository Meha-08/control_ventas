<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Mail, Lock, LogIn, ArrowLeft, Sparkles, Shield } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Bienvenido de vuelta',
        description: 'Ingresa tus credenciales para acceder a tu cuenta',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Iniciar sesión" />

    <div class="relative flex w-full flex-col items-center justify-center gap-8">
        <div
            v-if="status"
            class="mb-2 flex w-full items-center gap-2 rounded-xl border border-sky-400/40 bg-sky-500/20 px-4 py-3 text-sm font-medium text-white backdrop-blur-sm"
        >
            <Shield class="h-4 w-4" />
            {{ status }}
        </div>

        <div class="mb-2 flex items-center gap-2">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500/30 to-sky-400/30 ring-1 ring-white/30">
                <Sparkles class="h-4 w-4 text-white" />
            </div>
            <span class="text-sm font-bold text-white">Acceso seguro</span>
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex w-full flex-col gap-5"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="email" class="text-sm font-semibold text-white">
                        Correo electrónico
                    </Label>
                    <div class="group relative">
                        <div
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                        >
                            <Mail class="h-full w-full" />
                        </div>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            placeholder="correo@ejemplo.com"
                            class="h-12 rounded-xl border-white/20 bg-white/10 pl-11 font-medium text-white placeholder:text-white/40 transition-all duration-300 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                        />
                    </div>
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-sm font-semibold text-white">
                            Contraseña
                        </Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-xs font-semibold text-sky-300 transition-colors hover:text-sky-200 hover:underline"
                            :tabindex="5"
                        >
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <div class="group relative">
                        <div
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-500/20 p-0.5 text-white/70 transition-colors group-focus-within:bg-sky-500/30 group-focus-within:text-white"
                        >
                            <Lock class="h-full w-full" />
                        </div>
                        <PasswordInput
                            id="password"
                            name="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="h-12 rounded-xl border-white/20 bg-white/10 pl-11 font-medium text-white placeholder:text-white/40 transition-all duration-300 hover:bg-white/15 focus-visible:border-sky-400/50 focus-visible:bg-white/15 focus-visible:ring-sky-400/30"
                        />
                    </div>
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between py-2">
                    <Label
                        for="remember"
                        class="flex cursor-pointer items-center gap-2 text-sm font-medium text-white/90"
                    >
                        <Checkbox
                            id="remember"
                            name="remember"
                            :tabindex="3"
                            class="h-4 w-4 border-white/30 data-[state=checked]:bg-sky-500 data-[state=checked]:border-sky-400"
                        />
                        <span>Recordarme</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="group relative mt-2 flex h-12 w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-sky-500 via-purple-500 to-purple-600 font-bold text-white text-white shadow-lg shadow-sky-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-sky-500/40 hover:brightness-110"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <span class="absolute inset-0 bg-gradient-to-r from-sky-400 via-purple-500 to-purple-700 opacity-0 transition-opacity group-hover:opacity-100"></span>
                    <Spinner v-if="processing" />
                    <LogIn v-else class="relative z-10 h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                    <span class="relative z-10">Iniciar sesión</span>
                </Button>
            </div>

            <div class="my-2 flex items-center gap-4">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                <span class="text-xs font-medium text-white/70">o</span>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            </div>

            <div
                class="text-center text-sm font-medium text-white/80"
                v-if="canRegister"
            >
                ¿No tienes una cuenta?
                <TextLink
                    :href="register()"
                    :tabindex="5"
                    class="ml-1 font-bold text-white transition-all hover:text-sky-300 hover:underline"
                >
                    <span class="flex items-center justify-center gap-1">
                        <ArrowLeft class="h-3 w-3 rotate-180 transition-transform group-hover:translate-x-0.5" />
                        Regístrate ahora
                    </span>
                </TextLink>
            </div>
        </Form>

        <div class="mt-4 flex items-center gap-2 text-white/60">
            <Shield class="h-3.5 w-3.5" />
            <span class="text-xs font-medium">Conexión segura con cifrado SSL</span>
        </div>
    </div>
</template>

