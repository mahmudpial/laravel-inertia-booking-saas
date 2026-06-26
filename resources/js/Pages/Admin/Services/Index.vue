<script setup>
import { ref, computed, watch, onUnmounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ services: Array });

const isModalOpen = ref(false);
const isEditMode = ref(false);
const currentServiceId = ref(null);

// PROFESSIONAL SCROLL LOCK LOGIC
watch(isModalOpen, (val) => {
    if (val) {
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }
});

onUnmounted(() => {
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
});

const form = useForm({
    name: '',
    price: '',
    duration_minutes: '',
    description: ''
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
    form.duration_minutes = service.duration_minutes;
    form.description = service.description;
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

    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => closeModal(),
        preserveScroll: true
    });
};

const deleteService = (id) => {
    if (confirm('Authorize permanent deletion of this unit?')) {
        form.delete(route('admin.services.destroy', id));
    }
};
</script>

<template>

    <Head title="Inventory Management" />

    <AuthenticatedLayout>
        <!-- PAGE CONTENT -->
        <div
            class="min-h-screen bg-[#F8FAFC] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

                <!-- HEADER -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
                    <div>
                        <div class="flex items-center gap-2.5 mb-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-ping"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Inventory
                                Control</span>
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black text-[#11131A] tracking-tighter leading-none">
                            Service <span class="text-slate-400 font-medium">Catalog.</span>
                        </h1>
                    </div>

                    <button @click="openCreateModal"
                        class="inline-flex items-center px-10 py-5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-[1.5rem] hover:bg-indigo-600 transition-all shadow-2xl active:scale-95">
                        Deploy New SKU
                    </button>
                </div>

                <!-- ANALYTICS -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-16">
                    <div class="bg-white border border-slate-200/60 p-10 rounded-[3rem] shadow-xl shadow-slate-200/40">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Total Capacity
                        </p>
                        <h3 class="text-4xl font-black text-[#11131A] tracking-tighter">{{ totalServices }}</h3>
                    </div>
                    <div
                        class="bg-white border border-slate-200/60 p-10 rounded-[3rem] shadow-xl shadow-slate-200/40 text-emerald-600">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Avg Rate</p>
                        <h3 class="text-4xl font-black text-[#11131A] tracking-tighter">${{ avgPrice }}</h3>
                    </div>
                    <div
                        class="bg-white border border-slate-200/60 p-10 rounded-[3rem] shadow-xl shadow-slate-200/40 text-indigo-600">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Time Mean</p>
                        <h3 class="text-4xl font-black text-[#11131A] tracking-tighter">{{ avgDuration }}m</h3>
                    </div>
                </div>

                <!-- LIST -->
                <div v-if="services.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="service in services" :key="service.id"
                        class="group bg-white border border-slate-200/80 rounded-[3rem] p-10 transition-all duration-500 hover:-translate-y-2 hover:border-indigo-500/40 hover:shadow-2xl">
                        <div class="flex justify-between items-start mb-12">
                            <div
                                class="w-16 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-[#11131A] group-hover:text-white transition-all duration-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Unit Value
                                </p>
                                <p class="text-2xl font-black text-[#11131A] tracking-tighter">${{ service.price }}</p>
                            </div>
                        </div>
                        <h3
                            class="text-2xl font-black text-[#11131A] group-hover:text-indigo-600 transition-colors mb-2 tracking-tight">
                            {{ service.name }}</h3>
                        <p class="text-sm text-slate-500 font-medium line-clamp-2 leading-relaxed h-10 mb-8">{{
                            service.description || 'No system meta-description indexed.' }}</p>

                        <div class="flex items-center justify-between pt-8 border-t border-slate-50">
                            <span
                                class="text-[10px] font-black text-[#11131A] uppercase tracking-[0.3em] bg-slate-50 px-4 py-2 rounded-full">{{
                                    service.duration_minutes }} Min</span>
                            <div class="flex gap-2">
                                <button @click="openEditModal(service)"
                                    class="p-4 bg-slate-50 text-slate-400 hover:text-indigo-600 rounded-2xl transition-all"><svg
                                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg></button>
                                <button @click="deleteService(service.id)"
                                    class="p-4 bg-slate-50 text-slate-400 hover:text-rose-600 rounded-2xl transition-all"><svg
                                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TELEPORTED MODAL: FIXES Z-INDEX AND SCROLL ISSUES -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isModalOpen"
                    class="fixed inset-0 z-[1000] overflow-y-auto px-4 py-6 sm:py-12 flex items-center justify-center">

                    <!-- HIGH-DENSITY BACKDROP -->
                    <div class="fixed inset-0 bg-[#0A0B10]/90 backdrop-blur-xl" @click="closeModal"></div>

                    <!-- MODAL CARD -->
                    <div
                        class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] overflow-hidden animate-slideUp my-auto">
                        <div class="p-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-2">{{
                                    isEditMode ? 'Modify Registry' : 'Initial Deployment' }}</p>
                                <h2 class="text-3xl font-black text-[#11131A] tracking-tighter uppercase leading-none">
                                    {{ isEditMode ? 'Edit SKU' : 'New Service' }}</h2>
                            </div>
                            <button @click="closeModal"
                                class="w-12 h-12 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-600 rounded-2xl transition-all shadow-sm active:scale-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submit" class="p-8 sm:p-12 space-y-10">
                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 block ml-1">Official
                                    Name</label>
                                <input v-model="form.name" type="text" required
                                    placeholder="e.g. Master Intelligence Consult"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-5 text-slate-900 font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 block ml-1">Price
                                        (USD)</label>
                                    <input v-model="form.price" type="number" step="0.01" required placeholder="149.00"
                                        class="w-full bg-slate-50 border-none rounded-2xl p-5 text-slate-900 font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner" />
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 block ml-1">Span
                                        (Minutes)</label>
                                    <input v-model="form.duration_minutes" type="number" required placeholder="60"
                                        class="w-full bg-slate-50 border-none rounded-2xl p-5 text-slate-900 font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner" />
                                </div>
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 block ml-1">Description</label>
                                <textarea v-model="form.description" rows="4"
                                    placeholder="Index the session deliverables..."
                                    class="w-full bg-slate-50 border-none rounded-[2rem] p-6 text-slate-900 font-bold focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner resize-none"></textarea>
                            </div>

                            <div class="pt-8 flex flex-col sm:flex-row gap-4">
                                <button type="button" @click="closeModal"
                                    class="px-10 py-5 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-slate-200 transition-all active:scale-95">Discard</button>
                                <button type="submit" :disabled="form.processing"
                                    class="flex-1 py-5 bg-[#11131A] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-2xl hover:bg-indigo-600 transition-all shadow-2xl active:scale-95 disabled:opacity-50">
                                    {{ form.processing ? 'Syncing...' : (isEditMode ? 'Authorize Update' : `Initialize
                                    SKU`) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(input),
:deep(textarea),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(button) {
    font-family: 'Space Grotesk', sans-serif !important;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

@keyframes slideUp {
    from {
        transform: translateY(60px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slideUp {
    animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.shadow-inner {
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.04);
}
</style>