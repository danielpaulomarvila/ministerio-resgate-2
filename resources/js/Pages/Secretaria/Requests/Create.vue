<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    alert: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    type: props.alert ? 'alert_resolution_review' : 'manual_secretary_request',
    priority: 'normal',
    title: props.alert ? `Revisão: ${props.alert.title}` : '',
    description: props.alert ? props.alert.message : '',
    requester_person_id: props.alert?.related_person?.id || null,
    related_alert_id: props.alert?.id || null,
    assigned_to_user_id: null,
    due_at: null,
    internal_notes: '',
});

const submit = () => {
    form.post(route('secretaria.requests.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const getTypeHelpText = (type) => {
    switch (type) {
        case 'registration_review':
            return 'Use esta solicitação quando houver dúvida sobre o cadastro de uma pessoa.';
        case 'personal_data_change':
            return 'Use esta solicitação quando houver pedido de alteração de dados pessoais.';
        case 'family_link_review':
            return 'Use esta solicitação quando houver dúvida ou pendência sobre a família de uma pessoa.';
        case 'guardianship_review':
            return 'Use esta solicitação quando houver dúvida sobre a responsabilidade legal.';
        case 'child_transition_review':
            return 'Use esta solicitação para revisar a transição de criança para Júnior aos 11 anos.';
        case 'alert_resolution_review':
            return 'Use esta solicitação para revisar a resolução de um alerta.';
        case 'manual_secretary_request':
            return 'Use esta solicitação para pedidos internos da Secretaria.';
        default:
            return '';
    }
};
</script>

<template>
    <Head title="Nova Solicitação" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Nova Solicitação
                </h2>
                <Link
                    :href="route('secretaria.requests.index')"
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
                        <h3 class="text-lg font-semibold text-gray-900">
                            Criar Solicitação
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Esta solicitação não altera dados oficiais automaticamente.
                        </p>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Tipo -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Tipo <span class="text-red-600">*</span>
                                    </label>
                                    <select
                                        v-model="form.type"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required
                                    >
                                        <option value="registration_review">Revisão de cadastro</option>
                                        <option value="personal_data_change">Alteração de dados pessoais</option>
                                        <option value="family_link_review">Revisão de vínculo familiar</option>
                                        <option value="guardianship_review">Revisão de responsável</option>
                                        <option value="child_transition_review">Revisão de transição dos 11 anos</option>
                                        <option value="alert_resolution_review">Revisão de alerta</option>
                                        <option value="manual_secretary_request">Solicitação manual</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ getTypeHelpText(form.type) }}
                                    </p>
                                </div>

                                <!-- Prioridade -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Prioridade <span class="text-red-600">*</span>
                                    </label>
                                    <select
                                        v-model="form.priority"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required
                                    >
                                        <option value="low">Baixa</option>
                                        <option value="normal">Normal</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>

                                <!-- Título -->
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Título <span class="text-red-600">*</span>
                                    </label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required
                                        placeholder="Título da solicitação"
                                    />
                                </div>

                                <!-- Descrição -->
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Descrição
                                    </label>
                                    <textarea
                                        v-model="form.description"
                                        rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Descreva a solicitação em detalhes..."
                                    ></textarea>
                                </div>

                                <!-- Alerta relacionado (se vier de alerta) -->
                                <div v-if="alert" class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Alerta relacionado
                                    </label>
                                    <div class="rounded-md bg-gray-50 p-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ alert.title }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ alert.message }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Pessoa relacionada -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Pessoa relacionada
                                    </label>
                                    <input
                                        v-model="form.requester_person_id"
                                        type="number"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="ID da pessoa"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Deixe vazio se não houver pessoa relacionada.
                                    </p>
                                </div>

                                <!-- Responsável pela análise -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Responsável pela análise
                                    </label>
                                    <input
                                        v-model="form.assigned_to_user_id"
                                        type="number"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="ID do usuário"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Deixe vazio para não atribuir responsável.
                                    </p>
                                </div>

                                <!-- Prazo -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Prazo
                                    </label>
                                    <input
                                        v-model="form.due_at"
                                        type="datetime-local"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Deixe vazio se não houver prazo definido.
                                    </p>
                                </div>

                                <!-- Observações internas -->
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Observações internas
                                    </label>
                                    <textarea
                                        v-model="form.internal_notes"
                                        rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Observações apenas para uso interno da Secretaria..."
                                    ></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Salvando...' : 'Criar Solicitação' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
