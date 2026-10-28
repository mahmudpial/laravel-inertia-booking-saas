<script setup>
/**
 * SmartBooking Bookings & Appointment Queue
 * Modern SaaS Management Interface
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    bookings: Array
});

const searchQuery = ref('');
const statusFilter = ref('all');

const normalizeStatus = (status) => {
    const s = status ? status.toLowerCase().trim() : 'pending';
    return s === 'cancelled' ? 'canceled' : s;
};

const stats = computed(() => {
    const list = props.bookings ?? [];
    return {
        total: list.length,
        pending: list.filter(b => normalizeStatus(b.status) === 'pending').length,
        confirmed: list.filter(b => normalizeStatus(b.status) === 'confirmed').length,
        completed: list.filter(b => normalizeStatus(b.status) === 'completed').length,
        canceled: list.filter(b => normalizeStatus(b.status) === 'canceled').length,
    };
});

const filteredBookings = computed(() => {
    let list = props.bookings ?? [];
    if (statusFilter.value !== 'all') {
        list = list.filter(b => normalizeStatus(b.status) === statusFilter.value);
    }
    if (searchQuery.value.trim() !== '') {
        const q = searchQuery.value.toLowerCase().trim();
        list = list.filter(b => 
            (b.user?.name && b.user.name.toLowerCase().includes(q)) ||
            (b.user?.email && b.user.email.toLowerCase().includes(q)) ||
            (b.service?.name && b.service.name.toLowerCase().includes(q)) ||
            (b.staff?.name && b.staff.name.toLowerCase().includes(q))
        );
    }
    return list;
});

const updateStatus = (id, newStatus) => {
    router.patch(route('admin.bookings.updateStatus', id), {
        status: newStatus
    }, {
        preserveScroll: true
    });
};

const getBadgeClasses = (status) => {
    const s = normalizeStatus(status);
    if (s === 'confirmed') return 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
    if (s === 'pending') return 'bg-amber-50 text-amber-700 border-amber-200/70';
    if (s === 'completed') return 'bg-indigo-50 text-indigo-700 border-indigo-200/70';
    if (s === 'canceled') return 'bg-rose-50 text-rose-700 border-rose-200/70';
    return 'bg-slate-50 text-slate-700 border-slate-200';
};
</script>

<template>
    <Head title="Bookings & Appointments | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Bookings & Queue
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Manage incoming client appointments, adjust statuses, and track scheduled sessions.
                    </p>
                </div>
            </div>

            <!-- Status Filter Tabs / KPI Summary -->
            <div class="flex flex-wrap items-center gap-2">
                <button
                    @click="statusFilter = 'all'"
                    :class="[
                        'px-4 py-2 rounded-xl text-xs font-semibold transition-all border flex items-center gap-2',
                        statusFilter === 'all'
                            ? 'bg-slate-900 text-white border-slate-900 shadow-sm'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'
                    ]"
                >
                    <span>All Bookings</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="statusFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                        {{ stats.total }}
                    </span>
                </button>

                <button
                    @click="statusFilter = 'pending'"
                    :class="[
                        'px-4 py-2 rounded-xl text-xs font-semibold transition-all border flex items-center gap-2',
                        statusFilter === 'pending'
                            ? 'bg-amber-600 text-white border-amber-600 shadow-sm'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'
                    ]"
                >
                    <span>Pending</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="statusFilter === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700'">
                        {{ stats.pending }}
                    </span>
                </button>

                <button
                    @click="statusFilter = 'confirmed'"
                    :class="[
                        'px-4 py-2 rounded-xl text-xs font-semibold transition-all border flex items-center gap-2',
                        statusFilter === 'confirmed'
                            ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'
                    ]"
                >
                    <span>Confirmed</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="statusFilter === 'confirmed' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700'">
                        {{ stats.confirmed }}
                    </span>
                </button>

                <button
                    @click="statusFilter = 'completed'"
                    :class="[
                        'px-4 py-2 rounded-xl text-xs font-semibold transition-all border flex items-center gap-2',
                        statusFilter === 'completed'
                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'
                    ]"
                >
                    <span>Completed</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="statusFilter === 'completed' ? 'bg-white/20 text-white' : 'bg-indigo-100 text-indigo-700'">
                        {{ stats.completed }}
                    </span>
                </button>

                <button
                    @click="statusFilter = 'canceled'"
                    :class="[
                        'px-4 py-2 rounded-xl text-xs font-semibold transition-all border flex items-center gap-2',
                        statusFilter === 'canceled'
                            ? 'bg-rose-600 text-white border-rose-600 shadow-sm'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'
                    ]"
                >
                    <span>Canceled</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="statusFilter === 'canceled' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-700'">
                        {{ stats.canceled }}
                    </span>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search by client name, email, service, or specialist..."
                    class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-xs transition-all"
                />
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Bookings Ledger Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div v-if="filteredBookings.length > 0" class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/75 border-b border-slate-200/60 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-6">Client</th>
                                <th class="py-3 px-6">Service & Add-ons</th>
                                <th class="py-3 px-6">Specialist</th>
                                <th class="py-3 px-6">Schedule</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="b in filteredBookings" :key="b.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ (b.user?.name || 'G').charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs">{{ b.user?.name || 'Guest Client' }}</p>
                                            <p class="text-[11px] text-slate-400">{{ b.user?.email || 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-semibold text-slate-900 text-xs">{{ b.service?.name || 'Standard Service' }}</p>
                                    <div v-if="b.addons?.length" class="flex flex-wrap gap-1 mt-1">
                                        <span v-for="addon in b.addons" :key="addon.id" class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-brand-50 text-brand-600 border border-brand-100">
                                            + {{ addon.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-600">
                                    {{ b.staff?.name || 'Any Staff' }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-600">
                                    <span class="font-semibold text-slate-900">{{ b.booking_date }}</span>
                                    <span class="text-slate-400 block text-[11px] mt-0.5">{{ b.start_time?.substring(0, 5) }} - {{ b.end_time?.substring(0, 5) }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize',
                                            getBadgeClasses(b.status)
                                        ]"
                                    >
                                        {{ normalizeStatus(b.status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Confirm Button -->
                                        <button
                                            v-if="normalizeStatus(b.status) === 'pending'"
                                            @click="updateStatus(b.id, 'confirmed')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors"
                                            title="Confirm Appointment"
                                        >
                                            Confirm
                                        </button>

                                        <!-- Complete Button -->
                                        <button
                                            v-if="normalizeStatus(b.status) === 'confirmed'"
                                            @click="updateStatus(b.id, 'completed')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors"
                                            title="Mark Completed"
                                        >
                                            Complete
                                        </button>

                                        <!-- Cancel Button -->
                                        <button
                                            v-if="normalizeStatus(b.status) !== 'canceled' && normalizeStatus(b.status) !== 'completed'"
                                            @click="updateStatus(b.id, 'canceled')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors"
                                            title="Cancel Appointment"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center py-16 px-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No bookings match criteria</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Try clearing search filters or changing status tab above.
                    </p>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>