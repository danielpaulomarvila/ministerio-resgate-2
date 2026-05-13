<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import PrayerRequestModal from '../../Components/PrayerRequestModal.vue';
import { usePublicPageTransition } from '../../Composables/usePublicPageTransition';

defineProps({ canLogin: { type: Boolean, default: true } });

const publicPageElement = ref(null);
const { navigatePublicPage } = usePublicPageTransition(publicPageElement);
const trackingCode = ref('');
const searched = ref(false);
const foundRequest = ref(null);
const copied = ref(false);
const showPrayerModal = ref(false);
const accessPanelOpen = ref(false);
const accessNotice = ref(false);
const visitModalOpen = ref(false);
const visitSent = ref(false);

// Conteúdo provisório da página Acompanhar Oração.
// Futuramente será carregado da Administração Geral → Site Público.
const pageContent = {
    title: 'Acompanhar Oração',
    subtitle: 'Seu pedido foi recebido com carinho. Aqui você pode acompanhar o cuidado da nossa equipe de intercessão.',
    privacy: 'Seu pedido será tratado com cuidado e respeito. Você escolhe se deseja permanecer anônimo, receber contato ou criar um acesso para acompanhar melhor.',
};

const backgroundWords = ['fé', 'cuidado', 'intercessão', 'oração', 'paz', 'esperança'];
const accessForm = ref({ name: '', email: '', password: '', passwordConfirmation: '', code: '', keepAnonymous: true, allowContact: false, wantsFamily: false, wantsVisit: false });
const visitForm = ref({ name: '', whatsapp: '', preferredDay: '', notes: '', allowContact: true });

const visibilityLabels = {
    intercession_team: 'Apenas equipe de intercessão',
    pastoral_intercession: 'Pastoral e intercessão',
    pastoral_only: 'Somente pastoral',
    shared_anonymous: 'Compartilhamento anônimo com a rede de oração',
};

const statusLabels = {
    received: 'Pedido recebido',
    routed: 'Encaminhado para intercessão',
    praying: 'Em oração',
    answered: 'Resposta disponível',
    pastoral_notified: 'Pastoral acionada',
    waiting: 'Aguardando atualização',
    completed: 'Concluído',
    archived: 'Arquivado',
};

const timelineSteps = [
    { status: 'received', label: 'Pedido recebido' },
    { status: 'routed', label: 'Encaminhado para intercessão' },
    { status: 'praying', label: 'Em oração' },
    { status: 'answered', label: 'Resposta disponível' },
    { status: 'pastoral_notified', label: 'Pastoral acionada' },
    { status: 'waiting', label: 'Aguardando atualização' },
    { status: 'completed', label: 'Concluído' },
    { status: 'archived', label: 'Arquivado' },
];

const currentStatus = computed(() => {
    const latest = foundRequest.value?.timeline?.at(-1);
    return statusLabels[latest?.status] ?? latest?.label ?? statusLabels[foundRequest.value?.status] ?? 'Pedido recebido';
});

function loadStoredRequests() {
    if (typeof window === 'undefined') {
        return [];
    }

    // Persistência temporária no navegador.
    // Futuramente este acompanhamento será feito via API/MySQL,
    // com segurança, permissões e painel de intercessão.
    return JSON.parse(window.localStorage.getItem('familia_resgate_prayer_requests') ?? '[]');
}

function searchPrayerByTrackingCode() {
    const code = trackingCode.value.trim().toUpperCase();
    foundRequest.value = code ? loadStoredRequests().find((request) => request.tracking_code?.toUpperCase() === code) ?? null : null;
    searched.value = true;
    copied.value = false;
}

function hasTimelineStatus(status) {
    return foundRequest.value?.timeline?.some((event) => event.status === status) ?? false;
}

function stepDate(status) {
    return foundRequest.value?.timeline?.find((event) => event.status === status)?.date;
}

function formatDate(date) {
    return date ? new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(date)) : 'Próxima etapa';
}

