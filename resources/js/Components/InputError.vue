<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-1">
        <div v-if="message" class="mt-2 flex items-center gap-1.5 px-1">
            <!-- TACTILE ERROR ICON -->
            <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <!-- EDITORIAL ERROR TEXT -->
            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-rose-500 leading-none">
                {{ message }}
            </p>
        </div>
    </transition>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@900&display=swap');

p {
    font-family: 'Space Grotesk', sans-serif !important;
}
</style>