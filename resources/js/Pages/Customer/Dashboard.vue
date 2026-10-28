<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    bookings: {
        type: Array,
        default: () => []
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, upcoming: 0 })
    }
});

const activeTab = ref('all'); // 'all', 'upcoming', 'past', 'canceled'
const isConfirmingCancel = ref(false);
const selectedBooking = ref(null);
const isCancelling = ref(false);

const filteredBookings = computed(() => {
    if (activeTab.value === 'upcoming') {
        return props.bookings.filter(b => b.is_upcoming && b.status !== 'Canceled');
    }
    if (activeTab.value === 'past') {
        return props.bookings.filter(b => !b.is_upcoming && b.status !== 'Canceled');
    }
    if (activeTab.value === 'canceled') {
        return props.bookings.filter(b => b.status === 'Canceled');
    }
    return props.bookings;
});

const confirmedCount = computed(() => props.bookings.filter(b => b.status === 'Confirmed' && b.is_upcoming).length);
const completedCount = computed(() => props.bookings.filter(b => !b.is_upcoming && b.status !== 'Canceled').length);
const canceledCount = computed(() => props.bookings.filter(b => b.status === 'Canceled').length);

const openCancelModal = (booking) => {
    selectedBooking.value = booking;
    isConfirmingCancel.value = true;
};

const closeCancelModal = () => {
    isConfirmingCancel.value = false;
    selectedBooking.value = null;
};

const handleCancel = () => {
    if (!selectedBooking.value) return;
    isCancelling.value = true;
    router.patch(route('customer.bookings.cancel', selectedBooking.value.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            closeCancelModal();
            isCancelling.value = false;
        },
        onError: () => {
            isCancelling.value = false;
        }
    });
};

