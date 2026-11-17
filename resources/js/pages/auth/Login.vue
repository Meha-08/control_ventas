<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ArrowRight,
    Headphones,
    Lock,
    LockKeyhole,
    LogIn,
    Mail,
    ShieldCheck,
    Sparkles,
} from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: AuthSplitLayout,
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <div class="relative flex w-full flex-col gap-6">
        <Head title="Iniciar sesión" />

        <div
            v-if="status"
            class="flex items-center gap-2 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-900"
        >
            <ShieldCheck class="h-4 w-4 shrink-0 text-sky-600" />
            {{ status }}
        </div>

        <div class="flex justify-center">
            <span class="inline-flex items-center gap-2 rounded-full bg-linear-to-r from-sky-200 to-purple-200 px-3 py-1.5 text-xs font-semibold text-purple-800 ring-1 ring-purple-300">
                <Sparkles class="h-3.5 w-3.5" />
                Acceso seguro a tu cuenta ROZE
            </span>
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex w-full flex-col gap-5"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="email" class="text-sm font-semibold text-slate-800">
                        Correo electrónico
                    </Label>
                    <div class="group relative">
                        <div
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-200/80 p-0.5 text-sky-700 transition-colors group-focus-within:bg-purple-200 group-focus-within:text-purple-700"
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
                            class="h-12 rounded-xl border-sky-300 bg-linear-to-r from-white to-sky-100 pl-11 font-medium text-slate-900 placeholder:text-slate-400 transition-all duration-300 hover:border-purple-400 hover:from-white hover:to-purple-100 focus-visible:border-purple-500 focus-visible:from-white focus-visible:to-white focus-visible:ring-purple-300"
                        />
                    </div>
                    <p class="text-xs text-slate-500">
                        Usa el correo con el que te registraste.
                    </p>
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between gap-3">
                        <Label for="password" class="text-sm font-semibold text-slate-800">
                            Contraseña
                        </Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-xs font-semibold text-purple-600 transition-colors hover:text-purple-700 hover:underline"
                            :tabindex="5"
                        >
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <div class="group relative">
                        <div
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 rounded-lg bg-sky-200/80 p-0.5 text-sky-700 transition-colors group-focus-within:bg-purple-200 group-focus-within:text-purple-700"
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
                            class="h-12 rounded-xl border-sky-300 bg-linear-to-r from-white to-sky-100 pl-11 font-medium text-slate-900 placeholder:text-slate-400 transition-all duration-300 hover:border-purple-400 hover:from-white hover:to-purple-100 focus-visible:border-purple-500 focus-visible:from-white focus-visible:to-white focus-visible:ring-purple-300"
                        />
                    </div>
                    <InputError :message="errors.password" />
                </div>

                <div class="rounded-xl border border-sky-200 bg-linear-to-r from-sky-100 to-purple-100 px-4 py-3">
                    <Label
                        for="remember"
                        class="flex cursor-pointer items-center gap-3 text-sm font-medium text-slate-700"
                    >
                        <Checkbox
                            id="remember"
                            name="remember"
                            :tabindex="3"
                            class="h-4 w-4 border-sky-300 data-[state=checked]:border-purple-500 data-[state=checked]:bg-purple-500"
                        />
                        <span>Recordarme en este dispositivo</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="group relative mt-1 flex h-12 w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-linear-to-r from-sky-600 via-purple-600 to-fuchsia-600 font-bold text-white shadow-lg shadow-purple-500/40 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-purple-500/50 hover:brightness-110"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <span class="absolute inset-0 bg-linear-to-r from-sky-500 via-purple-600 to-fuchsia-700 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>
                    <Spinner v-if="processing" />
                    <LogIn v-else class="relative z-10 h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                    <span class="relative z-10">{{ processing ? 'Ingresando...' : 'Iniciar sesión' }}</span>
                    <ArrowRight v-if="!processing" class="relative z-10 h-4 w-4 opacity-0 transition-all group-hover:translate-x-1 group-hover:opacity-100" />
                </Button>
            </div>

            <div class="flex items-center gap-4">
                <div class="h-px flex-1 bg-linear-to-r from-transparent via-purple-200 to-transparent"></div>
                <span class="text-xs font-medium tracking-wide text-purple-400 uppercase">o continúa con</span>
                <div class="h-px flex-1 bg-linear-to-r from-transparent via-purple-200 to-transparent"></div>
            </div>

            <a
                href="/auth/google/redirect"
                class="group relative flex h-12 w-full items-center justify-center gap-3 rounded-xl border border-purple-300 bg-linear-to-r from-white to-purple-100 font-semibold text-slate-800 shadow-sm transition-all duration-300 hover:scale-[1.02] hover:border-purple-400 hover:from-white hover:to-fuchsia-100 hover:shadow-md"
            >
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white shadow-sm">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.24 1.26-1.44 3.69-5.5 3.69-3.31 0-6.02-2.74-6.02-6.12S8.69 5.55 12 5.55c1.89 0 3.15.8 3.87 1.49l2.64-2.54C16.82 2.94 14.62 2 12 2 6.99 2 2.93 6.06 2.93 11.07S6.99 20.13 12 20.13c6.93 0 8.64-4.85 8.64-7.35 0-.49-.05-.86-.12-1.24H12z"/>
                        <path fill="#34A853" d="M3.91 7.36l3.2 2.35C7.98 7.7 9.84 5.55 12 5.55c1.89 0 3.15.8 3.87 1.49l2.64-2.54C16.82 2.94 14.62 2 12 2 8.65 2 5.76 3.9 3.91 7.36z"/>
                        <path fill="#4A90E2" d="M12 20.13c2.56 0 4.71-.85 6.28-2.3l-2.9-2.38c-.77.54-1.8.92-3.38.92-4.05 0-5.48-2.67-5.74-3.93l-3.18 2.45C4.9 18.29 8.16 20.13 12 20.13z"/>
                        <path fill="#FBBC05" d="M6.26 12.44c-.12-.36-.19-.74-.19-1.14s.07-.78.18-1.14L3.05 7.81A9.03 9.03 0 0 0 2.93 11.3c0 1.45.35 2.82.98 4.03l3.35-2.59z"/>
                    </svg>
                </span>
                <span>Continuar con Google</span>
                <span class="absolute top-1 right-2 rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                    Rápido
                </span>
            </a>

            <div
                v-if="canRegister"
                class="rounded-xl border border-purple-200 bg-linear-to-r from-purple-100 to-fuchsia-100 px-4 py-3 text-center text-sm font-medium text-slate-800"
            >
                ¿No tienes una cuenta?
                <TextLink
                    :href="register()"
                    :tabindex="5"
                    class="ml-1 inline-flex items-center gap-1 font-bold text-slate-900 transition-all hover:text-sky-600 hover:underline"
                >
                    Regístrate ahora
                    <ArrowLeft class="h-3 w-3 rotate-180 transition-transform group-hover:translate-x-0.5" />
                </TextLink>
            </div>

            <div class="grid grid-cols-3 gap-2 border-t border-purple-100 pt-4">
                <div class="flex flex-col items-center gap-1 text-center">
                    <ShieldCheck class="h-4 w-4 text-sky-500" />
                    <span class="text-[10px] font-medium text-slate-500">Cifrado SSL</span>
                </div>
                <div class="flex flex-col items-center gap-1 text-center">
                    <LockKeyhole class="h-4 w-4 text-purple-500" />
                    <span class="text-[10px] font-medium text-slate-500">Datos seguros</span>
                </div>
                <div class="flex flex-col items-center gap-1 text-center">
                    <Headphones class="h-4 w-4 text-fuchsia-500" />
                    <span class="text-[10px] font-medium text-slate-500">Soporte 24/7</span>
                </div>
            </div>
        </Form>
    </div>
</template>
