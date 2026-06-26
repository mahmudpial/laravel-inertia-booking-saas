<template>
    <button
        class="inline-flex items-center justify-center px-6 py-3.5 bg-rose-600 border border-transparent rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-white shadow-xl shadow-rose-900/20 transition-all duration-300 hover:bg-rose-700 hover:shadow-rose-900/40 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
        <slot />
    </button>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@900&display=swap');

button {
    font-family: 'Space Grotesk', sans-serif !important;
}
</style>