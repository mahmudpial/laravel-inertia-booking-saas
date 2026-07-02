<script setup>
/**
 * System Timeline v4.2.0-Alpha
 * 
 * CORE ARCHITECTURE:
 * 1. Chronos Protocol: High-precision temporal quantization engine.
 * 2. Packet Dispatch: Visual rendering of confirmed vs pending sessions.
 * 3. Tactical Sync: Real-time drag-and-drop re-scheduling logic.
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

// Transform database bookings into High-Contrast Tactical Packets
const calendarEvents = computed(() => props.bookings.map(b => ({
    id: b.id,
    title: b.user?.name || 'Guest Node',
    start: `${b.booking_date}T${b.start_time}`,
    end: `${b.booking_date}T${b.end_time}`,
    extendedProps: {
        service: b.service?.name || 'Standard SKU',
        status: b.status.toLowerCase().trim()
    }
})));

// Handle Drag and Drop Tactical Sync
const handleEventDrop = (info) => {
    const newDate = info.event.startStr.split('T')[0];
    const newTime = info.event.startStr.split('T')[1].substring(0, 8);

    if (confirm(`Authorize Temporal Re-synchronization: ${newDate} @ ${newTime}?`)) {
        router.patch(route('admin.bookings.updateStatus', info.event.id), {
            booking_date: newDate,
            start_time: newTime
        }, {
            preserveScroll: true,
            onSuccess: () => alert('✅ Timeline Packet Synchronized.'),
        });
    } else {
        info.revert();
    }
};

// Tactical Navigation Handlers
const next = () => calendarRef.value.getApi().next();
const prev = () => calendarRef.value.getApi().prev();
const today = () => calendarRef.value.getApi().today();

const calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    headerToolbar: false,
    editable: true,
    selectable: true,
    dayMaxEvents: true,
    allDaySlot: false,
    slotMinTime: '08:00:00',
    slotMaxTime: '22:00:00',
    events: calendarEvents.value,
    eventDrop: handleEventDrop,
    eventClick: (info) => alert(`Packet Details:\nClient: ${info.event.title}\nService: ${info.event.extendedProps.service}`),
    height: 'auto',
    slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridian: 'short' },
    eventContent: (arg) => {
        const isConfirmed = arg.event.extendedProps.status === 'confirmed';
        return {
            html: `
                <div class="p-4 h-full flex flex-col justify-between border-l-[6px] ${isConfirmed ? 'bg-[#09090B] text-white border-indigo-600 shadow-2xl' : 'bg-white text-[#09090B] border-amber-500 shadow-xl'} rounded-2xl transition-all duration-300 group">
                    <div class="min-w-0">
                        <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-40 mb-1">${arg.timeText}</p>
                        <h4 class="text-[11px] font-black truncate uppercase tracking-tighter leading-tight">${arg.event.title}</h4>
                    </div>
                    <div class="pt-2 border-t ${isConfirmed ? 'border-white/10' : 'border-slate-100'} mt-2">
                        <p class="text-[8px] font-bold uppercase tracking-[0.1em] opacity-60 truncate">${arg.event.extendedProps.service}</p>
                    </div>
                </div>
            `
        };
    }
};
</script>

<template>

    <Head title="Operational Timeline | SmartBooking Intelligence" />

    <AuthenticatedLayout>
        <div
            class="relative min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] pb-48 selection:bg-indigo-600 selection:text-white">

            <!-- ARCHITECTURAL BLUEPRINT BACKGROUND SYNC -->
            <div class="fixed inset-0 z-0 pointer-events-none">
                <div
                    class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
                </div>
                <div
                    class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
                </div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 pt-16">

                <!-- =========================================================
                     EDITORIAL HERO SECTION (Two-Column Layout)
                     ========================================================= -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-10 animate-slideUp">
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                    <span
                                        class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Timeline
                                        Intelligence Active</span>
                                </div>
                            </div>
                            <h1
                                class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                                System <br />
                                <span class="text-indigo-600 font-medium text-5xl lg:text-8xl">Timeline.</span>
                            </h1>
                        </div>

                        <!-- TACTICAL NAVIGATION POD -->
                        <div
                            class="bg-white border border-slate-200/60 p-2.5 rounded-[2.5rem] shadow-2xl flex items-center gap-4 w-fit">
                            <button @click="prev"
                                class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl text-[#09090B] hover:bg-[#09090B] hover:text-white transition-all active:scale-90 border border-slate-100 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div @click="today"
                                class="px-8 py-2 text-center border-x border-slate-100 min-w-[180px] cursor-pointer group">
                                <p
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1 group-hover:text-indigo-600 transition-colors">
                                    Operational Cycle</p>
                                <span class="text-sm font-black text-[#09090B] uppercase tracking-tighter">Current
                                    Week</span>
                            </div>
                            <button @click="next"
                                class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl text-[#09090B] hover:bg-[#09090B] hover:text-white transition-all active:scale-90 border border-slate-100 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- TEMPORAL INTELLIGENCE ARTICLE -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn text-left">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">System
                                    Intelligence Briefing</p>
                                <div class="flex gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></div>
                                    <span
                                        class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">Throughput:
                                        Optimal</span>
                                </div>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">The
                                Chronos Protocol: <br /> Re-Quantizing Time.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    In an industrial-scale booking ecosystem, **Time** is the primary currency. The
                                    **System Timeline** implements a proprietary scheduling logic that visualizes human
                                    resource distribution with millisecond precision.
                                </p>
                                <p>
                                    By meticulously indexing your specialist nodes, you enable the engine to calculate
                                    precise availability windows, effectively reducing logistical overhead. Efficiency
                                    is an architectural requirement, not an option.
                                </p>
                            </article>
                            <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-[10px] font-black shadow-lg">
                                    TS</div>
                                <p
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-loose uppercase">
                                    Operational data integrity is prioritized <br /> via Node-Sentinel protocols.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =========================================================
                     MAIN CALENDAR ENGINE MODULE
                     ========================================================= -->
                <div
                    class="bg-white/90 backdrop-blur-xl border border-slate-200/60 rounded-[4.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.08)] p-8 lg:p-16 overflow-hidden animate-fadeIn">
                    <div class="overflow-x-auto no-scrollbar">
                        <div class="min-w-[1000px]">
                            <FullCalendar ref="calendarRef" :options="calendarOptions" />
                        </div>
                    </div>
                </div>

                <!-- SYSTEM FOOTER LEGEND -->
                <div
                    class="mt-16 flex flex-col md:flex-row items-center justify-between gap-12 px-14 py-12 bg-[#09090B] rounded-[4rem] shadow-2xl shadow-indigo-900/20 border border-white/5">
                    <div class="flex items-center gap-12">
                        <div class="flex items-center gap-4">
                            <div class="w-4 h-4 rounded-full bg-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.8)]"></div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Authorized
                                Deployment</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-4 h-4 border-2 border-amber-500 rounded-full shadow-[0_0_15px_rgba(245,158,11,0.4)]">
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Pending
                                Sync</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-6 opacity-30 group cursor-help">
                        <svg class="w-5 h-5 text-white group-hover:text-indigo-400 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-[10px] font-black text-white uppercase tracking-[0.8em]">Operational
                            Infrastructure v4.2.0</p>
                    </div>
                </div>

                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1.5em]">Confidential Control
                        Session | SmartBooking Engine</p>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* FULL CALENDAR OVERRIDES: INDUSTRIAL PRECISION */
