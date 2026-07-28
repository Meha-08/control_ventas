<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowRight,
    Award,
    Bed,
    BedDouble,
    BedSingle,
    ChevronDown,
    Heart,
    MapPin,
    MessageCircle,
    Package,
    Percent,
    Phone,
    Shield,
    ShoppingBag,
    Sparkles,
    Star,
    Truck,
    UserPlus,
    Zap,
} from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';
import { login, register } from '@/routes';

const props = defineProps<{
    canRegister: boolean;
    productos: {
        id: number;
        nombre_producto: string;
        tipo_producto: string;
        descripcion: string | null;
        precio_venta: string;
        stock: number;
        imagen_url: string | null;
    }[];
    categoriaImagenes: Record<string, string>;
    heroImagen: string;
}>();

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 40;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    observeSections();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const observeSections = () => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        },
        { threshold: 0.1 },
    );
    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
};

const menuItems = [
    { label: 'Inicio', href: '#hero' },
    { label: 'Colchones', href: '#categorias' },
    { label: 'Somieres', href: '#categorias' },
    { label: 'Espaldares', href: '#categorias' },
    { label: 'Almohadas', href: '#categorias' },
    { label: 'Cama completa', href: '#categorias' },
    { label: 'Ofertas', href: '#productos' },
    { label: 'Contacto', href: '#contacto' },
];

const categorias = [
    {
        nombre: 'Colchones',
        descripcion: 'Viscoelásticos, ortopédicos y de resortes',
        icono: BedDouble,
        gradient: 'from-sky-500/20 via-purple-500/10 to-transparent',
        hoverGradient: 'from-sky-500/30 via-purple-500/20 to-transparent',
        color: 'text-sky-400',
        bgIcon: 'bg-sky-500/20',
    },
    {
        nombre: 'Somier',
        descripcion: 'Americanos, abatibles y metálicos',
        icono: Bed,
        gradient: 'from-purple-500/20 via-purple-500/10 to-transparent',
        hoverGradient: 'from-purple-500/30 via-purple-500/20 to-transparent',
        color: 'text-purple-400',
        bgIcon: 'bg-purple-500/20',
    },
    {
        nombre: 'Espaldar',
        descripcion: 'Tapizados, modernos y clásicos',
        icono: BedSingle,
        gradient: 'from-sky-500/20 via-purple-500/10 to-transparent',
        hoverGradient: 'from-sky-500/30 via-purple-500/20 to-transparent',
        color: 'text-sky-400',
        bgIcon: 'bg-sky-500/20',
    },
    {
        nombre: 'Almohadas',
        descripcion: 'Viscoelásticas, pluma y ortopédicas',
        icono: BedSingle,
        gradient: 'from-purple-500/20 via-sky-500/10 to-transparent',
        hoverGradient: 'from-purple-500/30 via-sky-500/20 to-transparent',
        color: 'text-purple-400',
        bgIcon: 'bg-purple-500/20',
    },
    {
        nombre: 'Cama completa',
        descripcion: 'Juegos completos para tu descanso',
        icono: BedDouble,
        gradient: 'from-purple-500/20 via-sky-500/10 to-transparent',
        hoverGradient: 'from-purple-500/30 via-sky-500/20 to-transparent',
        color: 'text-purple-400',
        bgIcon: 'bg-purple-500/20',
    },
];

const productosDestacados = [
    {
        nombre: 'Colchón Viscoelástico Premium',
        precio: 1899,
        precioOriginal: 2499,
        descuento: '-24%',
        imagen: 'https://images.unsplash.com/photo-1630493565608-e34c7f8b2a48?w=600&q=80',
        rating: 5,
        categoria: 'Colchones',
    },
    {
        nombre: 'Somier Americano Luxe',
        precio: 999,
        precioOriginal: 1399,
        descuento: '-29%',
        imagen: 'https://images.unsplash.com/photo-1505692952047-1a78307da8e2?w=600&q=80',
        rating: 5,
        categoria: 'Somieres',
    },
    {
        nombre: 'Espaldar Tapizado Milano',
        precio: 599,
        precioOriginal: 799,
        descuento: '-25%',
        imagen: 'https://images.unsplash.com/photo-1616486029423-aaa4789e8c9a?w=600&q=80',
        rating: 4,
        categoria: 'Espaldares',
    },
    {
        nombre: 'Almohada Viscoelástica Memory',
        precio: 249,
        precioOriginal: 349,
        descuento: '-29%',
        imagen: 'https://images.unsplash.com/photo-1512918553160-c6e76e7b91b3?w=600&q=80',
        rating: 5,
        categoria: 'Almohadas',
    },
    {
        nombre: 'Colchón Ortopédico Duro',
        precio: 2199,
        precioOriginal: 2899,
        descuento: '-24%',
        imagen: 'https://images.unsplash.com/photo-1616627547584-bf28cee262db?w=600&q=80',
        rating: 5,
        categoria: 'Colchones',
    },
    {
        nombre: 'Somier Abatible con Almacén',
        precio: 1499,
        precioOriginal: 1999,
        descuento: '-25%',
        imagen: 'https://images.unsplash.com/photo-1445991842772-097fea258e7b?w=600&q=80',
        rating: 4,
        categoria: 'Somieres',
    },
];

