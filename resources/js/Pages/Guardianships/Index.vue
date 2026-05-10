<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de listagem de responsáveis e supervisores
 * Módulo Secretaria - Etapa 3
 *
 * Esta página mostra todas as responsabilidades cadastradas no sistema,
 * incluindo dados principais como menor, responsável, relação,
 * permissões e status.
 *
 * A Secretaria pode visualizar, editar e remover responsabilidades daqui.
 */

defineProps({
    guardianships: {
        type: Array,
        required: true
    }
});

/**
 * Remove uma responsabilidade com confirmação
 * Usa soft delete mantendo histórico com status 'ended'
 */
const destroy = (id) => {
    if (confirm('Tem certeza que deseja encerrar esta responsabilidade? Esta ação pode ser desfeita.')) {
        router.delete(route('guardianships.destroy', id));
    }
};

/**
 * Formata o tipo de relação para exibição em português
 */
const formatRelationship = (relationship) => {
    const relationshipMap = {
        'father': 'Pai',
        'mother': 'Mãe',
        'grandfather': 'Avô',
        'grandmother': 'Avó',
        'uncle': 'Tio',
        'aunt': 'Tia',
        'brother': 'Irmão',
        'sister': 'Irmã',
        'legal_guardian': 'Responsável legal',
        'tutor': 'Tutor',
        'other': 'Outro'
    };
    return relationshipMap[relationship] || relationship;
};

/**
 * Formata o status para exibição em português
 */
const formatStatus = (status) => {
    const statusMap = {
        'active': 'Ativo',
        'inactive': 'Inativo',
        'ended': 'Encerrado'
    };
    return statusMap[status] || status;
};
</script>

<template>
    <Head title="Responsáveis" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Responsáveis e Supervisores
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Listagem de Responsabilidades
                            </h3>
                            <Link
                                :href="route('guardianships.create')"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Nova Responsabilidade
                            </Link>
                        </div>

                        <!-- Tabela de responsabilidades -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Menor
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Idade
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Responsável
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Relação
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Resp. Legal
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Resp. Financeiro
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Autoriza Login
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="guardianship in guardianships" :key="guardianship.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ guardianship.minor.full_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ guardianship.minor.age || '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ guardianship.minor.age_group_label || '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ guardianship.guardian.full_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ formatRelationship(guardianship.relationship_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="guardianship.is_legal_guardian ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                            >
                                                {{ guardianship.is_legal_guardian ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="guardianship.is_financial_responsible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                            >
                                                {{ guardianship.is_financial_responsible ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="guardianship.can_authorize_login ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                            >
                                                {{ guardianship.can_authorize_login ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': guardianship.status === 'active',
                                                    'bg-red-100 text-red-800': guardianship.status === 'inactive',
                                                    'bg-gray-100 text-gray-800': guardianship.status === 'ended'
                                                }"
                                            >
                                                {{ formatStatus(guardianship.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('guardianships.show', guardianship.id)"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Visualizar
                                            </Link>
                                            <Link
                                                :href="route('guardianships.edit', guardianship.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="destroy(guardianship.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Encerrar
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="guardianships.length === 0">
                                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhuma responsabilidade cadastrada.
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
