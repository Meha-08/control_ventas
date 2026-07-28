<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowRight, BedDouble, Search, ShoppingBag, Star, X } from 'lucide-vue-next';
import { ref } from 'vue';

type Product = {
    id: number;
    nombre_producto: string;
    tipo_producto: string;
    descripcion: string | null;
    precio_venta: string;
    stock: number;
    imagen_url: string | null;
    created_at: string | null;
};

type Props = {
    productos: Product[];
    filters: { search?: string; tipo?: string };
};

const props = defineProps<Props>();

const search = ref(props.filters.search ?? '');

const tipos = ['Colchones', 'Somier', 'Espaldar', 'Almohadas', 'Cama completa'];

const buscar = () => {
    router.get('/menu', {
        search: search.value || undefined,
        tipo: props.filters.tipo,
    }, { preserveState: true, replace: true });
};

const limpiar = () => {
    search.value = '';
    router.get('/menu', { tipo: props.filters.tipo }, { preserveState: true, replace: true });
};

const filtrar = (tipo: string) => {
    router.get('/menu', {
        search: search.value || undefined,
        tipo: tipo || undefined,
    }, { preserveState: true, replace: true });
};

const formatPrice = (price: string | number) => {
    const num = typeof price === 'string' ? Number.parseFloat(price) : price;
    if (Number.isNaN(num)) return 'Bs 0.00';
    return 'Bs ' + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
};

