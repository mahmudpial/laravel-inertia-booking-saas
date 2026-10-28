<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: '',
    slug: ''
});

// Auto-generate slug from business name
watch(() => form.name, (newName) => {
    form.slug = newName
        .toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
});

const submit = () => {
    form.post(route('onboarding.store'));
};
</script>

<template>
    <Head title="Setup Your Business — SmartBooking" />

    <div class="min-h-screen bg-slate-50 font-sans antialiased text-slate-900 flex flex-col justify-center py-12 px-4 sm:px-6 relative overflow-hidden selection:bg-brand-600 selection:text-white">
        <!-- Blueprint Dot Grid Background -->
        <div class="fixed inset-0 z-0 pointer-events-none bg-dot-grid opacity-60"></div>

        <div class="relative z-10 sm:mx-auto max-w-xl w-full">
            <!-- Brand Anchor -->
            <div class="text-center mb-8">
                <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 items-center justify-center text-white shadow-lg shadow-brand-500/25 mb-4">
                    <ApplicationLogo class="w-7 h-7 text-white" />
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-50 border border-brand-200/60 rounded-full text-brand-700 text-xs font-semibold block mx-auto mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                    Step 1 of 1: Quick Workspace Setup
                </div>
                <h1 class="text-3xl sm:text-4xl font-display font-extrabold text-slate-900 tracking-tight">
                    Name your business workspace
                </h1>
                <p class="text-slate-500 text-sm mt-2 max-w-md mx-auto">
                    We'll create your public booking gateway and pre-configure standard business hours automatically.
                </p>
            </div>

            <!-- Setup Card -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Business Name -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Business / Studio Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="e.g. Apex Fitness Club, Bella Hair Studio"
                                class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                            />
                        </div>
                        <InputError class="mt-1.5" :message="form.errors.name" />
                    </div>

                    <!-- Slug with Live Preview -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Public Booking URL
                        </label>
                        <div class="flex rounded-xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:border-brand-600 focus-within:ring-4 focus-within:ring-brand-500/10 transition-all">
                            <span class="inline-flex items-center px-3.5 text-xs font-medium text-slate-500 border-r border-slate-200 bg-slate-100/60">
                                smartbooking.com/b/
                            </span>
                            <input
                                v-model="form.slug"
                                type="text"
                                required
                                placeholder="apex-fitness"
                                class="w-full px-3 py-3 text-sm bg-transparent border-none text-brand-700 font-semibold focus:ring-0 placeholder:text-slate-400 placeholder:font-normal"
                            />
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            This is your dedicated client booking link. You can share it on Instagram, WhatsApp, or your website.
                        </p>
                        <InputError class="mt-1.5" :message="form.errors.slug" />
                    </div>

                    <!-- Automatic Setup Checklist -->
                    <div class="p-4 rounded-2xl bg-brand-50/50 border border-brand-100 space-y-2.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-900 block">✨ Included in your workspace</span>
                        <div class="space-y-1.5 text-xs text-brand-800">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Default 9:00 AM – 5:00 PM operating availability (customizable)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Full interactive booking calendar & real-time slot generation</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Team specialist assignment & service add-ons catalog</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 px-4 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold text-sm shadow-md shadow-slate-900/10 hover:shadow-brand-600/25 transition-all duration-200 active:scale-[0.99] disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>{{ form.processing ? 'Creating your workspace...' : 'Launch Business Workspace' }}</span>
                        <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>