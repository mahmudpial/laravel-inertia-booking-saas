<script setup>
/**
 * SmartBooking Services & Catalog Management
 * Modern SaaS Service Registry Interface
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    services: Array
});

const isModalOpen = ref(false);
const isEditMode = ref(false);
const currentServiceId = ref(null);
const serviceToDelete = ref(null);
const showDeleteConfirm = ref(false);

const form = useForm({
    name: '',
    price: '',
    duration_minutes: '',
    description: '',
    addons: []
});

// Analytics
const totalServices = computed(() => props.services?.length ?? 0);
const avgPrice = computed(() => {
    if (!props.services?.length) return '0.00';
    const total = props.services.reduce((s, x) => s + parseFloat(x.price || 0), 0);
    return (total / props.services.length).toFixed(2);
});
const avgDuration = computed(() => {
    if (!props.services?.length) return 0;
    return Math.round(props.services.reduce((s, x) => s + parseInt(x.duration_minutes || 0), 0) / props.services.length);
});

// Add-ons Management
const addAddonField = () => form.addons.push({ name: '', price: 0, duration_minutes: 0 });
const removeAddonField = (index) => form.addons.splice(index, 1);

const openCreateModal = () => {
    isEditMode.value = false;
    form.reset();
    form.addons = [];
    isModalOpen.value = true;
};

const openEditModal = (service) => {
    isEditMode.value = true;
    currentServiceId.value = service.id;
    form.name = service.name;
    form.price = service.price;
    form.duration_minutes = service.duration_minutes;
    form.description = service.description || '';
    form.addons = (service.addons || []).map(a => ({
        id: a.id,
        name: a.name,
        price: a.price,
        duration_minutes: a.duration_minutes
    }));
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submit = () => {
    const action = isEditMode.value
        ? route('admin.services.update', currentServiceId.value)
        : route('admin.services.store');

    form[isEditMode.value ? 'put' : 'post'](action, {
        onSuccess: () => closeModal(),
        preserveScroll: true
    });
};

const confirmDelete = (service) => {
    serviceToDelete.value = service;
    showDeleteConfirm.value = true;
};

const executeDelete = () => {
    if (!serviceToDelete.value) return;
    form.delete(route('admin.services.destroy', serviceToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            serviceToDelete.value = null;
        }
    });
};
</script>

<template>
    <Head title="Service Catalog | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Services & Catalog
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Configure client-facing offerings, prices, durations, and revenue-boosting add-ons.
                    </p>
                </div>

                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm shadow-brand-500/25 transition-all active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add New Service</span>
                </button>
            </div>

            <!-- KPI Metric Chips -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Services</span>
                    <p class="text-2xl font-bold text-slate-900 font-display mt-1">{{ totalServices }} Active</p>
                </div>
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Average Price</span>
                    <p class="text-2xl font-bold text-slate-900 font-display mt-1">${{ avgPrice }}</p>
                </div>
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Average Session</span>
                    <p class="text-2xl font-bold text-slate-900 font-display mt-1">{{ avgDuration }} Minutes</p>
                </div>
            </div>

            <!-- Services Grid -->
            <div v-if="services?.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="service in services"
                    :key="service.id"
                    class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-all flex flex-col justify-between group"
                >
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-100">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ service.duration_minutes }}m
                            </span>
                            <span class="text-xl font-bold text-slate-900 font-display">
                                ${{ service.price }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                            {{ service.name }}
                        </h3>

                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            {{ service.description || 'No description provided.' }}
                        </p>

                        <!-- Add-ons list preview -->
                        <div v-if="service.addons?.length" class="mt-4 pt-3 border-t border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">
                                Add-on Enhancements ({{ service.addons.length }})
                            </span>
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="addon in service.addons"
                                    :key="addon.id"
                                    class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-50 border border-slate-200 text-slate-700"
                                >
                                    {{ addon.name }} (+${{ addon.price }})
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button
                            @click="openEditModal(service)"
                            class="text-xs font-semibold text-slate-700 hover:text-brand-600 transition-colors flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit</span>
                        </button>

                        <button
                            @click="confirmDelete(service)"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition-colors flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 font-display">No services created yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Add your first bookable service to allow clients to schedule appointments through your link.
                </p>
                <button
                    @click="openCreateModal"
                    class="mt-4 px-5 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm transition-all"
                >
                    Create First Service
                </button>
            </div>

            <!-- Create / Edit Modal -->
            <Transition
                enter-active-class="transition-opacity duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isModalOpen"
                    class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto"
                >
                    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-6 my-8 text-left">
                        <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-display">
                                    {{ isEditMode ? 'Edit Service' : 'Add New Service' }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Configure core service details, pricing, and upsell add-ons.
                                </p>
                            </div>
                            <button @click="closeModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Service Name *
                                </label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    required
                                    placeholder="e.g. Haircut & Styling, Consulting Hour"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                />
                                <span v-if="form.errors.name" class="text-xs text-rose-500 mt-1 block">{{ form.errors.name }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                        Price ($ USD) *
                                    </label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        v-model="form.price"
                                        required
                                        placeholder="45.00"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                    />
                                    <span v-if="form.errors.price" class="text-xs text-rose-500 mt-1 block">{{ form.errors.price }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                        Duration (Minutes) *
                                    </label>
                                    <input
                                        type="number"
                                        step="5"
                                        v-model="form.duration_minutes"
                                        required
                                        placeholder="45"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                    />
                                    <span v-if="form.errors.duration_minutes" class="text-xs text-rose-500 mt-1 block">{{ form.errors.duration_minutes }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Description (Optional)
                                </label>
                                <textarea
                                    v-model="form.description"
                                    rows="2"
                                    placeholder="Brief summary of what is included in this session..."
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                ></textarea>
                            </div>

                            <!-- Add-ons Builder -->
                            <div class="pt-3 border-t border-slate-100 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Service Add-ons</h4>
                                        <p class="text-[11px] text-slate-400">Optional upsells customers can attach to this booking.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="addAddonField"
                                        class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add Option</span>
                                    </button>
                                </div>

                                <div v-if="form.addons?.length" class="space-y-2">
                                    <div
                                        v-for="(addon, idx) in form.addons"
                                        :key="idx"
                                        class="flex items-center gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200/70"
                                    >
                                        <input
                                            type="text"
                                            v-model="addon.name"
                                            placeholder="Add-on Name"
                                            class="flex-1 bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-900"
                                        />
                                        <div class="flex items-center gap-1 w-24">
                                            <span class="text-xs text-slate-400 font-semibold">$</span>
                                            <input
                                                type="number"
                                                step="0.01"
                                                v-model="addon.price"
                                                placeholder="Price"
                                                class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-slate-900"
                                            />
                                        </div>
                                        <div class="flex items-center gap-1 w-20">
                                            <input
                                                type="number"
                                                v-model="addon.duration_minutes"
                                                placeholder="Mins"
                                                class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-slate-900"
                                            />
                                            <span class="text-[10px] text-slate-400">m</span>
                                        </div>
                                        <button
                                            type="button"
                                            @click="removeAddonField(idx)"
                                            class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors"
                                            title="Remove add-on"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/25 transition-all disabled:opacity-50"
                                >
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>{{ isEditMode ? 'Update Service' : 'Save Service' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>

            <!-- Delete Confirmation Modal -->
            <Transition
                enter-active-class="transition-opacity duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showDeleteConfirm"
                    class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
                >
                    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-slate-100 space-y-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Delete Service?</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Are you sure you want to remove <strong>{{ serviceToDelete?.name }}</strong>? This action cannot be undone.
                            </p>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button
                                @click="showDeleteConfirm = false"
                                class="flex-1 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                @click="executeDelete"
                                class="flex-1 py-2.5 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white shadow-xs transition-colors"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>

        </div>
    </AuthenticatedLayout>
</template>