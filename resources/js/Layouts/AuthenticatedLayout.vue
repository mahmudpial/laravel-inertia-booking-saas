<script setup>
/**
 * SmartBooking Authenticated Layout Framework v4.2.0-Final_Sync
 * 
 * FIXED: Vite Parsing Error using Template Literals (Backticks)
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref, watch, onUnmounted, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router, usePage } from '@inertiajs/vue3';

// --- INITIALIZATION ---
const showingNavigationDropdown = ref(false);
const user = computed(() => usePage().props.auth.user);

// --- IDENTITY & SECURITY LOGIC ---
const isAdmin = computed(() => user.value?.role === 'owner' || user.value?.role === 'super_admin');
const isSecure = computed(() => !!user.value?.email_verified_at);

// --- NAVIGATION PROTOCOLS ---
router.on('finish', () => {
    showingNavigationDropdown.value = false;
    if (typeof document !== 'undefined') document.body.style.overflow = '';
});

watch(showingNavigationDropdown, (val) => {
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
        class="min-h-screen flex flex-col bg-[#FDFDFF] selection:bg-indigo-600 selection:text-white font-sans antialiased">

        <!-- BLUEPRINT BACKGROUND SYSTEM -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
            </div>
        </div>

        <!-- =========================================================
             NAVIGATION: OBSIDIAN COMMAND BAR
             ========================================================= -->
        <nav class="sticky top-0 z-[120] bg-[#09090B] border-b border-white/5 shadow-2xl h-20 flex items-center">
            <div class="mx-auto max-w-7xl w-full px-6">
                <div class="flex items-center justify-between gap-8">

                    <!-- Brand Identity Hub -->
                    <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 shadow-2xl shadow-indigo-900/40 group-hover:rotate-6 transition-all duration-500">
                            <ApplicationLogo class="h-6 w-auto text-white" />
                        </div>
                        <div class="hidden sm:block text-left">
                            <span
                                class="text-xl font-black tracking-tighter text-white block leading-none uppercase">SmartBooking<span
                                    class="text-indigo-500">.</span></span>
                            <span
                                class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500 mt-1 block leading-none">Protocol
                                Suite v4.2.0</span>
                        </div>
                    </Link>

                    <!-- DYNAMIC TACTICAL NAVIGATION (Desktop) -->
                    <div class="hidden xl:flex items-stretch gap-1 h-20">
                        <!-- FIXED: Using Backticks to handle potential multi-line formatting -->
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            {{ isAdmin ? `Command Hub` : `Marketplace` }}
                        </NavLink>

                        <NavLink v-if="!isAdmin" :href="route('customer.dashboard')"
                            :active="route().current('customer.dashboard')">Agenda</NavLink>

                        <template v-if="isAdmin">
                            <NavLink :href="route('admin.bookings.index')"
                                :active="route().current('admin.bookings.index')">Queue</NavLink>
                            <NavLink :href="route('admin.calendar.index')"
                                :active="route().current('admin.calendar.index')">Timeline</NavLink>
                            <NavLink :href="route('admin.availability.index')"
                                :active="route().current('admin.availability.index')">Operations</NavLink>
                            <NavLink :href="route('admin.staff.index')" :active="route().current('admin.staff.index')">
                                Roster</NavLink>
                            <NavLink :href="route('admin.services.index')"
                                :active="route().current('admin.services.index')">Inventory</NavLink>
                        </template>
                    </div>

                    <!-- Right Section: Identity Node -->
                    <div class="flex items-center gap-4">
                        <div class="hidden md:block">
                            <Dropdown align="right" width="64">
                                <template #trigger>
                                    <button
                                        class="flex items-center gap-3 p-1 pr-5 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-300 group focus:outline-none">
                                        <div class="relative">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white text-[11px] font-black shadow-lg">
                                                {{ (user?.name?.charAt(0) || 'A').toUpperCase() }}
                                            </div>
                                            <div :class="isSecure ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-amber-500 animate-pulse'"
                                                class="absolute -bottom-1 -right-1 w-2.5 h-2.5 rounded-full border-2 border-[#09090B]">
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-black text-white uppercase tracking-widest">{{
                                            user?.name?.split(' ')[0] }}</span>
                                        <svg class="h-4 w-4 text-slate-500 group-hover:text-white transition-colors"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </button>
                                </template>
                                <template #content>
                                    <div class="p-3 bg-white space-y-1">
                                        <div
                                            class="px-4 py-4 border-b border-slate-50 mb-2 bg-slate-50/50 rounded-2xl text-left">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">
                                                Authorized Session</p>
                                            <p class="text-xs font-black text-[#11131A] truncate mt-1">{{ user?.email }}
                                            </p>
                                        </div>
                                        <DropdownLink :href="route('profile.edit')">Personal Identity</DropdownLink>
                                        <DropdownLink v-if="isAdmin" :href="route('admin.settings.edit')">Brand Studio
                                        </DropdownLink>
                                        <div class="my-2 border-t border-slate-50"></div>
                                        <DropdownLink :href="route('logout')" method="post" as="button"
                                            class="!text-rose-600 hover:!bg-rose-50">Disconnect Session</DropdownLink>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="md:hidden p-3 rounded-2xl bg-indigo-600 text-white shadow-xl relative z-[130] active:scale-90 transition-transform border border-white/10">
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
            <Transition enter-active-class="transition duration-400 ease-out"
                enter-from-class="opacity-0 translate-x-full" enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 translate-x-full">
                <div v-show="showingNavigationDropdown"
                    class="md:hidden fixed inset-0 top-20 bg-[#09090B] z-[100] px-6 py-12 flex flex-col overflow-y-auto no-scrollbar">
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mb-6 px-4">System Grid
                    </p>
                    <div class="space-y-2 text-left">
                        <!-- FIXED: Using Backticks for mobile labels -->
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            {{ isAdmin ? `Command Hub` : `Marketplace Hub` }}
                        </ResponsiveNavLink>

                        <ResponsiveNavLink v-if="!isAdmin" :href="route('customer.dashboard')"
                            :active="route().current('customer.dashboard')">My Agenda</ResponsiveNavLink>

                        <template v-if="isAdmin">
                            <ResponsiveNavLink :href="route('admin.bookings.index')"
                                :active="route().current('admin.bookings.index')">Queue</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.calendar.index')"
                                :active="route().current('admin.calendar.index')">Timeline</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.staff.index')"
                                :active="route().current('admin.staff.index')">Roster</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.services.index')"
                                :active="route().current('admin.services.index')">Inventory</ResponsiveNavLink>
                        </template>

                        <div class="pt-10 mt-6 border-t border-white/5 space-y-4 text-left">
                            <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mb-4 px-4">
                                Account Access</p>
                            <ResponsiveNavLink :href="route('profile.edit')">Identity Config</ResponsiveNavLink>
                            <ResponsiveNavLink v-if="isAdmin" :href="route('admin.settings.edit')">Brand Studio
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                                class="!text-rose-500 mt-6 pt-6 border-t border-white/5">Sign Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </Transition>
        </nav>

        <main class="flex-1 relative z-10">
            <slot />
        </main>

        <!-- FOOTER: SIGNATURE ARCHITECTURE (PRO-SLIM) -->
        <footer
            class="bg-[#09090B] text-white pt-16 pb-8 border-t border-white/5 relative overflow-hidden mt-auto z-10 text-left">
            <div class="mx-auto max-w-7xl px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-12 mb-12">

                    <!-- Brand Module (Synced) -->
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 shadow-2xl">
                            <ApplicationLogo class="h-6 w-auto text-white" />
                        </div>
                        <div class="text-left">
                            <span
                                class="text-2xl font-black tracking-tighter text-white block leading-none uppercase">SmartBooking<span
                                    class="text-indigo-500">.</span></span>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">Node
                                    Secure
                                    Infrastructure</span>
                                <div :class="isSecure ? 'bg-emerald-500 shadow-[0_0_10px_#10b981]' : 'bg-amber-500'"
                                    class="w-1.5 h-1.5 rounded-full"></div>
                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">{{
                                    isSecure ?
                                        `Synchronized` : `Handshake Pending` }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Map -->
                    <nav class="flex flex-wrap justify-center items-center gap-x-8 gap-y-3">
                        <Link :href="route('dashboard')"
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-white transition-all">
                            {{ isAdmin ? `Hub` : `Marketplace` }}</Link>
                        <Link v-if="!isAdmin" :href="route('customer.dashboard')"
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-white transition-all">
                            Agenda</Link>
                        <Link v-if="isAdmin" :href="route('admin.calendar.index')"
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-white transition-all">
                            Timeline</Link>
                        <Link :href="route('profile.edit')"
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-white transition-all">
                            Security</Link>
                    </nav>

                    <!-- Architect Identification Capsule -->
                    <div class="relative group">
                        <div
                            class="flex items-center gap-4 p-2 pr-5 bg-white/5 border border-white/5 rounded-3xl backdrop-blur-md transition-all duration-500 hover:border-indigo-500/40 shadow-2xl">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-[10px]">
                                PM</div>
                            <div class="text-left border-r border-white/10 pr-4">
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.4em]">Chief
                                    Architect</p>
                                <a href="https://linkedin.com/in/pial-mahmud" target="_blank"
                                    class="text-xs font-black text-white hover:text-indigo-400 transition-colors uppercase tracking-widest leading-none">Pial
                                    Mahmud</a>
                            </div>
                            <div class="flex gap-4">
                                <a href="https://github.com" target="_blank"
                                    class="text-slate-400 hover:text-white transition-all scale-90 hover:scale-110"><svg
                                        class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.182-1.304.412-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Legal Compliance -->
                <div class="pt-8 border-t border-white/5 text-center">
                    <p class="text-[9px] font-black text-slate-700 uppercase tracking-[1.5em] leading-loose">
                        INFRASTRUCTURE CORE
                        v4.2.0 | ALL DATA AES-256 ENCRYPTED</p>
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
:deep(button),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4) {
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
</style>