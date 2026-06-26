<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <!-- SIGNATURE RADIAL GRID BACKGROUND -->
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] px-6 py-12 selection:bg-indigo-100">

        <!-- SECURE LOGO ANCHOR -->
        <div class="mb-10 transition-transform duration-500 hover:scale-105">
            <Link href="/" class="flex flex-col items-center gap-4 group">
                <div
                    class="w-20 h-20 bg-[#11131A] rounded-[2.5rem] flex items-center justify-center shadow-2xl shadow-indigo-900/30 group-hover:bg-indigo-600 transition-all duration-500 relative">
                    <!-- Subtle pulsing outer ring for security feel -->
                    <div class="absolute inset-0 rounded-[2.5rem] bg-indigo-500/20 animate-ping group-hover:opacity-0">
                    </div>
                    <ApplicationLogo class="h-10 w-auto text-white relative z-10" />
                </div>
                <div class="text-center">
                    <span class="text-xl font-black tracking-tighter text-[#11131A] block">Smart<span
                            class="text-indigo-600">Booking.</span></span>
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400 mt-1">Authentication Hub
                    </p>
                </div>
            </Link>
        </div>

        <!-- ULTRA-PREMIUM AUTH CARD -->
        <div class="w-full sm:max-w-md relative">
            <!-- Decorative Glow behind card -->
            <div
                class="absolute -inset-4 bg-gradient-to-tr from-indigo-500/10 to-purple-500/5 blur-3xl opacity-50 rounded-full">
            </div>

            <div
                class="relative bg-white/80 backdrop-blur-xl border border-white rounded-[3rem] shadow-2xl shadow-slate-200/60 p-10 lg:p-12 overflow-hidden">
                <!-- Top Accent Line -->
                <div
                    class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-transparent via-indigo-500/20 to-transparent">
                </div>

                <!-- Form Content Injection -->
                <div class="relative z-10">
                    <slot />
                </div>
            </div>

            <!-- FOOTER SYSTEM INFO -->
            <div class="mt-10 text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Secure Operational
                    Environment</p>
                <div class="flex items-center justify-center gap-4 mt-4">
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

/* Force Space Grotesk on all slotted elements (Login/Register forms) */
:deep(body),
:deep(input),
:deep(button),
:deep(label),
:deep(a),
:deep(p) {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* Custom subtle animation for card entry */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sm\:max-w-md {
    animation: fadeInUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
</style>