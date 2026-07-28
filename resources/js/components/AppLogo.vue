<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="relative flex aspect-square size-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#9333EA] to-[#7E22CE] text-white shadow-lg shadow-[#9333EA]/30 ring-1 ring-white/30 transition-all duration-500"
        style="transform: perspective(400px) rotateX(2deg) rotateY(-2deg);"
    >
        <div class="absolute -inset-1 animate-sidebar-glow rounded-xl bg-[#9333EA]/20 blur-md"></div>
        <AppLogoIcon class="relative size-5 fill-current text-white drop-shadow-sm" />
    </div>
    <div class="ml-2 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-extrabold text-sidebar-foreground drop-shadow-sm tracking-tight"
            >Control Ventas Roze</span
        >
        <span class="text-[10px] font-semibold text-gradient-premium tracking-wide">Premium 2026</span>
    </div>
</template>

