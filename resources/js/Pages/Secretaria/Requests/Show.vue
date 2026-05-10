<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    request: {
        type: Object,
        required: true,
    },
});

const markInReviewForm = useForm({
    decision_notes: '',
});

const approveForm = useForm({
    decision_notes: '',
});

const rejectForm = useForm({
    decision_notes: '',
});

const completeForm = useForm({
    decision_notes: '',
});

const cancelForm = useForm({
    decision_notes: '',
});

const markInReview = () => {
    markInReviewForm.patch(route('secretaria.requests.mark-in-review', props.request.id), {
        onSuccess: () => {
            markInReviewForm.reset();
        },
    });
};

const approve = () => {
    approveForm.patch(route('secretaria.requests.approve', props.request.id), {
        onSuccess: () => {
            approveForm.reset();
        },
    });
};

const reject = () => {
    rejectForm.patch(route('secretaria.requests.reject', props.request.id), {
        onSuccess: () => {
            rejectForm.reset();
        },
    });
};

const complete = () => {
    completeForm.patch(route('secretaria.requests.complete', props.request.id), {
        onSuccess: () => {
            completeForm.reset();
        },
    });
};

const cancel = () => {
    cancelForm.patch(route('secretaria.requests.cancel', props.request.id), {
        onSuccess: () => {
            cancelForm.reset();
        },
    });
};

const getStatusColor = (status) => {
    switch (status) {
        case 'pending':
            return 'bg-green-100 text-green-800';
        case 'in_review':
            return 'bg-yellow-100 text-yellow-800';
        case 'approved':
            return 'bg-blue-100 text-blue-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        case 'completed':
            return 'bg-purple-100 text-purple-800';
        case 'cancelled':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'pending':
            return 'Pendente';
        case 'in_review':
            return 'Em análise';
        case 'approved':
            return 'Aprovada';
        case 'rejected':
            return 'Rejeitada';
        case 'completed':
            return 'Concluída';
        case 'cancelled':
            return 'Cancelada';
        default:
            return status;
    }
};

