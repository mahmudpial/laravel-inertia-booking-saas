<script setup>
/**
 * System Configuration Hub v4.2.0
 *
 * CORE SYSTEMS:
 * 1. Runtime Snapshot: Read-only view of the active app/env configuration.
 * 2. Service Drivers: Mail, queue, session, cache and filesystem backends.
 *
 * This is intentionally read-only for now — mutating live config from the
 * UI (SMTP creds, maintenance mode, etc.) needs its own guarded write path
 * and isn't wired up yet.
 */

import SovereignLayout from '@/Layouts/SovereignLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    runtime: Object,
    services: Object,
});

const runtimeRows = [
    { key: 'app_name', label: 'Application Name' },
    { key: 'app_env', label: 'Environment' },
    { key: 'app_debug', label: 'Debug Mode' },
    { key: 'app_url', label: 'App URL' },
    { key: 'app_timezone', label: 'Timezone' },
    { key: 'php_version', label: 'PHP Version' },
    { key: 'laravel_version', label: 'Laravel Version' },
];

const serviceRows = [
    { key: 'database_driver', label: 'Database Driver' },
    { key: 'mail_driver', label: 'Mail Driver' },
    { key: 'mail_from', label: 'Mail From Address' },
    { key: 'queue_connection', label: 'Queue Connection' },
    { key: 'session_driver', label: 'Session Driver' },
    { key: 'filesystem_disk', label: 'Filesystem Disk' },
    { key: 'cache_store', label: 'Cache Store' },
];
</script>

<template>

    <Head title="System Config | Sovereign Oversight" />

    <SovereignLayout>
        <div class="space-y-16 animate-fadeIn">

            <!-- HEADER -->
            <div class="space-y-6">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-indigo-600">Read-only runtime
                        snapshot</span>
                </div>
                <h1 class="text-7xl lg:text-9xl font-black tracking-tighter leading-[0.8] text-[#09090B] uppercase">
                    System <br /> <span class="text-indigo-600">Config.</span>
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- RUNTIME -->
                <div class="bg-white rounded-[4rem] border border-slate-200/60 shadow-2xl overflow-hidden">
                    <div class="px-12 py-12 border-b border-slate-100 bg-slate-50/40">
                        <h3 class="text-2xl font-black text-[#09090B] tracking-tighter uppercase">Runtime Environment
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <div v-for="row in runtimeRows" :key="row.key"
                            class="flex items-center justify-between px-12 py-7">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">{{ row.label
                            }}</span>
                            <span v-if="typeof runtime?.[row.key] === 'boolean'"
                                class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest"
                                :class="runtime[row.key] ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100'">
                                {{ runtime[row.key] ? 'Enabled' : 'Disabled' }}
                            </span>
                            <span v-else
                                class="text-sm font-black text-[#09090B] tracking-tight truncate max-w-[220px]">
                                {{ runtime?.[row.key] ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- SERVICES -->
                <div class="bg-[#09090B] rounded-[4rem] border border-white/5 shadow-2xl overflow-hidden relative">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#4f46e522_0%,transparent_70%)]">
                    </div>
                    <div class="relative z-10 px-12 py-12 border-b border-white/5">
                        <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Service Drivers</h3>
                    </div>
                    <div class="relative z-10 divide-y divide-white/5">
                        <div v-for="row in serviceRows" :key="row.key"
                            class="flex items-center justify-between px-12 py-7">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">{{ row.label
                            }}</span>
                            <span
                                class="text-sm font-black text-indigo-400 tracking-tight uppercase truncate max-w-[220px]">
                                {{ services?.[row.key] ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-[3rem] p-10 flex items-start gap-6">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shrink-0 text-white font-black">
                    !</div>
                <p class="text-xs font-bold text-amber-700 leading-relaxed uppercase tracking-widest">
                    This panel is read-only. Editing SMTP credentials, storage endpoints, or maintenance mode from
                    here isn't implemented yet — config changes still go through <code class="font-black">.env</code>.
                </p>
            </div>
        </div>
    </SovereignLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap');

:deep(body),
:deep(h1),
:deep(h2),
:deep(h3),
:deep(h4),
:deep(span),
:deep(p),
:deep(button),
:deep(a) {
    font-family: 'Space Grotesk', sans-serif !important;
    -webkit-font-smoothing: antialiased;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.8s ease-out forwards;
}
</style>
