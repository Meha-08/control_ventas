<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    BadgeCheck,
    BedDouble,
    ShieldCheck,
    Sparkles,
    Star,
    Truck,
} from 'lucide-vue-next';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';

withDefaults(defineProps<{
    title?: string;
    description?: string;
}>(), {
    title: 'Bienvenido de vuelta',
    description: 'Ingresa tus credenciales para acceder a tu cuenta',
});
</script>

<template>
    <div class="grid min-h-svh bg-linear-to-br from-sky-300 via-purple-300 to-fuchsia-400 lg:grid-cols-[1.05fr_1fr]">
        <div class="relative flex h-64 w-full flex-col justify-between overflow-hidden bg-slate-950 sm:h-80 lg:h-auto lg:min-h-svh">
            <img
                src="https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1800&q=85"
                alt="Habitación moderna ROZE"
                class="absolute inset-0 h-full w-full object-cover object-center brightness-110 saturate-125"
            />
            <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/50 to-slate-950/20"></div>
            <div class="absolute inset-0 bg-linear-to-r from-purple-950/60 via-transparent to-transparent"></div>
            <div class="animate-aurora-1 absolute -top-32 -left-24 h-96 w-96 rounded-full bg-sky-400/20 blur-3xl"></div>
            <div class="animate-aurora-2 absolute -right-24 -bottom-32 h-96 w-96 rounded-full bg-fuchsia-500/20 blur-3xl"></div>

            <div class="relative z-10 flex items-center gap-3 p-5 sm:p-8 lg:p-10">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 shadow-xl ring-1 ring-white/30 backdrop-blur-md lg:h-12 lg:w-12 lg:rounded-2xl">
                    <AppLogoIcon class="h-6 w-6 fill-current text-white lg:h-7 lg:w-7" />
                </div>
                <div>
                    <p class="text-xl font-black tracking-[0.2em] text-white">ROZE</p>
                    <p class="text-[10px] font-semibold tracking-widest text-sky-200 uppercase">Descanso premium</p>
                </div>
            </div>

            <div class="relative z-10 max-w-xl px-5 pb-6 sm:px-10 sm:pb-10 lg:px-14 lg:pb-14">
                <div class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-sky-200 ring-1 ring-white/20 backdrop-blur-md">
                    <Sparkles class="h-3.5 w-3.5" />
                    Tu descanso, nuestra pasión
                </div>

                <h2 class="text-3xl leading-tight font-black tracking-tight text-white sm:text-4xl lg:text-5xl [text-shadow:0_4px_24px_#000000aa]">
                    Duerme mejor.
                    <span class="block bg-linear-to-r from-sky-300 via-purple-300 to-fuchsia-400 bg-clip-text text-transparent">
                        Despierta mejor.
                    </span>
                </h2>

                <p class="mt-3 hidden max-w-md text-sm leading-relaxed text-white/70 sm:mt-5 sm:block lg:text-base">
                    Colchones, somieres, espaldares y almohadas diseñados para transformar tus noches. Calidad premium con entregas en toda Bolivia.
                </p>

                <div class="mt-4 flex flex-wrap gap-2 lg:mt-8 lg:gap-3">
                    <div class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-xs font-medium text-white backdrop-blur-md lg:px-4 lg:py-2.5 lg:text-sm">
                        <Truck class="h-4 w-4 text-sky-300" />
                        Envío a todo Bolivia
                    </div>
                    <div class="flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-xs font-medium text-white backdrop-blur-md lg:px-4 lg:py-2.5 lg:text-sm">
                        <BadgeCheck class="h-4 w-4 text-purple-300" />
                        Calidad premium
                    </div>
                </div>

                <div class="glass-card-dark mt-10 hidden max-w-sm rounded-2xl p-5 lg:block">
                    <div class="flex gap-1">
                        <Star v-for="i in 5" :key="i" class="h-4 w-4 fill-sky-300 text-sky-300" />
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-white/80">
                        “Cambié mi colchón y por fin duermo sin dolor de espalda. La entrega fue rapidísima.”
                    </p>
                    <p class="mt-3 text-xs font-semibold text-white/50">María G. · Cliente verificado</p>
                </div>
            </div>
        </div>

        <div class="relative flex min-h-[calc(100svh-16rem)] flex-col items-center justify-center overflow-hidden bg-linear-to-br from-sky-300 via-purple-300 to-fuchsia-300 px-6 py-10 sm:min-h-[calc(100svh-20rem)] lg:min-h-svh">
            <div class="absolute -top-28 -right-24 h-80 w-80 rounded-full bg-sky-400/60 blur-3xl"></div>
            <div class="absolute -bottom-28 -left-24 h-80 w-80 rounded-full bg-fuchsia-400/60 blur-3xl"></div>
            <div class="absolute top-1/3 -left-32 h-72 w-72 rounded-full bg-purple-400/50 blur-3xl"></div>
            <div class="pointer-events-none absolute top-1/2 left-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2 rounded-full bg-linear-to-r from-sky-500/50 via-purple-500/50 to-fuchsia-500/50 blur-3xl"></div>

            <Link
                :href="home()"
                class="relative z-10 mb-6 flex items-center gap-2 lg:hidden"
            >
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-linear-to-br from-sky-500 to-purple-600 shadow-lg">
                    <AppLogoIcon class="h-6 w-6 fill-current text-white" />
                </div>
                <span class="text-lg font-black tracking-widest text-slate-800">ROZE</span>
            </Link>

            <Card class="relative z-10 -translate-y-3 w-full max-w-md overflow-hidden rounded-3xl border-white bg-linear-to-br from-sky-300/95 via-purple-100/95 to-fuchsia-300/95 shadow-2xl shadow-purple-500/50 backdrop-blur-xl lg:translate-y-0">
                <div class="h-2.5 w-full bg-linear-to-r from-sky-500 via-purple-600 to-fuchsia-600"></div>
                <CardHeader class="px-8 pt-8 pb-0 text-center">
                    <div class="relative mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br from-sky-500 via-purple-500 to-fuchsia-600 shadow-lg shadow-purple-400/50">
                        <div class="absolute inset-0 rounded-2xl bg-fuchsia-400/40 blur-xl"></div>
                        <BedDouble class="relative h-7 w-7 text-white" />
                    </div>
                    <CardTitle class="bg-linear-to-r from-sky-700 via-purple-700 to-fuchsia-700 bg-clip-text text-2xl font-extrabold text-transparent">
                        {{ title }}
                    </CardTitle>
                    <CardDescription class="font-medium text-slate-600">
                        {{ description }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="px-8 py-7">
                    <slot />
                </CardContent>
            </Card>

            <div class="relative z-10 mt-6 flex items-center gap-2 text-xs font-medium text-slate-500">
                <ShieldCheck class="h-3.5 w-3.5 text-sky-500" />
                Conexión segura con cifrado SSL
            </div>
        </div>
    </div>
</template>
