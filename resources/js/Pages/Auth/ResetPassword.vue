<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>

        <Head title="Credential Reconstruction | SmartBooking" />

        <div class="space-y-10 animate-slideUp">
            <!-- SYSTEM PROTOCOL HEADER -->
            <div class="space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-rose-50 border border-rose-100 rounded-full shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-rose-600">Protocol:
                        Key_Reset_Authorized</span>
                </div>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-none text-[#09090B]">
                    Credential <br /> <span class="text-indigo-600 font-medium">Reset.</span>
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest leading-relaxed">
                    Deploy new security keys to regain <br /> infrastructure access.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- ACCESS IDENTIFIER (Prefilled) -->
                <div class="space-y-2 group">
                    <InputLabel for="email" value="Target Identifier" />
                    <TextInput id="email" type="email"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-400 font-bold shadow-inner opacity-70 cursor-not-allowed"
                        v-model="form.email" required readonly autocomplete="username" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- NEW SECURITY KEYS -->
                <div class="space-y-6">
                    <div class="space-y-2 group">
                        <InputLabel for="password" value="New Security Key" />
                        <TextInput id="password" type="password"
                            class="w-full bg-slate-50 border-none rounded-2xl p-5 text-[#09090B] font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner"
                            v-model="form.password" required autofocus autocomplete="new-password"
                            placeholder="••••••••" />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="space-y-2 group">
                        <InputLabel for="password_confirmation" value="Verify Security Key" />
                        <TextInput id="password_confirmation" type="password"
                            class="w-full bg-slate-50 border-none rounded-2xl p-5 text-[#09090B] font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner"
                            v-model="form.password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <!-- DEPLOYMENT TRIGGER -->
                <div class="pt-6">
                    <PrimaryButton
                        class="w-full py-6 bg-[#09090B] shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-[1.5rem]"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <span v-if="form.processing">Synchronizing Keys...</span>
                        <span v-else>Reconstruct Access</span>
                    </PrimaryButton>
                </div>
            </form>

            <div class="pt-8 border-t border-slate-100 text-center">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-relaxed">
                    Once pushed, all other active sessions <br /> will be forced to re-authenticate.
                </p>
            </div>
        </div>
    </AuthLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(input),
:deep(label),
:deep(button),
:deep(h2),
:deep(p) {
    font-family: 'Space Grotesk', sans-serif !important;
}

.shadow-inner {
    box-shadow: inset 0 2px 8px 0 rgba(0, 0, 0, 0.05);
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