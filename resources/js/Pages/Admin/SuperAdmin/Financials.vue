<script setup>
/**
 * Financial Intelligence Node v4.2.0
 *
 * CORE SYSTEMS:
 * 1. Platform Yield Projection: Assumed commission rate against paid booking volume.
 * 2. Trajectory Chart: 6-month gross revenue trend across all business nodes.
 * 3. Node Leaderboard: Top earning businesses on the platform.
 *
 * NOTE: No payment processor is integrated yet, so "yield" figures here are
 * a projection based on a flat commission rate, not settled transactions.
 */

import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

const props = defineProps({
    summary: Object,
    statusBreakdown: Object,
    chartData: Object,
    topBusinesses: Array,
});

const trendChart = computed(() => ({
    labels: props.chartData?.labels || [],
    datasets: [{
        label: 'Gross Revenue',
        data: props.chartData?.revenue || [],
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.06)',
        fill: true,
        tension: 0.4,
        borderWidth: 4,
        pointRadius: 0,
    }],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { display: false },
        x: {
            grid: { display: false },
            ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' },
        },
    },
};

const statusMeta = {
    pending: { label: 'Pending', color: 'text-amber-600', bg: 'bg-amber-50', border: 'border-amber-100' },
    confirmed: { label: 'Confirmed', color: 'text-indigo-600', bg: 'bg-indigo-50', border: 'border-indigo-100' },
    completed: { label: 'Completed', color: 'text-emerald-600', bg: 'bg-emerald-50', border: 'border-emerald-100' },
    canceled: { label: 'Canceled', color: 'text-rose-600', bg: 'bg-rose-50', border: 'border-rose-100' },
};

const statusEntries = computed(() => Object.entries(props.statusBreakdown || {}));
</script>

<template>

    <Head title="Financials | Sovereign Oversight" />

    <SovereignLayout>
        <div class="space-y-16 animate-fadeIn">

            <!-- HEADER -->
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-12">
                <div class="space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.4em] text-indigo-600">Projected
                            yield &middot; {{ summary?.commission_rate }}% commission model</span>
                    </div>
                    <h1 class="text-7xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                        Financial <br /> <span class="text-indigo-600">Trajectory.</span>
                    </h1>
                </div>

                <!-- KPI DOCK -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full xl:w-auto">
                    <div class="bg-[#09090B] p-8 rounded-[2.5rem] text-white shadow-2xl border border-white/5">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.4em] mb-4">Gross Revenue
                        </p>
                        <h3 class="text-3xl font-black tracking-tighter">${{ summary?.gross_revenue || '0.00' }}</h3>
                    </div>
                    <div class="bg-[#09090B] p-8 rounded-[2.5rem] text-white shadow-2xl border border-white/5">
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.4em] mb-4">Platform Yield
                        </p>
                        <h3 class="text-3xl font-black tracking-tighter text-indigo-400">${{ summary?.platform_yield ||
                            '0.00' }}</h3>
                    </div>
                    <div
                        class="bg-white border border-slate-200/60 p-8 rounded-[2.5rem] shadow-xl border-t-[10px] border-t-indigo-600">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4">Avg. Booking</p>
                        <h3 class="text-3xl font-black text-[#09090B] tracking-tighter">${{ summary?.avg_booking_value
                            || '0.00' }}</h3>
                    </div>
                </div>
            </div>

            <!-- ANALYTICS MATRIX -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Revenue Chart -->
                <div class="lg:col-span-8 bg-white border border-slate-200/60 rounded-[4rem] p-12 shadow-2xl">
                    <div class="flex justify-between items-center mb-12">
                        <h3 class="text-2xl font-black text-[#09090B] tracking-tighter uppercase">6-Month Revenue Signal
                        </h3>
                        <span
                            class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.4em] bg-emerald-50 px-4 py-2 rounded-full border border-emerald-100">
                            {{ summary?.paid_volume }} paid sessions
                        </span>
                    </div>
                    <div class="h-80">
                        <Line v-if="chartData?.labels?.length" :data="trendChart" :options="chartOptions" />
                        <div v-else class="h-full flex items-center justify-center">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.6em]">No revenue
                                signal
                                yet</p>
                        </div>
                    </div>
                </div>

                <!-- Status Breakdown -->
                <div
                    class="lg:col-span-4 bg-[#09090B] rounded-[3.5rem] p-12 text-white shadow-2xl relative overflow-hidden border border-white/5">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                    </div>
                    <div class="relative z-10 space-y-10">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Volume by Status
                        </p>
                        <div class="space-y-7">
                            <div v-for="[status, count] in statusEntries" :key="status"
                                class="flex justify-between items-center">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">{{
                                    statusMeta[status]?.label || status }}</span>
                                <span class="text-2xl font-black tracking-tighter"
                                    :class="statusMeta[status]?.color || 'text-white'">{{ count }}</span>
                            </div>
                            <div v-if="!statusEntries.length"
                                class="text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                No bookings indexed
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOP EARNING NODES -->
            <div
                class="bg-white rounded-[5rem] border border-slate-200/60 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] overflow-hidden">
                <div
                    class="px-14 py-14 border-b border-slate-100 flex flex-col md:flex-row md:items-end justify-between gap-10 bg-slate-50/40">
                    <div>
                        <h3 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">Top
                            Earning
                            Nodes</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">
                            Ranked by confirmed + completed booking revenue</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="(biz, idx) in topBusinesses" :key="biz.id"
                                class="group hover:bg-indigo-50/30 transition-colors">
                                <td class="px-14 py-12 w-24">
                                    <span class="text-3xl font-black text-slate-200 tracking-tighter">{{
                                        String(idx + 1).padStart(2, '0') }}</span>
                                </td>
                                <td class="px-14 py-12">
                                    <div class="flex items-center gap-8">
                                        <div
                                            class="w-16 h-14 rounded-[1.5rem] bg-[#09090B] flex items-center justify-center font-black text-white text-xl shadow-xl group-hover:bg-indigo-600 transition-all duration-500">
                                            {{ biz.name?.charAt(0).toUpperCase() }}
                                        </div>
                                        <p class="text-2xl font-black text-[#09090B] tracking-tighter uppercase">{{
                                            biz.name }}</p>
                                    </div>
                                </td>
                                <td class="px-14 py-12 text-right">
                                    <p class="text-2xl font-black text-indigo-600 tracking-tighter">${{
                                        biz.revenue?.toFixed(2) }}</p>
                                </td>
                                <td class="px-14 py-12 text-right">
                                    <Link :href="`/b/${biz.slug}`" target="_blank"
                                        class="px-8 py-4 bg-[#09090B] text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-all">
                                        Audit Node</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="!topBusinesses?.length" class="p-40 text-center">
                    <p class="text-xs font-black text-slate-300 uppercase tracking-[0.8em]">Zero Revenue Currently
                        Indexed</p>
                </div>
            </div>
        </div>
    </SovereignLayout>
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

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.8s ease-out forwards;
}
</style>
