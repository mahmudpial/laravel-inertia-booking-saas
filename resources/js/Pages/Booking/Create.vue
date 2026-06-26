<script setup>
import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({ business: Object, services: Array });

const selectedDate = ref('');
const availableSlots = ref([]);
const loading = ref(false);
const currentStep = ref(1);

const form = useForm({
    business_id: props.business.id,
    service_id: '',
    booking_date: '',
    start_time: '',
    notes: ''
});

const selectedService = computed(() =>
    props.services.find(s => s.id === form.service_id)
);

const fetchSlots = async () => {
    if (!form.service_id || !selectedDate.value) return;
    loading.value = true;
    try {
        const response = await axios.get(route('api.slots'), {
            params: { business_id: props.business.id, service_id: form.service_id, date: selectedDate.value }
        });
        availableSlots.value = response.data;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const selectService = (id) => {
    form.service_id = id;
    currentStep.value = 2;
    if (selectedDate.value) fetchSlots();
};

const submit = () => {
    form.booking_date = selectedDate.value;
    form.post(route('booking.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            selectedDate.value = '';
            availableSlots.value = [];
            currentStep.value = 1;
        }
    });
};
</script>

<template>

    <Head :title="'Book Appointment | ' + business.name" />

    <!-- RADIAL BACKGROUND (Matches Edit.vue) -->
    <div
        class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] font-sans antialiased text-[#11131A]">

        <div class="relative z-10 max-w-5xl mx-auto px-4 py-12 lg:py-24">

            <!-- EDITORIAL HERO HEADER -->
            <header class="text-center mb-16 lg:mb-24">
                <div class="inline-flex mb-8">
                    <div
                        class="w-20 h-20 bg-[#11131A] rounded-[2rem] flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-indigo-900/20">
                        {{ business.name.charAt(0) }}
                    </div>
                </div>
                <p class="text-indigo-600 font-black text-xs uppercase tracking-[0.4em] mb-4">Official Booking Portal
                </p>
                <h1 class="text-5xl lg:text-7xl font-black tracking-tighter leading-none mb-6">
                    {{ business.name }}<span class="text-slate-300">.</span>
                </h1>
                <p class="text-slate-500 max-w-lg mx-auto text-lg font-medium leading-relaxed">
                    {{ business.description || `Welcome to our premium service center. Select a slot to begin your
                    experience.` }}
                </p>
            </header>

            <!-- MAIN INTERFACE CARD -->
            <div
                class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[3.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">

                <!-- MINIMAL STEPPER -->
                <div class="flex items-center px-10 pt-10 gap-4">
                    <div v-for="step in 3" :key="step" class="flex-1 flex flex-col gap-2">
                        <div class="h-1 rounded-full transition-all duration-700"
                            :class="currentStep >= step ? 'bg-indigo-600' : 'bg-slate-100'"></div>
                        <span :class="currentStep >= step ? 'text-indigo-600' : 'text-slate-300'"
                            class="text-[10px] font-black uppercase tracking-widest">
                            Phase 0{{ step }}
                        </span>
                    </div>
                </div>

                <div class="p-8 lg:p-16">
                    <form @submit.prevent="submit">

                        <!-- STEP 1: SERVICE GALLERY -->
                        <Transition name="fade" mode="out-in">
                            <div v-if="currentStep === 1" class="space-y-10">
                                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                                    <h2 class="text-3xl font-black tracking-tight">Select <span
                                            class="text-slate-400 font-medium">Service</span></h2>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">All prices in
                                        USD</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <button v-for="s in services" :key="s.id" type="button" @click="selectService(s.id)"
                                        class="group text-left p-8 rounded-[2.5rem] border border-slate-100 bg-white hover:border-indigo-500 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 relative overflow-hidden">
                                        <div
                                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                                        </div>

                                        <div class="relative z-10 flex justify-between items-start mb-12">
                                            <div
                                                class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <div class="text-right">
                                                <p
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                    Starting from</p>
                                                <p class="text-2xl font-black text-[#11131A]">${{ s.price }}</p>
                                            </div>
                                        </div>

                                        <h3
                                            class="text-xl font-black text-[#11131A] group-hover:text-indigo-600 transition-colors">
                                            {{ s.name }}</h3>
                                        <p class="text-xs text-slate-400 mt-2 font-bold uppercase tracking-[0.2em]">{{
                                            s.duration_minutes }} Minutes Session</p>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 2: ENGINEERED SCHEDULING -->
                            <div v-else-if="currentStep === 2" class="space-y-12">
                                <div class="flex items-center gap-6">
                                    <button @click="currentStep = 1" type="button"
                                        class="w-12 h-12 bg-slate-100 flex items-center justify-center rounded-2xl hover:bg-[#11131A] hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <div>
                                        <h2 class="text-3xl font-black tracking-tight">Scheduling.</h2>
                                        <p class="text-sm font-bold text-indigo-600 uppercase tracking-widest">{{
                                            selectedService.name }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                                    <!-- Calendar Input Container -->
                                    <div class="bg-slate-50/50 p-8 rounded-[2.5rem] border border-slate-100">
                                        <label
                                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6 block">01.
                                            Pick a Date</label>
                                        <input type="date" v-model="selectedDate" @change="fetchSlots"
                                            class="w-full bg-white border-none rounded-2xl p-6 text-base font-black shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer" />
                                    </div>

                                    <!-- Time Slot Grid -->
                                    <div>
                                        <label
                                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6 block">02.
                                            Select Available Window</label>

                                        <div v-if="loading" class="flex items-center justify-center py-12">
                                            <div
                                                class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin">
                                            </div>
                                        </div>

                                        <div v-else-if="availableSlots.length > 0"
                                            class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            <button v-for="slot in availableSlots" :key="slot.time" type="button"
                                                @click="form.start_time = slot.time"
                                                :class="form.start_time === slot.time ? 'bg-[#11131A] text-white shadow-2xl scale-105' : 'bg-white border border-slate-100 text-slate-600 hover:border-indigo-400'"
                                                class="py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300">
                                                {{ slot.formatted }}
                                            </button>
                                        </div>

                                        <div v-else
                                            class="text-center py-12 px-8 bg-slate-50/50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                            <p
                                                class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-loose">
                                                Select a date to scan<br />available time slots</p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                                    <div v-if="form.start_time"
                                        class="px-6 py-3 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                        <p class="text-xs font-black text-indigo-700 uppercase tracking-widest">{{
                                            selectedDate }} at {{ form.start_time }}</p>
                                    </div>
                                    <button type="button" @click="currentStep = 3" :disabled="!form.start_time"
                                        class="w-full sm:w-auto px-10 py-5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-indigo-600 transition-all shadow-xl disabled:opacity-20 active:scale-95">
                                        Finalize Details
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 3: SUMMARY & COMMIT -->
                            <div v-else-if="currentStep === 3" class="max-w-xl mx-auto space-y-12">
                                <div class="text-center">
                                    <h2 class="text-3xl font-black tracking-tight mb-2">Finalize Booking.</h2>
                                    <p class="text-sm font-medium text-slate-500 uppercase tracking-widest">Review and
                                        confirm your session</p>
                                </div>

                                <div
                                    class="bg-[#11131A] p-10 rounded-[3rem] text-white shadow-2xl relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-8 opacity-10">
                                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="relative z-10">
                                        <p
                                            class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-6">
                                            Reservation Summary</p>
                                        <h3 class="text-2xl font-black mb-2">{{ selectedService.name }}</h3>
                                        <div class="flex items-center gap-3 text-slate-400 font-bold text-sm">
                                            <span>{{ selectedDate }}</span>
                                            <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                                            <span class="text-indigo-400">{{ form.start_time }}</span>
                                        </div>
                                        <button @click="currentStep = 1"
                                            class="mt-8 text-[10px] font-black uppercase tracking-widest border-b border-indigo-500 pb-1 hover:text-indigo-400 transition-colors">Edit
                                            Appointment</button>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 block ml-2">Special
                                        Requests (Optional)</label>
                                    <textarea v-model="form.notes" rows="4" placeholder="Mention any preferences..."
                                        class="w-full bg-slate-50 border-none rounded-[2rem] p-6 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all resize-none"></textarea>
                                </div>

                                <button type="submit" :disabled="form.processing"
                                    class="w-full py-6 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[2rem] hover:bg-indigo-700 transition-all shadow-2xl shadow-indigo-200 active:scale-[0.98] disabled:opacity-50">
                                    {{ form.processing ? 'Securing Slot...' : 'Confirm Appointment' }}
                                </button>
                            </div>
                        </Transition>

                    </form>
                </div>
            </div>

            <!-- FOOTER INFO -->
            <footer class="mt-20 text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Powered by SmartBooking
                    Engine v2.0</p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(input),
:deep(textarea),
:deep(h1),
:deep(h2),
:deep(h3) {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(10px);
}

/* Custom Date input styling */
input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(0.1) brightness(0.5);
    width: 20px;
    height: 20px;
}
</style>