<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();

const pseudoRandom = (seed: number): number => {
    const value = Math.sin(seed * 12.9898) * 43758.5453;

    return value - Math.floor(value);
};

const bubbles = Array.from({ length: 12 }, (_, i) => ({
    id: i,
    size: 20 + pseudoRandom(i + 1) * 80,
    left: pseudoRandom(i + 13) * 100,
    delay: pseudoRandom(i + 29) * 8,
    duration: 8 + pseudoRandom(i + 47) * 12,
    opacity: 0.06 + pseudoRandom(i + 67) * 0.08,
}));
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden p-6 md:p-10"
        style="background: linear-gradient(135deg, #ffffff 0%, #7dd3fc 18%, #c084fc 40%, #9333ea 65%, #7e22ce 100%)"
    >
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,#ffffff_0%,transparent_60%)]"
        ></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,#c084fc_0%,transparent_50%)]"
        ></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,#9333ea_0%,transparent_40%)] opacity-40"
        ></div>

        <div
            class="absolute -left-32 -top-32 h-[500px] w-[500px] animate-blob rounded-full bg-white/15 blur-3xl"
        ></div>
        <div
            class="animation-delay-2000 absolute -bottom-32 -right-32 h-[500px] w-[500px] animate-blob rounded-full bg-purple-300/20 blur-3xl"
        ></div>
        <div
            class="animation-delay-4000 absolute left-1/2 top-1/2 h-[400px] w-[400px] -translate-x-1/2 -translate-y-1/2 animate-blob rounded-full bg-sky-200/20 blur-3xl"
        ></div>

        <div class="absolute inset-0 overflow-hidden">
            <div
                v-for="b in bubbles"
                :key="b.id"
                class="bubble absolute rounded-full bg-white/25"
                :style="{
                    width: b.size + 'px',
                    height: b.size + 'px',
                    left: b.left + '%',
                    bottom: '-10%',
                    opacity: b.opacity,
                    animationDelay: b.delay + 's',
                    animationDuration: b.duration + 's',
                }"
            ></div>
        </div>

        <div class="animate-fade-in-up relative flex w-full max-w-md flex-col gap-6">
            <Link
                :href="home()"
                class="flex items-center justify-center gap-3 self-center py-4 transition-transform duration-300 hover:scale-105"
            >
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 shadow-xl backdrop-blur-md ring-1 ring-white/30"
                >
                    <AppLogoIcon
                        class="h-9 w-9 fill-current text-white"
                    />
                </div>
            </Link>

            <div class="flex flex-col gap-6">
                <Card
                    class="animate-scale-in rounded-3xl border-0 bg-white/15 shadow-2xl backdrop-blur-xl ring-1 ring-white/25"
                >
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle
                            class="text-2xl font-bold text-white drop-shadow-lg"
                        >
                            {{ title }}
                        </CardTitle>
                        <CardDescription class="text-white/80">
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animation-delay-1000 {
    animation-delay: 1s;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-3000 {
    animation-delay: 3s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
.animation-delay-5000 {
    animation-delay: 5s;
}

.bubble {
    animation: rise linear infinite;
}

@keyframes rise {
    0% {
        transform: translateY(0) scale(1) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-110vh) scale(0.8) rotate(720deg);
        opacity: 0;
    }
}
</style>

