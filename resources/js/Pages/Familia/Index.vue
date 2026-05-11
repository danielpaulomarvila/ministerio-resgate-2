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
        <!-- Saudação Principal -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Bem-vindo ao centro da Família Resgate</h1>
            <p class="mt-2 text-lg text-gray-300">Olá, {{ greetingName }}. Que bom ter você aqui.</p>
        </div>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Card de Aniversariantes -->
            <div class="rounded-lg bg-gray-800 p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4">Aniversariantes de hoje</h2>
                <div v-if="birthdayPeople.length > 0" class="space-y-2">
                    <div
                        v-for="person in birthdayPeople"
                        :key="person.id"
                        class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0"
                    >
                        <span class="text-gray-300">{{ person.name }}</span>
                        <span class="text-amber-500">{{ person.birth_date }}</span>
                    </div>
                    <div v-if="birthdayCount > 5" class="mt-3 text-sm text-gray-400">
                        E mais {{ birthdayCount - 5 }} aniversariantes
                    </div>
                </div>
                <div v-else class="text-gray-400">
                    Hoje não temos aniversariantes registrados.
                </div>
            </div>

            <!-- Card de Sistemas Disponíveis -->
            <div class="rounded-lg bg-gray-800 p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4">Sistemas disponíveis</h2>
                <div v-if="shortcuts.length > 0" class="space-y-3">
                    <Link
                        v-for="shortcut in shortcuts"
                        :key="shortcut.title"
                        :href="shortcut.route"
                        class="block p-4 rounded-lg bg-gray-700 hover:bg-gray-600 transition-colors"
                    >
                        <h3 class="text-lg font-medium text-white">{{ shortcut.title }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ shortcut.description }}</p>
                    </Link>
                </div>
                <div v-else class="text-gray-400">
                    Você ainda não possui sistemas internos liberados.
                </div>
            </div>

            <!-- Card de Avisos e Pendências -->
            <div class="rounded-lg bg-gray-800 p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4">Avisos e pendências</h2>
                <div class="text-gray-400">
                    Nenhum aviso importante no momento.
                </div>
            </div>

            <!-- Card Futuro de Oração -->
            <div class="rounded-lg bg-gray-800 p-6 border border-gray-700 opacity-75">
                <h2 class="text-xl font-semibold text-white mb-4">Oração</h2>
                <div class="text-gray-400">
                    Em breve, você poderá pedir oração e acompanhar pedidos pela Central de Oração.
                </div>
            </div>

            <!-- Card Futuro de Palavra do Dia -->
            <div class="rounded-lg bg-gray-800 p-6 border border-gray-700 opacity-75">
                <h2 class="text-xl font-semibold text-white mb-4">Palavra do dia</h2>
                <div class="text-gray-400">
                    Em breve, uma palavra de fé e encorajamento aparecerá aqui.
                </div>
            </div>
        </div>
    </div>
</template>
