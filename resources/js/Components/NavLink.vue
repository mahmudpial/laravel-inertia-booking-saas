<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

// Refined for Dark Navbar: No background colors, just text and dash
const classes = computed(() =>
    props.active
        ? 'relative inline-flex items-center px-4 h-full text-[10px] font-black uppercase tracking-[0.2em] text-white transition-all duration-300 group'
        : 'relative inline-flex items-center px-4 h-full text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-white transition-all duration-300 group',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <!-- LINK TEXT -->
        <span class="relative z-10">
            <slot />
        </span>

        <!-- GLOWING DASH INDICATOR -->
        <transition name="slide">
            <div v-if="active"
                class="absolute bottom-0 left-4 right-4 h-1 bg-indigo-500 rounded-full shadow-[0_0_12px_rgba(99,102,241,0.8)]">
            </div>
        </transition>
    </Link>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@900&display=swap');

a {
    font-family: 'Space Grotesk', sans-serif !important;
    text-decoration: none !important;
}

/* Slide animation for the active dash */
.slide-enter-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-enter-from {
    opacity: 0;
    transform: scaleX(0);
}

.slide-leave-active {
    transition: all 0.2s ease-in;
}

.slide-leave-to {
    opacity: 0;
}
</style>