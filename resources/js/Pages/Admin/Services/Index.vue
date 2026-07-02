<script setup>
import { ref, computed, watch, onUnmounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ services: Array });

const isModalOpen = ref(false);
const isEditMode = ref(false);
const currentServiceId = ref(null);

// CTO GRADE: SYSTEM SCROLL LOCKING
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
    description: '',
    addons: []
});

// Analytics Matrix
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

// Tactical Add-on Logic
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
    form.description = service.description;
    form.addons = service.addons || [];
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

const deleteService = (id) => {
    if (confirm('Authorize permanent system-wide purging of this SKU?')) {
        form.delete(route('admin.services.destroy', id));
    }
};
</script>

<template>

    <Head title="Inventory Registry | SmartBooking" />

    <AuthenticatedLayout>
        <div
            class="relative min-h-screen bg-[#FDFDFF] font-sans antialiased text-[#09090B] pb-48 selection:bg-indigo-600 selection:text-white">

            <!-- ARCHITECTURAL BLUEPRINT BACKGROUND -->
            <div class="fixed inset-0 z-0 pointer-events-none">
                <div
                    class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1.5px,transparent_1.5px)] [background-size:32px_32px] opacity-40">
                </div>
                <div
                    class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
                </div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 pt-16">

                <!-- EDITORIAL HERO SECTION -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-8 animate-slideUp">
                        <div class="flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Inventory
                                    Node Active</span>
                            </div>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            Service <br />
                            <span class="text-indigo-600 font-medium text-5xl lg:text-8xl">Catalog.</span>
                        </h1>
                        <button @click="openCreateModal"
                            class="inline-flex items-center px-12 py-6 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[2rem] shadow-2xl shadow-indigo-900/20 hover:bg-indigo-600 transition-all active:scale-95">
                            Deploy New SKU
                        </button>
                    </div>

                    <!-- INVENTORY INTELLIGENCE ARTICLE -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Inventory
                                    Strategy</p>
                                <span
                                    class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 py-1 bg-white/5 rounded-full">Secure
                                    Registry</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">
                                Managing the <br /> Operational Portfolio.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    Your **Service Catalog** is the engine of your revenue architecture. Each SKU
                                    represents a quantized time-block synchronized with your specialist roster.
                                </p>
                                <p>
                                    By integrating **Upsell Intelligence**, you maximize the net value of every session.
                                    System data indicates that a meticulously defined catalog reduces administrative
                                    friction by up to **28%**.
                                </p>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- ANALYTICS PODS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                    <div v-for="(val, label) in { 'Total Capacity': totalServices, 'Avg Valuation': '$' + avgPrice, 'Time Mean': avgDuration + 'm' }"
                        :key="label"
                        class="bg-white border border-slate-200/60 p-12 rounded-[4rem] shadow-xl hover:-translate-y-2 transition-all duration-500 group">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-6">{{ label }}</p>
                        <h3
                            class="text-6xl font-black text-[#09090B] tracking-tighter group-hover:text-indigo-600 transition-colors">
                            {{ val }}</h3>
                    </div>
                </div>

                <!-- SKU GRID -->
                <div v-if="services.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="service in services" :key="service.id"
                        class="group relative bg-white border border-slate-200/80 rounded-[4rem] p-12 transition-all duration-500 hover:-translate-y-2 hover:border-indigo-500/40 hover:shadow-2xl">

                        <div class="flex justify-between items-start mb-12">
                            <div
                                class="w-20 h-16 rounded-[1.5rem] bg-slate-50 flex items-center justify-center text-slate-200 group-hover:bg-[#09090B] group-hover:text-white transition-all duration-500 shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Unit Price
                                </p>
                                <p class="text-3xl font-black text-[#09090B] tracking-tighter">${{ service.price }}</p>
                            </div>
                        </div>

                        <div class="mb-12">
                            <h3
                                class="text-3xl font-black text-[#09090B] group-hover:text-indigo-600 transition-colors mb-4 tracking-tighter uppercase leading-none">
                                {{ service.name }}</h3>
                            <p
                                class="text-slate-500 font-medium line-clamp-2 leading-relaxed h-12 uppercase text-[11px] tracking-wide">
                                {{ service.description || 'No operational description indexed.' }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-10 border-t border-slate-100">
                            <span
                                class="text-[10px] font-black text-[#09090B] uppercase tracking-[0.4em] bg-slate-50 px-5 py-2.5 rounded-full border border-slate-100">{{
                                    service.duration_minutes }} Min Unit</span>
                            <div class="flex gap-3">
                                <button @click="openEditModal(service)"
                                    class="p-5 bg-slate-50 text-slate-400 hover:text-indigo-600 rounded-3xl transition-all shadow-sm active:scale-90 border border-transparent hover:border-indigo-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button @click="deleteService(service.id)"
                                    class="p-5 bg-slate-50 text-slate-400 hover:text-rose-600 rounded-3xl transition-all shadow-sm active:scale-90 border border-transparent hover:border-rose-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else
                    class="text-center py-48 bg-white/60 backdrop-blur-xl border border-slate-200/80 rounded-[5rem] shadow-sm animate-fadeIn">
                    <h3 class="text-4xl font-black text-[#09090B] uppercase tracking-tighter">Inventory Synchronized.
                    </h3>
                    <p class="text-slate-400 text-xs mt-4 uppercase tracking-[0.5em] font-black">Zero active units
                        detected.</p>
                </div>
            </div>
        </div>

        <!-- RE-ENGINEERED MODAL SYSTEM -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isModalOpen"
                    class="fixed inset-0 z-[2000] overflow-y-auto px-4 py-8 sm:py-24 flex items-start justify-center no-scrollbar">
                    <div class="fixed inset-0 bg-[#09090B]/95 backdrop-blur-xl transition-opacity" @click="closeModal">
                    </div>

                    <div
                        class="relative bg-white w-full max-w-5xl rounded-[4rem] shadow-[0_50px_150px_rgba(0,0,0,0.5)] overflow-hidden animate-slideUp z-10 my-auto">
                        <div class="p-12 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.5em] mb-3">
                                    Registry Initialization</p>
                                <h2 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                    {{ isEditMode ? 'Modify SKU' : 'New Service' }}</h2>
                            </div>
                            <button @click="closeModal"
                                class="w-14 h-14 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-600 rounded-[1.5rem] transition-all shadow-sm active:scale-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submit" class="p-10 sm:p-14 space-y-12">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                                <!-- LEFT COLUMN: CORE DATA -->
                                <div class="space-y-12">
                                    <div class="group">
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 block ml-1">Official
                                            Identifier</label>
                                        <input v-model="form.name" type="text" required
                                            placeholder="E.G. LUXURY SESSION"
                                            class="w-full bg-slate-50 border-none rounded-[2rem] p-7 text-lg font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner uppercase tracking-tighter" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <label
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] block ml-1">Rate
                                                (USD)</label>
                                            <div class="relative flex items-center">
                                                <span class="absolute left-6 text-slate-400 font-black text-sm">$</span>
                                                <input v-model="form.price" type="number" step="0.01" required
                                                    placeholder="0.00"
                                                    class="w-full bg-slate-50 border-none rounded-[2rem] p-7 pl-10 text-lg font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner" />
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <label
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] block ml-1">Span
                                                (Min)</label>
                                            <div class="relative flex items-center">
                                                <input v-model="form.duration_minutes" type="number" required
                                                    placeholder="60"
                                                    class="w-full bg-slate-50 border-none rounded-[2rem] p-7 text-lg font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner" />
                                                <span
                                                    class="absolute right-6 text-[9px] font-black text-slate-300 uppercase">MIN</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 block ml-1">Unit
                                            Deliverables</label>
                                        <textarea v-model="form.description" rows="4"
                                            placeholder="Brief unit intelligence..."
                                            class="w-full bg-slate-50 border-none rounded-[2.5rem] p-8 text-base font-bold text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner resize-none"></textarea>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN: UPSELL HUB -->
                                <div
                                    class="bg-slate-50 rounded-[3.5rem] p-10 shadow-inner space-y-10 border border-slate-100 h-fit">
                                    <div class="flex items-center justify-between px-2">
                                        <div>
                                            <p
                                                class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.5em]">
                                                Upsell Hub</p>
                                            <h4
                                                class="text-sm font-black text-[#09090B] uppercase mt-2 tracking-widest">
                                                Enhancement Modules</h4>
                                        </div>
                                        <button type="button" @click="addAddonField"
                                            class="px-5 py-3 bg-[#09090B] text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-all shadow-xl active:scale-90">+
                                            Add Package</button>
                                    </div>

                                    <div class="space-y-6 max-h-[450px] overflow-y-auto no-scrollbar pr-2">
                                        <TransitionGroup name="list">
                                            <div v-for="(addon, index) in form.addons" :key="index"
                                                class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col gap-6 relative group/addon hover:border-indigo-200 transition-all duration-300">
                                                <button type="button" @click="removeAddonField(index)"
                                                    class="absolute top-8 right-8 text-slate-300 hover:text-rose-600 transition-colors"><svg
                                                        class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                    </svg></button>
                                                <div class="space-y-3">
                                                    <label
                                                        class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Module
                                                        Label</label>
                                                    <input v-model="addon.name" placeholder="E.G. EXTRA CARE"
                                                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-[11px] font-black uppercase tracking-widest focus:ring-2 focus:ring-indigo-500 shadow-inner" />
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="space-y-3">
                                                        <label
                                                            class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Rate
                                                            ($)</label>
                                                        <input v-model="addon.price" type="number"
                                                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-[11px] font-black focus:ring-2 focus:ring-indigo-500 shadow-inner" />
                                                    </div>
                                                    <div class="space-y-3">
                                                        <label
                                                            class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Time
                                                            (Min)</label>
                                                        <input v-model="addon.duration_minutes" type="number"
                                                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-[11px] font-black focus:ring-2 focus:ring-indigo-500 shadow-inner" />
                                                    </div>
                                                </div>
                                            </div>
                                        </TransitionGroup>
                                        <div v-if="form.addons.length === 0"
                                            class="text-center py-20 opacity-30 border-2 border-dashed border-slate-200 rounded-[3rem]">
                                            <p class="text-[10px] font-black uppercase tracking-[0.4em]">Zero Packets
                                                Defined</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL ACTIONS -->
                            <div class="pt-12 flex flex-col sm:flex-row gap-8 border-t border-slate-100">
                                <button type="button" @click="closeModal"
                                    class="px-16 py-8 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] hover:bg-slate-200 transition-all active:scale-95">Abort
                                    Protocol</button>
                                <button type="submit" :disabled="form.processing"
                                    class="flex-1 py-8 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.5em] rounded-[2rem] hover:bg-indigo-600 transition-all shadow-[0_30px_60px_rgba(79,70,229,0.3)] active:scale-95 disabled:opacity-50 flex items-center justify-center gap-6 group">
                                    <svg v-if="form.processing" class="h-6 w-6 animate-spin" viewBox="0 0 24 24"
                                        fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span class="group-hover:translate-x-2 transition-transform duration-500">{{
                                        form.processing ? 'SYNCHRONIZING...' : (isEditMode ? 'Authorize Update' :
                                            'Initialize Deployment') }}</span>
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
:deep(button),
:deep(span) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.shadow-inner {
    box-shadow: inset 0 4px 12px 0 rgba(0, 0, 0, 0.08);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideUp {
    animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}
</style>