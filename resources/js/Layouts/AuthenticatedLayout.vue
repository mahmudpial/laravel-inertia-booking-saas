<script setup>
import { ref, watch, onUnmounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

// FIX 1: Prevent body scroll when menu is open
watch(showingNavigationDropdown, (val) => {
    if (val) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

// FIX 2: Close menu and reset scroll when navigation happens
router.on('finish', () => {
    showingNavigationDropdown.value = false;
    document.body.style.overflow = '';
});

// FIX 3: Global cleanup if component unmounts
onUnmounted(() => {
    document.body.style.overflow = '';
});
</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#F8FAFC] selection:bg-indigo-500 selection:text-white">
        <!-- =========================================================
             ULTRA-PREMIUM OBSIDIAN NAVIGATION
             ========================================================= -->
        <nav class="sticky top-0 z-[110] bg-[#11131A] border-b border-white/5 shadow-2xl h-20 flex items-center">
            <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-8">

                    <!-- Brand Identity -->
                    <div class="flex items-center gap-2">
                        <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-[1.25rem] bg-indigo-600 shadow-xl shadow-indigo-900/40 group-hover:rotate-6 transition-all duration-500">
                                <ApplicationLogo class="h-6 w-auto text-white" />
                            </div>
                            <div class="hidden sm:block">
                                <span class="text-xl font-black tracking-tighter text-white block leading-none">
                                    Smart<span class="text-indigo-400">Booking.</span>
                                </span>
                                <span
                                    class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500 mt-1 block">Operational
                                    Intelligence</span>
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex items-stretch gap-1 h-20">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="!text-slate-400 hover:!text-white">Dashboard</NavLink>
                        <NavLink :href="route('admin.bookings.index')" :active="route().current('admin.bookings.index')"
                            class="!text-slate-400 hover:!text-white">Bookings</NavLink>
                        <NavLink :href="route('admin.availability.index')"
                            :active="route().current('admin.availability.index')"
                            class="!text-slate-400 hover:!text-white">Operations</NavLink>
                        <NavLink :href="route('admin.services.index')" :active="route().current('admin.services.index')"
                            class="!text-slate-400 hover:!text-white">Inventory</NavLink>
                    </div>

                    <!-- Right Section: User Hub -->
                    <div class="flex items-center gap-4">
                        <!-- Desktop User Dropdown -->
                        <div class="hidden md:block">
                            <Dropdown align="right" width="64">
                                <template #trigger>
                                    <button
                                        class="flex items-center gap-3 p-1.5 pr-5 rounded-[1.25rem] border border-white/10 bg-white/5 hover:bg-white/10 hover:border-indigo-500/50 transition-all duration-300 focus:outline-none group">
                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white text-[11px] font-black shadow-lg shadow-indigo-900/20">
                                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="hidden lg:block text-left">
                                            <span
                                                class="text-[10px] font-black text-white uppercase tracking-widest block leading-none">{{
                                                    $page.props.auth.user.name.split(' ')[0] }}</span>
                                            <span
                                                class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mt-1 block">Authorized
                                                Admin</span>
                                        </div>
                                        <svg class="h-4 w-4 text-slate-500 group-hover:text-white transition-colors"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="p-3 bg-white space-y-1">
                                        <div class="px-4 py-4 border-b border-slate-50 mb-2 bg-slate-50/50 rounded-2xl">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">
                                                Identity Hub</p>
                                            <p class="text-xs font-black text-[#11131A] truncate mt-1">{{
                                                $page.props.auth.user.email }}</p>
                                        </div>
                                        <DropdownLink :href="route('profile.edit')">Account identity</DropdownLink>
                                        <DropdownLink :href="route('admin.settings.edit')">Brand Studio</DropdownLink>
                                        <div class="my-2 border-t border-slate-50"></div>
                                        <DropdownLink :href="route('logout')" method="post" as="button"
                                            class="!text-rose-600">
                                            Disconnect Session
                                        </DropdownLink>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Unified Mobile Menu Toggle -->
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="md:hidden p-3 rounded-2xl bg-indigo-600 text-white shadow-xl shadow-indigo-900/40 active:scale-90 transition-all border border-white/10 relative z-[120]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="!showingNavigationDropdown" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="3" d="M4 6h16M4 12h16m-7 6h7" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- MOBILE UNIFIED NAVIGATION DRAWER -->
            <Transition enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-x-full" enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 translate-x-full">
                <div v-show="showingNavigationDropdown"
                    class="md:hidden fixed inset-0 top-20 bg-[#11131A] z-[100] overflow-y-auto px-6 py-12 flex flex-col no-scrollbar">

                    <!-- App Links Section -->
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mb-6 px-4">Core Modules
                    </p>
                    <div class="space-y-3">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('admin.bookings.index')"
                            :active="route().current('admin.bookings.index')">
                            Bookings</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('admin.availability.index')"
                            :active="route().current('admin.availability.index')">Operations</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('admin.services.index')"
                            :active="route().current('admin.services.index')">
                            Inventory</ResponsiveNavLink>
                    </div>

                    <!-- Identity Section (Integrated here for Mobile) -->
                    <div class="pt-10 mt-10 border-t border-white/5">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mb-6 px-4">Account
                            Control</p>

                        <div class="px-6 py-6 mb-6 bg-white/5 rounded-[2rem] border border-white/5">
                            <p class="text-[8px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-1">Active
                                Account</p>
                            <p class="text-sm font-black text-white truncate">{{ $page.props.auth.user.email }}</p>
                        </div>

                        <div class="space-y-3">
                            <ResponsiveNavLink :href="route('profile.edit')">Account settings</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.settings.edit')">Brand Studio</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                                class="!text-rose-500 mt-6 bg-rose-500/5 !border-rose-500/20">
                                Terminate Session
                            </ResponsiveNavLink>
                        </div>
                    </div>

                    <div class="mt-auto pt-12 text-center">
                        <p class="text-[8px] font-black text-slate-700 uppercase tracking-[0.5em]">Engine Build
                            v4.2.0-Alpha</p>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- =========================================================
             MAIN SYSTEM WORKSPACE
             ========================================================= -->
        <main class="flex-1 relative z-10">
            <slot />
        </main>

        <!-- =========================================================
             PREMIUM ADMIN FOOTER (SYMMETRICAL & SLIM)
             ========================================================= -->
        <footer class="bg-[#11131A] text-white pt-12 pb-8 border-t border-white/5 relative overflow-hidden">
            <!-- Subtle Top Glow -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-px bg-gradient-to-r from-transparent via-indigo-500/20 to-transparent">
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-12 mb-12">

                    <!-- BRAND IDENTITY (Navbar Sync) -->
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-[1.25rem] bg-indigo-600 shadow-2xl shadow-indigo-900/40">
                            <ApplicationLogo class="h-6 w-auto text-white" />
                        </div>
                        <div class="text-left">
                            <span class="text-2xl font-black tracking-tighter text-white block leading-none">Smart<span
                                    class="text-indigo-500">Booking.</span></span>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">Engine
                                    v4.2.0</span>
                                <div class="w-1 h-1 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]">
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">Systems
                                    Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- SYSTEM QUICK NAV -->
                    <nav class="flex flex-wrap justify-center items-center gap-x-8 gap-y-3">
                        <Link :href="route('dashboard')"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">
                            Hub</Link>
                        <Link :href="route('admin.bookings.index')"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">
                            Queue</Link>
                        <Link :href="route('admin.services.index')"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">
                            SKUs</Link>
                        <Link :href="route('profile.edit')"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-white transition-all">
                            Security</Link>
                    </nav>

                    <!-- COMPACT ARCHITECT CAPSULE -->
                    <div class="relative group">
                        <div
                            class="flex items-center gap-4 p-2 pr-5 bg-white/5 border border-white/5 rounded-[1.5rem] backdrop-blur-md group-hover:border-indigo-500/40 transition-all duration-500 shadow-2xl">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#11131A] border border-white/10 flex items-center justify-center text-indigo-500 font-black text-[10px] shadow-inner">
                                PM</div>
                            <div class="text-left border-r border-white/10 pr-4">
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.4em]">Architect</p>
                                <a href="https://linkedin.com/in/pial-mahmud" target="_blank"
                                    class="text-xs font-black text-white hover:text-indigo-400 transition-colors uppercase tracking-widest leading-none">Pial
                                    Mahmud</a>
                            </div>
                            <!-- Social Connect -->
                            <div class="flex gap-2">
                                <a href="https://github.com" target="_blank"
                                    class="text-slate-500 hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.182-1.304.412-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg>
                                </a>
                                <a href="https://upwork.com" target="_blank"
                                    class="text-slate-500 hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.586 6.586c-2.072 0-3.834 1.343-4.664 3.208-.504-.73-.934-1.564-1.266-2.484H9.014v5.334c0 1.542-1.258 2.8-2.8 2.8s-2.8-1.258-2.8-2.8V7.31H.614v5.334c0 3.088 2.512 5.6 5.6 5.6s5.6-2.512 5.6-5.6V11.23c.27.534.618 1.056 1.036 1.546l-1.928 6.534h2.868l1.328-4.516c.828.618 1.83.99 2.92.99 2.756 0 5-2.244 5-5s-2.244-5-5-5zm0 7.2c-1.214 0-2.2-.986-2.2-2.2s.986-2.2 2.2-2.2 2.2.986 2.2 2.2-.986 2.2-2.2 2.2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COPYRIGHT STRIP -->
                <div
                    class="pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 px-4">
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.6em]">&copy; {{ new
                        Date().getFullYear() }} SmartBooking Infrastructure. All Rights Reserved.</p>
                    <div class="flex items-center gap-8">
                        <a href="#"
                            class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 hover:text-indigo-400 transition-all">Support
                            Console</a>
                        <div class="w-1 h-1 rounded-full bg-slate-800"></div>
                        <a href="#"
                            class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 hover:text-indigo-400 transition-all">SLA
                            Protocol</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(nav),
:deep(footer),
:deep(span),
:deep(p),
:deep(a),
:deep(button) {
    font-family: 'Space Grotesk', sans-serif !important;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>