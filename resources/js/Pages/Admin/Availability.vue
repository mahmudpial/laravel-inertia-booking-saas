<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

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
        onSuccess: () => {
            // High-end notification logic would trigger here
            alert('📅 Operational timeline synchronized.');
        }
    });
};
</script>

<template>

    <Head title="Operational Hours Control" />

    <AuthenticatedLayout>
        <!-- SIGNATURE RADIAL DOT BACKGROUND -->
        <div
            class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] pb-32">

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

                <!-- EDITORIAL PAGE HEADER -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
                    <div>
                        <div class="flex items-center gap-2.5 mb-4">
                            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-[0.3em] text-slate-400">Schedule
                                Intelligence</span>
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black text-[#11131A] tracking-tighter leading-none">
                            Business <span class="text-slate-400">Hours.</span>
                        </h1>
                        <p class="text-slate-500 font-medium mt-6 max-w-lg leading-relaxed">
                            Synchronize your professional availability. These windows define when your digital
                            storefront is open for new appointments.
                        </p>
                    </div>

                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-black uppercase text-slate-300 tracking-[0.2em] mb-2">System
                            Status</span>
                        <div
                            class="px-5 py-2 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center gap-3">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                            <span class="text-xs font-black text-[#11131A] uppercase tracking-widest">Online</span>
                        </div>
                    </div>
                </div>

                <!-- MAIN TIMELINE CONTROL -->
                <div
                    class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[3.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
                    <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                        <h3 class="text-xs font-black text-[#11131A] uppercase tracking-[0.3em]">Weekly Operational
                            Cycle</h3>
                        <div class="flex gap-1">
                            <div v-for="i in 7" :key="i" class="w-1.5 h-1.5 rounded-full bg-indigo-200"></div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="p-6 sm:p-10 space-y-4">

                        <!-- DAY STRIP MODULE -->
                        <div v-for="(item, index) in form.availabilities" :key="item.id" :class="[
                            'group flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 rounded-[2.5rem] border transition-all duration-500',
                            item.is_open
                                ? 'bg-white border-slate-100 hover:border-indigo-400 hover:shadow-[0_20px_50px_rgba(79,70,229,0.08)]'
                                : 'bg-slate-50/50 border-transparent opacity-60 grayscale'
                        ]">

                            <!-- Day Identity & Checkbox -->
                            <div class="flex items-center gap-6 md:w-56">
                                <div :class="[
                                    'w-14 h-14 rounded-2xl flex items-center justify-center font-black text-sm transition-all duration-700',
                                    item.is_open ? 'bg-[#11131A] text-white shadow-xl shadow-slate-900/20' : 'bg-slate-200 text-slate-400'
                                ]">
                                    {{ daysMap[item.day_of_week].slice(0, 2).toUpperCase() }}
                                </div>
                                <div>
                                    <span class="text-lg font-black text-[#11131A] block">{{ daysMap[item.day_of_week]
                                    }}</span>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{
                                        item.is_open ? 'Operational' : 'Closed' }}</p>
                                </div>
                            </div>

                            <!-- Engineered Switch -->
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer scale-110">
                                    <input type="checkbox" v-model="item.is_open" :true-value="1" :false-value="0"
                                        class="sr-only peer">
                                    <div
                                        class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-indigo-600">
                                    </div>
                                </label>
                            </div>

                            <!-- Time Selector Pod -->
                            <div v-if="item.is_open"
                                class="flex items-center gap-4 bg-slate-50/80 p-2.5 rounded-[1.5rem] border border-slate-100">
                                <div class="relative group/input">
                                    <input type="time" v-model="item.start_time" required
                                        class="bg-white border-none rounded-xl px-5 py-3 text-sm font-black text-[#11131A] focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                </div>

                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm border border-slate-100">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </div>

                                <div class="relative group/input">
                                    <input type="time" v-model="item.end_time" required
                                        class="bg-white border-none rounded-xl px-5 py-3 text-sm font-black text-[#11131A] focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                </div>
                            </div>

                            <!-- Offline Placeholder -->
                            <div v-else class="flex-1 flex justify-center md:justify-end">
                                <div class="px-6 py-3 bg-slate-100 rounded-2xl border border-slate-200">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Bookings
                                        Disabled</span>
                                </div>
                            </div>
                        </div>

                        <!-- ACTION FOOTER -->
                        <div
                            class="mt-12 pt-10 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-8">
                            <div class="flex items-start gap-4 text-slate-400 max-w-sm">
                                <div class="p-2 bg-indigo-50 rounded-xl text-indigo-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-medium leading-relaxed italic">Synchronizing will update your
                                    public booking slots. All active customer sessions will remain unchanged.</p>
                            </div>

                            <button type="submit" :disabled="form.processing"
                                class="w-full sm:w-auto px-12 py-5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-[1.5rem] hover:bg-indigo-600 transition-all shadow-2xl shadow-indigo-900/20 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-4">
                                <svg v-if="form.processing" class="h-4 w-4 animate-spin" viewBox="0 0 24 24"
                                    fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>{{ form.processing ? 'Synchronizing...' : 'Push Changes' }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- AESTHETIC FOOTER TAG -->
                <div class="mt-16 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Inventory System v2.4.0
                    </p>
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
:deep(h3) {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* Elegant time input customization */
input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0.2) sepia(1) saturate(5) hue-rotate(220deg);
    cursor: pointer;
    width: 18px;
    height: 18px;
}

/* Smooth list transition for active rows */
.group {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>