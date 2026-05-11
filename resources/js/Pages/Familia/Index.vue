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
        <div class="mb-8 rounded-2xl bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 p-8">
            <h1 class="text-3xl font-bold text-white mb-2">Centro Família Resgate</h1>
            <p class="text-lg text-amber-500 mb-4">Bem-vindo ao centro da Família Resgate</p>
            <p class="text-gray-300">Olá, {{ greetingName }}. Que bom ter você aqui.</p>
            <p class="text-sm text-gray-400 mt-2">Sua casa dentro do ecossistema Resgate.</p>
        </div>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Card de Aniversariantes -->
            <div class="rounded-2xl bg-gray-900/50 border border-gray-800 p-6 hover:border-gray-700 transition-colors">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-lg bg-amber-500/20 flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Aniversariantes de hoje</h2>
                </div>
                <div v-if="birthdayPeople.length > 0" class="space-y-3">
                    <div
                        v-for="person in birthdayPeople"
                        :key="person.id"
                        class="flex items-center justify-between py-2 px-3 rounded-lg bg-gray-800/50 border border-gray-700"
                    >
                        <span class="text-gray-200">{{ person.name }}</span>
                        <span class="text-amber-500 font-medium">{{ person.birth_date }}</span>
                    </div>
                    <div v-if="birthdayCount > 5" class="mt-3 text-sm text-gray-400 text-center">
                        E mais {{ birthdayCount - 5 }} aniversariantes
                    </div>
                </div>
                <div v-else class="text-gray-400 text-center py-4">
                    Hoje não temos aniversariantes registrados.
                </div>
            </div>

            <!-- Card de Sistemas Disponíveis -->
            <div class="rounded-2xl bg-gray-900/50 border border-gray-800 p-6 hover:border-gray-700 transition-colors">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-lg bg-blue-500/20 flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Sistemas disponíveis</h2>
                </div>
                <div v-if="shortcuts.length > 0" class="space-y-3">
                    <Link
                        v-for="shortcut in shortcuts"
                        :key="shortcut.title"
                        :href="shortcut.route"
                        class="block p-4 rounded-xl bg-gray-800/50 border border-gray-700 hover:bg-gray-800 hover:border-amber-500/50 transition-all"
                    >
                        <h3 class="text-lg font-medium text-white">{{ shortcut.title }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ shortcut.description }}</p>
                    </Link>
                </div>
                <div v-else class="text-gray-400 text-center py-4">
                    Nenhum sistema interno liberado para este usuário.
                </div>
            </div>

            <!-- Card de Avisos e Pendências -->
            <div class="rounded-2xl bg-gray-900/50 border border-gray-800 p-6 hover:border-gray-700 transition-colors">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-lg bg-green-500/20 flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Avisos e pendências</h2>
                </div>
                <div class="text-gray-400 text-center py-4">
                    Nenhum aviso importante no momento.
                </div>
            </div>

            <!-- Card Futuro de Oração -->
            <div class="rounded-2xl bg-gray-900/30 border border-gray-800 p-6 relative">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-500 text-xs font-medium border border-amber-500/30">
                        Em breve
                    </span>
                </div>
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-lg bg-purple-500/20 flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Oração</h2>
                </div>
                <div class="text-gray-400 text-center py-4">
                    Em breve, você poderá pedir oração e acompanhar pedidos pela Central de Oração.
                </div>
            </div>

            <!-- Card Futuro de Palavra do Dia -->
            <div class="rounded-2xl bg-gray-900/30 border border-gray-800 p-6 relative">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-500 text-xs font-medium border border-amber-500/30">
                        Em breve
                    </span>
                </div>
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-lg bg-amber-500/20 flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Palavra do dia</h2>
                </div>
                <div class="text-gray-400 text-center py-4">
                    Em breve, uma palavra de fé e encorajamento aparecerá aqui.
                </div>
            </div>
        </div>
    </div>
</template>
