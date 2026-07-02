<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <AuthLayout>

        <Head title="System Re-Authorization | SmartBooking" />

        <div class="space-y-10 animate-slideUp">
            <!-- SYSTEM LOCK HEADER -->
            <div class="space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-100 rounded-full shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-amber-600">Protocol:
                        Secure_Zone_Handshake</span>
                </div>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-none text-[#09090B]">
                    System <br /> <span class="text-indigo-600 font-medium">Validation.</span>
                </h2>
                <p class="text-slate-500 text-sm font-medium leading-relaxed italic">
                    You are attempting to access a high-security administrative node. Please re-authorize your identity
                    to continue.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <div class="space-y-2 group">
                    <InputLabel for="password" value="Active Security Key" />
                    <TextInput id="password" type="password"
                        class="w-full bg-slate-50 border-none rounded-2xl p-5 text-[#09090B] font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner"
                        v-model="form.password" required autocomplete="current-password" autofocus
                        placeholder="••••••••" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="pt-4">
                    <PrimaryButton
                        class="w-full py-6 bg-[#09090B] shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-[1.5rem]"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Authorizing...' : 'Authorize Access' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>

<style scoped>
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