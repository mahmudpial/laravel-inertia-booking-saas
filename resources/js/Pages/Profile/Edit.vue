<script setup>
/**
 * User Profile Hub v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Identity Management: Public communication node configuration.
 * 2. Security Handshaking: Credential encryption and key updates.
 * 3. Termination Protocol: Secure account purge logic.
 * 
 * @author Pial Mahmud (Chief Architect)
 */

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

    <Head title="Identity Hub | Account Settings" />

    <AuthenticatedLayout>
        <div
            class="relative min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] pb-48 selection:bg-indigo-600 selection:text-white">

            <!-- ARCHITECTURAL BLUEPRINT BACKGROUND -->
            <div class="fixed inset-0 z-0 pointer-events-none">
                <div
                    class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
                </div>
                <div
                    class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
                </div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 pt-16">

                <!-- =========================================================
                     EDITORIAL HERO SECTION (Two-Column Layout)
                     ========================================================= -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-8 animate-slideUp">
                        <div class="flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Secure
                                    Identity Hub Active</span>
                            </div>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            User <br />
                            <span
                                class="text-indigo-600 font-medium text-5xl lg:text-8xl uppercase tracking-tighter">Profile.</span>
                        </h1>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.5em] text-[10px]">Sentinel Node
                            v4.2.0-Alpha</p>
                    </div>

                    <!-- PRIVACY INTELLIGENCE BRIEFING -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Privacy
                                    Intelligence Briefing</p>
                                <div class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                                    <span class="text-[8px] font-black uppercase tracking-widest text-emerald-400">Node
                                        Secure</span>
                                </div>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">The
                                Sanctity of <br /> Administrative Data.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    In our professional ecosystem, your **Identity Node** is the core anchor of
                                    operational authority. We prioritize the absolute **Confidentiality** of your
                                    credentials through systematic packet masking and Node-Sentinel protocols.
                                </p>
                                <p>
                                    Managing your profile is a secure handshake between your local machine and our cloud
                                    infrastructure. By maintaining synchronized security keys, you ensure the long-term
                                    integrity of your booking grid and personnel assets.
                                </p>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- =========================================================
                     TACTICAL MANAGEMENT MODULES
                     ========================================================= -->
                <div class="space-y-12 max-w-5xl mx-auto">

                    <!-- MODULE 01: IDENTITY CONFIGURATION -->
                    <div
                        class="group relative bg-white border border-slate-200/80 rounded-[4rem] p-10 lg:p-16 transition-all duration-500 hover:border-indigo-500/40 hover:shadow-2xl">
                        <div class="flex flex-col lg:flex-row gap-16">
                            <div class="lg:w-1/3 space-y-6">
                                <div
                                    class="w-16 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-2xl shadow-indigo-900/30">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                    Public <br /> Identity.</h3>
                                <p
                                    class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] leading-relaxed">
                                    Modify your node labels and communication identifiers.</p>
                            </div>
                            <div class="lg:w-2/3">
                                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                            </div>
                        </div>
                    </div>

                    <!-- MODULE 02: CREDENTIAL ENCRYPTION -->
                    <div
                        class="group relative bg-white border border-slate-200/80 rounded-[4rem] p-10 lg:p-16 transition-all duration-500 hover:border-indigo-500/40 hover:shadow-2xl">
                        <div class="flex flex-col lg:flex-row gap-16">
                            <div class="lg:w-1/3 space-y-6">
                                <div
                                    class="w-16 h-14 rounded-2xl bg-[#09090B] flex items-center justify-center text-white shadow-2xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                    Security <br /> Keys.</h3>
                                <p
                                    class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] leading-relaxed">
                                    Rotate encryption passphrases to maintain system-wide lockdown.</p>
                            </div>
                            <div class="lg:w-2/3">
                                <UpdatePasswordForm />
                            </div>
                        </div>
                    </div>

                    <!-- MODULE 03: ABSOLUTE PURGE PROTOCOL -->
                    <div
                        class="group relative bg-white border border-rose-100 rounded-[4rem] p-10 lg:p-16 transition-all duration-500 hover:border-rose-300 hover:shadow-2xl">
                        <div class="flex flex-col lg:flex-row gap-16">
                            <div class="lg:w-1/3 space-y-6">
                                <div
                                    class="w-16 h-14 rounded-2xl bg-rose-600 flex items-center justify-center text-white shadow-2xl shadow-rose-900/30">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-black text-rose-900 tracking-tighter uppercase leading-none">
                                    Danger <br /> Zone.</h3>
                                <p
                                    class="text-rose-600/60 text-[10px] font-black uppercase tracking-[0.3em] leading-relaxed">
                                    Initiate global node termination and data purging sequences.</p>
                            </div>
                            <div class="lg:w-2/3">
                                <DeleteUserForm />
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER TAG -->
                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">Operational Infrastructure
                        v4.2.0-Alpha.01</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(span),
:deep(p),
:deep(button) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

/* Slide-up staggered animation for data pods */
.space-y-12>div {
    animation: slideUpSimple 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
}

.space-y-12>div:nth-child(1) {
    animation-delay: 0.1s;
}

.space-y-12>div:nth-child(2) {
    animation-delay: 0.2s;
}

.space-y-12>div:nth-child(3) {
    animation-delay: 0.3s;
}

@keyframes slideUpSimple {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.animate-slideUp {
    animation: slideUpSimple 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fadeIn {
    animation: fadeIn 1.5s ease-out forwards;
}
</style>