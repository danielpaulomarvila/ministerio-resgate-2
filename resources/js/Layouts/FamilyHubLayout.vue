<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-900">
        <!-- Header -->
        <header class="border-b border-gray-800 bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('familia.index')">
                            <ApplicationLogo class="h-8 w-auto text-amber-500" />
                        </Link>
                        <span class="ml-3 text-xl font-semibold text-white">Família Resgate</span>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="flex items-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                        >
                            <span class="mr-2">{{ $page.props.auth.user.name }}</span>
                            <svg
                                class="h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div
                            v-show="showingNavigationDropdown"
                            @click.away="showingNavigationDropdown = false"
                            class="absolute right-0 mt-2 w-48 rounded-lg bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                        >
                            <div class="py-1">
                                <Link
                                    :href="route('profile.edit')"
                                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white"
                                >
                                    Meu perfil
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="block w-full px-4 py-2 text-left text-sm text-red-400 hover:bg-gray-700 hover:text-red-300"
                                >
                                    Sair
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
