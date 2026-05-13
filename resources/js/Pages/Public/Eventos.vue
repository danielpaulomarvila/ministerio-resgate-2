<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { usePublicPageTransition } from '../../Composables/usePublicPageTransition';

defineProps({ canLogin: { type: Boolean, default: true } });

const publicPageElement = ref(null);
const activeCategory = ref('todos');
const selectedEvent = ref(null);
const detailsEvent = ref(null);
const showCalendarModal = ref(false);
const showTrackingModal = ref(false);
const interestedEventIds = ref([]);
const registrationStep = ref('form');
const trackingCode = ref('');
const trackingResult = ref(null);
const registrationForm = ref({ name: '', phone: '', email: '', quantity: 1, note: '', accepted: false });
const registrationReceipt = ref(null);
const { navigatePublicPage } = usePublicPageTransition(publicPageElement);

// Conteúdo provisório da página Eventos.
// Futuramente será carregado da Administração Geral → Site Público.
const eventIntro = {
    kicker: 'Eventos',
    title: 'Momentos que marcam para sempre a sua fé.',
    text: 'Nossos eventos são oportunidades únicas para crescer, se conectar, aprender e viver experiências transformadoras na presença de Deus.',
};

const eventPillars = [
    { title: 'Comunhão', text: 'Conecte-se com pessoas que compartilham a mesma fé.', icon: 'people' },
    { title: 'Crescimento', text: 'Ensino, cura e inspiração para sua jornada espiritual.', icon: 'book' },
    { title: 'Propósito', text: 'Eventos que edificam, servem e transformam vidas.', icon: 'heart' },
];

// Evento em destaque provisório, preparado para futura edição pela Administração Geral.
const featuredEvent = {
    id: 'retiro-renovar-2025',
    title: 'Retiro Renovar 2025',
    category: 'retiros',
    categoryLabel: 'Retiro',
    status: 'Em breve',
    statusTone: 'gold',
    date: '18 a 21 de Julho',
    shortDate: '18 JUL',
    location: 'Sítio Canaã - Serra Azul',
    time: '18 a 21 Jul',
    price: 'A partir de R$ 280,00',
    is_paid: true,
    capacity: 120,
    available_spots: 38,
    image: '/images/hero/resgate-retiro-01.jpg',
    fallbackImage: '/images/hero/resgate-culto-05.jpg',
    description: 'Quatro dias para se afastar, buscar a presença de Deus e voltar transformado.',
    verse: 'Ele faz coisas grandiosas e insondáveis, maravilhas sem número.',
    reference: 'Jó 5:9',
};

const eventCategories = [
    { id: 'todos', label: 'Todos' },
    { id: 'retiros', label: 'Retiros' },
    { id: 'conferencias', label: 'Conferências' },
    { id: 'cursos', label: 'Cursos' },
    { id: 'acao-social', label: 'Ação Social' },
    { id: 'jovens', label: 'Jovens' },
    { id: 'mulheres', label: 'Mulheres' },
    { id: 'kids', label: 'Kids' },
];

// Cards provisórios de eventos.
// Futuramente datas, vagas, imagens e valores virão da Administração Geral.
const eventCards = [
    { ...featuredEvent, status: 'Vagas limitadas', statusTone: 'warning', description: 'Encontros, ministrações, louvor, natureza e comunhão.' },
    { id: 'conferencia-avivamento', title: 'Conferência Avivamento', category: 'conferencias', categoryLabel: 'Conferência', shortDate: '02 AGO', status: 'Inscrições abertas', statusTone: 'dark', description: 'Uma noite de adoração, Palavra e avivamento.', time: '19h00', location: 'Auditório Principal', price: 'Entrada: R$ 30,00', is_paid: true, capacity: 300, available_spots: 180, image: '/images/hero/resgate-louvor-10.jpg', fallbackImage: '/images/hero/resgate-adoracao-02.jpg' },
    { id: 'curso-lideranca', title: 'Curso de Liderança', category: 'cursos', categoryLabel: 'Curso', shortDate: '16 AGO', status: 'Inscrições abertas', statusTone: 'dark', description: 'Aprenda a liderar com propósito e princípios bíblicos.', time: '08h00', location: 'Sala de Treinamento', price: 'A partir de R$ 70,00', is_paid: true, capacity: 60, available_spots: 22, image: '/images/hero/resgate-biblia-01.jpg', fallbackImage: '/images/hero/resgate-culto-05.jpg' },
    { id: 'dia-de-servir', title: 'Dia de Servir', category: 'acao-social', categoryLabel: 'Ação Social', shortDate: '30 AGO', status: 'Gratuito', statusTone: 'free', description: 'Um dia para amar, servir e fazer a diferença na nossa cidade.', time: '08h00', location: 'Comunidade', price: 'Gratuito', is_paid: false, capacity: 100, available_spots: 54, image: '/images/hero/resgate-comunhao-01.jpg', fallbackImage: '/images/hero/resgate-culto-05.jpg' },
    { id: 'encontro-jovens', title: 'Encontro de Jovens', category: 'jovens', categoryLabel: 'Jovens', shortDate: '12 SET', status: 'Inscrições abertas', statusTone: 'dark', description: 'Louvor, Palavra e comunhão para a nova geração.', time: '19h30', location: 'Auditório Jovem', price: 'Entrada: R$ 15,00', is_paid: true, capacity: 150, available_spots: 90, image: '/images/hero/resgate-jovens-01.jpg', fallbackImage: '/images/hero/resgate-louvor-10.jpg' },
];

