<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    business: Object
});

const logoPreview = ref(props.business.logo ? `/storage/${props.business.logo}` : null);
const bannerPreview = ref(props.business.banner ? `/storage/${props.business.banner}` : null);

const form = useForm({
    _method: 'POST', // Forces Laravel to parse multipart form data via POST payload correctly
    name: props.business.name || '',
    description: props.business.description || '',
    address: props.business.address || '',
    logo: null,
    banner: null
});

const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleBannerChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.banner = file;
        bannerPreview.value = URL.createObjectURL(file);
    }
};

const submitSettings = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => alert('✨ Profile parameters updated successfully!')
    });
};
</script>

<template>

    <Head title="Business Customization Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Business Settings</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submitSettings" class="bg-white shadow rounded-lg p-6 space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cover Banner</label>
                        <div
                            class="relative h-48 w-full bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                            <img v-if="bannerPreview" :src="bannerPreview" class="object-cover w-full h-full" />
                            <div v-else class="text-center text-gray-400">
                                <span class="block text-sm">Upload a cover image for your business profile (Max
                                    5MB)</span>
                            </div>
                            <input type="file" @change="handleBannerChange"
                                class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <div
                            class="relative w-24 h-24 bg-gray-100 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden flex-shrink-0">
                            <img v-if="logoPreview" :src="logoPreview" class="object-cover w-full h-full" />
                            <span v-else class="text-xs text-gray-400 text-center px-1">Upload Logo</span>
                            <input type="file" @change="handleLogoChange"
                                class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                        </div>

                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Business Public Name</label>
                            <input v-model="form.name" type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Business Biography / Description</label>
                        <textarea v-model="form.description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Tell your customers about your expert services..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Physical Address Location</label>
                        <input v-model="form.address" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Street layout, Suite number, State grid" />
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" :disabled="form.processing"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium shadow transition-colors">
                            {{ form.processing ? 'Saving changes...' : 'Save Settings' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>