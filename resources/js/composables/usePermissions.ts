import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { Auth } from '@/types/auth';

export function usePermissions() {
    const page = usePage();
    const auth = computed<Auth>(() => (page.props.auth ?? {}) as Auth);

    const can = (permission: string): boolean => {
        if (auth.value.isAdmin || auth.value.roles?.includes('admin')) {
            return true;
        }

        return (auth.value.permissions ?? []).includes(permission);
    };

    const hasRole = (...roles: string[]): boolean =>
        roles.some((r) => (auth.value.roles ?? []).includes(r));

    const roles = computed(() => auth.value.roles ?? []);
    const permissions = computed(() => auth.value.permissions ?? []);
    const isAdmin = computed(() => auth.value.isAdmin ?? false);

    return { can, hasRole, roles, permissions, isAdmin };
}
