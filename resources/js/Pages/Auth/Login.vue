<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ canResetPassword: Boolean, status: String });

const form = useForm({ email: '', password: '', remember: false });
const showPassword = ref(false);

const fillCredentials = (role) => {
    if (role === 'owner') {
        form.email = 'owner@example.com';
        form.password = 'password';
    } else if (role === 'customer') {
        form.email = 'customer@example.com';
        form.password = 'password';
    } else if (role === 'admin') {
        form.email = 'admin@example.com';
        form.password = 'password';
    }
};

const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') });
</script>

<template>
    <AuthLayout>
        <Head title="Sign In — SmartBooking" />

        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                    Welcome back
                </h1>
                <p class="text-sm text-slate-500 mt-1.5">
                    Sign in to your account to manage your bookings and schedule.
                </p>
            </div>

            <!-- Demo Credentials Helper Pill -->
            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">⚡ 1-Click Demo Fill</span>
                    <span class="text-[10px] text-slate-400">Pass: password</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button
                        type="button"
                        @click="fillCredentials('owner')"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:border-brand-500 hover:text-brand-600 hover:shadow-sm transition-all text-center"
                    >
                        🏢 Owner
                    </button>
                    <button
                        type="button"
                        @click="fillCredentials('customer')"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:border-brand-500 hover:text-brand-600 hover:shadow-sm transition-all text-center"
                    >
                        👤 Customer
                    </button>
                    <button
                        type="button"
                        @click="fillCredentials('admin')"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:border-brand-500 hover:text-brand-600 hover:shadow-sm transition-all text-center"
                    >
                        ⚡ Admin
                    </button>
                </div>
            </div>

            <!-- Status Banner -->
            <div v-if="status" class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl font-medium">
                {{ status }}
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Work Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </div>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="you@company.com"
                            required
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                        />
                    </div>
                    <InputError class="mt-1.5" :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-700">Password</label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-xs font-semibold text-brand-600 hover:text-brand-700 transition-colors"
                        >
                            Forgot password?
                        </Link>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                        >
                            <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                        </button>
                    </div>
                    <InputError class="mt-1.5" :message="form.errors.password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <Checkbox v-model:checked="form.remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
                        <span class="text-xs font-medium text-slate-600">Remember this device</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-3 px-4 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-brand-600 shadow-md shadow-slate-900/10 hover:shadow-brand-600/25 transition-all duration-200 active:scale-[0.99] disabled:opacity-50 flex items-center justify-center gap-2 mt-2"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Signing in...' : 'Sign in to Workspace' }}</span>
                </button>
            </form>

            <!-- Footer Link -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Don't have an account?
                    <Link :href="route('register')" class="font-semibold text-brand-600 hover:text-brand-700 ml-1">
                        Create free account
                    </Link>
                </p>
            </div>
        </div>
    </AuthLayout>
</template>