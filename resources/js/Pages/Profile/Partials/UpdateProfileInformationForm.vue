<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="max-w-2xl">
        <!-- Form Header (Optional - Can be removed if parent already has it) -->
        <header class="mb-8">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 mb-2">Public Record</p>
            <h2 class="text-2xl font-black text-[#11131A] tracking-tight">Identity Details</h2>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-8">
            <!-- Full Name Field -->
            <div class="relative group">
                <label for="name"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">
                    Display Name
                </label>
                <TextInput id="name" type="text"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-900 font-semibold focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm group-hover:bg-slate-100/50"
                    v-model="form.name" required autofocus autocomplete="name" placeholder="Enter your full name" />
                <InputError class="mt-2 text-xs font-bold text-rose-500" :message="form.errors.name" />
            </div>

            <!-- Email Address Field -->
            <div class="relative group">
                <label for="email"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">
                    Email Address
                </label>
                <TextInput id="email" type="email"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-900 font-semibold focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm group-hover:bg-slate-100/50"
                    v-model="form.email" required autocomplete="username" placeholder="name@company.com" />
                <InputError class="mt-2 text-xs font-bold text-rose-500" :message="form.errors.email" />
            </div>

            <!-- Email Verification Notice -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null"
                class="mt-4 p-5 bg-amber-50/50 border border-amber-100 rounded-3xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-amber-700">
                        Your email remains unverified.
                    </p>
                </div>
                <div class="mt-4">
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="text-xs font-black uppercase tracking-widest text-amber-600 hover:text-amber-800 underline decoration-2 underline-offset-4">
                        Resend verification link
                    </Link>
                </div>

                <div v-show="status === 'verification-link-sent'"
                    class="mt-3 text-xs font-bold text-emerald-600 uppercase tracking-wide">
                    ● A fresh link has been dispatched to your inbox.
                </div>
            </div>

            <!-- Action Area -->
            <div class="flex items-center gap-6 pt-4">
                <button type="submit" :disabled="form.processing"
                    class="px-10 py-4 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 active:scale-95 disabled:opacity-50">
                    {{ form.processing ? 'Syncing...' : 'Save Changes' }}
                </button>

                <Transition enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 translate-x-4" leave-active-class="transition duration-300 ease-in"
                    leave-to-class="opacity-0">
                    <div v-if="form.recentlySuccessful"
                        class="flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            Verified & Saved
                        </p>
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
/* Ensuring consistency with parent styling */
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap');

:deep(input) {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* Custom shadow for inputs */
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
}
</style>