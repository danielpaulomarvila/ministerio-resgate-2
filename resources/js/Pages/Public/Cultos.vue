<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePublicPageTransition } from '../../Composables/usePublicPageTransition';

defineProps({ canLogin: { type: Boolean, default: true } });

const publicPageElement = ref(null);
const selectedService = ref(null);
const activeHeroSlide = ref(0);
const heroImageErrors = ref(new Set());
const { navigatePublicPage } = usePublicPageTransition(publicPageElement);

// Conteúdo provisório da página Cultos.
// Futuramente será carregado da Administração Geral → Site Público.
const servicePage = {
    kicker: 'Cultos',
    title: 'Nós existimos para exaltar a Deus',
    text: 'Nossos cultos são momentos de encontro com Deus, comunhão, adoração e ensino da Palavra que transforma vidas.',
    verse: 'Deus é Espírito, e é necessário que os que o adoram o adorem em espírito e em verdade.',
    reference: 'João 4:24',
};

const worshipHighlights = [
    { title: 'Adoramos', text: 'Com alegria e reverência ao nosso Deus.', icon: 'heart' },
    { title: 'Aprendemos', text: 'A Palavra que edifica e transforma.', icon: 'book' },
];

// Imagem provisória de culto.
// Futuramente será substituída pela Administração Geral.
const heroSlides = [
    { image: '/images/hero/resgate-louvor-10.jpg', title: 'Família em adoração', position: 'center 48%' },
    { image: '/images/hero/resgate-culto-05.jpg', title: 'Culto e Palavra', position: 'center' },
    { image: '/images/hero/resgate-adoracao-02.jpg', title: 'Momento de louvor', position: 'center' },
];

// Cards provisórios de cultos semanais, preparados para futura edição pela Administração Geral.
const serviceCards = [
    { id: 'culto-familia', title: 'Culto da Família', day: 'Domingo', time: '19h00', description: 'O principal culto da semana. Louvor, Palavra e ministração.', tag: 'Para toda a família', icon: 'cross', tone: 'dark' },
    { id: 'oracao-manha', title: 'Oração da Manhã', day: 'Terça-feira', time: '06h00', description: 'Comece o dia buscando a Deus em oração e comunhão.', tag: 'Aberto a todos', icon: 'pray', tone: 'gold' },
    { id: 'estudo-biblico', title: 'Estudo Bíblico', day: 'Quarta-feira', time: '19h30', description: 'Estudo profundo da Palavra para edificação da fé.', tag: 'Para todas as idades', icon: 'book', tone: 'dark' },
    { id: 'culto-jovens', title: 'Culto de Jovens', day: 'Sexta-feira', time: '19h30', description: 'Um tempo especial para jovens, louvor e Palavra.', tag: 'Jovens e Adolescentes', icon: 'people', tone: 'gold' },
    { id: 'culto-infantil', title: 'Culto Infantil', day: 'Domingo', time: '10h00', description: 'Ensino, diversão e aprendizado da Palavra para crianças.', tag: 'Crianças', icon: 'kids', tone: 'dark' },
];

const practicalInfo = [
    { title: 'Chegue cedo', text: 'Recomendamos chegar 15 minutos antes do início.', icon: 'clock' },
    { title: 'Traga sua família', text: 'Nossos cultos são para todas as idades. Todos são bem-vindos!', icon: 'family' },
    { title: 'Ambiente familiar', text: 'Lugar seguro, acolhedor e cheio do amor de Deus.', icon: 'heart' },
    { title: 'Onde estamos', text: 'Rua das Nações, 125 - Centro · Sua Cidade - UF', icon: 'pin' },
];

function goToPreviousServiceHeroSlide() {
    activeHeroSlide.value = (activeHeroSlide.value - 1 + heroSlides.length) % heroSlides.length;
}

function goToNextServiceHeroSlide() {
    activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.length;
}

function markHeroImageError(index) {
    const nextErrors = new Set(heroImageErrors.value);
    nextErrors.add(index);
    heroImageErrors.value = nextErrors;
}

function openServiceDetails(serviceId) {
    selectedService.value = serviceCards.find((service) => service.id === serviceId) ?? null;
}

function closeServiceDetails() {
    selectedService.value = null;
}
</script>

