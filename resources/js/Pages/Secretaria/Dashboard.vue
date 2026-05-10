<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Painel da Secretaria
 * Etapa 4 do Projeto Ministério Resgate / Família Resgate
 *
 * Este painel mostra informações úteis e alertas básicos sobre:
 * - pessoas
 * - famílias
 * - responsáveis
 * - crianças menores de 11 anos
 * - Júniores
 * - Jovens
 * - adultos
 * - pessoas sem família
 * - menores sem responsável ativo
 * - crianças próximas de completar 11 anos
 * - cadastros possivelmente incompletos
 * - atalhos para Pessoas, Famílias e Responsáveis
 */

const props = defineProps({
    // Totais
    total_people: {
        type: Number,
        default: 0
    },
    total_families: {
        type: Number,
        default: 0
    },
    total_active_guardianships: {
        type: Number,
        default: 0
    },
    
    // Faixa etária
    children_under_11: {
        type: Number,
        default: 0
    },
    juniors: {
        type: Number,
        default: 0
    },
    youngs: {
        type: Number,
        default: 0
    },
    adults: {
        type: Number,
        default: 0
    },
    
    // Atenções
    people_without_family: {
        type: Number,
        default: 0
    },
    minors_without_guardian: {
        type: Number,
        default: 0
    },
    children_near_11: {
        type: Array,
        default: () => []
    },
    people_without_birth_date: {
        type: Number,
        default: 0
    },
    people_without_phone: {
        type: Number,
        default: 0
    },
    people_without_email_or_phone: {
        type: Number,
        default: 0
    },
    
    // Listas recentes
    recent_people: {
        type: Array,
        default: () => []
    },
    recent_families: {
        type: Array,
        default: () => []
    },
    recent_guardianships: {
        type: Array,
        default: () => []
    },
    
    // Alertas (Etapa 5)
    open_alerts: {
        type: Number,
        default: 0
    },
    urgent_alerts: {
        type: Number,
        default: 0
    },
    
    // Solicitações (Etapa 6)
    pending_requests: {
        type: Number,
        default: 0
    },
    urgent_requests: {
        type: Number,
        default: 0
    },
    in_review_requests: {
        type: Number,
        default: 0
    },
    
    // Acessos ao Sistema (Etapa 7)
    active_users: {
        type: Number,
        default: 0
    },
    suspended_users: {
        type: Number,
        default: 0
    },
    people_without_user: {
        type: Number,
        default: 0
    },
    juniors_with_access: {
        type: Number,
        default: 0
    },
});
</script>

