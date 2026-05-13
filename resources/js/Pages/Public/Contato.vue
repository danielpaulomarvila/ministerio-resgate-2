<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrayerRequestModal from '../../Components/PrayerRequestModal.vue';
import { usePublicPageTransition } from '../../Composables/usePublicPageTransition';

defineProps({ canLogin: { type: Boolean, default: true } });

const publicPageElement = ref(null);
const showPrayerModal = ref(false);
const showPastoralVisitModal = ref(false);
const contactMessageSent = ref(false);
const pastoralVisitSent = ref(false);
const { navigatePublicPage } = usePublicPageTransition(publicPageElement);

// Conteúdo provisório da página Contato.
// Futuramente será carregado da Administração Geral → Site Público.
const contactPage = {
    kicker: 'Contato',
    title: 'Estamos aqui para te receber!',
    text: 'Será uma alegria ter você e sua família conosco. Fale conosco, tire suas dúvidas ou venha nos visitar!',
    address: 'Rua das Nações, 125, Centro - Sua Cidade/UF',
    mapsUrl: 'https://www.google.com/maps/search/?api=1&query=Rua%20das%20Na%C3%A7%C3%B5es%20125%20Centro',
    whatsappUrl: 'https://wa.me/550012345678',
    emailUrl: 'mailto:contato@familiaresgate.com.br',
};

// Imagem provisória de contato.
// Futuramente será substituída pela Administração Geral.
const contactHero = {
    image: '/images/hero/resgate-igreja-01.jpg',
    fallbackImage: '/images/hero/resgate-culto-05.jpg',
    alt: 'Fachada acolhedora da Família Resgate',
    verse: 'Portanto, acolhei-vos uns aos outros, como também Cristo nos acolheu, para a glória de Deus.',
    reference: 'Romanos 15:7',
};

const quickContacts = [
    { id: 'address', title: 'Endereço', text: ['Rua das Nações, 125', 'Centro - Sua Cidade/UF', 'CEP 00000-000'], icon: 'pin', action: 'openAddressInMap' },
    { id: 'phone', title: 'Telefone / WhatsApp', text: ['(00) 1234-5678', 'Segunda a sexta: 08h às 18h', 'Sábado: 08h às 12h'], icon: 'phone', action: 'openChurchWhatsApp' },
    { id: 'email', title: 'E-mail', text: ['contato@familiaresgate.com.br', 'Responderemos o mais breve possível.'], icon: 'mail', action: 'openChurchEmail' },
    { id: 'hours', title: 'Horários da Igreja', text: ['Domingo: 08h, 10h e 19h', 'Quarta-feira: 19h30', 'Sexta-feira (Jovens): 19h30'], icon: 'clock', action: null },
    { id: 'social', title: 'Redes Sociais', text: ['Acompanhe nossas redes e fique por dentro de tudo que acontece.'], icon: 'social', action: null },
];

const socialLinks = [
    { label: 'Instagram', icon: '◎', url: 'https://www.instagram.com/' },
    { label: 'Facebook', icon: 'f', url: 'https://www.facebook.com/' },
    { label: 'YouTube', icon: '▶', url: 'https://www.youtube.com/' },
    { label: 'WhatsApp', icon: '◌', url: contactPage.whatsappUrl },
];

// Mapa provisório. Futuramente o endereço e embed do mapa virão da Administração Geral → Site Público → Contato.
const visitTips = [
    'Recomendamos chegar 15 minutos antes do culto.',
    'Temos estacionamento no local.',
    'Ambiente acessível para toda a família.',
];

const contactFormSubjects = ['Dúvida', 'Pedido de informação', 'Quero visitar', 'Eventos', 'Outro assunto'];
const contactForm = ref({ name: '', email: '', phone: '', subject: '', message: '' });
const pastoralVisitForm = ref({ name: '', phone: '', preferredTime: '', reason: '', notes: '' });

function openDirections() {
    window.open(contactPage.mapsUrl, '_blank', 'noopener,noreferrer');
}

function openAddressInMap() {
    openDirections();
}

function openChurchWhatsApp() {
    window.open(contactPage.whatsappUrl, '_blank', 'noopener,noreferrer');
}

