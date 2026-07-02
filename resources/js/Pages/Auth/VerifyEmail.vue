<script setup>
import { computed } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <AuthLayout>

        <Head title="Identity Synchronization | SmartBooking" />

        <div class="space-y-10 animate-slideUp">
            <!-- VERIFICATION HEADER -->
            <div class="space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Protocol:
                        Node_Confirmation</span>
                </div>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-none text-[#09090B]">
                    Packet <br /> <span class="text-indigo-600 font-medium text-5xl">Verification.</span>
                </h2>
            </div>

            <!-- STATUS MESSAGE -->
            <article
                class="text-slate-500 text-sm font-medium leading-relaxed italic border-l-2 border-slate-200 pl-6 space-y-4">
                <p>Registration sequence initiated. To finalize your deployment to the SmartBooking grid, we have
                    dispatched a verification packet to your identifier.</p>
                <p>If the packet was not received, utilize the transmitter below to resynchronize.</p>
            </article>

            <!-- SUCCESS NOTIFICATION -->
            <Transition name="fade">
                <div v-if="verificationLinkSent"
                    class="p-6 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-start gap-4">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest leading-normal">
                        A fresh verification packet has been successfully dispatched to your node.
                    </p>
                </div>
            </Transition>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="flex flex-col gap-4">
                    <PrimaryButton class="w-full py-6 bg-[#09090B] shadow-2xl rounded-[1.5rem]"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Syncing...' : 'Resend Verification Packet' }}
                    </PrimaryButton>

                    <Link :href="route('logout')" method="post" as="button"
                        class="w-full py-4 text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 hover:text-rose-600 transition-all hover:bg-rose-50 rounded-2xl">
                        Terminate Initialization
                    </Link>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideUp {
    animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>