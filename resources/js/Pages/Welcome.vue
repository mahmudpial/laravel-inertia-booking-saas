<script setup>
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

    <Head title="SmartBooking | Appointment Intelligence" />

    <div
        class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] font-sans antialiased text-[#11131A] selection:bg-indigo-600 selection:text-white">

        <!-- =========================================================
             PREMIUM NAVIGATION (GLASSMORTH)
             ========================================================= -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-200/60">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-[#11131A] rounded-xl flex items-center justify-center text-white shadow-xl shadow-indigo-900/20">
                        <ApplicationLogo class="w-6 h-auto" />
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase">Smart<span
                            class="text-indigo-600">Booking.</span></span>
                </div>

                <div v-if="canLogin" class="flex items-center gap-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="px-6 py-2.5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl hover:bg-indigo-600 transition-all active:scale-95">
                        Command Hub
                    </Link>

                    <template v-else>
                        <Link :href="route('login')"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-[#11131A] transition-colors">
                            Authenticate
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-indigo-900/20 hover:bg-[#11131A] transition-all active:scale-95 ml-4">
                            Initialize Account
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- =========================================================
             HERO SECTION
             ========================================================= -->
        <main class="pt-40 pb-20 px-6">
            <div
                class="max-w-7xl mx-auto text-center lg:text-left grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">

                <div class="lg:col-span-7 space-y-8">
                    <div
                        class="inline-flex items-center gap-2.5 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">System Live
                            v4.2.0</span>
                    </div>

                    <h1 class="text-6xl lg:text-8xl font-black tracking-tighter leading-[0.85] text-[#11131A]">
                        Appointment <br />
                        <span class="text-indigo-600">Intelligence.</span>
                    </h1>

                    <p class="text-slate-500 max-w-xl text-lg font-medium leading-relaxed">
                        The definitive ecosystem for professional service management. Automated scheduling, tactical
                        revenue
                        analytics, and high-contrast branding for modern businesses.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                        <Link :href="route('register')"
                            class="w-full sm:w-auto px-10 py-5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:bg-indigo-600 transition-all active:scale-95">
                            Start Deploying
                        </Link>
                        <a href="#features"
                            class="w-full sm:w-auto px-10 py-5 bg-white border border-slate-200 text-[#11131A] text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-slate-50 transition-all">
                            System Specs
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 relative hidden lg:block">
                    <!-- Abstract System Visual -->
                    <div
                        class="relative w-full aspect-square bg-[#11131A] rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(79,70,229,0.3)] overflow-hidden flex items-center justify-center group">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_center,#4f46e533_0%,transparent_70%)]">
                        </div>
                        <ApplicationLogo
                            class="w-32 h-auto text-indigo-500 opacity-20 group-hover:scale-110 transition-transform duration-1000" />

                        <!-- Floating Data Pod -->
                        <div
                            class="absolute bottom-10 left-10 right-10 bg-white/10 backdrop-blur-xl border border-white/10 p-6 rounded-3xl">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest">Active
                                    Packets</span>
                                <span class="text-xs font-black text-white tracking-tighter">Synchronized</span>
                            </div>
                            <div class="h-1 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 w-[70%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- =========================================================
             FEATURE GRID
             ========================================================= -->
        <section id="features" class="py-24 px-6 border-t border-slate-200/60 bg-white/40">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- Card 1 -->
                    <div
                        class="group p-10 bg-white border border-slate-200/80 rounded-[3rem] shadow-xl shadow-slate-200/30 hover:border-indigo-400 transition-all duration-500">
                        <div
                            class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-8 group-hover:bg-[#11131A] group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight mb-4 uppercase">Automated Queue</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">Real-time scheduling logic with
                            conflict
                            resolution and automated slot quantization.</p>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="group p-10 bg-white border border-slate-200/80 rounded-[3rem] shadow-xl shadow-slate-200/30 hover:border-indigo-400 transition-all duration-500">
                        <div
                            class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-8 group-hover:bg-[#11131A] group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight mb-4 uppercase">Revenue Intelligence</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">Financial dashboard to monitor
                            earnings,
                            average unit value, and business throughput.</p>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="group p-10 bg-white border border-slate-200/80 rounded-[3rem] shadow-xl shadow-slate-200/30 hover:border-indigo-400 transition-all duration-500">
                        <div
                            class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 mb-8 group-hover:bg-[#11131A] group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014.243 4.242 10.003 10.003 0 004.243 14.243m4.242 4.242L9.88 21.362A11.194 11.194 0 012 12c0-5.114 3.41-9.434 8.06-10.832" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight mb-4 uppercase">Identity Suite</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">Fully customizable public
                            storefronts and
                            branding assets for your unique business mark.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- =========================================================
             ULTRA-PREMIUM OBSIDIAN FOOTER (SYNCED)
             ========================================================= -->
        <footer class="bg-[#11131A] text-white pt-20 pb-10 border-t border-white/5 relative overflow-hidden">
            <div
                class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent">
            </div>

            <div class="mx-auto max-w-7xl px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-12 mb-16">

                    <!-- Footer Brand -->
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 shadow-2xl">
                            <ApplicationLogo class="h-6 w-auto text-white" />
                        </div>
                        <div class="text-left">
                            <span class="text-2xl font-black tracking-tighter text-white block leading-none">Smart<span
                                    class="text-indigo-500">Booking.</span></span>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">Engine
                                    Build
                                    v4.2.0</span>
                                <div class="w-1 h-1 rounded-full bg-emerald-500"></div>
                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">Global
                                    Launch</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Navigator -->
                    <nav class="flex flex-wrap justify-center items-center gap-x-8 gap-y-3">
                        <Link v-if="canLogin" :href="route('login')"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">
                            Authentication</Link>
                        <a href="https://github.com"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">Open
                            Source</a>
                        <a href="#"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">Support
                            Desk</a>
                    </nav>

                    <!-- Architect Card -->
                    <div class="relative group">
                        <div
                            class="flex items-center gap-4 p-2 pr-5 bg-white/5 border border-white/5 rounded-[1.5rem] backdrop-blur-md group-hover:border-indigo-500/40 transition-all duration-500 shadow-2xl">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#11131A] border border-white/10 flex items-center justify-center text-indigo-500 font-black text-[10px]">
                                PM</div>
                            <div class="text-left border-r border-white/10 pr-4">
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.4em]">Architect</p>
                                <a href="https://linkedin.com/in/pial-mahmud" target="_blank"
                                    class="text-xs font-black text-white hover:text-indigo-400 transition-colors uppercase tracking-widest">Pial
                                    Mahmud</a>
                            </div>
                            <div class="flex gap-2">
                                <a href="https://github.com" class="text-slate-500 hover:text-white transition-all"><svg
                                        class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.182-1.304.412-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.5em]">
                        &copy; {{ new Date().getFullYear() }} SmartBooking Infrastructure. Developed for Excellence.
                    </p>
                    <div class="flex items-center gap-8">
                        <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-500">Laravel v{{
                            laravelVersion
                        }}</span>
                        <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-500">PHP v{{ phpVersion
                        }}</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(span),
:deep(p),
:deep(a) {
    font-family: 'Space Grotesk', sans-serif !important;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

main {
    animation: fadeIn 0.8s ease-out forwards;
}
</style>