const downloadIcs = (booking) => {
    const title = `${booking.service_name} at ${booking.business_name}`;
    const description = `Appointment with ${booking.staff_name} for ${booking.service_name}`;
    const icsContent = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//SmartBooking//EN',
        'BEGIN:VEVENT',
        `SUMMARY:${title}`,
        `DESCRIPTION:${description}`,
        `STATUS:CONFIRMED`,
        'END:VEVENT',
        'END:VCALENDAR'
    ].join('\r\n');

    const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${booking.service_name.toLowerCase().replace(/\s+/g, '-')}-appointment.ics`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="My Appointments — SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-8 max-w-6xl mx-auto pb-16">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                            Client Portal
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                        My Appointments
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Track upcoming sessions, review past reservations, or re-book with your favorite specialists.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold text-xs transition-all shadow-sm active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Book New Session</span>
                    </Link>
                </div>
            </div>

            <!-- KPI Metric Chips -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Upcoming Active</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">{{ stats.upcoming }}</div>
                        <span class="text-[11px] text-brand-600 font-medium">Ready for check-in</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Completed Sessions</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">{{ completedCount }}</div>
                        <span class="text-[11px] text-emerald-600 font-medium">Past bookings</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Reservations</span>
                        <div class="text-2xl font-display font-bold text-slate-900 mt-1">{{ stats.total }}</div>
                        <span class="text-[11px] text-slate-500 font-medium">Lifetime bookings</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                <button
                    @click="activeTab = 'all'"
                    :class="[
                        'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all',
                        activeTab === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
                    ]"
                >
                    All Bookings ({{ bookings.length }})
                </button>
                <button
                    @click="activeTab = 'upcoming'"
                    :class="[
                        'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all',
                        activeTab === 'upcoming' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
                    ]"
                >
                    Upcoming ({{ stats.upcoming }})
                </button>
                <button
                    @click="activeTab = 'past'"
                    :class="[
                        'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all',
                        activeTab === 'past' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
                    ]"
                >
                    Past ({{ completedCount }})
                </button>
                <button
                    @click="activeTab = 'canceled'"
                    :class="[
                        'px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all',
                        activeTab === 'canceled' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
                    ]"
                >
                    Canceled ({{ canceledCount }})
                </button>
            </div>

            <!-- Bookings List -->
            <div v-if="filteredBookings.length > 0" class="space-y-4">
                <div
                    v-for="booking in filteredBookings"
                    :key="booking.id"
                    class="bg-white border border-slate-200/80 hover:border-slate-300 rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-200"
                >
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <!-- Left: Studio & Service Identity -->
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 text-white flex items-center justify-center font-display font-bold text-xl shrink-0 shadow-sm shadow-brand-500/20">
                                {{ (booking.business_name || 'B').charAt(0).toUpperCase() }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h3 class="font-display font-bold text-lg text-slate-900 truncate">
                                        {{ booking.business_name }}
                                    </h3>
                                    <!-- Status Pill -->
                                    <span
                                        :class="[
                                            'px-2.5 py-0.5 rounded-full text-[11px] font-semibold border',
                                            booking.status === 'Confirmed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '',
                                            booking.status === 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : '',
                                            booking.status === 'Completed' ? 'bg-blue-50 text-blue-700 border-blue-200' : '',
                                            booking.status === 'Canceled' ? 'bg-rose-50 text-rose-700 border-rose-200' : ''
                                        ]"
                                    >
                                        {{ booking.status }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                    <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md">
                                        {{ booking.service_name }}
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-xs text-slate-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ booking.staff_name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Middle: Date & Time Readout -->
                        <div class="flex items-center gap-6 sm:gap-8 bg-slate-50/80 p-3.5 sm:px-6 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Date</span>
                                <div class="flex items-center gap-1.5 text-sm font-semibold text-slate-800">
                                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ booking.date }}
                                </div>
                            </div>
                            <div class="w-px h-8 bg-slate-200"></div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Time</span>
                                <div class="flex items-center gap-1.5 text-sm font-semibold text-brand-700">
                                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ booking.time }}
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-2 justify-end">
                            <!-- Add to Calendar (if confirmed & upcoming) -->
                            <button
                                v-if="booking.status === 'Confirmed' && booking.is_upcoming"
                                type="button"
                                @click="downloadIcs(booking)"
                                title="Export to Calendar (.ics)"
                                class="px-3 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center gap-1.5 shadow-xs"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="hidden sm:inline">Add to Cal</span>
                            </button>

                            <!-- Re-book link -->
                            <Link
                                :href="`/b/${booking.business_slug}`"
                                class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-brand-600 transition-all shadow-xs flex items-center gap-1.5"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Re-book</span>
                            </Link>

                            <!-- Cancel Button (if active & upcoming) -->
                            <button
                                v-if="booking.status !== 'Canceled' && booking.is_upcoming"
                                type="button"
                                @click="openCancelModal(booking)"
                                class="px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-all"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20 bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xs">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-display font-bold text-slate-900">
                    {{ activeTab === 'all' ? 'No appointments yet' : `No ${activeTab} appointments found` }}
                </h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ activeTab === 'all' ? 'You have no booked appointments. Discover studios and schedule your next session online.' : 'Switch filter tabs or schedule a new booking.' }}
                </p>
                <div class="mt-5">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-semibold transition-all shadow-sm"
                    >
                        <span>Browse Verified Studios</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Cancel Confirmation Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="isConfirmingCancel" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="closeCancelModal"></div>

                    <div class="relative bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-200 z-10 animate-in fade-in zoom-in-95 duration-200">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>

                        <div class="text-center">
                            <h3 class="text-lg font-display font-bold text-slate-900">Cancel Appointment?</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Are you sure you want to cancel your appointment for
                                <strong class="text-slate-800">{{ selectedBooking?.service_name }}</strong> at
                                <strong class="text-slate-800">{{ selectedBooking?.business_name }}</strong> on {{ selectedBooking?.date }}?
                                This slot will be released back to the schedule.
                            </p>
                        </div>

                        <div class="flex items-center gap-3 mt-6">
                            <button
                                type="button"
                                @click="closeCancelModal"
                                class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors"
                            >
                                Keep Appointment
                            </button>
                            <button
                                type="button"
                                @click="handleCancel"
                                :disabled="isCancelling"
                                class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition-all shadow-sm disabled:opacity-50 flex items-center justify-center gap-1.5"
                            >
                                <svg v-if="isCancelling" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>{{ isCancelling ? 'Cancelling...' : 'Confirm Cancellation' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AuthenticatedLayout>
</template>