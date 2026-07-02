<script setup>
/**
 * Discovery Hub v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Node Discovery: Real-time marketplace of registered studio entities.
 * 2. Rapid Deployment: Immediate routing to business-specific booking channels.
 * 3. Agenda Intelligence: Real-time summary of the user's next active session.
 * 
 * @author Pial Mahmud (System Architect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    businesses: Array,
    my_upcoming: Array
});
</script>

<template>

    <Head title="Discovery Hub | Studio Marketplace" />

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

                <!-- EDITORIAL HERO HEADER -->
                <div class="mb-24 animate-slideUp">
                    <div
                        class="inline-flex items-center gap-2.5 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Marketplace
                            Discovery Engine Active</span>
                    </div>
                    <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                        Explore <br />
                        <span
                            class="text-indigo-600 font-medium text-5xl lg:text-8xl uppercase tracking-tighter">Studios.</span>
                    </h1>
                    <p class="mt-8 text-slate-400 font-bold uppercase tracking-[0.5em] text-xs">Infrastructure Node
                        Index v4.2</p>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16">

                    <!-- LEFT: BUSINESS MARKETPLACE (Studio Nodes) -->
                    <div class="xl:col-span-8 space-y-12">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-8">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-400">Available
                                Operational Nodes</h3>
                            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">{{
                                businesses.length }} Entities Online</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div v-for="shop in businesses" :key="shop.slug"
                                class="group relative bg-white border border-slate-200/60 rounded-[4rem] p-10 shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-500/40 transition-all duration-700 hover:-translate-y-2">

                                <div class="flex items-start justify-between mb-10">
                                    <div
                                        class="w-20 h-20 rounded-[1.5rem] bg-[#09090B] flex items-center justify-center shadow-2xl overflow-hidden group-hover:rotate-6 transition-all duration-700 shadow-indigo-900/10 border border-white/5">
                                        <img v-if="shop.logo" :src="shop.logo" class="w-full h-full object-cover" />
                                        <span v-else class="text-white font-black text-3xl">{{
                                            shop.name.charAt(0).toUpperCase() }}</span>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <div
                                            class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full flex items-center gap-2">
                                            <div
                                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]">
                                            </div>
                                            <span
                                                class="text-[8px] font-black uppercase tracking-widest text-emerald-600">Operational</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-12">
                                    <h4
                                        class="text-3xl font-black text-[#09090B] uppercase tracking-tighter leading-none mb-4 group-hover:text-indigo-600 transition-colors">
                                        {{ shop.name }}</h4>
                                    <p
                                        class="text-slate-500 text-sm font-medium line-clamp-2 leading-relaxed italic uppercase tracking-wide h-10">
                                        "{{ shop.description || 'Verified Service Specialist.' }}"
                                    </p>
                                </div>

                                <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">Region</span>
                                        <span class="text-[10px] font-black text-[#09090B] uppercase tracking-tight">{{
                                            shop.address || 'Global Node' }}</span>
                                    </div>
                                    <Link :href="`/b/${shop.slug}`"
                                        class="inline-flex items-center justify-center px-10 py-5 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] hover:bg-indigo-600 transition-all shadow-xl active:scale-95">
                                        Initialize
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: REAL-TIME AGENDA SIDEBAR -->
                    <div class="xl:col-span-4 space-y-10">
                        <div
                            class="bg-[#09090B] rounded-[4rem] p-12 text-white shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] relative overflow-hidden border border-white/5 group">
                            <div
                                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e533_0%,transparent_70%)]">
                            </div>

                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em] mb-12">
                                    Session Priority 01</p>

                                <div v-if="my_upcoming.length > 0" class="space-y-12">
                                    <div class="space-y-4">
                                        <span
                                            class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Active
                                            Packet</span>
                                        <h3 class="text-5xl font-black tracking-tighter leading-none uppercase">{{
                                            my_upcoming[0].service.name }}</h3>
                                        <p class="text-indigo-400 text-sm font-bold uppercase tracking-[0.2em] pt-2">{{
                                            my_upcoming[0].business.name }}</p>
                                    </div>

                                    <div class="pt-10 border-t border-white/10 flex flex-col gap-6">
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Temporal
                                                Slot</span>
                                            <span class="text-xs font-black text-white uppercase tracking-widest">{{
                                                my_upcoming[0].start_time }}</span>
                                        </div>
                                        <Link :href="route('customer.dashboard')"
                                            class="w-full py-5 bg-white/5 border border-white/10 rounded-[1.5rem] text-[9px] font-black uppercase tracking-[0.4em] text-center hover:bg-white hover:text-[#09090B] transition-all">
                                            Access Full Agenda
                                        </Link>
                                    </div>
                                </div>

                                <div v-else class="py-20 text-center opacity-30">
                                    <svg class="w-12 h-12 mx-auto mb-6 opacity-20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-[10px] font-black uppercase tracking-[0.5em]">No Active Packets</p>
                                </div>
                            </div>
                        </div>

                        <!-- SYSTEM INTEGRITY WIDGET -->
                        <div class="p-10 bg-white border border-slate-200 rounded-[3.5rem] shadow-xl">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em] mb-10 text-center">
                                Platform Status</p>
                            <div class="space-y-8">
                                <div v-for="node in ['Discovery Service', 'Handshake Protocol', 'Asset Cloud']"
                                    :key="node" class="flex justify-between items-center">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-600">{{ node
                                    }}</span>
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER METADATA -->
                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">Operational Infrastructure
                        v4.2.0-Alpha.01</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
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