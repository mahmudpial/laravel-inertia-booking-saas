<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="relative group w-full">
        <!-- TACTILE INPUT FIELD -->
        <input
            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-900 font-bold placeholder:text-slate-300 placeholder:font-medium placeholder:uppercase placeholder:text-[10px] placeholder:tracking-[0.2em] focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all duration-300 shadow-inner group-hover:bg-slate-100/80"
            v-model="model" ref="input" />

        <!-- MICRO-ACCENT BORDER (Visible only on focus) -->
        <div
            class="absolute inset-0 rounded-2xl pointer-events-none border border-slate-200/50 group-focus-within:border-indigo-500/20 transition-colors duration-300">
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap');

input {
    font-family: 'Space Grotesk', sans-serif !important;
    outline: none;
}

/* Custom shadow for that high-end 'carved' look */
.shadow-inner {
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03);
}

/* Target Webkit date pickers if this is used as a date input type */
input::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
    cursor: pointer;
}
</style>