<template>
    <Head title="Cultos" />

    <div ref="publicPageElement" class="public-home-screen public-route-page">
        <header class="public-header">
            <Link class="brand-mark" href="/inicio" aria-label="Família Resgate" @click="navigatePublicPage($event, '/inicio')">
                <img class="brand-logo" src="/images/brand/logo-resgate-gold.png" alt="Logo Ministério Resgate" />
                <span><small>Família</small><strong>Resgate</strong></span>
            </Link>
            <nav class="public-nav" aria-label="Navegação pública">
                <Link class="nav-item" href="/inicio" @click="navigatePublicPage($event, '/inicio')">Início</Link>
                <Link class="nav-item" href="/sobre_nos" @click="navigatePublicPage($event, '/sobre_nos')">Sobre Nós</Link>
                <Link class="nav-item active" href="/cultos" @click="navigatePublicPage($event, '/cultos')">Cultos</Link>
                <Link class="nav-item" href="/eventos" @click="navigatePublicPage($event, '/eventos')">Eventos</Link>
                <Link class="nav-item" href="/contato" @click="navigatePublicPage($event, '/contato')">Contato</Link>
            </nav>
            <Link v-if="canLogin" class="login-button" :href="$page.props.auth.user ? route('dashboard') : route('login')">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                Entrar
            </Link>
        </header>

        <main class="public-stage">
            <section class="service-hero" aria-label="Convite para os cultos da Família Resgate">
                <article class="service-copy">
                    <p class="section-kicker">{{ servicePage.kicker }}</p>
                    <h1>{{ servicePage.title }}</h1>
                    <span class="service-title-line"></span>
                    <p class="service-intro">{{ servicePage.text }}</p>
                    <div class="worship-highlights">
                        <article v-for="highlight in worshipHighlights" :key="highlight.title" class="worship-highlight-card">
                            <span><svg viewBox="0 0 48 48" aria-hidden="true"><path v-if="highlight.icon === 'heart'" d="M24 39s-14-8.5-14-20a8 8 0 0 1 14-5 8 8 0 0 1 14 5c0 11.5-14 20-14 20Z" /><path v-else d="M8 10h14c4 0 6 2 6 6v22c0-4-2-6-6-6H8ZM40 10H28c-4 0-6 2-6 6v22c0-4 2-6 6-6h12Z" /></svg></span>
                            <div><strong>{{ highlight.title }}</strong><p>{{ highlight.text }}</p></div>
                        </article>
                    </div>
                    <div class="service-actions">
                        <Link class="primary-cta" href="/eventos" @click="navigatePublicPage($event, '/eventos')">Ver Próximos Eventos</Link>
                        <Link class="secondary-cta" href="/contato" @click="navigatePublicPage($event, '/contato')">Como Chegar</Link>
                    </div>
                </article>

                <section class="service-visual" aria-label="Imagem de culto e convite">
                    <article v-for="(slide, index) in heroSlides" :key="slide.image" class="service-slide" :class="{ active: activeHeroSlide === index }">
                        <img v-if="!heroImageErrors.has(index)" :src="slide.image" :alt="slide.title" :style="{ objectPosition: slide.position }" loading="eager" @error="markHeroImageError(index)" />
                    </article>
                    <button type="button" class="service-arrow service-arrow-left" aria-label="Imagem anterior" @click="goToPreviousServiceHeroSlide">‹</button>
                    <button type="button" class="service-arrow service-arrow-right" aria-label="Próxima imagem" @click="goToNextServiceHeroSlide">›</button>
                    <article class="service-verse-card"><span>“</span><p>{{ servicePage.verse }}</p><strong>{{ servicePage.reference }}</strong></article>
                    <article class="service-invite-card"><span>♁</span><strong>Venha participar</strong><p>Você e sua família são sempre bem-vindos!</p><small>Traga sua fé, seus amigos e venha viver o agir de Deus.</small></article>
                    <div class="service-slide-dots" aria-hidden="true"><span v-for="(slide, index) in heroSlides" :key="`${slide.image}-dot`" :class="{ active: activeHeroSlide === index }"></span></div>
                </section>
            </section>

            <section class="services-section" aria-label="Nossos cultos semanais">
                <header class="services-heading"><h2>Nossos Cultos</h2><p>Programação semanal para toda a família</p></header>
                <div class="service-cards-grid">
                    <button v-for="service in serviceCards" :key="service.id" type="button" class="service-card" :class="`service-card-${service.tone}`" @click="openServiceDetails(service.id)">
                        <span class="service-card-icon">{{ service.icon === 'cross' ? '✝' : service.icon === 'pray' ? '♢' : service.icon === 'book' ? '▣' : service.icon === 'people' ? '♧' : '∞' }}</span>
                        <span class="service-card-content"><strong>{{ service.title }}</strong><small>{{ service.day }}</small><b>{{ service.time }}</b><p>{{ service.description }}</p><em>{{ service.tag }}</em></span>
                    </button>
                </div>
            </section>

            <section class="practical-strip" aria-label="Informações práticas dos cultos">
                <article v-for="info in practicalInfo" :key="info.title" class="practical-card"><span>{{ info.icon === 'clock' ? '◷' : info.icon === 'family' ? '♧' : info.icon === 'heart' ? '♡' : '⌖' }}</span><div><strong>{{ info.title }}</strong><p>{{ info.text }}</p></div></article>
            </section>

            <footer class="service-footer">
                <div class="footer-brand"><img src="/images/brand/logo-resgate-gold.png" alt="Logo Família Resgate" /><span><strong>Família Resgate</strong><small>Uma família que existe para resgatar vidas para Jesus.</small></span></div>
                <div class="footer-socials" aria-label="Redes sociais"><a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">◎</a><a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">f</a><a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="YouTube">▶</a><a href="https://wa.me/550012345678" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">◌</a></div>
                <div class="marvvium-signature" aria-label="Plataforma desenvolvida por Marvvium"><span><small>Plataforma desenvolvida por</small><strong>Marvvium</strong></span><img src="/images/brand/logo-marvvium.png" alt="Marvvium" /></div>
            </footer>
        </main>

        <div v-if="selectedService" class="service-modal-backdrop" role="presentation" @click.self="closeServiceDetails"><article class="service-modal" role="dialog" aria-modal="true" :aria-label="selectedService.title"><button type="button" aria-label="Fechar detalhes do culto" @click="closeServiceDetails">×</button><p class="section-kicker">Detalhes do culto</p><h2>{{ selectedService.title }}</h2><strong>{{ selectedService.day }} · {{ selectedService.time }}</strong><p>{{ selectedService.description }}</p><em>{{ selectedService.tag }}</em></article></div>
    </div>
