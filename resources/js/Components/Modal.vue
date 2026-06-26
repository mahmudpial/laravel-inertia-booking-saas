<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            showSlot.value = true;
            dialog.value?.showModal();
        } else {
            document.body.style.overflow = '';
            setTimeout(() => {
                dialog.value?.close();
                showSlot.value = false;
            }, 300); // Slightly longer for the premium transition
        }
    },
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        e.preventDefault();
        if (props.show) {
            close();
        }
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <dialog class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent no-scrollbar"
        ref="dialog">
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-12 sm:px-0" scroll-region>
            <!-- TACTILE BACKDROP -->
            <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                    <div class="absolute inset-0 bg-[#0A0B10]/60 backdrop-blur-md" />
                </div>
            </Transition>

            <!-- PREMIUM MODAL WINDOW -->
            <Transition enter-active-class="transition duration-500 ease-elastic"
                enter-from-class="opacity-0 scale-90 translate-y-8 sm:translate-y-0"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95">
                <div v-show="show"
                    class="relative mb-6 transform overflow-hidden rounded-[3rem] bg-white border border-slate-200/60 shadow-[0_30px_70px_-10px_rgba(16,19,26,0.25)] transition-all sm:mx-auto sm:w-full"
                    :class="maxWidthClass">
                    <!-- SLOT WRAPPER -->
                    <div v-if="showSlot" class="relative">
                        <slot />
                    </div>
                </div>
            </Transition>
        </div>
    </dialog>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

/* CUSTOM SPRING TRANSITION */
.ease-elastic {
    transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* GLOBAL FONT SYNC */
:deep(*) {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* HIDE DIALOG SCROLLBAR */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Ensure modal is clickable and interactive */
dialog::backdrop {
    display: none;
}
</style>