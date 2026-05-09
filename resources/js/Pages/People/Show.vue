<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de visualização de pessoa
 * Módulo Secretaria - Fase 2.1
 * 
 * Esta página exibe todos os dados da pessoa, incluindo:
 * - Dados principais (nome, telefone, email, etc.)
 * - Documentos (NIF, Cartão de Cidadão, Passaporte, etc.)
 * - Morada (com estrutura portuguesa)
 * - Idade calculada
 * - Situação de batismo
 * - Status
 * - Observações
 * - Avisos sobre faixa etária (menor de 11, Júnior, Jovem, Adulto)
 * 
 * Documentos e moradas foram separados em tabelas próprias
 * para deixar a tabela principal mais limpa e organizada.
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

/**
 * Formata o estado civil para exibição em português
 */
const formatMaritalStatus = (status) => {
    if (!status) return '-';
    const statusMap = {
        'single': 'Solteiro',
        'married': 'Casado',
        'divorced': 'Divorciado',
        'widowed': 'Viúvo',
        'separated': 'Separado'
    };
    return statusMap[status] || status;
};

/**
 * Formata o nível de escolaridade para exibição em português
 */
const formatEducationLevel = (level) => {
    if (!level) return '-';
    const levelMap = {
        'elementary': 'Fundamental',
        'high_school': 'Médio',
        'college': 'Superior',
        'postgraduate': 'Pós-graduação',
        'other': 'Outro'
    };
    return levelMap[level] || level;
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

                        <!-- A) Dados Pessoais -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                A) Dados Pessoais
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
                                    <span class="text-sm font-medium text-gray-500">Apelido/Sobrenome:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.last_name || '-' }}</p>
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
                                    <span class="text-sm font-medium text-gray-500">Estado Civil:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatMaritalStatus(person.marital_status) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nível de Escolaridade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatEducationLevel(person.education_level) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nacionalidade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.nationality || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Naturalidade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.birthplace || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Profissão:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.profession || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Ocupação:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.occupation || '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- B) Contactos -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                B) Contactos
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.email || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telemóvel Principal:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primary_phone || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telemóvel Secundário:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.secondary_phone || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">WhatsApp:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.whatsapp || '-' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Notas de Contacto:</span>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ person.contact_notes || '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- C) Documentos -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                C) Documentos
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">NIF:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.document?.nif || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Cartão de Cidadão:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.document?.citizen_card_number || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Passaporte:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.document?.passport_number || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Título de Residência:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.document?.residence_permit_number || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Outro Documento:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.document?.other_document || '-' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Notas sobre Documentos:</span>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ person.document?.document_notes || '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- D) Morada -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                D) Morada
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">País:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.country_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Distrito:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.district_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Concelho/Município:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.municipality_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Freguesia:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.parish_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Localidade:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.locality_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Localidade (Manual):</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.locality_manual || '-' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Rua/Avenida:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.address_line || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Número da Porta:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.door_number || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Andar/Fração:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.floor_or_unit || '-' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Complemento:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.address_complement || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Código Postal:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.primaryAddress?.postal_code || '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- E) Vida Cristã/Igreja -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                E) Vida Cristã/Igreja
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
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Data de Conversão:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(person.conversion_date) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Quem convidou (ID):</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ person.invited_by_person_id || '-' }}</p>
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
                                            'bg-blue-100 text-blue-800': person.person_status === 'congregated' || person.person_status === 'congregant',
                                            'bg-purple-100 text-purple-800': person.person_status === 'discipling',
                                            'bg-pink-100 text-pink-800': person.person_status === 'new_convert',
                                            'bg-orange-100 text-orange-800': person.person_status === 'regularization'
                                        }"
                                    >
                                        {{ formatStatus(person.person_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- F) Observações -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                F) Observações
                            </h3>
                            
                            <div>
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ person.general_notes || '-' }}</p>
                            </div>
                        </div>

                        <!-- Avisos sobre Elegibilidade -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Avisos sobre Elegibilidade Futura
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

                                <!-- Aviso sobre Departamento Resgatados -->
                                <div v-if="ageCategory === 'junior' || ageCategory === 'jovem'" class="p-4 bg-purple-50 border border-purple-200 rounded-md">
                                    <h4 class="text-sm font-medium text-purple-900 mb-2">
                                        Departamento Resgatados
                                    </h4>
                                    <p class="text-sm text-purple-700">
                                        {{ ageCategory === 'junior' ? 'Júnior (11-13 anos): Pode participar do departamento Resgatados.' : 'Jovem (14-17 anos): Pode participar do departamento Resgatados.' }}
                                    </p>
                                </div>

                                <!-- Aviso sobre Alerta de 11 Anos -->
                                <div v-if="ageCategory === 'menor_de_11'" class="p-4 bg-red-50 border border-red-200 rounded-md">
                                    <h4 class="text-sm font-medium text-red-900 mb-2">
                                        Alerta Importante
                                    </h4>
                                    <p class="text-sm text-red-700">
                                        Menor de 11 anos: Quando completar 11 anos, poderá ter usuário e participar do departamento Resgatados.
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
