<script setup>
import FamilyHubLayout from '@/Layouts/FamilyHubLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
    layout: FamilyHubLayout,
});

const props = defineProps({
    greetingName: String,
    birthdayPeople: Array,
    birthdayCount: Number,
    shortcuts: Array,
    personalAlertsCount: Number,
});
</script>

<template>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        <div class="mb-8 rounded-3xl bg-gradient-to-br from-[#FFF8F0] to-[#F5EBE0] border border-amber-200/50 p-8 shadow-lg relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute right-0 top-0 w-1/3 h-full opacity-10">
                <div class="absolute right-8 top-8 text-8xl">✝️</div>
                <div class="absolute right-16 top-32 text-6xl">👨‍👩‍👧‍👦</div>
                <div class="absolute right-12 top-56 text-5xl">🏠</div>
            </div>

            <div class="relative z-10">
                <p class="text-sm font-semibold text-amber-600 uppercase tracking-wider mb-2">
                    CENTRO FAMÍLIA RESGATE
                </p>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    Bem-vindo ao centro da Família Resgate! 👋
                </h1>
                <p class="text-lg text-gray-700 mb-2">
                    Olá, {{ greetingName }}. Que bom ter você aqui.
                </p>
                <p class="text-lg text-amber-600 font-medium">
                    🧡 Sua casa dentro do ecossistema Resgate.
                </p>

                <!-- Verse on the right -->
                <div class="mt-6 p-4 bg-white/60 rounded-2xl border border-amber-200/50 inline-block">
                    <p class="text-gray-700 italic font-medium">
                        "Eu e a minha casa serviremos ao Senhor."
                    </p>
                    <p class="text-sm text-gray-500 mt-1">— Josué 24:15</p>
                </div>
            </div>
        </div>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 - Aniversariantes de hoje -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <span class="text-3xl mr-3">🎂</span>
                        <h2 class="text-xl font-bold text-gray-800">Aniversariantes de hoje</h2>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center">
                        <span class="text-pink-600 font-bold">{{ birthdayCount }}</span>
                    </div>
                </div>

                <div v-if="birthdayPeople.length > 0" class="space-y-3">
                    <div
                        v-for="person in birthdayPeople.slice(0, 5)"
                        :key="person.id"
                        class="flex items-center space-x-3 p-3 bg-pink-50 rounded-xl"
                    >
                        <span class="text-2xl">🎉</span>
                        <span class="text-gray-700 font-medium">{{ person.name }}</span>
                    </div>
                    <div v-if="birthdayCount > 5" class="text-sm text-gray-500 text-center mt-2">
                        E mais {{ birthdayCount - 5 }} aniversariantes
                    </div>
                </div>
                <div v-else class="text-center py-6">
                    <div class="text-5xl mb-3">🎈</div>
                    <p class="text-gray-600 mb-2">
                        Hoje não temos aniversariantes registrados.
                    </p>
                    <p class="text-sm text-gray-500">
                        Mas não deixe de parabenizar alguém especial! 💙
                    </p>
                </div>
            </div>

            <!-- Card 2 - Sistemas disponíveis -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-shadow lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <span class="text-3xl mr-3">🚀</span>
                        <h2 class="text-xl font-bold text-gray-800">Sistemas disponíveis</h2>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-bold">{{ shortcuts.length }}</span>
                    </div>
                </div>

                <div v-if="shortcuts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Secretaria -->
                    <Link
                        v-if="shortcuts.some(s => s.title === 'Secretaria')"
                        :href="shortcuts.find(s => s.title === 'Secretaria')?.route || '#'"
                        class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:shadow-md transition-all"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-3xl mb-2">👨‍💻</div>
                                <h3 class="font-bold text-gray-800">Secretaria</h3>
                                <p class="text-sm text-gray-600 mt-1">Cadastros, famílias, solicitações e mais</p>
                            </div>
                            <span class="text-blue-600 font-semibold text-sm">→ Acessar</span>
                        </div>
                    </Link>

                    <!-- Departamentos -->
                    <Link
                        v-if="shortcuts.some(s => s.title === 'Departamentos')"
                        :href="shortcuts.find(s => s.title === 'Departamentos')?.route || '#'"
                        class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 hover:shadow-md transition-all"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-3xl mb-2">🏛️</div>
                                <h3 class="font-bold text-gray-800">Departamentos</h3>
                                <p class="text-sm text-gray-600 mt-1">Ministérios, equipes e vínculos</p>
                            </div>
                            <span class="text-purple-600 font-semibold text-sm">→ Acessar</span>
                        </div>
                    </Link>

                    <!-- Acessos -->
                    <Link
                        v-if="shortcuts.some(s => s.title === 'Acessos')"
                        :href="shortcuts.find(s => s.title === 'Acessos')?.route || '#'"
                        class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 hover:shadow-md transition-all"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-3xl mb-2">🛡️</div>
                                <h3 class="font-bold text-gray-800">Acessos</h3>
                                <p class="text-sm text-gray-600 mt-1">Gestão de usuários e permissões</p>
                            </div>
                            <span class="text-green-600 font-semibold text-sm">→ Acessar</span>
                        </div>
                    </Link>

                    <!-- Perfis de Acesso -->
                    <Link
                        v-if="shortcuts.some(s => s.title === 'Perfis de Acesso')"
                        :href="shortcuts.find(s => s.title === 'Perfis de Acesso')?.route || '#'"
                        class="p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border border-amber-200 hover:shadow-md transition-all"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-3xl mb-2">🪪</div>
                                <h3 class="font-bold text-gray-800">Perfis de Acesso</h3>
                                <p class="text-sm text-gray-600 mt-1">Perfis e permissões do sistema</p>
                            </div>
                            <span class="text-amber-600 font-semibold text-sm">→ Acessar</span>
                        </div>
                    </Link>
                </div>
                <div v-else class="text-center py-6">
                    <div class="text-5xl mb-3">🔒</div>
                    <p class="text-gray-600">
                        Você ainda não possui sistemas internos liberados.
                    </p>
                </div>
            </div>

            <!-- Card 3 - Avisos e pendências -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <span class="text-3xl mr-3">🔔</span>
                        <h2 class="text-xl font-bold text-gray-800">Avisos e pendências</h2>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-green-600 text-xl">✓</span>
                    </div>
                </div>
                <div class="text-center py-6">
                    <div class="text-5xl mb-3">✅</div>
                    <p class="text-gray-600">
                        Nenhum aviso importante no momento.
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        Tudo em ordem!
                    </p>
                </div>
            </div>

            <!-- Card 4 - Central de Oração (Em breve) -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 relative opacity-75">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold border border-amber-300">
                        Em breve
                    </span>
                </div>
                <div class="flex items-center mb-4">
                    <span class="text-3xl mr-3">🙏</span>
                    <h2 class="text-xl font-bold text-gray-800">Central de Oração</h2>
                </div>
                <div class="text-center py-6">
                    <div class="text-5xl mb-3">🕊️</div>
                    <p class="text-gray-600">
                        Em breve, você poderá pedir oração e acompanhar pedidos pela Central de Oração.
                    </p>
                </div>
            </div>

            <!-- Card 5 - Palavra do Dia (Em breve) -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 relative opacity-75">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold border border-amber-300">
                        Em breve
                    </span>
                </div>
                <div class="flex items-center mb-4">
                    <span class="text-3xl mr-3">📖</span>
                    <h2 class="text-xl font-bold text-gray-800">Palavra do Dia</h2>
                </div>
                <div class="text-center py-6">
                    <div class="text-5xl mb-3">📜</div>
                    <p class="text-gray-600">
                        Em breve, uma palavra de fé e encorajamento aparecerá aqui todos os dias para você.
                    </p>
                </div>
            </div>

            <!-- Card 6 - Mais sistemas (Em breve) -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 relative opacity-75">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold border border-amber-300">
                        Em breve
                    </span>
                </div>
                <div class="flex items-center mb-4">
                    <span class="text-3xl mr-3">✨</span>
                    <h2 class="text-xl font-bold text-gray-800">Mais sistemas</h2>
                </div>
                <div class="text-center py-6">
                    <div class="text-5xl mb-3">🎁</div>
                    <p class="text-gray-600">
                        Novos sistemas e funcionalidades estarão disponíveis em breve para abençoar ainda mais você.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <div class="inline-block bg-white/80 rounded-2xl p-6 shadow-md border border-amber-200/50">
                <p class="text-lg font-medium text-gray-700 italic mb-2">
                    "Eu e a minha casa serviremos ao Senhor."
                </p>
                <p class="text-sm text-gray-500 mb-4">— Josué 24:15</p>
                <p class="text-amber-600 font-semibold">
                    Bem-vindo à sua casa espiritual! 🏠
                </p>
            </div>
        </div>
    </div>
</template>
