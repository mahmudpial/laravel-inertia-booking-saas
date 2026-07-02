<script setup>
/**
 * SmartBooking Identity Suite: GuestLayout v4.2.0
 * 
 * CORE ARCHITECTURE:
 * 1. Blueprint Grid: Dual-layer background for engineered atmosphere.
 * 2. Tactical Pods: High-contrast Obsidian frames for security trust.
 * 3. Clinical Typography: Space Grotesk 900 for authoritative headers.
 * 
 * @author Pial Mahmud (System Architect)
 */

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <!-- SIGNATURE BLUEPRINT BACKGROUND SYSTEM -->
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#FDFDFF] px-6 py-12 selection:bg-indigo-600 selection:text-white relative overflow-hidden">

        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
            </div>
        </div>

        <!-- SECURE LOGO ANCHOR -->
        <div class="relative z-10 mb-12 animate-fadeIn">
            <Link href="/" class="flex flex-col items-center gap-4 group">
                <div
                    class="w-20 h-20 bg-[#09090B] rounded-[2.5rem] flex items-center justify-center shadow-2xl shadow-indigo-900/30 group-hover:bg-indigo-600 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,#4f46e533_0%,transparent_70%)]">
                    </div>
                    <ApplicationLogo
                        class="h-10 w-auto text-white relative z-10 group-hover:rotate-6 transition-transform" />
                </div>
                <div class="text-center">
                    <span class="text-xl font-black tracking-tighter text-[#09090B] block uppercase">SmartBooking<span
                            class="text-indigo-600">.</span></span>
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400 mt-2">Authorization Node
                        v4.2</p>
                </div>
            </Link>
        </div>

        <!-- ULTRA-PREMIUM AUTHENTICATION MODULE -->
        <div class="w-full sm:max-w-md relative z-10 animate-slideUp">
            <!-- Background Glow logic for depth -->
            <div
                class="absolute -inset-4 bg-gradient-to-tr from-indigo-500/10 to-purple-500/5 blur-3xl opacity-50 rounded-full">
            </div>

            <div
                class="relative bg-white/80 backdrop-blur-2xl border border-slate-200/60 rounded-[3.5rem] shadow-2xl shadow-slate-200/60 p-10 lg:p-14 overflow-hidden group">
                <!-- Tactical Top Accent -->
                <div
                    class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent">
                </div>

                <!-- Injection Slot for Login/Register content -->
                <div class="relative z-10">
                    <slot />
                </div>
            </div>

            <!-- FOOTER PROTOCOL INFO -->
            <div class="mt-12 text-center space-y-6">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em]">Secure Operational
                        Environment</p>
                </div>

                <!-- Tactical separator -->
                <div class="flex items-center justify-center gap-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

/* Force Space Grotesk on all slotted elements to ensure absolute brand consistency */
:deep(body),
:deep(input),
:deep(button),
:deep(label),
:deep(a),
:deep(p),
:deep(h1),
:deep(h2) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

@keyframes slideUp {
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
    animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fadeIn {
    animation: fadeIn 1.5s ease-out forwards;
}

/* Custom shadow refinement */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
}
</style>