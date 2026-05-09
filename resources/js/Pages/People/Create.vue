<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Página de criação de pessoa
 * Módulo Secretaria - Fase 2.1
 * 
 * Esta página permite que a Secretaria cadastre uma nova pessoa
 * no sistema. Não cria automaticamente usuário ou perfil de membro,
 * pois isso deve ser feito separadamente conforme as regras de idade
 * e batismo.
 */

const form = useForm({
    full_name: '',
    preferred_name: '',
    birth_date: '',
    gender: '',
    email: '',
    phone: '',
    document_number: '',
    is_baptized: false,
    baptism_date: '',
    person_status: 'active',
    notes: ''
});

/**
 * Submete o formulário para criar a pessoa
 */
const submit = () => {
    form.post(route('people.store'));
};
</script>

<template>
    <Head title="Nova Pessoa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Nova Pessoa
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

                        <form @submit.prevent="submit">
                            <!-- Dados Pessoais -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Dados Pessoais
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Nome Completo -->
                                    <div class="col-span-2">
                                        <label for="full_name" class="block text-sm font-medium text-gray-700">
                                            Nome Completo <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="full_name"
                                            v-model="form.full_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        />
                                        <div v-if="form.errors.full_name" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.full_name }}
                                        </div>
                                    </div>

                                    <!-- Nome Preferido -->
                                    <div>
                                        <label for="preferred_name" class="block text-sm font-medium text-gray-700">
                                            Nome Preferido
                                        </label>
                                        <input
                                            id="preferred_name"
                                            v-model="form.preferred_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.preferred_name" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.preferred_name }}
                                        </div>
                                    </div>

                                    <!-- Data de Nascimento -->
                                    <div>
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700">
                                            Data de Nascimento
                                        </label>
                                        <input
                                            id="birth_date"
                                            v-model="form.birth_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.birth_date" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.birth_date }}
                                        </div>
                                    </div>

                                    <!-- Gênero -->
                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700">
                                            Gênero
                                        </label>
                                        <select
                                            id="gender"
                                            v-model="form.gender"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="male">Masculino</option>
                                            <option value="female">Feminino</option>
                                            <option value="other">Outro</option>
                                        </select>
                                        <div v-if="form.errors.gender" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.gender }}
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">
                                            Email
                                        </label>
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.email }}
                                        </div>
                                    </div>

                                    <!-- Telefone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">
                                            Telefone
                                        </label>
                                        <input
                                            id="phone"
                                            v-model="form.phone"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.phone }}
                                        </div>
                                    </div>

                                    <!-- Documento -->
                                    <div>
                                        <label for="document_number" class="block text-sm font-medium text-gray-700">
                                            Documento (CPF/RG)
                                        </label>
                                        <input
                                            id="document_number"
                                            v-model="form.document_number"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.document_number" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.document_number }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Batismo -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Batismo
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- É Batizado -->
                                    <div>
                                        <label for="is_baptized" class="block text-sm font-medium text-gray-700">
                                            É Batizado? <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input
                                                    id="is_baptized"
                                                    v-model="form.is_baptized"
                                                    type="radio"
                                                    :value="true"
                                                    class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                                    required
                                                />
                                                <span class="ml-2">Sim</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input
                                                    v-model="form.is_baptized"
                                                    type="radio"
                                                    :value="false"
                                                    class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                                />
                                                <span class="ml-2">Não</span>
                                            </label>
                                        </div>
                                        <div v-if="form.errors.is_baptized" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.is_baptized }}
                                        </div>
                                    </div>

                                    <!-- Data de Batismo -->
                                    <div v-if="form.is_baptized">
                                        <label for="baptism_date" class="block text-sm font-medium text-gray-700">
                                            Data de Batismo <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="baptism_date"
                                            v-model="form.baptism_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        />
                                        <div v-if="form.errors.baptism_date" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.baptism_date }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Status
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Status da Pessoa -->
                                    <div>
                                        <label for="person_status" class="block text-sm font-medium text-gray-700">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="person_status"
                                            v-model="form.person_status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="active">Ativo</option>
                                            <option value="inactive">Inativo</option>
                                            <option value="visitor">Visitante</option>
                                            <option value="congregated">Congregado</option>
                                        </select>
                                        <div v-if="form.errors.person_status" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.person_status }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Observações
                                </h3>
                                
                                <div>
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

                            <!-- Botões -->
                            <div class="flex justify-end space-x-3">
                                <Link
                                    :href="route('people.index')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                >
                                    Salvar Pessoa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
