<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    permissions: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: '',
    slug: '',
    description: '',
    is_active: true,
    sort_order: 0,
    permissions: [],
});

const submit = () => {
    form.post(route('secretaria.access-profiles.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const togglePermission = (permissionId) => {
    const index = form.permissions.indexOf(permissionId);
    if (index === -1) {
        form.permissions.push(permissionId);
    } else {
        form.permissions.splice(index, 1);
    }
};

const generateSlug = () => {
    form.slug = form.name
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
};
</script>

<template>
    <Head title="Criar Perfil de Acesso" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                Criar Perfil de Acesso
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">
                                Nome *
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                @input="generateSlug"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Ex: Secretaria"
                                required
                            />
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Identificador -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-slate-700">
                                Identificador *
                            </label>
                            <input
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                class="notranslate mt-1 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm font-mono focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Ex: secretaria"
                                translate="no"
                                spellcheck="false"
                                autocomplete="off"
                                autocapitalize="off"
                                required
                            />
                            <p v-if="form.errors.slug" class="mt-2 text-sm text-red-600">
                                {{ form.errors.slug }}
                            </p>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700">
                                Descrição
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Descrição do perfil..."
                            />
                        </div>

                        <!-- Ordem -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-slate-700">
                                Ordem de Exibição
                            </label>
                            <input
                                id="sort_order"
                                v-model.number="form.sort_order"
                                type="number"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            />
                        </div>

                        <!-- Ativo -->
                        <div class="flex items-center">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            />
                            <label for="is_active" class="ml-2 block text-sm text-slate-700">
                                Perfil Ativo
                            </label>
                        </div>

                        <!-- Permissões -->
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 mb-4">Permissões</h3>
                            <div class="space-y-6">
                                <div v-for="(groupPermissions, groupName) in permissions" :key="groupName">
                                    <h4 class="text-sm font-semibold text-slate-700 mb-2 capitalize">{{ groupName }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        <label
                                            v-for="permission in groupPermissions"
                                            :key="permission.id"
                                            class="flex items-start p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="permission.id"
                                                v-model="form.permissions"
                                                class="h-4 w-4 mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            />
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-slate-900">{{ permission.name }}</div>
                                                <div class="text-xs text-slate-500">{{ permission.slug }}</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.permissions" class="mt-2 text-sm text-red-600">
                                {{ form.errors.permissions }}
                            </p>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                            <Link
                                :href="route('secretaria.access-profiles.index')"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                            >
                                Criar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