function openChurchEmail() {
    window.location.href = contactPage.emailUrl;
}

function openMap() {
    openDirections();
}

function openSocialNetwork(url) {
    window.open(url, '_blank', 'noopener,noreferrer');
}

function submitContactMessage() {
    if (!contactForm.value.name || !contactForm.value.email || !contactForm.value.subject || !contactForm.value.message) {
        return;
    }

    // Formulário temporário. Futuramente será integrado ao backend e Administração Geral → Site Público → Contato.
    const savedMessages = JSON.parse(localStorage.getItem('familia_resgate_contact_messages') ?? '[]');
    localStorage.setItem('familia_resgate_contact_messages', JSON.stringify([{ ...contactForm.value, created_at: new Date().toISOString() }, ...savedMessages]));
    contactMessageSent.value = true;
    contactForm.value = { name: '', email: '', phone: '', subject: '', message: '' };
}

function openPrayerModal() {
    showPrayerModal.value = true;
}

function closePrayerModal() {
    showPrayerModal.value = false;
}

function openPastoralVisitModal() {
    pastoralVisitSent.value = false;
    showPastoralVisitModal.value = true;
}

function closePastoralVisitModal() {
    showPastoralVisitModal.value = false;
}

function submitPastoralVisit() {
    if (!pastoralVisitForm.value.name || !pastoralVisitForm.value.phone || !pastoralVisitForm.value.reason) {
        return;
    }

    // Visita pastoral temporária. Futuramente será integrada ao backend e Administração Geral/Pastoral.
    localStorage.setItem('familia_resgate_pastoral_visit', JSON.stringify({ ...pastoralVisitForm.value, protocol: `VIS-${Date.now().toString().slice(-6)}` }));
    pastoralVisitSent.value = true;
    pastoralVisitForm.value = { name: '', phone: '', preferredTime: '', reason: '', notes: '' };
}
</script>

