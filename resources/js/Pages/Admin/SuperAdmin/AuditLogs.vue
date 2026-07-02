<script setup>
/**
 * Audit Ledger v4.2.0
 *
 * CORE SYSTEMS:
 * 1. Activity Feed: Chronological stream derived from business + booking timestamps.
 * 2. Event Classification: Tags each entry by node type and lifecycle stage.
 *
 * NOTE: There is no dedicated audit_logs table in the schema yet, this feed
 * is derived live from existing records (created_at / updated_at). It is a
 * good-enough activity trail for now; swap in a real audit table for formal
 * compliance/security logging later.
 */

import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    events: Array,
});

// Full literal class strings (not built via concatenation) so Tailwind's
// scanner can pick them up at build time.
const typeMeta = (type) => {
    if (type?.startsWith('business')) {
        return { dot: 'bg-indigo-500 text-indigo-500', badge: 'text-indigo-500 bg-indigo-500/10', label: 'Node' };
    }
    if (type === 'booking.confirmed') {
        return { dot: 'bg-indigo-500 text-indigo-500', badge: 'text-indigo-500 bg-indigo-500/10', label: 'Booking' };
    }
    if (type === 'booking.completed') {
        return { dot: 'bg-emerald-500 text-emerald-500', badge: 'text-emerald-500 bg-emerald-500/10', label: 'Booking' };
    }
    if (type === 'booking.canceled') {
        return { dot: 'bg-rose-500 text-rose-500', badge: 'text-rose-500 bg-rose-500/10', label: 'Booking' };
    }
    return { dot: 'bg-amber-500 text-amber-500', badge: 'text-amber-500 bg-amber-500/10', label: 'Booking' };
};
</script>

<template>

    <Head title="Audit Ledger | Sovereign Oversight" />

    <SovereignLayout>
        <div class="space-y-16 animate-fadeIn">

            <!-- HEADER -->
            <div class="space-y-6">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-indigo-600">Live activity
                        stream &middot; last {{ events?.length || 0 }} events</span>
                </div>
                <h1 class="text-7xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                    Audit <br /> <span class="text-indigo-600">Ledger.</span>
                </h1>
                <p class="text-slate-400 font-bold uppercase tracking-[0.4em] text-[10px] max-w-2xl">
                    Chronological record of platform-wide node registrations and booking lifecycle transitions.
                </p>
            </div>

            <!-- TIMELINE -->
            <div
                class="bg-white rounded-[5rem] border border-slate-200/60 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] overflow-hidden">
                <div class="px-14 py-14 border-b border-slate-100 bg-slate-50/40">
                    <h3 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">Event Stream
                    </h3>
                </div>

                <div class="divide-y divide-slate-50">
                    <div v-for="(event, idx) in events" :key="idx"
                        class="group flex items-center gap-10 px-14 py-10 hover:bg-indigo-50/30 transition-colors">
                        <div class="flex flex-col items-center gap-2 shrink-0">
                            <div class="w-3 h-3 rounded-full shadow-[0_0_10px_currentColor]"
                                :class="typeMeta(event.type).dot"></div>
                            <div v-if="idx !== events.length - 1" class="w-px h-10 bg-slate-100"></div>
                        </div>

                        <div class="w-28 shrink-0">
                            <span
                                class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-slate-100"
                                :class="typeMeta(event.type).badge">
                                {{ typeMeta(event.type).label }}
                            </span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-lg font-black text-[#09090B] tracking-tight uppercase truncate">{{
                                event.label }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1 truncate">{{
                                event.detail }} &middot; {{ event.actor }}</p>
                        </div>

                        <div class="shrink-0 text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{
                                event.timestamp }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="!events?.length" class="p-40 text-center">
                    <p class="text-xs font-black text-slate-300 uppercase tracking-[0.8em]">No Activity Indexed Yet</p>
                </div>
            </div>

            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">
                    Derived from live records &middot; not a formal compliance audit trail</p>
            </div>
        </div>
    </SovereignLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(span),
:deep(p),
:deep(button),
:deep(a) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.8s ease-out forwards;
}
</style>
