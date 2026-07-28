<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuBadge,
    SidebarMenuSub,
    SidebarMenuSubItem,
    SidebarMenuSubButton,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavGroup, NavItem } from '@/types';

defineProps<{
    items: NavItem[] | NavGroup[];
}>();

const { isCurrentUrl } = useCurrentUrl();

const isGrouped = (items: NavItem[] | NavGroup[]) => {
    return items.length > 0 && 'items' in items[0];
};

const collapsedGroups = ref<Set<string>>(new Set());

function toggleGroup(title: string) {
    if (collapsedGroups.value.has(title)) {
        collapsedGroups.value.delete(title);
    } else {
        collapsedGroups.value.add(title);
    }
}

function isCollapsed(title: string): boolean {
    return collapsedGroups.value.has(title);
}

const gradientMap: Record<string, string> = {
    'from-sky-400 to-sky-500': 'linear-gradient(135deg, #7DD3FC, #38BDF8)',
    'from-sky-400 to-purple-500': 'linear-gradient(135deg, #7DD3FC, #9333EA)',
    'from-purple-400 to-violet-500': 'linear-gradient(135deg, #C084FC, #A78BFA)',
    'from-blue-400 to-indigo-500': 'linear-gradient(135deg, #60A5FA, #818CF8)',
    'from-purple-600 to-purple-700': 'linear-gradient(135deg, #9333EA, #7E22CE)',
};

const iconColorMap: Record<string, string> = {
    'text-sky-400': '#7DD3FC',
    'text-purple-400': '#C084FC',
    'text-blue-400': '#60A5FA',
};

const gradientTextMap: Record<string, string> = {
    'text-sky-400': 'text-gradient-celeste',
    'text-purple-400': 'text-gradient-lilac',
    'text-blue-400': 'text-gradient-celeste',
};

const hoverBgMap: Record<string, string> = {
    celeste: 'rgba(125, 211, 252, 0.12)',
    lilac: 'rgba(192, 132, 252, 0.12)',
};

function getHoverBg(sectionColor?: string): string {
    return sectionColor ? hoverBgMap[sectionColor] || hoverBgMap.celeste : hoverBgMap.celeste;
}

const badgeColorMap: Record<string, string> = {
    sky: 'bg-[#9333EA]/15 text-purple-600 dark:text-purple-400',
    celeste: 'bg-[#7DD3FC]/15 text-sky-600 dark:text-sky-400',
    lilac: 'bg-[#C084FC]/15 text-purple-600 dark:text-purple-400',
};

function getGradientStyle(gradient?: string): string {
    return gradient ? gradientMap[gradient] || '' : '';
}

function getIconColorStyle(iconColor?: string): string {
    return iconColor ? iconColorMap[iconColor] || '' : '';
}

function getGradientTextClass(iconColor?: string): string {
    return iconColor ? gradientTextMap[iconColor] || 'text-gradient-celeste' : 'text-gradient-celeste';
}

function getBadgeClass(color?: string): string {
    return color ? badgeColorMap[color] || badgeColorMap.celeste : badgeColorMap.celeste;
}
</script>

