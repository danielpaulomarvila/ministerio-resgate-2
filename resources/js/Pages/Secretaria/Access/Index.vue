<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    users: Object,
    stats: Object,
});
</script>

<template>
    <Head title="Acessos do Sistema" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Acessos do Sistema
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <p class="text-gray-600">
                        Gerencie quem pode entrar no sistema sem confundir pessoa, usuário e membro.
                    </p>
                </div>

                <!-- Cards de estatísticas -->
                <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="text-sm font-medium text-gray-500">Usuários Ativos</div>
                        <div class="mt-2 text-3xl font-bold text-green-600">{{ stats.active }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="text-sm font-medium text-gray-500">Usuários Suspensos</div>
                        <div class="mt-2 text-3xl font-bold text-red-600">{{ stats.suspended }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="text-sm font-medium text-gray-500">Pessoas sem Usuário</div>
                        <div class="mt-2 text-3xl font-bold text-gray-600">{{ stats.people_without_user }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="text-sm font-medium text-gray-500">Júniores com Acesso</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">{{ stats.juniors_with_access }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="text-sm font-medium text-gray-500">Troca de Senha Pendente</div>
                        <div class="mt-2 text-3xl font-bold text-orange-600">{{ stats.pending_password_change }}</div>
                    </div>
                </div>

                <!-- Botão de criar -->
                <div class="mb-6">
                    <Link
                        :href="route('secretaria.access.create')"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Criar Novo Acesso
                    </Link>
                </div>

                <!-- Tabela de usuários -->
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Nome
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Pessoa Vinculada
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Idade/Fase
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Troca de Senha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Criado em
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="user in users.data" :key="user.id">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        {{ user.person ? user.person.full_name : '-' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        {{ user.person ? user.person.age_group_label() : '-' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-green-100 text-green-800': user.status === 'active',
                                            'bg-red-100 text-red-800': user.status === 'suspended',
                                            'bg-gray-100 text-gray-800': user.status === 'inactive',
                                        }"
                                    >
                                        {{ user.status === 'active' ? 'Ativo' : user.status === 'suspended' ? 'Suspenso' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        {{ user.must_change_password ? 'Sim' : 'Não' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        {{ new Date(user.created_at).toLocaleDateString('pt-BR') }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <Link
                                        :href="route('secretaria.access.show', user.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    >
                                        Ver
                                    </Link>
                                    <Link
                                        :href="route('secretaria.access.edit', user.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    >
                                        Editar
                                    </Link>
                                    <Link
                                        v-if="user.status === 'active'"
                                        :href="route('secretaria.access.suspend', user.id)"
                                        class="text-red-600 hover:text-red-900"
                                        method="POST"
                                        as="button"
                                    >
                                        Suspender
                                    </Link>
                                    <Link
                                        v-else
                                        :href="route('secretaria.access.reactivate', user.id)"
                                        class="text-green-600 hover:text-green-900"
                                        method="POST"
                                        as="button"
                                    >
                                        Reativar
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div v-if="users.links" class="mt-6 flex justify-center">
                    <template v-for="link in users.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="mx-1 rounded px-3 py-2 text-sm"
                            :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="mx-1 rounded px-3 py-2 text-sm text-gray-400"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
