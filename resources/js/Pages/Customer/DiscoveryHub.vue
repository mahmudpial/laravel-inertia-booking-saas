<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    businesses: {
        type: Array,
        default: () => []
    },
    my_upcoming: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');

const filteredBusinesses = computed(() => {
    if (!searchQuery.value) return props.businesses;
    const query = searchQuery.value.toLowerCase();
    return props.businesses.filter(b => 
        b.name?.toLowerCase().includes(query) ||
        b.description?.toLowerCase().includes(query) ||
        b.address?.toLowerCase().includes(query)
    );
});
</script>

<template>
    <Head title="Explore Studios & Specialists — SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-8 max-w-6xl mx-auto pb-16">
            <!-- Hero Header -->
            <div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 sm:p-12 text-white overflow-hidden shadow-xl">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Verified Studio Directory
                    </span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold tracking-tight leading-tight">
                        Discover & book top verified studios.
                    </h1>
                    <p class="text-slate-300 text-sm sm:text-base mt-3 leading-relaxed">
                        Find top-rated hair stylists, wellness spas, massage therapists, personal trainers, and consultants. Real-time availability with instant confirmation.
                    </p>

                    <!-- Search Bar -->
                    <div class="mt-6 flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-1.5 max-w-md focus-within:border-brand-400 focus-within:bg-white/15 transition-all">
                        <div class="pl-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search studios, treatments, or locations..."
                            class="w-full bg-transparent border-none text-sm text-white placeholder:text-slate-400 focus:ring-0 px-3 py-2"
                        />
                        <button
                            v-if="searchQuery"
                            type="button"
                            @click="searchQuery = ''"
                            class="pr-3 text-slate-400 hover:text-white text-xs font-semibold"
                        >
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Layout: 2 Columns (Grid + Upcoming Sidebar) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Studio Directory Cards (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-display font-bold text-xl text-slate-900">Featured Workspaces</h2>
                            <p class="text-xs text-slate-500 mt-0.5">{{ filteredBusinesses.length }} studios available for booking</p>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div v-if="filteredBusinesses.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div
                            v-for="shop in filteredBusinesses"
                            :key="shop.slug"
                            class="bg-white border border-slate-200/80 hover:border-brand-300 rounded-2xl p-6 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between group"
                        >
                            <div>
                                <div class="flex items-start justify-between mb-4">
                                    <!-- Studio Monogram / Avatar -->
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-display font-bold text-2xl shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform overflow-hidden">
                                        <img v-if="shop.logo" :src="shop.logo" class="w-full h-full object-cover" />
                                        <span v-else>{{ shop.name.charAt(0).toUpperCase() }}</span>
                                    </div>

                                    <!-- Status Pill -->
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Instant Booking
                                    </span>
                                </div>

                                <h3 class="font-display font-bold text-lg text-slate-900 group-hover:text-brand-600 transition-colors">
                                    {{ shop.name }}
                                </h3>

                                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                    {{ shop.description || 'Dedicated specialist studio offering custom booking appointments with real-time slot selection.' }}
                                </p>
                            </div>

                            <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs text-slate-400 truncate max-w-[150px]">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span class="truncate">{{ shop.address || 'Verified Studio' }}</span>
                                </div>

                                <Link
                                    :href="`/b/${shop.slug}`"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-semibold shadow-xs transition-all active:scale-95"
                                >
                                    <span>Book Now</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div v-else class="text-center py-16 bg-white border border-slate-200/80 rounded-2xl p-8">
                        <p class="text-sm font-semibold text-slate-700">No studios match your search</p>
                        <p class="text-xs text-slate-400 mt-1">Try searching with a different term or keyword.</p>
                        <button
                            type="button"
                            @click="searchQuery = ''"
                            class="mt-4 text-xs font-semibold text-brand-600 hover:underline"
                        >
                            Reset search
                        </button>
                    </div>
                </div>

                <!-- Right: Quick Upcoming & Helpful Info (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Upcoming Card Widget -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display font-bold text-sm text-slate-900">Next Upcoming</h3>
                            <Link :href="route('customer.dashboard')" class="text-xs font-semibold text-brand-600 hover:underline">
                                View all
                            </Link>
                        </div>

                        <div v-if="my_upcoming && my_upcoming.length > 0" class="space-y-3">
                            <div
                                v-for="booking in my_upcoming"
                                :key="booking.id"
                                class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between"
                            >
                                <div class="min-w-0 pr-2">
                                    <h4 class="text-xs font-bold text-slate-900 truncate">{{ booking.service?.name || 'Appointment' }}</h4>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ booking.business?.name }}</p>
                                    <p class="text-[10px] text-brand-600 font-semibold mt-1">{{ booking.booking_date }} at {{ booking.start_time }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                    {{ booking.status }}
                                </span>
                            </div>
                        </div>

                        <div v-else class="text-center py-8">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-xs text-slate-500">No active upcoming reservations</p>
                            <p class="text-[11px] text-slate-400 mt-1">Book your first session from the directory.</p>
                        </div>
                    </div>

                    <!-- Trust / Feature Card -->
                    <div class="bg-gradient-to-br from-brand-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="font-display font-bold text-base text-white">Guaranteed Appointments</h4>
                        <p class="text-xs text-white/80 mt-1 leading-relaxed">
                            All studios on our platform use automated real-time calendar syncing. No double-bookings, no waiting for phone confirmations.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>