<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    bookings: Array
});

// Clean up raw status strings from backend
const normalizeStatus = (status) => {
    const s = status ? status.toLowerCase().trim() : 'pending';
    return s === 'cancelled' ? 'canceled' : s;
};

// Compute KPI statistics for the top cards
const stats = computed(() => {
    const list = props.bookings ?? [];
    return {
        total: list.length,
        pending: list.filter(b => normalizeStatus(b.status) === 'pending').length,
        confirmed: list.filter(b => normalizeStatus(b.status) === 'confirmed').length,
        completed: list.filter(b => normalizeStatus(b.status) === 'completed').length,
    };
});

// Filtering Logic
const statusFilter = ref('all');
const filteredBookings = computed(() => {
    const list = props.bookings ?? [];
    if (statusFilter.value === 'all') return list;
    return list.filter(b => normalizeStatus(b.status) === statusFilter.value);
});

// Status Mutation Handler
const updateBookingStatus = (id, newStatus) => {
    if (confirm(`Authorize status deployment: ${newStatus.toUpperCase()}?`)) {
        router.patch(route('admin.bookings.updateStatus', id), {
            status: newStatus
        }, { preserveScroll: true });
    }
};

// Visual Helper: Pipeline Stage Calculation
const getStage = (status) => {
    const s = normalizeStatus(status);
    if (s === 'pending') return 1;
    if (s === 'confirmed') return 2;
    if (s === 'completed') return 3;
    return 0; // Canceled or unknown
};
</script>

