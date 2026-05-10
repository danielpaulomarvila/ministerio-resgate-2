<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    access_notes: props.user.access_notes || '',
});

const submit = () => {
    form.put(route('secretaria.access.update', props.user.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Editar Acesso" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Acesso
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Editar Usuário</h3>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6 p-6">
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

                        <!-- Informação da pessoa vinculada (apenas leitura) -->
                        <div v-if="user.person" class="rounded-md bg-gray-50 p-4">
                            <div class="text-sm font-medium text-gray-700">Pessoa Vinculada</div>
                            <div class="mt-1 text-sm text-gray-600">{{ user.person.full_name }}</div>
                            <div class="mt-1 text-sm text-gray-600">{{ user.person.email || 'Sem email' }}</div>
                            <div class="mt-1 text-sm text-gray-600">Idade: {{ user.person.age }} anos ({{ user.person.age_group_label() }})</div>
                            <p class="mt-2 text-xs text-gray-500">
                                A pessoa vinculada não pode ser alterada nesta tela para manter a integridade do sistema.
                            </p>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3">
                            <Link
                                :href="route('secretaria.access.show', user.id)"
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50"
                            >
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
