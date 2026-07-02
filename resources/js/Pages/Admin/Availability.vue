<script setup>
/**
 * Operational Timeline v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Temporal Quantization: Management of daily operational windows.
 * 2. Status Synchronization: Real-time update of public booking intervals.
 * 3. Node Intelligence: Integrated briefing on scheduling logistics.
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    availabilities: Array
});

const daysMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

const form = useForm({
    availabilities: props.availabilities
});

const submit = () => {
    form.put(route('admin.availability.update'), {
        preserveScroll: true,
        onSuccess: () => alert('✅ Operational Timeline Synchronized.'),
    });
};
</script>

<template>

    <Head title="Operational Timeline | Business Hours" />

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

                <!-- =========================================================
                     EDITORIAL HERO SECTION
                     ========================================================= -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-8 animate-slideUp">
                        <div class="flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span
                                    class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Operational
                                    Matrix Active</span>
                            </div>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            Business <br />
                            <span class="text-indigo-600 font-medium text-5xl lg:text-8xl">Hours.</span>
                        </h1>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.5em] text-[10px]">Temporal Control
                            v4.2.0-Alpha</p>
                    </div>

                    <!-- TEMPORAL INTELLIGENCE ARTICLE -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn text-left">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Scheduling
                                    Intelligence</p>
                                <span
                                    class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 py-1 bg-white/5 rounded-full">Unit
                                    Logic v4.2</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">The
                                Chronos Protocol: <br /> Engineering Time Quantization.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    In a high-frequency professional ecosystem, **Time** is the primary resource.
                                    Defining precise operational windows allows our engine to calculate and deploy
                                    session packets with surgical accuracy.
                                </p>
                                <p>
                                    Synchronizing your timeline ensures that specialist nodes are only active when the
                                    physical headquarters is operational. This prevents logistical friction and
                                    optimizes the global dispatch cycle.
                                </p>
                            </article>
                            <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-[10px] font-black shadow-lg">
                                    TS</div>
                                <p
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-loose">
                                    Confidentiality of operational data is secured <br /> via the Node-Sentinel
                                    framework.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =========================================================
                     OPERATIONAL WORKSPACE
                     ========================================================= -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

                    <!-- NAVIGATION & UPTIME SIDEBAR -->
                    <aside class="lg:col-span-3 space-y-10">
                        <nav
                            class="flex lg:flex-col overflow-x-auto lg:overflow-visible gap-4 pb-6 lg:pb-0 no-scrollbar sticky top-28">
                            <div
                                class="flex-shrink-0 px-8 py-5 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] shadow-2xl shadow-indigo-900/20 border border-white/5 cursor-default">
                                Weekly Schedule
                            </div>
                            <div
                                class="flex-shrink-0 px-8 py-5 bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] hover:bg-slate-50 transition-all cursor-pointer text-center">
                                Custom Overrides
                            </div>
                        </nav>

                        <!-- OPERATIONAL HEALTH WIDGET -->
                        <div
                            class="hidden lg:block p-10 bg-white border border-slate-200 rounded-[3rem] shadow-xl relative overflow-hidden group">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-8">Uptime
                                Integrity</p>
                            <div class="space-y-8">
                                <div v-for="(val, key) in { 'Node Sync': 'Active', 'Operational': 'Synced', 'Encryption': 'Verified' }"
                                    :key="key" class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ key
                                    }}</span>
                                    <span class="text-[10px] font-black text-indigo-600 uppercase">{{ val }}</span>
                                </div>
                            </div>
                            <div class="mt-10 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-indigo-600 shadow-[0_0_10px_#4f46e5]" style="width: 100%"></div>
                            </div>
                        </div>
                    </aside>

                    <!-- TIMELINE STRIPS -->
                    <main class="lg:col-span-9 space-y-4 animate-fadeIn">
                        <div v-for="(item, index) in form.availabilities" :key="item.id" :class="[
                            'group flex flex-col md:flex-row md:items-center justify-between gap-8 p-8 rounded-[3rem] border transition-all duration-500',
                            item.is_open
                                ? 'bg-white border-slate-200 shadow-xl hover:border-indigo-400 hover:shadow-indigo-500/5 hover:-translate-y-1'
                                : 'bg-slate-50/50 border-transparent opacity-60 grayscale'
                        ]">

                            <!-- Day Identity Pod -->
                            <div class="flex items-center gap-8 md:w-56 text-left">
                                <div :class="[
                                    'w-16 h-14 rounded-2xl flex items-center justify-center font-black text-lg transition-all duration-700 shadow-inner border border-transparent',
                                    item.is_open ? 'bg-[#09090B] text-white shadow-indigo-900/20 group-hover:bg-indigo-600' : 'bg-slate-200 text-slate-400'
                                ]">
                                    {{ daysMap[item.day_of_week].slice(0, 2).toUpperCase() }}
                                </div>
                                <div class="min-w-0">
                                    <h4
                                        class="text-xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                        {{ daysMap[item.day_of_week] }}</h4>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">{{
                                        item.is_open ? 'Operational' : 'Offline' }}</p>
                                </div>
                            </div>

                            <!-- Tactical Toggle -->
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer scale-110">
                                    <input type="checkbox" v-model="item.is_open" :true-value="1" :false-value="0"
                                        class="sr-only peer">
                                    <div
                                        class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                    </div>
                                </label>
                            </div>

                            <!-- Time Selector Slots -->
                            <div v-if="item.is_open"
                                class="flex items-center gap-4 bg-slate-50 p-2 rounded-[2rem] border border-slate-100 shadow-inner">
                                <input type="time" v-model="item.start_time" required
                                    class="bg-white border-none rounded-[1.25rem] px-6 py-4 text-base font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm" />

                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow-sm border border-slate-100">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </div>

                                <input type="time" v-model="item.end_time" required
                                    class="bg-white border-none rounded-[1.25rem] px-6 py-4 text-base font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm" />
                            </div>

                            <!-- Offline Label -->
                            <div v-else class="flex-1 flex justify-center md:justify-end pr-10">
                                <span
                                    class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] italic leading-none">System
                                    Offline</span>
                            </div>
                        </div>

                        <!-- TACTICAL DEPLOYMENT ZONE -->
                        <div
                            class="pt-20 border-t border-slate-100 flex flex-col xl:flex-row items-center justify-between gap-16">
                            <div class="flex items-start gap-8 text-slate-400 max-w-lg text-left">
                                <div
                                    class="p-5 bg-[#09090B] rounded-[2rem] text-indigo-500 shrink-0 shadow-2xl border border-white/10">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-[13px] font-medium leading-relaxed italic uppercase tracking-wider">
                                    Deploying updates to the operational timeline will force-quantize all available
                                    booking segments. Existing validated reservations remain immutable.</p>
                            </div>

                            <button @click="submit" :disabled="form.processing"
                                class="w-full md:w-auto px-20 py-8 bg-[#09090B] text-white text-[11px] font-black uppercase tracking-[0.6em] rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:bg-indigo-600 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-10 group">
                                <svg v-if="form.processing" class="h-6 w-6 animate-spin" viewBox="0 0 24 24"
                                    fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span class="group-hover:translate-x-2 transition-transform duration-500">Authorize &
                                    Sync Matrix</span>
                            </button>
                        </div>
                    </main>
                </div>

                <!-- AESTHETIC FOOTER TAG -->
                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1.5em]">Temporal Infrastructure
                        v4.2.0-Alpha.01</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(input),
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

.shadow-inner {
    box-shadow: inset 0 4px 12px 0 rgba(0, 0, 0, 0.08);
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

input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0.2) sepia(1) saturate(5) hue-rotate(220deg);
    cursor: pointer;
    width: 22px;
    height: 22px;
}
</style>