const benefitItems = [
    { title: 'Calendário sempre atualizado', text: 'Novos eventos toda semana.', icon: 'calendar' },
    { title: 'Inscrição 100% online', text: 'Simples, rápido e seguro.', icon: 'ticket' },
    { title: 'Parcelamento facilitado', text: 'Até 3x no cartão de crédito.', icon: 'card' },
    { title: 'Lembretes e avisos', text: 'Receba tudo pelo WhatsApp.', icon: 'bell' },
];

const filteredEvents = computed(() => activeCategory.value === 'todos' ? eventCards : eventCards.filter((event) => event.category === activeCategory.value));

function filterEventsByCategory(categoryId) {
    activeCategory.value = categoryId;
}

function openFullCalendarModal() {
    showCalendarModal.value = true;
}

function closeFullCalendarModal() {
    showCalendarModal.value = false;
}

function openRegistrationTrackingModal() {
    trackingCode.value = '';
    trackingResult.value = null;
    showTrackingModal.value = true;
}

function closeRegistrationTrackingModal() {
    showTrackingModal.value = false;
}

function openEventRegistration(eventId) {
    selectedEvent.value = eventCards.find((event) => event.id === eventId) ?? featuredEvent;
    registrationStep.value = 'form';
    registrationReceipt.value = null;
    registrationForm.value = { name: '', phone: '', email: '', quantity: 1, note: '', accepted: false };
}

function closeEventRegistration() {
    selectedEvent.value = null;
}

function openEventDetails(eventId) {
    detailsEvent.value = eventCards.find((event) => event.id === eventId) ?? featuredEvent;
}

function closeEventDetails() {
    detailsEvent.value = null;
}

function toggleEventInterest(eventId) {
    interestedEventIds.value = interestedEventIds.value.includes(eventId)
        ? interestedEventIds.value.filter((id) => id !== eventId)
        : [...interestedEventIds.value, eventId];
}

function generateRegistrationCode(eventId) {
    return `EVT-${eventId.slice(0, 3).toUpperCase()}-${Math.random().toString(36).slice(2, 6).toUpperCase()}`;
}

function submitEventRegistration() {
    if (!selectedEvent.value || !registrationForm.value.name || !registrationForm.value.phone || !registrationForm.value.email || !registrationForm.value.accepted) {
        return;
    }

    const code = generateRegistrationCode(selectedEvent.value.id);
    const receipt = {
        registration_code: code,
        event_id: selectedEvent.value.id,
        event_title: selectedEvent.value.title,
        name: registrationForm.value.name,
        phone: registrationForm.value.phone,
        email: registrationForm.value.email,
        quantity: registrationForm.value.quantity,
        payment_status: selectedEvent.value.is_paid ? 'pending' : 'free',
        status: selectedEvent.value.is_paid ? 'Aguardando pagamento' : 'Inscrição recebida',
        created_at: new Date().toISOString(),
        timeline: [{ status: 'received', label: 'Inscrição recebida', date: new Date().toLocaleDateString('pt-BR') }],
    };

    // Persistência temporária no navegador.
    // Futuramente as inscrições serão salvas no MySQL pela Administração Geral/Eventos.
    const savedRegistrations = JSON.parse(localStorage.getItem('familia_resgate_event_registrations') ?? '[]');
    localStorage.setItem('familia_resgate_event_registrations', JSON.stringify([receipt, ...savedRegistrations]));
    registrationReceipt.value = receipt;
    registrationStep.value = 'success';
}

async function copyRegistrationCode() {
    if (!registrationReceipt.value?.registration_code || !navigator?.clipboard) {
        return;
    }

    await navigator.clipboard.writeText(registrationReceipt.value.registration_code);
}

function trackTemporaryRegistration() {
    const savedRegistrations = JSON.parse(localStorage.getItem('familia_resgate_event_registrations') ?? '[]');
    trackingResult.value = savedRegistrations.find((registration) => registration.registration_code === trackingCode.value.trim().toUpperCase()) ?? false;
}
</script>

