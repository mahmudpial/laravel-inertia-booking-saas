<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// ব্যাকএন্ড থেকে পাঠানো সার্ভিস লিস্ট রিসিভ করা
defineProps({
    services: Array
});

// Inertia Form হেল্পার ব্যবহার করে ফর্ম ডাটা রেডি করা
const form = useForm({
    name: '',
    description: '',
    duration_minutes: '',
    price: ''
});

// ফর্ম সাবমিট করার ফাংশন
const submit = () => {
    form.post(route('services.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>

    <Head title="Services Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Services Management</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 shadow sm:rounded-lg md:col-span-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Service</h3>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Service Name</label>
                            <input v-model="form.name" type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="3"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (Minutes)</label>
                            <input v-model="form.duration_minutes" type="number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                            <input v-model="form.price" type="number" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        <button type="submit" :disabled="form.processing"
                            class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ form.processing ? 'Saving...' : 'Save Service' }}
                        </button>
                    </form>
                </div>

                <div class="bg-white p-6 shadow sm:rounded-lg md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Services</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duration</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="service in services" :key="service.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                        service.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                        service.duration_minutes }}
                                        mins</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ service.price }}
                                    </td>
                                </tr>
                                <tr v-if="services.length === 0">
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No services
                                        added yet.
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