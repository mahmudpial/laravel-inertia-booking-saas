<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    bookings: Array
});

// স্ট্যাটাস চেঞ্জ করার ফাংশন
const changeStatus = (bookingId, newStatus) => {
    if (confirm(`Are you sure you want to change status to ${newStatus}?`)) {
        // [FIXED] রাউট নাম ডট নোটেশনে আপডেট করা হয়েছে এবং অ্যাক্সিডেন্টাল ক্লিক এড়াতে কনফার্মেশন যোগ করা হয়েছে
        router.patch(route('admin.bookings.updateStatus', bookingId), {
            status: newStatus
        }, {
            preserveScroll: true,
            onSuccess: () => alert(`🎉 Status updated to ${newStatus} successfully!`)
        });
    }
};

// স্ট্যাটাসের ওপর ভিত্তি করে ব্যাজের কালার নির্ধারণ
const getStatusClass = (status) => {
    if (status === 'confirmed') return 'bg-green-100 text-green-800';
    if (status === 'canceled') return 'bg-red-100 text-red-800';
    return 'bg-yellow-100 text-yellow-800';
};
</script>

<template>

    <Head title="Admin - Booking Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Admin - Booking Management</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">All Appointments</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date & Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="booking in bookings" :key="booking.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ booking.user?.name || 'Guest
                                            Customer'
                                            }}</div>
                                        <div class="text-sm text-gray-500">{{ booking.user?.email || 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ booking.service?.name || 'Deleted Service' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ booking.booking_date }}</div>
                                        <div class="text-xs font-bold text-indigo-600">{{ booking.start_time }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[getStatusClass(booking.status), 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full uppercase']">
                                            {{ booking.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <template v-if="booking.status === 'pending'">
                                            <button @click="changeStatus(booking.id, 'confirmed')"
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-2.5 py-1.5 rounded text-xs transition-all">
                                                Confirm
                                            </button>
                                            <button @click="changeStatus(booking.id, 'canceled')"
                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded text-xs transition-all">
                                                Cancel
                                            </button>
                                        </template>
                                        <span v-else class="text-xs text-gray-400 italic">No actions available</span>
                                    </td>
                                </tr>
                                <tr v-if="bookings.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No bookings
                                        found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>