function copyTrackingCode() {
    const code = foundRequest.value?.tracking_code ?? trackingCode.value;

    if (!code) {
        return;
    }

    navigator.clipboard?.writeText(code);
    copied.value = true;
}

function openCreateAccessPanel() {
    accessPanelOpen.value = true;
    accessNotice.value = false;
    accessForm.value.code = foundRequest.value?.tracking_code ?? accessForm.value.code;
}

function simulateProtectedAccessCreation() {
    accessNotice.value = true;
    accessForm.value.password = '';
    accessForm.value.passwordConfirmation = '';
}

function openVisitRequestModal() {
    visitSent.value = false;
    visitModalOpen.value = true;
}

function submitVisitInterest() {
    if (!visitForm.value.name || !visitForm.value.whatsapp) {
        return;
    }

    // Persistência temporária no navegador.
    // Futuramente este fluxo será salvo via API/MySQL com permissões e segurança.
    const savedVisits = JSON.parse(localStorage.getItem('familia_resgate_visit_interests') ?? '[]');
    localStorage.setItem('familia_resgate_visit_interests', JSON.stringify([{ ...visitForm.value, created_at: new Date().toISOString() }, ...savedVisits]));
    visitSent.value = true;
    visitForm.value = { name: '', whatsapp: '', preferredDay: '', notes: '', allowContact: true };
}

function openNewPrayerRequest() {
    showPrayerModal.value = true;
}

function handlePrayerTracking(code) {
    showPrayerModal.value = false;
    trackingCode.value = code;
    searchPrayerByTrackingCode();
}

onMounted(() => {
    const pendingCode = window.sessionStorage.getItem('familia_resgate_pending_prayer_tracking_code');
    window.sessionStorage.removeItem('familia_resgate_pending_prayer_tracking_code');

    if (pendingCode) {
        trackingCode.value = pendingCode;
        searchPrayerByTrackingCode();
    }
});
</script>

