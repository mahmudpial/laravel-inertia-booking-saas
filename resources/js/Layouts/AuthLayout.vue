<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans antialiased text-slate-900 flex items-center justify-center p-4 sm:p-6 lg:p-8 selection:bg-brand-600 selection:text-white">
        <!-- Blueprint Dot Grid Background -->
        <div class="fixed inset-0 z-0 pointer-events-none bg-dot-grid opacity-60"></div>

        <!-- Main Auth Container -->
        <div class="relative z-10 w-full max-w-5xl bg-white border border-slate-200/80 shadow-2xl rounded-3xl flex flex-col lg:flex-row overflow-hidden">
            <!-- Left Side: Form Area -->
            <div class="lg:w-1/2 p-8 sm:p-12 lg:p-14 flex flex-col justify-center relative bg-white z-20">
                <div class="w-full max-w-sm mx-auto">
                    <!-- Mobile Brand Header -->
                    <div class="flex items-center gap-3 mb-8 lg:hidden">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-brand-500/30">
                            <ApplicationLogo class="w-5 h-5 text-white" />
                        </div>
                        <span class="font-display font-bold text-xl tracking-tight text-slate-900">SmartBooking</span>
                    </div>

                    <slot />
                </div>
            </div>

            <!-- Right Side: Brand Showcase Panel -->
            <div class="lg:w-1/2 bg-slate-950 relative overflow-hidden flex flex-col justify-between p-10 lg:p-12 text-white border-l border-slate-900">
                <!-- Background Ambient Glow -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Top Brand Header -->
                <div class="relative z-10 flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/25">
                        <ApplicationLogo class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <span class="font-display font-extrabold text-xl tracking-tight text-white block">SmartBooking<span class="text-brand-400">.</span></span>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Modern Booking OS</span>
                    </div>
                </div>

                <!-- Middle Content / Value Props -->
                <div class="relative z-10 my-12 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-500/10 border border-brand-500/20 rounded-full text-brand-300 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Trusted by 5,000+ Studios Worldwide
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-white tracking-tight leading-tight">
                        Frictionless booking infrastructure for fast-growing businesses.
                    </h2>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        From solo specialists to multi-location studios, automate your scheduling, payments, client communications, and staff roster in one unified platform.
                    </p>

                    <!-- Feature Highlights -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Real-time availability sync & Cal.com style booking</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Multi-tenant isolation & custom branded portals</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Automated reminders & Google Calendar export</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Status Bar -->
                <div class="relative z-10 pt-6 border-t border-slate-900 flex items-center justify-between text-xs text-slate-400">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        All systems operational
                    </span>
                    <span>v4.2 SaaS Edition</span>
                </div>
            </div>
        </div>
    </div>
</template>