<template>
    <Head title="Eventos" />

    <div ref="publicPageElement" class="events-public-screen public-route-page">
        <header class="public-header">
            <Link class="brand-mark" href="/inicio" aria-label="Família Resgate" @click="navigatePublicPage($event, '/inicio')">
                <img class="brand-logo" src="/images/brand/logo-resgate-gold.png" alt="Logo Ministério Resgate" />
                <span><small>Família</small><strong>Resgate</strong></span>
            </Link>
            <nav class="public-nav" aria-label="Navegação pública">
                <Link class="nav-item" href="/inicio" @click="navigatePublicPage($event, '/inicio')">Início</Link>
                <Link class="nav-item" href="/sobre_nos" @click="navigatePublicPage($event, '/sobre_nos')">Sobre Nós</Link>
                <Link class="nav-item" href="/cultos" @click="navigatePublicPage($event, '/cultos')">Cultos</Link>
                <Link class="nav-item active" href="/eventos" @click="navigatePublicPage($event, '/eventos')">Eventos</Link>
                <Link class="nav-item" href="/contato" @click="navigatePublicPage($event, '/contato')">Contato</Link>
            </nav>
            <Link v-if="canLogin" class="login-button" :href="$page.props.auth.user ? route('dashboard') : route('login')">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                Entrar
            </Link>
        </header>

        <main class="events-stage">
            <section class="events-hero" aria-label="Central de eventos e inscrições">
                <article class="events-intro-card">
                    <p class="section-kicker">{{ eventIntro.kicker }}</p>
                    <h1>{{ eventIntro.title }}</h1>
                    <span class="intro-line"></span>
                    <p class="intro-text">{{ eventIntro.text }}</p>

                    <div class="event-pillars">
                        <article v-for="pillar in eventPillars" :key="pillar.title" class="event-pillar">
                            <span>{{ pillar.icon === 'people' ? '♧' : pillar.icon === 'book' ? '▣' : '♡' }}</span>
                            <div><strong>{{ pillar.title }}</strong><p>{{ pillar.text }}</p></div>
                        </article>
                    </div>

                    <div class="intro-actions">
                        <button type="button" class="primary-cta" @click="openFullCalendarModal">Ver Calendário Completo</button>
                        <Link class="secondary-cta" href="/contato" @click="navigatePublicPage($event, '/contato')">Como Chegar</Link>
                    </div>
                    <button type="button" class="tracking-link" @click="openRegistrationTrackingModal">Acompanhar Inscrição</button>
                </article>

                <article class="featured-event-card" aria-label="Evento em destaque">
                    <img :src="featuredEvent.image" :alt="featuredEvent.title" loading="eager" @error="$event.target.src = featuredEvent.fallbackImage" />
                    <div class="featured-overlay">
                        <span class="status-badge status-gold">{{ featuredEvent.status }}</span>
                        <h2>Retiro Renovar <b>2025</b></h2>
                        <p class="featured-meta">◷ {{ featuredEvent.date }} · ⌖ {{ featuredEvent.location }}</p>
                        <p class="featured-description">{{ featuredEvent.description }}</p>
                        <div class="featured-info-grid">
                            <span><strong>Vagas limitadas</strong><small>Apenas {{ featuredEvent.capacity }} vagas disponíveis</small></span>
                            <span><strong>Inscrição antecipada</strong><small>Garanta seu lugar com desconto especial</small></span>
                            <span><strong>Pagamento seguro</strong><small>Parcele em até 3x no cartão</small></span>
                        </div>
                        <div class="featured-actions">
                            <button type="button" class="primary-cta" @click="openEventRegistration(featuredEvent.id)">Quero me inscrever</button>
                            <button type="button" class="dark-cta" @click="openEventDetails(featuredEvent.id)">Saiba mais</button>
                        </div>
                    </div>
                    <aside class="featured-verse"><span>“</span><p>{{ featuredEvent.verse }}</p><strong>{{ featuredEvent.reference }}</strong></aside>
                </article>
            </section>

            <section class="events-list-section" aria-label="Próximos eventos">
                <header class="events-list-heading">
                    <h2>Próximos Eventos</h2>
                    <button type="button" @click="openFullCalendarModal">Ver todos os eventos →</button>
                </header>

                <div class="event-filters" aria-label="Filtros de eventos">
                    <button v-for="category in eventCategories" :key="category.id" type="button" :class="{ active: activeCategory === category.id }" @click="filterEventsByCategory(category.id)">
                        {{ category.label }}
                    </button>
                </div>

                <div class="event-cards-grid">
                    <article v-for="event in filteredEvents" :key="event.id" class="event-card">
                        <div class="event-image-wrap">
                            <img :src="event.image" :alt="event.title" loading="lazy" @error="$event.target.src = event.fallbackImage" />
                            <span class="event-date">{{ event.shortDate }}</span>
                            <span class="event-status" :class="`status-${event.statusTone}`">{{ event.status }}</span>
                            <button type="button" class="interest-button" :aria-label="`Marcar interesse em ${event.title}`" @click="toggleEventInterest(event.id)">
                                {{ interestedEventIds.includes(event.id) ? '♥' : '♡' }}
                            </button>
                        </div>
                        <div class="event-card-body">
                            <small>{{ event.categoryLabel }}</small>
                            <h3>{{ event.title }}</h3>
                            <p>{{ event.description }}</p>
                            <div class="event-card-meta"><span>◷ {{ event.time }}</span><span>⌖ {{ event.location }}</span></div>
                            <div class="event-card-bottom"><strong>{{ event.price }}</strong><button type="button" @click="openEventRegistration(event.id)">Inscrever-se</button></div>
                            <button type="button" class="more-button" @click="openEventDetails(event.id)">Saber mais</button>
                        </div>
                    </article>
                </div>

                <p class="events-notice">ⓘ Alguns eventos possuem vagas limitadas e encerramento antecipado. Garanta sua inscrição com antecedência.</p>
            </section>

            <section class="benefits-strip" aria-label="Benefícios da central de eventos">
                <article v-for="benefit in benefitItems" :key="benefit.title">
                    <span>{{ benefit.icon === 'calendar' ? '▣' : benefit.icon === 'ticket' ? '◇' : benefit.icon === 'card' ? '▭' : '♢' }}</span>
                    <div><strong>{{ benefit.title }}</strong><p>{{ benefit.text }}</p></div>
                </article>
            </section>

            <footer class="events-footer">
                <div class="footer-brand"><img src="/images/brand/logo-resgate-gold.png" alt="Logo Família Resgate" /><span><strong>Família Resgate</strong><small>Uma família que existe para resgatar vidas para Jesus.</small></span></div>
                <div class="footer-socials" aria-label="Redes sociais"><a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">◎</a><a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">f</a><a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="YouTube">▶</a><a href="https://wa.me/550012345678" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">◌</a></div>
                <div class="marvvium-signature" aria-label="Plataforma desenvolvida por Marvvium"><span><small>Plataforma desenvolvida por</small><strong>Marvvium</strong></span><img src="/images/brand/logo-marvvium.png" alt="Marvvium" /></div>
            </footer>
        </main>

        <div v-if="selectedEvent" class="event-modal-backdrop" role="presentation" @click.self="closeEventRegistration">
            <article class="event-modal registration-modal" role="dialog" aria-modal="true" :aria-label="`Inscrição para ${selectedEvent.title}`">
                <button type="button" class="modal-close" aria-label="Fechar inscrição" @click="closeEventRegistration">×</button>

                <template v-if="registrationStep === 'form'">
                    <p class="section-kicker">Inscrição online</p>
                    <h2>{{ selectedEvent.title }}</h2>
                    <div class="modal-event-summary"><span>{{ selectedEvent.shortDate }}</span><strong>{{ selectedEvent.location }}</strong><small>{{ selectedEvent.price }} · {{ selectedEvent.available_spots }} vagas restantes</small></div>
                    <form class="registration-form" @submit.prevent="submitEventRegistration">
                        <label>Nome completo<input v-model="registrationForm.name" type="text" required /></label>
                        <label>WhatsApp<input v-model="registrationForm.phone" type="tel" required /></label>
                        <label>E-mail<input v-model="registrationForm.email" type="email" required /></label>
                        <label>Quantidade<input v-model.number="registrationForm.quantity" type="number" min="1" max="10" required /></label>
                        <label class="form-full">Observação opcional<textarea v-model="registrationForm.note" rows="2"></textarea></label>
                        <label class="terms-check"><input v-model="registrationForm.accepted" type="checkbox" required /> Aceito receber contato sobre esta inscrição.</label>
                        <p class="payment-note">{{ selectedEvent.is_paid ? 'A inscrição só será confirmada após validação do pagamento.' : 'Evento gratuito. Sua inscrição será enviada para confirmação.' }}</p>
                        <button type="submit" class="primary-cta form-full">Confirmar inscrição</button>
                    </form>
                </template>

                <template v-else>
                    <p class="section-kicker">Inscrição recebida</p>
                    <h2>Código de acompanhamento</h2>
                    <strong class="receipt-code">{{ registrationReceipt.registration_code }}</strong>
                    <p class="receipt-text">Guarde este código para acompanhar sua inscrição em {{ registrationReceipt.event_title }}.</p>
                    <div class="modal-actions"><button type="button" class="primary-cta" @click="copyRegistrationCode">Copiar código</button><button type="button" class="dark-cta" @click="openRegistrationTrackingModal">Acompanhar inscrição</button><button type="button" class="secondary-cta" @click="closeEventRegistration">Fechar</button></div>
                </template>
            </article>
        </div>

        <div v-if="detailsEvent" class="event-modal-backdrop" role="presentation" @click.self="closeEventDetails">
            <article class="event-modal details-modal" role="dialog" aria-modal="true" :aria-label="detailsEvent.title">
                <button type="button" class="modal-close" aria-label="Fechar detalhes" @click="closeEventDetails">×</button>
                <p class="section-kicker">Detalhes do evento</p>
                <h2>{{ detailsEvent.title }}</h2>
                <p>{{ detailsEvent.description }}</p>
                <div class="details-grid"><span><strong>Data</strong>{{ detailsEvent.time }}</span><span><strong>Local</strong>{{ detailsEvent.location }}</span><span><strong>Vagas</strong>{{ detailsEvent.available_spots }} de {{ detailsEvent.capacity }}</span><span><strong>Valor</strong>{{ detailsEvent.price }}</span></div>
                <button type="button" class="primary-cta" @click="openEventRegistration(detailsEvent.id)">Inscrever-se</button>
            </article>
        </div>

        <div v-if="showTrackingModal" class="event-modal-backdrop" role="presentation" @click.self="closeRegistrationTrackingModal">
            <article class="event-modal tracking-modal" role="dialog" aria-modal="true" aria-label="Acompanhar inscrição">
                <button type="button" class="modal-close" aria-label="Fechar acompanhamento" @click="closeRegistrationTrackingModal">×</button>
                <p class="section-kicker">Acompanhar inscrição</p>
                <h2>Digite o código recebido ao se inscrever.</h2>
                <div class="tracking-form"><input v-model="trackingCode" type="text" placeholder="EVT-2025-8F3K" @input="trackingCode = trackingCode.toUpperCase()" /><button type="button" class="primary-cta" @click="trackTemporaryRegistration">Buscar</button></div>
                <article v-if="trackingResult" class="tracking-result"><strong>{{ trackingResult.event_title }}</strong><p>Status: {{ trackingResult.status }}</p><small>{{ trackingResult.timeline[0].label }} em {{ trackingResult.timeline[0].date }}</small></article>
                <p v-else-if="trackingResult === false" class="tracking-empty">Não encontramos uma inscrição com este código.</p>
            </article>
        </div>

        <div v-if="showCalendarModal" class="event-modal-backdrop" role="presentation" @click.self="closeFullCalendarModal">
            <article class="event-modal calendar-modal" role="dialog" aria-modal="true" aria-label="Calendário completo de eventos">
                <button type="button" class="modal-close" aria-label="Fechar calendário" @click="closeFullCalendarModal">×</button>
                <p class="section-kicker">Calendário completo</p>
                <h2>Próximos eventos da Família Resgate</h2>
                <ul><li v-for="event in eventCards" :key="event.id"><strong>{{ event.shortDate }}</strong><span>{{ event.title }} · {{ event.location }}</span></li></ul>
            </article>
        </div>
    </div>