<template>
    <Head title="Acompanhar Oração" />

    <div ref="publicPageElement" class="tracking-screen public-route-page">
        <header class="public-header">
            <Link class="brand-mark" href="/inicio" aria-label="Família Resgate" @click="navigatePublicPage($event, '/inicio')">
                <img class="brand-logo" src="/images/brand/logo-resgate-gold.png" alt="Logo Ministério Resgate" />
                <span><small>Família</small><strong>Resgate</strong></span>
            </Link>
            <nav class="public-nav" aria-label="Navegação pública">
                <Link class="nav-item" href="/inicio" @click="navigatePublicPage($event, '/inicio')">Início</Link>
                <Link class="nav-item" href="/sobre_nos" @click="navigatePublicPage($event, '/sobre_nos')">Sobre Nós</Link>
                <Link class="nav-item" href="/cultos" @click="navigatePublicPage($event, '/cultos')">Cultos</Link>
                <Link class="nav-item" href="/eventos" @click="navigatePublicPage($event, '/eventos')">Eventos</Link>
                <Link class="nav-item" href="/contato" @click="navigatePublicPage($event, '/contato')">Contato</Link>
            </nav>
            <Link v-if="canLogin" class="login-button" :href="$page.props.auth.user ? route('dashboard') : route('login')">Entrar</Link>
        </header>

        <main class="tracking-stage">
            <span v-for="word in backgroundWords" :key="word" class="spirit-word">{{ word }}</span>

            <section class="tracking-hero">
                <p class="breadcrumb"><Link href="/inicio" @click="navigatePublicPage($event, '/inicio')">Início</Link> / Acompanhar Oração</p>
                <p class="section-kicker">Cuidado em oração</p>
                <h1>{{ pageContent.title }}</h1>
                <p>{{ pageContent.subtitle }}</p>
                <aside><strong>Privacidade e anonimato</strong><span>{{ pageContent.privacy }}</span></aside>
            </section>

            <section class="path-grid" aria-label="Caminhos para acompanhar oração">
                <article class="path-card night-card">
                    <span>◇</span>
                    <h2>Acompanhar com código</h2>
                    <p>Digite o código recebido ao enviar seu pedido de oração. Você poderá ver o status e as respostas disponíveis sem precisar se identificar.</p>
                    <form class="stack-form" @submit.prevent="searchPrayerByTrackingCode">
                        <label>Código de acompanhamento<input v-model="trackingCode" type="text" autocomplete="off" placeholder="Ex.: ORA-8F3K-27LQ" /></label>
                        <button type="submit">Consultar pedido</button>
                    </form>
                </article>

                <article class="path-card light-card">
                    <span>♢</span>
                    <h2>Criar acesso para acompanhar</h2>
                    <p>Crie um acesso simples para acompanhar seus pedidos, conversar com a equipe autorizada e receber orientações com privacidade.</p>
                    <button type="button" @click="openCreateAccessPanel">Criar meu acesso</button>
                    <form v-if="accessPanelOpen" class="stack-form access-form" @submit.prevent="simulateProtectedAccessCreation">
                        <label>Nome <small>opcional se quiser permanecer anônimo</small><input v-model="accessForm.name" type="text" /></label>
                        <label>E-mail<input v-model="accessForm.email" type="email" required /></label>
                        <label>Senha<input v-model="accessForm.password" type="password" required /></label>
                        <label>Confirmar senha<input v-model="accessForm.passwordConfirmation" type="password" required /></label>
                        <label>Código do pedido <small>opcional</small><input v-model="accessForm.code" type="text" placeholder="ORA-8F3K-27LQ" /></label>
                        <label class="check-row"><input v-model="accessForm.keepAnonymous" type="checkbox" /> <em>Quero continuar anônimo para a equipe de intercessão.</em></label>
                        <label class="check-row"><input v-model="accessForm.allowContact" type="checkbox" /> <em>Quero receber contato da igreja.</em></label>
                        <label class="check-row"><input v-model="accessForm.wantsFamily" type="checkbox" /> <em>Tenho interesse em conhecer a igreja / fazer parte da Família Resgate.</em></label>
                        <label class="check-row"><input v-model="accessForm.wantsVisit" type="checkbox" /> <em>Desejo marcar um dia para visitar a igreja.</em></label>
                        <p class="safe-note">Por segurança, o cadastro real será finalizado em área protegida da plataforma. Nenhuma senha é salva nesta simulação.</p>
                        <button type="submit">Criar meu acesso</button>
                        <p v-if="accessNotice" class="success-message">Fluxo preparado. Futuramente será integrado com login, API e Central Família.</p>
                    </form>
                </article>

                <article class="path-card night-card">
                    <span>⌂</span>
                    <h2>Sou membro da Família Resgate</h2>
                    <p>Se você já faz parte da igreja e possui acesso, entre na Central Família Resgate para acompanhar seus pedidos, enviar novos pedidos e receber cuidado da igreja.</p>
                    <div class="member-actions">
                        <Link :href="route('login')">Entrar na Central Família</Link>
                        <Link :href="route('login')">Ver meus pedidos de oração</Link>
                    </div>
                    <small>Futuramente: Central Família → Orações.</small>
                </article>
            </section>

            <section class="result-area" aria-live="polite">
                <article v-if="foundRequest" class="result-card">
                    <header><div><p class="section-kicker">Pedido encontrado</p><h2>{{ currentStatus }}</h2></div><strong>{{ foundRequest.tracking_code }}</strong></header>
                    <div class="result-meta">
                        <p><b>Categoria:</b> {{ foundRequest.category }}</p>
                        <p><b>Privacidade:</b> {{ foundRequest.is_anonymous ? 'Anônimo' : visibilityLabels[foundRequest.visibility] }}</p>
                        <p><b>Enviado em:</b> {{ formatDate(foundRequest.created_at) }}</p>
                        <p><b>Status atual:</b> {{ currentStatus }}</p>
                    </div>
                    <ol class="timeline">
                        <li v-for="step in timelineSteps" :key="step.status" :class="{ active: hasTimelineStatus(step.status) }"><span></span><div><b>{{ step.label }}</b><small>{{ stepDate(step.status) ? formatDate(stepDate(step.status)) : 'Próxima etapa' }}</small></div></li>
                    </ol>
                    <aside class="intercession-note"><b>Resposta/observação</b><p>{{ foundRequest.responses?.find((response) => response.visible_to_requester)?.message ?? 'Seu pedido foi recebido e está em oração. Nossa equipe está intercedendo por você. Continue firme, Deus cuida de cada detalhe.' }}</p></aside>
                    <footer class="result-actions"><button type="button" @click="copyTrackingCode">{{ copied ? 'Código copiado' : 'Copiar código' }}</button><button type="button" @click="openCreateAccessPanel">Quero criar acesso para acompanhar melhor</button><button type="button" @click="openNewPrayerRequest">Enviar novo pedido de oração</button></footer>
                </article>
                <article v-else-if="searched" class="not-found-card"><strong>Não encontramos um pedido com este código.</strong><p>Confira se digitou corretamente ou envie um novo pedido de oração.</p><button type="button" @click="openNewPrayerRequest">Enviar novo pedido de oração</button></article>
            </section>

            <section class="future-flow">
                <article><strong>Intercessão</strong><p>Pedidos serão acompanhados por equipe autorizada, com status e respostas visíveis quando apropriado.</p></article>
                <article><strong>Central Pastoral</strong><p>Casos sensíveis poderão ser encaminhados com sigilo para cuidado pastoral e visitas.</p></article>
                <article><strong>Central Família</strong><p>Membros verão seus pedidos dentro da área protegida, sem depender de código manual.</p></article>
                <button type="button" @click="openVisitRequestModal">Quero conhecer a igreja</button>
            </section>

            <footer class="tracking-footer">
                <div><img src="/images/brand/logo-resgate-gold.png" alt="Logo Família Resgate" /><span><strong>Família Resgate</strong><small>Uma família que existe para resgatar vidas para Jesus.</small></span></div>
                <div><span><small>Plataforma desenvolvida por</small><strong>Marvvium</strong></span><img src="/images/brand/logo-marvvium.png" alt="Marvvium" /></div>
            </footer>
        </main>

        <PrayerRequestModal :show="showPrayerModal" @close="showPrayerModal = false" @track="handlePrayerTracking" />

        <div v-if="visitModalOpen" class="modal-backdrop" role="presentation" @click.self="visitModalOpen = false">
            <article class="visit-modal" role="dialog" aria-modal="true" aria-label="Quero conhecer a igreja">
                <button type="button" class="close-button" aria-label="Fechar" @click="visitModalOpen = false">×</button>
                <p class="section-kicker">Família Resgate</p>
                <h2>Quero conhecer a igreja</h2>
                <form v-if="!visitSent" class="stack-form" @submit.prevent="submitVisitInterest">
                    <label>Nome<input v-model="visitForm.name" type="text" required /></label>
                    <label>WhatsApp<input v-model="visitForm.whatsapp" type="tel" required /></label>
                    <label>Melhor dia para visita<input v-model="visitForm.preferredDay" type="text" /></label>
                    <label>Observação<textarea v-model="visitForm.notes" rows="3"></textarea></label>
                    <label class="check-row"><input v-model="visitForm.allowContact" type="checkbox" /> <em>Quero receber contato da igreja.</em></label>
                    <button type="submit">Enviar interesse</button>
                </form>
                <p v-else class="success-message">Recebemos seu interesse. Futuramente esta solicitação será encaminhada à Secretaria/Central Família.</p>
            </article>
        </div>
    </div>
