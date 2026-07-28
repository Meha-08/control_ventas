<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Shield } from 'lucide-vue-next';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import type { User } from '@/types';

type Props = {
    user: User;
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const page = usePage();
const auth = page.props.auth;

const roleLabels: Record<string, string> = {
    admin: 'Administrador',
    vendedor: 'Vendedor',
    cliente: 'Cliente',
};

const userRole = computed(() => {
    const roles = auth?.roles as string[] | undefined;

    if (!roles || roles.length === 0) {
return '';
}

    return roleLabels[roles[0]] ?? roles[0];
});

const showAvatar = computed(
    () => props.user.avatar && props.user.avatar !== '',
);

const getInitials = (fullName?: string) => {
    if (!fullName) {
return '';
}

    const names = fullName.trim().split(' ');

    if (names.length === 0) {
return '';
}

    if (names.length === 1) {
return names[0].charAt(0).toUpperCase();
}

    return `${names[0].charAt(0)}${names[names.length - 1].charAt(0)}`.toUpperCase();
};
</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
        <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
        <AvatarFallback class="rounded-lg text-black dark:text-white">
            {{ getInitials(user.name) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">{{ user.name }}</span>
        <span v-if="userRole" class="truncate text-[11px] font-semibold text-sky-600 dark:text-sky-400 flex items-center gap-1">
            <Shield class="h-3 w-3" />
            {{ userRole }}
        </span>
        <span
            v-if="showEmail && !userRole"
            class="truncate text-xs text-muted-foreground"
            >{{ user.email }}</span
        >
        <span
            v-else-if="showEmail"
            class="truncate text-[10px] text-muted-foreground"
            >{{ user.email }}</span
        >
    </div>
</template>

