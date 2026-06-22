<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { watch } from 'vue';

const form = useForm({
    name: '',
    slug: ''
});

// বিজনেসের নাম লেখার সাথে সাথে অটোমেটিক স্ল্যাগ জেনারেট করার লজিক
watch(() => form.name, (newName) => {
    form.slug = newName
        .toLowerCase()
        .replace(/[^a-z0-9 -]/g, '') // স্পেশাল ক্যারেক্টার রিমুভ
        .replace(/\s+/g, '-')        // স্পেসকে ড্যাশ করা
        .replace(/-+/g, '-');        // ডাবল ড্যাশ রিমুভ
});

const submit = () => {
    form.post(route('onboarding.store'));
};
</script>

<template>

    <Head title="Setup Your Business" />

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto max-w-md w-full">
            <h2 class="text-center text-3xl font-extrabold text-gray-900">🚀 Welcome to Smart Booking</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Let's set up your business workspace first</p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Business Name</label>
                        <input v-model="form.name" type="text" required placeholder="e.g. Bella Vita Salon"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Booking URL Slug</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span
                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                /b/
                            </span>
                            <input v-model="form.slug" type="text" required placeholder="bella-vita-salon"
                                class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Your customers will book from this exact link.</p>
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                    </div>

                    <div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            {{ form.processing ? 'Creating Workspace...' : 'Complete Setup & Enter Dashboard' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>