</template>

<style scoped>
.public-home-screen {
    position: relative;
    display: grid;
    grid-template-rows: auto 1fr;
    min-height: 100vh;
    overflow-x: hidden;
    padding: 0 clamp(26px, 3.6vw, 56px) clamp(24px, 3vw, 42px);
    color: #08162e;
    background:
        radial-gradient(circle at 72% 16%, rgba(246, 198, 91, 0.18), transparent 22%),
        radial-gradient(circle at 12% 28%, rgba(255, 255, 255, 0.75), transparent 25%),
        linear-gradient(135deg, #fff8ea 0%, #f7f1e4 48%, #efe0c0 100%);
}

.public-home-screen::before {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image:
        linear-gradient(rgba(8, 22, 46, 0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(8, 22, 46, 0.025) 1px, transparent 1px);
    background-size: 42px 42px;
    mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.4), transparent 72%);
}

.public-header,
.public-stage {
    position: relative;
    z-index: 1;
    width: min(100%, 1460px);
    margin-inline: auto;
}

.public-header {
    display: grid;
    grid-template-columns: minmax(260px, 0.82fr) minmax(500px, 1.24fr) minmax(126px, 0.38fr);
    align-items: center;
    gap: 18px;
    min-height: 86px;
}

.brand-mark {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #08162e;
    text-decoration: none;
}

.brand-logo {
    width: clamp(62px, 5.2vw, 76px);
    height: clamp(54px, 4.8vw, 68px);
    object-fit: contain;
    transform: scale(1.95);
}

.brand-mark span {
    display: grid;
    line-height: 1;
}

.brand-mark small {
    color: #b87918;
    font-size: 0.76rem;
    font-weight: 900;
    letter-spacing: 0.18em;
    text-transform: uppercase;
}

.brand-mark strong,
.service-copy h1,
.services-heading h2,
.service-modal h2 {
    font-family: Georgia, 'Times New Roman', serif;
}

