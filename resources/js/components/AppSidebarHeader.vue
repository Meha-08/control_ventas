<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="relative flex h-16 shrink-0 items-center gap-2 border-b border-[#9333EA]/10 px-6 backdrop-blur-md transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
        :style="{ backgroundColor: 'var(--sidebar-header-bg)' }"
    >
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-[#9333EA]/5 via-transparent to-[#7DD3FC]/5"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[#9333EA]/15 to-transparent"></div>
        <div class="relative flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
    </header>
</template>

