<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="max-w-2xl">
        <header class="mb-8">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-rose-600 mb-2">Critical Action</p>
            <h2 class="text-2xl font-black text-[#11131A] tracking-tight">Account Termination</h2>
            <p class="mt-3 text-sm font-medium text-slate-500 leading-relaxed">
                Termination is absolute. All data associated with this business, including bookings, service history,
                and branding assets, will be permanently purged from our infrastructure.
            </p>
        </header>

        <button @click="confirmUserDeletion"
            class="px-8 py-4 bg-white border border-rose-200 text-rose-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
            Initiate Deletion
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-10 bg-white rounded-[3rem] overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-[#11131A] tracking-tight uppercase">Confirm Identity</h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Action requires
                            authentication</p>
                    </div>
                </div>

                <p class="text-sm font-medium text-slate-600 leading-relaxed mb-8">
                    To prevent accidental termination, please enter your password to authorize the permanent deletion of
                    your account.
                </p>

                <!-- Password Verification -->
                <div class="relative group mb-8">
                    <label for="password"
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">
                        Security Password
                    </label>
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-900 font-semibold focus:ring-2 focus:ring-rose-500 transition-all shadow-sm"
                        placeholder="••••••••" @keyup.enter="deleteUser" />
                    <InputError :message="form.errors.password" class="mt-2 text-xs font-bold text-rose-500" />
                </div>

                <!-- Modal Actions -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button @click="closeModal"
                        class="w-full sm:w-auto px-8 py-4 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all active:scale-95">
                        Abort Action
                    </button>

                    <button @click="deleteUser" :disabled="form.processing"
                        class="w-full sm:flex-1 px-8 py-4 bg-rose-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-200 active:scale-95 disabled:opacity-50">
                        {{ form.processing ? 'Purging Data...' : 'Confirm Permanent Deletion' }}
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap');

:deep(body),
:deep(input) {
    font-family: 'Space Grotesk', sans-serif !important;
}

/* Custom Modal Padding Fix */
:deep(.overflow-y-auto) {
    padding: 1.5rem;
}
</style>