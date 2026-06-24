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
        onSuccess: () => {
            // Smooth custom feedback instead of native browser alert
        }
    });
};
</script>

<template>

    <Head title="Business Customization Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin - Business Settings</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div v-if="$page.props.flash?.message"
                    class="mb-6 p-4 bg-green-100 text-green-700 text-sm rounded-lg shadow-sm font-medium border border-green-200 transition-all duration-300">
                    🎉 {{ $page.props.flash.message }}
                </div>

                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1 mb-6 md:mb-0">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900">Branding & Identity</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Configure your business public assets, branding logos, banners, and location references
                                visible
                                on customer reservation landing portals.
                            </p>
                        </div>

                        <nav class="mt-6 space-y-2">
                            <div
                                class="bg-indigo-50 text-indigo-700 border-indigo-500 w-full text-left px-3 py-2.5 font-semibold text-sm border-l-4 rounded-r-md">
                                🏢 Enterprise Profile Settings
                            </div>
                        </nav>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form @submit.prevent="submitSettings" class="bg-white shadow sm:rounded-lg overflow-hidden">

                            <div class="px-4 py-5 sm:px-6 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Customization Ledger</h3>
                                <p class="mt-1 text-xs text-gray-500">Update your public brand asset files (JPEG, PNG
                                    formats
                                    supported).</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Banner
                                        Image</label>
                                    <div
                                        class="relative h-48 w-full bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden group hover:border-indigo-400 transition-colors">
                                        <img v-if="bannerPreview" :src="bannerPreview"
                                            class="object-cover w-full h-full" />
                                        <div v-else class="text-center p-4">
                                            <span class="text-2xl block mb-1">🖼️</span>
                                            <span class="block text-sm font-medium text-gray-600">Upload profile cover
                                                image</span>
                                            <span class="block text-xs text-gray-400 mt-0.5">Recommended resolution
                                                (1200x400)
                                                Max 5MB</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity cursor-pointer">
                                            <span
                                                class="text-white text-xs font-bold bg-black/50 px-3 py-1.5 rounded-full">Change
                                                Banner</span>
                                        </div>
                                        <input type="file" @change="handleBannerChange"
                                            class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                                    </div>
                                    <div v-if="form.errors.banner" class="text-red-500 text-xs mt-1">{{
                                        form.errors.banner }}
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                                    <div
                                        class="relative w-24 h-24 bg-gray-50 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden flex-shrink-0 group hover:border-indigo-400 transition-colors">
                                        <img v-if="logoPreview" :src="logoPreview" class="object-cover w-full h-full" />
                                        <div v-else class="text-center px-1">
                                            <span class="text-xs font-medium text-gray-500">Upload Logo</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity cursor-pointer">
                                            <span
                                                class="text-white text-[10px] font-bold bg-black/50 px-1.5 py-1 rounded">Change</span>
                                        </div>
                                        <input type="file" @change="handleLogoChange"
                                            class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                                    </div>

                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-gray-700">Business Public
                                            Name</label>
                                        <input v-model="form.name" type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="e.g. Elegant Hair Salon & Spa" required />
                                        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{
                                            form.errors.name }}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Business Biography /
                                        Description</label>
                                    <textarea v-model="form.description" rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Tell your clients about your premium salon treatments, specialized clinical expertise or tutoring core schedules..."></textarea>
                                    <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{
                                        form.errors.description }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Physical Address
                                        Location</label>
                                    <input v-model="form.address" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="e.g. 452 Elite Corporate Way, Suite 10, New York grid" />
                                    <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{
                                        form.errors.address }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-gray-100 bg-gray-50/50 px-6 py-3.5">
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60">
                                    {{ form.processing ? 'Saving Profile Matrix...' : 'Save Settings' }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>