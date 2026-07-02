<script setup>
/**
 * Node Initialization v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Identity Mapping: Auto-generation of unique URL identifiers.
 * 2. Infrastructure Setup: First-time operational node deployment.
 * 3. Validation Logic: Real-time redundancy checking.
 * 
 * @author Pial Mahmud (System Architect)
 */

import { useForm, Head } from '@inertiajs/vue3';
import { watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: '',
    slug: ''
});

// Logic: Auto-quantize slug from business name input
watch(() => form.name, (newName) => {
    form.slug = newName
        .toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
});

const submit = () => {
    form.post(route('onboarding.store'));
};
</script>

<template>

    <Head title="Initialize Operational Node | SmartBooking" />

    <div
        class="min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] flex flex-col justify-center py-12 px-6 relative overflow-hidden selection:bg-indigo-600 selection:text-white">

        <!-- ARCHITECTURAL BLUEPRINT BACKGROUND -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
            </div>
        </div>

        <!-- LOGO ANCHOR -->
        <div class="relative z-10 mb-12 animate-fadeIn flex justify-center">
            <div
                class="w-20 h-20 bg-[#09090B] rounded-[2.5rem] flex items-center justify-center shadow-2xl shadow-indigo-900/30 relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,#4f46e533_0%,transparent_70%)]"></div>
                <ApplicationLogo class="h-10 w-auto text-white relative z-10" />
            </div>
        </div>

        <div class="relative z-10 sm:mx-auto max-w-xl w-full space-y-12">
            <!-- EDITORIAL HEADER -->
            <div class="text-center animate-slideUp">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Protocol:
                        Node_Initialization</span>
                </div>
                <h2 class="text-6xl lg:text-7xl font-black tracking-tighter uppercase leading-none text-[#09090B]">
                    Deploy <br /> <span class="text-indigo-600 font-medium">Workspace.</span>
                </h2>
                <p class="mt-6 text-slate-400 font-bold uppercase tracking-widest text-xs">
                    Define your operational identity on the booking grid.
                </p>
            </div>

            <!-- TACTICAL SETUP POD -->
            <div
                class="bg-white border border-slate-200/60 rounded-[4rem] p-10 lg:p-14 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] animate-fadeIn">

                <form @submit.prevent="submit" class="space-y-10">

                    <!-- MODULE 01: LEGAL LABEL -->
                    <div class="group">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 block ml-1">Legal
                            Identity Label</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <input v-model="form.name" type="text" required placeholder="E.G. BELLA VITA STUDIO"
                                class="block w-full rounded-[2rem] bg-slate-50 border-none py-6 pl-14 pr-8 text-lg font-black text-[#09090B] placeholder:text-slate-200 focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner uppercase tracking-tighter">
                        </div>
                        <InputError class="mt-3 ml-2" :message="form.errors.name" />
                    </div>

                    <!-- MODULE 02: URL QUANTIZATION -->
                    <div class="group">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 block ml-1">Communication
                            Path (Slug)</label>
                        <div
                            class="flex items-center rounded-[2rem] bg-slate-50 overflow-hidden shadow-inner focus-within:ring-2 focus-within:ring-indigo-500 transition-all">
                            <span
                                class="pl-8 pr-4 py-6 text-base font-black text-slate-300 uppercase tracking-widest border-r border-slate-100">
                                /B/
                            </span>
                            <input v-model="form.slug" type="text" required placeholder="identifier-node"
                                class="flex-1 bg-transparent border-none py-6 px-6 text-lg font-black text-indigo-600 placeholder:text-slate-200 focus:ring-0 uppercase tracking-tighter">
                        </div>
                        <p
                            class="mt-4 px-6 text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em] leading-relaxed">
                            Endpoint: <span class="text-indigo-400">smartbooking.com/b/{{ form.slug || 'node-identifier'
                            }}</span>
                        </p>
                        <InputError class="mt-3 ml-2" :message="form.errors.slug" />
                    </div>

                    <!-- DEPLOYMENT TRIGGER -->
                    <div class="pt-6">
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex items-center justify-center gap-6 rounded-[2rem] bg-[#09090B] py-8 text-[11px] font-black uppercase tracking-[0.5em] text-white shadow-[0_20px_50px_rgba(0,0,0,0.2)] hover:bg-indigo-600 transition-all active:scale-95 disabled:opacity-50 group">
                            <svg v-if="form.processing" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span class="group-hover:translate-x-2 transition-transform duration-500">{{ form.processing
                                ? 'SYNCHRONIZING...' : 'Initialize Operational Node' }}</span>
                        </button>
                    </div>

                    <p class="text-center text-[9px] font-black text-slate-300 uppercase tracking-[0.5em]">
                        Global asset synchronization will follow initialization.
                    </p>
                </form>

            </div>
        </div>

        <!-- FOOTER INFRASTRUCTURE TAG -->
        <div class="mt-20 text-center relative z-10">
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">Operational Infrastructure v4.2.0
            </p>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(input),
:deep(h2),
:deep(p),
:deep(span) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

.shadow-inner {
    box-shadow: inset 0 2px 8px 0 rgba(0, 0, 0, 0.06);
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