</template>

<style scoped>
.tracking-screen{position:relative;min-height:100vh;overflow-x:hidden;padding:0 clamp(24px,3.4vw,54px) 32px;color:#08162e;background:radial-gradient(circle at 20% 12%,rgba(246,198,91,.2),transparent 22%),radial-gradient(circle at 82% 24%,rgba(217,119,6,.14),transparent 20%),linear-gradient(135deg,#fff9ed,#f6eddd 54%,#ead8b8)}
.tracking-screen:before{content:'';position:fixed;inset:0;pointer-events:none;opacity:.18;background-image:linear-gradient(rgba(8,22,46,.08) 1px,transparent 1px),linear-gradient(90deg,rgba(8,22,46,.05) 1px,transparent 1px);background-size:64px 64px}.public-header{position:relative;z-index:3;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:22px;padding:20px 0 14px}.brand-mark,.nav-item,.login-button{color:inherit;text-decoration:none}.brand-mark{display:flex;align-items:center;gap:10px;font-family:Georgia,serif}.brand-logo{width:42px;height:42px;object-fit:contain}.brand-mark small,.brand-mark strong{display:block;line-height:1}.brand-mark small{color:#9a6a16;font-size:.72rem;letter-spacing:.18em;text-transform:uppercase}.brand-mark strong{font-size:1.35rem}.public-nav{display:flex;justify-content:center;gap:clamp(12px,2vw,28px)}.nav-item{color:rgba(8,22,46,.72);font-size:.82rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.nav-item:hover{color:#d97706}.login-button{padding:10px 16px;border:1px solid rgba(8,22,46,.14);border-radius:999px;background:rgba(255,255,255,.56);font-weight:900}.tracking-stage{position:relative;z-index:1;display:grid;gap:22px}.spirit-word{position:absolute;color:rgba(8,22,46,.055);font-family:Georgia,serif;font-size:clamp(2rem,6vw,5rem);font-weight:800;pointer-events:none}.spirit-word:nth-child(1){top:70px;left:4%}.spirit-word:nth-child(2){top:210px;right:7%}.spirit-word:nth-child(3){top:520px;left:10%}.spirit-word:nth-child(4){bottom:320px;right:4%}.spirit-word:nth-child(5){bottom:180px;left:18%}.spirit-word:nth-child(6){bottom:40px;right:20%}.tracking-hero{padding:clamp(34px,5vw,70px);border:1px solid rgba(246,198,91,.32);border-radius:34px;color:#fff;background:radial-gradient(circle at 82% 18%,rgba(246,198,91,.28),transparent 28%),linear-gradient(135deg,#06152f,#0b2448 58%,#3a250d);box-shadow:0 28px 80px rgba(8,22,46,.18)}.breadcrumb,.breadcrumb a{color:rgba(255,242,211,.72);text-decoration:none;font-size:.78rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase}.section-kicker{margin:0 0 10px;color:#d89418;font-size:.78rem;font-weight:900;letter-spacing:.18em;text-transform:uppercase}.tracking-hero .section-kicker{color:#f6c65b}.tracking-hero h1{max-width:760px;margin:0;font-family:Georgia,serif;font-size:clamp(3rem,7vw,6.8rem);line-height:.9}.tracking-hero>p:not(.breadcrumb):not(.section-kicker){max-width:780px;color:rgba(255,255,255,.78);font-size:1.08rem;line-height:1.75}.tracking-hero aside{display:grid;gap:6px;max-width:860px;padding:14px 18px;border:1px solid rgba(246,198,91,.24);border-radius:20px;background:rgba(255,255,255,.08)}.tracking-hero aside span{color:rgba(255,255,255,.74)}.path-grid{display:grid;grid-template-columns:1.05fr 1fr 1fr;gap:18px}.path-card,.result-card,.not-found-card,.future-flow,.tracking-footer,.visit-modal{border:1px solid rgba(8,22,46,.08);border-radius:28px;background:rgba(255,252,245,.88);box-shadow:0 24px 60px rgba(55,36,12,.1);backdrop-filter:blur(14px)}.path-card{padding:26px;transition:transform .26s ease,box-shadow .26s ease}.path-card:hover{transform:translateY(-4px);box-shadow:0 28px 72px rgba(55,36,12,.16)}.night-card{color:#fff;background:linear-gradient(145deg,#071b3a,#102f5b 62%,#6f430d)}.path-card>span{display:grid;width:48px;height:48px;margin-bottom:16px;place-items:center;border-radius:16px;color:#f6c65b;background:rgba(246,198,91,.12);font-size:1.45rem}.path-card h2,.result-card h2,.visit-modal h2{margin:0 0 10px;font-family:Georgia,serif;font-size:1.75rem}.path-card p,.future-flow p,.not-found-card p{color:rgba(8,22,46,.7);line-height:1.65}.night-card p,.night-card small{color:rgba(255,255,255,.76)}.stack-form{display:grid;gap:12px;margin-top:18px}label{display:grid;gap:7px;font-size:.76rem;font-weight:900;letter-spacing:.06em;text-transform:uppercase}label small{color:rgba(8,22,46,.48);letter-spacing:0;text-transform:none}input,textarea{width:100%;box-sizing:border-box;border:1px solid rgba(8,22,46,.12);border-radius:16px;padding:13px 14px;color:#08162e;background:rgba(255,255,255,.88);font:inherit;font-weight:700}.night-card label{color:rgba(255,255,255,.82)}button,.member-actions a{border:0;border-radius:999px;padding:13px 18px;color:#fff;background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 14px 30px rgba(217,119,6,.24);font-weight:900;cursor:pointer;text-align:center;text-decoration:none}.check-row{display:flex;align-items:flex-start;gap:10px;letter-spacing:0;text-transform:none}.check-row input{width:auto;margin-top:3px}.check-row em{font-style:normal;font-weight:700}.safe-note,.success-message{margin:0;padding:12px 14px;border-radius:16px;color:#73510e;background:rgba(246,198,91,.18);font-size:.88rem;line-height:1.5}.member-actions{display:grid;gap:10px;margin:18px 0 12px}.member-actions a:last-child{color:#071b3a;background:rgba(255,255,255,.9)}.result-card,.not-found-card{padding:clamp(24px,4vw,42px)}.result-card header{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px}.result-card header>strong{padding:10px 14px;border-radius:999px;background:#f6c65b}.result-meta{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:18px 0}.result-meta p,.intercession-note{margin:0;padding:14px;border-radius:18px;background:rgba(8,22,46,.05)}.timeline{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;padding:0;list-style:none}.timeline li{display:flex;gap:10px;opacity:.48}.timeline li.active{opacity:1}.timeline li>span{width:13px;height:13px;margin-top:4px;border-radius:999px;background:#d89418}.timeline b,.timeline small{display:block}.timeline small{color:rgba(8,22,46,.56)}.result-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}.future-flow{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;align-items:center;padding:22px}.future-flow article{padding:16px;border-radius:20px;background:rgba(255,255,255,.54)}.tracking-footer{display:flex;justify-content:space-between;align-items:center;gap:18px;padding:20px}.tracking-footer div{display:flex;align-items:center;gap:10px}.tracking-footer img{max-width:74px;max-height:44px;object-fit:contain}.tracking-footer small,.tracking-footer strong{display:block}.modal-backdrop{position:fixed;inset:0;z-index:30;display:grid;place-items:center;padding:20px;background:rgba(2,8,23,.62);backdrop-filter:blur(10px)}.visit-modal{position:relative;width:min(560px,100%);padding:28px}.close-button{position:absolute;top:14px;right:14px;width:38px;height:38px;padding:0;border-radius:999px;background:#071b3a}@media(max-width:1100px){.path-grid,.future-flow{grid-template-columns:1fr}.result-meta,.timeline{grid-template-columns:1fr 1fr}.public-header{grid-template-columns:1fr}.public-nav{justify-content:flex-start;flex-wrap:wrap}}@media(max-width:720px){.tracking-screen{padding-inline:18px}.tracking-hero{padding:30px 22px}.result-meta,.timeline{grid-template-columns:1fr}.tracking-footer{align-items:flex-start;flex-direction:column}}
</style>
