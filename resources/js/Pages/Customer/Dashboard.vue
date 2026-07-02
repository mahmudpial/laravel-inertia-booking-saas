<script setup>
/**
 * Personal Agenda Portal v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Session Tracking: Management of confirmed and historical packets.
 * 2. Re-deployment Engine: Instant access to business discovery nodes.
 * 3. Termination Protocol: Secure cancellation handshake via tactical modal.
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, watch, onUnmounted } from 'vue';

const props = defineProps({
    bookings: Array,
    stats: Object
});

const isConfirmingCancel = ref(false);
const selectedBookingId = ref(null);

// CTO GRADE: SYSTEM SCROLL LOCKING
watch(isConfirmingCancel, (val) => {
    if (val) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onUnmounted(() => {
    document.body.style.overflow = '';
});

const confirmCancellation = (id) => {
    selectedBookingId.value = id;
    isConfirmingCancel.value = true;
};

const handleCancel = () => {
    router.patch(route('customer.bookings.cancel', selectedBookingId.value), {}, {
        onSuccess: () => {
            isConfirmingCancel.value = false;
            selectedBookingId.value = null;
        }
    });
};
</script>

<template>

    <Head title="Personal Agenda | SmartBooking" />

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

                <!-- EDITORIAL GREETING HEADER -->
                <div class="flex flex-col xl:flex-row xl:items-end justify-between mb-24 gap-12 animate-slideUp">
                    <div class="space-y-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Client Access
                                Node Authorized</span>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            Personal <br />
                            <span class="text-indigo-600 font-medium text-5xl lg:text-8xl">Agenda.</span>
                        </h1>
                        <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-xs">Session Pipeline v4.2</p>
                    </div>

                    <!-- GLOBAL DISCOVERY ACTION -->
                    <Link href="/"
                        class="inline-flex items-center justify-center px-12 py-6 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[2rem] shadow-2xl shadow-indigo-900/20 hover:bg-indigo-600 transition-all active:scale-95 group">
                        <svg class="w-4 h-4 mr-4 group-hover:rotate-90 transition-transform duration-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        Initialize New Session
                    </Link>
                </div>

                <!-- TACTICAL SESSION GRID -->
                <div class="grid grid-cols-1 gap-8">
                    <div v-for="booking in bookings" :key="booking.id"
                        class="group relative bg-white border border-slate-200/60 rounded-[4rem] p-10 lg:p-14 transition-all duration-500 hover:border-indigo-500/40 hover:shadow-[0_40px_100px_-20px_rgba(79,70,229,0.12)]">

                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-12">

                            <!-- 01. Identity Block -->
                            <div class="flex items-center gap-10 flex-1 min-w-0">
                                <div
                                    class="w-24 h-20 shrink-0 rounded-[2rem] bg-[#09090B] flex items-center justify-center font-black text-4xl text-white shadow-2xl transition-all duration-500 group-hover:bg-indigo-600 group-hover:rotate-6">
                                    {{ (booking.business_name || 'B').charAt(0).toUpperCase() }}
                                </div>
                                <div class="min-w-0">
                                    <h4
                                        class="text-4xl font-black text-[#09090B] tracking-tighter truncate leading-none uppercase">
                                        {{ booking.business_name }}</h4>
                                    <div class="flex items-center gap-5 mt-5">
                                        <span class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em]">{{
                                            booking.service_name }}</span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">{{
                                            booking.staff_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 02. Readout Module -->
                            <div
                                class="grid grid-cols-2 xl:flex xl:flex-col gap-10 xl:gap-6 xl:w-64 p-10 xl:p-0 rounded-[3rem] bg-slate-50/50 xl:bg-transparent shadow-inner xl:shadow-none border border-slate-100 xl:border-none">
                                <div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] mb-3">
                                        Deployment</p>
                                    <div
                                        class="flex items-center gap-3 text-xl font-black text-[#09090B] tracking-tight">
                                        <svg class="w-5 h-5 text-indigo-500 opacity-40" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ booking.date }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] mb-3">
                                        Time-Window</p>
                                    <div
                                        class="flex items-center gap-3 text-xl font-black text-indigo-600 tracking-tight">
                                        <svg class="w-5 h-5 text-indigo-400 opacity-40" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ booking.time }}
                                    </div>
                                </div>
                            </div>

                            <!-- 03. Tactical Action Hub -->
                            <div
                                class="flex items-center gap-4 w-full xl:w-auto pt-10 xl:pt-0 border-t border-slate-100 xl:border-t-0">
                                <!-- PRO: Dynamic Re-Deployment link -->
                                <Link :href="`/b/${booking.business_slug}`"
                                    class="flex-1 xl:flex-none px-8 py-5 bg-white border border-slate-200 text-[#09090B] text-[10px] font-black uppercase tracking-[0.25em] rounded-2xl hover:bg-[#09090B] hover:text-white hover:border-[#09090B] transition-all active:scale-95 shadow-sm text-center">
                                    Re-book Studio
                                </Link>

                                <div :class="[
                                    'px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] border flex-1 xl:flex-none text-center',
                                    booking.status === 'Confirmed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-200',
                                    booking.status === 'Canceled' ? 'bg-rose-50 text-rose-600 border-rose-100' : ''
                                ]">
                                    {{ booking.status === 'Confirmed' ? 'Verified' : booking.status }}
                                </div>

                                <button v-if="booking.status !== 'Canceled'" @click="confirmCancellation(booking.id)"
                                    class="p-5 bg-slate-50 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all active:scale-90 border border-transparent hover:border-rose-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EMPTY STATE -->
                <div v-if="bookings.length === 0"
                    class="text-center py-64 bg-white/60 backdrop-blur-xl border border-slate-200/80 rounded-[6rem] shadow-sm animate-fadeIn">
                    <div
                        class="w-24 h-24 rounded-[2.5rem] bg-[#09090B] flex items-center justify-center mx-auto mb-10 shadow-2xl">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-5xl font-black text-[#09090B] uppercase tracking-tighter leading-none">Agenda
                        Synchronized.</h3>
                    <p class="text-slate-400 text-xs mt-6 uppercase tracking-[0.5em] font-black leading-relaxed">Zero
                        session packets currently indexed for this node.</p>
                </div>

                <!-- FOOTER TAG -->
                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">Secure Client
                        Infrastructure v4.2.0</p>
                </div>
            </div>
        </div>

        <!-- RE-ENGINEERED TERMINATION MODAL -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isConfirmingCancel" class="fixed inset-0 z-[2000] flex items-center justify-center p-6">
                    <!-- High-Density Opaque Backdrop -->
                    <div class="fixed inset-0 bg-[#09090B]/95 backdrop-blur-2xl" @click="isConfirmingCancel = false">
                    </div>

                    <div
                        class="relative bg-white w-full max-w-xl rounded-[4rem] p-16 text-center shadow-[0_50px_150px_rgba(0,0,0,0.5)] overflow-hidden animate-slideUp z-10">
                        <div
                            class="w-24 h-24 bg-rose-50 rounded-[2.5rem] flex items-center justify-center text-rose-600 mx-auto mb-12 shadow-xl shadow-rose-100/50">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="text-4xl font-black text-[#11131A] tracking-tighter uppercase mb-6 leading-none">
                            Terminate <br /> Packet?</h2>
                        <p
                            class="text-slate-500 text-base font-medium mb-12 leading-relaxed italic uppercase tracking-wider">
                            This action will force-purge your reservation from the operational grid.</p>

                        <div class="flex flex-col sm:flex-row gap-6">
                            <button @click="isConfirmingCancel = false"
                                class="flex-1 py-7 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] hover:bg-slate-200 transition-all active:scale-95">Abort</button>
                            <button @click="handleCancel"
                                class="flex-1 py-7 bg-rose-600 text-white text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] shadow-2xl shadow-rose-900/40 transition-all active:scale-95">Confirm
                                Purge</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(input),
:deep(textarea),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(p),
:deep(span),
:deep(button) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(60px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideUp {
    animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.animate-fadeIn {
    animation: fadeIn 1.2s ease-out forwards;
}

.shadow-inner {
    box-shadow: inset 0 4px 12px 0 rgba(0, 0, 0, 0.08);
}
</style>