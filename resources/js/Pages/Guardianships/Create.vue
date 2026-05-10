<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de criação de responsabilidade
 * Módulo Secretaria - Etapa 3
 *
 * Esta página permite criar uma nova responsabilidade sobre menor no sistema.
 * A Secretaria pode definir menor, responsável, relação, permissões, status e observações.
 */

const props = defineProps({
    people: {
        type: Array,
        required: true
    }
});

const form = useForm({
    minor_person_id: null,
    guardian_person_id: null,
    relationship_type: 'other',
    is_legal_guardian: false,
    is_financial_responsible: false,
    can_authorize_login: false,
    can_approve_changes: false,
    can_view_financial: false,
    can_receive_canteen_debts: false,
    starts_at: '',
    ends_at: '',
    status: 'active',
    notes: ''
});

const submit = () => {
    form.post(route('guardianships.store'));
};

/**
 * Formata o tipo de relação para exibição em português
 */
const formatRelationship = (relationship) => {
    const relationshipMap = {
        'other': 'Outro',
        'father': 'Pai',
        'mother': 'Mãe',
        'grandfather': 'Avô',
        'grandmother': 'Avó',
        'uncle': 'Tio',
        'aunt': 'Tia',
        'brother': 'Irmão',
        'sister': 'Irmã',
        'legal_guardian': 'Responsável legal',
        'tutor': 'Tutor'
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

/**
 * Filtra pessoas menores de 18 anos
 */
const minors = computed(() => {
    return props.people.filter(p => p.age !== null && p.age < 18);
});

/**
 * Filtra pessoas adultas (18+)
 */
const adults = computed(() => {
    return props.people.filter(p => p.age !== null && p.age >= 18);
});
</script>

<template>
    <Head title="Nova Responsabilidade" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Nova Responsabilidade
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">
                            Dados da Responsabilidade
                        </h3>

                        <form @submit.prevent="submit">
                            <!-- Bloco Menor -->
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Menor</h4>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="minor_person_id" class="block text-sm font-medium text-gray-700">
                                            Pessoa Menor <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="minor_person_id"
                                            v-model="form.minor_person_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Selecione uma pessoa menor (menos de 18 anos)</option>
                                            <option v-for="person in minors" :key="person.id" :value="person.id">
                                                {{ person.full_name }} - {{ person.age_group_label }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.minor_person_id" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.minor_person_id }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloco Responsável -->
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Responsável</h4>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="guardian_person_id" class="block text-sm font-medium text-gray-700">
                                            Responsável <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="guardian_person_id"
                                            v-model="form.guardian_person_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Selecione um responsável adulto (18 anos ou mais)</option>
                                            <option v-for="person in adults" :key="person.id" :value="person.id">
                                                {{ person.full_name }} - {{ person.age_group_label }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.guardian_person_id" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.guardian_person_id }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="relationship_type" class="block text-sm font-medium text-gray-700">
                                            Relação com o Menor <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="relationship_type"
                                            v-model="form.relationship_type"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="other">Outro</option>
                                            <option value="father">Pai</option>
                                            <option value="mother">Mãe</option>
                                            <option value="grandfather">Avô</option>
                                            <option value="grandmother">Avó</option>
                                            <option value="uncle">Tio</option>
                                            <option value="aunt">Tia</option>
                                            <option value="brother">Irmão</option>
                                            <option value="sister">Irmã</option>
                                            <option value="legal_guardian">Responsável legal</option>
                                            <option value="tutor">Tutor</option>
                                        </select>
                                        <div v-if="form.errors.relationship_type" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.relationship_type }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloco Autorizações -->
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Autorizações e Permissões</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input
                                                v-model="form.is_legal_guardian"
                                                type="checkbox"
                                                class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-gray-700">Responsável Legal</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500 ml-6">Autoridade legal sobre o menor</p>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input
                                                v-model="form.is_financial_responsible"
                                                type="checkbox"
                                                class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-gray-700">Responsável Financeiro</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500 ml-6">Paga pelas compras futuras da cantina</p>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input
                                                v-model="form.can_authorize_login"
                                                type="checkbox"
                                                class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-gray-700">Pode Autorizar Login</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500 ml-6">Autoriza criação de usuário para Júnior</p>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input
                                                v-model="form.can_approve_changes"
                                                type="checkbox"
                                                class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-gray-700">Pode Aprovar Alterações</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500 ml-6">Pode aprovar mudanças no cadastro</p>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input
                                                v-model="form.can_view_financial"
                                                type="checkbox"
                                                class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-gray-700">Pode Ver Financeiro</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500 ml-6">Acesso a dados financeiros futuros</p>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input
                                                v-model="form.can_receive_canteen_debts"
                                                type="checkbox"
                                                class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-sm text-gray-700">Recebe Dívidas da Cantina</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500 ml-6">Recebe cobranças de compras futuras</p>
                                    </div>
                                </div>
                                <div v-if="form.errors.is_legal_guardian" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.is_legal_guardian }}
                                </div>
                                <div v-if="form.errors.is_financial_responsible" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.is_financial_responsible }}
                                </div>
                                <div v-if="form.errors.can_receive_canteen_debts" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.can_receive_canteen_debts }}
                                </div>
                            </div>

                            <!-- Bloco Período -->
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Período de Responsabilidade</h4>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="starts_at" class="block text-sm font-medium text-gray-700">
                                            Data de Início
                                        </label>
                                        <input
                                            id="starts_at"
                                            v-model="form.starts_at"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.starts_at" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.starts_at }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="ends_at" class="block text-sm font-medium text-gray-700">
                                            Data de Fim
                                        </label>
                                        <input
                                            id="ends_at"
                                            v-model="form.ends_at"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.ends_at" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.ends_at }}
                                        </div>
                                    </div>
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
                                            <option value="active">Ativo</option>
                                            <option value="inactive">Inativo</option>
                                            <option value="ended">Encerrado</option>
                                        </select>
                                        <div v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.status }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloco Observações -->
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Observações</h4>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">
                                        Notas
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

                            <!-- Botões -->
                            <div class="flex justify-end space-x-3">
                                <Link
                                    :href="route('guardianships.index')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                >
                                    Salvar Responsabilidade
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
