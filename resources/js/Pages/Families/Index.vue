<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de listagem de famílias
 * Módulo Secretaria - Etapa 2
 *
 * Esta página mostra todas as famílias cadastradas no sistema,
 * incluindo dados principais como nome, responsável principal,
 * quantidade de membros e status.
 *
 * A Secretaria pode visualizar, editar e remover famílias daqui.
 */

defineProps({
    families: {
        type: Array,
        required: true
    }
});

/**
 * Remove uma família com confirmação
 * Usa soft delete para manter histórico
 */
const destroy = (id) => {
    if (confirm('Tem certeza que deseja remover esta família? Esta ação pode ser desfeita.')) {
        router.delete(route('families.destroy', id));
    }
};

/**
 * Formata o status da família para exibição em português
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
    <Head title="Famílias" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Famílias
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Listagem de Famílias
                            </h3>
                            <Link
                                :href="route('families.create')"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Nova Família
                            </Link>
                        </div>

                        <!-- Tabela de famílias -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Nome
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Responsável Principal
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Quantidade de Pessoas
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
                                    <tr v-for="family in families" :key="family.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ family.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ family.responsible?.full_name || '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                {{ family.members_count || 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': family.status === 'active',
                                                    'bg-red-100 text-red-800': family.status === 'inactive'
                                                }"
                                            >
                                                {{ formatStatus(family.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('families.show', family.id)"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Visualizar
                                            </Link>
                                            <Link
                                                :href="route('families.edit', family.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="destroy(family.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Remover
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="families.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhuma família cadastrada.
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
