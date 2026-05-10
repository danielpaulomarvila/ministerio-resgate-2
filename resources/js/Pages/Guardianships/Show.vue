<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de visualização de responsabilidade
 * Módulo Secretaria - Etapa 3
 *
 * Esta página mostra os detalhes de uma responsabilidade, incluindo:
 * - Dados do menor
 * - Dados do responsável
 * - Relação
 * - Permissões/autorizações
 * - Status e datas
 * - Avisos importantes por idade
 */

defineProps({
    guardianship: {
        type: Object,
        required: true
    }
});

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
    <Head title="Responsabilidade" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Responsabilidade
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <!-- Dados do Menor -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">
                                Dados do Menor
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nome:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.minor.full_name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Idade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.minor.age || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Faixa Etária:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.minor.age_group_label || '-' }}</p>
                                </div>
                            </div>

                            <!-- Avisos por idade -->
                            <div v-if="guardianship.minor.is_under_11" class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <h4 class="text-sm font-medium text-yellow-800 mb-2">⚠️ Aviso Importante</h4>
                                <p class="text-sm text-yellow-700">
                                    Este menor não deve ter utilizador próprio. As ações financeiras futuras devem ser vinculadas ao responsável financeiro.
                                </p>
                            </div>
                            <div v-else-if="guardianship.minor.is_junior" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">ℹ️ Informação</h4>
                                <p class="text-sm text-blue-700">
                                    Esta pessoa se enquadra como Júnior pela idade. Poderá ter utilizador futuramente, com supervisão dos responsáveis.
                                </p>
                            </div>
                            <div v-else-if="guardianship.minor.is_young" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-md">
                                <h4 class="text-sm font-medium text-green-800 mb-2">ℹ️ Informação</h4>
                                <p class="text-sm text-green-700">
                                    Esta pessoa se enquadra como Jovem pela idade. Poderá ter utilizador futuramente.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Responsável -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">
                                Dados do Responsável
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nome:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.guardian.full_name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Idade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.guardian.age || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Relação:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatRelationship(guardianship.relationship_type) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissões e Autorizações -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">
                                Permissões e Autorizações
                            </h3>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="flex items-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-3"
                                        :class="guardianship.is_legal_guardian ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.is_legal_guardian ? 'Sim' : 'Não' }}
                                    </span>
                                    <span class="text-sm text-gray-900">Responsável Legal</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-3"
                                        :class="guardianship.is_financial_responsible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.is_financial_responsible ? 'Sim' : 'Não' }}
                                    </span>
                                    <span class="text-sm text-gray-900">Responsável Financeiro</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-3"
                                        :class="guardianship.can_authorize_login ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.can_authorize_login ? 'Sim' : 'Não' }}
                                    </span>
                                    <span class="text-sm text-gray-900">Pode Autorizar Login</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-3"
                                        :class="guardianship.can_approve_changes ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.can_approve_changes ? 'Sim' : 'Não' }}
                                    </span>
                                    <span class="text-sm text-gray-900">Pode Aprovar Alterações</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-3"
                                        :class="guardianship.can_view_financial ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.can_view_financial ? 'Sim' : 'Não' }}
                                    </span>
                                    <span class="text-sm text-gray-900">Pode Ver Financeiro</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-3"
                                        :class="guardianship.can_receive_canteen_debts ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.can_receive_canteen_debts ? 'Sim' : 'Não' }}
                                    </span>
                                    <span class="text-sm text-gray-900">Recebe Dívidas da Cantina</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Período e Status -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">
                                Período e Status
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Data de Início:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.starts_at || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Data de Fim:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ guardianship.ends_at || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span
                                        class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-800': guardianship.status === 'active',
                                            'bg-red-100 text-red-800': guardianship.status === 'inactive',
                                            'bg-gray-100 text-gray-800': guardianship.status === 'ended'
                                        }"
                                    >
                                        {{ formatStatus(guardianship.status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Situação Atual:</span>
                                    <span
                                        class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="guardianship.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ guardianship.is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Observações:</span>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ guardianship.notes || '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-between items-center">
                        <Link
                            :href="route('guardianships.index')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Voltar
                        </Link>
                        <div class="space-x-3">
                            <Link
                                :href="route('guardianships.edit', guardianship.id)"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
