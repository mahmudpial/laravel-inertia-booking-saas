<script setup>
/**
 * SmartBooking Public Deployment Portal v4.2.5
 * 
 * DESIGN SPECIFICATIONS:
 * 1. Strategic Flow: 5-Phase journey (SKU > Add-on > Node > Time > Sync)
 * 2. Visual Identity: Industrial Obsidian and Indigo Signal Accents.
 * 3. Architecture: Full-screen blueprint grid.
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({ business: Object, services: Array, staff: Array });

const selectedDate = ref('');
const availableSlots = ref([]);
const loading = ref(false);
const currentStep = ref(1);
const selectedAddons = ref([]);

const form = useForm({
    business_id: props.business?.id,
    service_id: '',
    staff_id: '',
    addon_ids: [],
    booking_date: '',
    start_time: '',
    notes: ''
});

const selectedService = computed(() => props.services.find(s => s.id === form.service_id) || null);
const selectedStaff = computed(() => props.staff?.find(s => s.id === form.staff_id) || null);

const totalValuation = computed(() => {
    const base = parseFloat(selectedService.value?.price || 0);
    const addons = selectedAddons.value.reduce((sum, a) => sum + parseFloat(a.price), 0);
    return (base + addons).toFixed(2);
});

const fetchSlots = async () => {
    if (!form.service_id || !selectedDate.value || !form.staff_id) return;
    loading.value = true;
    try {
        const response = await axios.get(route('api.slots'), {
            params: { business_id: props.business.id, service_id: form.service_id, staff_id: form.staff_id, date: selectedDate.value }
        });
        availableSlots.value = response.data;
    } catch (error) { console.error(error); }
    finally { loading.value = false; }
};

const selectService = (id) => {
    form.service_id = id;
    selectedAddons.value = [];
    const service = props.services.find(s => s.id === id);
    currentStep.value = (service?.addons?.length > 0) ? 2 : 3;
};

const toggleAddon = (addon) => {
    const index = selectedAddons.value.findIndex(a => a.id === addon.id);
    if (index > -1) selectedAddons.value.splice(index, 1);
    else selectedAddons.value.push(addon);
};

const selectStaff = (id) => {
    form.staff_id = id;
    currentStep.value = 4;
};

const submit = () => {
    form.booking_date = selectedDate.value;
    form.addon_ids = selectedAddons.value.map(a => a.id);
    form.post(route('booking.store'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('✅ Session Secured and Dispatched.');
            form.reset();
            currentStep.value = 1;
        }
    });
};
</script>

<template>

    <Head :title="business.name + ' | Appointment Gateway'" />

    <div
        class="min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] pb-48 selection:bg-indigo-600 selection:text-white relative">

        <!-- BLUEPRINT BACKGROUND SYSTEM -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
            </div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 pt-16 lg:pt-32">

            <!-- EDITORIAL HERO HEADER -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                <div class="xl:col-span-6 space-y-8 animate-slideUp">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-16 h-16 bg-[#09090B] rounded-[1.5rem] flex items-center justify-center text-white shadow-2xl shadow-indigo-900/40">
                            <img v-if="business.logo" :src="'/storage/' + business.logo"
                                class="w-full h-full object-cover rounded-[1.5rem]" />
                            <span v-else class="text-2xl font-black">{{ business.name.charAt(0) }}</span>
                        </div>
                        <div class="px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full">
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-emerald-600">Encrypted
                                Gateway Active</span>
                        </div>
                    </div>
                    <h1 class="text-7xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                        Book <br /> <span class="text-indigo-600">{{ business.name }}.</span>
                    </h1>
                </div>

                <div
                    class="xl:col-span-6 bg-white border border-slate-200/60 p-10 lg:p-14 rounded-[4rem] shadow-2xl animate-fadeIn">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em] mb-6">Service Briefing
                    </p>
                    <article class="text-slate-500 text-lg leading-relaxed font-medium italic">
                        "Welcome to our professional operational node. We prioritize your privacy and time quantization.
                        Every booking packet is processed with end-to-end masked encryption to ensure total
                        confidentiality."
                    </article>
                </div>
            </div>

            <!-- MAIN OPERATIONAL CANVASS -->
            <div
                class="bg-white border border-slate-200 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] rounded-[5rem] overflow-hidden">

                <!-- PHASE STEPPER -->
                <div class="flex items-center px-10 pt-10 gap-2 sm:gap-4">
                    <div v-for="step in 5" :key="step" class="flex-1 flex flex-col gap-3">
                        <div class="h-1.5 rounded-full transition-all duration-1000"
                            :class="currentStep >= step ? 'bg-indigo-600 shadow-[0_0_12px_#4f46e5]' : 'bg-slate-100'">
                        </div>
                        <span :class="currentStep >= step ? 'text-indigo-600' : 'text-slate-300'"
                            class="text-[8px] font-black uppercase tracking-[0.3em] hidden sm:block">
                            Phase 0{{ step }}
                        </span>
                    </div>
                </div>

                <div class="p-8 lg:p-20">
                    <form @submit.prevent="submit">

                        <!-- PHASE 01: SKU SELECTION -->
                        <Transition name="fade" mode="out-in">
                            <div v-if="currentStep === 1" class="space-y-12">
                                <h2 class="text-4xl font-black tracking-tighter uppercase leading-none">Select <span
                                        class="text-indigo-600">Unit.</span></h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <button v-for="s in services" :key="s.id" type="button" @click="selectService(s.id)"
                                        class="group text-left p-12 rounded-[4rem] border border-slate-100 bg-[#FDFDFF] hover:border-indigo-500 hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                                        <div class="flex justify-between items-start mb-16">
                                            <div
                                                class="w-16 h-14 rounded-2xl bg-[#09090B] flex items-center justify-center text-white transition-all duration-500 shadow-xl">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <p
                                                class="text-3xl font-black text-[#09090B] tracking-tighter group-hover:text-indigo-600 transition-colors">
                                                ${{ s.price }}</p>
                                        </div>
                                        <h3
                                            class="text-2xl font-black text-[#09090B] uppercase tracking-tighter leading-none">
                                            {{ s.name }}</h3>
                                        <p class="text-xs text-slate-400 mt-4 font-black uppercase tracking-[0.3em]">{{
                                            s.duration_minutes }} Minute Operational Block</p>
                                    </button>
                                </div>
                            </div>

                            <!-- PHASE 02: ENHANCEMENTS (ADD-ONS) -->
                            <div v-else-if="currentStep === 2" class="space-y-12">
                                <div class="flex items-center gap-8">
                                    <button @click="currentStep = 1" type="button"
                                        class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl hover:bg-[#09090B] hover:text-white transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <h2 class="text-4xl font-black tracking-tighter uppercase leading-none">Enhance
                                        <span class="text-indigo-600">Packet.</span></h2>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <button v-for="addon in selectedService?.addons" :key="addon.id" type="button"
                                        @click="toggleAddon(addon)" :class="[
                                            'text-left p-8 rounded-[3.5rem] border transition-all duration-500 flex justify-between items-center group',
                                            selectedAddons.find(a => a.id === addon.id) ? 'bg-[#09090B] border-indigo-600 shadow-2xl' : 'bg-white border-slate-100 hover:border-indigo-400'
                                        ]">
                                        <div class="flex items-center gap-6">
                                            <div :class="selectedAddons.find(a => a.id === addon.id) ? 'bg-indigo-600 text-white' : 'bg-slate-50 text-slate-300'"
                                                class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 :class="selectedAddons.find(a => a.id === addon.id) ? 'text-white' : 'text-[#09090B]'"
                                                    class="font-black uppercase text-base tracking-tight leading-none">
                                                    {{ addon.name }}</h4>
                                                <p
                                                    class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-2">
                                                    +{{ addon.duration_minutes }} Min</p>
                                            </div>
                                        </div>
                                        <span
                                            :class="selectedAddons.find(a => a.id === addon.id) ? 'text-indigo-400' : 'text-indigo-600'"
                                            class="text-2xl font-black tracking-tighter">+${{ addon.price }}</span>
                                    </button>
                                </div>
                                <div class="pt-12 border-t border-slate-100 flex items-center justify-between">
                                    <div class="text-left">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2">
                                            Net Accumulation</p>
                                        <h3 class="text-5xl font-black text-[#09090B] tracking-tighter">${{
                                            totalValuation }}</h3>
                                    </div>
                                    <button type="button" @click="currentStep = 3"
                                        class="px-16 py-7 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] shadow-2xl hover:bg-indigo-600 transition-all active:scale-95">Deploy
                                        Roster</button>
                                </div>
                            </div>

                            <!-- PHASE 03: SPECIALIST ASSIGNMENT -->
                            <div v-else-if="currentStep === 3" class="space-y-12">
                                <div class="flex items-center gap-8">
                                    <button @click="currentStep = (selectedService?.addons?.length > 0 ? 2 : 1)"
                                        type="button"
                                        class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl hover:bg-[#09090B] transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <h2
                                        class="text-4xl font-black tracking-tighter uppercase leading-none text-[#09090B]">
                                        Choose <span class="text-indigo-600">Node.</span></h2>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                                    <button v-for="member in staff" :key="member.id" type="button"
                                        @click="selectStaff(member.id)"
                                        class="group p-12 rounded-[4rem] border border-slate-100 bg-white hover:border-indigo-500 hover:shadow-2xl transition-all duration-700 flex flex-col items-center text-center">
                                        <div
                                            class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center font-black text-4xl text-slate-200 group-hover:bg-[#09090B] group-hover:text-white group-hover:rotate-12 transition-all duration-500 mb-10 border-2 border-dashed border-slate-200 shadow-inner">
                                            {{ member.name.charAt(0) }}
                                        </div>
                                        <h3
                                            class="text-xl font-black text-[#09090B] uppercase tracking-tighter leading-none group-hover:text-indigo-600 transition-colors">
                                            {{ member.name }}</h3>
                                        <p
                                            class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.5em] mt-4">
                                            {{ member.role || 'Authorized Specialist' }}</p>
                                    </button>
                                </div>
                            </div>

                            <!-- PHASE 04: OPERATIONAL SYNC (CALENDAR) -->
                            <div v-else-if="currentStep === 4" class="space-y-20">
                                <div class="flex items-center gap-8">
                                    <button @click="currentStep = 3" type="button"
                                        class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl hover:bg-[#09090B] transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <h2 class="text-4xl font-black tracking-tighter uppercase leading-none">Operational
                                        <span class="text-indigo-600">Sync.</span></h2>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-start">
                                    <div
                                        class="bg-slate-50/50 p-12 rounded-[4rem] border border-slate-100 shadow-inner">
                                        <label
                                            class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 mb-10 block">01.
                                            Temporal Selection</label>
                                        <input type="date" v-model="selectedDate" @change="fetchSlots"
                                            class="w-full bg-white border-none rounded-[2rem] p-8 text-xl font-black shadow-2xl focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer uppercase tracking-tighter" />
                                    </div>

                                    <div class="min-h-[400px]">
                                        <label
                                            class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 mb-10 block">02.
                                            Verified Time Windows</label>
                                        <div v-if="loading" class="flex items-center justify-center py-20">
                                            <div
                                                class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin">
                                            </div>
                                        </div>
                                        <div v-else-if="availableSlots.length > 0"
                                            class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                            <button v-for="slot in availableSlots" :key="slot.time" type="button"
                                                @click="form.start_time = slot.time"
                                                :class="form.start_time === slot.time ? 'bg-[#09090B] text-white shadow-2xl scale-105' : 'bg-white border border-slate-100 text-[#09090B] hover:border-indigo-400 shadow-sm'"
                                                class="py-6 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300">
                                                {{ slot.formatted }}
                                            </button>
                                        </div>
                                        <div v-else
                                            class="text-center py-32 px-10 bg-slate-50/50 rounded-[4rem] border-2 border-dashed border-slate-200">
                                            <p
                                                class="text-[10px] font-black text-slate-300 uppercase tracking-[0.8em] leading-loose">
                                                Choose date to scan<br />operational slots</p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="pt-12 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-10">
                                    <div v-if="form.start_time"
                                        class="px-10 py-5 bg-[#09090B] rounded-full flex items-center gap-5 shadow-2xl">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <p class="text-sm font-black text-white uppercase tracking-widest">{{
                                            selectedDate }} at {{ form.start_time }}</p>
                                    </div>
                                    <button type="button" @click="currentStep = 5" :disabled="!form.start_time"
                                        class="w-full sm:w-auto px-16 py-7 bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.5em] rounded-[2rem] shadow-2xl active:scale-95 disabled:opacity-20">
                                        Authorize Sync
                                    </button>
                                </div>
                            </div>

                            <!-- PHASE 05: FINAL DISPATCH (THE RECEIPT) -->
                            <div v-else-if="currentStep === 5" class="max-w-3xl mx-auto space-y-16 animate-slideUp">
                                <div class="text-center space-y-6">
                                    <h2
                                        class="text-5xl lg:text-7xl font-black tracking-tighter uppercase text-[#09090B]">
                                        Registry <span class="text-indigo-600">Confirm.</span></h2>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.6em]">Final
                                        Authorized Packet Review</p>
                                </div>

                                <div
                                    class="bg-[#09090B] p-12 lg:p-20 rounded-[5rem] text-white shadow-[0_60px_150px_rgba(0,0,0,0.4)] relative overflow-hidden group border border-white/5">
                                    <div
                                        class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e533_0%,transparent_70%)] opacity-50">
                                    </div>
                                    <div class="relative z-10 space-y-12">
                                        <div class="flex flex-col lg:flex-row justify-between gap-10">
                                            <div class="space-y-4">
                                                <p
                                                    class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">
                                                    Selected Unit</p>
                                                <h3 class="text-5xl font-black uppercase tracking-tighter leading-none">
                                                    {{ selectedService?.name }}</h3>
                                            </div>
                                            <div class="lg:text-right space-y-4">
                                                <p
                                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.5em]">
                                                    Principal Expert</p>
                                                <p
                                                    class="text-2xl font-black uppercase tracking-tighter text-indigo-200 leading-none">
                                                    {{ selectedStaff?.name }}</p>
                                            </div>
                                        </div>

                                        <div v-if="selectedAddons.length > 0" class="pt-10 border-t border-white/10">
                                            <p
                                                class="text-[10px] font-black text-slate-500 uppercase tracking-[0.5em] mb-8">
                                                Enhancement Modules</p>
                                            <div class="space-y-4">
                                                <div v-for="a in selectedAddons" :key="a.id"
                                                    class="flex justify-between items-center bg-white/5 p-6 rounded-3xl border border-white/5">
                                                    <span
                                                        class="text-sm font-black uppercase tracking-widest text-slate-300">{{
                                                        a.name }}</span>
                                                    <span class="text-lg font-black text-indigo-400">+${{ a.price
                                                        }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex flex-col md:flex-row justify-between items-end pt-12 border-t border-white/10 gap-10">
                                            <div class="text-left space-y-4">
                                                <p
                                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.5em]">
                                                    Synchronized Window</p>
                                                <p class="text-2xl font-black uppercase tracking-tighter leading-none">
                                                    {{ selectedDate }}</p>
                                                <p
                                                    class="text-2xl font-black uppercase tracking-tighter text-indigo-400 leading-none">
                                                    {{ form.start_time }}</p>
                                            </div>
                                            <div class="text-right space-y-4">
                                                <p
                                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.5em]">
                                                    Total Yield Value</p>
                                                <p
                                                    class="text-7xl font-black tracking-tighter text-emerald-400 leading-none">
                                                    ${{ totalValuation }}</p>
                                            </div>
                                        </div>
                                        <button @click="currentStep = 1"
                                            class="text-[10px] font-black uppercase tracking-[0.4em] border-b-4 border-indigo-600 pb-2 hover:text-indigo-400 transition-all">Re-initialize
                                            Packet</button>
                                    </div>
                                </div>

                                <div class="space-y-8 px-4">
                                    <label
                                        class="text-[11px] font-black uppercase tracking-[0.8em] text-slate-400 block text-center">Optional
                                        Session Requirements</label>
                                    <textarea v-model="form.notes" rows="4"
                                        placeholder="Transmit operational intelligence to the specialist..."
                                        class="w-full bg-slate-50 border-none rounded-[3rem] p-10 text-lg font-bold text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all resize-none shadow-inner leading-relaxed italic"></textarea>
                                </div>

                                <button type="submit" :disabled="form.processing"
                                    class="w-full py-10 bg-indigo-600 text-white text-[12px] font-black uppercase tracking-[0.8em] rounded-[3rem] hover:bg-[#09090B] transition-all shadow-[0_40px_100px_rgba(79,70,229,0.3)] active:scale-95 disabled:opacity-50">
                                    Deploy Operational Session
                                </button>
                            </div>
                        </Transition>

                    </form>
                </div>
            </div>

            <footer class="mt-32 text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1.5em]">Secure Infrastructure Node
                    v4.2.5 | SmartBooking Engine</p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&display=swap');

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

.fade-enter-active,
.fade-leave-active {
    transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.98) translateY(20px);
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
    animation: slideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.shadow-inner {
    box-shadow: inset 0 6px 16px 0 rgba(0, 0, 0, 0.08);
}

input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(0.1) brightness(0.5);
    width: 24px;
    height: 24px;
}
</style>