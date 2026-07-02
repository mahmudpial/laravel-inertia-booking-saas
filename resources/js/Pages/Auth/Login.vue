<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ canResetPassword: Boolean, status: String });

const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') });
</script>

<template>
    <AuthLayout>

        <Head title="Login" />
        <div class="space-y-10 animate-slideUp">
            <div class="space-y-4">
                <p class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.4em]">Protocol: Security_Check
                </p>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-none text-[#09090B]">Identity <br />
                    <span class="text-indigo-600 font-medium">Verify.</span>
                </h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Enter authorized credentials.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                    <InputLabel value="Access Identifier" />
                    <TextInput v-model="form.email" type="email" placeholder="NAME@DOMAIN.COM" required />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <InputLabel value="Security Key" />
                        <Link :href="route('password.request')"
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600">
                            Recover Key?</Link>
                    </div>
                    <TextInput v-model="form.password" type="password" placeholder="••••••••" required />
                    <InputError :message="form.errors.password" />
                </div>
                <div class="flex items-center">
                    <Checkbox v-model:checked="form.remember" />
                    <span class="ml-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Persist
                        Session</span>
                </div>
                <PrimaryButton class="w-full py-6" :disabled="form.processing">Initialize Hub</PrimaryButton>
            </form>

            <div class="pt-8 border-t border-slate-100 text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">New Node Instance?</p>
                <Link :href="route('register')"
                    class="mt-4 inline-block text-xs font-black text-indigo-600 uppercase tracking-[0.2em] hover:tracking-[0.3em] transition-all">
                    Generate New Identity</Link>
            </div>
        </div>
    </AuthLayout>
</template>