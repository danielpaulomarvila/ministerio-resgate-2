<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`Perfil: ${profile.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                Perfil de Acesso
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link
                        :href="route('secretaria.access-profiles.index')"
                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                    >
                        ← Voltar para Perfis
                    </Link>
                </div>

                <!-- Header do Perfil -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden mb-6">
                    <div class="px-6 py-6 border-b border-slate-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">{{ profile.name }}</h3>
                                <code class="notranslate rounded bg-slate-100 px-2 py-1 font-mono text-sm text-slate-700 mt-1 block" translate="no">{{ profile.slug }}</code>
                                <p class="text-slate-600 mt-2">{{ profile.description || 'Sem descrição' }}</p>
                            </div>
                            <div class="flex gap-3">
                                <Link
                                    :href="route('secretaria.access-profiles.edit', profile.id)"
                                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                                >
                                    Editar
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-slate-500">Status</p>
                            <span
                                :class="profile.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                class="mt-1 inline-block px-2 py-1 text-xs font-semibold rounded-full"
                            >
                                {{ profile.is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Tipo</p>
                            <span
                                :class="profile.is_system ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                                class="mt-1 inline-block px-2 py-1 text-xs font-semibold rounded-full"
                            >
                                {{ profile.is_system ? 'Sistema' : 'Personalizado' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Ordem</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ profile.sort_order }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white shadow-sm rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500">Permissões</p>
                                <p class="text-3xl font-bold text-slate-900">{{ profile.permissions.length }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-100">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500">Usuários</p>
                                <p class="text-3xl font-bold text-slate-900">{{ profile.users.length }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissões -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Permissões</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div v-if="profile.permissions.length === 0" class="text-slate-500 text-sm">
                            Nenhuma permissão atribuída.
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="(perms, group) in Object.groupBy(profile.permissions, p => p.group)" :key="group">
                                <h4 class="text-sm font-semibold text-slate-700 mb-2 capitalize">{{ group }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    <div
                                        v-for="permission in perms"
                                        :key="permission.id"
                                        class="flex items-center p-3 bg-slate-50 rounded-lg"
                                    >
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ permission.name }}</div>
                                            <code class="notranslate text-xs text-slate-500 font-mono rounded bg-slate-100 px-1 py-0.5" translate="no">{{ permission.slug }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usuários -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Usuários com este Perfil</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div v-if="profile.users.length === 0" class="text-slate-500 text-sm">
                            Nenhum usuário com este perfil.
                        </div>
                        <table v-else class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                <tr v-for="user in profile.users" :key="user.id">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">
                                        {{ user.person_name || user.name }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-500">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-1 text-xs font-semibold rounded-full"
                                        >
                                            {{ user.status === 'active' ? 'Ativo' : 'Suspenso' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
