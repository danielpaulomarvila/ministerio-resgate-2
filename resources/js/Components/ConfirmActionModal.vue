<script setup>
/**
 * Componente Modal de Confirmação de Ação
 * 
 * Modal reutilizável para confirmar ações sensíveis como exclusão
 * Não usa window.confirm/alert nativo do navegador
 * Visual limpo, moderno e coerente com o sistema Ministério Resgate
 * 
 * Props:
 * - show: boolean - Controla visibilidade do modal
 * - title: string - Título do modal
 * - message: string - Mensagem explicativa
 * - confirmText: string - Texto do botão de confirmação (padrão: "Confirmar")
 * - cancelText: string - Texto do botão de cancelamento (padrão: "Cancelar")
 * - confirmButtonClass: string - Classe CSS do botão de confirmação (padrão: vermelho)
 * 
 * Events:
 * - confirm: Disparado ao clicar em confirmar
 * - cancel: Disparado ao clicar em cancelar ou fechar
 */

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Confirmar ação',
    },
    message: {
        type: String,
        default: 'Tem certeza que deseja realizar esta ação?',
    },
    confirmText: {
        type: String,
        default: 'Confirmar',
    },
    cancelText: {
        type: String,
        default: 'Cancelar',
    },
    confirmButtonClass: {
        type: String,
        default: 'bg-red-600 hover:bg-red-700 text-white',
    },
});

const emit = defineEmits(['confirm', 'cancel']);

/**
 * Confirma a ação
 */
const confirm = () => {
    emit('confirm');
};

/**
 * Cancela a ação
 */
const cancel = () => {
    emit('cancel');
};
</script>

<template>
    <!-- Modal com fundo escurecido -->
    <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @click="cancel"
        >
            <!-- Fundo escurecido -->
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>

            <!-- Card centralizado -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="show"
                    class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6"
                    @click.stop
                >
                    <!-- Título -->
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">
                        {{ title }}
                    </h3>

                    <!-- Mensagem -->
                    <p class="text-sm text-slate-600 mb-6">
                        {{ message }}
                    </p>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="cancel"
                            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            {{ cancelText }}
                        </button>
                        <button
                            type="button"
                            @click="confirm"
                            class="rounded-lg px-4 py-2 text-sm font-semibold transition-colors"
                            :class="confirmButtonClass"
                        >
                            {{ confirmText }}
                        </button>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>
