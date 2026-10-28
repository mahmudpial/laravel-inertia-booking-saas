<script setup>
/**
 * SmartBooking Business Command Center
 * Modern SaaS Dashboard Architecture
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS, Title, Tooltip, Legend, LineElement,
    CategoryScale, LinearScale, PointElement, Filler
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

const props = defineProps({
    stats: Object,
    business: Object,
    chartData: Object,
    recentBookings: Array
});

const copied = ref(false);

const bookingUrl = computed(() => {
    if (typeof window !== 'undefined' && props.business?.slug) {
        return `${window.location.origin}/b/${props.business.slug}`;
    }
    return '';
});

const copyLink = async () => {
    if (!bookingUrl.value) return;
    try {
        await navigator.clipboard.writeText(bookingUrl.value);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 3000);
    } catch (e) {
        console.error('Failed to copy', e);
    }
};

// 7-Day Chart Data
const lineChartData = computed(() => ({
    labels: props.chartData?.labels?.length ? props.chartData.labels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [{
        label: 'Bookings',
        data: props.chartData?.bookings?.length ? props.chartData.bookings : [0, 0, 0, 0, 0, 0, 0],
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.08)',
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#ffffff',
        pointBorderColor: '#4f46e5',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        borderWidth: 2.5
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { family: 'Plus Jakarta Sans', size: 12 },
            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
            padding: 10,
            cornerRadius: 8,
            displayColors: false,
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } },
            grid: { color: 'rgba(226, 232, 240, 0.6)' }
        },
        x: {
            grid: { display: false },
            ticks: { color: '#94a3b8', font: { size: 11 } }
        }
    }
};

const getStatusBadge = (status) => {
    const s = (status || '').toLowerCase();
    if (s === 'confirmed') return 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
    if (s === 'pending') return 'bg-amber-50 text-amber-700 border-amber-200/60';
    if (s === 'completed') return 'bg-indigo-50 text-indigo-700 border-indigo-200/60';
    return 'bg-slate-50 text-slate-600 border-slate-200';
};
</script>

<template>
    <Head title="Business Dashboard | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-8">

            <!-- =========================================================
                 1. TOP GREETING & HERO HEADER
                 ========================================================= -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            Live System
                        </span>
                        <span class="text-xs text-slate-400 font-medium">&bull;</span>
                        <span class="text-xs text-slate-500 font-medium">{{ business?.name }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Business Overview
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Monitor your appointments, client bookings, and revenue metrics in real time.
                    </p>
                </div>

                <!-- Action Toolbar -->
                <div class="flex flex-wrap items-center gap-3">
                    <Link
                        :href="route('admin.calendar.index')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-xs transition-all active:scale-[0.98]"
                    >
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Open Calendar</span>
                    </Link>

                    <Link
                        :href="route('admin.services.index')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm shadow-brand-500/25 transition-all active:scale-[0.98]"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Manage Services</span>
                    </Link>
                </div>
            </div>

            <!-- =========================================================
                 2. CLIENT BOOKING LINK SHARING WIDGET
                 ========================================================= -->
            <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-300">Public Booking Gateway</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30">Shareable</span>
                        </div>
                        <p class="text-sm font-medium text-slate-300">
                            Give your clients this link to let them view your services and book appointments 24/7.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="flex-1 md:w-80 px-3.5 py-2 rounded-xl bg-white/10 border border-white/15 text-xs text-slate-200 font-mono truncate select-all">
                            {{ bookingUrl }}
                        </div>
                        <button
                            @click="copyLink"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-white text-slate-900 hover:bg-brand-50 transition-all shrink-0 active:scale-95 flex items-center gap-1.5 shadow-md"
                        >
                            <svg v-if="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <svg v-else class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ copied ? 'Copied!' : 'Copy Link' }}</span>
                        </button>
                        <a
                            :href="bookingUrl"
                            target="_blank"
                            class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors shrink-0"
                            title="Open Preview"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- =========================================================
                 3. ELEVATED METRIC KPI CARDS
                 ========================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- KPI: Total Revenue -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Gross Earnings</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-display">
                            ${{ stats?.total_earnings || '0.00' }}
                        </p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">
                            <span class="text-emerald-600 font-semibold">&bull; Active</span>
                            <span>from confirmed & completed</span>
                        </p>
                    </div>
                </div>

                <!-- KPI: Total Bookings -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Bookings</span>
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-display">
                            {{ stats?.total_bookings || 0 }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ stats?.confirmed_bookings || 0 }} confirmed, {{ stats?.completed_bookings || 0 }} finished
                        </p>
                    </div>
                </div>

                <!-- KPI: Pending Appointments -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Review</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-display">
                            {{ stats?.pending_bookings || 0 }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ stats?.pending_bookings > 0 ? 'Requires owner response' : 'Queue cleared' }}
                        </p>
                    </div>
                </div>

                <!-- KPI: Service & Staff Density -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Roster & Offerings</span>
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-display">
                            {{ stats?.services_count || 0 }} <span class="text-base font-normal text-slate-400">/ {{ stats?.staff_count || 0 }}</span>
                        </p>
                        <p class="text-xs text-slate-500">
                            Active services & staff roster
                        </p>
                    </div>
                </div>

            </div>

            <!-- =========================================================
                 4. ANALYTICS & ACTIVITY GRID
                 ========================================================= -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- 7-Day Appointment Volume Chart (8 Cols) -->
                <div class="lg:col-span-8 p-6 sm:p-7 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 font-display">Appointment Volume Trend</h2>
                            <p class="text-xs text-slate-500 mt-0.5">7-day rolling window of client booking activity</p>
                        </div>
                        <div class="px-2.5 py-1 rounded-md bg-slate-100 text-[11px] font-semibold text-slate-600">
                            Last 7 Days
                        </div>
                    </div>

                    <div class="h-64 sm:h-72 w-full">
                        <Line :data="lineChartData" :options="chartOptions" />
                    </div>
                </div>

                <!-- Quick Roster Overview & Shortcuts (4 Cols) -->
                <div class="lg:col-span-4 space-y-5">
                    <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                        <h2 class="text-base font-bold text-slate-900 font-display mb-4">Quick Shortcuts</h2>
                        <div class="space-y-2.5">
                            <Link
                                :href="route('admin.bookings.index')"
                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors group"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs font-bold">
                                        Q
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 group-hover:text-slate-900">Manage Queue</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>

                            <Link
                                :href="route('admin.availability.index')"
                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors group"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                        H
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 group-hover:text-slate-900">Configure Hours</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>

                            <Link
                                :href="route('admin.staff.index')"
                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors group"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center text-xs font-bold">
                                        T
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 group-hover:text-slate-900">Staff Specialists</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- Business Profile Health -->
                    <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tenant Brand</span>
                            <Link :href="route('admin.settings.edit')" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Edit</Link>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 truncate">{{ business?.name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ business?.description || 'No description provided.' }}</p>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span>Phone:</span>
                            <span class="font-medium text-slate-700">{{ business?.phone || 'Not set' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- =========================================================
                 5. RECENT BOOKINGS FEED TABLE
                 ========================================================= -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200/60 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 font-display">Recent Client Bookings</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Latest appointment transactions placed across your business</p>
                    </div>
                    <Link
                        :href="route('admin.bookings.index')"
                        class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1"
                    >
                        <span>View All Bookings</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>

                <div v-if="recentBookings?.length > 0" class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/75 border-b border-slate-200/60 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-6">Client</th>
                                <th class="py-3 px-6">Service</th>
                                <th class="py-3 px-6">Specialist</th>
                                <th class="py-3 px-6">Schedule</th>
                                <th class="py-3 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="booking in recentBookings" :key="booking.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ (booking.user?.name || 'G').charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs">{{ booking.user?.name || 'Guest Client' }}</p>
                                            <p class="text-[11px] text-slate-500">{{ booking.user?.email || 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-800 text-xs">
                                    {{ booking.service?.name || 'Standard Service' }}
                                    <span v-if="booking.service?.price" class="text-slate-400 font-normal">(${{ booking.service.price }})</span>
                                </td>
                                <td class="py-4 px-6 text-slate-600 text-xs">
                                    {{ booking.staff?.name || 'Any Staff' }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-600">
                                    <span class="font-semibold text-slate-900">{{ booking.booking_date }}</span>
                                    <span class="text-slate-400 ml-1">@ {{ booking.start_time?.substring(0, 5) }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize',
                                            getStatusBadge(booking.status)
                                        ]"
                                    >
                                        {{ booking.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16 px-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No bookings yet</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Share your public booking URL with your clients to start scheduling appointments.
                    </p>
                    <div class="mt-4">
                        <button
                            @click="copyLink"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm transition-all"
                        >
                            <span>Copy Booking Link</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>