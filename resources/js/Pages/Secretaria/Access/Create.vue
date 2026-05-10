<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const form = useForm({
    person_id: null,
    name: '',
    email: '',
    password: '',
    access_notes: '',
});

const personSearch = ref('');
const peopleResults = ref([]);
const selectedPerson = ref(null);
const eligibility = ref(null);
const isSearchingPeople = ref(false);
const searchWasPerformed = ref(false);

const searchPeople = async () => {
    const search = personSearch.value.trim();

    selectedPerson.value = null;
    form.person_id = null;
    eligibility.value = null;
    peopleResults.value = [];
    searchWasPerformed.value = true;

    if (search.length < 2) {
        return;
    }

    isSearchingPeople.value = true;

    try {
        const response = await axios.get('/people/search', {
            params: { q: search },
            headers: { Accept: 'application/json' },
        });

        const rawResults = response.data;

        if (Array.isArray(rawResults)) {
            peopleResults.value = rawResults;
        } else if (Array.isArray(rawResults.data)) {
            peopleResults.value = rawResults.data;
        } else if (Array.isArray(rawResults.people)) {
            peopleResults.value = rawResults.people;
        } else {
            peopleResults.value = [];
        }
    } catch (error) {
        peopleResults.value = [];
    } finally {
        isSearchingPeople.value = false;
    }
};

const selectPerson = async (person) => {
    selectedPerson.value = person;
    form.person_id = person.id;
    personSearch.value = person.full_name ?? person.name ?? '';
    form.name = person.full_name ?? person.name ?? form.name;
    form.email = person.email ?? form.email;

    peopleResults.value = [];
    searchWasPerformed.value = false;

    await checkEligibility(person.id);
};

const checkEligibility = async (personId) => {
    eligibility.value = null;

    try {
        const response = await axios.get(`/secretaria/acessos/elegibilidade/${personId}`, {
            headers: { Accept: 'application/json' },
        });

        eligibility.value = response.data;
    } catch (error) {
        eligibility.value = {
            allowed: false,
            reason: 'Não foi possível verificar a elegibilidade desta pessoa.',
        };
    }
};

const clearPerson = () => {
    selectedPerson.value = null;
    form.person_id = null;
    personSearch.value = '';
    peopleResults.value = [];
    eligibility.value = null;
    searchWasPerformed.value = false;
};

const submit = () => {
    form.post(route('secretaria.access.store'), {
        preserveScroll: true,
    });
};

const canSubmit = computed(() => {
    return Boolean(
        selectedPerson.value &&
        form.person_id &&
        eligibility.value &&
        eligibility.value.allowed === true &&
        !form.processing
    );
});
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
                            <div class="mt-2 space-y-3">
                                <div class="flex gap-2">
                                    <input
                                        id="person"
                                        v-model="personSearch"
                                        type="text"
                                        autocomplete="off"
                                        class="block flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Digite pelo menos 2 letras do nome"
                                        @keydown.enter.prevent="searchPeople"
                                    />
                                    <button
                                        type="button"
                                        @click="searchPeople"
                                        :disabled="isSearchingPeople || personSearch.trim().length < 2"
                                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ isSearchingPeople ? 'Buscando...' : 'Buscar' }}
                                    </button>
                                </div>

                                <p v-if="form.errors.person_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.person_id }}
                                </p>

                                <!-- Pessoa selecionada -->
                                <div v-if="selectedPerson" class="rounded-md bg-gray-50 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ selectedPerson.full_name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ selectedPerson.email || selectedPerson.primary_phone || 'Sem contato informado' }}
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            @click="clearPerson"
                                            class="rounded-md border border-gray-300 bg-white px-3 py-1 text-sm text-gray-700 hover:bg-gray-50"
                                        >
                                            Trocar pessoa
                                        </button>
                                    </div>
                                </div>

                                <!-- Lista de resultados -->
                                <div v-if="peopleResults.length > 0 && !selectedPerson" class="space-y-3">
                                    <p class="text-sm font-medium text-slate-600">
                                        {{ peopleResults.length === 1 ? 'Resultado encontrado:' : 'Resultados encontrados:' }}
                                    </p>
                                    <div
                                        v-for="person in peopleResults"
                                        :key="person.id"
                                        class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-4"
                                    >
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-slate-900">
                                                {{ person.full_name }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ person.email || person.primary_phone || 'Sem contato informado' }}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="selectPerson(person)"
                                            class="shrink-0 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                                        >
                                            Selecionar
                                        </button>
                                    </div>
                                </div>

                                <!-- Nenhum resultado -->
                                <div
                                    v-if="searchWasPerformed && !isSearchingPeople && peopleResults.length === 0 && !selectedPerson && personSearch.trim().length >= 2"
                                    class="text-sm text-gray-500"
                                >
                                    Nenhuma pessoa encontrada.
                                </div>
                            </div>
                        </div>

                        <!-- Painel de elegibilidade -->
                        <div
                            v-if="eligibility && selectedPerson"
                            class="rounded-md p-4"
                            :class="eligibility.eligibility && eligibility.eligibility.allowed ? 'bg-green-50' : 'bg-red-50'"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full"
                                        :class="eligibility.eligibility && eligibility.eligibility.allowed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                                    >
                                        {{ eligibility.eligibility && eligibility.eligibility.allowed ? '✓' : '✕' }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h3
                                        class="text-sm font-medium"
                                        :class="eligibility.eligibility && eligibility.eligibility.allowed ? 'text-green-800' : 'text-red-800'"
                                    >
                                        {{ eligibility.eligibility && eligibility.eligibility.allowed ? 'Acesso permitido' : 'Acesso bloqueado' }}
                                    </h3>
                                    <div
                                        class="mt-2 text-sm"
                                        :class="eligibility.eligibility && eligibility.eligibility.allowed ? 'text-green-700' : 'text-red-700'"
                                    >
                                        {{ eligibility.eligibility && eligibility.eligibility.reason ? eligibility.eligibility.reason : 'Não foi possível verificar elegibilidade.' }}
                                    </div>
                                    <div v-if="eligibility.eligibility && eligibility.eligibility.age" class="mt-1 text-sm text-gray-600">
                                        Idade: {{ eligibility.eligibility.age }} anos
                                    </div>
                                    <div v-if="eligibility.eligibility && eligibility.eligibility.age_group" class="mt-1 text-sm text-gray-600">
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
                                :disabled="!canSubmit"
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
