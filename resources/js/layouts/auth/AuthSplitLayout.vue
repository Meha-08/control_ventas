<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();

const bubbles = Array.from({ length: 12 }, (_, i) => ({
    id: i,
    size: 20 + Math.random() * 80,
    left: Math.random() * 100,
    delay: Math.random() * 8,
    duration: 8 + Math.random() * 12,
    opacity: 0.06 + Math.random() * 0.08,
}));
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <div
            class="relative hidden h-full flex-col overflow-hidden p-10 text-white lg:flex"
            style="background: linear-gradient(135deg, #ffffff 0%, #7dd3fc 18%, #c084fc 40%, #9333ea 65%, #7e22ce 100%)"
        >
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-white/20 via-transparent to-purple-500/20"></div>
            <div class="pointer-events-none absolute -right-20 -top-20 h-96 w-96 rounded-full bg-white/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-20 h-96 w-96 rounded-full bg-purple-300/20 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
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
            <Link
                :href="home()"
                class="relative z-20 flex items-center text-lg font-medium"
            >
                <AppLogoIcon class="mr-2 size-8 fill-current text-white" />
                {{ name }}
            </Link>
        </div>
        <div class="lg:p-8">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]"
            >
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
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

