<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="max-w-2xl">
        <header class="mb-8">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 mb-2">Security Ledger</p>
            <h2 class="text-2xl font-black text-[#11131A] tracking-tight">Access Credentials</h2>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-8">
            <!-- Current Password -->
            <div class="relative group">
                <label for="current_password"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">
                    Current Authentication Key
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <TextInput id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                        type="password"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-11 text-slate-900 font-semibold focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm group-hover:bg-slate-100/50"
                        autocomplete="current-password" placeholder="••••••••" />
                </div>
                <InputError :message="form.errors.current_password" class="mt-2 text-xs font-bold text-rose-500" />
            </div>

            <!-- New Password -->
            <div class="relative group">
                <label for="password"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">
                    New Security Key
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-11 text-slate-900 font-semibold focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm group-hover:bg-slate-100/50"
                        autocomplete="new-password" placeholder="Min. 8 characters" />
                </div>
                <InputError :message="form.errors.password" class="mt-2 text-xs font-bold text-rose-500" />
            </div>

            <!-- Confirm Password -->
            <div class="relative group">
                <label for="password_confirmation"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">
                    Confirm New Key
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-11 text-slate-900 font-semibold focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm group-hover:bg-slate-100/50"
                        autocomplete="new-password" placeholder="Repeat new password" />
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2 text-xs font-bold text-rose-500" />
            </div>

            <!-- Action Area -->
            <div class="flex items-center gap-6 pt-4">
                <button type="submit" :disabled="form.processing"
                    class="px-10 py-4 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 active:scale-95 disabled:opacity-50">
                    {{ form.processing ? 'Encrypting...' : 'Update Password' }}
                </button>

                <Transition enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 translate-x-4" leave-active-class="transition duration-300 ease-in"
                    leave-to-class="opacity-0">
                    <div v-if="form.recentlySuccessful"
                        class="flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            Securely Updated
                        </p>
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap');

:deep(input) {
    font-family: 'Space Grotesk', sans-serif !important;
}

.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
}
</style>