<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps({ status: String });
const form = useForm({ email: '' });
const submit = () => form.post(route('password.email'));
</script>

<template>
    <AuthLayout>

        <Head title="Recover Key" />
        <div class="space-y-10 animate-slideUp">
            <div class="space-y-4">
                <p class="text-rose-600 font-black text-[10px] uppercase tracking-[0.4em]">Protocol: Key_Recovery</p>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-none text-[#09090B]">Security <br />
                    <span class="text-slate-400 font-medium text-5xl">Override.</span>
                </h2>
            </div>

            <p v-if="!status" class="text-slate-500 text-sm font-medium leading-relaxed italic">Enter your deployment
                identity. We will transmit a recovery key to your registered node.</p>

            <div v-if="status"
                class="p-6 bg-emerald-50 border border-emerald-100 rounded-3xl text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                {{ status }}</div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                    <InputLabel value="Registered Identity" />
                    <TextInput v-model="form.email" type="email" placeholder="NAME@DOMAIN.COM" required />
                    <InputError :message="form.errors.email" />
                </div>
                <PrimaryButton class="w-full py-6 bg-indigo-600" :disabled="form.processing">Transmit Key
                </PrimaryButton>
            </form>

            <Link :href="route('login')"
                class="block w-full text-center text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-[#09090B]">
                Cancel Protocol</Link>
        </div>
    </AuthLayout>
</template>