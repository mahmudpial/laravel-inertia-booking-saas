<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    bookings: Array
});

/**
 * Handle administrative status modification triggers (Confirmed, Canceled, Completed)
 * Executes an Inertia patch request targetting transactional persistence seamlessly.
 */
const changeStatus = (bookingId, newStatus) => {
    if (confirm(`Are you sure you want to change the booking status to ${newStatus}?`)) {
        router.patch(route('admin.bookings.updateStatus', bookingId), {
            status: newStatus.toLowerCase().trim()
        }, {
            preserveScroll: true,
            onSuccess: () => {
                // Real-time flash notification trigger handled seamlessly via Inertia session shared props
            }
        });
    }
};

/**
 * Dynamic mapping matrix defining Tailwind color scheme badges
 * based precisely on database state values.
 */
const getStatusClass = (status) => {
    const safeStatus = status ? status.toLowerCase().trim() : '';
    switch (safeStatus) {
        case 'confirmed':
            return 'bg-green-100 text-green-800';
        case 'canceled':
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        case 'completed':
            return 'bg-blue-100 text-blue-800';
        default:
            return 'bg-yellow-100 text-yellow-800'; // Default pending state accent
    }
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
                <div v-if="$page.props.flash?.message"
                    class="mb-4 p-4 bg-green-100 text-green-700 text-sm rounded-lg shadow-sm font-medium transition-all duration-300">
                    🎉 {{ $page.props.flash.message }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">All Appointments Ledger</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer Details
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service Offered
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date & Timeline
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Live Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions Control
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="booking in bookings" :key="booking.id"
                                    class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ booking.user?.name || 'Guest Customer' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ booking.user?.email || 'N/A' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        {{ booking.service?.name || 'Deleted Service Module' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="font-medium text-gray-700">{{ booking.booking_date }}</div>
                                        <div class="text-xs font-bold text-indigo-600 mt-0.5">{{ booking.start_time }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[getStatusClass(booking.status), 'px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full uppercase tracking-wider']">
                                            {{ booking.status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div v-if="booking.status?.toLowerCase().trim() === 'pending'"
                                            class="flex items-center space-x-2">
                                            <button @click="changeStatus(booking.id, 'confirmed')"
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded text-xs transition-all font-semibold border border-green-200 shadow-sm">
                                                Confirm
                                            </button>
                                            <button @click="changeStatus(booking.id, 'canceled')"
                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded text-xs transition-all font-semibold border border-red-200 shadow-sm">
                                                Cancel
                                            </button>
                                        </div>

                                        <div v-else-if="booking.status?.toLowerCase().trim() === 'confirmed'"
                                            class="flex items-center space-x-2">
                                            <button @click="changeStatus(booking.id, 'completed')"
                                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded text-xs transition-all font-semibold border border-indigo-200 shadow-sm">
                                                Mark Completed
                                            </button>
                                        </div>

                                        <span v-else class="text-xs text-gray-400 italic font-normal">Closed /
                                            Processed</span>
                                    </td>
                                </tr>

                                <tr v-if="bookings.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 font-medium">
                                        No active business reservations or appointments detected setup.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>