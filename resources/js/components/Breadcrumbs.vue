<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

type Props = {
    breadcrumbs: BreadcrumbItemType[];
};

defineProps<Props>();
</script>

<template>
    <Breadcrumb>
        <BreadcrumbList>
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <BreadcrumbItem>
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage class="font-bold text-gradient-celeste text-glow-celeste">
                            {{ item.title }}
                        </BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child>
                            <Link :href="item.href" class="font-semibold transition-all duration-300 hover:text-sky-600 hover:underline">
                                {{ item.title }}
                            </Link>
                        </BreadcrumbLink>
                    </template>
                </BreadcrumbItem>
                <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1">
                    <ChevronRight class="h-3.5 w-3.5 text-sky-500/60" />
                </BreadcrumbSeparator>
            </template>
        </BreadcrumbList>
    </Breadcrumb>
</template>