</template>

<style scoped>
.events-public-screen {
    position: relative;
    display: grid;
    grid-template-rows: auto 1fr;
    min-height: 100vh;
    overflow-x: hidden;
    padding: 0 clamp(24px, 3.4vw, 54px) clamp(20px, 2.4vw, 34px);
    color: #08162e;
    background:
        radial-gradient(circle at 68% 10%, rgba(246, 198, 91, 0.16), transparent 24%),
        linear-gradient(135deg, #fff8ea 0%, #f7f1e4 48%, #efe0c0 100%);
}

.events-public-screen::before {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image:
        linear-gradient(rgba(8, 22, 46, 0.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(8, 22, 46, 0.022) 1px, transparent 1px);
    background-size: 42px 42px;
    mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.38), transparent 72%);
}

.public-header,
.events-stage {
    position: relative;
    z-index: 1;
}

.public-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    border-bottom: 1px solid rgba(8, 22, 46, 0.08);
}

.brand-mark {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #06152f;
    text-decoration: none;
    text-transform: uppercase;
    transition: transform 260ms ease;
}

.brand-mark:hover,
.nav-item:hover,
.login-button:hover,
button:hover,
.event-card:hover,
.benefits-strip article:hover {
    transform: translateY(-2px);
}

.brand-logo {
    width: 44px;
    height: 44px;
    object-fit: contain;
}