const beneficios = [
    {
        titulo: 'Envíos a toda Bolivia',
        descripcion: 'Llegamos a cualquier ciudad del país con entrega programada y montaje incluido.',
        icono: Truck,
        gradient: 'from-sky-500 to-purple-500',
        shadow: 'shadow-sky-500/25',
        bgGlow: 'bg-sky-500/10',
    },
    {
        titulo: 'Garantía de calidad',
        descripcion: 'Todos nuestros productos tienen garantía de hasta 10 años. Respaldo total.',
        icono: Shield,
        gradient: 'from-purple-500 to-violet-500',
        shadow: 'shadow-purple-500/25',
        bgGlow: 'bg-purple-500/10',
    },
    {
        titulo: 'Pago seguro',
        descripcion: 'Transferencias, tarjetas y contra entrega. Tus datos protegidos al 100%.',
        icono: Award,
        gradient: 'from-sky-500 to-cyan-500',
        shadow: 'shadow-sky-500/25',
        bgGlow: 'bg-sky-500/10',
    },
    {
        titulo: 'Atención personalizada',
        descripcion: 'Asesores expertos te guiarán a elegir el producto perfecto para tu descanso.',
        icono: MessageCircle,
        gradient: 'from-sky-500 to-purple-500',
        shadow: 'shadow-sky-500/25',
        bgGlow: 'bg-sky-500/10',
    },
];

const testimonios = [
    {
        nombre: 'María Fernanda',
        rol: 'Santa Cruz',
        texto: 'Compré un colchón viscoelástico y la diferencia es increíble. El servicio de entrega fue puntual y muy profesional.',
        estrellas: 5,
    },
    {
        nombre: 'Carlos Méndez',
        rol: 'La Paz',
        texto: 'El somier americano que compré superó mis expectativas. Sólido, bien terminado y a un precio justo. Muy recomendados.',
        estrellas: 5,
    },
    {
        nombre: 'Ana Paula Ríos',
        rol: 'Cochabamba',
        texto: 'Las almohadas viscoelásticas son una maravilla. Mi calidad de sueño mejoró desde la primera noche. Volveré a comprar.',
        estrellas: 5,
    },
];

const formatPrice = (price: string | number) => {
    const num = typeof price === 'string' ? Number.parseFloat(price) : price;
    if (Number.isNaN(num)) return 'Bs 0';
    return 'Bs ' + num.toLocaleString('es-BO');
};

const scrollToSection = (href: string) => {
    const el = document.querySelector(href);

    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
};

const goToCategoria = (tipo: string) => {
    router.get('/menu', { tipo }, { preserveState: true, replace: true });
};

const bubbles = Array.from({ length: 12 }, (_, i) => ({
    id: i,
    size: 20 + Math.random() * 80,
    left: Math.random() * 100,
    delay: Math.random() * 8,
    duration: 8 + Math.random() * 12,
    opacity: 0.06 + Math.random() * 0.08,
}));

const mobileMenuOpen = ref(false);
</script>

