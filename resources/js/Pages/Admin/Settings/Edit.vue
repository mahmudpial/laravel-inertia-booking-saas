<script setup>
/**
 * SmartBooking Business Settings & Branding
 * Modern SaaS Profile & Storefront Customization Interface
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    business: Object
});

const logoPreview = ref(props.business?.logo ? `/storage/${props.business.logo}` : null);
const bannerPreview = ref(props.business?.banner ? `/storage/${props.business.banner}` : null);
const savedSuccess = ref(false);

const form = useForm({
    _method: 'POST',
    name: props.business?.name || '',
    description: props.business?.description || '',
    address: props.business?.address || '',
    logo: null,
    banner: null
});

const handleFile = (e, key) => {
    const file = e.target.files[0];
    if (file) {
        form[key] = file;
        const reader = new FileReader();
        reader.onload = (event) => {
            if (key === 'logo') logoPreview.value = event.target.result;
            if (key === 'banner') bannerPreview.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitSettings = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            savedSuccess.value = true;
            setTimeout(() => {
                savedSuccess.value = false;
            }, 3500);
        }
    });
};
</script>

<template>
    <Head title="Business Settings | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-6 max-w-4xl">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Business Settings & Branding
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Customize your public booking profile, brand identity, address, and client communications.
                    </p>
                </div>

                <a
                    v-if="business?.slug"
                    :href="route('booking.page', business.slug)"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 shadow-xs transition-colors"
                >
                    <span>View Public Gateway</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Main Settings Form -->
            <form @submit.prevent="submitSettings" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-6 sm:p-8 space-y-6">

                    <!-- Profile Info -->
                    <div class="space-y-4">
                        <h2 class="text-base font-bold text-slate-900 font-display">Profile & Brand Identity</h2>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Business Legal Name *
                            </label>
                            <input
                                type="text"
                                v-model="form.name"
                                required
                                placeholder="e.g. Elite Studio & Spa"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                            />
                            <span v-if="form.errors.name" class="text-xs text-rose-500 mt-1 block">{{ form.errors.name }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Public Booking Slug
                            </label>
                            <div class="flex items-center">
                                <span class="px-3.5 py-2.5 rounded-l-xl bg-slate-100 border border-r-0 border-slate-200 text-xs text-slate-500 font-mono">
                                    /b/
                                </span>
                                <input
                                    type="text"
                                    :value="business?.slug"
                                    disabled
                                    class="w-full bg-slate-100 border border-slate-200 rounded-r-xl px-4 py-2.5 text-xs text-slate-600 font-mono cursor-not-allowed"
                                />
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Unique URL identifier used by clients to reach your booking page.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Description & Client Welcome Bio
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Describe your studio, services, specializations, or what clients should expect..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                            ></textarea>
                            <span v-if="form.errors.description" class="text-xs text-rose-500 mt-1 block">{{ form.errors.description }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Studio Physical Address
                            </label>
                            <input
                                type="text"
                                v-model="form.address"
                                placeholder="e.g. 742 Evergreen Terrace, Suite 100, New York, NY"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                            />
                            <span v-if="form.errors.address" class="text-xs text-rose-500 mt-1 block">{{ form.errors.address }}</span>
                        </div>
                    </div>

                    <!-- Media Assets Section -->
                    <div class="pt-6 border-t border-slate-100 space-y-5">
                        <h2 class="text-base font-bold text-slate-900 font-display">Logos & Visual Assets</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Logo Upload -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                    Brand Logo / Avatar
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-xs">
                                        <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-cover" />
                                        <span v-else class="text-xs font-bold text-slate-400">No Logo</span>
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFile($event, 'logo')"
                                        class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer"
                                    />
                                </div>
                            </div>

                            <!-- Banner Upload -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                    Storefront Cover Banner
                                </label>
                                <div class="space-y-3">
                                    <div class="w-full h-16 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shadow-xs">
                                        <img v-if="bannerPreview" :src="bannerPreview" class="w-full h-full object-cover" />
                                        <span v-else class="text-xs font-bold text-slate-400">Default Clean Banner</span>
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFile($event, 'banner')"
                                        class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Save Action -->
                <div class="p-5 sm:p-6 bg-slate-50/75 border-t border-slate-200/60 flex items-center justify-between">
                    <span v-if="savedSuccess" class="text-xs font-semibold text-emerald-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Profile changes saved successfully!</span>
                    </span>
                    <span v-else class="text-xs text-slate-400">
                        Updates appear instantly on your public gateway.
                    </span>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/25 transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        <span v-if="form.processing">Saving Changes...</span>
                        <span v-else>Save Business Settings</span>
                    </button>
                </div>
            </form>

        </div>
    </AuthenticatedLayout>
</template>