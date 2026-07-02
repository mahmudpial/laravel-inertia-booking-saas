<script setup>
/**
 * SmartBooking Identity Suite: AuthLayout v4.2.0
 * 
 * DESIGN SPECIFICATIONS:
 * - Font: Space Grotesk (700-900 Weight)
 * - Palette: Obsidian (#09090B), System Indigo (#4f46e5), Ceramic White (#FDFDFF)
 * - Grid: 32px Radial Blueprint + 14x24 Linear Matrix
 * 
 * @author Pial Mahmud (Lead Architect)
 */

import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div
        class="min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] flex items-center justify-center p-0 sm:p-6 lg:p-12 overflow-x-hidden selection:bg-indigo-600 selection:text-white">

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
             MAIN COMMAND CENTER CONTAINER
             ========================================================= -->
        <div
            class="relative z-10 w-full max-w-[1400px] min-h-screen lg:min-h-[850px] bg-white border border-slate-200 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] rounded-none sm:rounded-[4rem] flex flex-col lg:flex-row overflow-hidden animate-fadeIn">

            <!-- LEFT SIDE: DYNAMIC TACTICAL ENGINE (Forms) -->
            <div class="lg:w-[42%] p-10 lg:p-24 flex flex-col justify-center relative bg-white z-20 order-2 lg:order-1">
                <div class="w-full max-w-md mx-auto">
                    <!-- Dynamic form content injected here -->
                    <slot />
                </div>
            </div>

            <!-- RIGHT SIDE: THE BRAND MONUMENT (Identity) -->
            <div
                class="lg:w-[58%] bg-[#09090B] relative overflow-hidden flex flex-col justify-between p-12 lg:p-24 text-white order-1 lg:order-2">
                <!-- High-Performance Background Glow -->
                <div
                    class="absolute top-0 right-0 w-[700px] h-[700px] bg-indigo-600/15 blur-[120px] rounded-full -mr-48 -mt-48 animate-pulse">
                </div>

                <!-- 01. Brand Header -->
                <div class="relative z-10 flex items-center gap-6 group">
                    <div
                        class="w-16 h-16 bg-indigo-600 rounded-[1.5rem] flex items-center justify-center shadow-2xl shadow-indigo-900/40 transform group-hover:rotate-6 transition-all duration-700">
                        <ApplicationLogo class="h-10 w-auto text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-black tracking-tighter uppercase leading-none">SmartBooking<span
                                class="text-indigo-500">.</span></h1>
                        <p class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-500 mt-2 leading-none">
                            Infrastructure Intelligence</p>
                    </div>
                </div>

                <!-- 02. Studio Intelligence Briefing -->
                <div class="relative z-10 max-w-lg space-y-12 my-20 lg:my-0">
                    <div class="space-y-4">
                        <span
                            class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.6em] block">Operational
                            Briefing</span>
                        <h3 class="text-6xl lg:text-7xl font-black tracking-tighter uppercase leading-[0.85]">The Era of
                            <br /> Precision.
                        </h3>
                    </div>

                    <article class="text-slate-400 text-lg leading-relaxed font-medium italic space-y-8">
                        <p class="relative">
                            <span class="absolute -left-6 top-0 text-4xl text-indigo-500/20 font-serif">"</span>
                            In a professional ecosystem, efficiency is determined by how we manage the quantization of
                            time. Our engine provides the architectural stability required to scale your resources
                            without friction.
                        </p>
                        <div class="flex flex-col gap-2 pt-4">
                            <p
                                class="text-[10px] not-italic text-slate-500 font-black uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-indigo-600"></span> Aim: Total Data Confidentiality
                            </p>
                            <p
                                class="text-[10px] not-italic text-slate-500 font-black uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-indigo-600"></span> Priority: Global Node Sync
                            </p>
                        </div>
                    </article>

                    <!-- Architect Tactical Dock (Synchronized) -->
                    <div class="pt-10 flex items-center gap-4">
                        <div
                            class="flex items-center gap-4 p-2.5 pr-8 bg-white/5 border border-white/5 rounded-full backdrop-blur-md hover:bg-white/10 transition-all duration-500">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-[11px] shadow-lg">
                                PM</div>
                            <div class="text-left border-r border-white/10 pr-8 mr-2">
                                <p class="text-[7px] font-black text-slate-500 uppercase tracking-[0.4em]">Architect</p>
                                <span class="text-xs font-black text-white uppercase tracking-widest leading-none">Pial
                                    Mahmud</span>
                            </div>
                            <div class="flex items-center gap-5">
                                <a href="https://github.com" target="_blank"
                                    class="text-slate-400 hover:text-white transition-all scale-100 hover:scale-125">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.182-1.304.412-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg>
                                </a>
                                <a href="https://linkedin.com/in/pial-mahmud" target="_blank"
                                    class="text-slate-400 hover:text-[#0077b5] transition-all scale-100 hover:scale-125">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                    </svg>
                                </a>
                                <a href="https://upwork.com" target="_blank"
                                    class="text-slate-400 hover:text-[#14a800] transition-all scale-100 hover:scale-125">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.586 6.586c-2.072 0-3.834 1.343-4.664 3.208-.504-.73-.934-1.564-1.266-2.484H9.014v5.334c0 1.542-1.258 2.8-2.8 2.8s-2.8-1.258-2.8-2.8V7.31H.614v5.334c0 3.088 2.512 5.6 5.6 5.6s5.6-2.512 5.6-5.6V11.23c.27.534.618 1.056 1.036 1.546l-1.928 6.534h2.868l1.328-4.516c.828.618 1.83.99 2.92.99 2.756 0 5-2.244 5-5s-2.244-5-5-5zm0 7.2c-1.214 0-2.2-.986-2.2-2.2s.986-2.2 2.2-2.2 2.2.986 2.2 2.2-.986 2.2-2.2 2.2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 03. Technical Status Bar -->
                <div class="relative z-10 flex items-center justify-between border-t border-white/5 pt-10">
                    <div class="flex items-center gap-6">
                        <p class="text-[8px] font-black text-slate-600 uppercase tracking-[0.8em]">Node-Sentinel v4.2.0
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_12px_#10b981] animate-pulse">
                        </div>
                        <span
                            class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Security:
                            Authorized Environment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

/* CTO Force: Ensures surgical precision of typography across all auth views */
:deep(body),
:deep(input),
:deep(label),
:deep(button),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(p),
:deep(span),
:deep(a) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

/* Scrollbar Purge */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.99);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fadeIn {
    animation: fadeIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>