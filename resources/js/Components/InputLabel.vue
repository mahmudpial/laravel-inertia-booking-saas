<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block mb-2 ml-1 cursor-default">
        <!-- EDITORIAL LABEL STYLE -->
        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 leading-none">
            <span v-if="value">{{ value }}</span>
            <span v-else>
                <slot />
            </span>
        </span>
    </label>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@900&display=swap');

label span {
    font-family: 'Space Grotesk', sans-serif !important;
}
</style>