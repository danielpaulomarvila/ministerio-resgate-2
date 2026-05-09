<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de listagem de pessoas
 * Módulo Secretaria - Fase 2.1
 * 
 * Esta página mostra todas as pessoas cadastradas no sistema,
 * incluindo dados principais como nome, idade calculada, status,
 * telefone, email e se é batizada.
 * 
 * A Secretaria pode visualizar, editar e remover pessoas daqui.
 */

defineProps({
    people: {
        type: Array,
        required: true
    }
});

/**
 * Remove uma pessoa com confirmação
 * Usa soft delete para manter histórico
 */
const destroy = (id) => {
    if (confirm('Tem certeza que deseja remover esta pessoa? Esta ação pode ser desfeita.')) {
        router.delete(route('people.destroy', id));
    }
};

/**
 * Formata o status da pessoa para exibição em português
 */
const formatStatus = (status) => {
    const statusMap = {
        'active': 'Ativo',
        'inactive': 'Inativo',
        'visitor': 'Visitante',
        'congregated': 'Congregado',
        'congregant': 'Congregado',
        'discipling': 'Discipulando',
        'new_convert': 'Novo convertido',
        'regularization': 'Em regularização'
    };
    return statusMap[status] || status;
};

/**
 * Formata o gênero para exibição em português
 */
const formatGender = (gender) => {
    if (!gender) return '-';
    const genderMap = {
        'male': 'Masculino',
        'female': 'Feminino',
        'other': 'Outro'
    };
    return genderMap[gender] || gender;
};
</script>

<template>
    <Head title="Pessoas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Pessoas
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Listagem de Pessoas
                            </h3>
                            <Link
                                :href="route('people.create')"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Nova Pessoa
                            </Link>
                        </div>

                        <!-- Tabela de pessoas -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Nome
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Idade
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Telefone
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Cidade
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Batizado
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="person in people" :key="person.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ person.full_name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ person.preferred_name || '-' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ person.age || '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ person.phone || '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ person.email || '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ person.city || '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': person.person_status === 'active',
                                                    'bg-red-100 text-red-800': person.person_status === 'inactive',
                                                    'bg-yellow-100 text-yellow-800': person.person_status === 'visitor',
                                                    'bg-blue-100 text-blue-800': person.person_status === 'congregated' || person.person_status === 'congregant',
                                                    'bg-purple-100 text-purple-800': person.person_status === 'discipling',
                                                    'bg-pink-100 text-pink-800': person.person_status === 'new_convert',
                                                    'bg-orange-100 text-orange-800': person.person_status === 'regularization'
                                                }"
                                            >
                                                {{ formatStatus(person.person_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="person.is_baptized ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                            >
                                                {{ person.is_baptized ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('people.show', person.id)"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Visualizar
                                            </Link>
                                            <Link
                                                :href="route('people.edit', person.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="destroy(person.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Remover
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="people.length === 0">
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhuma pessoa cadastrada.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
