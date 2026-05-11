<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-[#F8F6F3]">
        <!-- Mobile Sidebar Overlay -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 bg-black/50 z-40 xl:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed left-0 top-0 h-full w-[280px] bg-gradient-to-b from-[#1e3a5f] to-[#0f2744] shadow-xl z-50 transition-transform duration-300 xl:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="h-14 w-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">R</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Família Resgate</h1>
                        <p class="text-xs text-amber-400">Centro da igreja</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <div class="p-4 flex-1 overflow-y-auto">
                <!-- Main Items -->
                <nav class="space-y-2">
                    <Link
                        :href="route('familia.index')"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-white hover:bg-white/10 transition-colors"
                    >
                        <span class="text-xl">🏠</span>
                        <span class="font-medium">Centro Família</span>
                    </Link>

                    <Link
                        v-if="$page.props.shortcuts?.some(s => s.title === 'Secretaria')"
                        :href="$page.props.shortcuts?.find(s => s.title === 'Secretaria')?.route || '#'"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-white hover:bg-white/10 transition-colors"
                    >
                        <span class="text-xl">📋</span>
                        <span class="font-medium">Secretaria</span>
                    </Link>

                    <Link
                        v-if="$page.props.shortcuts?.some(s => s.title === 'Departamentos')"
                        :href="$page.props.shortcuts?.find(s => s.title === 'Departamentos')?.route || '#'"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-white hover:bg-white/10 transition-colors"
                    >
                        <span class="text-xl">🏛️</span>
                        <span class="font-medium">Departamentos</span>
                    </Link>

                    <Link
                        v-if="$page.props.shortcuts?.some(s => s.title === 'Acessos')"
                        :href="$page.props.shortcuts?.find(s => s.title === 'Acessos')?.route || '#'"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-white hover:bg-white/10 transition-colors"
                    >
                        <span class="text-xl">🛡️</span>
                        <span class="font-medium">Acessos</span>
                    </Link>

                    <Link
                        v-if="$page.props.shortcuts?.some(s => s.title === 'Perfis de Acesso')"
                        :href="$page.props.shortcuts?.find(s => s.title === 'Perfis de Acesso')?.route || '#'"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-white hover:bg-white/10 transition-colors"
                    >
                        <span class="text-xl">🪪</span>
                        <span class="font-medium">Perfis de Acesso</span>
                    </Link>
                </nav>

                <!-- Future Systems Section -->
                <div class="mt-8">
                    <h3 class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-3">
                        Futuros sistemas
                    </h3>
                    <nav class="space-y-2">
                        <button
                            disabled
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 transition-colors cursor-not-allowed"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">🙏</span>
                                <span>Central de Oração</span>
                            </div>
                            <span class="px-2 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-medium border border-amber-500/30">
                                Em breve
                            </span>
                        </button>

                        <button
                            disabled
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 transition-colors cursor-not-allowed"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">📖</span>
                                <span>Palavra do Dia</span>
                            </div>
                            <span class="px-2 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-medium border border-amber-500/30">
                                Em breve
                            </span>
                        </button>

                        <button
                            disabled
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 transition-colors cursor-not-allowed"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">💰</span>
                                <span>Financeiro</span>
                            </div>
                            <span class="px-2 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-medium border border-amber-500/30">
                                Em breve
                            </span>
                        </button>

                        <button
                            disabled
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 transition-colors cursor-not-allowed"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">☕</span>
                                <span>Cantina</span>
                            </div>
                            <span class="px-2 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-medium border border-amber-500/30">
                                Em breve
                            </span>
                        </button>

                        <button
                            disabled
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 transition-colors cursor-not-allowed"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">💒</span>
                                <span>Centro Pastoral</span>
                            </div>
                            <span class="px-2 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-medium border border-amber-500/30">
                                Em breve
                            </span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/10">
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-2">👨‍👩‍👧‍👦</div>
                    <p class="text-sm text-white/80 leading-relaxed">
                        Somos uma família que ama, acolhe e transforma vidas.
                    </p>
                    <div class="mt-2 text-2xl">❤️</div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="xl:pl-[280px]">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-30">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <!-- Mobile Menu Button -->
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="xl:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        >
                            <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Breadcrumb -->
                        <div class="flex items-center space-x-2">
                            <span class="text-xl">🏠</span>
                            <span class="text-lg font-semibold text-gray-800">Centro Família</span>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-4">
                            <!-- Notification Icon (Visual Only) -->
                            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <!-- Message Icon (Visual Only) -->
                            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </button>

                            <!-- User Menu -->
                            <div class="relative">
                                <button
                                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                >
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                                        <span class="text-white font-semibold">
                                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-sm font-semibold text-gray-800">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-xs text-gray-500">Bem-vindo(a)!</p>
                                    </div>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div
                                    v-show="showingNavigationDropdown"
                                    @click.away="showingNavigationDropdown = false"
                                    class="absolute right-0 mt-2 w-48 rounded-lg bg-white shadow-lg ring-1 ring-gray-200 z-50"
                                >
                                    <div class="py-1">
                                        <Link
                                            :href="route('profile.edit')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            Meu perfil
                                        </Link>
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-100"
                                        >
                                            Sair
                                        </Link>
                                    </div>
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
    </div>
</template>
