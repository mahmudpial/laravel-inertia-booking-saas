<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type"
        class="inline-flex items-center justify-center px-8 py-4 bg-white border border-slate-200/80 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 transition-all duration-300 shadow-sm hover:bg-slate-50 hover:text-[#11131A] hover:border-slate-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
        <!-- SLOT FOR BUTTON CONTENT -->
        <span class="relative z-10">
            <slot />
        </span>
    </button>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@900&display=swap');

button {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* Custom spring-like transition timing */
.transition-all {
    transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
}
</style>