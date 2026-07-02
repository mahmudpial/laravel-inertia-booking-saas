<script setup>
/**
 * Brand Studio v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Asset Synchronization: Real-time image processing and preview.
 * 2. Identity Registry: Management of legal entity labels and HQ data.
 * 3. Strategic Content: Integrated branding intelligence article.
 * 
 * @author Pial Mahmud (Lead Architect)
 */

import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ business: Object });

// Reactive Asset Previews (with null safety)
const logoPreview = ref(props.business?.logo ? `/storage/${props.business.logo}` : null);
const bannerPreview = ref(props.business?.banner ? `/storage/${props.business.banner}` : null);

const form = useForm({
    _method: 'POST', // Enforced for multipart/form-data compatibility
    name: props.business?.name || '',
    description: props.business?.description || '',
    address: props.business?.address || '',
    logo: null,
    banner: null
});

const handleFile = (e, key) => {
    const file = e.target.files[0];
    if (file) {
        form[key] = file;
        const reader = new FileReader();
        reader.onload = (event) => {
            if (key === 'logo') logoPreview.value = event.target.result;
            if (key === 'banner') bannerPreview.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitSettings = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => alert('✅ System Intelligence Synchronized.'),
    });
};
</script>

<template>

    <Head title="Brand Studio | Strategic Identity Hub" />

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

                <!-- EDITORIAL HERO SECTION -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-8 animate-slideUp">
                        <div class="flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Identity
                                    Protocol: Operational</span>
                            </div>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            Brand <br />
                            <span class="text-indigo-600 font-medium text-5xl lg:text-8xl">Studio.</span>
                        </h1>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.5em] text-[10px]">Registry Module
                            v4.2.0-Alpha</p>
                    </div>

                    <!-- STUDIO INSIGHT ARTICLE (Logic Hub) -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Strategic
                                    Briefing</p>
                                <span
                                    class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 py-1 bg-white/5 rounded-full">Secure
                                    Transmission</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">The
                                Architecture of <br /> Permanent Impressions.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    In a high-frequency professional ecosystem, your **Brand Studio** acts as the
                                    primary interface of trust. Research indicates that systematic visual consistency
                                    can elevate client retention by up to **42%**.
                                </p>
                                <p>
                                    Our aim is total data integrity. When a specialist node is activated within your
                                    roster, the brand mark provides the legal and professional validation required for
                                    global synchronization.
                                </p>
                            </article>
                            <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-[10px] font-black shadow-lg">
                                    AI</div>
                                <p
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-loose">
                                    Data Security and Confidentiality is prioritized <br /> via the Node-Sentinel
                                    Handshake.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OPERATIONAL WORKSPACE -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

                    <!-- NAVIGATION & KPI SIDEBAR -->
                    <aside class="lg:col-span-3 space-y-10">
                        <nav
                            class="flex lg:flex-col overflow-x-auto lg:overflow-visible gap-4 pb-6 lg:pb-0 no-scrollbar sticky top-28">
                            <div
                                class="flex-shrink-0 px-8 py-5 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] shadow-2xl shadow-indigo-900/20 border border-white/5 cursor-default">
                                Profile Identity
                            </div>
                            <div
                                class="flex-shrink-0 px-8 py-5 bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] hover:bg-slate-50 transition-all cursor-pointer">
                                Audit Log History
                            </div>
                        </nav>

                        <!-- IDENTITY COMPLETION WIDGET -->
                        <div
                            class="hidden lg:block p-10 bg-white border border-slate-200 rounded-[3rem] shadow-xl relative overflow-hidden group">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-8">System
                                Readiness</p>
                            <div class="space-y-8">
                                <div v-for="(val, key) in { 'Brand Mark': 'Ready', 'Legal Label': 'Synced', 'Encryption': 'Active' }"
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

                    <!-- BRANDING MAIN WORKSPACE -->
                    <main class="lg:col-span-9 space-y-16 animate-fadeIn">

                        <!-- MODULE 01: BANNER DEPLOYMENT -->
                        <div
                            class="bg-white border border-slate-200/60 rounded-[4rem] p-4 shadow-2xl shadow-slate-200/40 group relative">
                            <div class="absolute top-10 left-10 z-20">
                                <span
                                    class="px-4 py-1.5 bg-[#09090B] text-white text-[9px] font-black uppercase tracking-[0.4em] rounded-full shadow-2xl">01.
                                    Studio Backdrop</span>
                            </div>
                            <div
                                class="relative aspect-[21/7] rounded-[3.5rem] bg-slate-50 overflow-hidden border-2 border-dashed border-slate-200 hover:border-indigo-400 transition-all duration-700 shadow-inner">
                                <img v-if="bannerPreview" :src="bannerPreview"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                                <div v-else
                                    class="absolute inset-0 flex flex-col items-center justify-center text-slate-300">
                                    <svg class="w-16 h-16 mb-4 opacity-10" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.5em]">Synchronize System
                                        Banner Asset</span>
                                </div>
                                <div
                                    class="absolute inset-0 bg-[#09090B]/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-sm cursor-pointer">
                                    <div
                                        class="px-10 py-5 bg-white text-[#09090B] rounded-full text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl scale-90 group-hover:scale-100 transition-all">
                                        Push New Asset</div>
                                </div>
                                <input type="file" @change="(e) => handleFile(e, 'banner')"
                                    class="absolute inset-0 opacity-0 cursor-pointer" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                            <!-- MODULE 02: BRAND MARK -->
                            <div
                                class="md:col-span-5 bg-white border border-slate-200/60 rounded-[4rem] p-12 shadow-xl shadow-slate-200/30 flex flex-col items-center text-center group">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-10">02.
                                    Brand Identifier</p>
                                <div
                                    class="relative w-64 h-64 rounded-[3rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all duration-700 group-hover:border-indigo-400 shadow-inner">
                                    <img v-if="logoPreview" :src="logoPreview"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                    <div v-else class="text-slate-200">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-indigo-600/90 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center cursor-pointer">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <input type="file" @change="(e) => handleFile(e, 'logo')"
                                        class="absolute inset-0 opacity-0 cursor-pointer" />
                                </div>
                                <p
                                    class="mt-12 text-[10px] font-bold text-slate-300 uppercase tracking-[0.4em] leading-relaxed">
                                    System Architecture <br /> Auto-Scaling 1:1</p>
                            </div>

                            <!-- MODULE 03: IDENTITY FIELDS -->
                            <div
                                class="md:col-span-7 bg-white border border-slate-200/60 rounded-[4rem] p-12 lg:p-14 shadow-xl shadow-slate-200/30 space-y-12 flex flex-col justify-center">
                                <div class="group">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-4 block ml-1">Legal
                                        Label</label>
                                    <input v-model="form.name"
                                        class="w-full bg-slate-50 border-none rounded-[2rem] p-8 text-2xl font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner tracking-tighter uppercase" />
                                </div>
                                <div class="group">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-4 block ml-1">Headquarters
                                        Location</label>
                                    <input v-model="form.address"
                                        class="w-full bg-slate-50 border-none rounded-[2rem] p-8 text-xl font-bold text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner tracking-tight" />
                                </div>
                            </div>
                        </div>

                        <!-- MODULE 04: BUSINESS BIO -->
                        <div
                            class="bg-white border border-slate-200/60 rounded-[4rem] p-12 lg:p-16 shadow-xl shadow-slate-200/30">
                            <div class="flex items-center justify-between mb-8 px-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] block">Operational
                                    Bio & Aim</label>
                                <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">Public
                                    Intelligence Output</span>
                            </div>
                            <textarea v-model="form.description" rows="8"
                                class="w-full bg-slate-50 border-none rounded-[3rem] p-10 text-xl font-medium text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all resize-none shadow-inner leading-relaxed italic"
                                placeholder="Introduce your business mission..."></textarea>
                        </div>

                        <!-- MODULE 05: TACTICAL DEPLOYMENT -->
                        <div
                            class="pt-20 border-t border-slate-100 flex flex-col xl:flex-row items-center justify-between gap-16">
                            <div class="flex items-start gap-8 text-slate-400 max-w-lg">
                                <div
                                    class="p-5 bg-[#09090B] rounded-[2rem] text-indigo-500 shrink-0 shadow-2xl border border-white/10">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-[13px] font-medium leading-relaxed italic uppercase tracking-wider">
                                    Deploying updates will force-synchronize all brand assets across the SmartBooking
                                    infrastructure. Data integrity is guaranteed via secure handshake protocols.</p>
                            </div>

                            <button @click="submitSettings" :disabled="form.processing"
                                class="w-full md:w-auto px-20 py-8 bg-[#09090B] text-white text-[11px] font-black uppercase tracking-[0.6em] rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:bg-indigo-600 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-10 group">
                                <svg v-if="form.processing" class="h-6 w-6 animate-spin" viewBox="0 0 24 24"
                                    fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span class="group-hover:translate-x-2 transition-transform duration-500">{{
                                    form.processing ? 'SYNCHRONIZING...' : 'Authorize & Push Updates' }}</span>
                            </button>
                        </div>
                    </main>
                </div>

                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1.5em]">Command Infrastructure
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
:deep(textarea),
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
</style>