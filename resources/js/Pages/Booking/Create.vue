<script setup>
/**
 * SmartBooking Public Client Booking Experience
 * Modern Cal.com / Airbnb Luxe Architecture
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref, computed } from 'vue';
import { useForm, Head, Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    business: Object,
    services: Array,
    staff: Array
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user);

const selectedDate = ref('');
const availableSlots = ref([]);
const loadingSlots = ref(false);
const currentStep = ref(1);
const selectedAddons = ref([]);
const bookingSuccess = ref(false);
const confirmedBookingData = ref(null);

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

const totalDuration = computed(() => {
    const base = selectedService.value?.duration_minutes || 0;
    const addons = selectedAddons.value.reduce((sum, a) => sum + (a.duration_minutes || 0), 0);
    return base + addons;
});

const totalPrice = computed(() => {
    const base = parseFloat(selectedService.value?.price || 0);
    const addons = selectedAddons.value.reduce((sum, a) => sum + parseFloat(a.price || 0), 0);
    return (base + addons).toFixed(2);
});

const fetchSlots = async () => {
    if (!form.service_id || !selectedDate.value || !form.staff_id) return;
    loadingSlots.value = true;
    form.start_time = '';
    try {
        const response = await axios.get(route('api.slots'), {
            params: {
                business_id: props.business.id,
                service_id: form.service_id,
                staff_id: form.staff_id,
                date: selectedDate.value
            }
        });
        availableSlots.value = response.data;
    } catch (error) {
        console.error('Failed to load slots', error);
        availableSlots.value = [];
    } finally {
        loadingSlots.value = false;
    }
};

const selectService = (id) => {
    form.service_id = id;
    selectedAddons.value = [];
    const service = props.services.find(s => s.id === id);
    if (service?.addons && service.addons.length > 0) {
        currentStep.value = 2;
    } else {
        currentStep.value = 3;
    }
};

const toggleAddon = (addon) => {
    const idx = selectedAddons.value.findIndex(a => a.id === addon.id);
    if (idx > -1) selectedAddons.value.splice(idx, 1);
    else selectedAddons.value.push(addon);
};

const selectStaff = (id) => {
    form.staff_id = id;
    currentStep.value = 4;
    // Set default date to today or tomorrow if not set
    if (!selectedDate.value) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        selectedDate.value = tomorrow.toISOString().split('T')[0];
    }
    fetchSlots();
};

const selectSlot = (time) => {
    form.start_time = time;
};

const submitBooking = () => {
    form.booking_date = selectedDate.value;
    form.addon_ids = selectedAddons.value.map(a => a.id);

    form.post(route('booking.store'), {
        preserveScroll: true,
        onSuccess: () => {
            confirmedBookingData.value = {
                service: selectedService.value?.name,
                staff: selectedStaff.value?.name,
                date: form.booking_date,
                time: form.start_time,
                total: totalPrice.value
            };
            bookingSuccess.value = true;
            form.reset();
        }
    });
};

const resetFlow = () => {
    bookingSuccess.value = false;
    currentStep.value = 1;
    selectedAddons.value = [];
    selectedDate.value = '';
    availableSlots.value = [];
};

// Google Calendar Quick Export Link
const googleCalendarLink = computed(() => {
    if (!confirmedBookingData.value) return '#';
    const title = encodeURIComponent(`${confirmedBookingData.value.service} at ${props.business.name}`);
    const details = encodeURIComponent(`Appointment with ${confirmedBookingData.value.staff}. Total: $${confirmedBookingData.value.total}`);
    const location = encodeURIComponent(props.business.address || props.business.name);
    return `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&details=${details}&location=${location}`;
});
</script>

<template>
    <Head :title="business.name + ' | Book Appointment Online'" />

    <div class="min-h-screen bg-slate-50/70 text-slate-900 font-sans antialiased selection:bg-brand-600 selection:text-white pb-24">

        <!-- Blueprint Background Pattern -->
        <div class="fixed inset-0 z-0 pointer-events-none bg-dot-grid opacity-60"></div>

        <!-- =========================================================
             1. PUBLIC TOP BRAND NAVBAR
             ========================================================= -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/70">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <Link :href="route('booking.page', business.slug)" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-xs">
                        <img v-if="business.logo" :src="'/storage/' + business.logo" class="w-full h-full object-cover rounded-xl" />
                        <span v-else>{{ business.name.charAt(0) }}</span>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-sm font-bold text-slate-900 tracking-tight leading-tight">{{ business.name }}</span>
                        <span class="text-[11px] text-slate-500 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Online Booking Gateway
                        </span>
                    </div>
                </Link>

                <div class="flex items-center gap-3">
                    <template v-if="authUser">
                        <Link
                            :href="route('customer.dashboard')"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors"
                        >
                            My Agenda
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors"
                        >
                            Sign In
                        </Link>
                        <Link
                            :href="route('register')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-xs transition-colors"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- =========================================================
             2. BUSINESS IDENTITY HERO CARD
             ========================================================= -->
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 pt-8 pb-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-2xl shrink-0 shadow-lg shadow-brand-500/20">
                        <img v-if="business.logo" :src="'/storage/' + business.logo" class="w-full h-full object-cover rounded-2xl" />
                        <span v-else>{{ business.name.charAt(0) }}</span>
                    </div>
                    <div class="space-y-1 text-left">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 font-display">
                                {{ business.name }}
                            </h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                                Verified Business
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 max-w-xl">
                            {{ business.description || 'Welcome! Schedule your appointment in a few easy steps below.' }}
                        </p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 pt-1">
                            <span v-if="business.address" class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ business.address }}
                            </span>
                            <span v-if="business.phone" class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ business.phone }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Instant Pricing Summary Capsule -->
                <div v-if="selectedService" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 min-w-[200px] text-left">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Selected Offering</p>
                    <p class="text-sm font-bold text-slate-900 truncate">{{ selectedService.name }}</p>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-500">{{ totalDuration }} mins total</span>
                        <span class="text-base font-extrabold text-brand-600 font-display">${{ totalPrice }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================
             3. MAIN BOOKING WORKFLOW CANVAS
             ========================================================= -->
        <main class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 pt-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-card overflow-hidden">

                <!-- STEP PROGRESS BAR -->
                <div class="px-6 sm:px-10 pt-6 pb-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center justify-between">
                        <div
                            v-for="(stepName, idx) in ['Service', 'Add-ons', 'Specialist', 'Date & Time', 'Confirm']"
                            :key="idx"
                            class="flex-1 flex flex-col items-center relative group"
                        >
                            <div class="flex items-center w-full">
                                <div
                                    :class="[
                                        'w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all z-10 shrink-0 mx-auto',
                                        currentStep > idx + 1
                                            ? 'bg-emerald-500 text-white shadow-sm'
                                            : currentStep === idx + 1
                                                ? 'bg-brand-600 text-white ring-4 ring-brand-100 shadow-md shadow-brand-500/25'
                                                : 'bg-slate-200 text-slate-500'
                                    ]"
                                >
                                    <svg v-if="currentStep > idx + 1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else>{{ idx + 1 }}</span>
                                </div>
                            </div>
                            <span
                                :class="[
                                    'text-[10px] sm:text-xs font-medium mt-2 hidden sm:block',
                                    currentStep === idx + 1 ? 'text-brand-600 font-bold' : 'text-slate-500'
                                ]"
                            >
                                {{ stepName }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- STEP VIEWS -->
                <div class="p-6 sm:p-10">

                    <!-- =========================================================
                         STEP 1: SELECT SERVICE
                         ========================================================= -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <div class="text-left">
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 font-display">
                                Choose a Service
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Select from our professional offerings below to begin scheduling.
                            </p>
                        </div>

                        <div v-if="services?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button
                                v-for="service in services"
                                :key="service.id"
                                type="button"
                                @click="selectService(service.id)"
                                :class="[
                                    'text-left p-6 rounded-2xl border transition-all duration-200 relative group hover:shadow-card flex flex-col justify-between',
                                    form.service_id === service.id
                                        ? 'bg-brand-50/50 border-brand-500 ring-2 ring-brand-500/20'
                                        : 'bg-white border-slate-200/80 hover:border-brand-400'
                                ]"
                            >
                                <div>
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <span class="text-xl font-bold text-slate-900 font-display">
                                            ${{ service.price }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                                        {{ service.name }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                        {{ service.description || 'Professional session tailored to your requirements.' }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ service.duration_minutes }} Minutes
                                    </span>
                                    <span v-if="service.addons?.length" class="text-brand-600 font-medium">
                                        {{ service.addons.length }} add-ons available
                                    </span>
                                </div>
                            </button>
                        </div>

                        <div v-else class="text-center py-16 px-6">
                            <p class="text-sm text-slate-500">No active services currently listed for this business.</p>
                        </div>
                    </div>

                    <!-- =========================================================
                         STEP 2: ENHANCEMENTS & ADD-ONS (Optional)
                         ========================================================= -->
                    <div v-else-if="currentStep === 2" class="space-y-6">
                        <div class="flex items-center justify-between pb-2">
                            <div class="flex items-center gap-3">
                                <button
                                    @click="currentStep = 1"
                                    type="button"
                                    class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 font-display">
                                        Customize with Add-ons
                                    </h2>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                        Enhance your {{ selectedService?.name }} appointment (optional).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div
                                v-for="addon in selectedService?.addons"
                                :key="addon.id"
                                @click="toggleAddon(addon)"
                                :class="[
                                    'p-5 rounded-2xl border cursor-pointer transition-all flex items-center justify-between group',
                                    selectedAddons.find(a => a.id === addon.id)
                                        ? 'bg-brand-50 border-brand-500 ring-2 ring-brand-500/20 shadow-xs'
                                        : 'bg-white border-slate-200 hover:border-slate-300'
                                ]"
                            >
                                <div class="flex items-center gap-3.5">
                                    <div
                                        :class="[
                                            'w-5 h-5 rounded-md flex items-center justify-center border transition-colors',
                                            selectedAddons.find(a => a.id === addon.id)
                                                ? 'bg-brand-600 border-brand-600 text-white'
                                                : 'border-slate-300 bg-white'
                                        ]"
                                    >
                                        <svg v-if="selectedAddons.find(a => a.id === addon.id)" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">{{ addon.name }}</h4>
                                        <p class="text-xs text-slate-500">+{{ addon.duration_minutes }} minutes</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-brand-600">+${{ addon.price }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-500">Subtotal</span>
                                <p class="text-xl font-bold text-slate-900 font-display">${{ totalPrice }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    @click="currentStep = 3"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                                >
                                    Skip Add-ons
                                </button>
                                <button
                                    type="button"
                                    @click="currentStep = 3"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/25 transition-all"
                                >
                                    Continue to Specialist
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================================
                         STEP 3: SELECT SPECIALIST
                         ========================================================= -->
                    <div v-else-if="currentStep === 3" class="space-y-6">
                        <div class="flex items-center gap-3 pb-2">
                            <button
                                @click="currentStep = (selectedService?.addons?.length > 0 ? 2 : 1)"
                                type="button"
                                class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 font-display">
                                    Select Specialist
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                    Choose the team member you would like to handle your session.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <button
                                v-for="member in staff"
                                :key="member.id"
                                type="button"
                                @click="selectStaff(member.id)"
                                :class="[
                                    'p-6 rounded-2xl border text-center transition-all duration-200 flex flex-col items-center hover:shadow-card group',
                                    form.staff_id === member.id
                                        ? 'bg-brand-50/50 border-brand-500 ring-2 ring-brand-500/20'
                                        : 'bg-white border-slate-200/80 hover:border-brand-400'
                                ]"
                            >
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-900 text-white font-bold text-xl flex items-center justify-center mb-4 shadow-md group-hover:scale-105 transition-transform">
                                    {{ member.name.charAt(0).toUpperCase() }}
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                                    {{ member.name }}
                                </h3>
                                <span class="inline-flex mt-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                    {{ member.role || 'Certified Specialist' }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- =========================================================
                         STEP 4: SELECT DATE & TIME
                         ========================================================= -->
                    <div v-else-if="currentStep === 4" class="space-y-6">
                        <div class="flex items-center gap-3 pb-2">
                            <button
                                @click="currentStep = 3"
                                type="button"
                                class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 font-display">
                                    Select Date & Time
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                    Pick an available date and booking interval with {{ selectedStaff?.name }}.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                            <!-- Date Selector (5 cols) -->
                            <div class="lg:col-span-5 p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                    1. Choose Appointment Date
                                </label>
                                <input
                                    type="date"
                                    v-model="selectedDate"
                                    @change="fetchSlots"
                                    :min="new Date().toISOString().split('T')[0]"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all cursor-pointer shadow-xs"
                                />
                                <p class="text-xs text-slate-500">
                                    Available slots will dynamically calculate based on duration ({{ totalDuration }} mins).
                                </p>
                            </div>

                            <!-- Time Slots (7 cols) -->
                            <div class="lg:col-span-7 space-y-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                    2. Available Time Slots
                                </label>

                                <div v-if="loadingSlots" class="py-16 text-center">
                                    <div class="w-8 h-8 border-3 border-brand-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                                    <p class="text-xs text-slate-500 font-medium">Checking available calendar windows...</p>
                                </div>

                                <div v-else-if="availableSlots.length > 0" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                    <button
                                        v-for="slot in availableSlots"
                                        :key="slot.time"
                                        type="button"
                                        @click="selectSlot(slot.time)"
                                        :class="[
                                            'py-3 px-4 rounded-xl text-xs font-semibold transition-all shadow-xs border',
                                            form.start_time === slot.time
                                                ? 'bg-brand-600 text-white border-brand-600 shadow-md shadow-brand-500/30'
                                                : 'bg-white text-slate-700 border-slate-200 hover:border-brand-500 hover:bg-brand-50/50'
                                        ]"
                                    >
                                        {{ slot.formatted }}
                                    </button>
                                </div>

                                <div v-else class="text-center py-16 px-6 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-700">No slots available on this date</p>
                                    <p class="text-xs text-slate-500 mt-1">Please select another date to view open windows.</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                            <div v-if="form.start_time" class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-semibold text-slate-800">
                                    {{ selectedDate }} at {{ form.start_time }}
                                </span>
                            </div>
                            <div v-else class="text-xs text-slate-400">
                                Select a slot above to proceed
                            </div>

                            <button
                                type="button"
                                @click="currentStep = 5"
                                :disabled="!form.start_time"
                                class="px-6 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/25 transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                            >
                                Review Details &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- =========================================================
                         STEP 5: CONFIRM & SUBMIT
                         ========================================================= -->
                    <div v-else-if="currentStep === 5" class="max-w-2xl mx-auto space-y-6">
                        <div class="text-center space-y-1">
                            <h2 class="text-2xl font-bold tracking-tight text-slate-900 font-display">
                                Review & Confirm Appointment
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500">
                                Please verify your appointment summary before finalizing.
                            </p>
                        </div>

                        <!-- Summary Card -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-6 space-y-4 text-left">
                            <div class="flex items-start justify-between pb-4 border-b border-slate-200/70">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Service</span>
                                    <h3 class="text-lg font-bold text-slate-900">{{ selectedService?.name }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ totalDuration }} minutes total duration</p>
                                </div>
                                <span class="text-2xl font-extrabold text-brand-600 font-display">${{ totalPrice }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Specialist</span>
                                    <p class="font-semibold text-slate-800 mt-0.5">{{ selectedStaff?.name }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Scheduled Time</span>
                                    <p class="font-semibold text-slate-800 mt-0.5">{{ selectedDate }} @ {{ form.start_time }}</p>
                                </div>
                            </div>

                            <div v-if="selectedAddons.length > 0" class="pt-3 border-t border-slate-200/70">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Selected Add-ons</span>
                                <div class="space-y-1 text-xs">
                                    <div v-for="addon in selectedAddons" :key="addon.id" class="flex items-center justify-between text-slate-700">
                                        <span>+ {{ addon.name }} ({{ addon.duration_minutes }}m)</span>
                                        <span class="font-semibold">+${{ addon.price }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Notes -->
                        <div class="text-left space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                Special Requests or Notes (Optional)
                            </label>
                            <textarea
                                v-model="form.notes"
                                rows="3"
                                placeholder="Let us know if you have any preferences or instructions..."
                                class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs text-slate-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400 shadow-xs"
                            ></textarea>
                        </div>

                        <!-- Auth Verification Notification if Guest -->
                        <div v-if="!authUser" class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-left text-xs text-amber-800 flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <span class="font-bold">Authentication Required:</span>
                                <span> You will be prompted to sign in or create an account to secure this appointment.</span>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-4">
                            <button
                                type="button"
                                @click="currentStep = 4"
                                class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                            >
                                Back
                            </button>

                            <button
                                type="button"
                                @click="submitBooking"
                                :disabled="form.processing"
                                class="px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/25 transition-all active:scale-[0.98] disabled:opacity-50"
                            >
                                <span v-if="form.processing">Securing Booking...</span>
                                <span v-else>Confirm & Book Appointment</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- =========================================================
             4. INTERACTIVE BOOKING SUCCESS MODAL
             ========================================================= -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="bookingSuccess"
                class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
            >
                <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 text-center space-y-6 animate-fadeIn">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-md shadow-emerald-500/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-2xl font-bold text-slate-900 font-display">Appointment Confirmed!</h3>
                        <p class="text-xs text-slate-500">
                            Your session has been successfully booked with {{ business.name }}.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-left text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Service:</span>
                            <span class="font-semibold text-slate-900">{{ confirmedBookingData?.service }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Specialist:</span>
                            <span class="font-semibold text-slate-900">{{ confirmedBookingData?.staff }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Date & Time:</span>
                            <span class="font-semibold text-slate-900">{{ confirmedBookingData?.date }} @ {{ confirmedBookingData?.time }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-slate-200 text-sm">
                            <span class="font-bold text-slate-700">Total:</span>
                            <span class="font-extrabold text-brand-600">${{ confirmedBookingData?.total }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a
                            :href="googleCalendarLink"
                            target="_blank"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-xs"
                        >
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Add to Google Calendar</span>
                        </a>

                        <div class="flex gap-2">
                            <Link
                                :href="route('customer.dashboard')"
                                class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white shadow-xs transition-colors"
                            >
                                View My Agenda
                            </Link>
                            <button
                                @click="resetFlow"
                                class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                            >
                                Book Another
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

    </div>
</template>