const getImagenUrl = (p: Product): string | null => {
    return p.imagen_url || null;
};

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
    <Head title="Menú - ROZE" />

    <div class="relative min-h-screen overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #7dd3fc 18%, #c084fc 40%, #9333ea 65%, #7e22ce 100%)">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -left-60 -top-60 h-[800px] w-[800px] animate-blob rounded-full bg-white/15 blur-3xl"></div>
            <div class="animation-delay-2000 absolute -right-60 -bottom-60 h-[700px] w-[700px] animate-blob rounded-full bg-purple-300/20 blur-3xl"></div>
            <div class="animation-delay-4000 absolute left-1/3 top-1/3 h-[600px] w-[600px] animate-blob rounded-full bg-sky-200/20 blur-3xl"></div>
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

        <div class="relative z-10">
            <nav class="fixed top-0 z-50 w-full bg-white/10 backdrop-blur-xl border-b border-white/20">
                <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6">
                    <Link href="/" class="group flex items-center gap-3 transition-all duration-300 hover:scale-105">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 shadow-lg backdrop-blur-md ring-1 ring-white/30">
                            <BedDouble class="h-6 w-6 text-white" />
                        </div>
                        <span class="text-2xl font-black tracking-tight text-white drop-shadow-md">ROZE</span>
                    </Link>
                    <div class="flex items-center gap-3">
                        <Link href="/login" class="rounded-xl px-5 py-2.5 text-sm font-bold text-white drop-shadow-md transition-all duration-300 hover:bg-white/20">
                            Iniciar Sesión
                        </Link>
                        <Link href="/" class="group relative overflow-hidden rounded-xl bg-white/20 px-5 py-2.5 text-sm font-bold text-white shadow-lg backdrop-blur-md ring-1 ring-white/30 transition-all duration-300 hover:bg-white/30 hover:shadow-xl">
                            <span class="relative z-10">Volver</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <div class="mx-auto max-w-7xl px-6 pt-32 pb-16">
                <div class="mb-14 text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-bold text-white shadow-lg backdrop-blur-md ring-1 ring-white/30 drop-shadow-md">
                        <Star class="h-3.5 w-3.5 text-white" />
                        Catálogo ROZE
                    </div>
                    <h1 class="text-4xl font-black text-white drop-shadow-lg md:text-5xl">
                        Nuestro
                        <span class="text-white/90">Menú</span>
                    </h1>
                    <p class="mx-auto mt-3 max-w-xl text-lg font-medium text-white drop-shadow-md">
                        Productos diseñados para tu mejor descanso
                    </p>
                </div>

                <div class="mb-10 flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-white/60" />
                        <input
                            v-model="search"
                            placeholder="Buscar productos..."
                            class="h-12 w-full rounded-2xl border border-white/30 bg-white/20 pl-10 pr-10 text-sm font-medium text-white placeholder-white/60 shadow-lg backdrop-blur-md transition-all duration-300 focus:border-white/50 focus:outline-none focus:ring-2 focus:ring-white/30"
                            @keyup.enter="buscar"
                        />
                        <button
                            v-if="search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-white/60 hover:text-white transition-colors"
                            @click="limpiar"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <button
                        class="h-12 rounded-2xl bg-white/20 px-6 text-sm font-bold text-white shadow-lg backdrop-blur-md ring-1 ring-white/30 transition-all duration-300 hover:bg-white/30 hover:shadow-xl drop-shadow-md"
                        @click="buscar"
                    >
                        Buscar
                    </button>
                </div>

                <div class="mb-10 flex flex-wrap gap-2">
                    <button
                        :class="!filters.tipo ? 'bg-white/30 text-white ring-white/50 shadow-lg' : 'bg-white/10 text-white/80 ring-white/20 hover:bg-white/20 hover:text-white'"
                        class="rounded-2xl px-5 py-2.5 text-sm font-bold transition-all duration-300 ring-1 backdrop-blur-md drop-shadow-md"
                        @click="filtrar('')"
                    >
                        Todos
                    </button>
                    <button
                        v-for="tipo in tipos"
                        :key="tipo"
                        :class="filters.tipo === tipo ? 'bg-white/30 text-white ring-white/50 shadow-lg' : 'bg-white/10 text-white/80 ring-white/20 hover:bg-white/20 hover:text-white'"
                        class="rounded-2xl px-5 py-2.5 text-sm font-bold transition-all duration-300 ring-1 backdrop-blur-md drop-shadow-md"
                        @click="filtrar(tipo)"
                    >
                        {{ tipo }}
                    </button>
                </div>

                <div v-if="productos.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 ring-1 ring-white/30">
                        <ShoppingBag class="h-8 w-8 text-white" />
                    </div>
                    <h3 class="text-lg font-bold text-white drop-shadow-md">No hay productos</h3>
                    <p class="mt-1 text-sm font-medium text-white/80 max-w-sm drop-shadow-sm">
                        No se encontraron productos con los filtros seleccionados.
                    </p>
                    <button
                        class="mt-4 rounded-2xl bg-white/20 px-6 py-3 text-sm font-bold text-white shadow-lg backdrop-blur-md ring-1 ring-white/30 transition-all duration-300 hover:bg-white/30 hover:shadow-xl drop-shadow-md"
                        @click="limpiar"
                    >
                        Limpiar filtros
                    </button>
                </div>

                <div v-else class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div
                        v-for="producto in productos"
                        :key="producto.id"
                        class="group overflow-hidden rounded-2xl bg-white/15 shadow-lg shadow-black/10 backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:bg-white/25 hover:shadow-xl hover:shadow-black/15 ring-1 ring-white/20 hover:ring-white/40"
                    >
                        <div class="relative overflow-hidden aspect-square">
                            <img
                                v-if="getImagenUrl(producto)"
                                :src="getImagenUrl(producto)!"
                                :alt="producto.nombre_producto"
                                class="h-full w-full object-cover transition-all duration-700 group-hover:scale-110"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center bg-white/20">
                                <ShoppingBag class="h-16 w-16 text-white/40" />
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-purple-500/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            <div class="absolute left-3 top-3">
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/25 px-3 py-1.5 text-xs font-bold text-white shadow-lg backdrop-blur-md ring-1 ring-white/30 drop-shadow-sm">
                                    {{ producto.tipo_producto }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="text-sm font-bold text-white drop-shadow-sm">{{ producto.nombre_producto }}</h3>
                            <p v-if="producto.descripcion" class="mt-1 text-xs font-medium text-white/70 line-clamp-2 drop-shadow-sm">{{ producto.descripcion }}</p>
                            <div class="mt-2.5">
                                <span class="text-base font-black text-white drop-shadow-md">{{ formatPrice(producto.precio_venta) }}</span>
                            </div>
                            <div class="mt-2.5 flex items-center gap-2 text-xs font-medium" :class="producto.stock > 0 ? 'text-green-300' : 'text-red-300'">
                                <span class="h-1.5 w-1.5 rounded-full" :class="producto.stock > 0 ? 'bg-green-300' : 'bg-red-300'" />
                                {{ producto.stock > 0 ? `${producto.stock} en stock` : 'Agotado' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animation-delay-1000 { animation-delay: 1s; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }

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