<template>
    <Head title="Contato" />

    <div ref="publicPageElement" class="contact-public-screen public-route-page">
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
                <Link class="nav-item active" href="/contato" @click="navigatePublicPage($event, '/contato')">Contato</Link>
            </nav>
            <Link v-if="canLogin" class="login-button" :href="$page.props.auth.user ? route('dashboard') : route('login')">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                Entrar
            </Link>
        </header>

        <main class="contact-stage">
            <section class="contact-hero" aria-label="Contato e acolhimento">
                <article class="contact-intro">
                    <p class="section-kicker">{{ contactPage.kicker }}</p>
                    <h1>Estamos aqui para <span>te receber!</span></h1>
                    <p>{{ contactPage.text }}</p>
                    <button type="button" class="primary-cta" @click="openDirections">Como Chegar</button>
                </article>

                <article class="contact-image-card">
                    <img :src="contactHero.image" :alt="contactHero.alt" loading="eager" @error="$event.target.src = contactHero.fallbackImage" />
                    <aside class="contact-verse-card"><span>“</span><p>{{ contactHero.verse }}</p><strong>{{ contactHero.reference }}</strong></aside>
                </article>
            </section>

            <section class="quick-contact-grid" aria-label="Informações rápidas de contato">
                <article v-for="item in quickContacts" :key="item.id" class="quick-contact-card" :class="{ clickable: item.action }" @click="item.action === 'openAddressInMap' ? openAddressInMap() : item.action === 'openChurchWhatsApp' ? openChurchWhatsApp() : item.action === 'openChurchEmail' ? openChurchEmail() : null">
                    <span class="quick-icon">{{ item.icon === 'pin' ? '⌖' : item.icon === 'phone' ? '☎' : item.icon === 'mail' ? '✉' : item.icon === 'clock' ? '◷' : '♧' }}</span>
                    <div>
                        <strong>{{ item.title }}</strong>
                        <p v-for="line in item.text" :key="line">{{ line }}</p>
                        <div v-if="item.id === 'social'" class="quick-socials">
                            <button v-for="social in socialLinks" :key="social.label" type="button" :aria-label="social.label" @click.stop="openSocialNetwork(social.url)">{{ social.icon }}</button>
                        </div>
                    </div>
                </article>
            </section>

            <section class="contact-content-grid">
                <article class="map-panel" aria-label="Onde estamos" @click="openMap">
                    <header><span>⌖</span><strong>Onde Estamos</strong></header>
                    <div class="map-visual">
                        <div class="map-lines"></div>
                        <span class="map-pin">⌖</span>
                        <aside><strong>Dicas para sua visita</strong><p v-for="tip in visitTips" :key="tip">✓ {{ tip }}</p></aside>
                    </div>
                </article>

                <article class="message-panel" aria-label="Envie sua mensagem">
                    <header><span>✈</span><strong>Envie sua mensagem</strong></header>
                    <form class="contact-form" @submit.prevent="submitContactMessage">
                        <label>Nome completo *<input v-model="contactForm.name" type="text" required /></label>
                        <label>E-mail *<input v-model="contactForm.email" type="email" required /></label>
                        <label>Telefone / WhatsApp<input v-model="contactForm.phone" type="tel" /></label>
                        <label>Assunto *<select v-model="contactForm.subject" required><option value="" disabled>Selecione</option><option v-for="subject in contactFormSubjects" :key="subject" :value="subject">{{ subject }}</option></select></label>
                        <label class="form-full">Mensagem *<textarea v-model="contactForm.message" rows="4" placeholder="Escreva sua mensagem aqui..." required></textarea></label>
                        <p v-if="contactMessageSent" class="success-message">Mensagem enviada com sucesso. Nossa equipe entrará em contato assim que possível.</p>
                        <button type="submit" class="primary-cta">Enviar Mensagem</button>
                    </form>
                </article>

                <aside class="other-contact-panel" aria-label="Outras formas de contato">
                    <h2>Outras formas de contato</h2>
                    <article><span>♢</span><div><strong>Pedir Oração</strong><p>Estamos aqui por você. Envie seu pedido de oração.</p><button type="button" @click="openPrayerModal">Pedir Oração</button></div></article>
                    <article><span>⌂</span><div><strong>Visita Pastoral</strong><p>Deseja uma visita pastoral? Fale com nossa equipe.</p><button type="button" @click="openPastoralVisitModal">Solicitar Visita</button></div></article>
                </aside>
            </section>

            <footer class="contact-footer">
                <div class="footer-brand"><img src="/images/brand/logo-resgate-gold.png" alt="Logo Família Resgate" /><span><strong>Família Resgate</strong><small>Uma família que existe para resgatar vidas para Jesus.</small></span></div>
                <div class="footer-socials" aria-label="Redes sociais"><button v-for="social in socialLinks" :key="social.label" type="button" :aria-label="social.label" @click="openSocialNetwork(social.url)">{{ social.icon }}</button></div>
                <div class="marvvium-signature" aria-label="Plataforma desenvolvida por Marvvium"><span><small>Plataforma desenvolvida por</small><strong>Marvvium</strong></span><img src="/images/brand/logo-marvvium.png" alt="Marvvium" /></div>
            </footer>
        </main>

        <PrayerRequestModal :show="showPrayerModal" @close="closePrayerModal" />

        <div v-if="showPastoralVisitModal" class="contact-modal-backdrop" role="presentation" @click.self="closePastoralVisitModal">
            <article class="contact-modal" role="dialog" aria-modal="true" aria-label="Solicitar visita pastoral">
                <button type="button" class="modal-close" aria-label="Fechar visita pastoral" @click="closePastoralVisitModal">×</button>
                <p class="section-kicker">Visita pastoral</p>
                <h2>Solicitar visita</h2>
                <form v-if="!pastoralVisitSent" class="modal-form" @submit.prevent="submitPastoralVisit"><label>Nome<input v-model="pastoralVisitForm.name" type="text" required /></label><label>WhatsApp<input v-model="pastoralVisitForm.phone" type="tel" required /></label><label>Melhor dia/horário<input v-model="pastoralVisitForm.preferredTime" type="text" /></label><label>Motivo<textarea v-model="pastoralVisitForm.reason" rows="3" required></textarea></label><label>Observações<textarea v-model="pastoralVisitForm.notes" rows="2"></textarea></label><button type="submit" class="primary-cta">Solicitar visita</button></form>
                <p v-else class="success-message">Solicitação recebida. Nossa equipe pastoral entrará em contato em breve.</p>
            </article>
        </div>
    </div>
