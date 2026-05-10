<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    request: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: props.request.title,
    description: props.request.description,
    priority: props.request.priority,
    requester_person_id: props.request.requester_person_id,
    related_alert_id: props.request.related_alert_id,
    assigned_to_user_id: props.request.assigned_to_user_id,
    due_at: props.request.due_at,
    internal_notes: props.request.internal_notes,
});

const submit = () => {
    form.put(route('secretaria.requests.update', props.request.id), {
        onSuccess: () => {
            // Sucesso
        },
    });
};
</script>

<template>
    <Head title="Editar Solicitação" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Editar Solicitação
                </h2>
                <Link
                    :href="route('secretaria.requests.show', request.id)"
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
                            Editar Solicitação #{{ request.id }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Esta solicitação não altera dados oficiais automaticamente.
                        </p>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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

                            <div class="mt-6 flex justify-end space-x-4">
                                <Link
                                    :href="route('secretaria.requests.show', request.id)"
                                    class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