<template>
    <Head title="ROZE - Empresa Roze" />

    <div class="relative min-h-screen overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #7dd3fc 18%, #c084fc 40%, #9333ea 65%, #7e22ce 100%)">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -left-40 -top-40 h-[700px] w-[700px] animate-blob rounded-full bg-white/15 blur-3xl"></div>
            <div class="animation-delay-2000 absolute -right-40 -bottom-40 h-[600px] w-[600px] animate-blob rounded-full bg-purple-300/20 blur-3xl"></div>
            <div class="animation-delay-4000 absolute left-1/3 top-1/2 h-[500px] w-[500px] animate-blob rounded-full bg-sky-200/20 blur-3xl"></div>
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

        <nav
            :class="[
                'fixed top-0 z-50 w-full transition-all duration-500',
                isScrolled
                    ? 'bg-sky-800/80 backdrop-blur-xl shadow-lg shadow-sky-500/10'
                    : 'bg-transparent',
            ]"
        >
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6">
                <a href="#hero" class="group flex items-center gap-3 transition-all duration-300 hover:scale-105">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 via-purple-500 to-purple-600 shadow-lg shadow-sky-500/30 transition-all duration-500 group-hover:shadow-sky-500/50">
                        <BedDouble class="h-5 w-5 text-white" />
                    </div>
                    <span class="text-2xl font-black tracking-tight text-white">
                        ROZE
                    </span>
                </a>

                <!-- Mobile hamburger -->
                <button
                    class="flex items-center justify-center rounded-xl p-2 text-white/70 transition-all duration-300 hover:bg-white/10 hover:text-white md:hidden"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    aria-label="Toggle menu"
                >
                    <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Desktop nav -->
                <div class="hidden items-center gap-1 md:flex">
                    <a
                        v-for="item in menuItems"
                        :key="item.label"
                        :href="item.href"
                        @click.prevent="scrollToSection(item.href)"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-white/70 transition-all duration-300 hover:bg-white/10 hover:text-white hover:scale-105"
                    >
                        {{ item.label }}
                    </a>
                </div>

                <div class="hidden items-center gap-3 md:flex">
                    <Link
                        href="/menu"
                        class="group relative overflow-hidden rounded-xl bg-gradient-to-r from-purple-500 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/40 hover:scale-105"
                    >
                        <span class="absolute inset-0 bg-gradient-to-r from-purple-400 to-violet-500 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                        <span class="relative z-10">Catálogo de Menú</span>
                    </Link>
                    <Link
                        :href="login()"
                        class="group relative overflow-hidden rounded-xl bg-gradient-to-r from-sky-500 via-purple-500 to-purple-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-sky-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-sky-500/40 hover:scale-105"
                    >
                        <span class="absolute inset-0 bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                        <span class="relative z-10">Iniciar Sesión</span>
                    </Link>
                </div>
            </div>

            <!-- Mobile menu dropdown -->
            <div
                v-if="mobileMenuOpen"
                class="border-t border-white/20 md:hidden"
                :class="isScrolled ? 'bg-sky-800/95 backdrop-blur-xl' : 'bg-sky-800/60 backdrop-blur-md'"
            >
                <div class="mx-auto max-w-7xl px-6 py-4">
                    <div class="flex flex-col gap-1">
                        <a
                            v-for="item in menuItems"
                            :key="item.label"
                            :href="item.href"
                            @click.prevent="scrollToSection(item.href); mobileMenuOpen = false"
                            class="rounded-xl px-4 py-3 text-sm font-semibold text-white/80 transition-all duration-300 hover:bg-white/10 hover:text-white"
                        >
                            {{ item.label }}
                        </a>
                    </div>
                    <div class="mt-4 flex flex-col gap-3 border-t border-white/20 pt-4">
                        <Link
                            href="/menu"
                            class="flex items-center justify-center rounded-xl bg-gradient-to-r from-purple-500 to-violet-600 px-5 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300"
                            @click="mobileMenuOpen = false"
                        >
                            Catálogo de Menú
                        </Link>
                        <Link
                            :href="login()"
                            class="flex items-center justify-center rounded-xl bg-gradient-to-r from-sky-500 via-purple-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300"
                            @click="mobileMenuOpen = false"
                        >
                            Iniciar Sesión
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <section id="hero" class="relative flex min-h-screen items-center overflow-hidden pt-20">
            <div class="absolute inset-0">
                <img
                    :src="heroImagen"
                    alt="Habitación moderna"
                    class="h-full w-full object-cover opacity-85"
                />
                <div class="absolute inset-0 bg-gradient-to-r from-purple-900/60 via-purple-900/30 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-purple-900/50 via-transparent to-purple-900/20"></div>
            </div>

            <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-20 md:py-32">
                <div class="max-w-3xl">
                    <div class="reveal mb-6 inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2 text-sm font-semibold text-white/90 shadow-lg backdrop-blur-md ring-1 ring-white/20">
                        <Sparkles class="h-4 w-4 text-sky-300" />
                        Nueva colección 2026
                    </div>

                    <h1 class="reveal mb-6 text-5xl font-black leading-[1.1] tracking-tight text-white md:text-7xl lg:text-8xl">
                        Descanso que
                        <span class="bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 bg-clip-text text-transparent">
                            transforma
                        </span>
                        tu vida
                    </h1>

                    <p class="reveal mb-10 max-w-xl text-lg leading-relaxed text-white/60 md:text-xl">
                        Colchones, somieres, espaldares y almohadas diseñados para brindarte el mejor descanso. Calidad premium con entregas en toda Bolivia.
                    </p>

                    <div class="reveal flex flex-col gap-4 sm:flex-row">
                        <Link
                            href="/menu"
                            class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-r from-sky-500 via-purple-500 to-purple-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-sky-500/30 transition-all duration-500 hover:shadow-2xl hover:shadow-sky-500/50 hover:scale-105"
                        >
                            <span class="absolute inset-0 bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>
                            <ShoppingBag class="relative z-10 h-5 w-5" />
                            <span class="relative z-10">Explorar Catálogo</span>
                            <ArrowRight class="relative z-10 h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                        </Link>
                        <Link
                            href="/menu"
                            class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl border border-white/20 bg-white/10 px-8 py-4 text-base font-bold text-white shadow-lg backdrop-blur-md transition-all duration-500 hover:bg-white/20 hover:shadow-xl hover:scale-105"
                        >
                            <Percent class="h-5 w-5 text-sky-300" />
                            <span>Ver Menú</span>
                        </Link>
                    </div>

                    <div class="reveal mt-16 flex items-center gap-8">
                        <div class="flex -space-x-3">
                            <div v-for="i in 4" :key="i" class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-sky-800 bg-gradient-to-br from-sky-500 to-purple-600 text-sm font-bold text-white shadow-lg">
                                {{ ['M', 'C', 'A', 'P'][i - 1] }}
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1">
                                <Star v-for="i in 5" :key="i" class="h-4 w-4 fill-sky-300 text-sky-300" />
                            </div>
                            <p class="mt-1 text-sm font-medium text-white/50">Más de <span class="text-white font-bold">2,000</span> clientes satisfechos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <ChevronDown class="h-6 w-6 text-white/40" />
            </div>
        </section>

        <section id="categorias" class="relative py-24 md:py-32">
            <div class="mx-auto max-w-7xl px-6">
                <div class="reveal mb-16 text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-white/70 shadow-lg backdrop-blur-md ring-1 ring-white/10">
                        <Package class="h-4 w-4 text-purple-400" />
                        Nuestras Categorías
                    </div>
                    <h2 class="text-4xl font-black text-white md:text-5xl">
                        Todo para tu
                        <span class="bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 bg-clip-text text-transparent">descanso</span>
                    </h2>
                    <p class="mx-auto mt-4 max-w-lg text-lg text-white/50">
                        Encontrarás la colección más completa de productos para dormitorio en Bolivia
                    </p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
                    <div
                        v-for="(cat, index) in categorias"
                        :key="cat.nombre"
                        class="reveal group relative cursor-pointer overflow-hidden rounded-3xl transition-all duration-700 hover:-translate-y-2"
                        :style="{ transitionDelay: `${index * 0.1}s` }"
                        @click="goToCategoria(cat.nombre)"
                    >
                        <div class="absolute inset-0 overflow-hidden rounded-3xl">
                            <img
                                :src="categoriaImagenes[cat.nombre]"
                                :alt="cat.nombre"
                                class="h-full w-full object-cover transition-all duration-700 group-hover:scale-110"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-sky-800 via-sky-800/60 to-transparent"></div>
                        </div>

                        <div class="relative z-10 flex h-[320px] flex-col justify-end p-6 sm:p-8">
                            <div
                                class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl shadow-lg backdrop-blur-md transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl"
                                :class="cat.bgIcon"
                            >
                                <component :is="cat.icono" class="h-7 w-7 text-white" />
                            </div>
                            <h3 class="mb-1.5 text-2xl font-bold text-white">{{ cat.nombre }}</h3>
                            <p class="mb-5 text-sm text-white/60">{{ cat.descripcion }}</p>
                            <div class="flex items-center gap-2 text-sm font-semibold transition-all duration-300"
                                :class="cat.color">
                                <span>Ver productos</span>
                                <ArrowRight class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" />
                            </div>
                        </div>

                        <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 transition-opacity duration-500 group-hover:opacity-100 ring-1 ring-white/20"
                            :class="'bg-gradient-to-br ' + cat.hoverGradient">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="productos" class="relative py-24 md:py-32">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -right-40 top-1/3 h-[500px] w-[500px] animate-blob rounded-full bg-sky-500/10 blur-3xl"></div>
                <div class="animation-delay-2000 absolute -left-40 bottom-1/3 h-[400px] w-[400px] animate-blob rounded-full bg-purple-500/5 blur-3xl"></div>
            </div>

            <div class="mx-auto max-w-7xl px-6">
                <div class="reveal mb-16 text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-white/70 shadow-lg backdrop-blur-md ring-1 ring-white/10">
                        <Star class="h-4 w-4 text-sky-300" />
                        Productos Destacados
                    </div>
                    <h2 class="text-4xl font-black text-white md:text-5xl">
                        Lo más
                        <span class="bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 bg-clip-text text-transparent">vendido</span>
                    </h2>
                    <p class="mx-auto mt-4 max-w-lg text-lg text-white/50">
                        Los favoritos de nuestros clientes con los mejores precios del mercado
                    </p>
                </div>

                <div v-if="productos.length === 0" class="flex flex-col items-center py-16 text-center">
                    <ShoppingBag class="h-12 w-12 text-white/20" />
                    <p class="mt-4 text-white/40">No hay productos disponibles</p>
                </div>
                <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="(producto, index) in productos"
                        :key="producto.id"
                        class="reveal group overflow-hidden rounded-3xl bg-gradient-to-br from-white/5 via-white/[0.03] to-transparent shadow-lg shadow-black/20 backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-sky-500/10 ring-1 ring-white/10 hover:ring-sky-300/30"
                        :style="{ transitionDelay: `${index * 0.08}s` }"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                v-if="producto.imagen_url"
                                :src="producto.imagen_url"
                                :alt="producto.nombre_producto"
                                class="aspect-[4/3] w-full object-cover transition-all duration-700 group-hover:scale-110"
                            />
                            <div v-else class="flex aspect-[4/3] w-full items-center justify-center bg-gradient-to-br from-sky-500/20 via-purple-500/10 to-sky-500/20">
                                <ShoppingBag class="h-12 w-12 text-sky-300/40" />
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-sky-800/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            <div class="absolute left-4 top-4">
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/20 px-3 py-1.5 text-xs font-bold text-white shadow-lg backdrop-blur-md ring-1 ring-white/20">
                                    {{ producto.tipo_producto }}
                                </span>
                            </div>

                            <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white opacity-0 backdrop-blur-md transition-all duration-300 hover:bg-sky-500/50 group-hover:opacity-100">
                                <Heart class="h-4 w-4" />
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white">{{ producto.nombre_producto }}</h3>
                            <p v-if="producto.descripcion" class="mt-2 text-sm text-white/50 line-clamp-2">{{ producto.descripcion }}</p>
                            <div class="mt-3 flex items-center gap-3">
                                <span class="text-2xl font-black text-white">{{ formatPrice(producto.precio_venta) }}</span>
                            </div>
                            <div class="mt-3 flex items-center gap-2 text-sm" :class="producto.stock > 0 ? 'text-green-400' : 'text-red-400'">
                                <span class="h-2 w-2 rounded-full" :class="producto.stock > 0 ? 'bg-green-400' : 'bg-red-400'" />
                                {{ producto.stock > 0 ? `${producto.stock} en stock` : 'Agotado' }}
                            </div>
                            <button
                                class="mt-5 flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500/20 via-purple-500/20 to-sky-500/20 px-5 py-3 text-sm font-bold text-white shadow-lg backdrop-blur-sm ring-1 ring-white/20 transition-all duration-300 hover:from-sky-500/40 hover:via-purple-500/40 hover:to-purple-600/40 hover:shadow-lg hover:shadow-sky-500/20 hover:scale-[1.02]"
                            >
                                <ShoppingBag class="h-4 w-4" />
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative py-24 md:py-32">
            <div class="mx-auto max-w-7xl px-6">
                <div class="reveal mb-16 text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-white/70 shadow-lg backdrop-blur-md ring-1 ring-white/10">
                        <Award class="h-4 w-4 text-sky-400" />
                        ¿Por qué ROZE?
                    </div>
                    <h2 class="text-4xl font-black text-white md:text-5xl">
                        Beneficios
                        <span class="bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 bg-clip-text text-transparent">exclusivos</span>
                    </h2>
                    <p class="mx-auto mt-4 max-w-lg text-lg text-white/50">
                        Todo lo que necesitas para una experiencia de compra inigualable
                    </p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="(beneficio, index) in beneficios"
                        :key="beneficio.titulo"
                        class="reveal group relative overflow-hidden rounded-3xl bg-white/5 p-8 shadow-lg shadow-black/20 backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-xl"
                        :style="{ transitionDelay: `${index * 0.1}s` }"
                    >
                        <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full opacity-0 blur-2xl transition-all duration-700 group-hover:opacity-30 group-hover:scale-150"
                            :class="beneficio.bgGlow">
                        </div>

                        <div class="relative z-10 mb-6 flex h-16 w-16 items-center justify-center rounded-2xl shadow-lg transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl"
                            :class="'bg-gradient-to-br ' + beneficio.gradient">
                            <component :is="beneficio.icono" class="h-7 w-7 text-white" />
                        </div>

                        <h3 class="relative z-10 mb-3 text-xl font-bold text-white">{{ beneficio.titulo }}</h3>
                        <p class="relative z-10 text-sm leading-relaxed text-white/50">{{ beneficio.descripcion }}</p>

                        <div class="relative z-10 mt-6 h-1 w-0 rounded-full bg-gradient-to-r transition-all duration-700 group-hover:w-full"
                            :class="beneficio.gradient">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative py-24 md:py-32">
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                <div class="h-[600px] w-[600px] animate-blob rounded-full bg-gradient-to-r from-sky-500/10 via-purple-500/5 to-purple-700/5 blur-3xl"></div>
            </div>

            <div class="mx-auto max-w-7xl px-6">
                <div class="reveal mb-16 text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-white/70 shadow-lg backdrop-blur-md ring-1 ring-white/10">
                        <MessageCircle class="h-4 w-4 text-sky-300" />
                        Testimonios
                    </div>
                    <h2 class="text-4xl font-black text-white md:text-5xl">
                        Lo que dicen nuestros
                        <span class="bg-gradient-to-r from-sky-300 via-purple-400 to-purple-600 bg-clip-text text-transparent">clientes</span>
                    </h2>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div
                        v-for="(testimonio, index) in testimonios"
                        :key="testimonio.nombre"
                        class="reveal group rounded-3xl bg-white/5 p-8 shadow-lg shadow-black/20 backdrop-blur-sm ring-1 ring-white/10 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-sky-500/5"
                        :style="{ transitionDelay: `${index * 0.15}s` }"
                    >
                        <div class="mb-4 flex items-center gap-1">
                            <Star v-for="i in testimonio.estrellas" :key="i" class="h-4 w-4 fill-sky-300 text-sky-300" />
                        </div>
                        <p class="mb-6 text-sm leading-relaxed text-white/60">"{{ testimonio.texto }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-purple-600 text-sm font-bold text-white shadow-lg">
                                {{ testimonio.nombre.charAt(0) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ testimonio.nombre }}</p>
                                <p class="text-xs text-white/40">{{ testimonio.rol }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative py-24 md:py-32">
            <div class="mx-auto max-w-7xl px-6">
                <div class="reveal relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-600/30 via-purple-600/30 to-purple-800/30 shadow-2xl shadow-sky-500/20 backdrop-blur-xl ring-1 ring-white/20">
                    <div class="pointer-events-none absolute inset-0 overflow-hidden">
                        <div class="absolute -left-20 -top-20 h-60 w-60 animate-blob rounded-full bg-sky-400/30 blur-3xl"></div>
                        <div class="animation-delay-2000 absolute -bottom-20 -right-20 h-60 w-60 animate-blob rounded-full bg-sky-300/20 blur-3xl"></div>
                    </div>

                    <div class="relative z-10 flex flex-col items-center px-8 py-16 text-center md:px-16 md:py-20">
                        <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white shadow-lg backdrop-blur-md">
                            <Sparkles class="h-4 w-4 text-sky-300" />
                            Comienza hoy
                        </div>
                        <h2 class="mb-4 text-4xl font-black text-white md:text-5xl">
                            ¿Listo para transformar tu descanso?
                        </h2>
                        <p class="mb-10 max-w-md text-lg text-white/60">
                            Únete a miles de clientes que ya disfrutan del mejor descanso con ROZE
                        </p>
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <Link
                                :href="register()"
                                class="group inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-purple-500 to-purple-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-sky-500/30 transition-all duration-500 hover:shadow-2xl hover:shadow-sky-500/50 hover:scale-105"
                            >
                                <UserPlus class="h-5 w-5" />
                                Crear cuenta gratis
                            </Link>
                            <Link
                                :href="login()"
                                class="group inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-8 py-4 text-base font-bold text-white shadow-lg backdrop-blur-md transition-all duration-500 hover:bg-white/20 hover:shadow-xl hover:scale-105"
                            >
                                <Zap class="h-5 w-5 text-sky-300" />
                                Iniciar sesión
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer id="contacto" class="relative border-t border-white/10 py-16">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid gap-10 md:grid-cols-4">
                    <div class="md:col-span-1">
                        <div class="mb-5 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 via-purple-500 to-purple-600 shadow-lg">
                                <BedDouble class="h-5 w-5 text-white" />
                            </div>
                            <span class="text-2xl font-black text-white">ROZE</span>
                        </div>
                        <p class="text-sm leading-relaxed text-white/40">
                            Tu tienda de confianza para el mejor descanso. Colchones, somieres, espaldares y almohadas con calidad premium.
                        </p>
                        <div class="mt-6 flex items-center gap-3">
                            <div v-for="social in ['facebook', 'instagram', 'twitter', 'whatsapp']" :key="social" class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-2xl bg-white/10 text-white/60 transition-all duration-300 hover:bg-gradient-to-br hover:from-sky-500 hover:via-purple-500 hover:to-purple-600 hover:text-white hover:scale-110 hover:shadow-lg hover:shadow-sky-500/30">
                                <svg v-if="social === 'facebook'" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                                <svg v-if="social === 'instagram'" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm3.5-12c-.828 0-1.5-.672-1.5-1.5S14.672 5 15.5 5 17 5.672 17 6.5 16.328 8 15.5 8zM12 7c2.757 0 5 2.243 5 5s-2.243 5-5 5-5-2.243-5-5 2.243-5 5-5zm0 8c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3z"/></svg>
                                <svg v-if="social === 'twitter'" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                <svg v-if="social === 'whatsapp'" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-widest text-white/60">Productos</h3>
                        <ul class="space-y-3">
                            <li v-for="item in ['Colchones', 'Somier', 'Espaldar', 'Almohadas', 'Cama completa']" :key="item">
                                <Link :href="'/menu?tipo=' + item" class="text-sm text-white/40 transition-all duration-300 hover:text-sky-300 hover:translate-x-1 inline-block">
                                    {{ item }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-widest text-white/60">Atención al Cliente</h3>
                        <ul class="space-y-3">
                            <li v-for="item in ['Preguntas frecuentes', 'Política de devolución', 'Términos y condiciones', 'Privacidad', 'Garantías']" :key="item">
                                <a href="#" class="text-sm text-white/40 transition-all duration-300 hover:text-sky-300 hover:translate-x-1 inline-block">
                                    {{ item }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-widest text-white/60">Contacto</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <MapPin class="mt-0.5 h-4 w-4 shrink-0 text-sky-300" />
                                <span class="text-sm text-white/40">Av. Principal #123, Santa Cruz, Bolivia</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <Phone class="h-4 w-4 shrink-0 text-purple-400" />
                                <span class="text-sm text-white/40">+591 700 000 000</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <MessageCircle class="h-4 w-4 shrink-0 text-sky-400" />
                                <span class="text-sm text-white/40">WhatsApp: +591 700 000 001</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 border-t border-white/10 pt-8 text-center">
                    <p class="text-sm text-white/30">&copy; {{ new Date().getFullYear() }} ROZE. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.animation-delay-1000 {
    animation-delay: 1s;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-3000 {
    animation-delay: 3s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}

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

.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

