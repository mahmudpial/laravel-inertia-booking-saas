<script setup>
import { ref, computed, watch, onUnmounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const isSidebarOpen = ref(false);

const navModules = [
    { label: 'Global Oversight', route: 'superadmin.dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { label: 'Entity Manager', route: 'superadmin.entities.index', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { label: 'Financials & Yield', route: 'superadmin.financials', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'Security Audit Logs', route: 'superadmin.audit', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { label: 'System Runtime Config', route: 'superadmin.config', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
];

router.on('finish', () => {
    isSidebarOpen.value = false;
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
    <div class="min-h-screen flex bg-slate-50 font-sans antialiased text-slate-900 selection:bg-brand-600 selection:text-white">
        <!-- Blueprint Dot Grid Background -->
        <div class="fixed inset-0 z-0 pointer-events-none bg-dot-grid opacity-60"></div>

        <!-- Mobile Drawer Backdrop -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isSidebarOpen"
                @click="isSidebarOpen = false"
                class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden"
            ></div>
        </Transition>

        <!-- Sidebar Navigation -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 w-72 bg-slate-950 text-white border-r border-slate-900 z-50 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div>
                <!-- Brand Anchor -->
                <div class="h-16 px-6 flex items-center justify-between border-b border-slate-900">
                    <Link :href="route('superadmin.dashboard')" class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-500 to-brand-600 flex items-center justify-center text-white shadow-md shadow-amber-500/20 group-hover:rotate-6 transition-all">
                            <ApplicationLogo class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <span class="font-display font-bold text-base tracking-tight text-white block">
                                Sovereign<span class="text-amber-400">.</span>
                            </span>
                            <span class="text-[9px] font-semibold uppercase tracking-wider text-amber-400/80 block">SuperAdmin Root</span>
                        </div>
                    </Link>

                    <button
                        @click="isSidebarOpen = false"
                        class="p-1 rounded-lg text-slate-400 hover:text-white lg:hidden"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Nav Menu -->
                <nav class="p-4 space-y-1 mt-3">
                    <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        Platform Grid Controls
                    </div>

                    <Link
                        v-for="item in navModules"
                        :key="item.label"
                        :href="route(item.route)"
                        :class="[
                            'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all group',
                            route().current(item.route)
                                ? 'bg-amber-500/10 text-amber-300 border border-amber-500/30 shadow-xs'
                                : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent'
                        ]"
                    >
                        <svg
                            class="w-4 h-4 shrink-0 transition-colors"
                            :class="route().current(item.route) ? 'text-amber-400' : 'text-slate-500 group-hover:text-amber-400'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                        </svg>
                        <span>{{ item.label }}</span>
                    </Link>
                </nav>
            </div>

            <!-- Bottom User & Logout Dock -->
            <div class="p-4 border-t border-slate-900 space-y-3">
                <div class="flex items-center gap-3 px-2 py-1">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-500/30 text-amber-400 flex items-center justify-center font-bold text-xs">
                        {{ (user?.name?.charAt(0) || 'A').toUpperCase() }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-xs font-semibold text-white truncate">{{ user?.name }}</div>
                        <div class="text-[10px] text-amber-400/80 truncate">Super Admin Principal</div>
                    </div>
                </div>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full py-2.5 px-3 rounded-xl bg-white/5 hover:bg-rose-500/10 hover:text-rose-400 border border-white/5 hover:border-rose-500/20 text-slate-400 text-xs font-semibold transition-all flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Sign Out</span>
                </Link>
            </div>
        </aside>

        <!-- Main Viewport Canvas -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden relative">
            <!-- Top Header -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between shrink-0 relative z-30">
                <div class="flex items-center gap-4">
                    <button
                        @click="isSidebarOpen = true"
                        class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    </button>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Root Sovereign System
                        </span>
                        <span class="text-slate-300 hidden sm:inline">•</span>
                        <span class="text-xs text-slate-500 hidden sm:inline">Cross-Tenant Global Oversight</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        href="/"
                        class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Public Portal</span>
                    </Link>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8 lg:p-10 relative z-10">
                <slot />
            </main>
        </div>
    </div>
</template>