.brand-mark small {
    display: block;
    color: #d89418;
    font-family: Georgia, serif;
    font-size: 0.62rem;
    letter-spacing: 0.12em;
}

.brand-mark strong {
    display: block;
    font-family: Georgia, serif;
    font-size: 1.28rem;
    line-height: 0.9;
}

.public-nav {
    display: inline-flex;
    align-items: center;
    gap: clamp(22px, 3vw, 52px);
}

.nav-item {
    position: relative;
    padding: 27px 0 24px;
    color: #06152f;
    font-size: 0.8rem;
    font-weight: 800;
    text-decoration: none;
    transition: color 260ms ease, transform 260ms ease;
}

.nav-item::after {
    content: '';
    position: absolute;
    right: 0;
    bottom: 14px;
    left: 0;
    height: 2px;
    border-radius: 999px;
    background: #d89418;
    transform: scaleX(0);
    transition: transform 260ms ease;
}

.nav-item.active,
.nav-item:hover {
    color: #d89418;
}

.nav-item.active::after,
.nav-item:hover::after {
    transform: scaleX(1);
}

.login-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 38px;
    padding: 0 18px;
    border-radius: 10px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    box-shadow: 0 8px 18px rgba(201, 120, 5, 0.24);
    font-size: 0.82rem;
    font-weight: 800;
    text-decoration: none;
    transition: transform 260ms ease, box-shadow 260ms ease;
}

.login-button svg {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.9;
}

.events-stage {
    display: grid;
    grid-template-rows: auto auto auto auto;
    gap: clamp(12px, 1.45vw, 22px);
    min-height: auto;
}

.events-hero {
    display: grid;
    grid-template-columns: minmax(255px, 0.55fr) minmax(620px, 1.45fr);
    gap: clamp(20px, 2.6vw, 38px);
    min-height: clamp(278px, 32vw, 420px);
}

