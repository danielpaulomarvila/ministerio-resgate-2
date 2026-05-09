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
    marital_status: '',
    education_level: '',
    email: '',
    phone: '',
    secondary_phone: '',
    document_number: '',
    secondary_document: '',
    address: '',
    address_number: '',
    address_complement: '',
    neighborhood: '',
    postal_code: '',
    city: '',
    state: '',
    country: 'Brasil',
    is_baptized: false,
    baptism_date: '',
    conversion_date: '',
    invited_by_person_id: null,
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
                            <!-- A) Dados Pessoais -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    A) Dados Pessoais
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

                                    <!-- Estado Civil -->
                                    <div>
                                        <label for="marital_status" class="block text-sm font-medium text-gray-700">
                                            Estado Civil
                                        </label>
                                        <select
                                            id="marital_status"
                                            v-model="form.marital_status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="single">Solteiro</option>
                                            <option value="married">Casado</option>
                                            <option value="divorced">Divorciado</option>
                                            <option value="widowed">Viúvo</option>
                                            <option value="separated">Separado</option>
                                        </select>
                                        <div v-if="form.errors.marital_status" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.marital_status }}
                                        </div>
                                    </div>

                                    <!-- Nível de Escolaridade -->
                                    <div>
                                        <label for="education_level" class="block text-sm font-medium text-gray-700">
                                            Nível de Escolaridade
                                        </label>
                                        <select
                                            id="education_level"
                                            v-model="form.education_level"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="elementary">Fundamental</option>
                                            <option value="high_school">Médio</option>
                                            <option value="college">Superior</option>
                                            <option value="postgraduate">Pós-graduação</option>
                                            <option value="other">Outro</option>
                                        </select>
                                        <div v-if="form.errors.education_level" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.education_level }}
                                        </div>
                                    </div>

                                    <!-- Documento Principal -->
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

                                    <!-- Documento Secundário -->
                                    <div>
                                        <label for="secondary_document" class="block text-sm font-medium text-gray-700">
                                            Documento Secundário (RG/CNH/etc)
                                        </label>
                                        <input
                                            id="secondary_document"
                                            v-model="form.secondary_document"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.secondary_document" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.secondary_document }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- B) Contatos -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    B) Contatos
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
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

                                    <!-- Telefone Principal -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">
                                            Telefone Principal
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

                                    <!-- Telefone Secundário -->
                                    <div>
                                        <label for="secondary_phone" class="block text-sm font-medium text-gray-700">
                                            Telefone Secundário
                                        </label>
                                        <input
                                            id="secondary_phone"
                                            v-model="form.secondary_phone"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.secondary_phone" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.secondary_phone }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C) Endereço -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    C) Endereço
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Endereço -->
                                    <div class="col-span-2">
                                        <label for="address" class="block text-sm font-medium text-gray-700">
                                            Endereço (Rua/Avenida)
                                        </label>
                                        <input
                                            id="address"
                                            v-model="form.address"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.address }}
                                        </div>
                                    </div>

                                    <!-- Número -->
                                    <div>
                                        <label for="address_number" class="block text-sm font-medium text-gray-700">
                                            Número
                                        </label>
                                        <input
                                            id="address_number"
                                            v-model="form.address_number"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.address_number" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.address_number }}
                                        </div>
                                    </div>

                                    <!-- Complemento -->
                                    <div>
                                        <label for="address_complement" class="block text-sm font-medium text-gray-700">
                                            Complemento
                                        </label>
                                        <input
                                            id="address_complement"
                                            v-model="form.address_complement"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.address_complement" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.address_complement }}
                                        </div>
                                    </div>

                                    <!-- Bairro/Freguesia -->
                                    <div>
                                        <label for="neighborhood" class="block text-sm font-medium text-gray-700">
                                            Bairro/Freguesia
                                        </label>
                                        <input
                                            id="neighborhood"
                                            v-model="form.neighborhood"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.neighborhood" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.neighborhood }}
                                        </div>
                                    </div>

                                    <!-- Código Postal/CEP -->
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">
                                            Código Postal/CEP
                                        </label>
                                        <input
                                            id="postal_code"
                                            v-model="form.postal_code"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.postal_code" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.postal_code }}
                                        </div>
                                    </div>

                                    <!-- Cidade -->
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700">
                                            Cidade
                                        </label>
                                        <input
                                            id="city"
                                            v-model="form.city"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.city" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.city }}
                                        </div>
                                    </div>

                                    <!-- Estado/Distrito -->
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700">
                                            Estado/Distrito
                                        </label>
                                        <input
                                            id="state"
                                            v-model="form.state"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.state" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.state }}
                                        </div>
                                    </div>

                                    <!-- País -->
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700">
                                            País
                                        </label>
                                        <input
                                            id="country"
                                            v-model="form.country"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.country" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.country }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- D) Vida Cristã/Igreja -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    D) Vida Cristã/Igreja
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
                                            Data de Batismo
                                        </label>
                                        <input
                                            id="baptism_date"
                                            v-model="form.baptism_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.baptism_date" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.baptism_date }}
                                        </div>
                                    </div>

                                    <!-- Data de Conversão -->
                                    <div>
                                        <label for="conversion_date" class="block text-sm font-medium text-gray-700">
                                            Data de Conversão
                                        </label>
                                        <input
                                            id="conversion_date"
                                            v-model="form.conversion_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.conversion_date" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.conversion_date }}
                                        </div>
                                    </div>

                                    <!-- Quem Convidou -->
                                    <div>
                                        <label for="invited_by_person_id" class="block text-sm font-medium text-gray-700">
                                            Quem convidou/influenciou/indicou
                                        </label>
                                        <input
                                            id="invited_by_person_id"
                                            v-model="form.invited_by_person_id"
                                            type="text"
                                            placeholder="ID da pessoa (futuro: autocomplete)
"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors.invited_by_person_id" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.invited_by_person_id }}
                                        </div>
                                    </div>

                                    <!-- Status -->
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
                                            <option value="congregant">Congregado</option>
                                            <option value="discipling">Discipulando</option>
                                            <option value="new_convert">Novo convertido</option>
                                            <option value="regularization">Em regularização</option>
                                        </select>
                                        <div v-if="form.errors.person_status" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.person_status }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E) Observações -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    E) Observações
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
