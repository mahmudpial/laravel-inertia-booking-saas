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
    <Head title="Account Settings — SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-8 max-w-5xl mx-auto pb-16">
            <!-- Header -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        Preferences & Security
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                    Account Settings
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Manage your personal profile details, password security, and account preferences.
                </p>
            </div>

            <!-- Settings Sections -->
            <div class="space-y-6">
                <!-- Profile Information Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
                    <div class="flex items-start gap-4 mb-6 pb-6 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-display font-bold text-slate-900">Profile Information</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Update your account's profile name and email address.</p>
                        </div>
                    </div>
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                </div>

                <!-- Update Password Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
                    <div class="flex items-start gap-4 mb-6 pb-6 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-display font-bold text-slate-900">Update Password</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                    </div>
                    <UpdatePasswordForm />
                </div>

                <!-- Delete Account Card -->
                <div class="bg-white border border-rose-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
                    <div class="flex items-start gap-4 mb-6 pb-6 border-b border-rose-100">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-display font-bold text-rose-900">Delete Account</h2>
                            <p class="text-xs text-rose-600/70 mt-0.5">Permanently delete your account and all associated resources.</p>
                        </div>
                    </div>
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>