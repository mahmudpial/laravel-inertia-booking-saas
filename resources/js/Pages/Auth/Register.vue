<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation')
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Create Account — SmartBooking" />

        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                    Start your 14-day trial
                </h1>
                <p class="text-sm text-slate-500 mt-1.5">
                    No credit card required. Get your business booking portal live in 2 minutes.
                </p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Sarah Connor"
                            required
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                        />
                    </div>
                    <InputError class="mt-1.5" :message="form.errors.name" />
                </div>

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
                            placeholder="sarah@studio.com"
                            required
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                        />
                    </div>
                    <InputError class="mt-1.5" :message="form.errors.email" />
                </div>

                <!-- Password Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="At least 8 chars"
                            required
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                        <input
                            v-model="form.password_confirmation"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Re-type password"
                            required
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                        />
                    </div>
                </div>
                <InputError class="mt-1" :message="form.errors.password" />

                <!-- Password hint -->
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Must be at least 8 characters</span>
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="text-xs text-slate-500 hover:text-slate-700 font-medium"
                    >
                        {{ showPassword ? 'Hide password' : 'Show password' }}
                    </button>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-3 px-4 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-brand-600 shadow-md shadow-slate-900/10 hover:shadow-brand-600/25 transition-all duration-200 active:scale-[0.99] disabled:opacity-50 flex items-center justify-center gap-2 mt-4"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Creating account...' : 'Create free account' }}</span>
                </button>
            </form>

            <!-- Terms notice -->
            <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                By registering, you agree to our Terms of Service and Privacy Policy.
            </p>

            <!-- Footer Link -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Already have an account?
                    <Link :href="route('login')" class="font-semibold text-brand-600 hover:text-brand-700 ml-1">
                        Sign in instead
                    </Link>
                </p>
            </div>
        </div>
    </AuthLayout>
</template>