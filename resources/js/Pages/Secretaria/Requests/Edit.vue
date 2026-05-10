<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    request: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: props.request.title,
    description: props.request.description,
    priority: props.request.priority,
    requester_person_id: props.request.requester_person_id,
    related_alert_id: props.request.related_alert_id,
    assigned_to_user_id: props.request.assigned_to_user_id,
    due_at: props.request.due_at,
    internal_notes: props.request.internal_notes,
});

// Autocomplete para pessoas
const personSearch = ref('');
const personResults = ref([]);
const selectedPerson = ref(null);

// Inicializar com a pessoa relacionada existente
onMounted(() => {
    if (props.request.requesterPerson) {
        personSearch.value = props.request.requesterPerson.full_name;
        selectedPerson.value = props.request.requesterPerson;
    }
    if (props.request.assignedToUser) {
        userSearch.value = props.request.assignedToUser.name;
        selectedUser.value = props.request.assignedToUser;
    }
});

watch(personSearch, async (newValue) => {
    if (newValue.length >= 2) {
        try {
            const response = await axios.get('/people/search', {
                params: { q: newValue }
            });
            personResults.value = response.data;
        } catch (error) {
            console.error('Erro ao buscar pessoas:', error);
            personResults.value = [];
        }
    } else {
        personResults.value = [];
    }
});

const selectPerson = (person) => {
    form.requester_person_id = person.id;
    personSearch.value = person.full_name;
    personResults.value = [];
    selectedPerson.value = person;
};

const clearPerson = () => {
    form.requester_person_id = null;
    personSearch.value = '';
    personResults.value = [];
    selectedPerson.value = null;
};

// Autocomplete para usuários
const userSearch = ref('');
const userResults = ref([]);
const selectedUser = ref(null);

watch(userSearch, async (newValue) => {
    if (newValue.length >= 2) {
        try {
            const response = await axios.get('/users/search', {
                params: { q: newValue }
            });
            userResults.value = response.data;
        } catch (error) {
            console.error('Erro ao buscar usuários:', error);
            userResults.value = [];
        }
    } else {
        userResults.value = [];
    }
});

const selectUser = (user) => {
    form.assigned_to_user_id = user.id;
    userSearch.value = user.name;
    userResults.value = [];
    selectedUser.value = user;
};

const clearUser = () => {
    form.assigned_to_user_id = null;
    userSearch.value = '';
    userResults.value = [];
    selectedUser.value = null;
};

const submit = () => {
    form.put(route('secretaria.requests.update', props.request.id), {
        onSuccess: () => {
            // Sucesso
        },
    });
};
</script>

<template>
    <Head title="Editar Solicitação" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Editar Solicitação
                </h2>
                <Link
                    :href="route('secretaria.requests.show', request.id)"
                    class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                >
                    Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Editar Solicitação #{{ request.id }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Esta solicitação não altera dados oficiais automaticamente.
                        </p>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Título -->
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Título <span class="text-red-600">*</span>
                                    </label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required
                                        placeholder="Título da solicitação"
                                    />
                                </div>

                                <!-- Descrição -->
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Descrição
                                    </label>
                                    <textarea
                                        v-model="form.description"
                                        rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Descreva a solicitação em detalhes..."
                                    ></textarea>
                                </div>

                                <!-- Prioridade -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Prioridade <span class="text-red-600">*</span>
                                    </label>
                                    <select
                                        v-model="form.priority"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required
                                    >
                                        <option value="low">Baixa</option>
                                        <option value="normal">Normal</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>

                                <!-- Prazo -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Prazo
                                    </label>
                                    <input
                                        v-model="form.due_at"
                                        type="datetime-local"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Deixe vazio se não houver prazo definido.
                                    </p>
                                </div>

                                <!-- Pessoa relacionada -->
                                <div class="relative">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Pessoa relacionada
                                    </label>
                                    <div class="relative">
                                        <input
                                            v-model="personSearch"
                                            type="text"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Digite o nome da pessoa..."
                                            @input="personResults = []"
                                        />
                                        <button
                                            v-if="personSearch || selectedPerson"
                                            type="button"
                                            @click="clearPerson"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    <!-- Dropdown de resultados -->
                                    <div
                                        v-if="personResults.length > 0"
                                        class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg"
                                    >
                                        <ul class="py-1">
                                            <li
                                                v-for="person in personResults"
                                                :key="person.id"
                                                @click="selectPerson(person)"
                                                class="cursor-pointer px-4 py-2 hover:bg-gray-100"
                                            >
                                                <div class="font-medium">{{ person.full_name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ person.primary_phone || person.email || 'Sem contato' }}
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Deixe vazio se não houver pessoa relacionada.
                                    </p>
                                </div>

                                <!-- Responsável pela análise -->
                                <div class="relative">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Responsável pela análise
                                    </label>
                                    <div class="relative">
                                        <input
                                            v-model="userSearch"
                                            type="text"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Digite o nome do usuário..."
                                            @input="userResults = []"
                                        />
                                        <button
                                            v-if="userSearch || selectedUser"
                                            type="button"
                                            @click="clearUser"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    <!-- Dropdown de resultados -->
                                    <div
                                        v-if="userResults.length > 0"
                                        class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg"
                                    >
                                        <ul class="py-1">
                                            <li
                                                v-for="user in userResults"
                                                :key="user.id"
                                                @click="selectUser(user)"
                                                class="cursor-pointer px-4 py-2 hover:bg-gray-100"
                                            >
                                                <div class="font-medium">{{ user.name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ user.email || 'Sem email' }}
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Deixe vazio para não atribuir responsável.
                                    </p>
                                </div>

                                <!-- Observações internas -->
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Observações internas
                                    </label>
                                    <textarea
                                        v-model="form.internal_notes"
                                        rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Observações apenas para uso interno da Secretaria..."
                                    ></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-4">
                                <Link
                                    :href="route('secretaria.requests.show', request.id)"
                                    class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
