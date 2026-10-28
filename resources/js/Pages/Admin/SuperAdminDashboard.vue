<script setup>
import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({})
    },
    chartData: {
        type: Object,
        default: () => ({ labels: [], revenue: [] })
    },
    recentEntities: {
        type: Array,
        default: () => []
    }
});

const yieldChart = computed(() => ({
    labels: props.chartData?.labels || [],
    datasets: [{
        label: 'Gross Platform Volume',
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
    }]
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
                label: (context) => ` Volume: $${Number(context.raw).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
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
            ticks: {
                color: '#64748b',
                font: { size: 11 }
            }
        }
    }
};
</script>

<template>
    <Head title="Sovereign Control Oversight — SmartBooking" />

    <SovereignLayout>
        <div class="space-y-8 max-w-7xl mx-auto pb-16">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Root Administrator
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                        Global Platform Oversight
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        High-altitude telemetry across all business workspaces, platform gross volumes, and system node health.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        :href="route('superadmin.entities.index')"
                        class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-semibold shadow-xs transition-all flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Manage Workspaces</span>
                    </Link>
                </div>
            </div>

            <!-- KPI Metric Cards (4 Grid) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Gross Revenue -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Volume</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">${{ stats.revenue || '0.00' }}</div>
                        <span class="text-[11px] text-emerald-600 font-medium">Estimated MRR: ${{ stats.mrr || '0.00' }}</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Total Workspaces -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Workspaces</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">{{ stats.businesses?.total || 0 }}</div>
                        <span class="text-[11px] text-brand-600 font-medium">{{ stats.businesses?.active || 0 }} Active / {{ stats.businesses?.suspended || 0 }} Suspended</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>

                <!-- Registered Users -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Platform Accounts</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">{{ stats.users || 0 }}</div>
                        <span class="text-[11px] text-slate-500 font-medium">Owners & Clients combined</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>

                <!-- Health Status -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Node Cluster</span>
                        <div class="text-2xl font-display font-bold text-emerald-600 mt-1">100% Nominal</div>
                        <span class="text-[11px] text-slate-500 font-medium">Database & Queues healthy</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Analytics & Telemetry Row -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Revenue Trajectory Chart (8 cols) -->
                <div class="lg:col-span-8 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-display font-bold text-lg text-slate-900">Platform Volume Trajectory</h2>
                            <p class="text-xs text-slate-500 mt-0.5">30-day cross-tenant gross volume aggregation</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Live Stream
                        </span>
                    </div>

                    <div class="h-72 w-full">
                        <Line :data="yieldChart" :options="chartOptions" />
                    </div>
                </div>

                <!-- Platform Node Telemetry (4 cols) -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-slate-950 text-white border border-slate-900 rounded-2xl p-6 shadow-xs relative overflow-hidden">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-brand-500/20 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Infrastructure Sentinel</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            </div>

                            <div class="space-y-3.5">
                                <div
                                    v-for="service in [
                                        { name: 'Multi-Tenant Isolation Scope', status: 'Enforced', color: 'emerald' },
                                        { name: 'SQLite Storage Driver', status: 'Operational', color: 'emerald' },
                                        { name: 'Queue Worker Worker-01', status: 'Active (Daemon)', color: 'emerald' },
                                        { name: 'Inertia v2 Protocol', status: 'Nominal', color: 'emerald' }
                                    ]"
                                    :key="service.name"
                                    class="flex items-center justify-between text-xs pb-2 border-b border-white/5 last:border-b-0"
                                >
                                    <span class="text-slate-300">{{ service.name }}</span>
                                    <span class="font-semibold text-emerald-400">{{ service.status }}</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <Link
                                    :href="route('superadmin.config')"
                                    class="w-full py-2 px-3 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-semibold transition-all block text-center"
                                >
                                    View Full Configuration
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Navigation Card -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Sovereign Tools</span>
                        <div class="grid grid-cols-2 gap-2">
                            <Link
                                :href="route('superadmin.financials')"
                                class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 border border-slate-100 text-xs font-semibold text-slate-700 hover:text-brand-700 transition-colors"
                            >
                                💰 Financial Yield
                            </Link>
                            <Link
                                :href="route('superadmin.audit')"
                                class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 border border-slate-100 text-xs font-semibold text-slate-700 hover:text-brand-700 transition-colors"
                            >
                                📜 Audit Ledger
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Business Entities Ledger Table -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-display font-bold text-lg text-slate-900">Recent Business Workspaces</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Latest registered business nodes on the platform</p>
                    </div>
                    <Link
                        :href="route('superadmin.entities.index')"
                        class="text-xs font-semibold text-brand-600 hover:text-brand-700"
                    >
                        View all workspaces →
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 text-slate-400 uppercase font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Business Workspace</th>
                                <th class="px-6 py-3.5">Owner / Principal</th>
                                <th class="px-6 py-3.5">Total Bookings</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="entity in recentEntities"
                                :key="entity.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-display font-bold text-sm">
                                            {{ entity.name?.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ entity.name }}</div>
                                            <div class="text-[11px] text-slate-400 font-mono">/b/{{ entity.slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-900">{{ entity.owner }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900">{{ entity.load }}</span>
                                    <span class="text-slate-400 text-[11px] ml-1">sessions</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-0.5 rounded-full text-[11px] font-semibold border',
                                            entity.status === 'Active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'
                                        ]"
                                    >
                                        {{ entity.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="`/b/${entity.slug}`"
                                            target="_blank"
                                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold transition-all inline-flex items-center gap-1"
                                        >
                                            <span>Visit Portal</span>
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!recentEntities || recentEntities.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    No business workspaces registered yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </SovereignLayout>
</template>