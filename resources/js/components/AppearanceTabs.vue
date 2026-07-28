<script setup lang="ts">
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Claro' },
    { value: 'dark', Icon: Moon, label: 'Oscuro' },
    { value: 'system', Icon: Monitor, label: 'Sistema' },
] as const;
</script>

<template>
    <div
        class="inline-flex gap-1 rounded-lg bg-purple-100 p-1 dark:bg-purple-800/50"
    >
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                appearance === value
                    ? 'bg-purple-600/20 shadow-xs text-purple-800 dark:text-purple-100 dark:bg-purple-700'
                    : 'text-purple-600 hover:bg-purple-200/60 hover:text-purple-800 dark:text-purple-300 dark:hover:bg-purple-700/60',
            ]"
        >
            <component :is="Icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>