<template>

    <Head title="Booking Control Center" />

    <AuthenticatedLayout>
        <!-- SIGNATURE RADIAL GRID BACKGROUND -->
        <div
            class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] pb-32">

            <!-- EDITORIAL HEADER SECTION -->
            <div class="border-b border-slate-200/60 bg-white/80 backdrop-blur-xl">
                <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24">
                    <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-12">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-3 w-3 rounded-full bg-indigo-600 animate-ping"></span>
                                <span class="text-xs font-black uppercase tracking-[0.4em] text-slate-400">Live
                                    Operation Feed</span>
                            </div>
                            <h1 class="text-5xl lg:text-7xl font-black text-[#11131A] tracking-tighter leading-[0.85]">
                                Control <br />
                                <span class="text-indigo-600">Bookings.</span>
                            </h1>
                        </div>

                        <!-- RESPONSIVE STATS HUB -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full xl:w-auto">
                            <div class="bg-[#11131A] p-8 rounded-[2.5rem] text-white shadow-2xl shadow-indigo-900/20">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-3">Total
                                </p>
                                <h3 class="text-4xl font-black">{{ stats.total }}</h3>
                            </div>
                            <div
                                class="bg-white border border-slate-200 p-8 rounded-[2.5rem] shadow-sm border-t-amber-500 border-t-8">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3">Queue
                                </p>
                                <h3 class="text-4xl font-black text-[#11131A]">{{ stats.pending }}</h3>
                            </div>
                            <div
                                class="hidden md:block bg-white border border-slate-200 p-8 rounded-[2.5rem] shadow-sm border-t-emerald-500 border-t-8">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3">
                                    Confirmed</p>
                                <h3 class="text-4xl font-black text-[#11131A]">{{ stats.confirmed }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 mt-16">

                <!-- STICKY FILTER DOCK -->
                <div class="flex justify-center mb-16 overflow-x-auto no-scrollbar py-2">
                    <div
                        class="inline-flex items-center gap-2 p-2.5 bg-white border border-slate-200 shadow-xl shadow-slate-200/40 rounded-full">
                        <button v-for="tab in ['all', 'pending', 'confirmed', 'completed', 'canceled']" :key="tab"
                            @click="statusFilter = tab" :class="[
                                'px-8 py-3 rounded-full text-xs font-black uppercase tracking-[0.2em] transition-all duration-500 whitespace-nowrap cursor-pointer',
                                statusFilter === tab ? 'bg-[#11131A] text-white shadow-lg scale-[1.05]' : 'text-slate-400 hover:text-[#11131A] hover:bg-slate-50'
                            ]">
                            {{ tab }}
                        </button>
                    </div>
                </div>

                <!-- LIQUID DATA MODULES -->
                <TransitionGroup name="list" tag="div" class="space-y-8" v-if="filteredBookings.length > 0">
                    <div v-for="booking in filteredBookings" :key="booking.id"
                        class="group bg-white border border-slate-200/80 rounded-[3.5rem] p-8 lg:p-12 transition-all duration-700 hover:border-indigo-500/40 hover:shadow-[0_40px_100px_-20px_rgba(79,70,229,0.12)]">

                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-12">

                            <!-- IDENTITY BLOCK -->
                            <div class="flex items-center gap-8 flex-1">
                                <div
                                    class="w-24 h-20 shrink-0 rounded-[2rem] bg-slate-50 border border-slate-100 flex items-center justify-center font-black text-4xl text-slate-200 group-hover:bg-[#11131A] group-hover:text-white group-hover:shadow-2xl transition-all duration-700">
                                    {{ booking.user?.name?.charAt(0).toUpperCase() || 'U' }}
                                </div>
                                <div class="min-w-0">
                                    <h4
                                        class="text-3xl font-black text-[#11131A] tracking-tight truncate leading-tight">
                                        {{ booking.user?.name || 'Client Unspecified' }}</h4>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em]">{{
                                            booking.service?.name }}</span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                        <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">SID-{{
                                            booking.id }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- TIMELINE GRID (MOBILE OPTIMIZED) -->
                            <div
                                class="grid grid-cols-2 lg:flex lg:flex-col gap-8 lg:gap-4 lg:w-56 p-8 lg:p-0 rounded-[2.5rem] bg-slate-50/50 lg:bg-transparent">
                                <div>
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mb-2">
                                        Schedule</p>
                                    <div class="flex items-center gap-3 text-lg font-black text-[#11131A]">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ booking.booking_date }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mb-2">
                                        Time-Slot</p>
                                    <div class="flex items-center gap-3 text-lg font-black text-indigo-600">
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ booking.start_time }}
                                    </div>
                                </div>
                            </div>

                            <!-- PIPELINE CONTROL -->
                            <div class="w-full lg:w-48 flex flex-col gap-4">
                                <div class="flex gap-2">
                                    <div v-for="n in 3" :key="n"
                                        class="h-2 flex-1 rounded-full bg-slate-100 transition-all duration-1000 overflow-hidden">
                                        <div class="h-full bg-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.6)]"
                                            :style="{ width: getStage(booking.status) >= n ? '100%' : '0%' }"></div>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-between items-center text-[10px] font-black uppercase tracking-[0.3em]">
                                    <span class="text-slate-300">Phase Index</span>
                                    <span :class="{
                                        'text-amber-500': normalizeStatus(booking.status) === 'pending',
                                        'text-emerald-500': normalizeStatus(booking.status) === 'confirmed',
                                        'text-indigo-600': normalizeStatus(booking.status) === 'completed',
                                        'text-rose-500': normalizeStatus(booking.status) === 'canceled',
                                    }">{{ booking.status }}</span>
                                </div>
                            </div>

                            <!-- TACTICAL ACTION STACK -->
                            <div
                                class="flex flex-col sm:flex-row lg:flex-col gap-3 w-full lg:w-auto pt-10 lg:pt-0 border-t border-slate-100 lg:border-t-0">
                                <template v-if="normalizeStatus(booking.status) === 'pending'">
                                    <button @click="updateBookingStatus(booking.id, 'confirmed')"
                                        class="w-full lg:w-44 py-5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-indigo-600 transition-all shadow-2xl active:scale-95 cursor-pointer">
                                        Confirm
                                    </button>
                                    <button @click="updateBookingStatus(booking.id, 'canceled')"
                                        class="w-full lg:w-44 py-5 bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:text-rose-600 hover:border-rose-200 transition-all active:scale-95 cursor-pointer">
                                        Discard
                                    </button>
                                </template>

                                <template v-else-if="normalizeStatus(booking.status) === 'confirmed'">
                                    <button @click="updateBookingStatus(booking.id, 'completed')"
                                        class="w-full lg:w-44 py-6 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-emerald-500 transition-all shadow-2xl shadow-emerald-500/20 active:scale-95 cursor-pointer">
                                        Finalize
                                    </button>
                                </template>

                                <div v-else
                                    class="w-full lg:w-44 py-5 px-4 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]">Node
                                        Terminated</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </TransitionGroup>

                <!-- EDITORIAL EMPTY STATE -->
                <div v-else
                    class="text-center py-48 bg-white/60 backdrop-blur-xl border border-slate-200/80 rounded-[4rem] shadow-sm">
                    <h3 class="text-4xl font-black text-[#11131A] uppercase tracking-tighter">Queue Empty.</h3>
                    <p class="text-slate-400 text-xs mt-4 uppercase tracking-[0.4em] font-black">All session packets are
                        currently
                        processed.</p>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(button),
:deep(span) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

/* Pipeline Flow Animations */
.list-enter-active,
.list-leave-active {
    transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(40px) scale(0.98);
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>