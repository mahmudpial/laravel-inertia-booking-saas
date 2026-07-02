<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });
const submit = () => form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') });
</script>

<template>
    <AuthLayout>

        <Head title="Register" />
        <div class="space-y-10 animate-slideUp">
            <div class="space-y-4">
                <p class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.4em]">Protocol: Create_Node</p>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-none text-[#09090B]">Identity <br />
                    <span class="text-indigo-600 font-medium text-5xl">Generation.</span>
                </h2>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="space-y-2">
                    <InputLabel value="Legal Label" />
                    <TextInput v-model="form.name" type="text" placeholder="FULL NAME" required />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="space-y-2">
                    <InputLabel value="Deployment ID" />
                    <TextInput v-model="form.email" type="email" placeholder="EMAIL@DOMAIN.COM" required />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <InputLabel value="Security Key" />
                        <TextInput v-model="form.password" type="password" placeholder="••••" required />
                    </div>
                    <div class="space-y-2">
                        <InputLabel value="Verify" />
                        <TextInput v-model="form.password_confirmation" type="password" placeholder="••••" required />
                    </div>
                </div>
                <InputError :message="form.errors.password" />
                <PrimaryButton class="w-full py-6 mt-4" :disabled="form.processing">Initialize Deployment
                </PrimaryButton>
            </form>

            <div class="pt-8 border-t border-slate-100 text-center">
                <Link :href="route('login')"
                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-[#09090B]">Return
                    to Auth Portal</Link>
            </div>
        </div>
    </AuthLayout>
</template>