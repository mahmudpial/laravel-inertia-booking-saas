<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// ব্যাকএন্ড থেকে পাঠানো স্ট্যাটস ডেটা রিসিভ করা
defineProps({
    stats: Object
});
</script>

<template>

    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard Overview</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500 p-5">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                                📊
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.total_bookings }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500 p-5">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-50 text-yellow-600 mr-4">
                                ⏳
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Approvals
                                </p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.pending_bookings }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 p-5">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
                                💰
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Earnings</p>
                                <p class="text-2xl font-bold text-gray-900">${{ stats.total_earnings }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900">Welcome back, Admin!</h3>
                    <p class="mt-1 text-sm text-gray-500">Here is what's happening with your business today. Use the
                        sidebar/navigation to manage bookings and services.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>