<script setup>
/**
 * SmartBooking Calendar Timeline
 * High-Density SaaS Calendar Interface
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

const props = defineProps({
    bookings: Array,
    business: Object
});

const calendarRef = ref(null);
const activeView = ref('timeGridWeek');
const selectedEvent = ref(null);
const showEventModal = ref(false);

const calendarEvents = computed(() => (props.bookings || []).map(b => {
    const status = (b.status || 'pending').toLowerCase().trim();
    let bg = '#4f46e5'; // default indigo
    if (status === 'confirmed') bg = '#059669'; // emerald
    if (status === 'pending') bg = '#d97706'; // amber
    if (status === 'completed') bg = '#4f46e5'; // indigo
    if (status === 'canceled') bg = '#e11d48'; // rose

    return {
        id: b.id,
        title: b.user?.name || 'Guest Client',
        start: `${b.booking_date}T${b.start_time}`,
        end: `${b.booking_date}T${b.end_time}`,
        backgroundColor: bg,
        borderColor: bg,
        extendedProps: {
            service: b.service?.name || 'Standard Service',
            staff: b.staff?.name || 'Any Staff',
            status: status,
            notes: b.notes || '',
            date: b.booking_date,
            time: `${b.start_time?.substring(0, 5)} - ${b.end_time?.substring(0, 5)}`
        }
    };
}));

const handleEventDrop = (info) => {
    const newDate = info.event.startStr.split('T')[0];
    const newTime = info.event.startStr.split('T')[1].substring(0, 8);

    router.patch(route('admin.bookings.updateStatus', info.event.id), {
        booking_date: newDate,
        start_time: newTime
    }, {
        preserveScroll: true,
        onError: () => info.revert()
    });
};

const handleEventClick = (info) => {
    selectedEvent.value = {
        id: info.event.id,
        title: info.event.title,
        service: info.event.extendedProps.service,
        staff: info.event.extendedProps.staff,
        status: info.event.extendedProps.status,
        date: info.event.extendedProps.date,
        time: info.event.extendedProps.time,
        notes: info.event.extendedProps.notes
    };
    showEventModal.value = true;
};

// Navigation Handlers
const next = () => calendarRef.value?.getApi().next();
const prev = () => calendarRef.value?.getApi().prev();
const today = () => calendarRef.value?.getApi().today();

const changeView = (viewName) => {
    activeView.value = viewName;
    calendarRef.value?.getApi().changeView(viewName);
};

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: activeView.value,
    headerToolbar: false,
    editable: true,
    selectable: true,
    dayMaxEvents: 3,
    allDaySlot: false,
    slotMinTime: '07:00:00',
    slotMaxTime: '21:00:00',
    events: calendarEvents.value,
    eventDrop: handleEventDrop,
    eventClick: handleEventClick,
    height: 'auto',
    slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridian: 'short' },
    eventClassNames: 'cursor-pointer rounded-lg text-xs font-semibold p-1 shadow-xs',
}));
</script>

<template>
    <Head title="Calendar Timeline | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-6">

            <!-- Page Header & Calendar Controls -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Appointment Timeline
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Visualize daily and weekly appointment distribution with drag-and-drop rescheduling.
                    </p>
                </div>

                <!-- Calendar View & Navigation Toolbar -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Prev / Today / Next -->
                    <div class="flex items-center bg-white rounded-xl border border-slate-200 p-0.5 shadow-xs">
                        <button
                            @click="prev"
                            class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors"
                            title="Previous"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button
                            @click="today"
                            class="px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                        >
                            Today
                        </button>
                        <button
                            @click="next"
                            class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors"
                            title="Next"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- View Selector Tabs -->
                    <div class="flex items-center bg-white rounded-xl border border-slate-200 p-0.5 shadow-xs">
                        <button
                            @click="changeView('timeGridDay')"
                            :class="[
                                'px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors',
                                activeView === 'timeGridDay' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'
                            ]"
                        >
                            Day
                        </button>
                        <button
                            @click="changeView('timeGridWeek')"
                            :class="[
                                'px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors',
                                activeView === 'timeGridWeek' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'
                            ]"
                        >
                            Week
                        </button>
                        <button
                            @click="changeView('dayGridMonth')"
                            :class="[
                                'px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors',
                                activeView === 'dayGridMonth' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'
                            ]"
                        >
                            Month
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Container Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 overflow-hidden">
                <FullCalendar ref="calendarRef" :options="calendarOptions" />
            </div>

            <!-- Event Details Modal Dialog -->
            <Transition
                enter-active-class="transition-opacity duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showEventModal"
                    class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
                >
                    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-slate-100 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Appointment Details</span>
                                <h3 class="text-lg font-bold text-slate-900">{{ selectedEvent?.title }}</h3>
                            </div>
                            <button @click="showEventModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Service:</span>
                                <span class="font-semibold text-slate-800">{{ selectedEvent?.service }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Specialist:</span>
                                <span class="font-semibold text-slate-800">{{ selectedEvent?.staff }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Scheduled:</span>
                                <span class="font-semibold text-slate-800">{{ selectedEvent?.date }} @ {{ selectedEvent?.time }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Status:</span>
                                <span class="font-bold capitalize text-brand-600">{{ selectedEvent?.status }}</span>
                            </div>
                            <div v-if="selectedEvent?.notes" class="pt-2 border-t border-slate-200/70 text-slate-600">
                                <p class="text-[10px] uppercase font-bold text-slate-400">Notes:</p>
                                <p class="mt-0.5">{{ selectedEvent.notes }}</p>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button
                                @click="showEventModal = false"
                                class="w-full py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>

        </div>
    </AuthenticatedLayout>
</template>