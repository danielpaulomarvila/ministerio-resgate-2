<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmActionModal from '@/Components/ConfirmActionModal.vue';
import { ref } from 'vue';

const props = defineProps({
    department: {
        type: Object,
        required: true,
    },
    members: {
        type: Array,
        required: true,
    },
});

/**
 * Exclui um departamento (soft delete)
 * 
 * IMPORTANTE:
 * - Departamento do sistema não pode ser excluído
 * - Departamento com pessoas ativas vinculadas não pode ser excluído
 * - Usa soft delete (não apaga dados do banco)
 * - Não apaga pessoas, usuários, membros ou member_profile
 * - Usa modal visual, não window.confirm nativo
 */
const showDeleteModal = ref(false);

const openDeleteModal = () => {
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const confirmDeleteDepartment = () => {
    router.delete(route('secretaria.departments.destroy', props.department.id), {
        onFinish: () => closeDeleteModal(),
    });
};
</script>

<template>
    <Head :title="department.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    {{ department.name }}
                </h2>
                <div class="space-x-3">
                    <Link
                        :href="route('secretaria.departments.edit', department.id)"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Editar
                    </Link>
                    <button
                        v-if="!department.is_system"
                        type="button"
                        @click="openDeleteModal"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                    >
                        Excluir
                    </button>
                    <Link
                        :href="route('secretaria.departments.index')"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Voltar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Cards de Informações -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
                    <!-- Informações do Departamento -->
                    <div class="bg-white shadow-sm rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Informações</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-500">Identificador:</span>
                                <span class="ml-2 text-sm font-mono notranslate">{{ department.slug }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Tipo:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.department_type_label }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Status:</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="department.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                    {{ department.status_label }}
                                </span>
                            </div>
                            <div v-if="department.description">
                                <span class="text-sm text-slate-500">Descrição:</span>
                                <p class="mt-1 text-sm text-slate-700">{{ department.description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Líderes -->
                    <div class="bg-white shadow-sm rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Liderança</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-500">Líder:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.leader_name || 'Não definido' }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Auxiliar:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.assistant_leader_name || 'Não definido' }}</span>
                            </div>
                            <div v-if="department.parent_department_name">
                                <span class="text-sm text-slate-500">Departamento Pai:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.parent_department_name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reuniões -->
                    <div class="bg-white shadow-sm rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Reuniões</h3>
                        <div class="space-y-3">
                            <div v-if="department.meeting_day">
                                <span class="text-sm text-slate-500">Dia:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.meeting_day }}</span>
                            </div>
                            <div v-if="department.meeting_time">
                                <span class="text-sm text-slate-500">Horário:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.meeting_time }}</span>
                            </div>
                            <div v-if="department.location">
                                <span class="text-sm text-slate-500">Local:</span>
                                <span class="ml-2 text-sm font-medium">{{ department.location }}</span>
                            </div>
                            <div v-if="department.color">
                                <span class="text-sm text-slate-500">Cor:</span>
                                <div class="mt-2 flex items-center">
                                    <div class="w-6 h-6 rounded-full mr-2" :style="{ backgroundColor: department.color }"></div>
                                    <span class="text-sm font-mono notranslate">{{ department.color }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div v-if="department.notes" class="bg-white shadow-sm rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Observações</h3>
                    <p class="text-sm text-slate-700">{{ department.notes }}</p>
                </div>

                <!-- Membros -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">Membros ({{ members.length }})</h3>
                        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            Adicionar Pessoa
                        </button>
                    </div>
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Função</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Líder</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="member in members" :key="member.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    {{ member.person_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ member.role_label }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ member.category || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="member.is_leader" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        Líder
                                    </span>
                                    <span v-else-if="member.is_assistant" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Auxiliar
                                    </span>
                                    <span v-else class="text-sm text-slate-400">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ member.status === 'active' ? 'Ativo' : member.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Editar
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        Remover
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="members.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500">
                                    Nenhum membro vinculado a este departamento.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- Modal de Confirmação de Exclusão -->
    <ConfirmActionModal
        :show="showDeleteModal"
        title="Excluir departamento"
        :message="`Tem certeza que deseja excluir o departamento '${props.department.name}'? Esta ação removerá o departamento da listagem, mas não apagará pessoas, usuários, membros ou históricos vinculados.`"
        confirm-text="Excluir Departamento"
        cancel-text="Cancelar"
        confirm-button-class="bg-red-600 hover:bg-red-700 text-white"
        @confirm="confirmDeleteDepartment"
        @cancel="closeDeleteModal"
    />
</template>