.events-intro-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.section-kicker {
    margin: 0 0 5px;
    color: #d89418;
    font-size: 0.72rem;
    font-weight: 900;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.events-intro-card h1 {
    max-width: 330px;
    margin: 0;
    color: #06152f;
    font-family: Georgia, serif;
    font-size: clamp(2rem, 3vw, 3.18rem);
    line-height: 0.93;
}

.intro-line {
    width: 72px;
    height: 2px;
    margin: 12px 0 14px;
    border-radius: 999px;
    background: linear-gradient(90deg, #d89418, rgba(216, 148, 24, 0));
}

.intro-text {
    max-width: 390px;
    margin: 0 0 14px;
    color: #334155;
    font-size: 0.78rem;
    line-height: 1.55;
}

.event-pillars {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 8px;
    margin-bottom: 14px;
}

.event-pillar {
    display: grid;
    grid-template-columns: 34px 1fr;
    gap: 7px;
    align-items: center;
    min-width: 0;
}

.event-pillar > span {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border-radius: 50%;
    color: #fff8ea;
    background: #06152f;
}

.event-pillar:nth-child(2) > span {
    background: #d89418;
}

.event-pillar strong {
    display: block;
    color: #06152f;
    font-size: 0.68rem;
}

.event-pillar p {
    margin: 2px 0 0;
    color: #475569;
    font-size: 0.58rem;
    line-height: 1.35;
}

.intro-actions,
.featured-actions,
.modal-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.primary-cta,
.secondary-cta,
.dark-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 36px;
    padding: 0 16px;
    border: 0;
    border-radius: 9px;
    font-size: 0.72rem;
    font-weight: 900;
    text-decoration: none;
    cursor: pointer;
    transition: transform 260ms ease, box-shadow 260ms ease, border-color 260ms ease;
}

.primary-cta {
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    box-shadow: 0 8px 18px rgba(201, 120, 5, 0.26);
}

.secondary-cta {
    color: #06152f;
    border: 1px solid rgba(216, 148, 24, 0.42);
    background: rgba(255, 248, 234, 0.74);
}

.dark-cta {
    color: #fff8ea;
    border: 1px solid rgba(255, 248, 234, 0.28);
    background: rgba(6, 21, 47, 0.84);
}

.tracking-link {
    align-self: flex-start;
    margin-top: 8px;
    border: 0;
    color: #d89418;
    background: transparent;
    font-size: 0.7rem;
    font-weight: 900;
    cursor: pointer;
    transition: transform 260ms ease, color 260ms ease;
}

.featured-event-card {
    position: relative;
    min-height: clamp(278px, 32vw, 420px);
    overflow: hidden;
    border: 1px solid rgba(216, 148, 24, 0.36);
    border-radius: 20px;
    background: #06152f;
    box-shadow: 0 22px 45px rgba(8, 22, 46, 0.22);
}

.featured-event-card > img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.featured-event-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(6, 21, 47, 0.96) 0%, rgba(6, 21, 47, 0.78) 42%, rgba(6, 21, 47, 0.16) 100%);
}

.featured-overlay {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 620px;
    height: 100%;
    padding: 22px 28px;
    color: #fff8ea;
}

.status-badge,
.event-status {
    display: inline-flex;
    align-items: center;
    align-self: flex-start;
    min-height: 20px;
    padding: 0 9px;
    border-radius: 999px;
    font-size: 0.58rem;
    font-weight: 900;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.status-gold,
.status-warning {
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
}

.status-dark {
    color: #fff8ea;
    background: rgba(6, 21, 47, 0.86);
}

.status-free {
    color: #063319;
    background: #d6f5de;
}

.featured-overlay h2 {
    max-width: 470px;
    margin: 8px 0 6px;
    font-family: Georgia, serif;
    font-size: clamp(2.1rem, 4.1vw, 4.7rem);
    line-height: 0.84;
    text-transform: uppercase;
}

.featured-overlay h2 b {
    color: #f0a51d;
    font-size: 0.52em;
}

.featured-meta,
.featured-description {
    margin: 0 0 8px;
    color: #f8e7c0;
    font-size: 0.76rem;
}

.featured-description {
    max-width: 520px;
    line-height: 1.45;
}

.featured-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 8px;
    margin: 7px 0 12px;
    padding-top: 10px;
    border-top: 1px solid rgba(255, 248, 234, 0.18);
}

.featured-info-grid span {
    display: block;
    padding-right: 8px;
    border-right: 1px solid rgba(255, 248, 234, 0.15);
}

.featured-info-grid span:last-child {
    border-right: 0;
}

.featured-info-grid strong {
    display: block;
    color: #fff8ea;
    font-size: 0.66rem;
}

.featured-info-grid small {
    display: block;
    margin-top: 2px;
    color: #f8e7c0;
    font-size: 0.58rem;
    line-height: 1.3;
}

.featured-verse {
    position: absolute;
    right: 28px;
    bottom: 18px;
    z-index: 1;
    width: min(250px, 28%);
    padding: 16px 18px;
    border: 1px solid rgba(216, 148, 24, 0.54);
    border-radius: 14px;
    color: #fff8ea;
    background: rgba(6, 21, 47, 0.88);
    box-shadow: 0 16px 28px rgba(0, 0, 0, 0.24);
}

.featured-verse span {
    color: #f0a51d;
    font-size: 1.7rem;
    line-height: 1;
}

.featured-verse p {
    margin: 0 0 6px;
    font-family: Georgia, serif;
    font-size: 0.86rem;
    line-height: 1.35;
}

.featured-verse strong {
    color: #f0a51d;
    font-size: 0.65rem;
}

.events-list-section {
    min-height: 0;
}

