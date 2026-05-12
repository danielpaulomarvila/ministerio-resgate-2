<script setup>
import { computed, reactive, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close', 'track']);

const requestStorageKey = 'familia_resgate_prayer_requests';

const categories = [
    'Família',
    'Saúde',
    'Vida espiritual',
    'Trabalho / finanças',
    'Casamento / relacionamento',
    'Filhos',
    'Ansiedade / tristeza',
    'Gratidão / testemunho',
    'Libertação / direção',
    'Outro',
];

const visibilityOptions = [
    { value: 'intercession_team', label: 'Apenas equipe de intercessão' },
    { value: 'pastoral_intercession', label: 'Pastoral e intercessão' },
    { value: 'pastoral_only', label: 'Somente pastoral' },
    { value: 'shared_anonymous', label: 'Pode ser compartilhado anonimamente com a rede de oração' },
];

const urgencyOptions = ['Normal', 'Importante', 'Urgente'];

const form = reactive({
    submissionType: 'identified',
    name: '',
    phone: '',
    email: '',
    request_text: '',
    category: 'Família',
    visibility: 'intercession_team',
    urgency: 'Normal',
    allow_contact: false,
    consent: false,
});

const errors = ref({});
const confirmation = ref(null);
const copied = ref(false);

const isAnonymous = computed(() => form.submissionType === 'anonymous');

watch(isAnonymous, (anonymous) => {
    if (anonymous) {
        form.name = '';
        form.phone = '';
        form.email = '';
        form.allow_contact = false;
    }
});

watch(
    () => props.show,
    (showing) => {
        if (!showing) {
            copied.value = false;
        }
    },
);

function resetForm() {
    form.submissionType = 'identified';
    form.name = '';
    form.phone = '';
    form.email = '';
    form.request_text = '';
    form.category = 'Família';
    form.visibility = 'intercession_team';
    form.urgency = 'Normal';
    form.allow_contact = false;
    form.consent = false;
    errors.value = {};
    confirmation.value = null;
    copied.value = false;
}

function closeModal() {
    resetForm();
    emit('close');
}

function validateForm() {
    const nextErrors = {};

    if (!isAnonymous.value && !form.name.trim()) {
        nextErrors.name = 'Informe seu nome ou escolha o envio anônimo.';
    }

    if (!form.request_text.trim()) {
        nextErrors.request_text = 'Escreva o motivo do pedido de oração.';
    }

    if (!form.consent) {
        nextErrors.consent = 'Confirme o cuidado e encaminhamento do pedido.';
    }

    errors.value = nextErrors;
    return Object.keys(nextErrors).length === 0;
}

function loadStoredRequests() {
    if (typeof window === 'undefined') {
        return [];
    }

    return JSON.parse(window.localStorage.getItem(requestStorageKey) ?? '[]');
}

function saveStoredRequests(requests) {
    if (typeof window === 'undefined') {
        return;
    }

    window.localStorage.setItem(requestStorageKey, JSON.stringify(requests));
}

function randomCodeSegment() {
    const source = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    const values = new Uint32Array(4);

    if (typeof window !== 'undefined' && window.crypto) {
        window.crypto.getRandomValues(values);
    } else {
        values.forEach((_, index) => {
            values[index] = Math.floor(Math.random() * source.length);
        });
    }

    return Array.from(values, (value) => source[value % source.length]).join('');
}

function generateTrackingCode() {
    const existingCodes = new Set(loadStoredRequests().map((request) => request.tracking_code));
    let code = `ORA-${randomCodeSegment()}-${randomCodeSegment()}`;

    while (existingCodes.has(code)) {
        code = `ORA-${randomCodeSegment()}-${randomCodeSegment()}`;
    }

    return code;
}

function routePrayerRequest() {
    const routes = [];

    if (form.visibility !== 'pastoral_only') {
        routes.push('intercession');
    }

    if (['pastoral_intercession', 'pastoral_only'].includes(form.visibility)) {
        routes.push('pastoral');
    }

    if (form.visibility === 'shared_anonymous') {
        routes.push('prayer_network');
    }

    return routes;
}

function buildTimeline(now, routedTo) {
    const timeline = [
        { status: 'received', label: 'Pedido recebido', date: now },
        {
            status: routedTo.includes('pastoral') && !routedTo.includes('intercession') ? 'pastoral_notified' : 'routed',
            label: routedTo.includes('pastoral') && !routedTo.includes('intercession')
                ? 'Encaminhado para cuidado pastoral'
                : 'Encaminhado para intercessão',
            date: now,
        },
    ];

    if (routedTo.includes('pastoral') && routedTo.includes('intercession')) {
        timeline.push({ status: 'pastoral_notified', label: 'Pastoral acionada', date: now });
    }

    timeline.push({ status: 'praying', label: 'Em oração', date: now });

    return timeline;
}

// Estrutura temporária em localStorage até existir API real.
// O formato já separa privacidade, roteamento e status para futura integração com intercessão/pastoral.
function submitPrayerRequest() {
    if (!validateForm()) {
        return;
    }

    const now = new Date().toISOString();
    const routedTo = routePrayerRequest();
    const trackingCode = generateTrackingCode();
    const priority = form.urgency === 'Urgente'
        ? 'urgent'
        : form.urgency === 'Importante'
            ? 'important'
            : 'normal';

    const requestPayload = {
        tracking_code: trackingCode,
        is_anonymous: isAnonymous.value,
        name: isAnonymous.value ? null : form.name.trim(),
        phone: isAnonymous.value ? null : form.phone.trim() || null,
        email: isAnonymous.value ? null : form.email.trim() || null,
        category: form.category,
        urgency: form.urgency,
        visibility: form.visibility,
        allow_contact: !isAnonymous.value && form.allow_contact,
        consent: form.consent,
        request_text: form.request_text.trim(),
        status: 'praying',
        priority,
        routed_to: routedTo,
        created_at: now,
        updated_at: now,
        timeline: buildTimeline(now, routedTo),
    };

    saveStoredRequests([requestPayload, ...loadStoredRequests()]);
    confirmation.value = requestPayload;
}

async function copyTrackingCode() {
    if (!confirmation.value || typeof navigator === 'undefined') {
        return;
    }

    await navigator.clipboard?.writeText(confirmation.value.tracking_code);
    copied.value = true;
}

function openTracking() {
    if (!confirmation.value) {
        return;
    }

    emit('track', confirmation.value.tracking_code);
    closeModal();
}
</script>

<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div v-if="show" class="modal-backdrop" role="presentation" @click.self="closeModal">
                <section
                    class="prayer-modal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="prayer-request-title"
                >
                    <!-- Modal público de Pedido de Oração com suporte a anonimato, privacidade e código de acompanhamento. -->
                    <header class="modal-header">
                        <div>
                            <p>Pedido de oração</p>
                            <h2 id="prayer-request-title">
                                Conte conosco em oração
                            </h2>
                        </div>
                        <button type="button" class="modal-close" aria-label="Fechar pedido de oração" @click="closeModal">
                            ×
                        </button>
                    </header>

                    <div v-if="confirmation" class="success-state">
                        <span class="success-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </span>
                        <h3>Pedido enviado com sucesso.</h3>
                        <p>
                            Recebemos seu pedido com carinho. Nossa equipe de intercessão estará orando por você.
                        </p>

                        <div class="tracking-code-box">
                            <small>Código de acompanhamento</small>
                            <strong>{{ confirmation.tracking_code }}</strong>
                        </div>

                        <div class="success-actions">
                            <button type="button" class="secondary-action" @click="copyTrackingCode">
                                {{ copied ? 'Código copiado' : 'Copiar código' }}
                            </button>
                            <button type="button" class="primary-action" @click="openTracking">
                                Acompanhar pedido
                            </button>
                            <button type="button" class="ghost-action" @click="closeModal">
                                Fechar
                            </button>
                        </div>
                    </div>

                    <form v-else class="prayer-form" @submit.prevent="submitPrayerRequest">
                        <p class="privacy-note">
                            Seu pedido será tratado com cuidado e respeito. Você pode escolher quem poderá vê-lo e também pode enviar de forma anônima.
                        </p>

                        <fieldset class="type-card-field">
                            <legend>Tipo de envio</legend>
                            <div class="type-card-grid">
                                <label class="type-card" :class="{ active: form.submissionType === 'identified' }">
                                    <input v-model="form.submissionType" type="radio" value="identified" />
                                    <span class="type-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                            <path d="M4 21a8 8 0 0 1 16 0" />
                                        </svg>
                                    </span>
                                    <span class="type-content">
                                        <strong>Quero me identificar</strong>
                                        <small>Meu nome poderá ser usado apenas pela equipe autorizada.</small>
                                    </span>
                                    <span class="radio-dot" aria-hidden="true"></span>
                                </label>

                                <label class="type-card" :class="{ active: form.submissionType === 'anonymous' }">
                                    <input v-model="form.submissionType" type="radio" value="anonymous" />
                                    <span class="type-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 3 20 6v6c0 5-3.2 8-8 9-4.8-1-8-4-8-9V6Z" />
                                            <path d="M9.5 12.5 11.2 14 15 10" />
                                        </svg>
                                    </span>
                                    <span class="type-content">
                                        <strong>Quero enviar anônimo</strong>
                                        <small>Seu nome não será solicitado nem exibido.</small>
                                    </span>
                                    <span class="radio-dot" aria-hidden="true"></span>
                                </label>
                            </div>
                        </fieldset>

                        <p v-if="isAnonymous" class="anonymous-note">
                            Você pode enviar este pedido de forma anônima. Mesmo sem se identificar, nossa equipe irá interceder com cuidado e respeito.
                        </p>

                        <div class="form-grid">
                            <label>
                                <span>Nome</span>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    :disabled="isAnonymous"
                                    :required="!isAnonymous"
                                    placeholder="Seu nome"
                                />
                                <small v-if="errors.name">{{ errors.name }}</small>
                            </label>

                            <label>
                                <span>WhatsApp/telefone</span>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    :disabled="isAnonymous"
                                    placeholder="Opcional"
                                />
                            </label>

                            <label>
                                <span>E-mail</span>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    :disabled="isAnonymous"
                                    placeholder="Opcional"
                                />
                            </label>

                            <label>
                                <span>Categoria</span>
                                <select v-model="form.category">
                                    <option v-for="category in categories" :key="category" :value="category">
                                        {{ category }}
                                    </option>
                                </select>
                            </label>

                            <label>
                                <span>Nível de privacidade</span>
                                <select v-model="form.visibility">
                                    <option v-for="option in visibilityOptions" :key="option.value" :value="option.value">
                                        {{ option.label }}
                                    </option>
                                </select>
                            </label>

                            <label>
                                <span>Urgência</span>
                                <select v-model="form.urgency">
                                    <option v-for="urgency in urgencyOptions" :key="urgency" :value="urgency">
                                        {{ urgency }}
                                    </option>
                                </select>
                            </label>
                        </div>

                        <label class="full-field">
                            <span>Pedido de oração</span>
                            <textarea
                                v-model="form.request_text"
                                required
                                rows="4"
                                placeholder="Escreva aqui o motivo pelo qual deseja oração..."
                            ></textarea>
                            <small v-if="errors.request_text">{{ errors.request_text }}</small>
                        </label>

                        <label class="check-row" :class="{ muted: isAnonymous }">
                            <input v-model="form.allow_contact" type="checkbox" :disabled="isAnonymous" />
                            <span>Autorizo que a igreja entre em contato comigo sobre este pedido.</span>
                        </label>

                        <label class="check-row">
                            <input v-model="form.consent" type="checkbox" required />
                            <span>Entendo que este pedido será tratado com cuidado e encaminhado conforme a privacidade escolhida.</span>
                        </label>
                        <small v-if="errors.consent" class="consent-error">{{ errors.consent }}</small>

                        <footer class="modal-actions">
                            <button type="button" class="secondary-action" @click="closeModal">
                                Cancelar
                            </button>
                            <button type="submit" class="primary-action">
                                Enviar pedido
                            </button>
                        </footer>
                    </form>
                </section>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-backdrop {
    position: fixed;
    z-index: 60;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 18px;
    background: rgba(2, 8, 23, 0.5);
    backdrop-filter: blur(12px);
}

.prayer-modal {
    width: min(860px, 96vw);
    max-height: min(88vh, 720px);
    overflow: auto;
    border: 1px solid rgba(217, 164, 65, 0.48);
    border-radius: 26px;
    color: #f8f2e6;
    background:
        radial-gradient(circle at 88% 10%, rgba(246, 198, 91, 0.22), transparent 28%),
        linear-gradient(135deg, #071b3a, #06152f 68%, #020817);
    box-shadow: 0 32px 80px rgba(2, 8, 23, 0.42);
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 26px 16px;
    border-bottom: 1px solid rgba(246, 198, 91, 0.18);
}

.modal-header p {
    margin: 0 0 5px;
    color: #f2c15b;
    font-size: 0.78rem;
    font-weight: 900;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.modal-header h2 {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(1.45rem, 1.75vw, 1.9rem);
}

.modal-close {
    display: grid;
    width: 38px;
    height: 38px;
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

.modal-close:hover {
    transform: translateY(-2px);
    border-color: #f2c15b;
}

.prayer-form,
.success-state {
    padding: 18px 26px 24px;
}

.privacy-note,
.anonymous-note {
    margin: 0 0 12px;
    color: rgba(248, 242, 230, 0.82);
    line-height: 1.55;
}

.anonymous-note {
    padding: 12px 14px;
    border: 1px solid rgba(246, 198, 91, 0.34);
    border-radius: 14px;
    background: rgba(246, 198, 91, 0.09);
}

.type-card-field {
    margin: 0 0 12px;
    padding: 0;
    border: 0;
}

.type-card-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.type-card-field legend {
    margin: 0 0 14px;
    color: #f2c15b;
    font-size: 0.78rem;
    font-weight: 900;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.type-card {
    position: relative;
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    align-items: center;
    gap: 12px;
    min-height: 72px;
    padding: 12px 14px;
    border: 1px solid rgba(255, 248, 234, 0.18);
    border-radius: 16px;
    background: rgba(255, 248, 234, 0.06);
    cursor: pointer;
    transition: border-color 250ms ease, background 250ms ease, transform 250ms ease;
}

.type-card input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.type-card.active,
.type-card:hover {
    transform: translateY(-2px);
    border-color: rgba(246, 198, 91, 0.72);
    background: rgba(246, 198, 91, 0.12);
}

.type-icon {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border-radius: 12px;
    background: rgba(246, 198, 91, 0.1);
    color: #f6c65b;
}

.type-icon svg {
    width: 23px;
    height: 23px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
}

.type-content {
    min-width: 0;
}

.type-content strong,
.type-content small {
    display: block;
}

.type-content strong {
    margin: 0 0 4px;
    color: #fff8ea;
    font-size: 0.9rem;
    line-height: 1.15;
}

.type-content small {
    margin: 0;
    color: rgba(248, 242, 230, 0.74);
    font-size: 0.74rem;
    line-height: 1.35;
}

.radio-dot {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(246, 198, 91, 0.44);
    border-radius: 50%;
}

.type-card.active .radio-dot {
    border-color: #f6c65b;
    background: radial-gradient(circle, #f6c65b 0 42%, transparent 45%);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

label {
    min-width: 0;
}

.form-grid label > span,
.full-field > span {
    display: block;
    margin-bottom: 5px;
    color: #f8f2e6;
    font-size: 0.78rem;
    font-weight: 800;
}

input,
select,
textarea {
    width: 100%;
    border: 1px solid rgba(255, 248, 234, 0.22);
    min-height: 42px;
    border-radius: 12px;
    color: #f8f2e6;
    background: rgba(255, 248, 234, 0.08);
    box-shadow: none;
    transition: border-color 250ms ease, background 250ms ease;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #f2c15b;
    background: rgba(255, 248, 234, 0.12);
    outline: none;
    box-shadow: 0 0 0 3px rgba(246, 198, 91, 0.16);
}

input:disabled {
    opacity: 0.48;
    cursor: not-allowed;
}

select option {
    color: #08162e;
}

textarea {
    resize: none;
    min-height: 92px;
}

small,
.consent-error {
    display: block;
    margin-top: 5px;
    color: #ffd27a;
    font-size: 0.74rem;
}

.full-field {
    display: block;
    margin-top: 11px;
}

.check-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 9px;
    color: rgba(248, 242, 230, 0.88);
}

.check-row input {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    border-radius: 5px;
    color: #d97706;
}

.check-row span {
    margin: 0;
    color: inherit;
    font-weight: 600;
    line-height: 1.45;
}

.check-row.muted {
    opacity: 0.58;
}

.modal-actions,
.success-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 16px;
}

.primary-action,
.secondary-action,
.ghost-action {
    min-height: 42px;
    border-radius: 14px;
    padding: 0 20px;
    font-weight: 900;
    cursor: pointer;
    transition: transform 250ms ease, box-shadow 250ms ease, border-color 250ms ease;
}

.primary-action {
    border: 1px solid rgba(246, 198, 91, 0.7);
    color: #08162e;
    background: linear-gradient(135deg, #f6c65b, #f59e0b 72%, #d97706);
    box-shadow: 0 16px 28px rgba(245, 158, 11, 0.22);
}

.secondary-action,
.ghost-action {
    border: 1px solid rgba(246, 198, 91, 0.36);
    color: #f8f2e6;
    background: rgba(255, 248, 234, 0.06);
}

.primary-action:hover,
.secondary-action:hover,
.ghost-action:hover {
    transform: translateY(-3px);
    border-color: #f2c15b;
    box-shadow: 0 18px 30px rgba(217, 164, 65, 0.18);
}

.success-state {
    display: grid;
    justify-items: center;
    text-align: center;
}

.success-icon {
    display: grid;
    width: 68px;
    height: 68px;
    place-items: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #f6c65b, #f59e0b);
    box-shadow: 0 18px 30px rgba(245, 158, 11, 0.22);
}

.success-icon svg {
    width: 34px;
    height: 34px;
    fill: none;
    stroke: #08162e;
    stroke-width: 2.5;
}

.success-state h3 {
    margin: 18px 0 8px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.8rem;
}

.success-state p {
    max-width: 560px;
    margin: 0;
    color: rgba(248, 242, 230, 0.82);
    line-height: 1.6;
}

.tracking-code-box {
    margin-top: 22px;
    padding: 14px 26px;
    border: 1px solid rgba(246, 198, 91, 0.5);
    border-radius: 18px;
    background: rgba(255, 248, 234, 0.07);
}

.tracking-code-box small {
    margin: 0 0 7px;
    color: #f2c15b;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.tracking-code-box strong {
    color: #fff8ea;
    font-size: 1.45rem;
    letter-spacing: 0.08em;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 220ms ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

.modal-fade-enter-active .prayer-modal,
.modal-fade-leave-active .prayer-modal {
    transition: transform 260ms ease, opacity 260ms ease;
}

.modal-fade-enter-from .prayer-modal,
.modal-fade-leave-to .prayer-modal {
    opacity: 0;
    transform: scale(0.96) translateY(10px);
}

@media (max-width: 760px) {
    .modal-backdrop {
        padding: 12px;
    }

    .modal-header,
    .prayer-form,
    .success-state {
        padding-inline: 18px;
    }

    .form-grid,
    .type-card-grid {
        grid-template-columns: 1fr;
    }

    .modal-actions,
    .success-actions {
        flex-direction: column;
    }
}
</style>
