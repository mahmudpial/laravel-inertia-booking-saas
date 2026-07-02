<script setup>
/**
 * Entity Ledger v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Global Node Management: Audit and status control of business units.
 * 2. Throughput Monitoring: Real-time tracking of session packet volume.
 * 3. Identity Verification: Ownership and principal mapping.
 * 
 * @author Pial Mahmud (System Architect)
 */

import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ entities: Object });

const toggleStatus = (id) => {
    if (confirm('Authorize node status mutation? This affects system-wide operational access.')) {
        router.patch(route('superadmin.entities.toggle', id), {}, { preserveScroll: true });
    }
};
</script>

<template>

    <Head title="Entity Ledger | Sovereign Oversight" />

    <SovereignLayout>
        <div class="space-y-20 animate-fadeIn">

            <!-- =========================================================
                 EDITORIAL HERO SECTION
                 ========================================================= -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 items-start">
                <div class="xl:col-span-5 space-y-8 animate-slideUp">
                    <div class="flex items-center gap-3">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Entity
                                Control Node Active</span>
                        </div>
                    </div>
                    <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                        Node <br />
                        <span
                            class="text-indigo-600 font-medium text-5xl lg:text-8xl uppercase tracking-tighter">Index.</span>
                    </h1>
                    <p class="text-slate-400 font-bold uppercase tracking-[0.5em] text-[10px]">Registry Module
                        v4.2.0-Alpha</p>
                </div>

                <!-- ENTITY INTELLIGENCE ARTICLE -->
                <div
                    class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                    </div>
                    <div class="relative z-10 space-y-10">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Management
                                briefing</p>
                            <span
                                class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-4 py-1 bg-white/5 rounded-full border border-white/10">Authorized
                                access only</span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">Quantizing
                            the <br /> Global Business Grid.</h2>
                        <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                            <p>
                                The **Entity Ledger** is the primary administrative interface for multi-tenant
                                oversight. Every business node registered on the platform is a quantized unit of
                                operational throughput.
                            </p>
                            <p>
                                Maintaining the integrity of these nodes is essential for platform-wide stability.
                                High-frequency monitoring of booking packets and service SKUs allows for surgical
                                administrative precision when managing the infrastructure.
                            </p>
                        </article>
                    </div>
                </div>
            </div>

            <!-- =========================================================
                 THE ENTITY LEDGER (High-Density Table)
                 ========================================================= -->
            <div
                class="bg-white rounded-[5rem] border border-slate-200/60 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] overflow-hidden">
                <div
                    class="px-14 py-14 border-b border-slate-100 flex flex-col md:flex-row md:items-end justify-between gap-10 bg-slate-50/40">
                    <div>
                        <h3 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">Global
                            Registry</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">
                            Platform-wide node synchronization ledger</p>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="px-8 py-4 bg-white border border-slate-200 rounded-[1.5rem] shadow-sm flex items-center gap-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Managed
                                Nodes</span>
                            <span class="text-2xl font-black text-[#09090B] leading-none">{{ entities.total }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-14 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                                    Business Node</th>
                                <th class="px-14 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                                    Operational Status</th>
                                <th class="px-14 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                                    Throughput</th>
                                <th
                                    class="px-14 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="entity in entities.data" :key="entity.id"
                                class="group hover:bg-indigo-50/30 transition-all duration-500">
                                <!-- Col 1: Identity -->
                                <td class="px-14 py-12">
                                    <div class="flex items-center gap-10">
                                        <div
                                            class="w-20 h-16 rounded-[2rem] bg-[#09090B] flex items-center justify-center font-black text-white text-2xl shadow-2xl group-hover:bg-indigo-600 group-hover:rotate-6 transition-all duration-500 shadow-indigo-900/10">
                                            {{ entity.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-3xl font-black text-[#09090B] tracking-tighter leading-none uppercase truncate max-w-[280px]">
                                                {{ entity.name }}</p>
                                            <div class="flex items-center gap-3 mt-3">
                                                <span
                                                    class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{
                                                        entity.owner }}</span>
                                                <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                                <span
                                                    class="text-[10px] font-black text-indigo-500 uppercase tracking-tighter">{{
                                                        entity.email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Col 2: Status Badge -->
                                <td class="px-14 py-12">
                                    <div :class="[
                                        'inline-flex items-center gap-3 px-6 py-3 rounded-2xl border transition-all duration-500 shadow-sm',
                                        entity.status === 'active' ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100 opacity-60'
                                    ]">
                                        <div :class="entity.status === 'active' ? 'bg-emerald-500 shadow-[0_0_10px_#10b981]' : 'bg-rose-500'"
                                            class="w-2 h-2 rounded-full"></div>
                                        <span :class="entity.status === 'active' ? 'text-emerald-600' : 'text-rose-600'"
                                            class="text-[10px] font-black uppercase tracking-[0.3em]">{{ entity.status
                                            }}</span>
                                    </div>
                                </td>

                                <!-- Col 3: Technical Metrics -->
                                <td class="px-14 py-12">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-4">
                                            <span
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sessions</span>
                                            <p class="text-xl font-black text-[#09090B] tracking-tighter leading-none">
                                                {{ entity.load }}</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Modules</span>
                                            <p class="text-xl font-black text-indigo-600 tracking-tighter leading-none">
                                                {{ entity.skus }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Col 4: Action Node -->
                                <td class="px-14 py-12 text-right">
                                    <div class="flex justify-end gap-3">
                                        <Link :href="`/b/${entity.slug}`" target="_blank"
                                            class="px-8 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-[9px] font-black uppercase tracking-widest text-[#09090B] hover:bg-[#09090B] hover:text-white transition-all active:scale-90 shadow-sm">
                                            Audit Node</Link>

                                        <button @click="toggleStatus(entity.id)"
                                            :class="entity.status === 'active' ? 'bg-rose-50 text-rose-600 hover:bg-rose-600' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600'"
                                            class="px-8 py-4 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:text-white transition-all active:scale-95 shadow-sm">
                                            {{ entity.status === 'active' ? 'Suspend' : 'Activate' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION NODE -->
                <div class="px-14 py-12 border-t border-slate-50 bg-slate-50/20 flex justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em]">End of Verified Data
                        Stream</p>
                </div>
            </div>

            <div class="mt-40 text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">Operational Infrastructure
                    v4.2.0 | Secure Ledger Control</p>
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