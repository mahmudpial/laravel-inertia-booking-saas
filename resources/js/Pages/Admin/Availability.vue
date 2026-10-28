<script setup>
/**
 * SmartBooking Working Hours & Availability
 * Weekly Schedule Management Interface
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    availabilities: Array
});

const daysMap = [
    { id: 0, name: 'Sunday', short: 'Sun' },
    { id: 1, name: 'Monday', short: 'Mon' },
    { id: 2, name: 'Tuesday', short: 'Tue' },
    { id: 3, name: 'Wednesday', short: 'Wed' },
    { id: 4, name: 'Thursday', short: 'Thu' },
    { id: 5, name: 'Friday', short: 'Fri' },
    { id: 6, name: 'Saturday', short: 'Sat' },
];

const form = useForm({
    availabilities: props.availabilities?.map(a => ({
        id: a.id,
        business_id: a.business_id,
        day_of_week: a.day_of_week,
        start_time: a.start_time ? a.start_time.substring(0, 5) : '09:00',
        end_time: a.end_time ? a.end_time.substring(0, 5) : '18:00',
        is_open: Boolean(a.is_open),
    })) || []
});

const getDayName = (dayOfWeek) => {
    return daysMap.find(d => d.id === dayOfWeek)?.name || `Day ${dayOfWeek}`;
};

const applyStandardWeekdays = () => {
    form.availabilities.forEach(a => {
        if (a.day_of_week >= 1 && a.day_of_week <= 5) {
            a.is_open = true;
            a.start_time = '09:00';
            a.end_time = '18:00';
        }
    });
};

const submit = () => {
    form.put(route('admin.availability.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Working Hours & Schedule | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-6 max-w-4xl">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Working Hours & Schedule
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Define your weekly business hours. Open intervals dictate dynamically bookable client time slots.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="applyStandardWeekdays"
                        class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 shadow-xs transition-colors"
                        title="Set Monday-Friday to 9:00 AM - 6:00 PM"
                    >
                        Standard 9-6 Weekdays
                    </button>
                </div>
            </div>

            <!-- Schedule Configuration Card -->
            <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200/60">
                    <h2 class="text-base font-bold text-slate-900 font-display">Weekly Operating Hours</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Toggle days off and adjust opening / closing windows.</p>
                </div>

                <div class="divide-y divide-slate-100">
                    <div
                        v-for="(item, index) in form.availabilities"
                        :key="item.day_of_week"
                        :class="[
                            'p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors',
                            item.is_open ? 'bg-white' : 'bg-slate-50/60'
                        ]"
                    >
                        <!-- Left: Day Name & Open/Closed Toggle -->
                        <div class="flex items-center gap-4 min-w-[180px]">
                            <!-- Switch -->
                            <button
                                type="button"
                                @click="item.is_open = !item.is_open"
                                :class="[
                                    'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none',
                                    item.is_open ? 'bg-brand-600' : 'bg-slate-200'
                                ]"
                            >
                                <span
                                    :class="[
                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out',
                                        item.is_open ? 'translate-x-5' : 'translate-x-0'
                                    ]"
                                />
                            </button>

                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900">{{ getDayName(item.day_of_week) }}</span>
                                <span :class="item.is_open ? 'text-emerald-600' : 'text-slate-400'" class="text-[11px] font-semibold">
                                    {{ item.is_open ? 'Open for Bookings' : 'Closed' }}
                                </span>
                            </div>
                        </div>

                        <!-- Right: Time Inputs -->
                        <div v-if="item.is_open" class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <label class="text-xs text-slate-400 font-medium">Opens:</label>
                                <input
                                    type="time"
                                    v-model="item.start_time"
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 shadow-2xs"
                                />
                            </div>

                            <span class="text-slate-300 font-bold">&ndash;</span>

                            <div class="flex items-center gap-2">
                                <label class="text-xs text-slate-400 font-medium">Closes:</label>
                                <input
                                    type="time"
                                    v-model="item.end_time"
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 shadow-2xs"
                                />
                            </div>
                        </div>

                        <div v-else class="text-xs font-medium text-slate-400 italic">
                            No appointments will be generated on this day.
                        </div>
                    </div>
                </div>

                <!-- Footer Save Action -->
                <div class="p-5 sm:p-6 bg-slate-50/75 border-t border-slate-200/60 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        Changes will take effect immediately on public booking slots.
                    </p>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/25 transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        <span v-if="form.processing">Saving Schedule...</span>
                        <span v-else>Save Working Hours</span>
                    </button>
                </div>
            </form>

        </div>
    </AuthenticatedLayout>
</template>