.brand-mark strong {
    font-size: clamp(1.28rem, 1.75vw, 1.68rem);
    text-transform: uppercase;
}

.public-nav {
    display: flex;
    justify-content: center;
    gap: clamp(24px, 3.6vw, 56px);
}

.nav-item {
    position: relative;
    color: #08162e;
    font-size: 1rem;
    font-weight: 800;
    text-decoration: none;
    transition: color 260ms ease, transform 260ms ease;
}

.nav-item:hover,
.nav-item.active {
    color: #d97706;
    transform: translateY(-1px);
}

.nav-item.active::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -16px;
    width: 66px;
    height: 2px;
    border-radius: 999px;
    background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    transform: translateX(-50%);
}

.login-button {
    justify-self: end;
    min-width: 132px;
    min-height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 16px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f6c65b, #f59e0b 62%, #d97706);
    box-shadow: 0 14px 30px rgba(217, 119, 6, 0.26);
    font-weight: 900;
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

.public-stage {
    display: grid;
    grid-template-rows: auto auto auto auto;
    gap: clamp(18px, 2vw, 28px);
    min-height: auto;
}

.service-hero {
    display: grid;
    grid-template-columns: minmax(320px, 0.86fr) minmax(650px, 1.68fr);
    gap: clamp(22px, 3vw, 42px);
    min-height: clamp(360px, 43vw, 560px);
}

.service-copy {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.section-kicker {
    margin: 0;
    color: #c48a1d;
    font-size: 0.74rem;
    font-weight: 900;
    letter-spacing: 0.18em;
    text-transform: uppercase;
}

.service-copy h1 {
    max-width: 470px;
    margin: 4px 0 0;
    color: #06152f;
    font-size: clamp(2.2rem, 4vw, 4.5rem);
    line-height: 0.9;
    letter-spacing: -0.045em;
    text-transform: uppercase;
}

.service-copy h1::first-letter {
    letter-spacing: -0.06em;
}

.service-copy h1 {
    text-wrap: balance;
}

.service-copy h1::after {
    content: '';
}

.service-title-line {
    width: 60px;
    height: 2px;
    margin: 12px 0;
    border-radius: 999px;
    background: linear-gradient(90deg, #d8a241, rgba(216, 162, 65, 0));
}

.service-intro {
    max-width: 510px;
    margin: 0 0 14px;
    color: #1d2939;
    font-size: clamp(0.82rem, 0.85vw, 0.96rem);
    font-weight: 650;
    line-height: 1.55;
}

.worship-highlights {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.worship-highlight-card {
    display: grid;
    grid-template-columns: 42px 1fr;
    align-items: center;
    gap: 10px;
}

.worship-highlight-card span {
    width: 38px;
    height: 38px;
    color: #d8a241;
}

.worship-highlight-card svg {
    width: 100%;
    height: 100%;
    fill: none;
    stroke: currentColor;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-width: 2;
}

.worship-highlight-card strong {
    display: block;
    color: #06152f;
    font-size: 0.78rem;
    font-weight: 950;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.worship-highlight-card p {
    margin: 2px 0 0;
    color: #334155;
    font-size: 0.72rem;
    line-height: 1.35;
}

.service-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.primary-cta,
.secondary-cta {
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    padding: 0 18px;
    font-size: 0.82rem;
    font-weight: 900;
    text-decoration: none;
    transition: transform 260ms ease, box-shadow 260ms ease, border-color 260ms ease;
}

.primary-cta {
    color: #fff8ea;
    background: linear-gradient(135deg, #f6c65b, #f59e0b 58%, #d97706);
    box-shadow: 0 12px 25px rgba(217, 119, 6, 0.25);
}

.secondary-cta {
    color: #06152f;
    border: 1px solid rgba(216, 162, 65, 0.48);
    background: rgba(255, 248, 234, 0.72);
}

.service-visual {
    position: relative;
    min-height: clamp(340px, 40vw, 540px);
    overflow: hidden;
    border: 1px solid rgba(216, 162, 65, 0.34);
    border-radius: 24px;
    background: #06152f;
    box-shadow: 0 24px 48px rgba(8, 22, 46, 0.22);
}

.service-slide,
.service-slide img,
.service-slide::after {
    position: absolute;
    inset: 0;
}

.service-slide {
    opacity: 0;
    background: linear-gradient(135deg, #020817, #12345f, #d8a241);
    transition: opacity 500ms ease;
}

.service-slide.active {
    opacity: 1;
}

.service-slide::after {
    content: '';
    background: linear-gradient(90deg, rgba(2, 8, 23, 0.72), rgba(2, 8, 23, 0.18) 46%, rgba(2, 8, 23, 0.7));
}

.service-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.service-verse-card,
.service-invite-card {
    position: absolute;
    z-index: 2;
    bottom: clamp(22px, 3vw, 36px);
    border: 1px solid rgba(216, 162, 65, 0.55);
    border-radius: 18px;
    background: rgba(6, 21, 47, 0.84);
    color: #fff8ea;
    box-shadow: 0 18px 34px rgba(2, 8, 23, 0.32);
    backdrop-filter: blur(12px);
}

.service-verse-card {
    left: clamp(20px, 3vw, 36px);
    width: min(310px, 38%);
    padding: 22px 24px 18px;
}

.service-verse-card span {
    color: #f6c65b;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 2rem;
    line-height: 0.7;
}

.service-verse-card p {
    margin: 4px 0 12px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(0.9rem, 1vw, 1.08rem);
    line-height: 1.45;
}

.service-verse-card strong {
    color: #f6c65b;
    font-size: 0.8rem;
}

.service-invite-card {
    right: clamp(20px, 3vw, 36px);
    width: min(285px, 34%);
    padding: 20px;
}

.service-invite-card span {
    display: block;
    color: #f6c65b;
    font-size: 2rem;
}

.service-invite-card strong {
    display: block;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.service-invite-card p,
.service-invite-card small {
    display: block;
    margin: 0;
    line-height: 1.4;
}

.service-invite-card p {
    font-size: 0.84rem;
}

.service-invite-card small {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 248, 234, 0.18);
    color: #f8e7c0;
    font-size: 0.68rem;
}

.service-arrow {
    position: absolute;
    top: 50%;
    z-index: 3;
    width: 32px;
    height: 32px;
    border: 1px solid rgba(246, 198, 91, 0.42);
    border-radius: 999px;
    color: #fff8ea;
    background: rgba(2, 8, 23, 0.42);
    cursor: pointer;
    opacity: 0;
    transform: translateY(-50%);
    transition: opacity 260ms ease, background 260ms ease;
}

.service-visual:hover .service-arrow {
    opacity: 1;
}

.service-arrow-left {
    left: 12px;
}

.service-arrow-right {
    right: 12px;
}

.service-slide-dots {
    position: absolute;
    left: 50%;
    bottom: 14px;
    z-index: 3;
    display: flex;
    gap: 8px;
    transform: translateX(-50%);
}

.service-slide-dots span {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: rgba(255, 248, 234, 0.55);
}

.service-slide-dots span.active {
    width: 22px;
    background: #f59e0b;
}

.services-heading {
    margin: 0 0 14px;
    text-align: center;
}

.services-heading h2 {
    margin: 0;
    color: #06152f;
    font-size: clamp(1.55rem, 2vw, 2.15rem);
    text-transform: uppercase;
}

.services-heading p {
    margin: 2px 0 0;
    color: #334155;
    font-size: 0.82rem;
}

.service-cards-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: clamp(12px, 1.4vw, 18px);
}

.service-card {
    display: grid;
    grid-template-columns: 54px 1fr;
    gap: 10px;
    min-height: 170px;
    padding: 18px 16px 15px;
    border: 1px solid rgba(216, 162, 65, 0.26);
    border-radius: 18px;
    color: #08162e;
    background: rgba(255, 248, 234, 0.7);
    box-shadow: 0 12px 26px rgba(8, 22, 46, 0.06);
    text-align: left;
    cursor: pointer;
    transition: transform 260ms ease, border-color 260ms ease, box-shadow 260ms ease;
}

.service-card:hover,
.practical-card:hover,
.primary-cta:hover,
.secondary-cta:hover,
.login-button:hover {
    transform: translateY(-3px);
}

.service-card:hover {
    border-color: rgba(216, 162, 65, 0.58);
    box-shadow: 0 18px 32px rgba(8, 22, 46, 0.1);
}

.service-card-icon {
    width: 48px;
    height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    color: #fff8ea;
    background: #06152f;
    font-size: 1.45rem;
}

.service-card-gold .service-card-icon,
.service-card-gold em {
    background: linear-gradient(135deg, #f6c65b, #d97706);
}

.service-card-content {
    display: flex;
    min-width: 0;
    flex-direction: column;
}

.service-card-content strong {
    color: #06152f;
    font-size: 0.8rem;
    font-weight: 950;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.service-card-content small {
    color: #334155;
    font-size: 0.72rem;
    font-weight: 900;
    text-transform: uppercase;
}

.service-card-content b {
    margin-top: 2px;
    color: #06152f;
    font-size: 1rem;
}

.service-card-content p {
    margin: 10px 0 9px;
    color: #334155;
    font-size: 0.72rem;
    line-height: 1.45;
}

.service-card-content em {
    align-self: flex-start;
    margin-top: auto;
    border-radius: 8px;
    padding: 5px 9px;
    color: #fff8ea;
    background: #06152f;
    font-size: 0.68rem;
    font-style: normal;
    font-weight: 850;
}

.practical-strip,
.service-footer {
    display: grid;
    border-radius: 18px;
    background: #06152f;
    color: #fff8ea;
    box-shadow: 0 18px 35px rgba(8, 22, 46, 0.16);
}

.practical-strip {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    align-items: center;
    padding: 18px 22px;
}

.practical-card {
    display: grid;
    grid-template-columns: 42px 1fr;
    gap: 12px;
    align-items: center;
    min-height: 68px;
    padding: 0 16px;
    border-right: 1px solid rgba(255, 248, 234, 0.14);
    transition: transform 260ms ease;
}

.practical-card:last-child {
    border-right: 0;
}

.practical-card span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #f6c65b;
    font-size: 1.8rem;
}

.practical-card strong {
    display: block;
    font-size: 0.76rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.practical-card p {
    margin: 3px 0 0;
    color: #f8e7c0;
    font-size: 0.7rem;
    line-height: 1.35;
}

.service-footer {
    grid-template-columns: minmax(310px, 1.1fr) minmax(240px, 0.9fr) minmax(270px, 0.8fr);
    align-items: center;
    gap: 18px;
    padding: 18px 28px;
}

.footer-brand,
.marvvium-signature {
    display: inline-flex;
    align-items: center;
    gap: 14px;
}

.footer-brand img {
    width: 58px;
    height: 42px;
    object-fit: contain;
}

.footer-brand span,
.marvvium-signature span {
    display: grid;
}

.footer-brand strong {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.2rem;
}

.footer-brand small,
.marvvium-signature small {
    color: #f8e7c0;
    font-size: 0.72rem;
}

.footer-socials {
    display: flex;
    justify-content: center;
    gap: 18px;
}

.footer-socials a {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(246, 198, 91, 0.44);
    border-radius: 999px;
    color: #f6c65b;
    text-decoration: none;
    transition: transform 260ms ease, background 260ms ease;
}

.marvvium-signature {
    justify-self: end;
    text-align: right;
}

.marvvium-signature img {
    width: 58px;
    height: 36px;
    object-fit: contain;
}

.service-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 20;
    display: grid;
    place-items: center;
    background: rgba(2, 8, 23, 0.42);
    backdrop-filter: blur(8px);
}

.service-modal {
    position: relative;
    width: min(420px, calc(100vw - 32px));
    border: 1px solid rgba(216, 162, 65, 0.44);
    border-radius: 24px;
    padding: 28px;
    background: #fff8ea;
    box-shadow: 0 28px 60px rgba(2, 8, 23, 0.32);
}

.service-modal button {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 999px;
    color: #fff8ea;
    background: #06152f;
    cursor: pointer;
}

.service-modal h2 {
    margin: 8px 0;
    color: #06152f;
    font-size: 2rem;
}

.service-modal strong,
.service-modal em {
    color: #d97706;
}

.service-modal p:not(.section-kicker) {
    color: #334155;
    line-height: 1.55;
}

@media (max-width: 1180px) {
    .public-home-screen {
        height: auto;
        min-height: 100vh;
        overflow-y: auto;
    }

    .public-stage,
    .service-hero,
    .service-cards-grid,
    .practical-strip,
    .service-footer {
        grid-template-columns: 1fr;
    }

    .public-stage {
        grid-template-rows: auto;
    }

    .service-visual {
        min-height: 340px;
    }
}
</style>
