<script setup>
/**
 * SmartBooking Staff & Team Management
 * Modern SaaS Specialist Roster Interface
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    staff: Array
});

const isModalOpen = ref(false);
const memberToDelete = ref(null);
const showDeleteConfirm = ref(false);

const form = useForm({
    name: '',
    role: ''
});

const openModal = () => {
    form.reset();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submit = () => {
    form.post(route('admin.staff.store'), {
        preserveScroll: true,
        onSuccess: () => closeModal()
    });
};

const confirmDelete = (member) => {
    memberToDelete.value = member;
    showDeleteConfirm.value = true;
};

const executeDelete = () => {
    if (!memberToDelete.value) return;
    form.delete(route('admin.staff.destroy', memberToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            memberToDelete.value = null;
        }
    });
};
</script>

<template>
    <Head title="Staff & Specialists | SmartBooking" />

    <AuthenticatedLayout>
        <div class="space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-display">
                        Staff & Specialists
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Manage your team members and specialist roster eligible for customer bookings.
                    </p>
                </div>

                <button
                    @click="openModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm shadow-brand-500/25 transition-all active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Add Team Member</span>
                </button>
            </div>

            <!-- Team Members Grid -->
            <div v-if="staff?.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="member in staff"
                    :key="member.id"
                    class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-card transition-all flex flex-col justify-between group"
                >
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-900 text-white font-bold text-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                {{ member.name.charAt(0).toUpperCase() }}
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                            {{ member.name }}
                        </h3>

                        <p class="text-xs text-brand-600 font-semibold mt-0.5">
                            {{ member.role || 'Certified Specialist' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Available for bookings</span>

                        <button
                            @click="confirmDelete(member)"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition-colors flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Remove</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 font-display">No team members added</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Add at least one staff member so customers can select a specialist during the booking flow.
                </p>
                <button
                    @click="openModal"
                    class="mt-4 px-5 py-2.5 rounded-xl text-xs font-semibold bg-brand-600 text-white hover:bg-brand-700 shadow-sm transition-all"
                >
                    Add First Member
                </button>
            </div>

            <!-- Add Member Modal -->
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
                    class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
                >
                    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-6 text-left">
                        <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-display">Add Team Member</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Enter specialist details to join your scheduling roster.</p>
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
                                    Full Name *
                                </label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    required
                                    placeholder="e.g. Sarah Jenkins"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                />
                                <InputError :message="form.errors.name" class="mt-1" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Role / Title *
                                </label>
                                <input
                                    type="text"
                                    v-model="form.role"
                                    required
                                    placeholder="e.g. Master Stylist, Senior Consultant"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                />
                                <InputError :message="form.errors.role" class="mt-1" />
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
                                    <span v-if="form.processing">Adding...</span>
                                    <span v-else>Add Specialist</span>
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
                            <h3 class="text-base font-bold text-slate-900">Remove Team Member?</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Are you sure you want to remove <strong>{{ memberToDelete?.name }}</strong>? Future slots for this specialist will be removed.
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
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>

        </div>
    </AuthenticatedLayout>
</template>