.events-list-heading {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    margin-bottom: 5px;
    text-align: center;
}

.events-list-heading h2 {
    margin: 0;
    color: #06152f;
    font-family: Georgia, serif;
    font-size: 1.02rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.events-list-heading button,
.more-button {
    border: 0;
    color: #06152f;
    background: transparent;
    font-size: 0.66rem;
    font-weight: 800;
    cursor: pointer;
}

.event-filters {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 8px;
}

.event-filters button {
    min-height: 25px;
    padding: 0 15px;
    border: 1px solid rgba(8, 22, 46, 0.08);
    border-radius: 999px;
    color: #06152f;
    background: rgba(255, 248, 234, 0.78);
    font-size: 0.61rem;
    font-weight: 900;
    cursor: pointer;
    transition: transform 260ms ease, background 260ms ease, color 260ms ease;
}

.event-filters button.active {
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    box-shadow: 0 8px 16px rgba(201, 120, 5, 0.18);
}

.event-cards-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 11px;
}

.event-card {
    overflow: hidden;
    border: 1px solid rgba(216, 148, 24, 0.2);
    border-radius: 13px;
    background: rgba(255, 248, 234, 0.86);
    box-shadow: 0 12px 24px rgba(8, 22, 46, 0.08);
    transition: transform 260ms ease, box-shadow 260ms ease, border-color 260ms ease;
}

.event-card:hover {
    border-color: rgba(216, 148, 24, 0.48);
    box-shadow: 0 17px 30px rgba(8, 22, 46, 0.12);
}

.event-image-wrap {
    position: relative;
    height: 76px;
    overflow: hidden;
    background: #06152f;
}

.event-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.86;
}

.event-date {
    position: absolute;
    top: 8px;
    left: 8px;
    display: grid;
    width: 40px;
    height: 42px;
    place-items: center;
    border-radius: 8px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    font-size: 0.68rem;
    font-weight: 900;
    line-height: 1.05;
    text-align: center;
}

.event-status {
    position: absolute;
    left: 8px;
    bottom: 8px;
}

.interest-button {
    position: absolute;
    top: 8px;
    right: 8px;
    display: grid;
    width: 28px;
    height: 28px;
    place-items: center;
    border: 1px solid rgba(255, 248, 234, 0.45);
    border-radius: 50%;
    color: #fff8ea;
    background: rgba(6, 21, 47, 0.48);
    cursor: pointer;
}

.event-card-body {
    padding: 9px 10px 10px;
}

.event-card-body small {
    display: block;
    color: #c97805;
    font-size: 0.55rem;
    font-weight: 900;
    text-transform: uppercase;
}

.event-card-body h3 {
    margin: 2px 0 3px;
    color: #06152f;
    font-family: Georgia, serif;
    font-size: 1rem;
    line-height: 1.05;
}

.event-card-body p {
    min-height: 30px;
    margin: 0 0 6px;
    color: #334155;
    font-size: 0.64rem;
    line-height: 1.35;
}

.event-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 8px;
    color: #475569;
    font-size: 0.58rem;
}

.event-card-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.event-card-bottom strong {
    color: #06152f;
    font-size: 0.64rem;
}

.event-card-bottom button {
    min-height: 27px;
    padding: 0 12px;
    border: 0;
    border-radius: 7px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    font-size: 0.6rem;
    font-weight: 900;
    cursor: pointer;
}

.more-button {
    margin-top: 5px;
    padding: 0;
    color: #c97805;
}

.events-notice {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 26px;
    margin: 8px auto 0;
    border: 1px solid rgba(216, 148, 24, 0.18);
    border-radius: 999px;
    color: #334155;
    background: rgba(255, 248, 234, 0.7);
    font-size: 0.67rem;
}

.benefits-strip {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    align-items: center;
    overflow: hidden;
    border-radius: 14px;
    color: #fff8ea;
    background: #06152f;
    box-shadow: 0 16px 32px rgba(8, 22, 46, 0.16);
}

.benefits-strip article {
    display: grid;
    grid-template-columns: 36px 1fr;
    gap: 10px;
    align-items: center;
    min-height: 58px;
    padding: 0 18px;
    border-right: 1px solid rgba(255, 248, 234, 0.14);
    transition: transform 260ms ease;
}

.benefits-strip article:last-child {
    border-right: 0;
}

.benefits-strip span {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border: 1px solid rgba(216, 148, 24, 0.72);
    border-radius: 10px;
    color: #f0a51d;
}

.benefits-strip strong {
    display: block;
    font-size: 0.72rem;
}

.benefits-strip p {
    margin: 2px 0 0;
    color: #f8e7c0;
    font-size: 0.62rem;
}

.events-footer {
    display: grid;
    grid-template-columns: minmax(300px, 1.1fr) minmax(240px, 0.9fr) minmax(260px, 0.8fr);
    align-items: center;
    gap: 18px;
    padding: 10px 24px;
    border-radius: 16px 16px 0 0;
    color: #fff8ea;
    background: #06152f;
}