<template>
    <SidebarGroup v-if="isGrouped(items)" class="px-2 py-0">
        <template v-for="group in items" :key="group.title">
            <div
                class="group/label relative flex cursor-pointer items-center justify-between rounded-lg px-3 py-1.5 transition-all duration-200 hover:bg-white/10"
                @click="toggleGroup(group.title)"
            >
                <SidebarGroupLabel
                    class="flex items-center gap-2 font-extrabold tracking-wide uppercase text-[11px] text-gray-700 dark:text-gray-200"
                >
                    <div
                        class="h-2.5 w-2.5 rounded-full ring-2 ring-white/20 shadow-sm"
                        :style="{ backgroundColor: getIconColorStyle(group.items[0]?.iconColor) || '#7DD3FC' }"
                    ></div>
                    <span>{{ group.title }}</span>
                </SidebarGroupLabel>
                <div
                    class="flex h-5 w-5 items-center justify-center rounded-md transition-transform duration-300"
                    :class="[getGradientTextClass(group.items[0]?.iconColor), { 'rotate-180': !isCollapsed(group.title) }]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-3 w-3"
                    >
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </div>
            </div>
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 max-h-0"
                enter-to-class="opacity-100 max-h-[500px]"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 max-h-[500px]"
                leave-to-class="opacity-0 max-h-0"
            >
                <SidebarMenu
                    v-show="!isCollapsed(group.title)"
                    class="mb-3 overflow-hidden"
                >
                    <SidebarMenuItem
                        v-for="item in group.items"
                        :key="item.title"
                        class="relative"
                    >
                        <SidebarMenuButton
                            as-child
                            :is-active="isCurrentUrl(item.href)"
                            :tooltip="item.title"
                            :style="{
                                ...(isCurrentUrl(item.href) && group.gradient ? { borderLeftColor: getGradientStyle(group.gradient) } : {}),
                                ...(isCurrentUrl(item.href) && group.sectionColor ? { backgroundColor: getHoverBg(group.sectionColor) } : {}),
                            }"
                            class="relative border-l-2 border-l-transparent font-bold transition-all duration-300"
                            :class="[
                                isCurrentUrl(item.href) ? 'shadow-sm' : 'hover:scale-[1.02]',
                                isCurrentUrl(item.href) ? getGradientTextClass(item.iconColor) : 'text-gray-600 dark:text-gray-300',
                            ]"
                        >
                            <div
                                v-if="isCurrentUrl(item.href)"
                                class="absolute inset-y-1 left-0 w-0.5 rounded-full transition-all duration-500"
                                :style="{ backgroundColor: getGradientStyle(group.gradient) || '#7DD3FC', boxShadow: '0 0 8px ' + (getIconColorStyle(item.items?.[0]?.iconColor) || '#7DD3FC') + '80' }"
                            ></div>
                            <Link :href="item.href" class="flex w-full items-center gap-3">
                                <component
                                    :is="item.icon"
                                    :style="{ color: getIconColorStyle(item.iconColor) || undefined }"
                                    :class="[
                                        'size-4 shrink-0 transition-all duration-300',
                                        isCurrentUrl(item.href) ? 'scale-110 drop-shadow-sm' : '',
                                    ]"
                                />
                                <span class="flex-1 truncate font-bold" :class="isCurrentUrl(item.href) ? 'drop-shadow-sm' : 'text-gray-700 dark:text-gray-200'">{{ item.title }}</span>
                                <SidebarMenuBadge
                                    v-if="item.badge && !item.children"
                                    :class="getBadgeClass(item.badgeColor)"
                                    class="px-1.5 py-0.5 text-[10px] font-bold leading-none"
                                >
                                    {{ item.badge }}
                                </SidebarMenuBadge>
                            </Link>
                        </SidebarMenuButton>
                        <SidebarMenuSub v-if="item.children">
                            <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                                <SidebarMenuSubButton
                                    as-child
                                    :is-active="isCurrentUrl(child.href)"
                                >
                                    <Link :href="child.href" class="flex w-full items-center gap-3">
                                        <component
                                            :is="child.icon"
                                            :style="{ color: getIconColorStyle(child.iconColor) || undefined }"
                                            class="size-3.5 shrink-0"
                                        />
                                        <span class="flex-1 truncate text-xs font-semibold" :class="getGradientTextClass(child.iconColor)">{{ child.title }}</span>
                                        <SidebarMenuBadge
                                            v-if="child.badge"
                                            :class="getBadgeClass(child.badgeColor)"
                                            class="px-1.5 py-0.5 text-[10px] font-bold leading-none"
                                        >
                                            {{ child.badge }}
                                        </SidebarMenuBadge>
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </SidebarMenuItem>
                </SidebarMenu>
            </Transition>
        </template>
    </SidebarGroup>
    <SidebarGroup v-else class="px-2 py-0">
        <SidebarGroupLabel class="font-bold tracking-wide uppercase text-[11px] text-gray-700 dark:text-gray-200">Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                    class="font-bold transition-all duration-300 hover:scale-[1.02] text-gray-700 dark:text-gray-200"
                >
                    <Link :href="item.href">
                        <component :is="item.icon" class="size-4 shrink-0" />
                        <span class="font-bold">{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>

