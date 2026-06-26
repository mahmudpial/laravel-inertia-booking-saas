<template>
    <button
        class="inline-flex items-center justify-center px-8 py-4 bg-[#11131A] border border-transparent rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] text-white transition-all duration-300 shadow-xl shadow-slate-900/10 hover:bg-indigo-600 hover:shadow-indigo-900/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
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

/* Ensures smooth color transition for the background-color and transform */
.transition-all {
    transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
}
</style>