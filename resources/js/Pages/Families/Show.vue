<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de visualização de família
 * Módulo Secretaria - Etapa 2
 *
 * Esta página mostra os detalhes de uma família, incluindo:
 * - Dados da família
 * - Responsável principal
 * - Membros da família
 * - Papel familiar de cada pessoa
 * - Idade/faixa etária de cada pessoa
 * - Se é responsável familiar
 * - Ações para editar/remover vínculo
 */

const props = defineProps({
    family: {
        type: Object,
        required: true
    },
    members: {
        type: Array,
        required: true
    },
    people: {
        type: Array,
        required: true
    }
});

// Form para adicionar membro à família
const memberForm = useForm({
    person_id: null,
    role: 'other',
    is_responsible: false,
    joined_at: '',
    notes: ''
});

const addMember = () => {
    memberForm.post(route('families.attachPerson', props.family.id));
};

/**
 * Remove um membro da família com confirmação
 */
const removeMember = (memberId) => {
    if (confirm('Tem certeza que deseja remover esta pessoa da família? Esta ação pode ser desfeita.')) {
        router.delete(route('families.detachPerson', [props.family.id, memberId]));
    }
};

/**
 * Formata o papel familiar para exibição em português
 */
const formatRole = (role) => {
    const roleMap = {
        'father': 'Pai',
        'mother': 'Mãe',
        'son': 'Filho',
        'daughter': 'Filha',
        'spouse': 'Cônjuge',
        'guardian': 'Responsável',
        'relative': 'Familiar',
        'other': 'Outro'
    };
    return roleMap[role] || role;
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
    <Head :title="family.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ family.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <!-- Dados da família -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Dados da Família
                                </h3>
                                <Link
                                    :href="route('families.edit', family.id)"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Editar
                                </Link>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nome:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ family.name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Responsável Principal:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ family.responsible?.full_name || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Descrição:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ family.description || '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span
                                        class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-800': family.status === 'active',
                                            'bg-red-100 text-red-800': family.status === 'inactive'
                                        }"
                                    >
                                        {{ formatStatus(family.status) }}
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Observações:</span>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ family.notes || '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Membros da família -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">
                                Membros da Família ({{ members.length }})
                            </h3>

                            <!-- Form para adicionar membro -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-md">
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Adicionar Pessoa à Família</h4>
                                <form @submit.prevent="addMember" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="person_id" class="block text-sm font-medium text-gray-700">
                                            Pessoa <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="person_id"
                                            v-model="memberForm.person_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Selecione uma pessoa</option>
                                            <option v-for="person in people" :key="person.id" :value="person.id">
                                                {{ person.full_name }}
                                            </option>
                                        </select>
                                        <div v-if="memberForm.errors.person_id" class="mt-1 text-sm text-red-600">
                                            {{ memberForm.errors.person_id }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700">
                                            Papel Familiar <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="role"
                                            v-model="memberForm.role"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="other">Outro</option>
                                            <option value="father">Pai</option>
                                            <option value="mother">Mãe</option>
                                            <option value="son">Filho</option>
                                            <option value="daughter">Filha</option>
                                            <option value="spouse">Cônjuge</option>
                                            <option value="guardian">Responsável</option>
                                            <option value="relative">Familiar</option>
                                        </select>
                                        <div v-if="memberForm.errors.role" class="mt-1 text-sm text-red-600">
                                            {{ memberForm.errors.role }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Marcar como Responsável Familiar
                                        </label>
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input
                                                    v-model="memberForm.is_responsible"
                                                    type="checkbox"
                                                    class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                                />
                                                <span class="ml-2">Sim</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="joined_at" class="block text-sm font-medium text-gray-700">
                                            Data de Entrada
                                        </label>
                                        <input
                                            id="joined_at"
                                            v-model="memberForm.joined_at"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="memberForm.errors.joined_at" class="mt-1 text-sm text-red-600">
                                            {{ memberForm.errors.joined_at }}
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">
                                            Observações
                                        </label>
                                        <textarea
                                            id="notes"
                                            v-model="memberForm.notes"
                                            rows="2"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <div v-if="memberForm.errors.notes" class="mt-1 text-sm text-red-600">
                                            {{ memberForm.errors.notes }}
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <button
                                            type="submit"
                                            :disabled="memberForm.processing"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                        >
                                            Adicionar Pessoa
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Tabela de membros -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Nome
                                            </th>
                                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Papel Familiar
                                            </th>
                                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Idade
                                            </th>
                                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Faixa Etária
                                            </th>
                                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Responsável Familiar
                                            </th>
                                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                                Ações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ member.full_name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">
                                                    {{ formatRole(member.pivot.role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">
                                                    {{ member.age || '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">
                                                    {{ member.age_category }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                    :class="member.pivot.is_responsible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                                >
                                                    {{ member.pivot.is_responsible ? 'Sim' : 'Não' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button
                                                    @click="removeMember(member.pivot.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Remover
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="members.length === 0">
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Nenhuma pessoa vinculada à família.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
