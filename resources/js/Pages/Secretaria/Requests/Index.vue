<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    requests: {
        type: Array,
        default: () => [],
    },
    counts: {
        type: Object,
        default: () => ({
            pending: 0,
            in_review: 0,
            approved: 0,
            completed: 0,
            urgent: 0,
        }),
    },
    filters: {
        type: Object,
        default: () => ({
            status: 'all',
            type: 'all',
            priority: 'all',
        }),
    },
});

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
</script>

<template>
    <Head title="Solicitações da Secretaria" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Solicitações da Secretaria
                </h2>
                <Link
                    :href="route('secretaria.requests.create')"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Nova Solicitação
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Descrição -->
                <div class="mb-6 rounded-lg bg-blue-50 p-4">
                    <p class="text-sm text-blue-800">
                        Área para acompanhar pedidos, revisões e decisões antes de alterar dados oficiais.
                    </p>
                </div>

                <!-- Resumo por Status -->
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-5">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pendentes</p>
                                <p class="text-3xl font-bold text-green-600">{{ counts.pending }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Em análise</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ counts.in_review }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Aprovadas</p>
                                <p class="text-3xl font-bold text-blue-600">{{ counts.approved }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Concluídas</p>
                                <p class="text-3xl font-bold text-purple-600">{{ counts.completed }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Urgentes</p>
                                <p class="text-3xl font-bold text-red-600">{{ counts.urgent }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="mb-6 rounded-lg bg-white p-6 shadow">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select
                                v-model="filters.status"
                                @change="$inertia.get(route('secretaria.requests.index', { status: filters.status, type: filters.type, priority: filters.priority }))"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="all">Todos</option>
                                <option value="pending">Pendentes</option>
                                <option value="in_review">Em análise</option>
                                <option value="approved">Aprovadas</option>
                                <option value="rejected">Rejeitadas</option>
                                <option value="completed">Concluídas</option>
                                <option value="cancelled">Canceladas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select
                                v-model="filters.type"
                                @change="$inertia.get(route('secretaria.requests.index', { status: filters.status, type: filters.type, priority: filters.priority }))"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="all">Todos</option>
                                <option value="registration_review">Revisão de cadastro</option>
                                <option value="personal_data_change">Alteração de dados pessoais</option>
                                <option value="family_link_review">Revisão de vínculo familiar</option>
                                <option value="guardianship_review">Revisão de responsável</option>
                                <option value="child_transition_review">Revisão de transição dos 11 anos</option>
                                <option value="alert_resolution_review">Revisão de alerta</option>
                                <option value="manual_secretary_request">Solicitação manual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prioridade</label>
                            <select
                                v-model="filters.priority"
                                @change="$inertia.get(route('secretaria.requests.index', { status: filters.status, type: filters.type, priority: filters.priority }))"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="all">Todas</option>
                                <option value="low">Baixa</option>
                                <option value="normal">Normal</option>
                                <option value="high">Alta</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Lista de Solicitações -->
                <div class="rounded-lg bg-white shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[200px]">
                                        Título
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[180px]">
                                        Tipo
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[100px]">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[100px]">
                                        Prioridade
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[140px]">
                                        Data
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[100px]">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-if="requests.length === 0">
                                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                        Nenhuma solicitação encontrada.
                                    </td>
                                </tr>
                                <tr v-for="request in requests" :key="request.id">
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ request.title }}
                                        </div>
                                        <div v-if="request.description" class="text-sm text-gray-500">
                                            {{ request.description }}
                                        </div>
                                        <div v-if="request.requester_person" class="mt-1 text-xs text-gray-400">
                                            Pessoa: {{ request.requester_person.full_name }}
                                        </div>
                                        <div v-if="request.related_alert" class="mt-1 text-xs text-gray-400">
                                            Alerta: {{ request.related_alert.title }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="text-sm text-gray-900">
                                            {{ getTypeLabel(request.type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="getStatusColor(request.status)"
                                        >
                                            {{ getStatusLabel(request.status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="getPriorityColor(request.priority)"
                                        >
                                            {{ getPriorityLabel(request.priority) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        {{ request.created_at }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm font-medium">
                                        <Link
                                            :href="route('secretaria.requests.show', request.id)"
                                            class="mr-3 text-indigo-600 hover:text-indigo-900"
                                        >
                                            Ver
                                        </Link>
                                        <Link
                                            v-if="request.can_be_edited"
                                            :href="route('secretaria.requests.edit', request.id)"
                                            class="text-gray-600 hover:text-gray-900"
                                        >
                                            Editar
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
