<script setup>
/**
 * Team Roster v4.2.0-Alpha
 * 
 * CORE SYSTEMS:
 * 1. Personnel Roster: Management of specialist nodes and roles.
 * 2. Authorization Engine: Secure deployment and termination protocols.
 * 3. Human Capital Briefing: Integrated editorial intelligence.
 * 
 * @author Pial Mahmud (System Architect)
 */

import { ref, watch, onUnmounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({ staff: Array });
const isModalOpen = ref(false);

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

const form = useForm({ name: '', role: '' });

const submit = () => {
    form.post(route('admin.staff.store'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
            // Subtle tactical feedback could be replaced with a custom toast system
        }
    });
};

const deleteMember = (id) => {
    if (confirm('Authorize permanent termination of specialist access? This action is absolute.')) {
        form.delete(route('admin.staff.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>

    <Head title="Team Roster | Operational Personnel" />

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

                <!-- =========================================================
                     EDITORIAL HERO SECTION
                     ========================================================= -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-16 mb-24 items-start">
                    <div class="xl:col-span-5 space-y-8 animate-slideUp">
                        <div class="flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Personnel
                                    Roster Active</span>
                            </div>
                        </div>
                        <h1 class="text-8xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B]">
                            Team <br />
                            <span class="text-indigo-600 font-medium text-5xl lg:text-8xl">Roster.</span>
                        </h1>
                        <button @click="isModalOpen = true"
                            class="inline-flex items-center px-12 py-6 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-[2rem] shadow-2xl shadow-indigo-900/20 hover:bg-indigo-600 transition-all active:scale-95">
                            Authorize Specialist
                        </button>
                    </div>

                    <!-- PERSONNEL INTELLIGENCE ARTICLE -->
                    <div
                        class="xl:col-span-7 bg-[#09090B] rounded-[4rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden group border border-white/5 animate-fadeIn">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                        </div>
                        <div class="relative z-10 space-y-10">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em]">Roster
                                    Intelligence</p>
                                <span
                                    class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 py-1 bg-white/5 rounded-full">Secure
                                    Node</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-tight">
                                Optimizing Human <br /> Capital Throughput.</h2>
                            <article class="text-slate-400 text-lg leading-relaxed space-y-6 font-medium italic">
                                <p>
                                    A professional ecosystem is only as strong as its specialists. The **Team Roster**
                                    is your deployment control for human resources. Assigning specific roles ensures
                                    that customer expectations align with operational delivery.
                                </p>
                                <p>
                                    By meticulously indexing your specialists, you enable the engine to calculate
                                    precise availability windows. Data integrity is maintained via Node-Sentinel
                                    encrypted session handshakes.
                                </p>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- =========================================================
                     SPECIALIST GRID
                     ========================================================= -->
                <div v-if="staff.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="member in staff" :key="member.id"
                        class="group relative bg-white border border-slate-200/80 rounded-[4rem] p-12 transition-all duration-500 hover:-translate-y-2 hover:border-indigo-500/40 hover:shadow-2xl">

                        <div class="flex flex-col items-center text-center">
                            <!-- Tactical Avatar -->
                            <div
                                class="w-32 h-32 rounded-full bg-slate-50 flex items-center justify-center text-4xl font-black text-slate-200 group-hover:bg-[#09090B] group-hover:text-white transition-all duration-500 mb-8 border-2 border-dashed border-slate-200 group-hover:border-indigo-500 group-hover:rotate-6 shadow-inner">
                                {{ member.name.charAt(0).toUpperCase() }}
                            </div>

                            <div class="space-y-2 mb-10">
                                <div class="flex items-center justify-center gap-2 mb-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></div>
                                    <span
                                        class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Status:
                                        Active Node</span>
                                </div>
                                <h3 class="text-3xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                    {{ member.name }}</h3>
                                <p class="text-sm font-black text-indigo-600 uppercase tracking-[0.2em] pt-4">{{
                                    member.role || 'Authorized Professional' }}</p>
                            </div>

                            <div class="pt-10 border-t border-slate-50 w-full flex flex-col items-center gap-6">
                                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.4em]">Resource ID:
                                    US-{{ 100 + member.id }}</p>
                                <button
                                    class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 hover:text-rose-600 transition-all hover:tracking-[0.6em] active:scale-90"
                                    @click="deleteMember(member.id)">
                                    Terminate Access
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EMPTY STATE -->
                <div v-else
                    class="text-center py-48 bg-white/60 backdrop-blur-xl border border-slate-200/80 rounded-[5rem] shadow-sm animate-fadeIn">
                    <div
                        class="w-24 h-24 rounded-[2.5rem] bg-[#09090B] flex items-center justify-center mx-auto mb-10 shadow-2xl">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-4xl font-black text-[#09090B] uppercase tracking-tighter">Zero Specialists Indexed.
                    </h3>
                    <p class="text-slate-400 text-xs mt-4 uppercase tracking-[0.5em] font-black leading-relaxed">Begin
                        by initializing your team members to the active roster.</p>
                </div>

                <div class="mt-60 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[1em]">Personnel Infrastructure
                        v4.2.0-Alpha.01</p>
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

                    <div class="fixed inset-0 bg-[#09090B]/95 backdrop-blur-xl transition-opacity"
                        @click="isModalOpen = false"></div>

                    <!-- MODAL ENGINE CARD -->
                    <div
                        class="relative bg-white w-full max-w-xl rounded-[4rem] shadow-[0_50px_150px_rgba(0,0,0,0.5)] overflow-hidden animate-slideUp z-10 my-auto">
                        <div class="p-12 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.5em] mb-3">
                                    Authorize Personnel</p>
                                <h2 class="text-4xl font-black text-[#09090B] tracking-tighter uppercase leading-none">
                                    New Specialist</h2>
                            </div>
                            <button @click="isModalOpen = false"
                                class="w-14 h-14 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-600 rounded-[1.5rem] transition-all shadow-sm active:scale-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submit" class="p-10 sm:p-16 space-y-12">
                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 block ml-1">Full
                                    Identity Name</label>
                                <input v-model="form.name" type="text" required placeholder="E.G. PIAL MAHMUD"
                                    class="w-full bg-slate-50 border-none rounded-[2rem] p-7 text-lg font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner uppercase tracking-tighter" />
                                <InputError class="mt-3 ml-2" :message="form.errors.name" />
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4 block ml-1">Professional
                                    Role</label>
                                <input v-model="form.role" type="text" placeholder="E.G. SENIOR SYSTEMS ARCHITECT"
                                    class="w-full bg-slate-50 border-none rounded-[2rem] p-7 text-lg font-black text-[#09090B] focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner uppercase tracking-tighter" />
                                <InputError class="mt-3 ml-2" :message="form.errors.role" />
                            </div>

                            <div class="pt-6 flex flex-col sm:flex-row gap-6">
                                <button type="button" @click="isModalOpen = false"
                                    class="px-12 py-6 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-[0.5em] rounded-[1.5rem] hover:bg-slate-200 transition-all active:scale-95">Abort</button>
                                <button type="submit" :disabled="form.processing"
                                    class="flex-1 py-6 bg-[#09090B] text-white text-[10px] font-black uppercase tracking-[0.5em] rounded-[1.5rem] hover:bg-indigo-600 transition-all shadow-[0_20px_50px_rgba(0,0,0,0.3)] active:scale-95 disabled:opacity-50 flex items-center justify-center gap-6 group">
                                    <svg v-if="form.processing" class="h-5 w-5 animate-spin" viewBox="0 0 24 24"
                                        fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span class="group-hover:translate-x-2 transition-transform duration-500">Authorize
                                        & Deploy</span>
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
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(button),
:deep(span),
:deep(p) {
    font-family: 'Space Grotesk', sans-serif !important;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
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

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.animate-slideUp {
    animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fadeIn {
    animation: fadeIn 1.5s ease-out forwards;
}
</style>