</template>

<style scoped>
.contact-public-screen {
    position: relative;
    display: grid;
    grid-template-rows: auto 1fr;
    min-height: 100vh;
    overflow-x: hidden;
    padding: 0 clamp(24px, 3.4vw, 54px) clamp(18px, 2vw, 30px);
    color: #08162e;
    background:
        radial-gradient(circle at 76% 12%, rgba(246, 198, 91, 0.16), transparent 22%),
        linear-gradient(135deg, #fff8ea 0%, #f7f1e4 48%, #efe0c0 100%);
}

.contact-public-screen::before {
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
.contact-stage {
    position: relative;
    z-index: 1;
}

.public-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    min-height: 78px;
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
.quick-contact-card.clickable:hover,
.map-panel:hover {
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

.login-button,
.primary-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 38px;
    padding: 0 18px;
    border: 0;
    border-radius: 10px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    box-shadow: 0 8px 18px rgba(201, 120, 5, 0.24);
    font-size: 0.78rem;
    font-weight: 900;
    text-decoration: none;
    cursor: pointer;
    transition: transform 260ms ease, box-shadow 260ms ease;
}

.login-button svg {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.9;
}

.contact-stage {
    display: grid;
    grid-template-rows: auto auto auto auto;
    gap: clamp(12px, 1.4vw, 20px);
    padding-top: 14px;
}

.contact-hero {
    display: grid;
    grid-template-columns: minmax(280px, 0.48fr) minmax(640px, 1.52fr);
    gap: clamp(22px, 3vw, 42px);
}

.contact-intro {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.section-kicker {
    margin: 0 0 6px;
    color: #d89418;
    font-size: 0.72rem;
    font-weight: 900;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.contact-intro h1 {
    max-width: 430px;
    margin: 0 0 14px;
    color: #06152f;
    font-family: Georgia, serif;
    font-size: clamp(2.35rem, 4vw, 4.15rem);
    line-height: 0.92;
}

.contact-intro h1 span {
    color: #d89418;
}

.contact-intro p {
    max-width: 410px;
    margin: 0 0 18px;
    color: #334155;
    font-size: 0.86rem;
    line-height: 1.55;
}

.contact-intro .primary-cta {
    align-self: flex-start;
    background: #06152f;
    box-shadow: 0 12px 24px rgba(8, 22, 46, 0.18);
}

.contact-image-card {
    position: relative;
    min-height: clamp(260px, 28vw, 410px);
    overflow: hidden;
    border: 1px solid rgba(216, 148, 24, 0.36);
    border-radius: 20px;
    background: #06152f;
    box-shadow: 0 22px 45px rgba(8, 22, 46, 0.2);
}

.contact-image-card > img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.88;
}

.contact-image-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(6, 21, 47, 0.08), rgba(6, 21, 47, 0.34) 58%, rgba(6, 21, 47, 0.86));
}

.contact-verse-card {
    position: absolute;
    top: 22px;
    right: 28px;
    bottom: 22px;
    z-index: 1;
    display: flex;
    width: min(290px, 34%);
    flex-direction: column;
    justify-content: center;
    padding: 22px;
    border: 1px solid rgba(216, 148, 24, 0.32);
    border-radius: 16px;
    color: #fff8ea;
    background: rgba(6, 21, 47, 0.78);
}

.contact-verse-card span {
    color: #f0a51d;
    font-size: 2rem;
}

.contact-verse-card p {
    margin: 0 0 14px;
    font-family: Georgia, serif;
    font-size: 1.05rem;
    line-height: 1.42;
}

.contact-verse-card strong {
    padding-top: 12px;
    border-top: 1px solid rgba(216, 148, 24, 0.36);
    color: #f0a51d;
    font-size: 0.72rem;
}

.quick-contact-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 12px;
}

