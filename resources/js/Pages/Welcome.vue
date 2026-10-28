<script setup>
/**
 * SmartBooking SaaS Product Landing Page
 * World-Class High-Conversion Architecture
 * 
 * @author Pial Mahmud (System Architect)
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
    <Head title="SmartBooking | Multi-Tenant Appointment & Scheduling SaaS" />

    <div class="min-h-screen bg-slate-50/70 text-slate-900 font-sans antialiased selection:bg-brand-600 selection:text-white relative overflow-x-hidden">

        <!-- Dot Grid Blueprint Background -->
        <div class="fixed inset-0 z-0 pointer-events-none bg-dot-grid opacity-70"></div>

        <!-- Ambient Glow Spheres -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-brand-400/15 via-indigo-400/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>

        <!-- =========================================================
             1. GLASSMORPHIC TOP NAVBAR
             ========================================================= -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-brand-500/25">
                        <ApplicationLogo class="w-5 h-5 text-white" />
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-lg font-extrabold tracking-tight text-slate-900 font-display flex items-center gap-1.5 leading-none">
                            SmartBooking
                            <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-brand-50 text-brand-600 rounded border border-brand-200">SaaS</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium mt-1">Multi-Tenant Scheduling</span>
                    </div>
                </div>

                <!-- Center Nav Links -->
                <div class="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-600">
                    <a href="#features" class="hover:text-brand-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="hover:text-brand-600 transition-colors">How it Works</a>
                    <a href="#roles" class="hover:text-brand-600 transition-colors">Architecture</a>
                    <a href="#demo" class="hover:text-brand-600 transition-colors">Live Demo</a>
                </div>

                <!-- Right Auth Action Buttons -->
                <div v-if="canLogin" class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-4 py-2 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm shadow-brand-500/25 transition-all"
                    >
                        Go to Dashboard &rarr;
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-3.5 py-2 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-colors"
                        >
                            Sign In
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-brand-600 shadow-sm transition-all"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- =========================================================
             2. HERO VALUE PROPOSITION
             ========================================================= -->
        <section class="relative z-10 pt-16 sm:pt-24 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
            <!-- Signal Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-xs mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-semibold text-slate-700">Multi-Tenant Appointment Infrastructure</span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-bold text-brand-600">v4.2.0</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 font-display max-w-4xl mx-auto leading-[1.1]">
                Effortless appointment booking for <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-600">modern service businesses.</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                Give any salon, clinic, studio, or consultancy its own branded booking page, automated calendar scheduling, staff rosters, and client agenda—from a single unified platform.
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <Link
                    :href="route('register')"
                    class="px-7 py-3.5 rounded-xl text-sm font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-lg shadow-brand-500/25 transition-all active:scale-[0.98]"
                >
                    Start Free Trial
                </Link>

                <a
                    href="/b/elite-studio"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-xs transition-all"
                >
                    <span>View Demo Booking Page</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Hero Interactive Preview Showcase -->
            <div id="demo" class="mt-16 sm:mt-20 max-w-5xl mx-auto rounded-3xl bg-white border border-slate-200/80 shadow-2xl p-4 sm:p-6 text-left relative overflow-hidden">
                <!-- Mac-style top bar -->
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        <span class="ml-2 text-xs font-mono text-slate-400">smartbooking.saas/b/elite-studio</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Live Preview
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    <!-- Left: Service selection mockup -->
                    <div class="md:col-span-7 space-y-3">
                        <div class="p-4 rounded-2xl bg-brand-50/60 border border-brand-200/70 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Executive Haircut & Styling</h4>
                                <p class="text-xs text-slate-500 mt-0.5">45 minutes &bull; Master Stylist Sarah</p>
                            </div>
                            <span class="text-base font-extrabold text-brand-600 font-display">$45.00</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center justify-between opacity-80">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Deluxe Beard Grooming</h4>
                                <p class="text-xs text-slate-500 mt-0.5">30 minutes &bull; Senior Colorist Michael</p>
                            </div>
                            <span class="text-base font-bold text-slate-700 font-display">$30.00</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center justify-between opacity-80">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Full Scalp & Therapy Spa</h4>
                                <p class="text-xs text-slate-500 mt-0.5">60 minutes &bull; Master Stylist Sarah</p>
                            </div>
                            <span class="text-base font-bold text-slate-700 font-display">$75.00</span>
                        </div>
                    </div>

                    <!-- Right: Slot Picker Mockup -->
                    <div class="md:col-span-5 p-5 rounded-2xl bg-slate-900 text-white space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-300">Select Time Window</span>
                            <span class="text-[10px] text-brand-300 font-bold uppercase">Tomorrow</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-white/10 border border-white/10 text-center font-semibold hover:bg-white/20 transition-colors">
                                09:00 AM
                            </div>
                            <div class="p-2.5 rounded-xl bg-brand-600 text-white font-bold text-center shadow-md">
                                10:30 AM &check;
                            </div>
                            <div class="p-2.5 rounded-xl bg-white/10 border border-white/10 text-center font-semibold hover:bg-white/20 transition-colors">
                                01:30 PM
                            </div>
                            <div class="p-2.5 rounded-xl bg-white/10 border border-white/10 text-center font-semibold hover:bg-white/20 transition-colors">
                                03:00 PM
                            </div>
                        </div>

                        <Link
                            :href="route('register')"
                            class="block w-full py-2.5 text-center rounded-xl bg-white text-slate-900 text-xs font-bold uppercase tracking-wider hover:bg-brand-50 transition-colors"
                        >
                            Set Up Your Own Storefront
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- =========================================================
             3. PRODUCT FEATURES (BENTO GRID)
             ========================================================= -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Enterprise Ready</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-display mt-2 tracking-tight">
                    Engineered for businesses that value speed & privacy.
                </h2>
                <p class="text-sm text-slate-500 mt-3">
                    Everything you need to accept bookings, coordinate specialists, and analyze revenue in one stack.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Feature 1: Branded Booking Page -->
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-all space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 font-display">Branded Slug Portals</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Every business gets an isolated, high-speed public page at <code class="px-1.5 py-0.5 rounded bg-slate-100 font-mono text-xs">/b/{slug}</code> with custom logo, cover, and color accents.
                    </p>
                </div>

                <!-- Feature 2: Smart Slot Calculation -->
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-all space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 font-display">Collision-Free Slot Engine</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Automatic calculation of open intervals based on service duration, specialist schedule, and existing appointments. Zero double-booking risks.
                    </p>
                </div>

                <!-- Feature 3: Row-Level Tenant Isolation -->
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-all space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 font-display">Bulletproof Tenant Isolation</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Enforced at database and middleware levels via <code class="px-1.5 py-0.5 rounded bg-slate-100 font-mono text-xs">TenantScope</code>. No tenant can ever access another's private data.
                    </p>
                </div>

            </div>
        </section>

        <!-- =========================================================
             4. ARCHITECTURE & ROLE TIERS
             ========================================================= -->
        <section id="roles" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-t border-slate-200/60">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">3-Tier Access Layer</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-display mt-2 tracking-tight">
                    One unified codebase, three distinct experiences.
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Tier 1: Customer Experience -->
                <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-300">Role 01</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-white/10 text-white">Client</span>
                    </div>
                    <h3 class="text-xl font-bold font-display">Customer Agenda</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Self-service appointment hub where clients can review upcoming sessions, reschedule or cancel with 1-click, and discover nearby businesses.
                    </p>
                    <div class="pt-4 border-t border-white/10 text-xs text-brand-300 font-semibold">
                        &rarr; Dedicated route: /my-agenda
                    </div>
                </div>

                <!-- Tier 2: Business Owner Command -->
                <div class="p-6 sm:p-8 rounded-3xl bg-brand-600 text-white space-y-4 shadow-xl shadow-brand-500/20">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-100">Role 02</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-white/20 text-white">Owner</span>
                    </div>
                    <h3 class="text-xl font-bold font-display">Business Command Center</h3>
                    <p class="text-xs text-brand-100 leading-relaxed">
                        Comprehensive administrative suite with FullCalendar views, queue management, working hours scheduler, service catalog, and revenue tracking.
                    </p>
                    <div class="pt-4 border-t border-white/20 text-xs text-white font-semibold">
                        &rarr; Dedicated route: /dashboard
                    </div>
                </div>

                <!-- Tier 3: Super Admin Oversight -->
                <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Role 03</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-white/10 text-white">Platform</span>
                    </div>
                    <h3 class="text-xl font-bold font-display">Sovereign Oversight</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Platform-level command over all registered businesses, active vs. suspended states, system audit log ledger, and gross platform financial volume.
                    </p>
                    <div class="pt-4 border-t border-white/10 text-xs text-amber-300 font-semibold">
                        &rarr; Dedicated route: /system-control/oversight
                    </div>
                </div>

            </div>
        </section>

        <!-- =========================================================
             5. BOTTOM CTA BANNER
             ========================================================= -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
            <div class="p-8 sm:p-14 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white text-center space-y-6 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-16 -left-16 w-64 h-64 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <h2 class="text-3xl sm:text-5xl font-extrabold font-display tracking-tight text-white">
                    Ready to scale your appointment business?
                </h2>

                <p class="text-sm text-slate-300 max-w-lg mx-auto leading-relaxed">
                    Set up your business tenant in under 60 seconds with our automated onboarding wizard.
                </p>

                <div class="pt-2 flex flex-wrap items-center justify-center gap-4">
                    <Link
                        :href="route('register')"
                        class="px-8 py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-white text-slate-900 hover:bg-brand-50 transition-all shadow-md active:scale-95"
                    >
                        Create Your Business Now
                    </Link>
                </div>
            </div>
        </section>

        <!-- =========================================================
             6. FOOTER
             ========================================================= -->
        <footer class="py-10 border-t border-slate-200/60 text-center text-xs text-slate-400">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <ApplicationLogo class="w-4 h-4 text-brand-600" />
                    <span class="font-bold text-slate-700">SmartBooking SaaS</span>
                    <span>&bull; Built with Laravel 13, Inertia.js v2, Vue 3 & Tailwind CSS</span>
                </div>
                <div>
                    Designed & Engineered by <a href="https://github.com/mahmudpial" target="_blank" class="font-semibold text-slate-600 hover:text-brand-600">Pial Mahmud</a>
                </div>
            </div>
        </footer>

    </div>
</template>