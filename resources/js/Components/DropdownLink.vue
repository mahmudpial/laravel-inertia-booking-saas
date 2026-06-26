<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
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
</script>

<template>
    <div class="px-2">
        <Link :href="href" :method="method" :as="as"
            class="group block w-full px-4 py-3 text-start text-[10px] font-black uppercase tracking-[0.2em] text-[#11131A] rounded-2xl transition-all duration-300 hover:bg-indigo-50/50 hover:text-indigo-600 hover:pl-5 focus:outline-none focus:bg-indigo-50/50 active:scale-[0.98]">
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