.quick-contact-card {
    display: grid;
    grid-template-columns: 42px 1fr;
    gap: 10px;
    min-height: 118px;
    padding: 18px 16px;
    border: 1px solid rgba(216, 148, 24, 0.18);
    border-radius: 16px;
    background: rgba(255, 248, 234, 0.78);
    box-shadow: 0 12px 24px rgba(8, 22, 46, 0.06);
    transition: transform 260ms ease, border-color 260ms ease, box-shadow 260ms ease;
}

.quick-contact-card.clickable {
    cursor: pointer;
}

.quick-contact-card:hover {
    border-color: rgba(216, 148, 24, 0.46);
    box-shadow: 0 16px 28px rgba(8, 22, 46, 0.1);
}

.quick-icon {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border: 1px solid rgba(216, 148, 24, 0.48);
    border-radius: 50%;
    color: #d89418;
    font-size: 1.2rem;
}

.quick-contact-card strong {
    display: block;
    margin-bottom: 7px;
    color: #06152f;
    font-size: 0.72rem;
    font-weight: 900;
    text-transform: uppercase;
}

.quick-contact-card p {
    margin: 0 0 4px;
    color: #334155;
    font-size: 0.68rem;
    line-height: 1.35;
}

.quick-socials,
.footer-socials {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

.quick-socials button,
.footer-socials button {
    display: grid;
    width: 28px;
    height: 28px;
    place-items: center;
    border: 1px solid rgba(216, 148, 24, 0.54);
    border-radius: 50%;
    color: #f0a51d;
    background: #06152f;
    cursor: pointer;
}

.contact-content-grid {
    display: grid;
    grid-template-columns: minmax(330px, 0.95fr) minmax(380px, 1.05fr) minmax(250px, 0.62fr);
    gap: 14px;
    align-items: stretch;
}

.map-panel,
.message-panel,
.other-contact-panel {
    border-radius: 18px;
    box-shadow: 0 14px 28px rgba(8, 22, 46, 0.08);
}

.map-panel {
    overflow: hidden;
    color: #fff8ea;
    background: #06152f;
    cursor: pointer;
    transition: transform 260ms ease, box-shadow 260ms ease;
}

.map-panel header,
.message-panel header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    color: #06152f;
    font-size: 0.78rem;
    font-weight: 900;
    text-transform: uppercase;
}

.map-panel header {
    color: #fff8ea;
}

.map-panel header span,
.message-panel header span {
    color: #d89418;
    font-size: 1.15rem;
}

.map-visual {
    position: relative;
    min-height: 230px;
    margin: 0 16px 16px;
    overflow: hidden;
    border-radius: 14px;
    background:
        linear-gradient(35deg, transparent 48%, rgba(216, 148, 24, 0.32) 49%, rgba(216, 148, 24, 0.32) 51%, transparent 52%),
        linear-gradient(120deg, transparent 46%, rgba(8, 22, 46, 0.12) 47%, rgba(8, 22, 46, 0.12) 53%, transparent 54%),
        #e8efe4;
}

.map-lines {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(32deg, transparent 45%, rgba(8, 22, 46, 0.22) 46%, transparent 48%),
        linear-gradient(112deg, transparent 40%, rgba(8, 22, 46, 0.15) 41%, transparent 43%);
    background-size: 150px 80px;
}

.map-pin {
    position: absolute;
    top: 44%;
    left: 56%;
    display: grid;
    width: 44px;
    height: 44px;
    place-items: center;
    border-radius: 50%;
    color: #fff8ea;
    background: linear-gradient(135deg, #f0a51d, #c97805);
    box-shadow: 0 12px 22px rgba(201, 120, 5, 0.32);
}

.map-visual aside {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 210px;
    padding: 16px;
    color: #fff8ea;
    background: rgba(6, 21, 47, 0.92);
}

.map-visual aside strong {
    display: block;
    margin-bottom: 8px;
    color: #f0a51d;
    font-size: 0.72rem;
    text-transform: uppercase;
}

.map-visual aside p {
    margin: 0 0 7px;
    color: #f8e7c0;
    font-size: 0.66rem;
    line-height: 1.35;
}

.message-panel {
    background: rgba(255, 248, 234, 0.82);
    border: 1px solid rgba(216, 148, 24, 0.16);
}

.contact-form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    padding: 0 18px 18px;
}

