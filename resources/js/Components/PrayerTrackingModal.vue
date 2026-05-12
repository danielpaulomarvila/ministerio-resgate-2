<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    initialCode: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['close']);

const requestStorageKey = 'familia_resgate_prayer_requests';

const trackingCode = ref('');
const foundRequest = ref(null);
const searched = ref(false);

const timelineSteps = [
    { status: 'received', label: 'Pedido recebido' },
    { status: 'routed', label: 'Encaminhado para intercessão' },
    { status: 'praying', label: 'Em oração' },
    { status: 'pastoral_notified', label: 'Pastoral acionada' },
    { status: 'waiting', label: 'Aguardando atualização' },
    { status: 'answered', label: 'Respondido / concluído' },
    { status: 'archived', label: 'Arquivado' },
];

const visibilityLabels = {
    intercession_team: 'Apenas equipe de intercessão',
    pastoral_intercession: 'Pastoral e intercessão',
    pastoral_only: 'Somente pastoral',
    shared_anonymous: 'Compartilhamento anônimo com a rede de oração',
};

const statusLabel = computed(() => {
    if (!foundRequest.value) {
        return '';
    }

    const latest = foundRequest.value.timeline?.at(-1);
    return latest?.label ?? 'Recebido';
});

watch(
    () => props.show,
    (showing) => {
        if (showing) {
            trackingCode.value = props.initialCode;
            foundRequest.value = null;
            searched.value = false;

            if (props.initialCode) {
                searchRequest();
            }
        }
    },
);

watch(
    () => props.initialCode,
    (nextCode) => {
        if (props.show && nextCode) {
            trackingCode.value = nextCode;
            searchRequest();
        }
    },
);

function closeModal() {
    emit('close');
}

function loadStoredRequests() {
    if (typeof window === 'undefined') {
        return [];
    }

    return JSON.parse(window.localStorage.getItem(requestStorageKey) ?? '[]');
}

// Área de acompanhamento por código.
// Busca somente no armazenamento temporário do navegador até existir endpoint público de acompanhamento.
function searchRequest() {
    const normalizedCode = trackingCode.value.trim().toUpperCase();
    const request = loadStoredRequests().find(
        (storedRequest) => storedRequest.tracking_code.toUpperCase() === normalizedCode,
    );

    foundRequest.value = request ?? null;
    searched.value = true;
}

function hasTimelineStatus(status) {
    return foundRequest.value?.timeline?.some((event) => event.status === status) ?? false;
}

function isTimelineStepActive(status) {
    const activeStatuses = new Set(foundRequest.value?.timeline?.map((event) => event.status) ?? []);

    if (status === 'routed') {
        return activeStatuses.has('routed') || activeStatuses.has('pastoral_notified');
    }

    return activeStatuses.has(status);
}

function stepDate(status) {
    const event = foundRequest.value?.timeline?.find((timelineEvent) => timelineEvent.status === status);

    if (!event && status === 'routed') {
        return foundRequest.value?.timeline?.find((timelineEvent) => timelineEvent.status === 'pastoral_notified')?.date;
    }

    return event?.date;
}

function formatDate(date) {
    if (!date) {
        return 'Próxima etapa';
    }

    return new Intl.DateTimeFormat('pt-PT', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(date));
}
</script>

