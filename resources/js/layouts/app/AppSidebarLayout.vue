<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const bubbles = Array.from({ length: 8 }, (_, i) => ({
    id: i,
    size: 15 + Math.random() * 50,
    left: Math.random() * 100,
    delay: Math.random() * 8,
    duration: 10 + Math.random() * 14,
    opacity: 0.04 + Math.random() * 0.06,
}));
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="relative overflow-x-hidden">
            <div class="pointer-events-none fixed inset-0 overflow-hidden bg-aurora">
                <div class="animate-aurora-1 -left-40 -top-40 h-[600px] w-[600px] bg-[#7DD3FC]/8"></div>
                <div class="animate-aurora-2 -bottom-40 -right-40 h-[600px] w-[600px] bg-[#C084FC]/6"></div>
                <div class="animate-aurora-3 left-1/3 top-1/3 h-[400px] w-[400px] bg-[#38BDF8]/5"></div>
                <div class="animate-aurora-4 right-1/3 bottom-1/3 h-[300px] w-[300px] bg-[#9333EA]/4"></div>

                <div class="animate-float-particle absolute h-1 w-1 rounded-full bg-[#7DD3FC]/40" style="left: 20%; animation-delay: 0s;"></div>
                <div class="animate-float-particle absolute h-1.5 w-1.5 rounded-full bg-[#C084FC]/30" style="left: 40%; animation-delay: 0.8s;"></div>
                <div class="animate-float-particle absolute h-1 w-1 rounded-full bg-[#38BDF8]/40" style="left: 60%; animation-delay: 1.6s;"></div>
                <div class="animate-float-particle absolute h-1 w-1 rounded-full bg-[#7DD3FC]/30" style="left: 80%; animation-delay: 2.4s;"></div>
                <div class="animate-float-particle absolute h-1 w-1 rounded-full bg-[#C084FC]/35" style="left: 30%; animation-delay: 3.2s;"></div>
                <div class="animate-float-particle absolute h-1.5 w-1.5 rounded-full bg-[#38BDF8]/25" style="left: 70%; animation-delay: 4s;"></div>

                <div class="absolute left-1/2 top-0 h-px w-1/2 bg-gradient-to-r from-transparent via-[#7DD3FC]/20 to-transparent"></div>

                <div
                    v-for="b in bubbles"
                    :key="b.id"
                    class="bubble absolute rounded-full bg-white/20"
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
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <div class="relative z-10 px-6 py-6">
                <slot />
            </div>
        </AppContent>
        <Toaster />
    </AppShell>
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

