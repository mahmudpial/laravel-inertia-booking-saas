<script setup>
/**
 * SmartBooking Welcome Portal v4.2.0-Alpha
 * 
 * CORE ARCHITECTURE:
 * 1. Strategic Identity: Macro-typography and high-contrast branding.
 * 2. Trust Engineering: Explicit security protocol signaling.
 * 3. Tactical Discovery: Service SKU performance overview.
 * 
 * @author Pial Mahmud (Lead Architect)
 */

import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>

    <Head title="SmartBooking | Secure Appointment Infrastructure" />

    <div
        class="min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] selection:bg-indigo-600 selection:text-white relative overflow-x-hidden">

        <!-- =========================================================
             AESTHETIC BLUEPRINT BACKGROUND (FIXED)
             ========================================================= -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
            </div>
        </div>

        <!-- =========================================================
             NAVIGATION: ARCHITECTURAL GLASS
             ========================================================= -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/70 backdrop-blur-2xl border-b border-slate-200/60">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-4 group cursor-pointer">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#09090B] shadow-2xl shadow-indigo-900/20 group-hover:bg-indigo-600 transition-all duration-500">
                        <ApplicationLogo class="h-6 w-auto text-white" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tighter uppercase leading-none">Smart<span
                                class="text-indigo-600">Booking.</span></span>
                        <span
                            class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mt-1">Infrastructure
                            Node</span>
                    </div>
                </div>

                <div v-if="canLogin" class="flex items-center gap-6">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="px-8 py-3 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl hover:bg-indigo-600 transition-all active:scale-95">
                        Access Hub
                    </Link>

                    <template v-else>
                        <Link :href="route('login')"
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 hover:text-indigo-600 transition-colors">
                            Authenticate
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="px-8 py-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-900/30 hover:bg-[#09090B] transition-all active:scale-95 ml-4">
                            Deploy Node
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- =========================================================
             HERO: MISSION CRITICAL DISPATCH
             ========================================================= -->
        <header class="relative z-10 pt-48 pb-32 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-20 items-center">

                <div class="lg:col-span-7 space-y-10 animate-slideUp">
                    <div
                        class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600">Global
                            Operational
                            Integrity v4.2</span>
                    </div>

                    <h1 class="text-7xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                        Automate <br />
                        <span class="text-indigo-600">Precision.</span>
                    </h1>

                    <p class="text-slate-500 max-w-xl text-xl font-medium leading-relaxed">
                        The definitive ecosystem for professional session management. Engineered for scalability,
                        real-time
                        tactical analytics, and total data confidentiality.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 pt-6">
                        <Link :href="route('register')"
                            class="w-full sm:w-auto px-12 py-6 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.2)] hover:bg-indigo-600 transition-all active:scale-95">
                            Initialize Deployment
                        </Link>
                        <a href="#aim"
                            class="w-full sm:w-auto px-12 py-6 bg-white border border-slate-200 text-[#09090B] text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] hover:bg-slate-50 transition-all">
                            System Aim
                        </a>
                    </div>
                </div>

                <!-- HERO VISUAL POD: SYSTEM PREVIEW -->
                <div class="lg:col-span-5 relative hidden lg:block animate-fadeIn">
                    <div
                        class="relative w-full aspect-[4/5] bg-[#09090B] rounded-[5rem] shadow-[0_50px_100px_-20px_rgba(79,70,229,0.4)] overflow-hidden group border border-white/5">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e544_0%,transparent_70%)]">
                        </div>
                        <div class="absolute inset-0 flex flex-col p-16 justify-between">
                            <ApplicationLogo
                                class="w-24 h-auto text-white opacity-20 group-hover:scale-110 transition-transform duration-1000" />
                            <div class="space-y-8">
                                <div class="h-px w-full bg-white/10"></div>
                                <p
                                    class="text-3xl font-black text-white leading-none uppercase italic tracking-tighter">
                                    Confidentiality <br /> By Design.
                                </p>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="px-5 py-2 bg-indigo-600/20 backdrop-blur-md rounded-full border border-indigo-500/30 text-[9px] font-black text-indigo-400 uppercase tracking-widest">
                                        A-Grade Encryption</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- =========================================================
             MISSION & SECURITY SECTION
             ========================================================= -->
        <section id="aim"
            class="relative z-10 py-40 px-6 bg-[#09090B] text-white rounded-t-[6rem] lg:rounded-t-[10rem] shadow-[0_-30px_100px_rgba(0,0,0,0.2)]">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 mb-32 items-center">
                    <div class="lg:col-span-6">
                        <p class="text-indigo-400 font-black text-[10px] uppercase tracking-[0.5em] mb-8">Platform
                            Objectives
                        </p>
                        <h2 class="text-6xl lg:text-8xl font-black tracking-tighter leading-[0.8] uppercase mb-8">
                            Project <br />
                            <span class="text-slate-500">Mission.</span>
                        </h2>
                        <p
                            class="text-slate-400 text-lg font-medium leading-relaxed italic border-l-4 border-indigo-600 pl-8">
                            "SmartBooking is engineered to redefine the bridge between administrative logic and client
                            discovery. We quantize time to maximize your revenue throughput."
                        </p>
                    </div>
                    <div class="lg:col-span-6 bg-white/5 border border-white/5 p-12 rounded-[4rem] backdrop-blur-3xl">
                        <h3 class="text-2xl font-black uppercase tracking-tighter mb-6">Security Priority One.</h3>
                        <p class="text-slate-500 text-base leading-relaxed font-medium mb-8">
                            Ensuring maximum data security and absolute confidentiality for our admins and users is our
                            top
                            priority. We implement Node-Sentinel protocols to protect every session packet.
                        </p>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <p class="text-3xl font-black text-white tracking-tighter">99.9%</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Uptime Sync
                                </p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-3xl font-black text-indigo-500 tracking-tighter">256-Bit</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Packet Masking
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TACTICAL SERVICE CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="(feature, f) in features" :key="f"
                        class="group p-12 bg-white/5 border border-white/5 rounded-[4rem] hover:bg-white/10 hover:border-indigo-500/50 transition-all duration-700">
                        <div :class="feature.color"
                            class="w-16 h-16 rounded-3xl flex items-center justify-center text-white mb-12 shadow-2xl transition-transform duration-500 group-hover:rotate-6 group-hover:scale-110">
                            <component :is="feature.icon" class="w-8 h-8" />
                        </div>
                        <h3 class="text-3xl font-black tracking-tighter uppercase mb-6 leading-none">{{ feature.title }}
                        </h3>
                        <p class="text-slate-500 text-base leading-relaxed font-medium">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- =========================================================
             FOOTER: THE ARCHITECT SIGNATURE
             ========================================================= -->
        <footer class="bg-[#09090B] text-white pt-32 pb-16 overflow-hidden border-t border-white/5 relative">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-16 mb-24">
                    <div class="flex items-center gap-6">
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-indigo-600 shadow-2xl">
                            <ApplicationLogo class="h-8 w-auto text-white" />
                        </div>
                        <div class="text-left">
                            <span
                                class="text-4xl font-black tracking-tighter text-white block leading-none uppercase">SmartBooking<span
                                    class="text-indigo-500">.</span></span>
                            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-600 mt-4">Enterprise
                                Infrastructure Suite</p>
                        </div>
                    </div>

                    <!-- Pial Mahmud Signature Card -->
                    <div class="relative group">
                        <div
                            class="flex items-center gap-5 p-3 pr-8 bg-white/5 border border-white/10 rounded-[2.5rem] backdrop-blur-md group-hover:border-indigo-500/40 transition-all duration-500 shadow-2xl">
                            <div
                                class="w-14 h-14 rounded-2xl bg-[#09090B] border border-white/10 flex items-center justify-center text-white font-black text-xs">
                                PM</div>
                            <div class="text-left border-r border-white/10 pr-8">
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.4em]">Chief
                                    Architect</p>
                                <a href="https://linkedin.com/in/pial-mahmud" target="_blank"
                                    class="text-lg font-black text-white hover:text-indigo-400 transition-colors uppercase tracking-widest leading-none">Pial
                                    Mahmud</a>
                            </div>
                            <div class="flex gap-5 ml-2">
                                <a href="https://github.com" target="_blank"
                                    class="text-slate-500 hover:text-white transition-all scale-100 hover:scale-125"><svg
                                        class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.182-1.304.412-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright Integrity Bar -->
                <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-[9px] font-black text-slate-700 uppercase tracking-[0.6em]">
                        &copy; {{ new Date().getFullYear() }} NODE INFRASTRUCTURE. ALL OPERATIONS LOGGED.
                    </p>
                    <div class="flex items-center gap-10">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></div>
                            <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-500">System
                                Secure</span>
                        </div>
                        <span
                            class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 underline decoration-indigo-500 underline-offset-4">Version
                            4.2.0-FINAL</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
// TACTICAL FEATURE DATA
const features = [
    {
        title: "Tactile Security",
        desc: "End-to-end masked session packets ensure your administrative data remains strictly confidential and non-indexed by external entities.",
        color: "bg-indigo-600",
        icon: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' }
    },
    {
        title: "Yield Analytics",
        desc: "High-contrast revenue intelligence modules provide a surgical view of your business throughput and specialist performance index.",
        color: "bg-emerald-600",
        icon: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>' }
    },
    {
        title: "Identity Mark",
        desc: "Customizable Brand Studios allow you to deploy a unique visual mark to your discovery node, establishing trust before the first click.",
        color: "bg-[#11131A]",
        icon: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>' }
    }
];
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(span),
:deep(p),
:deep(a),
:deep(button) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
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
    animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fadeIn {
    animation: fadeIn 1.5s ease-out forwards;
}

/* Custom touch-scroll for feature cards on small devices */
@media (max-width: 1024px) {
    h1 {
        font-size: 4.5rem;
    }

    .text-9xl {
        font-size: 5rem;
    }
}
</style>