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
 * 
 * Documentos e moradas foram separados em tabelas próprias
 * para deixar a tabela principal mais limpa e organizada.
 */

const form = useForm({
    person: {
        full_name: '',
        preferred_name: '',
        last_name: '',
        birth_date: '',
        gender: '',
        marital_status: '',
        education_level: '',
        nationality: '',
        birthplace: '',
        profession: '',
        occupation: '',
        primary_phone: '',
        secondary_phone: '',
        whatsapp: '',
        email: '',
        contact_notes: '',
        photo_path: '',
        is_baptized: false,
        baptism_date: '',
        conversion_date: '',
        invited_by_person_id: null,
        person_status: 'active',
        general_notes: ''
    },
    document: {
        nif: '',
        residence_permit_number: '',
        other_document: '',
        document_notes: ''
    },
    address: {
        country_name: 'Portugal',
        district_name: '',
        municipality_name: '',
        parish_name: '',
        locality_name: '',
        address_line: '',
        door_number: '',
        floor_or_unit: '',
        address_complement: '',
        postal_code: '',
        full_address: ''
    }
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
                                        <label for="person.full_name" class="block text-sm font-medium text-gray-700">
                                            Nome Completo <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="person.full_name"
                                            v-model="form.person.full_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        />
                                        <div v-if="form.errors['person.full_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.full_name'] }}
                                        </div>
                                    </div>

                                    <!-- Nome Preferido -->
                                    <div>
                                        <label for="person.preferred_name" class="block text-sm font-medium text-gray-700">
                                            Nome Preferido
                                        </label>
                                        <input
                                            id="person.preferred_name"
                                            v-model="form.person.preferred_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.preferred_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.preferred_name'] }}
                                        </div>
                                    </div>

                                    <!-- Apelido/Sobrenome -->
                                    <div>
                                        <label for="person.last_name" class="block text-sm font-medium text-gray-700">
                                            Apelido/Sobrenome
                                        </label>
                                        <input
                                            id="person.last_name"
                                            v-model="form.person.last_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.last_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.last_name'] }}
                                        </div>
                                    </div>

                                    <!-- Data de Nascimento -->
                                    <div>
                                        <label for="person.birth_date" class="block text-sm font-medium text-gray-700">
                                            Data de Nascimento
                                        </label>
                                        <input
                                            id="person.birth_date"
                                            v-model="form.person.birth_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.birth_date']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.birth_date'] }}
                                        </div>
                                    </div>

                                    <!-- Gênero -->
                                    <div>
                                        <label for="person.gender" class="block text-sm font-medium text-gray-700">
                                            Gênero
                                        </label>
                                        <select
                                            id="person.gender"
                                            v-model="form.person.gender"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="male">Masculino</option>
                                            <option value="female">Feminino</option>
                                            <option value="other">Outro</option>
                                        </select>
                                        <div v-if="form.errors['person.gender']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.gender'] }}
                                        </div>
                                    </div>

                                    <!-- Estado Civil -->
                                    <div>
                                        <label for="person.marital_status" class="block text-sm font-medium text-gray-700">
                                            Estado Civil
                                        </label>
                                        <select
                                            id="person.marital_status"
                                            v-model="form.person.marital_status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="single">Solteiro</option>
                                            <option value="married">Casado</option>
                                            <option value="divorced">Divorciado</option>
                                            <option value="widowed">Viúvo</option>
                                            <option value="separated">Separado</option>
                                        </select>
                                        <div v-if="form.errors['person.marital_status']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.marital_status'] }}
                                        </div>
                                    </div>

                                    <!-- Nível de Escolaridade -->
                                    <div>
                                        <label for="person.education_level" class="block text-sm font-medium text-gray-700">
                                            Nível de Escolaridade
                                        </label>
                                        <select
                                            id="person.education_level"
                                            v-model="form.person.education_level"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="elementary">Fundamental</option>
                                            <option value="high_school">Médio</option>
                                            <option value="college">Superior</option>
                                            <option value="postgraduate">Pós-graduação</option>
                                            <option value="other">Outro</option>
                                        </select>
                                        <div v-if="form.errors['person.education_level']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.education_level'] }}
                                        </div>
                                    </div>

                                    <!-- Nacionalidade -->
                                    <div>
                                        <label for="person.nationality" class="block text-sm font-medium text-gray-700">
                                            Nacionalidade
                                        </label>
                                        <input
                                            id="person.nationality"
                                            v-model="form.person.nationality"
                                            type="text"
                                            placeholder="Ex.: Portuguesa"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.nationality']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.nationality'] }}
                                        </div>
                                    </div>

                                    <!-- Naturalidade -->
                                    <div>
                                        <label for="person.birthplace" class="block text-sm font-medium text-gray-700">
                                            Naturalidade
                                        </label>
                                        <input
                                            id="person.birthplace"
                                            v-model="form.person.birthplace"
                                            type="text"
                                            placeholder="Ex.: Lisboa"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.birthplace']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.birthplace'] }}
                                        </div>
                                    </div>

                                    <!-- Profissão -->
                                    <div>
                                        <label for="person.profession" class="block text-sm font-medium text-gray-700">
                                            Profissão
                                        </label>
                                        <input
                                            id="person.profession"
                                            v-model="form.person.profession"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.profession']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.profession'] }}
                                        </div>
                                    </div>

                                    <!-- Ocupação -->
                                    <div>
                                        <label for="person.occupation" class="block text-sm font-medium text-gray-700">
                                            Ocupação
                                        </label>
                                        <input
                                            id="person.occupation"
                                            v-model="form.person.occupation"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.occupation']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.occupation'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- B) Contactos -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    B) Contactos
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Email -->
                                    <div>
                                        <label for="person.email" class="block text-sm font-medium text-gray-700">
                                            Email
                                        </label>
                                        <input
                                            id="person.email"
                                            v-model="form.person.email"
                                            type="email"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.email']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.email'] }}
                                        </div>
                                    </div>

                                    <!-- Telemóvel Principal -->
                                    <div>
                                        <label for="person.primary_phone" class="block text-sm font-medium text-gray-700">
                                            Telemóvel Principal
                                        </label>
                                        <input
                                            id="person.primary_phone"
                                            v-model="form.person.primary_phone"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.primary_phone']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.primary_phone'] }}
                                        </div>
                                    </div>

                                    <!-- Telemóvel Secundário -->
                                    <div>
                                        <label for="person.secondary_phone" class="block text-sm font-medium text-gray-700">
                                            Telemóvel Secundário
                                        </label>
                                        <input
                                            id="person.secondary_phone"
                                            v-model="form.person.secondary_phone"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.secondary_phone']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.secondary_phone'] }}
                                        </div>
                                    </div>

                                    <!-- WhatsApp -->
                                    <div>
                                        <label for="person.whatsapp" class="block text-sm font-medium text-gray-700">
                                            WhatsApp
                                        </label>
                                        <input
                                            id="person.whatsapp"
                                            v-model="form.person.whatsapp"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.whatsapp']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.whatsapp'] }}
                                        </div>
                                    </div>

                                    <!-- Notas de Contacto -->
                                    <div class="col-span-2">
                                        <label for="person.contact_notes" class="block text-sm font-medium text-gray-700">
                                            Notas de Contacto
                                        </label>
                                        <textarea
                                            id="person.contact_notes"
                                            v-model="form.person.contact_notes"
                                            rows="2"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <div v-if="form.errors['person.contact_notes']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.contact_notes'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C) Documentos -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    C) Documentos
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- NIF -->
                                    <div>
                                        <label for="document.nif" class="block text-sm font-medium text-gray-700">
                                            NIF (Número de Identificação Fiscal)
                                        </label>
                                        <input
                                            id="document.nif"
                                            v-model="form.document.nif"
                                            type="text"
                                            placeholder="Ex.: 123456789"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['document.nif']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['document.nif'] }}
                                        </div>
                                    </div>


                                    <!-- Título de Residência -->
                                    <div>
                                        <label for="document.residence_permit_number" class="block text-sm font-medium text-gray-700">
                                            Título de Residência
                                        </label>
                                        <input
                                            id="document.residence_permit_number"
                                            v-model="form.document.residence_permit_number"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['document.residence_permit_number']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['document.residence_permit_number'] }}
                                        </div>
                                    </div>

                                    <!-- Outro Documento -->
                                    <div>
                                        <label for="document.other_document" class="block text-sm font-medium text-gray-700">
                                            Outro Documento
                                        </label>
                                        <input
                                            id="document.other_document"
                                            v-model="form.document.other_document"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['document.other_document']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['document.other_document'] }}
                                        </div>
                                    </div>

                                    <!-- Notas sobre Documentos -->
                                    <div class="col-span-2">
                                        <label for="document.document_notes" class="block text-sm font-medium text-gray-700">
                                            Notas sobre Documentos
                                        </label>
                                        <textarea
                                            id="document.document_notes"
                                            v-model="form.document.document_notes"
                                            rows="2"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <div v-if="form.errors['document.document_notes']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['document.document_notes'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- D) Morada -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    D) Morada
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- País -->
                                    <div>
                                        <label for="address.country_name" class="block text-sm font-medium text-gray-700">
                                            País
                                        </label>
                                        <input
                                            id="address.country_name"
                                            v-model="form.address.country_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.country_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.country_name'] }}
                                        </div>
                                    </div>

                                    <!-- Distrito -->
                                    <div>
                                        <label for="address.district_name" class="block text-sm font-medium text-gray-700">
                                            Distrito
                                        </label>
                                        <input
                                            id="address.district_name"
                                            v-model="form.address.district_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.district_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.district_name'] }}
                                        </div>
                                    </div>

                                    <!-- Concelho/Município -->
                                    <div>
                                        <label for="address.municipality_name" class="block text-sm font-medium text-gray-700">
                                            Concelho/Município
                                        </label>
                                        <input
                                            id="address.municipality_name"
                                            v-model="form.address.municipality_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.municipality_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.municipality_name'] }}
                                        </div>
                                    </div>

                                    <!-- Freguesia -->
                                    <div>
                                        <label for="address.parish_name" class="block text-sm font-medium text-gray-700">
                                            Freguesia
                                        </label>
                                        <input
                                            id="address.parish_name"
                                            v-model="form.address.parish_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.parish_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.parish_name'] }}
                                        </div>
                                    </div>

                                    <!-- Localidade -->
                                    <div>
                                        <label for="address.locality_name" class="block text-sm font-medium text-gray-700">
                                            Localidade
                                        </label>
                                        <input
                                            id="address.locality_name"
                                            v-model="form.address.locality_name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.locality_name']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.locality_name'] }}
                                        </div>
                                    </div>


                                    <!-- Rua/Avenida -->
                                    <div class="col-span-2">
                                        <label for="address.address_line" class="block text-sm font-medium text-gray-700">
                                            Rua/Avenida
                                        </label>
                                        <input
                                            id="address.address_line"
                                            v-model="form.address.address_line"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.address_line']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.address_line'] }}
                                        </div>
                                    </div>

                                    <!-- Número da Porta -->
                                    <div>
                                        <label for="address.door_number" class="block text-sm font-medium text-gray-700">
                                            Número da Porta
                                        </label>
                                        <input
                                            id="address.door_number"
                                            v-model="form.address.door_number"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.door_number']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.door_number'] }}
                                        </div>
                                    </div>

                                    <!-- Andar/Fração -->
                                    <div>
                                        <label for="address.floor_or_unit" class="block text-sm font-medium text-gray-700">
                                            Andar/Fração
                                        </label>
                                        <input
                                            id="address.floor_or_unit"
                                            v-model="form.address.floor_or_unit"
                                            type="text"
                                            placeholder="Ex.: 1º Esq."
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.floor_or_unit']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.floor_or_unit'] }}
                                        </div>
                                    </div>

                                    <!-- Complemento -->
                                    <div class="col-span-2">
                                        <label for="address.address_complement" class="block text-sm font-medium text-gray-700">
                                            Complemento
                                        </label>
                                        <input
                                            id="address.address_complement"
                                            v-model="form.address.address_complement"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.address_complement']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.address_complement'] }}
                                        </div>
                                    </div>

                                    <!-- Código Postal -->
                                    <div>
                                        <label for="address.postal_code" class="block text-sm font-medium text-gray-700">
                                            Código Postal (0000-000)
                                        </label>
                                        <input
                                            id="address.postal_code"
                                            v-model="form.address.postal_code"
                                            type="text"
                                            placeholder="Ex.: 1000-001"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['address.postal_code']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['address.postal_code'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E) Vida Cristã/Igreja -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    E) Vida Cristã/Igreja
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- É Batizado -->
                                    <div>
                                        <label for="person.is_baptized" class="block text-sm font-medium text-gray-700">
                                            É Batizado? <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input
                                                    id="person.is_baptized"
                                                    v-model="form.person.is_baptized"
                                                    type="radio"
                                                    :value="true"
                                                    class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                                    required
                                                />
                                                <span class="ml-2">Sim</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input
                                                    v-model="form.person.is_baptized"
                                                    type="radio"
                                                    :value="false"
                                                    class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                                />
                                                <span class="ml-2">Não</span>
                                            </label>
                                        </div>
                                        <div v-if="form.errors['person.is_baptized']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.is_baptized'] }}
                                        </div>
                                    </div>

                                    <!-- Data de Batismo -->
                                    <div v-if="form.person.is_baptized">
                                        <label for="person.baptism_date" class="block text-sm font-medium text-gray-700">
                                            Data de Batismo
                                        </label>
                                        <input
                                            id="person.baptism_date"
                                            v-model="form.person.baptism_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.baptism_date']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.baptism_date'] }}
                                        </div>
                                    </div>

                                    <!-- Data de Conversão -->
                                    <div>
                                        <label for="person.conversion_date" class="block text-sm font-medium text-gray-700">
                                            Data de Conversão
                                        </label>
                                        <input
                                            id="person.conversion_date"
                                            v-model="form.person.conversion_date"
                                            type="date"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.conversion_date']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.conversion_date'] }}
                                        </div>
                                    </div>

                                    <!-- Quem Convidou -->
                                    <div>
                                        <label for="person.invited_by_person_id" class="block text-sm font-medium text-gray-700">
                                            Quem convidou/influenciou/indicou
                                        </label>
                                        <input
                                            id="person.invited_by_person_id"
                                            v-model="form.person.invited_by_person_id"
                                            type="text"
                                            placeholder="ID da pessoa (futuro: autocomplete)"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <div v-if="form.errors['person.invited_by_person_id']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.invited_by_person_id'] }}
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="person.person_status" class="block text-sm font-medium text-gray-700">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="person.person_status"
                                            v-model="form.person.person_status"
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
                                        <div v-if="form.errors['person.person_status']" class="mt-1 text-sm text-red-600">
                                            {{ form.errors['person.person_status'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- F) Observações -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    F) Observações
                                </h3>
                                
                                <div>
                                    <label for="person.general_notes" class="block text-sm font-medium text-gray-700">
                                        Observações Gerais
                                    </label>
                                    <textarea
                                        id="person.general_notes"
                                        v-model="form.person.general_notes"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                    <div v-if="form.errors['person.general_notes']" class="mt-1 text-sm text-red-600">
                                        {{ form.errors['person.general_notes'] }}
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
