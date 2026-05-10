<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    alert: {
        type: Object,
        required: true,
    },
});

const verifyForm = useForm({
    resolution_notes: '',
});

const ignoreForm = useForm({
    resolution_notes: '',
});

const inProgressForm = useForm({
    resolution_notes: '',
});

const markInProgress = () => {
    inProgressForm.patch(route('secretaria.alerts.mark-in-progress', props.alert.id), {
        onSuccess: () => {
            window.location.reload();
        },
    });
};

const verifyResolution = () => {
    verifyForm.post(route('secretaria.alerts.verify-resolution', props.alert.id), {
        onSuccess: () => {
            window.location.href = route('secretaria.alerts.index');
        },
    });
};

const ignoreAlert = () => {
    ignoreForm.patch(route('secretaria.alerts.ignore', props.alert.id), {
        onSuccess: () => {
            window.location.href = route('secretaria.alerts.index');
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

const getActionButtons = () => {
    switch (props.alert.type) {
        case 'incomplete_registration':
            return [
                {
                    label: 'Editar cadastro da pessoa',
                    route: route('people.edit', props.alert.related_person?.id),
                    color: 'bg-indigo-600 hover:bg-indigo-700',
                },
            ];
        case 'person_without_family':
            return [
                {
                    label: 'Ver famílias',
                    route: route('families.index'),
                    color: 'bg-indigo-600 hover:bg-indigo-700',
                },
                {
                    label: 'Criar família',
                    route: route('families.create'),
                    color: 'bg-green-600 hover:bg-green-700',
                },
                {
                    label: 'Editar pessoa',
                    route: route('people.edit', props.alert.related_person?.id),
                    color: 'bg-gray-600 hover:bg-gray-700',
                },
            ];
        case 'minor_without_guardian':
            return [
                {
                    label: 'Criar responsável',
                    route: route('guardianships.create'),
                    color: 'bg-indigo-600 hover:bg-indigo-700',
                },
            ];
        case 'child_turning_11':
            return [
                {
                    label: 'Ver cadastro da pessoa',
                    route: route('people.show', props.alert.related_person?.id),
                    color: 'bg-indigo-600 hover:bg-indigo-700',
                },
            ];
        case 'guardianship_ending_soon':
        case 'guardianship_expired':
            return [
                {
                    label: 'Editar responsabilidade',
                    route: route('guardianships.index'),
                    color: 'bg-indigo-600 hover:bg-indigo-700',
                },
            ];
        default:
            return [];
    }
};
</script>

<template>
    <Head title="Resolver Alerta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Resolver Alerta
                </h2>
                <Link
                    :href="route('secretaria.alerts.index')"
                    class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                >
                    Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ alert.title }}
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    {{ alert.message }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-sm font-semibold"
                                    :class="getStatusColor(alert.status)"
                                >
                                    {{ getStatusLabel(alert.status) }}
                                </span>
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-sm font-semibold"
                                    :class="getSeverityColor(alert.severity)"
                                >
                                    {{ getSeverityLabel(alert.severity) }}
                                </span>
                                <Link
                                    :href="route('secretaria.requests.create', { alert_id: alert.id })"
                                    class="mt-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                                >
                                    Criar solicitação de revisão
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Informações do Alerta</h4>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ getTypeLabel(alert.type) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Detectado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ alert.detected_at }}
                                </dd>
                            </div>
                            <div v-if="alert.resolved_at">
                                <dt class="text-sm font-medium text-gray-500">Resolvido em</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ alert.resolved_at }}
                                </dd>
                            </div>
                            <div v-if="alert.resolved_by">
                                <dt class="text-sm font-medium text-gray-500">Resolvido por</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ alert.resolved_by.name }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div v-if="alert.related_person" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Pessoa Relacionada</h4>
                        <div class="flex items-center space-x-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100">
                                <span class="text-lg font-bold text-indigo-600">
                                    {{ alert.related_person.full_name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ alert.related_person.full_name }}
                                </p>
                                <p v-if="alert.related_person.birth_date" class="text-sm text-gray-500">
                                    Nascimento: {{ alert.related_person.birth_date }}
                                </p>
                                <p v-if="alert.related_person.primary_phone" class="text-sm text-gray-500">
                                    Telefone: {{ alert.related_person.primary_phone }}
                                </p>
                                <p v-if="alert.related_person.email" class="text-sm text-gray-500">
                                    Email: {{ alert.related_person.email }}
                                </p>
                                <Link
                                    :href="route('people.show', alert.related_person.id)"
                                    class="mt-1 text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Ver perfil
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="alert.related_family" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Família Relacionada</h4>
                        <div class="flex items-center space-x-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <span class="text-lg font-bold text-green-600">
                                    {{ alert.related_family.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ alert.related_family.name }}
                                </p>
                                <Link
                                    :href="route('families.show', alert.related_family.id)"
                                    class="mt-1 text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Ver família
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="alert.resolution_notes" class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Observações</h4>
                        <p class="text-sm text-gray-600">
                            {{ alert.resolution_notes }}
                        </p>
                    </div>

                    <div class="border-b border-gray-200 p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Ações Contextuais</h4>
                        <div v-if="getActionButtons().length > 0" class="flex flex-wrap gap-2">
                            <Link
                                v-for="(button, index) in getActionButtons()"
                                :key="index"
                                :href="button.route"
                                :class="button.color"
                                class="rounded-md px-4 py-2 text-sm font-medium text-white"
                            >
                                {{ button.label }}
                            </Link>
                        </div>
                        <p v-else class="text-sm text-gray-500">
                            Nenhuma ação contextual disponível para este tipo de alerta.
                        </p>
                    </div>

                    <div v-if="alert.status === 'pending' || alert.status === 'in_progress'" class="p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-900">Tratamento do Alerta</h4>
                        
                        <div class="mb-6 rounded-md bg-blue-50 p-4">
                            <h5 class="mb-2 text-sm font-semibold text-blue-900">Instruções</h5>
                            <p class="text-sm text-blue-800">
                                1. Corrija o problema real no cadastro correspondente usando as ações contextuais acima.<br>
                                2. Volte a esta tela e clique em "Verificar resolução".<br>
                                3. O sistema validará se o problema foi realmente corrigido.<br>
                                4. Se estiver corrigido, o alerta será marcado como resolvido.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <!-- Marcar em andamento -->
                            <div v-if="alert.status === 'pending'">
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Observações (opcional)
                                        </label>
                                        <textarea
                                            v-model="inProgressForm.resolution_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Adicione observações sobre o andamento..."
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="markInProgress"
                                            :disabled="inProgressForm.processing"
                                            class="rounded-md bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700 disabled:opacity-50"
                                        >
                                            {{ inProgressForm.processing ? 'Marcando...' : 'Marcar em andamento' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Verificar resolução -->
                            <div>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Observações da resolução <span class="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            v-model="verifyForm.resolution_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Descreva como o problema foi corrigido..."
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="verifyResolution"
                                            :disabled="verifyForm.processing"
                                            class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                                        >
                                            {{ verifyForm.processing ? 'Verificando...' : 'Verificar resolução' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Ignorar -->
                            <div>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Motivo para ignorar <span class="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            v-model="ignoreForm.resolution_notes"
                                            rows="2"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Explique por que este alerta deve ser ignorado..."
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            @click="ignoreAlert"
                                            :disabled="ignoreForm.processing"
                                            class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
                                        >
                                            {{ ignoreForm.processing ? 'Ignorando...' : 'Ignorar' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="alert.status === 'resolved'" class="p-6">
                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm text-green-800">
                                Este alerta foi resolvido em {{ alert.resolved_at }}.
                            </p>
                        </div>
                    </div>

                    <div v-if="alert.status === 'dismissed'" class="p-6">
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-800">
                                Este alerta foi ignorado.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