.footer-brand,
.marvvium-signature {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

.footer-brand img {
    width: 42px;
    height: 42px;
    object-fit: contain;
}

.footer-brand strong {
    display: block;
    font-family: Georgia, serif;
    font-size: 1.04rem;
}

.footer-brand small,
.marvvium-signature small {
    display: block;
    color: #f8e7c0;
    font-size: 0.64rem;
}

.footer-socials {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.footer-socials a {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border: 1px solid rgba(216, 148, 24, 0.54);
    border-radius: 50%;
    color: #f0a51d;
    text-decoration: none;
    transition: transform 260ms ease, background 260ms ease;
}

.footer-socials a:hover {
    transform: translateY(-3px);
    background: rgba(216, 148, 24, 0.12);
}

.marvvium-signature {
    justify-content: flex-end;
}

.marvvium-signature strong {
    display: block;
    text-align: right;
}

.marvvium-signature img {
    width: 74px;
    height: 34px;
    object-fit: contain;
}

.event-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 40;
    display: grid;
    place-items: center;
    padding: 24px;
    background: rgba(6, 21, 47, 0.58);
    backdrop-filter: blur(8px);
}

.event-modal {
    position: relative;
    width: min(560px, 94vw);
    max-height: 90vh;
    overflow: auto;
    padding: 28px;
    border: 1px solid rgba(216, 148, 24, 0.34);
    border-radius: 22px;
    color: #08162e;
    background: #fff8ea;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.28);
}

.event-modal h2 {
    margin: 0 0 12px;
    color: #06152f;
    font-family: Georgia, serif;
    font-size: 1.7rem;
    line-height: 1.05;
}

.modal-close {
    position: absolute;
    top: 14px;
    right: 16px;
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 50%;
    color: #fff8ea;
    background: #06152f;
    cursor: pointer;
}

.modal-event-summary,
.tracking-result {
    display: grid;
    gap: 4px;
    margin-bottom: 14px;
    padding: 12px;
    border-radius: 14px;
    color: #fff8ea;
    background: #06152f;
}

.modal-event-summary span {
    color: #f0a51d;
    font-weight: 900;
}

.modal-event-summary small,
.tracking-result small {
    color: #f8e7c0;
}

.registration-form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.registration-form label {
    display: grid;
    gap: 5px;
    color: #06152f;
    font-size: 0.72rem;
    font-weight: 900;
}

.registration-form input,
.registration-form textarea,
.tracking-form input {
    width: 100%;
    border: 1px solid rgba(8, 22, 46, 0.16);
    border-radius: 10px;
    padding: 10px 12px;
    background: rgba(255, 255, 255, 0.72);
    font-size: 0.82rem;
    outline: none;
}

.registration-form input:focus,
.registration-form textarea:focus,
.tracking-form input:focus {
    border-color: #d89418;
    box-shadow: 0 0 0 3px rgba(216, 148, 24, 0.14);
}

.form-full,
.terms-check,
.payment-note {
    grid-column: 1 / -1;
}

.terms-check {
    display: flex !important;
    grid-template-columns: unset !important;
    align-items: center;
    gap: 8px !important;
}

.terms-check input {
    width: auto;
}

.payment-note,
.receipt-text,
.tracking-empty {
    margin: 0;
    color: #475569;
    font-size: 0.78rem;
    line-height: 1.45;
}

.receipt-code {
    display: block;
    margin: 10px 0;
    padding: 14px;
    border-radius: 12px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    text-align: center;
    letter-spacing: 0.08em;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    margin: 16px 0;
}

.details-grid span {
    display: grid;
    gap: 3px;
    padding: 12px;
    border-radius: 12px;
    background: rgba(216, 148, 24, 0.1);
    font-size: 0.78rem;
}

.tracking-form {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    margin-bottom: 14px;
}

.calendar-modal ul {
    display: grid;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.calendar-modal li {
    display: grid;
    grid-template-columns: 74px 1fr;
    gap: 10px;
    align-items: center;
    padding: 10px;
    border-radius: 12px;
    background: rgba(216, 148, 24, 0.1);
}

.calendar-modal li strong {
    color: #c97805;
}

@media (max-width: 1280px) {
    .events-public-screen {
        height: auto;
        min-height: 100vh;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .events-stage {
        grid-template-rows: auto auto auto auto;
        gap: 14px;
        padding-bottom: 18px;
    }

    .events-hero,
    .event-cards-grid {
        grid-template-columns: 1fr;
    }

    .event-cards-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .featured-verse {
        width: 260px;
    }
}

@media (max-width: 760px) {
    .events-public-screen {
        grid-template-rows: auto 1fr;
        padding: 0 18px 18px;
    }

    .public-header,
    .public-nav,
    .events-footer,
    .benefits-strip,
    .event-pillars,
    .event-cards-grid {
        grid-template-columns: 1fr;
    }

    .public-header,
    .public-nav {
        flex-wrap: wrap;
        justify-content: center;
        padding: 12px 0;
    }

    .featured-verse {
        position: relative;
        right: auto;
        bottom: auto;
        width: auto;
        margin: 0 18px 18px;
    }

    .featured-info-grid,
    .benefits-strip,
    .events-footer,
    .registration-form,
    .details-grid,
    .tracking-form {
        grid-template-columns: 1fr;
    }
}
</style>
