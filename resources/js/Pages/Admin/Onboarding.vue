<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { watch } from 'vue';

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

    <Head title="Setup Your Business" />

    <div
        class="min-h-screen bg-gradient-to-br from-[#F5F6FA] via-white to-[#EEF0F6] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Decorative background element -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-indigo-100/30 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-purple-100/20 blur-3xl"></div>
        </div>

        <div class="relative z-10 sm:mx-auto max-w-md w-full">
            <!-- Logo / Icon -->
            <div class="flex justify-center">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/25">
                    <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke-linecap="round" />
                        <path d="M8 10h8M8 14h5" stroke-linecap="round" />
                        <path d="M16 8v8" stroke-linecap="round" stroke-dasharray="2 2" />
                    </svg>
                </div>
            </div>

            <h2 class="mt-6 text-center font-display text-3xl font-bold tracking-tight text-[#11131A]">
                Welcome to Smart Booking
            </h2>
            <p class="mt-2 text-center text-sm text-[#6B7280]">
                Let’s set up your business workspace in just a few steps.
            </p>
        </div>

        <div class="relative z-10 mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white/80 backdrop-blur-sm py-8 px-6 shadow-xl shadow-indigo-500/5 sm:rounded-2xl sm:px-10 border border-white/30">

                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Business Name -->
                    <div>
                        <label class="block text-sm font-semibold text-[#11131A]">
                            Business Name
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="mt-1.5 relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#9CA3AF]">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 1a7 7 0 00-4.95 11.95l1.06-1.06A5 5 0 1110 5a5 5 0 11-3.89 8.89L5.05 14.95A7 7 0 0010 3z" />
                                </svg>
                            </div>
                            <input v-model="form.name" type="text" required placeholder="e.g. Bella Vita Salon"
                                class="block w-full rounded-xl border border-[#E7E9F2] bg-white/70 py-2.5 pl-10 pr-4 text-sm text-[#11131A] placeholder-[#9CA3AF] shadow-sm transition-all focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:border-[#D5D9E5]">
                        </div>
                        <p v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-rose-500">{{ form.errors.name
                        }}</p>
                    </div>

                    <!-- Booking URL Slug -->
                    <div>
                        <label class="block text-sm font-semibold text-[#11131A]">
                            Booking URL Slug
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="mt-1.5 flex rounded-xl shadow-sm">
                            <span
                                class="inline-flex items-center rounded-l-xl border border-r-0 border-[#E7E9F2] bg-[#F8F9FC] px-3 text-sm text-[#6B7280]">
                                /b/
                            </span>
                            <input v-model="form.slug" type="text" required placeholder="bella-vita-salon"
                                class="flex-1 block w-full rounded-r-xl border border-[#E7E9F2] bg-white/70 py-2.5 px-3 text-sm text-[#11131A] placeholder-[#9CA3AF] shadow-sm transition-all focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:border-[#D5D9E5]">
                        </div>
                        <p class="mt-1.5 text-xs text-[#9CA3AF]">
                            Your customers will book from <span
                                class="font-mono font-medium text-indigo-600">yourdomain.com/b/{{ form.slug ||
                                    'your-slug' }}</span>
                        </p>
                        <p v-if="form.errors.slug" class="mt-1.5 text-xs font-medium text-rose-500">{{ form.errors.slug
                        }}</p>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#11131A] py-3 text-sm font-semibold text-white shadow-lg shadow-[#11131A]/10 transition-all duration-200 hover:shadow-xl hover:shadow-[#11131A]/20 hover:bg-[#2A2D3A] active:scale-[0.97] focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg v-if="form.processing" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            {{ form.processing ? 'Setting up…' : 'Complete Setup & Enter Dashboard' }}
                        </button>
                    </div>

                    <!-- Footer note -->
                    <p class="text-center text-[11px] text-[#9CA3AF]">
                        You can change these settings later in your dashboard.
                    </p>
                </form>

            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&display=swap');

.font-display {
    font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif;
}
</style>