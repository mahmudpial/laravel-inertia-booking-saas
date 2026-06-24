<script setup>
import { ref, watch, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    business: Object,
    services: Array
});

// Component reactive states for filtering and time slot generation
const selectedService = ref('');
const selectedDate = ref('');
const slots = ref([]);
const selectedTime = ref('');
const loading = ref(false);

/**
 * Dynamically compute today's date formatted as YYYY-MM-DD.
 * Used as the minimum boundary for the HTML date picker to prevent past booking selections.
 */
const minDate = computed(() => {
    return new Date().toISOString().split('T')[0];
});

/**
 * Fetch available time slots asynchronously from the backend API.
 * Clears dependent selections, runs validation guards, passes query parameters via Axios,
 * and updates the locally tracked slots array based on business and runtime availability.
 */
const fetchSlots = async () => {
    if (!selectedService.value || !selectedDate.value) return;

    loading.value = true;
    slots.value = [];
    selectedTime.value = '';

    try {
        const response = await axios.get(route('api.slots'), {
            params: {
                business_id: props.business.id,
                service_id: selectedService.value,
                date: selectedDate.value
            }
        });
        slots.value = response.data;
    } catch (error) {
        console.error("An error occurred while fetching availability slots:", error);
    } finally {
        loading.value = false;
    }
};

/**
 * Watcher configuration targeting the service and date states.
 * Automatically triggers the slot regeneration sequence whenever either field value mutates.
 */
watch([selectedService, selectedDate], fetchSlots);

/**
 * Core Inertia Form Object instance initialization.
 * Defines the request structure and handles standard processing states, routing, and resetting parameters.
 */
const bookingForm = useForm({
    business_id: props.business.id,
    service_id: '',
    booking_date: '',
    start_time: '',
    notes: ''
});

/**
 * Process client-side form submission.
 * Maps active tracking variables into the form state payload right before transmission,
 * manages successful resetting protocols, and parses database-level concurrency errors into readable alerts.
 */
const confirmBooking = () => {
    bookingForm.business_id = props.business.id;
    bookingForm.service_id = selectedService.value;
    bookingForm.booking_date = selectedDate.value;
    bookingForm.start_time = selectedTime.value;

    bookingForm.post(route('booking.store'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('🎉 Appointment Booked Successfully!');

            // Clear current operational state variables upon successful processing
            slots.value = [];
            selectedTime.value = '';
            selectedDate.value = '';
            selectedService.value = '';
            bookingForm.reset();
        },
        onError: (errors) => {
            // Handle and display specialized race condition double-booking alerts
            if (errors.time) {
                alert(errors.time);
            } else {
                // Fallback catch-all structural mapping for generic validation messages
                const errorMessages = Object.values(errors).join('\n');
                alert(errorMessages || 'Something went wrong! Please check the console.');
            }
        }
    });
};
</script>

<template>

    <Head :title="'Book an Appointment - ' + business.name" />

    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

            <div class="relative h-48 w-full bg-indigo-900">
                <img v-if="business.banner" :src="'/storage/' + business.banner"
                    class="w-full h-full object-cover opacity-80" alt="Business Banner" />
                <div v-else class="w-full h-full flex items-center justify-center text-indigo-200 font-medium">
                    Welcome to {{ business.name }}
                </div>

                <div class="absolute -bottom-10 left-6">
                    <div
                        class="w-20 h-20 bg-white rounded-full p-1 shadow-md border border-gray-100 overflow-hidden flex items-center justify-center">
                        <img v-if="business.logo" :src="'/storage/' + business.logo"
                            class="w-full h-full object-cover rounded-full" alt="Business Logo" />
                        <span v-else class="text-xl font-bold text-indigo-600">{{ business.name.charAt(0) }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-12 px-6 pb-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">{{ business.name }}</h2>
                <p v-if="business.description" class="mt-2 text-sm text-gray-600 leading-relaxed">
                    {{ business.description }}
                </p>
                <div v-if="business.address" class="mt-3 flex items-center text-xs text-gray-500 space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ business.address }}</span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Select Service</label>
                    <select v-model="selectedService"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled>Choose a service</option>
                        <option v-for="service in services" :key="service.id" :value="service.id">
                            {{ service.name }} (${{ service.price }}) - {{ service.duration_minutes }} mins
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Select Date</label>
                    <input v-model="selectedDate" type="date" :min="minDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div v-if="selectedService && selectedDate">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Slots</label>

                    <div v-if="loading" class="text-center py-4 text-sm text-gray-500">Calculating slots...</div>

                    <div v-else-if="slots.length === 0" class="text-center py-4 text-sm text-red-500 font-medium">
                        Sorry, we are closed or fully booked on this day!
                    </div>

                    <div v-else class="grid grid-cols-3 gap-2">
                        <button v-for="slot in slots" :key="slot.time" @click="selectedTime = slot.time" type="button"
                            :class="[
                                selectedTime === slot.time
                                    ? 'bg-indigo-600 text-white border-indigo-600'
                                    : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-500',
                                'border text-center py-2 rounded-md text-sm font-medium transition-all'
                            ]">
                            {{ slot.formatted_time }}
                        </button>
                    </div>
                </div>

                <div v-if="selectedTime" class="pt-4">
                    <textarea v-model="bookingForm.notes" placeholder="Any special instructions? (Optional)"
                        class="mb-3 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        rows="2"></textarea>

                    <button @click="confirmBooking" :disabled="bookingForm.processing"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-md shadow transition-colors">
                        {{ bookingForm.processing ? 'Booking...' : 'Confirm Appointment' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>