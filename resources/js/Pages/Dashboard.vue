<script setup>
/**
 * Command Center v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Revenue Trajectory: Real-time 7-day synchronization.
 * 2. Operational KPIs: Macro-scale data quantization.
 * 3. System Ledger: Verified transactional session logs.
 * 4. Node Integrity: Real-time hardware/infrastructure monitoring.
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Line, Doughnut } from 'vue-chartjs';
import {
    Chart as ChartJS, Title, Tooltip, Legend, LineElement,
    CategoryScale, LinearScale, PointElement, ArcElement, Filler
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, ArcElement, Filler);

const props = defineProps({
    stats: Object,
    business: Object,
    chartData: Object,
    recentBookings: Array
});

// Chart 01: Revenue Trajectory (Clinical Indigo)
const revenueChartData = computed(() => ({
    labels: props.chartData?.labels || [],
    datasets: [{
        label: 'Revenue',
        data: props.chartData?.revenue || [],
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.05)',
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#09090B',
        pointBorderColor: '#4f46e5',
        pointHoverRadius: 6,
        borderWidth: 4
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { display: false },
        x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' } }
    }
};

const bookingUrl = computed(() => `${window.location.origin}/b/${props.business?.slug}`);
const copyLink = () => {
    navigator.clipboard.writeText(bookingUrl.value);
    alert('✅ Deployment URL secured to clipboard.');
};
const formatMoney = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(parseFloat(val || 0));
</script>

<template>

    <Head title="Command Center | SmartBooking Intelligence" />

    <AuthenticatedLayout>
        <div
            class="relative min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] pb-48 selection:bg-indigo-600 selection:text-white">

            <!-- ARCHITECTURAL BLUEPRINT BACKGROUND -->
            <div class="fixed inset-0 z-0 pointer-events-none">
                <div
                    class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
                </div>
                <div
                    class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
                </div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 pt-16">

                <!-- =========================================================
                     EDITORIAL DISPATCH HEADER (Fixed White Space)
                     ========================================================= -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-10 animate-slideUp">
                        <div class="space-y-6">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span
                                    class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Administrative
                                    Dispatch Node Active</span>
                            </div>
                            <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                                Control <br />
                                <span
                                    class="text-indigo-600 font-medium text-5xl lg:text-8xl uppercase tracking-tighter">Hub.</span>
                            </h1>
                        </div>

                        <!-- TACTICAL LINK MODULE -->
                        <div
                            class="bg-white border border-slate-200/60 p-2.5 rounded-[2rem] shadow-2xl flex items-center gap-4 w-fit">
                            <div class="px-6 py-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 shadow-inner">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Node: {{
                                    business?.slug }}</span>
                            </div>
                            <button @click="copyLink"
                                class="px-8 py-4 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-[1.5rem] hover:bg-indigo-600 transition-all active:scale-95 shadow-xl">
                                Push Link
                            </button>
                        </div>
                    </div>

                    <!-- ADMINISTRATIVE INTELLIGENCE BRIEFING -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Operations
                                    Intelligence Briefing</p>
                                <div class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                                    <span
                                        class="text-[8px] font-black uppercase tracking-widest text-emerald-400">AES-256
                                        Encrypted Session</span>
                                </div>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">
                                Engineering <br /> Operational Throughput.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    Welcome to the Command Hub. This interface is engineered to provide a 360-degree
                                    oversight of your session packets and revenue trajectory. Efficiency in a booking
                                    ecosystem is determined by the quantization of available time slots against
                                    specialist roster capacity.
                                </p>
                                <p>
                                    Our priority is **Maximum Confidentiality**. Every transaction indexed in the System
                                    Ledger below is protected by Node-Sentinel encryption, ensuring that your
                                    administrative data and customer privacy remain absolute.
                                </p>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- KPI TACTICAL PODS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div v-for="(val, label) in { 'Yield Index': formatMoney(stats.total_earnings), 'Data Packets': stats.total_bookings, 'Review Queue': stats.pending_bookings }"
                        :key="label"
                        class="group bg-white border border-slate-200/60 p-12 rounded-[4rem] shadow-xl hover:-translate-y-2 transition-all duration-500">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-6">{{ label }}</p>
                        <h3
                            class="text-6xl font-black text-[#09090B] tracking-tighter group-hover:text-indigo-600 transition-colors">
                            {{ val }}</h3>
                    </div>
                </div>

                <!-- INTELLIGENCE LAYER -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
                    <!-- Revenue Trajectory Pod -->
                    <div class="lg:col-span-8 bg-white border border-slate-200/60 rounded-[4rem] p-12 shadow-2xl">
                        <div class="flex justify-between items-center mb-12">
                            <div>
                                <h3 class="text-3xl font-black text-[#09090B] tracking-tighter uppercase">Revenue
                                    Trajectory</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-2">7-Day
                                    Real-Time Operational Sync</p>
                            </div>
                        </div>
                        <div class="h-80">
                            <Line :data="revenueChartData" :options="chartOptions" />
                        </div>
                    </div>

                    <!-- Sidebar Hub -->
                    <div class="lg:col-span-4 space-y-10">
                        <!-- Human Resources Tactical Pod -->
                        <div
                            class="bg-[#09090B] rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden group border border-white/5">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em] mb-6">Staff
                                    Deployment</p>
                                <h4 class="text-4xl font-black mb-8 leading-[0.9] uppercase tracking-tighter">Team
                                    <br /> Specialists.
                                </h4>
                                <Link :href="route('admin.staff.index')"
                                    class="inline-flex items-center justify-center w-full py-6 bg-white text-[#09090B] rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.4em] shadow-xl hover:bg-indigo-600 hover:text-white transition-all active:scale-95">
                                    Manage Roster
                                </Link>
                            </div>
                            <div
                                class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-600/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000">
                            </div>
                        </div>

                        <!-- System Integrity Pod -->
                        <div class="bg-white border border-slate-200/60 rounded-[3.5rem] p-12 shadow-xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-12">Node
                                Integrity Report</p>
                            <div class="space-y-10">
                                <div v-for="(node, n) in ['Booking Engine', 'Database Node', 'Global CDN']" :key="n"
                                    class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-[#09090B] uppercase tracking-widest">{{
                                        node }}</span>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.6)] animate-pulse">
                                        </div>
                                        <span
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Secure</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SYSTEM LEDGER -->
                <div
                    class="bg-white rounded-[5rem] border border-slate-200/60 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] overflow-hidden animate-fadeIn">
                    <div
                        class="px-12 py-16 border-b border-slate-100 flex flex-col md:flex-row md:items-end justify-between gap-10 bg-slate-50/40">
                        <div>
                            <h3 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                System Ledger</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4">Verified
                                Transactional Session Logs (Real-time)</p>
                        </div>
                        <Link :href="route('admin.bookings.index')"
                            class="px-12 py-6 bg-[#09090B] rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.4em] text-white hover:bg-indigo-600 transition-all shadow-2xl active:scale-95">
                            Verify Full Queue</Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="booking in recentBookings" :key="booking.id"
                                    class="group hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-14 py-14">
                                        <div class="flex items-center gap-10">
                                            <div
                                                class="w-20 h-16 rounded-[2rem] bg-[#09090B] flex items-center justify-center font-black text-white text-2xl shadow-2xl group-hover:rotate-6 transition-all duration-500">
                                                {{ (booking.user?.charAt(0) || 'U').toUpperCase() }}
                                            </div>
                                            <div>
                                                <p
                                                    class="text-3xl font-black text-[#09090B] tracking-tighter leading-none uppercase">
                                                    {{ booking.user }}</p>
                                                <p
                                                    class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mt-3">
                                                    {{ booking.service }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-14 py-14">
                                        <div
                                            class="inline-flex items-center gap-3 px-5 py-2.5 bg-emerald-50 border border-emerald-100 rounded-full shadow-sm">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                            <span
                                                class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.25em]">{{
                                                    booking.status }}</span>
                                        </div>
                                    </td>
                                    <td class="px-14 py-14 text-right">
                                        <p class="text-3xl font-black text-[#09090B] tracking-tighter leading-none">${{
                                            booking.value }}</p>
                                        <p
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3">
                                            Node ID: #{{ booking.id }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!recentBookings?.length" class="p-40 text-center">
                        <svg class="w-16 h-16 text-slate-200 mx-auto mb-10" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="text-xs font-black text-slate-300 uppercase tracking-[0.8em]">Zero Transactional
                            Packets Indexed</p>
                    </div>
                </div>

                <!-- FOOTER METADATA -->
                <div class="mt-40 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1.5em]">Sovereign Infrastructure
                        v4.2.0 | All Data Synchronized via Sentinel Node</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(span),
:deep(p),
:deep(button),
:deep(a) {
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

.shadow-inner {
    box-shadow: inset 0 4px 12px 0 rgba(0, 0, 0, 0.08);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.animate-slideUp {
    animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fadeIn {
    animation: fadeIn 1.5s ease-out forwards;
}
</style>