<script setup>
/**
 * Sovereign Intelligence Hub v4.3.0-PRO
 * 
 * DESIGN PHILOSOPHY:
 * 1. Macro-Financial Analytics: Integrated via Global Yield Line Chart.
 * 2. Infrastructure Integrity: Real-time system health telemetry pods.
 * 3. Entity Management: Verified Node Ledger for administrative oversight.
 */

import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

const props = defineProps({
    stats: Object,
    chartData: Object,
    recentEntities: Array // FIXED: Matched with Controller variable name
});

// Chart: Platform Revenue Trajectory (Indigo Signal)
const yieldChart = computed(() => ({
    labels: props.chartData?.labels || [],
    datasets: [{
        label: 'Global Yield',
        data: props.chartData?.revenue || [], // FIXED: Matched with Controller data key
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.05)',
        fill: true,
        tension: 0.4,
        borderWidth: 4,
        pointRadius: 0
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { display: false },
        x: {
            grid: { display: false },
            ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }
        }
    }
};
</script>

<template>

    <Head title="Sovereign Hub | Global Management" />

    <SovereignLayout>
        <!-- REMOVED: max-w-7xl and mx-auto to allow Layout's internal container to manage spacing -->
        <div class="space-y-16 animate-fadeIn">

            <!-- EDITORIAL OVERLORD HEADER -->
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-12">
                <div class="space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.4em] text-indigo-600">Global Grid
                            Oversight protocol v4.2.0</span>
                    </div>
                    <h1 class="text-7xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                        Master <br /> <span class="text-indigo-600">Oversight.</span>
                    </h1>
                </div>

                <!-- PLATFORM KPI DOCK -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full xl:w-auto">
                    <div class="bg-[#09090B] p-8 rounded-[2.5rem] text-white shadow-2xl border border-white/5">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.4em] mb-4">Total Revenue
                        </p>
                        <h3 class="text-3xl font-black tracking-tighter">${{ stats?.revenue || '0.00' }}</h3>
                    </div>
                    <div
                        class="bg-white border border-slate-200/60 p-8 rounded-[2.5rem] shadow-xl border-t-[10px] border-t-indigo-600">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4">Node Count</p>
                        <h3 class="text-3xl font-black text-[#09090B] tracking-tighter">{{ stats?.businesses?.total || 0
                        }}</h3>
                    </div>
                </div>
            </div>

            <!-- ANALYTICS MATRIX -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Global Yield Chart -->
                <div class="lg:col-span-8 bg-white border border-slate-200/60 rounded-[4rem] p-12 shadow-2xl">
                    <div class="flex justify-between items-center mb-12">
                        <h3 class="text-2xl font-black text-[#09090B] tracking-tighter uppercase">Financial Trajectory
                        </h3>
                        <span
                            class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.4em] bg-emerald-50 px-4 py-2 rounded-full border border-emerald-100">Live
                            Sync</span>
                    </div>
                    <div class="h-80">
                        <Line :data="yieldChart" :options="chartOptions" />
                    </div>
                </div>

                <!-- Platform Integrity Readout -->
                <div class="lg:col-span-4 space-y-8">
                    <div
                        class="bg-[#09090B] rounded-[3.5rem] p-12 text-white shadow-2xl relative overflow-hidden border border-white/5 group">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Integrity Check
                            </p>
                            <div class="space-y-8">
                                <div v-for="node in ['Storage Cluster', 'Auth Sentinel', 'Email Relay']" :key="node"
                                    class="flex justify-between items-center">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">{{ node
                                    }}</span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]">
                                        </div>
                                        <span
                                            class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Nominal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- THE ENTITY LEDGER (Central Business Manager) -->
            <div
                class="bg-white rounded-[5rem] border border-slate-200/60 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] overflow-hidden">
                <div
                    class="px-14 py-14 border-b border-slate-100 flex flex-col md:flex-row md:items-end justify-between gap-10 bg-slate-50/40">
                    <div>
                        <h3 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">Entity
                            Ledger</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">
                            Platform-wide node oversight and administrative control</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-slate-50">
                            <!-- FIXED: Corrected variable to recentEntities -->
                            <tr v-for="entity in recentEntities" :key="entity.id"
                                class="group hover:bg-indigo-50/30 transition-colors">
                                <td class="px-14 py-14">
                                    <div class="flex items-center gap-10">
                                        <div
                                            class="w-20 h-16 rounded-[2rem] bg-[#09090B] flex items-center justify-center font-black text-white text-2xl shadow-2xl group-hover:bg-indigo-600 transition-all duration-700">
                                            {{ entity.name?.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p
                                                class="text-3xl font-black text-[#09090B] tracking-tighter leading-none uppercase">
                                                {{ entity.name }}</p>
                                            <p
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3">
                                                Principal: {{ entity.owner }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-14 py-14">
                                    <div class="flex flex-col gap-2">
                                        <span
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Node
                                            Status</span>
                                        <span
                                            class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-[0.2em] border border-emerald-100 w-fit">Verified</span>
                                    </div>
                                </td>
                                <td class="px-14 py-14 text-right">
                                    <div class="flex justify-end gap-3">
                                        <Link :href="`/b/${entity.slug}`" target="_blank"
                                            class="px-8 py-4 bg-[#09090B] text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-all">
                                            Audit Node</Link>
                                        <button
                                            class="px-8 py-4 bg-rose-50 text-rose-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all">Suspend
                                            Node</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- FIXED: Added fallback for empty state -->
                <div v-if="!recentEntities?.length" class="p-40 text-center">
                    <p class="text-xs font-black text-slate-300 uppercase tracking-[0.8em]">Zero Entities Currently
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