<script setup>
/**
 * Booking Control Center v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Queue Management: Real-time synchronization of session packets.
 * 2. Tactical Pipeline: Visual tracking of booking lifecycle stages.
 * 3. Authorization Hub: Secure deployment of status transitions.
 * 
 * @author Pial Mahmud (Lead Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    bookings: Array
});

// Protocol: Normalize raw status strings for system-wide consistency
const normalizeStatus = (status) => {
    const s = status ? status.toLowerCase().trim() : 'pending';
    return s === 'cancelled' ? 'canceled' : s;
};

// KPI Intelligence: Real-time operational metrics
const stats = computed(() => {
    const list = props.bookings ?? [];
    return {
        total: list.length,
        pending: list.filter(b => normalizeStatus(b.status) === 'pending').length,
        confirmed: list.filter(b => normalizeStatus(b.status) === 'confirmed').length,
        completed: list.filter(b => normalizeStatus(b.status) === 'completed').length,
    };
});

// Filtering Intelligence
const statusFilter = ref('all');
const filteredBookings = computed(() => {
    const list = props.bookings ?? [];
    if (statusFilter.value === 'all') return list;
    return list.filter(b => normalizeStatus(b.status) === statusFilter.value);
});

// Authorization Handler: Executes secure status transitions
const updateBookingStatus = (id, newStatus) => {
    if (confirm(`Authorize deployment of status: ${newStatus.toUpperCase()}?`)) {
        router.patch(route('admin.bookings.updateStatus', id), {
            status: newStatus
        }, { preserveScroll: true });
    }
};

// Visual Helper: Logic-based pipeline stage quantization
const getStage = (status) => {
    const s = normalizeStatus(status);
    if (s === 'pending') return 1;
    if (s === 'confirmed') return 2;
    if (s === 'completed') return 3;
    return 0; // Terminated or Discarded
};
</script>

<template>

    <Head title="Queue Management | Operational Intelligence" />

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

                <!-- EDITORIAL HEADER SECTION -->
                <div class="flex flex-col xl:flex-row xl:items-end justify-between mb-20 gap-12">
                    <div class="space-y-8 animate-slideUp">
                        <div class="flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Queue
                                    Integrity Secured</span>
                            </div>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            Queue <br />
                            <span class="text-indigo-600">Control.</span>
                        </h1>
                        <p
                            class="text-slate-500 max-w-lg text-lg font-medium leading-relaxed uppercase tracking-widest">
                            Authorized oversight of synchronized session packets and operational deployment.
                        </p>
                    </div>

                    <!-- TACTICAL KPI PODS -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 w-full xl:w-auto animate-fadeIn">
                        <div
                            class="bg-[#09090B] p-8 rounded-[3rem] text-white shadow-2xl shadow-indigo-900/20 border border-white/5 group">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.5em] mb-4">Packets</p>
                            <h3
                                class="text-5xl font-black tracking-tighter group-hover:text-indigo-400 transition-colors">
                                {{ stats.total }}</h3>
                        </div>
                        <div
                            class="bg-white border border-slate-200/60 p-8 rounded-[3rem] shadow-xl border-t-[10px] border-t-amber-500">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em] mb-4">Pending</p>
                            <h3 class="text-5xl font-black text-[#09090B] tracking-tighter">{{ stats.pending }}</h3>
                        </div>
                        <div
                            class="hidden md:block bg-white border border-slate-200/60 p-8 rounded-[3rem] shadow-xl border-t-[10px] border-t-emerald-500">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em] mb-4">Verified</p>
                            <h3 class="text-5xl font-black text-[#09090B] tracking-tighter">{{ stats.confirmed }}</h3>
                        </div>
                    </div>
                </div>

                <!-- FLOATING CONTROL DOCK (FILTER) -->
                <div class="flex justify-center mb-16 overflow-x-auto no-scrollbar py-2 animate-fadeIn">
                    <div
                        class="inline-flex items-center gap-2 p-2.5 bg-white/80 backdrop-blur-xl border border-slate-200 shadow-2xl shadow-slate-200/40 rounded-full">
                        <button v-for="tab in ['all', 'pending', 'confirmed', 'completed', 'canceled']" :key="tab"
                            @click="statusFilter = tab" :class="[
                                'px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-[0.3em] transition-all duration-500 whitespace-nowrap cursor-pointer',
                                statusFilter === tab ? 'bg-[#09090B] text-white shadow-xl scale-[1.05]' : 'text-slate-400 hover:text-[#09090B] hover:bg-slate-50'
                            ]">
                            {{ tab }}
                        </button>
                    </div>
                </div>

                <!-- SESSION PACKET MODULES -->
                <TransitionGroup name="list" tag="div" class="space-y-8" v-if="filteredBookings.length > 0">
                    <div v-for="booking in filteredBookings" :key="booking.id"
                        class="group relative bg-white border border-slate-200/80 rounded-[4rem] p-10 lg:p-14 transition-all duration-700 hover:border-indigo-500/40 hover:shadow-[0_40px_100px_-20px_rgba(79,70,229,0.15)]">

                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-12">

                            <!-- IDENTITY MODULE -->
                            <div class="flex items-center gap-10 flex-1 min-w-0">
                                <div
                                    class="w-24 h-20 shrink-0 rounded-[2rem] bg-[#09090B] flex items-center justify-center font-black text-4xl text-white shadow-2xl group-hover:bg-indigo-600 group-hover:rotate-6 transition-all duration-700">
                                    {{ (booking.user?.name?.charAt(0) || 'U').toUpperCase() }}
                                </div>
                                <div class="min-w-0">
                                    <h4
                                        class="text-4xl font-black text-[#09090B] tracking-tighter truncate leading-none uppercase">
                                        {{ booking.user?.name || 'Client Unspecified' }}</h4>
                                    <div class="flex items-center gap-5 mt-5">
                                        <span class="text-xs font-black text-indigo-600 uppercase tracking-[0.4em]">{{
                                            booking.service?.name }}</span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                        <span
                                            class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Packet
                                            ID: #{{ booking.id }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- TIMELINE READOUT MODULE -->
                            <div
                                class="grid grid-cols-2 xl:flex xl:flex-col gap-10 xl:gap-6 xl:w-64 p-10 xl:p-0 rounded-[3rem] bg-slate-50/50 xl:bg-transparent shadow-inner xl:shadow-none border border-slate-100 xl:border-none">
                                <div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] mb-3">
                                        Deployment Date</p>
                                    <div
                                        class="flex items-center gap-3 text-xl font-black text-[#09090B] tracking-tight">
                                        <svg class="w-5 h-5 text-indigo-500 opacity-40" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ booking.booking_date }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] mb-3">
                                        Operational Slot</p>
                                    <div
                                        class="flex items-center gap-3 text-xl font-black text-indigo-600 tracking-tight">
                                        <svg class="w-5 h-5 text-indigo-400 opacity-40" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ booking.start_time }}
                                    </div>
                                </div>
                            </div>

                            <!-- PIPELINE RAIL -->
                            <div class="w-full xl:w-56 flex flex-col gap-5">
                                <div class="flex gap-2.5">
                                    <div v-for="n in 3" :key="n"
                                        class="h-2 flex-1 rounded-full bg-slate-100 transition-all duration-1000 overflow-hidden shadow-inner">
                                        <div class="h-full bg-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.8)]"
                                            :style="{ width: getStage(booking.status) >= n ? '100%' : '0%' }"></div>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-between items-center text-[10px] font-black uppercase tracking-[0.4em]">
                                    <span class="text-slate-300 italic">Sync Phase {{ getStage(booking.status)
                                    }}/3</span>
                                    <span :class="{
                                        'text-amber-500': normalizeStatus(booking.status) === 'pending',
                                        'text-emerald-500': normalizeStatus(booking.status) === 'confirmed',
                                        'text-indigo-600': normalizeStatus(booking.status) === 'completed',
                                        'text-rose-500': normalizeStatus(booking.status) === 'canceled',
                                    }">{{ booking.status }}</span>
                                </div>
                            </div>

                            <!-- TACTICAL ACTIONS -->
                            <div
                                class="flex flex-col sm:flex-row xl:flex-col gap-4 w-full xl:w-auto pt-10 xl:pt-0 border-t border-slate-100 xl:border-t-0">
                                <template v-if="normalizeStatus(booking.status) === 'pending'">
                                    <button @click="updateBookingStatus(booking.id, 'confirmed')"
                                        class="w-full xl:w-56 py-6 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] hover:bg-indigo-600 transition-all shadow-[0_20px_50px_rgba(0,0,0,0.2)] active:scale-95 cursor-pointer">
                                        Authorize Sync
                                    </button>
                                    <button @click="updateBookingStatus(booking.id, 'canceled')"
                                        class="w-full xl:w-56 py-6 bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] hover:text-rose-600 hover:border-rose-200 transition-all active:scale-95 cursor-pointer">
                                        Discard Packet
                                    </button>
                                </template>

                                <template v-else-if="normalizeStatus(booking.status) === 'confirmed'">
                                    <button @click="updateBookingStatus(booking.id, 'completed')"
                                        class="w-full xl:w-56 py-7 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] hover:bg-emerald-500 transition-all shadow-[0_20px_50px_rgba(16,185,129,0.3)] active:scale-95 cursor-pointer">
                                        Finalize Deployment
                                    </button>
                                </template>

                                <div v-else
                                    class="w-full xl:w-56 py-6 px-4 bg-slate-50 border border-slate-200/60 rounded-[1.5rem] text-center shadow-inner">
                                    <span
                                        class="text-[9px] font-black text-slate-300 uppercase tracking-[0.5em]">Process
                                        Terminated</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </TransitionGroup>

                <!-- EDITORIAL EMPTY STATE -->
                <div v-else
                    class="text-center py-64 bg-white/60 backdrop-blur-xl border border-slate-200/80 rounded-[6rem] shadow-sm animate-fadeIn">
                    <div
                        class="w-24 h-24 rounded-[2.5rem] bg-[#09090B] flex items-center justify-center mx-auto mb-10 shadow-2xl">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-5xl font-black text-[#09090B] uppercase tracking-tighter">Queue Synchronized.</h3>
                    <p class="text-slate-400 text-sm mt-6 uppercase tracking-[0.6em] font-black leading-relaxed">No
                        pending session
                        packets detected in local cluster.</p>
                </div>

                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1.5em]">Command Infrastructure
                        v4.2.0-Alpha.01 |
                        Secured by Sentinel Node</p>
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
:deep(button),
:deep(span),
:deep(p) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

/* Pipeline Flow Animations */
.list-enter-active,
.list-leave-active {
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(60px) scale(0.95);
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.shadow-inner {
    box-shadow: inset 0 2px 8px 0 rgba(0, 0, 0, 0.06);
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