<template>
    <Head title="Painel da Secretaria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Painel da Secretaria
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        Visão inicial para acompanhar cadastros, famílias e responsáveis.
                    </p>
                </div>

                <!-- Cards principais -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Pessoas</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ total_people }}</div>
                            <Link :href="route('people.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver pessoas →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Famílias</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ total_families }}</div>
                            <Link :href="route('families.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver famílias →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Responsáveis Ativos</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ total_active_guardianships }}</div>
                            <Link :href="route('guardianships.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver responsáveis →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Menores sem Responsável</div>
                            <div class="mt-2 text-3xl font-bold" :class="minors_without_guardian > 0 ? 'text-red-600' : 'text-gray-900'">
                                {{ minors_without_guardian }}
                            </div>
                            <div v-if="minors_without_guardian > 0" class="mt-3 text-sm text-red-600">
                                Atenção necessária
                            </div>
                            <div v-else class="mt-3 text-sm text-green-600">
                                Todos cobertos
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Alertas (Etapa 5) -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Alertas Abertos</div>
                            <div class="mt-2 text-3xl font-bold text-green-600">{{ open_alerts }}</div>
                            <Link :href="route('secretaria.alerts.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver alertas →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Alertas Urgentes</div>
                            <div class="mt-2 text-3xl font-bold" :class="urgent_alerts > 0 ? 'text-red-600' : 'text-gray-900'">
                                {{ urgent_alerts }}
                            </div>
                            <Link :href="route('secretaria.alerts.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver alertas →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card de Solicitações (Etapa 6) -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Solicitações Pendentes</div>
                            <div class="mt-2 text-3xl font-bold" :class="pending_requests > 0 ? 'text-green-600' : 'text-gray-900'">
                                {{ pending_requests }}
                            </div>
                            <Link :href="route('secretaria.requests.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver solicitações →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Solicitações em Análise</div>
                            <div class="mt-2 text-3xl font-bold" :class="in_review_requests > 0 ? 'text-yellow-600' : 'text-gray-900'">
                                {{ in_review_requests }}
                            </div>
                            <Link :href="route('secretaria.requests.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver solicitações →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Solicitações Urgentes</div>
                            <div class="mt-2 text-3xl font-bold" :class="urgent_requests > 0 ? 'text-red-600' : 'text-gray-900'">
                                {{ urgent_requests }}
                            </div>
                            <Link :href="route('secretaria.requests.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver solicitações →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Em Análise</div>
                            <div class="mt-2 text-3xl font-bold" :class="in_review_requests > 0 ? 'text-yellow-600' : 'text-gray-900'">
                                {{ in_review_requests }}
                            </div>
                            <Link :href="route('secretaria.requests.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver solicitações →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card de Acessos ao Sistema (Etapa 7) -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Usuários Ativos</div>
                            <div class="mt-2 text-3xl font-bold text-green-600">{{ active_users }}</div>
                            <Link :href="route('secretaria.access.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver acessos →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Usuários Suspensos</div>
                            <div class="mt-2 text-3xl font-bold" :class="suspended_users > 0 ? 'text-red-600' : 'text-gray-900'">
                                {{ suspended_users }}
                            </div>
                            <Link :href="route('secretaria.access.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver acessos →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Pessoas sem Usuário</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ people_without_user }}</div>
                            <Link :href="route('secretaria.access.create')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Criar acesso →
                            </Link>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Júniores com Acesso</div>
                            <div class="mt-2 text-3xl font-bold text-blue-600">{{ juniors_with_access }}</div>
                            <Link :href="route('secretaria.access.index')" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Ver acessos →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Cards por faixa etária -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pessoas por Faixa Etária</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Crianças (menos de 11)</div>
                                <div class="mt-2 text-2xl font-bold text-blue-600">{{ children_under_11 }}</div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Júniores (11-13)</div>
                                <div class="mt-2 text-2xl font-bold text-green-600">{{ juniors }}</div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Jovens (14-17)</div>
                                <div class="mt-2 text-2xl font-bold text-purple-600">{{ youngs }}</div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Adultos (18+)</div>
                                <div class="mt-2 text-2xl font-bold text-gray-600">{{ adults }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atenções da Secretaria -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Atenções da Secretaria</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Pessoas sem Família</div>
                                <div class="mt-2 text-2xl font-bold" :class="people_without_family > 0 ? 'text-amber-600' : 'text-gray-900'">
                                    {{ people_without_family }}
                                </div>
                                <div v-if="people_without_family > 0" class="mt-3 text-sm text-amber-600">
                                    Vincular às famílias
                                </div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Sem Data de Nascimento</div>
                                <div class="mt-2 text-2xl font-bold" :class="people_without_birth_date > 0 ? 'text-amber-600' : 'text-gray-900'">
                                    {{ people_without_birth_date }}
                                </div>
                                <div v-if="people_without_birth_date > 0" class="mt-3 text-sm text-amber-600">
                                    Revisar cadastros
                                </div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Sem Telefone</div>
                                <div class="mt-2 text-2xl font-bold" :class="people_without_phone > 0 ? 'text-amber-600' : 'text-gray-900'">
                                    {{ people_without_phone }}
                                </div>
                                <div v-if="people_without_phone > 0" class="mt-3 text-sm text-amber-600">
                                    Adicionar contato
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crianças próximas dos 11 anos -->
                <div v-if="children_near_11.length > 0" class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Crianças Próximas dos 11 Anos</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg overflow-hidden">
                        <div class="p-4">
                            <p class="text-sm text-blue-800 mb-4">
                                Revise crianças que estão prestes a entrar na fase Júnior. Ao completar 11 anos, a Secretaria deve revisar o cadastro para possível transição com usuário supervisionado.
                            </p>
                            <div class="space-y-3">
                                <div v-for="child in children_near_11" :key="child.id" class="flex items-center justify-between bg-white p-3 rounded-md">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ child.full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ child.age }} anos</div>
                                    </div>
                                    <div class="text-sm text-blue-600 font-medium">
                                        {{ child.turns_11_at }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Listas recentes -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Pessoas Recentes</h4>
                            <div v-if="recent_people.length > 0" class="space-y-3">
                                <div v-for="person in recent_people" :key="person.id" class="text-sm">
                                    <Link :href="route('people.show', person.id)" class="text-gray-900 hover:text-blue-600">
                                        {{ person.full_name }}
                                    </Link>
                                    <div class="text-xs text-gray-500">{{ person.created_at }}</div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500">
                                Nenhuma pessoa cadastrada ainda.
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Famílias Recentes</h4>
                            <div v-if="recent_families.length > 0" class="space-y-3">
                                <div v-for="family in recent_families" :key="family.id" class="text-sm">
                                    <Link :href="route('families.show', family.id)" class="text-gray-900 hover:text-blue-600">
                                        {{ family.name }}
                                    </Link>
                                    <div class="text-xs text-gray-500">{{ family.created_at }}</div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500">
                                Nenhuma família cadastrada ainda.
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Responsabilidades Recentes</h4>
                            <div v-if="recent_guardianships.length > 0" class="space-y-3">
                                <div v-for="guardianship in recent_guardianships" :key="guardianship.id" class="text-sm">
                                    <Link :href="route('guardianships.show', guardianship.id)" class="text-gray-900 hover:text-blue-600">
                                        {{ guardianship.minor_name }}
                                    </Link>
                                    <div class="text-xs text-gray-500">
                                        Responsável: {{ guardianship.guardian_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ guardianship.created_at }}</div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500">
                                Nenhuma responsabilidade cadastrada ainda.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atalhos rápidos -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Atalhos Rápidos</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link :href="route('people.create')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium text-center">
                            Cadastrar Pessoa
                        </Link>
                        <Link :href="route('people.index')" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-3 rounded-md text-sm font-medium text-center">
                            Ver Pessoas
                        </Link>
                        <Link :href="route('families.create')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md text-sm font-medium text-center">
                            Criar Família
                        </Link>
                        <Link :href="route('families.index')" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-3 rounded-md text-sm font-medium text-center">
                            Ver Famílias
                        </Link>
                        <Link :href="route('guardianships.create')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-md text-sm font-medium text-center">
                            Criar Responsável
                        </Link>
                        <Link :href="route('guardianships.index')" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-3 rounded-md text-sm font-medium text-center">
                            Ver Responsáveis
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
