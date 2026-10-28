<script setup>
import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    runtime: {
        type: Object,
        default: () => ({})
    },
    services: {
        type: Object,
        default: () => ({})
    },
});

const runtimeRows = [
    { key: 'app_name', label: 'Application Name' },
    { key: 'app_env', label: 'Environment' },
    { key: 'app_debug', label: 'Debug Mode' },
    { key: 'app_url', label: 'App URL' },
    { key: 'app_timezone', label: 'Timezone' },
    { key: 'php_version', label: 'PHP Version' },
    { key: 'laravel_version', label: 'Laravel Framework' },
];

const serviceRows = [
    { key: 'database_driver', label: 'Database Driver' },
    { key: 'queue_connection', label: 'Queue Connection' },
    { key: 'cache_store', label: 'Cache Store' },
    { key: 'session_driver', label: 'Session Driver' },
    { key: 'filesystem_disk', label: 'Storage Disk' },
    { key: 'mail_driver', label: 'Mail Driver' },
    { key: 'mail_from', label: 'Default Mailer From' },
];
</script>

<template>
    <Head title="System Runtime Configuration — Sovereign Control" />

    <SovereignLayout>
        <div class="space-y-8 max-w-5xl mx-auto pb-16">
            <!-- Header -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                        Read-Only Runtime Snapshot
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                    System Runtime Configuration
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Current active runtime environment, driver bindings, and framework settings.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Runtime Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-sm text-slate-900">Application Runtime</h2>
                            <p class="text-[11px] text-slate-400">Environment & engine variables</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <div
                            v-for="row in runtimeRows"
                            :key="row.key"
                            class="flex items-center justify-between py-2 border-b border-slate-50 last:border-b-0 text-xs"
                        >
                            <span class="text-slate-500 font-medium">{{ row.label }}</span>
                            <span class="font-mono font-semibold text-slate-900 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                                {{ runtime[row.key] ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Service Drivers Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-sm text-slate-900">Service Drivers & Connections</h2>
                            <p class="text-[11px] text-slate-400">Storage, database & background workers</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <div
                            v-for="row in serviceRows"
                            :key="row.key"
                            class="flex items-center justify-between py-2 border-b border-slate-50 last:border-b-0 text-xs"
                        >
                            <span class="text-slate-500 font-medium">{{ row.label }}</span>
                            <span class="font-mono font-semibold text-brand-700 bg-brand-50/50 px-2 py-0.5 rounded-md border border-brand-100">
                                {{ services[row.key] ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SovereignLayout>
</template>
