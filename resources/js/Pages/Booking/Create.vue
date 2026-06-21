<script setup>
import { ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    business: Object,
    services: Array
});

const selectedService = ref('');
const selectedDate = ref('');
const slots = ref([]);
const selectedTime = ref('');
const loading = ref(false);

// যখনই সার্ভিস অথবা ডেট চেঞ্জ হবে, আমরা এক্সিওস (Axios) দিয়ে ব্যাকএন্ড থেকে স্লট নিয়ে আসব
const fetchSlots = async () => {
    if (!selectedService.value || !selectedDate.value) return;

    loading.value = true;
    slots.value = [];
    selectedTime.value = '';

    try {
        const response = await axios.get(route('api.slots'), {
            params: {
                service_id: selectedService.value,
                date: selectedDate.value
            }
        });
        slots.value = response.data;
    } catch (error) {
        console.error("Slots fetch করতে সমস্যা হয়েছে:", error);
    } finally {
        loading.value = false;
    }
};

// ওয়াচার (Watcher) দিয়ে ইনপুট ট্র্যাক করা
watch([selectedService, selectedDate], fetchSlots);

// বুকিং ফর্ম রেডি করা
const bookingForm = useForm({
    service_id: '',
    booking_date: '',
    start_time: '',
    notes: ''
});

// কাস্টমার যখন বাটনে ক্লিক করবে
const confirmBooking = () => {
    bookingForm.service_id = selectedService.value;
    bookingForm.booking_date = selectedDate.value;
    bookingForm.start_time = selectedTime.value;

    bookingForm.post(route('booking.store'), {
        onSuccess: () => {
            alert('🎉 Appointment Booked Successfully!');
            // ফর্ম এবং স্লট রিসেট করা
            slots.value = [];
            selectedTime.value = '';
            selectedDate.value = '';
            selectedService.value = '';
        },
        onError: (errors) => {
            alert(errors.time || 'Something went wrong!');
        }
    });
};
</script>

<template>

    <Head :title="'Book an Appointment - ' + business.name" />

    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">{{ business.name }}</h2>
            <p class="text-sm text-center text-gray-500 mb-6">Choose your service and preferred time slot</p>

            <div class="space-y-6">
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
                    <input v-model="selectedDate" type="date" :min="new Date().toISOString().split('T')[0]"
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

                <!-- ৪. বুকিং কনফার্ম বাটন -->
                <div v-if="selectedTime" class="pt-4">
                    <!-- কাস্টমারের জন্য একটি অপশনাল নোট বক্স -->
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