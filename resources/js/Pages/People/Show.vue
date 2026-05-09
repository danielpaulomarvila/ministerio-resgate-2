<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de visualização de pessoa
 * Módulo Secretaria - Fase 2.1
 * 
 * Esta página exibe todos os dados da pessoa, incluindo:
 * - Dados principais (nome, telefone, email, etc.)
 * - Idade calculada
 * - Situação de batismo
 * - Status
 * - Observações
 * - Avisos sobre faixa etária (menor de 11, Júnior, Jovem, Adulto)
 * 
 * Também mostra avisos sobre quando a pessoa poderia futuramente
 * ter usuário ou perfil de membro, conforme as regras de idade e batismo.
 */

const props = defineProps({
    person: {
        type: Object,
        required: true
    },
    ageCategory: {
        type: String,
        default: null
    }
});

/**
 * Formata o status da pessoa para exibição em português
 */
const formatStatus = (status) => {
    const statusMap = {
        'active': 'Ativo',
        'inactive': 'Inativo',
        'visitor': 'Visitante',
        'congregated': 'Congregado'
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

/**
 * Formata a data para exibição
 */
const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('pt-BR');
};

/**
 * Retorna o texto da categoria de idade
 */
const getAgeCategoryText = (category) => {
    const categoryMap = {
        'menor_de_11': 'Menor de 11 anos',
        'junior': 'Júnior (11-13 anos)',
        'jovem': 'Jovem (14-17 anos)',
        'adulto': 'Adulto (18 anos ou mais)'
    };
    return categoryMap[category] || '';
};

/**
 * Retorna a mensagem de aviso sobre usuário
 */
const getUserWarning = (category, isBaptized) => {
    if (category === 'menor_de_11') {
        return 'Menores de 11 anos não podem ter usuário.';
    }
    if (category === 'junior' || category === 'jovem') {
        return 'Pode ter usuário, mas requer supervisão dos pais/responsáveis.';
    }
    if (category === 'adulto') {
        return 'Pode ter usuário independente de membresia.';
    }
    return '';
};

/**
 * Retorna a mensagem de aviso sobre perfil de membro
 */
const getMemberProfileWarning = (category, isBaptized) => {
    if (!isBaptized) {
        return 'Não pode ser membro pois não é batizado.';
    }
    if (category === 'menor_de_11') {
        return 'Pode ser membro se for batizado, mas não terá usuário.';
    }
    if (category === 'junior' || category === 'jovem') {
        return 'Pode ser membro se for batizado.';
    }
    if (category === 'adulto') {
        return 'Pode ser membro se for batizado.';
    }
    return '';
};
</script>

<template>
    <Head :title="person.full_name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalhes da Pessoa
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <Link
                                :href="route('people.index')"
                                class="text-sm font-medium text-blue-600 hover:text-blue-500"
                            >
                                &larr; Voltar para lista
                            </Link>
                        </div>

                        <!-- Aviso de Categoria de Idade -->
                        <div v-if="ageCategory" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">
                                Categoria de Idade
                            </h4>
                            <p class="text-sm text-blue-700">
                                {{ getAgeCategoryText(ageCategory) }}
                            </p>
                        </div>

                        <!-- Dados Principais -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Dados Principais
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nome Completo:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.full_name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nome Preferido:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.preferred_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Idade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.age || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Data de Nascimento:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(person.birth_date) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Gênero:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatGender(person.gender) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.email || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telefone:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.phone || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Documento:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.document_number || '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Batismo -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Batismo
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">É Batizado:</span>
                                    <p class="mt-1">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="person.is_baptized ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                        >
                                            {{ person.is_baptized ? 'Sim' : 'Não' }}
                                        </span>
                                    </p>
                                </div>
                                <div v-if="person.is_baptized">
                                    <span class="text-sm font-medium text-gray-500">Data de Batismo:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(person.baptism_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Status
                            </h3>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Status da Pessoa:</span>
                                <p class="mt-1">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-800': person.person_status === 'active',
                                            'bg-red-100 text-red-800': person.person_status === 'inactive',
                                            'bg-yellow-100 text-yellow-800': person.person_status === 'visitor',
                                            'bg-blue-100 text-blue-800': person.person_status === 'congregated'
                                        }"
                                    >
                                        {{ formatStatus(person.person_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Observações
                            </h3>
                            
                            <div>
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ person.notes || '-' }}</p>
                            </div>
                        </div>

                        <!-- Avisos sobre Usuário e Membro -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Informações Adicionais
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Aviso sobre Usuário -->
                                <div v-if="ageCategory" class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <h4 class="text-sm font-medium text-yellow-900 mb-2">
                                        Acesso ao Sistema (Usuário)
                                    </h4>
                                    <p class="text-sm text-yellow-700">
                                        {{ getUserWarning(ageCategory, person.is_baptized) }}
                                    </p>
                                </div>

                                <!-- Aviso sobre Perfil de Membro -->
                                <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                                    <h4 class="text-sm font-medium text-green-900 mb-2">
                                        Perfil de Membro
                                    </h4>
                                    <p class="text-sm text-green-700">
                                        {{ getMemberProfileWarning(ageCategory, person.is_baptized) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('people.edit', person.id)"
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
