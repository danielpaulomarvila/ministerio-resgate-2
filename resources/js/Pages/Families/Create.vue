<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de criação de família
 * Módulo Secretaria - Etapa 2
 *
 * Esta página permite criar uma nova família no sistema.
 * A Secretaria pode definir nome, descrição, responsável principal,
 * status e observações.
 */

const props = defineProps({
    people: {
        type: Array,
        required: true
    }
});

const form = useForm({
    name: '',
    description: '',
    responsible_person_id: null,
    status: 'active',
    notes: ''
});

const submit = () => {
    form.post(route('families.store'));
};

/**
 * Formata o status para exibição em português
 */
const formatStatus = (status) => {
    const statusMap = {
        'active': 'Ativa',
        'inactive': 'Inativa'
    };
    return statusMap[status] || status;
};
</script>

<template>
    <Head title="Nova Família" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Nova Família
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">
                            Dados da Família
                        </h3>

                        <form @submit.prevent="submit">
                            <!-- Bloco Dados da família -->
                            <div class="mb-8">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Nome da família -->
                                    <div class="col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700">
                                            Nome da Família <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        />
                                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.name }}
                                        </div>
                                    </div>

                                    <!-- Descrição -->
                                    <div class="col-span-2">
                                        <label for="description" class="block text-sm font-medium text-gray-700">
                                            Descrição
                                        </label>
                                        <textarea
                                            id="description"
                                            v-model="form.description"
                                            rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.description }}
                                        </div>
                                    </div>

                                    <!-- Responsável principal -->
                                    <div>
                                        <label for="responsible_person_id" class="block text-sm font-medium text-gray-700">
                                            Responsável Principal
                                        </label>
                                        <select
                                            id="responsible_person_id"
                                            v-model="form.responsible_person_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione uma pessoa</option>
                                            <option v-for="person in people" :key="person.id" :value="person.id">
                                                {{ person.full_name }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.responsible_person_id" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.responsible_person_id }}
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="status"
                                            v-model="form.status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="active">Ativa</option>
                                            <option value="inactive">Inativa</option>
                                        </select>
                                        <div v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.status }}
                                        </div>
                                    </div>

                                    <!-- Observações -->
                                    <div class="col-span-2">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">
                                            Observações
                                        </label>
                                        <textarea
                                            id="notes"
                                            v-model="form.notes"
                                            rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="flex justify-end space-x-3">
                                <Link
                                    :href="route('families.index')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                >
                                    Salvar Família
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
