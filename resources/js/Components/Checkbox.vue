<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },
    set(val) {
        emit('update:checked', val);
    },
});

// Logic to determine if this specific value is in the checked array or if boolean is true
const isChecked = computed(() => {
    if (Array.isArray(props.checked)) {
        return props.checked.includes(props.value);
    }
    return props.checked;
});
</script>

<template>
    <label class="relative inline-flex items-center cursor-pointer group">
        <!-- Hidden Native Input for Accessibility/Logic -->
        <input type="checkbox" :value="value" v-model="proxyChecked" class="sr-only peer" />

        <!-- CUSTOM PREMIUM UI BOX -->
        <div class="w-6 h-6 flex items-center justify-center rounded-lg border-2 transition-all duration-300 overflow-hidden"
            :class="[
                isChecked
                    ? 'bg-[#11131A] border-[#11131A] shadow-lg shadow-indigo-900/20 scale-105'
                    : 'bg-slate-50 border-slate-200 group-hover:border-indigo-400 group-hover:bg-white'
            ]">
            <!-- CUSTOM SVG CHECKMARK -->
            <transition name="check">
                <svg v-if="isChecked" class="w-3.5 h-3.5 text-white" viewBox="0 0 12 10" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 5L4.5 8.5L11 1.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </transition>
        </div>

        <!-- Optional: Pulse effect on focus (visible via keyboard nav) -->
        <div
            class="absolute inset-0 rounded-lg peer-focus:ring-2 peer-focus:ring-indigo-400 peer-focus:ring-offset-2 pointer-events-none transition-all">
        </div>
    </label>
</template>

<style scoped>
/* Drawing animation for the checkmark */
.check-enter-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.check-enter-from {
    opacity: 0;
    transform: scale(0.5) rotate(-15deg);
}

/* Subtle bounce effect when clicking */
label:active div {
    transform: scale(0.9);
}
</style>