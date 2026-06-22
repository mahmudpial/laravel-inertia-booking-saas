<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    services: Array
});

// মডাল ওপেন/ক্লোজ এবং এডিট মোড ট্র্যাকিং স্টেট
const isModalOpen = ref(false);
const isEditMode = ref(false);
const currentServiceId = ref(null);

const form = useForm({
    id: null,
    name: '',
    price: '',
    duration_minutes: '', // 👈 যদি শুধু 'duration' লেখা থাকে, তবে এটিকে 'duration_minutes' করে দিন
    description: ''
});
const openCreateModal = () => {
    isEditMode.value = false;
    form.reset();
    isModalOpen.value = true;
};

const openEditModal = (service) => {
    isEditMode.value = true;
    currentServiceId.value = service.id;
    form.name = service.name;
    form.price = service.price;
    form.duration = service.duration;
    form.description = service.description;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submit = () => {
    if (isEditMode.value) {
        form.put(route('admin.services.update', currentServiceId.value), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.services.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const deleteService = (id) => {
    if (confirm('Are you sure you want to delete this service?')) {
        form.delete(route('admin.services.destroy', id));
    }
};
</script>

<template>

    <Head title="Admin - Manage Services" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Manage Services</h2>
                <button @click="openCreateModal"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    + Add New Service
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div v-if="services.length === 0" class="text-center py-12 text-gray-500">
                        No services added yet. Click "+ Add New Service" to get started!
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duration</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description</th>
                                    <th
                                        class="px-6 py-3 class='text-right' text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="service in services" :key="service.id">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ service.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">${{
                                        service.price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ service.duration }} mins
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ service.description
                                        || '-'
                                        }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <button @click="openEditModal(service)"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button @click="deleteService(service.id)"
                                            class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    {{ isEditMode ? '🔧 Edit Service' : '🚀 Add New Service' }}
                </h3>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input v-model="form.name" type="text" required placeholder="e.g. Hair Cut & Style"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                            <input v-model="form.price" type="number" step="0.01" required placeholder="25.00"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (Mins)</label>
                            <input v-model="form.duration_minutes" type="number" required placeholder="30"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea v-model="form.description" rows="3" placeholder="Brief details about the service..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" @click="closeModal"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            {{ form.processing ? 'Saving...' : 'Save Service' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>