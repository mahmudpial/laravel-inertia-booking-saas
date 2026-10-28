<script setup>
import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    entities: {
        type: Object,
        default: () => ({ data: [], total: 0 })
    }
});

const searchQuery = ref('');
const statusFilter = ref('all'); // 'all', 'active', 'suspended'
const isConfirmingToggle = ref(false);
const targetEntity = ref(null);
const isMutating = ref(false);

const filteredEntities = computed(() => {
    let list = props.entities.data || [];
    if (statusFilter.value !== 'all') {
        list = list.filter(e => e.status === statusFilter.value);
    }
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(e => 
            e.name?.toLowerCase().includes(q) ||
            e.owner?.toLowerCase().includes(q) ||
            e.email?.toLowerCase().includes(q) ||
            e.slug?.toLowerCase().includes(q)
        );
    }
    return list;
});

const openToggleModal = (entity) => {
    targetEntity.value = entity;
    isConfirmingToggle.value = true;
};

const closeToggleModal = () => {
    isConfirmingToggle.value = false;
    targetEntity.value = null;
};

const executeToggle = () => {
    if (!targetEntity.value) return;
    isMutating.value = true;
    router.patch(route('superadmin.entities.toggle', targetEntity.value.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            closeToggleModal();
            isMutating.value = false;
        },
        onError: () => {
            isMutating.value = false;
        }
    });
};
</script>

<template>
    <Head title="Workspaces & Entities — Sovereign Control" />

    <SovereignLayout>
        <div class="space-y-8 max-w-7xl mx-auto pb-16">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            Multi-Tenant Registry
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                        Business Workspace Directory
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Inspect tenant isolation nodes, monitor booking loads, and modify operational status.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-xs flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Workspaces:</span>
                        <span class="font-display font-bold text-slate-900 text-base">{{ entities.total }}</span>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs">
                <!-- Search Box -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Filter by business name, owner, or email..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-500/10 transition-all font-medium"
                    />
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1.5">
                    <button
                        @click="statusFilter = 'all'"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all',
                            statusFilter === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'
                        ]"
                    >
                        All
                    </button>
                    <button
                        @click="statusFilter = 'active'"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all',
                            statusFilter === 'active' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100'
                        ]"
                    >
                        Active
                    </button>
                    <button
                        @click="statusFilter = 'suspended'"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all',
                            statusFilter === 'suspended' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100'
                        ]"
                    >
                        Suspended
                    </button>
                </div>
            </div>

            <!-- Entities Table -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 text-slate-400 uppercase font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Business Workspace</th>
                                <th class="px-6 py-3.5">Owner / Principal</th>
                                <th class="px-6 py-3.5">Total Bookings</th>
                                <th class="px-6 py-3.5">Services (SKUs)</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="entity in filteredEntities"
                                :key="entity.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-display font-bold text-sm shadow-xs">
                                            {{ entity.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ entity.name }}</div>
                                            <div class="text-[11px] text-slate-400 font-mono">/b/{{ entity.slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ entity.owner }}</div>
                                    <div class="text-[11px] text-slate-400">{{ entity.email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900">{{ entity.load }}</span>
                                    <span class="text-slate-400 text-[11px] ml-1">sessions</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-brand-600">{{ entity.skus }}</span>
                                    <span class="text-slate-400 text-[11px] ml-1">services</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-0.5 rounded-full text-[11px] font-semibold border inline-flex items-center gap-1.5',
                                            entity.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'
                                        ]"
                                    >
                                        <span :class="['w-1.5 h-1.5 rounded-full', entity.status === 'active' ? 'bg-emerald-500' : 'bg-rose-500']"></span>
                                        {{ entity.status === 'active' ? 'Active' : 'Suspended' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="`/b/${entity.slug}`"
                                            target="_blank"
                                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold transition-all inline-flex items-center gap-1"
                                        >
                                            <span>Visit</span>
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </Link>

                                        <button
                                            type="button"
                                            @click="openToggleModal(entity)"
                                            :class="[
                                                'px-3 py-1.5 rounded-lg font-semibold transition-all border text-xs',
                                                entity.status === 'active'
                                                    ? 'text-rose-600 hover:bg-rose-50 border-rose-200/60'
                                                    : 'text-emerald-700 hover:bg-emerald-50 border-emerald-200/60'
                                            ]"
                                        >
                                            {{ entity.status === 'active' ? 'Suspend' : 'Activate' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredEntities.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    No workspaces found matching your filter criteria.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Toggle Status Confirmation Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="isConfirmingToggle" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="closeToggleModal"></div>

                    <div class="relative bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-200 z-10 animate-in fade-in zoom-in-95 duration-200">
                        <div
                            :class="[
                                'w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4',
                                targetEntity?.status === 'active' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'
                            ]"
                        >
                            <svg v-if="targetEntity?.status === 'active'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>

                        <div class="text-center">
                            <h3 class="text-lg font-display font-bold text-slate-900">
                                {{ targetEntity?.status === 'active' ? 'Suspend Workspace?' : 'Activate Workspace?' }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Are you sure you want to change the operational status of
                                <strong class="text-slate-800">{{ targetEntity?.name }}</strong> to
                                <strong :class="targetEntity?.status === 'active' ? 'text-rose-600' : 'text-emerald-600'">
                                    {{ targetEntity?.status === 'active' ? 'Suspended' : 'Active' }}
                                </strong>?
                                <span v-if="targetEntity?.status === 'active'">Clients will not be able to book appointments while suspended.</span>
                                <span v-else>The workspace public booking portal will be immediately accessible.</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-3 mt-6">
                            <button
                                type="button"
                                @click="closeToggleModal"
                                class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                @click="executeToggle"
                                :disabled="isMutating"
                                :class="[
                                    'flex-1 py-2.5 px-4 rounded-xl text-white text-xs font-semibold transition-all shadow-sm disabled:opacity-50 flex items-center justify-center gap-1.5',
                                    targetEntity?.status === 'active' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700'
                                ]"
                            >
                                <svg v-if="isMutating" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>{{ isMutating ? 'Processing...' : (targetEntity?.status === 'active' ? 'Confirm Suspension' : 'Confirm Activation') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </SovereignLayout>
</template>