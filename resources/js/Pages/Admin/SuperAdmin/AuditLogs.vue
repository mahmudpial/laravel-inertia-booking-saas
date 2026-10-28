<script setup>
import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    events: {
        type: Array,
        default: () => []
    },
});

const filterType = ref('all'); // 'all', 'business', 'booking'

const filteredEvents = computed(() => {
    if (filterType.value === 'business') {
        return props.events.filter(e => e.type?.startsWith('business'));
    }
    if (filterType.value === 'booking') {
        return props.events.filter(e => e.type?.startsWith('booking'));
    }
    return props.events;
});

const typeMeta = (type) => {
    if (type?.startsWith('business')) {
        return { badge: 'bg-indigo-50 text-indigo-700 border-indigo-200', dot: 'bg-indigo-500', label: 'Workspace' };
    }
    if (type === 'booking.confirmed') {
        return { badge: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-500', label: 'Confirmed' };
    }
    if (type === 'booking.completed') {
        return { badge: 'bg-blue-50 text-blue-700 border-blue-200', dot: 'bg-blue-500', label: 'Completed' };
    }
    if (type === 'booking.canceled') {
        return { badge: 'bg-rose-50 text-rose-700 border-rose-200', dot: 'bg-rose-500', label: 'Canceled' };
    }
    return { badge: 'bg-amber-50 text-amber-700 border-amber-200', dot: 'bg-amber-500', label: 'Pending' };
};
</script>

<template>
    <Head title="Security & Activity Audit Ledger — Sovereign Control" />

    <SovereignLayout>
        <div class="space-y-8 max-w-5xl mx-auto pb-16">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            Live Stream: Last {{ events.length }} events
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                        Security & Activity Audit Ledger
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Chronological record of platform-wide workspace registrations, status changes, and booking lifecycle transitions.
                    </p>
                </div>

                <!-- Filter Pills -->
                <div class="flex items-center gap-1.5 bg-white border border-slate-200 p-1.5 rounded-xl shadow-xs">
                    <button
                        @click="filterType = 'all'"
                        :class="[
                            'px-3 py-1 rounded-lg text-xs font-semibold transition-all',
                            filterType === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'
                        ]"
                    >
                        All Events
                    </button>
                    <button
                        @click="filterType = 'business'"
                        :class="[
                            'px-3 py-1 rounded-lg text-xs font-semibold transition-all',
                            filterType === 'business' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'
                        ]"
                    >
                        Workspaces
                    </button>
                    <button
                        @click="filterType = 'booking'"
                        :class="[
                            'px-3 py-1 rounded-lg text-xs font-semibold transition-all',
                            filterType === 'booking' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'
                        ]"
                    >
                        Bookings
                    </button>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
                <div v-if="filteredEvents.length > 0" class="space-y-6">
                    <div
                        v-for="(event, idx) in filteredEvents"
                        :key="idx"
                        class="flex items-start gap-4 pb-6 border-b border-slate-100 last:border-b-0 last:pb-0"
                    >
                        <!-- Timeline Dot / Icon -->
                        <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0 mt-0.5">
                            <span :class="['w-2.5 h-2.5 rounded-full', typeMeta(event.type).dot]"></span>
                        </div>

                        <!-- Content Block -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-bold text-slate-900">{{ event.label }}</span>
                                    <span :class="['px-2 py-0.5 rounded-md text-[10px] font-semibold border', typeMeta(event.type).badge]">
                                        {{ typeMeta(event.type).label }}
                                    </span>
                                </div>
                                <span class="text-xs text-slate-400 font-mono">{{ event.timestamp }}</span>
                            </div>

                            <p class="text-xs text-slate-600 mt-1">
                                <span class="font-medium text-slate-800">{{ event.detail }}</span>
                                <span class="text-slate-400 ml-2">by {{ event.actor }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-16">
                    <p class="text-sm text-slate-500 font-medium">No activity events recorded yet.</p>
                </div>
            </div>
        </div>
    </SovereignLayout>
</template>