<template>
    <Teleport to="body">
        <Transition name="tracking-fade">
            <div v-if="show" class="tracking-backdrop" role="presentation" @click.self="closeModal">
                <section
                    class="tracking-modal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="tracking-title"
                >
                    <!-- Modal de Acompanhar Oração, separado do envio para manter a Home compacta e sem scroll. -->
                    <header class="tracking-header">
                        <div>
                            <p>Já tem um código?</p>
                            <h2 id="tracking-title">Acompanhar Oração</h2>
                        </div>
                        <button type="button" class="tracking-close" aria-label="Fechar acompanhamento" @click="closeModal">
                            ×
                        </button>
                    </header>

                    <div class="tracking-body">
                        <p class="tracking-intro">Digite o código recebido ao enviar seu pedido.</p>

                        <form class="tracking-search" @submit.prevent="searchRequest">
                            <input
                                v-model="trackingCode"
                                type="text"
                                placeholder="ORA-8F3K-27LQ"
                                autocomplete="off"
                            />
                            <button type="submit">Buscar pedido</button>
                        </form>

                        <article v-if="foundRequest" class="request-card">
                            <div class="request-card-header">
                                <div>
                                    <small>Código</small>
                                    <strong>{{ foundRequest.tracking_code }}</strong>
                                </div>
                                <span>{{ statusLabel }}</span>
                            </div>

                            <div class="request-meta">
                                <p><strong>Categoria:</strong> {{ foundRequest.category }}</p>
                                <p><strong>Privacidade:</strong> {{ visibilityLabels[foundRequest.visibility] }}</p>
                                <p><strong>Urgência:</strong> {{ foundRequest.urgency }}</p>
                                <p><strong>Envio:</strong> {{ formatDate(foundRequest.created_at) }}</p>
                            </div>

                            <p class="care-message">
                                Seu pedido segue em cuidado. Dados pessoais não são exibidos neste acompanhamento público.
                            </p>

                            <ol class="timeline">
                                <li
                                    v-for="step in timelineSteps"
                                    :key="step.status"
                                    :class="{ active: isTimelineStepActive(step.status), hiddenStep: step.status === 'pastoral_notified' && !hasTimelineStatus('pastoral_notified') }"
                                >
                                    <span></span>
                                    <div>
                                        <strong>{{ step.label }}</strong>
                                        <small>{{ formatDate(stepDate(step.status)) }}</small>
                                    </div>
                                </li>
                            </ol>
                        </article>

                        <div v-else-if="searched" class="not-found">
                            <strong>Não encontramos um pedido com este código.</strong>
                            <p>Confira se digitou corretamente e tente buscar novamente.</p>
                        </div>

                        <footer class="tracking-actions">
                            <button v-if="searched" type="button" class="secondary-action" @click="trackingCode = ''; foundRequest = null; searched = false">
                                Buscar novamente
                            </button>
                            <button type="button" class="ghost-action" @click="closeModal">
                                Fechar
                            </button>
                        </footer>
                    </div>
                </section>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.tracking-backdrop {
    position: fixed;
    z-index: 60;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 24px;
    background: rgba(2, 8, 23, 0.48);
    backdrop-filter: blur(12px);
}

.tracking-modal {
    width: min(720px, 96vw);
    max-height: min(90vh, 760px);
    overflow: auto;
    border: 1px solid rgba(217, 164, 65, 0.48);
    border-radius: 26px;
    color: #f8f2e6;
    background:
        radial-gradient(circle at 20% 0%, rgba(246, 198, 91, 0.2), transparent 26%),
        linear-gradient(135deg, #071b3a, #06152f 68%, #020817);
    box-shadow: 0 32px 80px rgba(2, 8, 23, 0.42);
}

.tracking-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    padding: 26px 30px 18px;
    border-bottom: 1px solid rgba(246, 198, 91, 0.18);
}

