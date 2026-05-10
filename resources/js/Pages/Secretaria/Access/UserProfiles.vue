<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    availableProfiles: {
        type: Array,
        required: true,
    },
    userProfileIds: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    profiles: props.userProfileIds,
    notes: '',
});

const submit = () => {
    form.put(route('secretaria.acessos.perfis.update', props.user.id), {
        onSuccess: () => {
            form.reset('notes');
        },
    });
};

const toggleProfile = (profileId) => {
    const index = form.profiles.indexOf(profileId);
    if (index === -1) {
        form.profiles.push(profileId);
    } else {
        form.profiles.splice(index, 1);
    }
};
</script>

<template>
    <Head :title="`Perfis de Acesso - ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                Gerenciar Perfis de Acesso
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link
                        :href="route('secretaria.acessos.show', user.id)"
                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                    >
                        ← Voltar para Detalhes do Acesso
                    </Link>
                </div>

                <!-- Info do Usuário -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden mb-6">
                    <div class="px-6 py-6 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">{{ user.name }}</h3>
                        <p class="text-sm text-slate-500 mt-1">{{ user.email }}</p>
                        <p v-if="user.person" class="text-sm text-slate-600 mt-1">
                            Pessoa: {{ user.person.full_name }}
                        </p>
                    </div>
                    <div class="px-6 py-4">
                        <span
                            :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            class="px-2 py-1 text-xs font-semibold rounded-full"
                        >
                            {{ user.status === 'active' ? 'Ativo' : 'Suspenso' }}
                        </span>
                    </div>
                </div>

                <!-- Perfis Atuais -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Perfis Atuais</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div v-if="user.access_profiles.length === 0" class="text-slate-500 text-sm">
                            Nenhum perfil atribuído.
                        </div>
                        <div v-else class="space-y-2">
                            <div
                                v-for="profile in user.access_profiles"
                                :key="profile.id"
                                class="flex items-center justify-between p-3 bg-slate-50 rounded-lg"
                            >
                                <div>
                                    <div class="text-sm font-medium text-slate-900">{{ profile.name }}</div>
                                    <code class="notranslate text-xs text-slate-500 font-mono rounded bg-slate-100 px-1 py-0.5" translate="no">{{ profile.slug }}</code>
                                </div>
                                <span
                                    v-if="profile.is_system"
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"
                                >
                                    Sistema
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulário de Edição -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-slate-900">Atribuir Perfis</h3>

                        <!-- Lista de Perfis -->
                        <div class="space-y-4">
                            <label
                                v-for="profile in availableProfiles"
                                :key="profile.id"
                                class="flex items-start p-4 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer"
                            >
                                <input
                                    type="checkbox"
                                    :value="profile.id"
                                    v-model="form.profiles"
                                    class="h-4 w-4 mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-medium text-slate-900">{{ profile.name }}</div>
                                        <span
                                            v-if="profile.is_system"
                                            class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"
                                        >
                                            Sistema
                                        </span>
                                        <span
                                            :class="profile.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                        >
                                            {{ profile.is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-500">{{ profile.description }}</div>
                                </div>
                            </label>
                        </div>

                        <!-- Notas -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700">
                                Notas (opcional)
                            </label>
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                rows="2"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Notas sobre a atribuição de perfis..."
                            />
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                            <Link
                                :href="route('secretaria.acessos.show', user.id)"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                            >
                                Salvar Perfis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
