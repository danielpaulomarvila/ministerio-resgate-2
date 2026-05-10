<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    has_age_violation: {
        type: Boolean,
        default: false,
    },
    active_guardians: {
        type: Array,
        default: () => [],
    },
});

const showSuspendModal = ref(false);
const showReactivateModal = ref(false);

const suspendForm = useForm({
    access_revoked_reason: '',
});

const reactivateForm = useForm({
    access_notes: '',
});

const suspend = () => {
    suspendForm.post(route('secretaria.access.suspend', props.user.id), {
        onSuccess: () => {
            showSuspendModal.value = false;
            suspendForm.reset();
        },
    });
};

const reactivate = () => {
    reactivateForm.patch(route('secretaria.access.reactivate', props.user.id), {
        onSuccess: () => {
            showReactivateModal.value = false;
            reactivateForm.reset();
        },
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${date.getFullYear()} ${hours}:${minutes}`;
};

const ageGroupLabel = () => {
    if (!props.user.person || !props.user.person.birth_date) return '-';
    const age = props.user.person.age;
    if (age < 11) return 'Criança (menos de 11)';
    if (age >= 11 && age < 14) return 'Júnior (11-13)';
    if (age >= 14 && age < 18) return 'Jovem (14-17)';
    return 'Adulto (18+)';
};
</script>

<template>
    <Head title="Detalhes do Acesso" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detalhes do Acesso
                </h2>
                <div class="flex space-x-2">
                    <Link
                        :href="route('secretaria.access.edit', props.user.id)"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Editar
                    </Link>
                    <Link
                        :href="route('secretaria.access.index')"
                        class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                    >
                        Voltar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Alerta de violação de idade -->
                <div
                    v-if="props.has_age_violation"
                    class="mb-6 rounded-md bg-red-50 p-4"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">
                                !
                            </span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Violação de Regra de Idade
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                Este acesso viola a regra de idade. Este usuário é menor de 11 anos e não deveria ter acesso próprio. Revise imediatamente.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dados do usuário -->
                <div class="mb-6 rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Dados do Usuário</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user?.name || '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user?.email || '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-green-100 text-green-800': props.user?.status === 'active',
                                            'bg-red-100 text-red-800': props.user?.status === 'suspended',
                                            'bg-gray-100 text-gray-800': props.user?.status === 'inactive',
                                        }"
                                    >
                                        {{ props.user?.status === 'active' ? 'Ativo' : props.user?.status === 'suspended' ? 'Suspenso' : 'Inativo' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Troca de Senha Pendente</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ props.user?.must_change_password ? 'Sim' : 'Não' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Acesso Concedido em</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(props.user?.access_granted_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Acesso Revogado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(props.user?.access_revoked_at) }}</dd>
                            </div>
                            <div v-if="props.user?.access_revoked_reason">
                                <dt class="text-sm font-medium text-gray-500">Motivo da Revogação</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user.access_revoked_reason }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(props.user?.created_at) }}</dd>
                            </div>
                        </dl>
                        <div v-if="props.user?.access_notes" class="mt-4">
                            <dt class="text-sm font-medium text-gray-500">Observações</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ props.user.access_notes }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Dados da pessoa vinculada -->
                <div v-if="props.user?.person" class="mb-6 rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Pessoa Vinculada</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user.person.full_name || '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email da Pessoa</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user.person.email || '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Idade</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user.person.age ? props.user.person.age + ' anos' : '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Grupo de Idade</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ageGroupLabel() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Batizado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user.person.is_baptized ? 'Sim' : 'Não' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pode ser Membro</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ props.user.person.can_be_member ? 'Sim' : 'Não' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Responsáveis ativos (se for Júnior) -->
                <div v-if="props.active_guardians.length > 0" class="mb-6 rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Responsáveis Ativos Autorizados</h3>
                    </div>
                    <div class="p-6">
                        <div v-for="guardian in props.active_guardians" :key="guardian.id" class="mb-3">
                            <div class="text-sm font-medium text-gray-900">{{ guardian.name }}</div>
                            <div class="text-sm text-gray-500">Autorizado para login: {{ guardian.can_authorize_login ? 'Sim' : 'Não' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Ações</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex space-x-3">
                            <Link
                                :href="route('secretaria.access.edit', props.user?.id)"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                            >
                                Editar
                            </Link>
                            <button
                                v-if="props.user?.status === 'active'"
                                @click="showSuspendModal = true"
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                            >
                                Suspender
                            </button>
                            <button
                                v-else-if="props.user?.status === 'suspended'"
                                @click="showReactivateModal = true"
                                class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                            >
                                Reativar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Suspensão -->
        <div v-if="showSuspendModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="rounded-lg bg-white shadow-xl">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Suspender Acesso</h3>
                        <form @submit.prevent="suspend">
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700">
                                    Motivo da Suspensão
                                </label>
                                <textarea
                                    id="reason"
                                    v-model="suspendForm.access_revoked_reason"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="showSuspendModal = false"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="suspendForm.processing"
                                    class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 disabled:opacity-50"
                                >
                                    Suspender
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Reativação -->
        <div v-if="showReactivateModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="rounded-lg bg-white shadow-xl">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Reativar Acesso</h3>
                        <form @submit.prevent="reactivate">
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">
                                    Observações (opcional)
                                </label>
                                <textarea
                                    id="notes"
                                    v-model="reactivateForm.access_notes"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="showReactivateModal = false"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="reactivateForm.processing"
                                    class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 disabled:opacity-50"
                                >
                                    Reativar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