const getPriorityColor = (priority) => {
    switch (priority) {
        case 'low':
            return 'bg-blue-100 text-blue-800';
        case 'normal':
            return 'bg-gray-100 text-gray-800';
        case 'high':
            return 'bg-orange-100 text-orange-800';
        case 'urgent':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getPriorityLabel = (priority) => {
    switch (priority) {
        case 'low':
            return 'Baixa';
        case 'normal':
            return 'Normal';
        case 'high':
            return 'Alta';
        case 'urgent':
            return 'Urgente';
        default:
            return priority;
    }
};

const getTypeLabel = (type) => {
    switch (type) {
        case 'registration_review':
            return 'Revisão de cadastro';
        case 'personal_data_change':
            return 'Alteração de dados pessoais';
        case 'family_link_review':
            return 'Revisão de vínculo familiar';
        case 'guardianship_review':
            return 'Revisão de responsável';
        case 'child_transition_review':
            return 'Revisão de transição dos 11 anos';
        case 'alert_resolution_review':
            return 'Revisão de alerta';
        case 'manual_secretary_request':
            return 'Solicitação manual';
        default:
            return type;
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${date.getFullYear()} ${hours}:${minutes}`;
};
</script>

<template>
    <Head title="Detalhes da Solicitação" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detalhes da Solicitação
                </h2>
                <div class="flex space-x-2">
                    <Link
                        v-if="request.status === 'pending' || request.status === 'in_review'"
                        :href="route('secretaria.requests.edit', request.id)"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Editar
                    </Link>
                    <Link
                        :href="route('secretaria.requests.index')"
                        class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                    >
                        Voltar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ request.title }}
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    {{ request.description }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-sm font-semibold"
                                    :class="getStatusColor(request.status)"
                                >
                                    {{ getStatusLabel(request.status) }}
                                </span>
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-sm font-semibold"
                                    :class="getPriorityColor(request.priority)"
                                >
                                    {{ getPriorityLabel(request.priority) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Informações da Solicitação</h4>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ getTypeLabel(request.type) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ formatDate(request.created_at) }}
                                </dd>
                            </div>
                            <div v-if="request.due_at">
                                <dt class="text-sm font-medium text-gray-500">Prazo</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ formatDate(request.due_at) }}
                                </dd>
                            </div>
                            <div v-if="request.assigned_to_user">
                                <dt class="text-sm font-medium text-gray-500">Responsável</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ request.assigned_to_user.name }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div v-if="request.requester_person" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Pessoa Relacionada</h4>
                        <div class="flex items-center space-x-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100">
                                <span class="text-lg font-bold text-indigo-600">
                                    {{ request.requester_person.full_name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ request.requester_person.full_name }}
                                </p>
                                <Link
                                    :href="route('people.show', request.requester_person.id)"
                                    class="mt-1 text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Ver perfil
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="request.related_alert" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Alerta Relacionado</h4>
                        <div class="flex items-center space-x-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                <span class="text-lg font-bold text-red-600">
                                    {{ request.related_alert.title.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ request.related_alert.title }}
                                </p>
                                <Link
                                    :href="route('secretaria.alerts.show', request.related_alert.id)"
                                    class="mt-1 text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Ver alerta
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Histórico</h4>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div v-if="request.submitted_at">
                                <dt class="text-sm font-medium text-gray-500">Enviado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ request.submitted_at }}
                                </dd>
                            </div>
                            <div v-if="request.reviewed_at">
                                <dt class="text-sm font-medium text-gray-500">Revisado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ request.reviewed_at }}
                                </dd>
                            </div>
                            <div v-if="request.approved_at">
                                <dt class="text-sm font-medium text-gray-500">Aprovado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ request.approved_at }}
                                </dd>
                            </div>
                            <div v-if="request.rejected_at">
                                <dt class="text-sm font-medium text-gray-500">Rejeitado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ request.rejected_at }}
                                </dd>
                            </div>
                            <div v-if="request.completed_at">
                                <dt class="text-sm font-medium text-gray-500">Concluído em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ request.completed_at }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div v-if="request.internal_notes" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Observações Internas</h4>
                        <p class="text-sm text-gray-600">
                            {{ request.internal_notes }}
                        </p>
                    </div>

                    <div v-if="request.decision_notes" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Decisão/Observação Final</h4>
                        <p class="text-sm text-gray-600">
                            {{ request.decision_notes }}
                        </p>
                    </div>

                    <div v-if="request.status === 'pending' || request.status === 'in_review'" class="p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Ações</h4>
                        <div class="space-y-6">
                            <!-- Marcar em análise -->
                            <div v-if="request.status === 'pending'">
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Observações (opcional)
                                        </label>
                                        <textarea
                                            v-model="markInReviewForm.decision_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Adicione observações sobre a análise..."
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="markInReview"
                                            :disabled="markInReviewForm.processing"
                                            class="rounded-md bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700 disabled:opacity-50"
                                        >
                                            {{ markInReviewForm.processing ? 'Marcando...' : 'Marcar em análise' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Aprovar -->
                            <div>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Observações da aprovação <span class="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            v-model="approveForm.decision_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Descreva o motivo da aprovação..."
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="approve"
                                            :disabled="approveForm.processing"
                                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                                        >
                                            {{ approveForm.processing ? 'Aprovando...' : 'Aprovar' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejeitar -->
                            <div>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Motivo da rejeição <span class="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            v-model="rejectForm.decision_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Explique o motivo da rejeição..."
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="reject"
                                            :disabled="rejectForm.processing"
                                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
                                        >
                                            {{ rejectForm.processing ? 'Rejeitando...' : 'Rejeitar' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Concluir -->
                            <div>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Observações da conclusão <span class="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            v-model="completeForm.decision_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Descreva como a solicitação foi concluída..."
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="complete"
                                            :disabled="completeForm.processing"
                                            class="rounded-md bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700 disabled:opacity-50"
                                        >
                                            {{ completeForm.processing ? 'Concluindo...' : 'Concluir' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancelar -->
                            <div>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Motivo do cancelamento <span class="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            v-model="cancelForm.decision_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Explique o motivo do cancelamento..."
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="cancel"
                                            :disabled="cancelForm.processing"
                                            class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
                                        >
                                            {{ cancelForm.processing ? 'Cancelando...' : 'Cancelar' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="request.status === 'approved' || request.status === 'completed'" class="p-6">
                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm text-green-800">
                                Esta solicitação foi {{ getStatusLabel(request.status) }}.
                            </p>
                        </div>
                    </div>

                    <div v-if="request.status === 'rejected'" class="p-6">
                        <div class="rounded-md bg-red-50 p-4">
                            <p class="text-sm text-red-800">
                                Esta solicitação foi rejeitada.
                            </p>
                        </div>
                    </div>

                    <div v-if="request.status === 'cancelled'" class="p-6">
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-800">
                                Esta solicitação foi cancelada.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
