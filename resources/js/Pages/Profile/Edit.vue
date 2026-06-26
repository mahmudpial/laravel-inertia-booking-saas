<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>

    <Head title="Account Settings" />

    <AuthenticatedLayout>
        <!-- ULTRA-MODERN RADIAL BACKGROUND -->
        <div
            class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px] pb-24">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

                <!-- PREMIUM HEADER SECTION -->
                <div class="mb-16 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-4">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-slate-400">Account
                            Portal</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-black text-[#11131A] tracking-tighter leading-[0.9]">
                        User <span class="text-slate-400">Profile.</span>
                    </h1>
                    <p class="text-slate-500 font-medium mt-4 text-lg max-w-xl">
                        Manage your digital identity, security credentials, and account preferences.
                    </p>
                </div>

                <div class="space-y-10">

                    <!-- SECTION 1: PERSONAL IDENTITY -->
                    <div
                        class="group bg-white border border-slate-200/60 rounded-[3rem] shadow-2xl shadow-slate-200/40 overflow-hidden hover:border-indigo-200 transition-all duration-500">
                        <div class="flex flex-col lg:flex-row">
                            <div
                                class="lg:w-1/3 p-10 bg-slate-50/50 border-b lg:border-b-0 lg:border-r border-slate-100">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-[#11131A] uppercase tracking-tight">Identity</h3>
                                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Update your account's public
                                    profile information and registered email address.</p>
                            </div>
                            <div class="lg:w-2/3 p-10">
                                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                                    class="max-w-2xl" />
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: SECURITY & PASSWORDS -->
                    <div
                        class="group bg-white border border-slate-200/60 rounded-[3rem] shadow-2xl shadow-slate-200/40 overflow-hidden hover:border-indigo-200 transition-all duration-500">
                        <div class="flex flex-col lg:flex-row">
                            <div
                                class="lg:w-1/3 p-10 bg-slate-50/50 border-b lg:border-b-0 lg:border-r border-slate-100">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-[#11131A] flex items-center justify-center text-white mb-6 shadow-lg shadow-slate-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-[#11131A] uppercase tracking-tight">Security</h3>
                                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Ensure your account is using a
                                    long, random password to stay secure against unauthorized access.</p>
                            </div>
                            <div class="lg:w-2/3 p-10">
                                <UpdatePasswordForm class="max-w-2xl" />
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: DANGER ZONE -->
                    <div
                        class="group bg-white border border-rose-100 rounded-[3rem] shadow-2xl shadow-rose-100/30 overflow-hidden hover:border-rose-300 transition-all duration-500">
                        <div class="flex flex-col lg:flex-row">
                            <div class="lg:w-1/3 p-10 bg-rose-50/30 border-b lg:border-b-0 lg:border-r border-rose-50">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-rose-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-rose-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-rose-900 uppercase tracking-tight">Danger Zone</h3>
                                <p class="text-sm text-rose-600/70 mt-2 leading-relaxed">Once your account is deleted,
                                    all of its resources and data will be permanently purged.</p>
                            </div>
                            <div class="lg:w-2/3 p-10">
                                <DeleteUserForm class="max-w-2xl" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap');

:deep(body) {
    font-family: 'Space Grotesk', sans-serif;
}

/* Slide-up animation for cards */
.space-y-10>div {
    animation: slideUp 0.6s ease-out forwards;
    opacity: 0;
}

.space-y-10>div:nth-child(1) {
    animation-delay: 0.1s;
}

.space-y-10>div:nth-child(2) {
    animation-delay: 0.2s;
}

.space-y-10>div:nth-child(3) {
    animation-delay: 0.3s;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>