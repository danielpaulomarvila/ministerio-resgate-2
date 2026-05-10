<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    alerts: {
        type: Array,
        default: () => [],
    },
    counts: {
        type: Object,
        default: () => ({
            open: 0,
            resolved: 0,
            ignored: 0,
            info: 0,
            warning: 0,
            danger: 0,
        }),
    },
    filters: {
        type: Object,
        default: () => ({
            status: 'all',
            type: 'all',
            severity: 'all',
        }),
    },
});

const form = useForm({
    resolution_notes: '',
});

const resolveAlert = (alert) => {
    window.location.href = route('secretaria.alerts.resolve.show', alert.id);
};

const ignoreAlert = (alert) => {
    form.patch(route('secretaria.alerts.ignore', alert.id), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const regenerateAlerts = () => {
    form.post(route('secretaria.alerts.regenerate'), {
        onSuccess: () => {
            window.location.reload();
        },
    });
};

const getSeverityColor = (severity) => {
    switch (severity) {
        case 'low':
            return 'bg-blue-100 text-blue-800';
        case 'medium':
            return 'bg-yellow-100 text-yellow-800';
        case 'high':
        case 'critical':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getSeverityLabel = (severity) => {
    switch (severity) {
        case 'low':
            return 'Informativo';
        case 'medium':
            return 'Atenção';
        case 'high':
            return 'Alto';
        case 'critical':
            return 'Crítico';
        default:
            return severity;
    }
};

const getStatusColor = (status) => {
    switch (status) {
        case 'pending':
            return 'bg-green-100 text-green-800';
        case 'in_progress':
            return 'bg-yellow-100 text-yellow-800';
        case 'resolved':
            return 'bg-blue-100 text-blue-800';
        case 'dismissed':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'pending':
            return 'Aberto';
        case 'in_progress':
            return 'Em andamento';
        case 'resolved':
            return 'Resolvido';
        case 'dismissed':
            return 'Ignorado';
        default:
            return status;
    }
};

const getTypeLabel = (type) => {
    switch (type) {
        case 'child_turning_11':
            return 'Criança completando 11 anos';
        case 'minor_without_guardian':
            return 'Menor sem responsável';
        case 'person_without_family':
            return 'Pessoa sem família';
        case 'incomplete_registration':
            return 'Cadastro incompleto';
        case 'guardianship_ending_soon':
            return 'Responsabilidade terminando';
        case 'guardianship_expired':
            return 'Responsabilidade vencida';
        default:
            return type;
    }
};
</script>

<template>
    <Head title="Alertas da Secretaria" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Alertas da Secretaria
                </h2>
                <button
                    @click="regenerateAlerts"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Regenerar Alertas
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Resumo por Status -->
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Abertos</p>
                                <p class="text-3xl font-bold text-green-600">{{ counts.open }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-green-100 p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Resolvidos</p>
                                <p class="text-3xl font-bold text-blue-600">{{ counts.resolved }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-blue-100 p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Ignorados</p>
                                <p class="text-3xl font-bold text-gray-600">{{ counts.ignored }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-gray-100 p-3">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumo por Severidade -->
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Informativos</p>
                                <p class="text-3xl font-bold text-blue-600">{{ counts.info }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-blue-100 p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Atenção</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ counts.warning }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-yellow-100 p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Urgentes</p>
                                <p class="text-3xl font-bold text-red-600">{{ counts.danger }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-red-100 p-3">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                @change="$inertia.get(route('secretaria.alerts.index', { status: filters.status, type: filters.type, severity: filters.severity }))"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="all">Todos</option>
                                <option value="pending">Abertos</option>
                                <option value="resolved">Resolvidos</option>
                                <option value="dismissed">Ignorados</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select
                                v-model="filters.type"
                                @change="$inertia.get(route('secretaria.alerts.index', { status: filters.status, type: filters.type, severity: filters.severity }))"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="all">Todos</option>
                                <option value="child_turning_11">Criança completando 11 anos</option>
                                <option value="minor_without_guardian">Menor sem responsável</option>
                                <option value="person_without_family">Pessoa sem família</option>
                                <option value="incomplete_registration">Cadastro incompleto</option>
                                <option value="guardianship_ending_soon">Responsabilidade terminando</option>
                                <option value="guardianship_expired">Responsabilidade vencida</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Severidade</label>
                            <select
                                v-model="filters.severity"
                                @change="$inertia.get(route('secretaria.alerts.index', { status: filters.status, type: filters.type, severity: filters.severity }))"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="all">Todas</option>
                                <option value="low">Informativo</option>
                                <option value="medium">Atenção</option>
                                <option value="high">Alto</option>
                                <option value="critical">Crítico</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Lista de Alertas -->
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
                                        Severidade
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[120px]">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[140px]">
                                        Data
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 min-w-[150px]">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-if="alerts.length === 0">
                                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                        Nenhum alerta encontrado.
                                    </td>
                                </tr>
                                <tr v-for="alert in alerts" :key="alert.id">
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ alert.title }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ alert.message }}
                                        </div>
                                        <div v-if="alert.related_person" class="mt-1 text-xs text-gray-400">
                                            Pessoa: {{ alert.related_person.full_name }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="text-sm text-gray-900">
                                            {{ getTypeLabel(alert.type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="getSeverityColor(alert.severity)"
                                        >
                                            {{ getSeverityLabel(alert.severity) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="getStatusColor(alert.status)"
                                        >
                                            {{ getStatusLabel(alert.status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        {{ alert.detected_at }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm font-medium">
                                        <Link
                                            :href="route('secretaria.alerts.show', alert.id)"
                                            class="mr-3 text-indigo-600 hover:text-indigo-900"
                                        >
                                            Ver
                                        </Link>
                                        <Link
                                            v-if="alert.status === 'pending' || alert.status === 'in_progress'"
                                            :href="route('secretaria.alerts.resolve.show', alert.id)"
                                            class="mr-3 text-green-600 hover:text-green-900"
                                        >
                                            Tratar
                                        </Link>
                                        <button
                                            v-if="alert.status === 'pending' || alert.status === 'in_progress'"
                                            @click="ignoreAlert(alert)"
                                            class="text-gray-600 hover:text-gray-900"
                                        >
                                            Ignorar
                                        </button>
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
