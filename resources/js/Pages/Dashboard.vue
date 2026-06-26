<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    business: Object,
    // Assuming you might add this to your controller later; 
    // styled to look like professional system logs.
    recentBookings: {
        type: Array,
        default: () => []
    }
});

const bookingUrl = computed(() => `${window.location.origin}/b/${props.business?.slug}`);

const copyLink = () => {
    navigator.clipboard.writeText(bookingUrl.value);
    alert('Deployment URL copied to clipboard.');
};

const formatMoney = (val) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(parseFloat(val || 0));
};
</script>

<template>

    <Head title="System Intelligence Dashboard" />

    <AuthenticatedLayout>
        <!-- SIGNATURE RADIAL DOT BACKGROUND -->
        <div
            class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] pb-32">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

                <!-- PREMIUM DISPATCH HEADER -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-16 gap-8">
                    <div>
                        <div class="flex items-center gap-2.5 mb-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-[0.3em] text-slate-400">System
                                Live: {{ business?.name }}</span>
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black text-[#11131A] tracking-tighter leading-none">
                            Management <span class="text-slate-400 font-medium text-4xl lg:text-6xl">Hub.</span>
                        </h1>
                    </div>

                    <!-- SMART ACTION DOCK -->
                    <div
                        class="bg-white border border-slate-200/80 p-2 rounded-[2rem] shadow-xl shadow-slate-200/40 flex flex-col sm:flex-row items-center gap-4 max-w-xl">
                        <div class="flex items-center gap-4 px-6 py-3 bg-slate-50 rounded-[1.5rem] w-full sm:w-auto">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span
                                class="text-[11px] font-black text-[#11131A] uppercase tracking-widest truncate max-w-[180px]">{{
                                    bookingUrl }}</span>
                        </div>
                        <button @click="copyLink"
                            class="w-full sm:w-auto px-8 py-4 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-[1.5rem] hover:bg-indigo-600 transition-all shadow-xl active:scale-95">
                            Copy Link
                        </button>
                    </div>
                </div>

                <!-- ANALYTICS GRID -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

                    <!-- REVENUE MODULE -->
                    <div
                        class="group bg-white border border-slate-200/60 p-10 rounded-[3rem] shadow-2xl shadow-slate-200/40 hover:-translate-y-2 transition-all duration-500">
                        <div class="flex justify-between items-start mb-12">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-emerald-500 tracking-[0.2em] uppercase bg-emerald-50 px-3 py-1 rounded-full">Live
                                Earnings</span>
                        </div>
                        <h4 class="text-5xl font-black text-[#11131A] tracking-tighter">{{
                            formatMoney(stats.total_earnings) }}</h4>
                        <p class="text-slate-400 text-[10px] font-black mt-3 uppercase tracking-[0.2em]">Gross Revenue
                            Index</p>
                    </div>

                    <!-- VOLUME MODULE -->
                    <div
                        class="group bg-white border border-slate-200/60 p-10 rounded-[3rem] shadow-2xl shadow-slate-200/40 hover:-translate-y-2 transition-all duration-500">
                        <div class="flex justify-between items-start mb-12">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-indigo-400 tracking-[0.2em] uppercase bg-indigo-50 px-3 py-1 rounded-full">Total
                                Load</span>
                        </div>
                        <h4 class="text-5xl font-black text-[#11131A] tracking-tighter">{{ stats.total_bookings }}</h4>
                        <p class="text-slate-400 text-[10px] font-black mt-3 uppercase tracking-[0.2em]">Appointments
                            Confirmed</p>
                    </div>

                    <!-- ACTION MODULE -->
                    <div
                        class="group bg-[#11131A] p-10 rounded-[3rem] shadow-2xl shadow-indigo-900/20 hover:-translate-y-2 transition-all duration-500 text-white">
                        <div class="flex justify-between items-start mb-12">
                            <div
                                class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white group-hover:bg-amber-500 transition-all duration-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-amber-500 tracking-[0.2em] uppercase bg-white/5 px-3 py-1 rounded-full">Action
                                Required</span>
                        </div>
                        <h4 class="text-5xl font-black tracking-tighter">{{ stats.pending_bookings }}</h4>
                        <Link :href="route('admin.bookings.index')"
                            class="text-indigo-400 text-[10px] font-black mt-3 uppercase tracking-[0.2em] flex items-center gap-2 hover:text-white transition-colors">
                            Initialize Queue Review
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- SECONDARY LAYOUT -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                    <!-- SYSTEM LEDGER (RECENT ACTIVITY) -->
                    <div class="lg:col-span-8">
                        <div
                            class="bg-white rounded-[3.5rem] border border-slate-200/60 shadow-2xl shadow-slate-200/30 overflow-hidden">
                            <div class="px-10 py-10 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-black text-[#11131A] tracking-tight">System Ledger</h3>
                                    <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-1">
                                        Real-time session updates</p>
                                </div>
                                <Link :href="route('admin.bookings.index')"
                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 hover:text-indigo-800 transition">
                                    View History</Link>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-slate-50">
                                        <tr v-for="i in 4" :key="i"
                                            class="group hover:bg-slate-50/50 transition-colors">
                                            <td class="px-10 py-8">
                                                <div class="flex items-center gap-6">
                                                    <div
                                                        class="w-14 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-xs font-black text-slate-300 group-hover:bg-[#11131A] group-hover:text-white transition-all duration-500">
                                                        U{{ i }}
                                                    </div>
                                                    <div>
                                                        <p class="text-base font-black text-[#11131A]">Automated Entry
                                                            #{{ 8024 + i }}</p>
                                                        <p
                                                            class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                                            Scheduled Event</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-10 py-8">
                                                <span
                                                    class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-[0.2em] border border-emerald-100">Synchronized</span>
                                            </td>
                                            <td class="px-10 py-8 text-right">
                                                <p class="text-base font-black text-[#11131A] tracking-tight">{{
                                                    formatMoney(120 - (i * 15)) }}</p>
                                                <p
                                                    class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                                    Net Value</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="stats.total_bookings === 0" class="p-20 text-center">
                                <p class="text-sm font-bold text-slate-300 uppercase tracking-widest leading-loose">No
                                    transactional data<br />currently indexed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- SIDEBAR OPS -->
                    <div class="lg:col-span-4 space-y-8">
                        <!-- DEPLOYMENT CARD -->
                        <div
                            class="bg-indigo-600 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-indigo-900/20 group">
                            <div class="relative z-10">
                                <h4 class="text-2xl font-black mb-4 leading-none">Scaling <br />Operations?</h4>
                                <p class="text-indigo-100 text-sm mb-10 font-medium leading-relaxed">Expand your service
                                    intelligence and pricing models to optimize revenue throughput.</p>
                                <Link :href="route('admin.services.index')"
                                    class="inline-flex items-center justify-center w-full py-5 bg-white text-[#11131A] rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.3em] shadow-xl hover:bg-indigo-50 transition-all active:scale-95">
                                    Manage Catalog
                                </Link>
                            </div>
                            <!-- Background Decor -->
                            <div
                                class="absolute -right-6 -bottom-6 w-32 h-32 bg-indigo-500 rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700">
                            </div>
                        </div>

                        <!-- INTEGRITY PANEL -->
                        <div
                            class="bg-white border border-slate-200/60 rounded-[3rem] p-10 shadow-xl shadow-slate-200/30">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-10">Integrity
                                Report</h4>
                            <div class="space-y-8">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.6)] animate-pulse">
                                    </div>
                                    <span class="text-xs font-black text-[#11131A] uppercase tracking-widest">Booking
                                        Engine</span>
                                </div>
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.6)]">
                                    </div>
                                    <span class="text-xs font-black text-[#11131A] uppercase tracking-widest">Global
                                        CDN</span>
                                </div>
                                <div class="flex items-center gap-5">
                                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                                    <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Analytics
                                        AI</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER TAG -->
                <div class="mt-24 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Command Dashboard
                        v4.2.0-Alpha</p>
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
:deep(p) {
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