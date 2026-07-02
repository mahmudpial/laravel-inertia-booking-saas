<script setup>
/**
 * Sovereign Control Framework v4.3.0-PREMIUM
 * 
 * ARCHITECTURAL FIXES:
 * 1. Flex-Grid Layout: Strictly separated Sidebar from Main Canvas on Desktop.
 * 2. Z-Index Management: Mobile drawer sits at z-[600] to ensure absolute priority.
 * 3. Body Scroll Sync: Prevent double scrollbars during navigation.
 */

import { ref, computed, watch, onUnmounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const user = computed(() => usePage().props.auth.user);
const isSidebarOpen = ref(false);

const navModules = [
    { label: 'Oversight', route: 'superadmin.dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { label: 'Entity Manager', route: 'superadmin.entities.index', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { label: 'Financials', route: 'superadmin.financials', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'Audit Ledger', route: 'superadmin.audit', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { label: 'System Config', route: 'superadmin.config', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
];

router.on('finish', () => {
    isSidebarOpen.value = false;
    document.body.style.overflow = '';
});

watch(isSidebarOpen, (val) => {
    if (typeof document !== 'undefined') {
        document.body.style.overflow = val ? 'hidden' : '';
    }
});

onUnmounted(() => {
    if (typeof document !== 'undefined') document.body.style.overflow = '';
});
</script>

<template>
    <div
        class="min-h-screen flex bg-[#FDFDFF] font-sans antialiased text-[#09090B] selection:bg-indigo-600 selection:text-white overflow-hidden">

        <!-- BLUEPRINT BACKGROUND SYSTEM -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
            </div>
        </div>

        <!-- MOBILE BACKDROP -->
        <Transition name="fade">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
                class="fixed inset-0 bg-[#09090B]/90 backdrop-blur-md z-[550] lg:hidden"></div>
        </Transition>

        <!-- =========================================================
             SIDEBAR: SOVEREIGN CONTROL PILLAR
             ========================================================= -->
        <aside :class="[
            'fixed inset-y-0 left-0 w-80 bg-[#09090B] border-r border-white/5 z-[600] shadow-2xl transition-transform duration-500 ease-in-out lg:relative lg:translate-x-0',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
        ]">
            <div class="p-10 h-20 flex items-center shrink-0">
                <Link :href="route('superadmin.dashboard')" class="flex items-center gap-4 group">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 shadow-2xl shadow-indigo-900/40 group-hover:rotate-6 transition-all duration-500">
                        <ApplicationLogo class="h-6 w-auto text-white" />
                    </div>
                    <div class="text-left">
                        <span
                            class="text-xl font-black tracking-tighter text-white block leading-none uppercase">Sovereign<span
                                class="text-indigo-500">.</span></span>
                        <span
                            class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500 mt-1 block leading-none">Admin
                            Node</span>
                    </div>
                </Link>
            </div>

            <nav class="flex-1 px-6 space-y-2 mt-12 overflow-y-auto no-scrollbar">
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.5em] mb-8 px-4">Global Matrix</p>

                <Link v-for="item in navModules" :key="item.label" :href="route(item.route)"
                    class="flex items-center gap-5 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] transition-all group relative overflow-hidden"
                    :class="route().current(item.route) ? 'bg-white/5 text-white border border-white/5 shadow-inner' : 'text-slate-500 hover:bg-white/5 hover:text-white border border-transparent'">

                    <svg class="w-4.5 h-4.5 transition-colors duration-500"
                        :class="route().current(item.route) ? 'text-indigo-500 shadow-[0_0_10px_#4f46e5]' : 'text-slate-600 group-hover:text-indigo-400'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="item.icon" />
                    </svg>

                    {{ item.label }}

                    <div v-if="route().current(item.route)"
                        class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full shadow-[0_0_15px_#4f46e5]"></div>
                </Link>
            </nav>

            <div class="p-8 border-t border-white/5 shrink-0">
                <Link :href="route('logout')" method="post" as="button"
                    class="w-full py-5 bg-rose-500/5 border border-rose-500/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.4em] text-rose-500 hover:bg-rose-600 hover:text-white transition-all active:scale-95 shadow-xl">
                    Terminate Session
                </Link>
            </div>
        </aside>

        <!-- =========================================================
             MAIN VIEWPORT: DYNAMIC CANVAS
             ========================================================= -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">

            <!-- TELEMETRY HEADER -->
            <header
                class="h-20 border-b border-slate-200/60 bg-white/70 backdrop-blur-3xl flex items-center px-6 lg:px-12 justify-between shrink-0 relative z-[400]">
                <div class="flex items-center gap-6">
                    <!-- MOBILE MENU TRIGGER -->
                    <button @click="isSidebarOpen = true"
                        class="lg:hidden p-3 rounded-2xl bg-[#09090B] text-white shadow-2xl hover:bg-indigo-600 transition-all active:scale-90 border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_12px_#10b981]"></div>
                        <span
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-[#09090B] hidden sm:inline">Operational
                            Oversight System v4.2</span>
                    </div>
                </div>

                <!-- Account Node -->
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black text-[#09090B] uppercase tracking-widest leading-none">{{
                            user?.name }}</p>
                        <p class="text-[8px] font-bold text-indigo-600 uppercase tracking-widest mt-1.5">Sovereign
                            Principal</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-[#09090B] border border-white/10 flex items-center justify-center text-white font-black text-[11px] shadow-2xl shadow-indigo-900/10">
                        {{ (user?.name?.charAt(0) || 'A').toUpperCase() }}
                    </div>
                </div>
            </header>

            <!-- SCROLLABLE CONTENT CANVAS -->
            <main class="flex-1 overflow-y-auto no-scrollbar scroll-smooth p-6 lg:p-12 relative z-0">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(*) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>