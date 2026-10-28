<script setup>
/**
 * SmartBooking Enterprise SaaS Layout
 * Standardized Collapsible / Fixed Sidebar Architecture
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref, computed, onMounted, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const business = computed(() => user.value?.business || page.props.business || null);
const isAdmin = computed(() => user.value?.role === 'owner' || user.value?.role === 'super_admin');
const isSuperAdmin = computed(() => user.value?.role === 'super_admin');

const mobileMenuOpen = ref(false);
const sidebarCollapsed = ref(false);

// Toast Notification State
const toast = ref({
    show: false,
    message: '',
    type: 'success'
});

const triggerToast = (msg, type = 'success') => {
    toast.value = { show: true, message: msg, type };
    setTimeout(() => {
        toast.value.show = false;
    }, 4500);
};

// Watch Inertia flash messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) triggerToast(flash.success, 'success');
    else if (flash?.error) triggerToast(flash.error, 'error');
    else if (flash?.message) triggerToast(flash.message, 'info');
}, { deep: true, immediate: true });

router.on('finish', () => {
    mobileMenuOpen.value = false;
});

const publicBookingUrl = computed(() => {
    if (business.value?.slug) {
        return route('booking.page', business.value.slug);
    }
    return null;
});

const initials = computed(() => {
    if (!user.value?.name) return 'U';
    return user.value.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
});
</script>

<template>
    <div class="min-h-screen bg-slate-50/70 text-slate-900 flex font-sans antialiased selection:bg-brand-600 selection:text-white">

        <!-- =========================================================
             1. DESKTOP SAAS SIDEBAR (Fixed Left)
             ========================================================= -->
        <aside
            :class="[
                'hidden lg:flex flex-col fixed inset-y-0 left-0 z-40 bg-[#09090b] text-white border-r border-slate-800/80 transition-all duration-300',
                sidebarCollapsed ? 'w-20' : 'w-72'
            ]"
        >
            <!-- Sidebar Header & Workspace Info -->
            <div class="h-20 flex items-center justify-between px-5 border-b border-white/5">
                <Link :href="route('dashboard')" class="flex items-center gap-3 group overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/25 shrink-0 group-hover:scale-105 transition-transform">
                        <ApplicationLogo class="w-5 h-5 text-white" />
                    </div>
                    <div v-show="!sidebarCollapsed" class="flex flex-col overflow-hidden">
                        <span class="text-base font-bold tracking-tight text-white font-display leading-tight flex items-center gap-1.5">
                            SmartBooking
                            <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-brand-500/20 text-brand-400 rounded-md border border-brand-500/30">SaaS</span>
                        </span>
                        <span class="text-[11px] text-slate-400 truncate">
                            {{ business?.name || 'Workspace' }}
                        </span>
                    </div>
                </Link>

                <button
                    @click="sidebarCollapsed = !sidebarCollapsed"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors"
                    :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!sidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Workspace Status Capsule -->
            <div v-show="!sidebarCollapsed && isAdmin" class="px-5 pt-5 pb-2">
                <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-slate-200">Tenant Online</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">{{ user?.role }}</span>
                        </div>
                    </div>
                    <a
                        v-if="publicBookingUrl"
                        :href="publicBookingUrl"
                        target="_blank"
                        class="text-[10px] font-medium text-brand-400 hover:text-brand-300 flex items-center gap-1 bg-brand-500/10 px-2 py-1 rounded-md border border-brand-500/20 hover:bg-brand-500/20 transition-all"
                        title="Open Live Public Booking Page"
                    >
                        <span>Portal</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">
                <!-- Group: Main -->
                <div>
                    <div v-show="!sidebarCollapsed" class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Overview
                    </div>
                    <div class="space-y-1">
                        <Link
                            :href="route('dashboard')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('dashboard')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Dashboard' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span v-show="!sidebarCollapsed">{{ isAdmin ? 'Dashboard' : 'Discovery Hub' }}</span>
                        </Link>

                        <Link
                            v-if="!isAdmin"
                            :href="route('customer.dashboard')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('customer.dashboard')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'My Agenda' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">My Agenda</span>
                        </Link>

                        <Link
                            v-if="isAdmin"
                            :href="route('admin.calendar.index')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('admin.calendar.index')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Calendar' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">Calendar</span>
                        </Link>
                    </div>
                </div>

                <!-- Group: Operations (Admin Only) -->
                <div v-if="isAdmin">
                    <div v-show="!sidebarCollapsed" class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Operations
                    </div>
                    <div class="space-y-1">
                        <Link
                            :href="route('admin.bookings.index')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('admin.bookings.index')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Bookings' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span v-show="!sidebarCollapsed">Bookings & Queue</span>
                        </Link>

                        <Link
                            :href="route('admin.services.index')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('admin.services.index')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Services' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">Services & Catalog</span>
                        </Link>

                        <Link
                            :href="route('admin.availability.index')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('admin.availability.index')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Working Hours' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">Working Hours</span>
                        </Link>
                    </div>
                </div>

                <!-- Group: Management (Admin Only) -->
                <div v-if="isAdmin">
                    <div v-show="!sidebarCollapsed" class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Management
                    </div>
                    <div class="space-y-1">
                        <Link
                            :href="route('admin.staff.index')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('admin.staff.index')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Team Roster' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">Staff & Specialists</span>
                        </Link>

                        <Link
                            :href="route('admin.settings.edit')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('admin.settings.edit')
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-semibold'
                                    : 'text-slate-300 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Brand Studio' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">Business Settings</span>
                        </Link>
                    </div>
                </div>

                <!-- Group: Platform Oversight (SuperAdmin Only) -->
                <div v-if="isSuperAdmin">
                    <div v-show="!sidebarCollapsed" class="px-3 mb-2 text-[10px] font-bold text-amber-400 uppercase tracking-wider">
                        Platform
                    </div>
                    <div class="space-y-1">
                        <Link
                            :href="route('superadmin.dashboard')"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group',
                                route().current('superadmin.*')
                                    ? 'bg-amber-600 text-white shadow-md font-semibold'
                                    : 'text-amber-200/80 hover:text-white hover:bg-white/[0.06]'
                            ]"
                            :title="sidebarCollapsed ? 'Super Admin' : ''"
                        >
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span v-show="!sidebarCollapsed">System Oversight</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- Sidebar User Profile Footer -->
            <div class="p-3 border-t border-white/5">
                <div class="p-2 rounded-xl bg-white/[0.03] flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-brand-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-md">
                            {{ initials }}
                        </div>
                        <div v-show="!sidebarCollapsed" class="min-w-0 flex flex-col text-left">
                            <span class="text-xs font-semibold text-white truncate">{{ user?.name }}</span>
                            <span class="text-[11px] text-slate-400 truncate">{{ user?.email }}</span>
                        </div>
                    </div>

                    <div v-show="!sidebarCollapsed" class="flex items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <div class="p-1 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100">
                                    <DropdownLink :href="route('profile.edit')" class="rounded-lg text-xs font-medium">Profile Settings</DropdownLink>
                                    <DropdownLink v-if="isAdmin" :href="route('admin.settings.edit')" class="rounded-lg text-xs font-medium">Business Profile</DropdownLink>
                                    <div class="my-1 border-t border-slate-100"></div>
                                    <DropdownLink :href="route('logout')" method="post" as="button" class="rounded-lg text-xs font-medium !text-rose-600 hover:!bg-rose-50">Sign Out</DropdownLink>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </aside>

        <!-- =========================================================
             2. MAIN WRAPPER (Dynamic Margin for Desktop Sidebar)
             ========================================================= -->
        <div :class="['flex-1 flex flex-col min-h-screen transition-all duration-300', sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72']">

            <!-- Sticky Top App Bar -->
            <header class="sticky top-0 z-30 h-16 bg-white/80 backdrop-blur-md border-b border-slate-200/70 px-4 sm:px-6 lg:px-8 flex items-center justify-between shadow-xs">
                <!-- Left: Mobile Menu Button & Breadcrumbs -->
                <div class="flex items-center gap-3">
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                        aria-label="Toggle Navigation"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <Link :href="route('dashboard')" class="hover:text-slate-900 transition-colors">Workspace</Link>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-slate-900 font-semibold truncate capitalize">
                            {{ route().current()?.split('.').pop() || 'Overview' }}
                        </span>
                    </div>
                </div>

                <!-- Right: Quick Actions & Live Public Link -->
                <div class="flex items-center gap-3">
                    <a
                        v-if="publicBookingUrl"
                        :href="publicBookingUrl"
                        target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-xs transition-all"
                    >
                        <span>Public Booking Page</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    <!-- User Top Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2.5 p-1 pl-2 rounded-full border border-slate-200 hover:border-slate-300 bg-white transition-all">
                                <span class="text-xs font-semibold text-slate-700 hidden sm:inline">{{ user?.name?.split(' ')[0] }}</span>
                                <div class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">
                                    {{ initials }}
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <div class="p-2 bg-white rounded-xl shadow-xl border border-slate-100 text-left min-w-[200px]">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-semibold text-slate-900 truncate">{{ user?.name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ user?.email }}</p>
                                </div>
                                <DropdownLink :href="route('profile.edit')" class="rounded-lg text-xs">Profile Settings</DropdownLink>
                                <DropdownLink v-if="isAdmin" :href="route('admin.settings.edit')" class="rounded-lg text-xs">Business Settings</DropdownLink>
                                <div class="my-1 border-t border-slate-100"></div>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="rounded-lg text-xs !text-rose-600 hover:!bg-rose-50">Sign Out</DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page Header Slot (If present) -->
            <header v-if="$slots.header" class="bg-white border-b border-slate-200/60 px-6 py-6 sm:px-8">
                <slot name="header" />
            </header>

            <!-- Main Workspace Canvas -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <slot />
            </main>

            <!-- Minimal SaaS Footer -->
            <footer class="mt-auto py-5 px-6 border-t border-slate-200/60 text-center text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3 max-w-7xl w-full mx-auto">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>SmartBooking Platform &bull; Tenant Isolation Active</span>
                </div>
                <span>&copy; {{ new Date().getFullYear() }} SmartBooking SaaS. All rights reserved.</span>
            </footer>
        </div>

        <!-- =========================================================
             3. MOBILE SLIDE-OVER DRAWER
             ========================================================= -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="mobileMenuOpen"
                @click="mobileMenuOpen = false"
                class="lg:hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm"
            ></div>
        </Transition>

        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div
                v-show="mobileMenuOpen"
                class="lg:hidden fixed inset-y-0 left-0 z-50 w-72 bg-[#09090b] text-white p-5 flex flex-col shadow-2xl border-r border-slate-800"
            >
                <div class="flex items-center justify-between pb-5 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white">
                            <ApplicationLogo class="w-5 h-5 text-white" />
                        </div>
                        <span class="text-base font-bold font-display text-white">SmartBooking</span>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 py-6 space-y-2 overflow-y-auto">
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                        :class="route().current('dashboard') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                    >
                        <span>Dashboard</span>
                    </Link>

                    <Link
                        v-if="!isAdmin"
                        :href="route('customer.dashboard')"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                        :class="route().current('customer.dashboard') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                    >
                        <span>My Agenda</span>
                    </Link>

                    <template v-if="isAdmin">
                        <Link
                            :href="route('admin.calendar.index')"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                            :class="route().current('admin.calendar.index') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                        >
                            <span>Calendar Timeline</span>
                        </Link>
                        <Link
                            :href="route('admin.bookings.index')"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                            :class="route().current('admin.bookings.index') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                        >
                            <span>Bookings Queue</span>
                        </Link>
                        <Link
                            :href="route('admin.services.index')"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                            :class="route().current('admin.services.index') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                        >
                            <span>Services & Catalog</span>
                        </Link>
                        <Link
                            :href="route('admin.staff.index')"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                            :class="route().current('admin.staff.index') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                        >
                            <span>Staff Team</span>
                        </Link>
                        <Link
                            :href="route('admin.availability.index')"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                            :class="route().current('admin.availability.index') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                        >
                            <span>Working Hours</span>
                        </Link>
                        <Link
                            :href="route('admin.settings.edit')"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium hover:bg-white/5 transition-colors"
                            :class="route().current('admin.settings.edit') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-300'"
                        >
                            <span>Business Settings</span>
                        </Link>
                    </template>

                    <div class="pt-4 mt-4 border-t border-white/10 space-y-1">
                        <Link :href="route('profile.edit')" class="block px-3.5 py-2 text-sm text-slate-300 hover:text-white">Profile Settings</Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-3.5 py-2 text-sm text-rose-400 hover:text-rose-300">Sign Out</Link>
                    </div>
                </nav>
            </div>
        </Transition>

        <!-- =========================================================
             4. FLOATING TOAST NOTIFICATION
             ========================================================= -->
        <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="toast.show"
                class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-slate-900 text-white p-4 rounded-2xl shadow-2xl border border-slate-700/60 flex items-start gap-3"
            >
                <div
                    :class="[
                        'w-8 h-8 rounded-lg flex items-center justify-center shrink-0 text-sm font-bold',
                        toast.type === 'error' ? 'bg-rose-500/20 text-rose-400' : 'bg-emerald-500/20 text-emerald-400'
                    ]"
                >
                    <svg v-if="toast.type !== 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-xs font-semibold text-white">{{ toast.type === 'error' ? 'Notice' : 'Success' }}</p>
                    <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">{{ toast.message }}</p>
                </div>
                <button @click="toast.show = false" class="text-slate-400 hover:text-white p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </Transition>

    </div>
</template>