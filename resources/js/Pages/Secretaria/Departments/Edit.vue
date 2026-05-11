<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    department: {
        type: Object,
        required: true,
    },
    parentDepartments: {
        type: Array,
        required: true,
    },
    people: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: props.department.name,
    slug: props.department.slug,
    description: props.department.description || '',
    department_type: props.department.department_type,
    status: props.department.status,
    parent_department_id: props.department.parent_department_id,
    leader_person_id: props.department.leader_person_id,
    assistant_leader_person_id: props.department.assistant_leader_person_id,
    color: props.department.color || '',
    icon: props.department.icon || '',
    meeting_day: props.department.meeting_day || '',
    meeting_time: props.department.meeting_time || '',
    location: props.department.location || '',
    sort_order: props.department.sort_order || 0,
    is_system: props.department.is_system,
    notes: props.department.notes || '',
});

const submit = () => {
    form.put(route('secretaria.departments.update', props.department.id));
};
</script>

<template>
    <Head title="Editar Departamento" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                Editar Departamento
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-2xl p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Nome *</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required
                            />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>

                        <!-- Identificador -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-slate-700">Identificador *</label>
                            <input
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm notranslate font-mono"
                                required
                            />
                            <p class="mt-1 text-xs text-slate-500">Use letras minúsculas, sem espaços ou acentos. Ex: louvor, midia, jovens-resgatados</p>
                            <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700">Descrição</label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            ></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                        </div>

                        <!-- Tipo e Status -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="department_type" class="block text-sm font-medium text-slate-700">Tipo *</label>
                                <select
                                    id="department_type"
                                    v-model="form.department_type"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                >
                                    <option value="ministry">Ministério</option>
                                    <option value="administrative">Administrativo</option>
                                    <option value="youth">Jovens</option>
                                    <option value="support">Apoio</option>
                                    <option value="financial">Financeiro</option>
                                    <option value="worship">Louvor</option>
                                    <option value="children">Infantil</option>
                                    <option value="evangelism">Evangelismo</option>
                                    <option value="other">Outro</option>
                                </select>
                                <p v-if="form.errors.department_type" class="mt-1 text-sm text-red-600">{{ form.errors.department_type }}</p>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-slate-700">Status *</label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                >
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                    <option value="archived">Arquivado</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>
                        </div>

                        <!-- Departamento Pai -->
                        <div>
                            <label for="parent_department_id" class="block text-sm font-medium text-slate-700">Departamento Pai</label>
                            <select
                                id="parent_department_id"
                                v-model="form.parent_department_id"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option :value="null">Nenhum</option>
                                <option v-for="dept in parentDepartments" :key="dept.id" :value="dept.id">
                                    {{ dept.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.parent_department_id" class="mt-1 text-sm text-red-600">{{ form.errors.parent_department_id }}</p>
                        </div>

                        <!-- Líder e Auxiliar -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="leader_person_id" class="block text-sm font-medium text-slate-700">Líder</label>
                                <select
                                    id="leader_person_id"
                                    v-model="form.leader_person_id"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option :value="null">Nenhum</option>
                                    <option v-for="person in people" :key="person.id" :value="person.id">
                                        {{ person.full_name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.leader_person_id" class="mt-1 text-sm text-red-600">{{ form.errors.leader_person_id }}</p>
                            </div>

                            <div>
                                <label for="assistant_leader_person_id" class="block text-sm font-medium text-slate-700">Auxiliar</label>
                                <select
                                    id="assistant_leader_person_id"
                                    v-model="form.assistant_leader_person_id"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option :value="null">Nenhum</option>
                                    <option v-for="person in people" :key="person.id" :value="person.id">
                                        {{ person.full_name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.assistant_leader_person_id" class="mt-1 text-sm text-red-600">{{ form.errors.assistant_leader_person_id }}</p>
                            </div>
                        </div>

                        <!-- Cor e Ícone -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="color" class="block text-sm font-medium text-slate-700">Cor</label>
                                <input
                                    id="color"
                                    v-model="form.color"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="#6366f1"
                                />
                                <p v-if="form.errors.color" class="mt-1 text-sm text-red-600">{{ form.errors.color }}</p>
                            </div>

                            <div>
                                <label for="icon" class="block text-sm font-medium text-slate-700">Ícone</label>
                                <input
                                    id="icon"
                                    v-model="form.icon"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <p v-if="form.errors.icon" class="mt-1 text-sm text-red-600">{{ form.errors.icon }}</p>
                            </div>
                        </div>

                        <!-- Reuniões -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div>
                                <label for="meeting_day" class="block text-sm font-medium text-slate-700">Dia de Reunião</label>
                                <input
                                    id="meeting_day"
                                    v-model="form.meeting_day"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Domingo, Quarta, etc."
                                />
                                <p v-if="form.errors.meeting_day" class="mt-1 text-sm text-red-600">{{ form.errors.meeting_day }}</p>
                            </div>

                            <div>
                                <label for="meeting_time" class="block text-sm font-medium text-slate-700">Horário</label>
                                <input
                                    id="meeting_time"
                                    v-model="form.meeting_time"
                                    type="time"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <p v-if="form.errors.meeting_time" class="mt-1 text-sm text-red-600">{{ form.errors.meeting_time }}</p>
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-slate-700">Local</label>
                                <input
                                    id="location"
                                    v-model="form.location"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <p v-if="form.errors.location" class="mt-1 text-sm text-red-600">{{ form.errors.location }}</p>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700">Observações</label>
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            ></textarea>
                            <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</p>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="form.reset()"
                                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Salvando...' : 'Salvar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