.fc {
    --fc-border-color: #f1f5f9;
    --fc-today-bg-color: rgba(79, 70, 229, 0.04);
    border: none !important;
}

.fc .fc-scrollgrid {
    border: none !important;
}

.fc .fc-col-header-cell {
    padding: 40px 0 !important;
    border: none !important;
    background: transparent;
}

.fc .fc-col-header-cell-cushion {
    font-family: 'Space Grotesk';
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.4em;
    font-size: 13px;
    color: #94a3b8;
}

.fc .fc-timegrid-slot {
    height: 110px !important;
    border-bottom: 1px solid #f8fafc !important;
}

.fc .fc-timegrid-slot-label-cushion {
    font-family: 'Space Grotesk';
    font-weight: 800;
    font-size: 12px;
    color: #09090B;
    text-transform: uppercase;
    letter-spacing: 0.2em;
}

.fc .fc-v-event {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.fc .fc-timegrid-axis-cushion {
    display: none;
}

/* Inset Shadow for Grid Cells */
.fc-timegrid-col {
    transition: background-color 0.3s ease;
}

.fc-timegrid-col:hover {
    background-color: rgba(0, 0, 0, 0.01);
}

/* Refined Scrollbars */
.fc-scroller::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

.fc-scroller::-webkit-scrollbar-track {
    background: transparent;
}

.fc-scroller::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 20px;
}
</style>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(span),
:deep(p),
:deep(button) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
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