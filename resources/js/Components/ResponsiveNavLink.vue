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
    method: {
        type: String,
        default: 'get',
    },
    as: {
        type: String,
        default: 'a',
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full px-6 py-4 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-indigo-900/20 transition-all duration-300'
        : 'block w-full px-6 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-50 hover:text-indigo-600 transition-all duration-300 active:scale-95'
);
</script>

<template>
    <div class="px-2 py-1">
        <Link :href="href" :method="method" :as="as" :class="classes">
            <slot />
        </Link>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@900&display=swap');

a,
button {
    font-family: 'Space Grotesk', sans-serif !important;
}
</style>