.tracking-header p {
    margin: 0 0 5px;
    color: #f2c15b;
    font-size: 0.78rem;
    font-weight: 900;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.tracking-header h2 {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(1.6rem, 2vw, 2.1rem);
}

.tracking-close {
    display: grid;
    width: 42px;
    height: 42px;
    place-items: center;
    border: 1px solid rgba(246, 198, 91, 0.34);
    border-radius: 14px;
    color: #f8f2e6;
    background: rgba(255, 248, 234, 0.07);
    font-size: 1.7rem;
    line-height: 1;
    cursor: pointer;
    transition: transform 250ms ease, border-color 250ms ease;
}

.tracking-close:hover {
    transform: translateY(-2px);
    border-color: #f2c15b;
}

.tracking-body {
    padding: 22px 30px 30px;
}

.tracking-intro {
    margin: 0 0 16px;
    color: rgba(248, 242, 230, 0.82);
}

.tracking-search {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
}

.tracking-search input {
    min-height: 48px;
    border: 1px solid rgba(255, 248, 234, 0.22);
    border-radius: 14px;
    color: #f8f2e6;
    background: rgba(255, 248, 234, 0.08);
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.tracking-search input:focus {
    border-color: #f2c15b;
    outline: none;
    box-shadow: 0 0 0 3px rgba(246, 198, 91, 0.16);
}

.tracking-search button,
.secondary-action,
.ghost-action {
    min-height: 48px;
    border-radius: 14px;
    padding: 0 18px;
    font-weight: 900;
    cursor: pointer;
    transition: transform 250ms ease, box-shadow 250ms ease, border-color 250ms ease;
}

.tracking-search button {
    border: 1px solid rgba(246, 198, 91, 0.72);
    color: #08162e;
    background: linear-gradient(135deg, #f6c65b, #f59e0b 72%, #d97706);
    box-shadow: 0 16px 28px rgba(245, 158, 11, 0.2);
}

.secondary-action,
.ghost-action {
    border: 1px solid rgba(246, 198, 91, 0.36);
    color: #f8f2e6;
    background: rgba(255, 248, 234, 0.06);
}

.tracking-search button:hover,
.secondary-action:hover,
.ghost-action:hover {
    transform: translateY(-3px);
    border-color: #f2c15b;
    box-shadow: 0 18px 30px rgba(217, 164, 65, 0.18);
}

.request-card,
.not-found {
    margin-top: 18px;
    padding: 18px;
    border: 1px solid rgba(246, 198, 91, 0.34);
    border-radius: 18px;
    background: rgba(255, 248, 234, 0.07);
}

.request-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.request-card-header small {
    display: block;
    color: #f2c15b;
    font-size: 0.72rem;
    font-weight: 900;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.request-card-header strong {
    color: #fff8ea;
    font-size: 1.25rem;
    letter-spacing: 0.08em;
}

.request-card-header span {
    border-radius: 999px;
    padding: 8px 12px;
    color: #08162e;
    background: #f6c65b;
    font-size: 0.78rem;
    font-weight: 900;
}

.request-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px 16px;
    margin-top: 18px;
}

.request-meta p,
.care-message,
.not-found p {
    margin: 0;
    color: rgba(248, 242, 230, 0.82);
    line-height: 1.5;
}

.care-message {
    margin-top: 16px;
}

.timeline {
    display: grid;
    gap: 0;
    margin: 18px 0 0;
    padding: 0;
    list-style: none;
}

.timeline li {
    position: relative;
    display: grid;
    grid-template-columns: 26px 1fr;
    gap: 12px;
    padding-bottom: 14px;
    color: rgba(248, 242, 230, 0.55);
}

.timeline li::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 24px;
    bottom: 0;
    width: 1px;
    background: rgba(246, 198, 91, 0.2);
}

.timeline li:last-child::before {
    display: none;
}

.timeline li.hiddenStep {
    display: none;
}

.timeline li > span {
    width: 24px;
    height: 24px;
    border: 1px solid rgba(246, 198, 91, 0.4);
    border-radius: 50%;
    background: rgba(255, 248, 234, 0.06);
}

.timeline li.active {
    color: #fff8ea;
}

.timeline li.active > span {
    border-color: #f6c65b;
    background: radial-gradient(circle, #f6c65b 0 32%, rgba(246, 198, 91, 0.18) 34%);
    box-shadow: 0 0 18px rgba(246, 198, 91, 0.28);
}

.timeline strong {
    display: block;
    font-size: 0.92rem;
}

.timeline small {
    color: rgba(248, 242, 230, 0.62);
}

.not-found strong {
    display: block;
    margin-bottom: 6px;
    color: #ffd27a;
}

.tracking-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 18px;
}

.tracking-fade-enter-active,
.tracking-fade-leave-active {
    transition: opacity 220ms ease;
}

.tracking-fade-enter-from,
.tracking-fade-leave-to {
    opacity: 0;
}

.tracking-fade-enter-active .tracking-modal,
.tracking-fade-leave-active .tracking-modal {
    transition: transform 260ms ease, opacity 260ms ease;
}

.tracking-fade-enter-from .tracking-modal,
.tracking-fade-leave-to .tracking-modal {
    opacity: 0;
    transform: scale(0.96) translateY(10px);
}

@media (max-width: 680px) {
    .tracking-backdrop {
        padding: 12px;
    }

    .tracking-header,
    .tracking-body {
        padding-inline: 18px;
    }

    .tracking-search,
    .request-meta {
        grid-template-columns: 1fr;
    }

    .tracking-actions {
        flex-direction: column;
    }
}
</style>
