<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';

const form = useForm({
    person_id: '',
    name: '',
    email: '',
    password: '',
    access_notes: '',
});

const peopleSearch = ref('');
const peopleResults = ref([]);
const showPeopleDropdown = ref(false);
const eligibility = ref(null);
const selectedPerson = ref(null);

watch(peopleSearch, (value) => {
    if (value.length >= 2) {
        axios.get(`/people/search?q=${value}`)
            .then(response => {
                peopleResults.value = response.data;
                showPeopleDropdown.value = true;
            });
    } else {
        peopleResults.value = [];
        showPeopleDropdown.value = false;
    }
});

watch(() => form.person_id, (value) => {
    if (value) {
        checkEligibility(value);
    } else {
        eligibility.value = null;
    }
});

const checkEligibility = (personId) => {
    axios.get(`/secretaria/acessos/elegibilidade/${personId}`)
        .then(response => {
            eligibility.value = response.data;
            if (!response.data.eligibility.allowed) {
                form.person_id = '';
            }
        });
};

const selectPerson = (person) => {
    selectedPerson.value = person;
    form.person_id = person.id;
    form.name = person.full_name;
    form.email = person.email || '';
    peopleSearch.value = person.full_name;
    showPeopleDropdown.value = false;
};

const clearPerson = () => {
    selectedPerson.value = null;
    form.person_id = '';
    form.name = '';
    form.email = '';
    peopleSearch.value = '';
    eligibility.value = null;
};

const submit = () => {
    form.post(route('secretaria.access.store'), {
        preserveScroll: true,
    });
};

const canSubmit = () => {
    return form.person_id && eligibility && eligibility.eligibility.allowed;
};
</script>

<template>
    <Head title="Criar Acesso" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Criar Novo Acesso
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Informações do Usuário</h3>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6 p-6">
                        <!-- Pessoa vinculada -->
                        <div>
                            <label for="person" class="block text-sm font-medium text-gray-700">
                                Pessoa Vinculada
                            </label>
                            <div class="relative mt-1">
                                <input
                                    id="person"
                                    v-model="peopleSearch"
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Digite o nome da pessoa..."
                                    @focus="showPeopleDropdown = true"
                                />
                                <button
                                    v-if="selectedPerson"
                                    type="button"
                                    @click="clearPerson"
                                    class="absolute right-2 top-2 text-gray-400 hover:text-gray-600"
                                >
                                    ✕
                                </button>
                            </div>
                            <div
                                v-if="showPeopleDropdown && peopleResults.length > 0"
                                class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg"
                            >
                                <div
                                    v-for="person in peopleResults"
                                    :key="person.id"
                                    class="cursor-pointer border-b px-4 py-2 hover:bg-gray-100"
                                    @click="selectPerson(person)"
                                >
                                    <div class="font-medium text-gray-900">{{ person.full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ person.email }}</div>
                                    <div class="text-sm text-gray-500">{{ person.primary_phone }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Painel de elegibilidade -->
                        <div
                            v-if="eligibility && selectedPerson"
                            class="rounded-md p-4"
                            :class="eligibility.eligibility.allowed ? 'bg-green-50' : 'bg-red-50'"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full"
                                        :class="eligibility.eligibility.allowed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                                    >
                                        {{ eligibility.eligibility.allowed ? '✓' : '✕' }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h3
                                        class="text-sm font-medium"
                                        :class="eligibility.eligibility.allowed ? 'text-green-800' : 'text-red-800'"
                                    >
                                        {{ eligibility.eligibility.allowed ? 'Acesso permitido' : 'Acesso bloqueado' }}
                                    </h3>
                                    <div
                                        class="mt-2 text-sm"
                                        :class="eligibility.eligibility.allowed ? 'text-green-700' : 'text-red-700'"
                                    >
                                        {{ eligibility.eligibility.reason }}
                                    </div>
                                    <div v-if="eligibility.eligibility.age" class="mt-1 text-sm text-gray-600">
                                        Idade: {{ eligibility.eligibility.age }} anos
                                    </div>
                                    <div v-if="eligibility.eligibility.age_group" class="mt-1 text-sm text-gray-600">
                                        Grupo: {{ eligibility.eligibility.age_group }}
                                    </div>
                                    <div v-if="eligibility.has_active_user" class="mt-1 text-sm text-red-600">
                                        Esta pessoa já possui usuário ativo.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nome do usuário -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nome do Usuário
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Preenchido automaticamente com o nome da pessoa, mas pode ser ajustado.
                            </p>
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
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Preenchido automaticamente com o email da pessoa, se houver.
                            </p>
                        </div>

                        <!-- Senha temporária -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Senha Temporária
                            </label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Deixe em branco para gerar automaticamente"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Se não informada, uma senha segura será gerada automaticamente.
                            </p>
                        </div>

                        <!-- Observações -->
                        <div>
                            <label for="access_notes" class="block text-sm font-medium text-gray-700">
                                Observações de Acesso
                            </label>
                            <textarea
                                id="access_notes"
                                v-model="form.access_notes"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3">
                            <Link
                                :href="route('secretaria.access.index')"
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="!canSubmit()"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Criar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
