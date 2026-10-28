<script setup>
import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

const props = defineProps({
    summary: {
        type: Object,
        default: () => ({})
    },
    statusBreakdown: {
        type: Object,
        default: () => ({})
    },
    chartData: {
        type: Object,
        default: () => ({ labels: [], revenue: [] })
    },
    topBusinesses: {
        type: Array,
        default: () => []
    },
});

const trendChart = computed(() => ({
    labels: props.chartData?.labels || [],
    datasets: [{
        label: 'Gross Volume ($)',
        data: props.chartData?.revenue || [],
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.08)',
        fill: true,
        tension: 0.35,
        borderWidth: 3,
        pointBackgroundColor: '#4f46e5',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
    }],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#0f172a',
            titleColor: '#ffffff',
            bodyColor: '#e2e8f0',
            padding: 10,
            cornerRadius: 10,
            callbacks: {
                label: (context) => ` Revenue: $${Number(context.raw).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
            }
        }
    },
    scales: {
        y: {
            grid: { color: 'rgba(226, 232, 240, 0.6)' },
            ticks: {
                color: '#64748b',
                font: { size: 11 },
                callback: (val) => `$${val}`
            }
        },
        x: {
            grid: { display: false },
            ticks: { color: '#64748b', font: { size: 11 } }
        }
    }
};

const statusMeta = {
    pending: { label: 'Pending Payment/Confirmation', badge: 'bg-amber-50 text-amber-700 border-amber-200' },
    confirmed: { label: 'Confirmed Sessions', badge: 'bg-indigo-50 text-indigo-700 border-indigo-200' },
    completed: { label: 'Completed Deliveries', badge: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    canceled: { label: 'Canceled / Voided', badge: 'bg-rose-50 text-rose-700 border-rose-200' },
};

const statusEntries = computed(() => Object.entries(props.statusBreakdown || {}));
</script>

<template>
    <Head title="Financial Yield & Performance — Sovereign Control" />

    <SovereignLayout>
        <div class="space-y-8 max-w-7xl mx-auto pb-16">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            Commission & Yield Model: {{ summary?.commission_rate }}%
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                        Financial Intelligence & Yield
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Aggregated gross booking transaction volume and platform commission projections.
                    </p>
                </div>
            </div>

            <!-- KPI Metric Chips (4 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Gross Volume</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">${{ summary?.gross_revenue || '0.00' }}</div>
                        <span class="text-[11px] text-slate-500 font-medium">All tenant bookings</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">
                        $
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Projected Yield</span>
                        <div class="text-2xl font-display font-bold text-brand-600 mt-1">${{ summary?.platform_yield || '0.00' }}</div>
                        <span class="text-[11px] text-brand-600 font-medium">@ {{ summary?.commission_rate }}% commission</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Avg. Ticket Value</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">${{ summary?.avg_booking_value || '0.00' }}</div>
                        <span class="text-[11px] text-slate-500 font-medium">Per confirmed session</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Paid Sessions</span>
                        <div class="text-2xl font-display font-bold text-emerald-600 mt-1">{{ summary?.paid_volume || 0 }}</div>
                        <span class="text-[11px] text-slate-500 font-medium">Out of {{ summary?.total_volume || 0 }} total</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Charts & Breakdown Row -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- 6-Month Chart (8 cols) -->
                <div class="lg:col-span-8 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-display font-bold text-lg text-slate-900">6-Month Gross Revenue Signal</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Historical trend across confirmed and completed bookings</p>
                        </div>
                    </div>

                    <div class="h-72 w-full">
                        <Line v-if="chartData?.labels?.length" :data="trendChart" :options="chartOptions" />
                        <div v-else class="h-full flex items-center justify-center text-slate-400 text-xs">
                            No revenue signal captured yet
                        </div>
                    </div>
                </div>

                <!-- Status Breakdown Card (4 cols) -->
                <div class="lg:col-span-4 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-display font-bold text-sm text-slate-900">Booking Volume by Status</h3>
                    
                    <div class="space-y-3">
                        <div
                            v-for="[status, count] in statusEntries"
                            :key="status"
                            class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between"
                        >
                            <div class="min-w-0 pr-2">
                                <span class="text-xs font-semibold text-slate-700 block truncate">
                                    {{ statusMeta[status]?.label || status }}
                                </span>
                            </div>
                            <span class="font-display font-bold text-slate-900 text-sm">
                                {{ count }}
                            </span>
                        </div>

                        <div v-if="!statusEntries.length" class="text-center py-6 text-slate-400 text-xs">
                            No bookings indexed
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Earning Workspaces Table -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-display font-bold text-lg text-slate-900">Top Earning Workspaces</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ranked by gross confirmed booking volume</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 text-slate-400 uppercase font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5 w-16">Rank</th>
                                <th class="px-6 py-3.5">Business Workspace</th>
                                <th class="px-6 py-3.5 text-right">Gross Revenue</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="(biz, idx) in topBusinesses"
                                :key="biz.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <td class="px-6 py-4 font-display font-bold text-slate-400 text-sm">
                                    #{{ idx + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-display font-bold text-sm">
                                            {{ biz.name?.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ biz.name }}</div>
                                            <div class="text-[11px] text-slate-400 font-mono">/b/{{ biz.slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-display font-bold text-emerald-600 text-sm">
                                        ${{ Number(biz.revenue || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link
                                        :href="`/b/${biz.slug}`"
                                        target="_blank"
                                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold transition-all inline-flex items-center gap-1"
                                    >
                                        <span>Visit Portal</span>
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="!topBusinesses || topBusinesses.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    No transaction volume recorded yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </SovereignLayout>
</template>
