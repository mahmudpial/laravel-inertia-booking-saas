<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    availabilities: Array
});

// দিনের নামগুলো সহজে চেনার জন্য ম্যাপ করা (০ = Sunday, ১ = Monday...)
const daysMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// ফর্ম ডেটা ইনিশিয়ালাইজ করা
const form = useForm({
    availabilities: props.availabilities
});

const submit = () => {
    form.put(route('admin.availability.update'), {
        preserveScroll: true,
        onSuccess: () => alert('📅 Business hours updated successfully!')
    });
};
</script>

<template>

    <Head title="Admin - Business Hours" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Manage Business Hours</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Weekly Schedule</h3>
                    <p class="text-sm text-gray-500 mb-6">Set your shop opening hours, closing hours, and weekly
                        day-offs.</p>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div v-for="(item, index) in form.availabilities" :key="item.id"
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border rounded-lg bg-gray-50 gap-4">

                            <div class="w-32 font-semibold text-gray-700">
                                {{ daysMap[item.day_of_week] }}
                            </div>

                            <div class="flex items-center space-x-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="item.is_open" :true-value="1" :false-value="0"
                                        class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                    </div>
                                    <span class="ml-3 text-sm font-medium"
                                        :class="item.is_open ? 'text-green-600' : 'text-red-500'">
                                        {{ item.is_open ? 'Open' : 'Closed' }}
                                    </span>
                                </label>
                            </div>

                            <div class="flex items-center space-x-3" v-if="item.is_open">
                                <input type="time" v-model="item.start_time" required
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <span class="text-gray-400">to</span>
                                <input type="time" v-model="item.end_time" required
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div v-else class="text-sm text-gray-400 italic py-2">
                                Shop is closed on this day
                            </div>
                        </div>

                        <div class="pt-4 text-right">
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ form.processing ? 'Saving Changes...' : 'Save Schedule Settings' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>