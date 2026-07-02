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

const classes = computed(() =>
    props.active
        ? 'relative inline-flex items-center px-6 h-full text-[10px] font-black uppercase tracking-[0.25em] text-white transition-all duration-500 group'
        : 'relative inline-flex items-center px-6 h-full text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 hover:text-white transition-all duration-500 group',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <!-- TACTICAL LINK TEXT -->
        <span class="relative z-10">
            <slot />
        </span>

        <!-- ENGINEERED ACTIVE INDICATOR (CENTERED DASH) -->
        <transition name="slide-up">
            <div v-if="active"
                class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4 h-1 bg-indigo-500 rounded-full shadow-[0_0_12px_rgba(99,102,241,0.8)]">
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

/* Premium Slide Animation */
.slide-up-enter-active {
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-up-enter-from {
    opacity: 0;
    transform: translate(-50%, 4px) scaleX(0);
}

.slide-up-leave-active {
    transition: all 0.2s ease-in;
}

.slide-up-leave-to {
    opacity: 0;
    transform: translate(-50%, 2px);
}
</style>