.contact-form label,
.modal-form label {
    display: grid;
    gap: 5px;
    color: #334155;
    font-size: 0.68rem;
    font-weight: 800;
}

.contact-form input,
.contact-form select,
.contact-form textarea,
.modal-form input,
.modal-form textarea {
    width: 100%;
    border: 1px solid rgba(8, 22, 46, 0.12);
    border-radius: 10px;
    padding: 10px 12px;
    color: #08162e;
    background: rgba(255, 255, 255, 0.7);
    font-size: 0.78rem;
    outline: none;
}

.contact-form input:focus,
.contact-form select:focus,
.contact-form textarea:focus,
.modal-form input:focus,
.modal-form textarea:focus {
    border-color: #d89418;
    box-shadow: 0 0 0 3px rgba(216, 148, 24, 0.14);
}

.form-full,
.success-message {
    grid-column: 1 / -1;
}

.success-message {
    margin: 0;
    color: #0f5132;
    font-size: 0.76rem;
    font-weight: 800;
}

.other-contact-panel {
    padding: 18px;
    color: #fff8ea;
    background: #06152f;
}

.other-contact-panel h2 {
    margin: 0 0 14px;
    color: #f0a51d;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.other-contact-panel article {
    display: grid;
    grid-template-columns: 38px 1fr;
    gap: 12px;
    padding: 16px 0;
    border-bottom: 1px solid rgba(255, 248, 234, 0.12);
}

.other-contact-panel article:last-child {
    border-bottom: 0;
}

.other-contact-panel article > span {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    color: #f0a51d;
    font-size: 1.35rem;
}

.other-contact-panel strong {
    display: block;
    font-size: 0.78rem;
    text-transform: uppercase;
}

.other-contact-panel p {
    margin: 4px 0 10px;
    color: #f8e7c0;
    font-size: 0.68rem;
    line-height: 1.4;
}

.other-contact-panel button {
    min-height: 32px;
    padding: 0 16px;
    border: 1px solid rgba(216, 148, 24, 0.58);
    border-radius: 8px;
    color: #f0a51d;
    background: transparent;
    font-size: 0.68rem;
    font-weight: 900;
    cursor: pointer;
}

.contact-footer {
    display: grid;
    grid-template-columns: minmax(300px, 1.1fr) minmax(240px, 0.9fr) minmax(260px, 0.8fr);
    align-items: center;
    gap: 18px;
    padding: 14px 26px;
    border-radius: 16px;
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
    justify-content: center;
    gap: 20px;
    margin-top: 0;
}

.footer-socials button {
    width: 36px;
    height: 36px;
    background: transparent;
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

.contact-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 40;
    display: grid;
    place-items: center;
    padding: 24px;
    background: rgba(6, 21, 47, 0.58);
    backdrop-filter: blur(8px);
}

.contact-modal {
    position: relative;
    width: min(540px, 94vw);
    max-height: 90vh;
    overflow: auto;
    padding: 28px;
    border: 1px solid rgba(216, 148, 24, 0.34);
    border-radius: 22px;
    color: #08162e;
    background: #fff8ea;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.28);
}

.contact-modal h2 {
    margin: 0 0 14px;
    color: #06152f;
    font-family: Georgia, serif;
    font-size: 1.7rem;
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

.modal-form {
    display: grid;
    gap: 10px;
}

@media (max-width: 1280px) {
    .contact-hero,
    .quick-contact-grid,
    .contact-content-grid {
        grid-template-columns: 1fr;
    }

    .quick-contact-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .contact-content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 760px) {
    .contact-public-screen {
        padding: 0 18px 18px;
    }

    .public-header,
    .public-nav {
        flex-wrap: wrap;
        justify-content: center;
        padding: 12px 0;
    }

    .quick-contact-grid,
    .contact-form,
    .contact-footer {
        grid-template-columns: 1fr;
    }

    .contact-verse-card {
        position: relative;
        top: auto;
        right: auto;
        bottom: auto;
        width: auto;
        margin: 18px;
    }

    .map-visual aside {
        position: relative;
        width: auto;
    }
}
</style>
