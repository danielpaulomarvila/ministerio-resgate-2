<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePublicPageTransition } from '../../Composables/usePublicPageTransition';


defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
});

const activeHistorySlide = ref(0);
const publicPageElement = ref(null);
const { navigatePublicPage } = usePublicPageTransition(publicPageElement);

// Conteúdo provisório da página Sobre Nós.
// Futuramente será carregado da Administração Geral → Site Público.
const aboutPage = {
    kicker: 'Sobre Nós',
    title: 'Nossa história é testemunho da graça de Deus.',
    text: 'De um pequeno grupo de pessoas com um grande sonho, nasceu uma família que hoje resgata vidas e transforma corações através do amor de Jesus.',
    storyLabel: 'Nossa História',
    storyText: 'Cada passo foi guiado por Deus. Cada conquista é para Ele.',
    gratitude: 'Ebenezer! Até aqui nos ajudou o Senhor!',
    verse: {
        text: 'Grandes coisas fez o Senhor por nós, e por isso estamos alegres.',
        reference: 'Salmos 126:3',
    },
};

// Imagens provisórias da linha do tempo.
// Futuramente serão substituídas por fotos reais via Administração Geral.
const historySlides = [
    {
        year: '2005',
        title: 'O Começo',
        text: 'Tudo começou em um pequeno grupo de oração em lares, com fé, amor e desejo de ver vidas transformadas.',
        image: '/images/hero/resgate-grupo-orando-07.jpg',
        tone: 'sepia(0.48) grayscale(0.5)',
    },
    {
        year: '2007',
        title: 'Os Primeiros Passos',
        text: 'As reuniões cresceram, mais famílias chegaram e o sonho de igreja começou a nascer.',
        image: '/images/hero/resgate-biblia-04.jpg',
        tone: 'sepia(0.34) grayscale(0.28)',
    },
    {
        year: '2010',
        title: 'Construindo um Lugar',
        text: 'Com muita oração, trabalho e fé, iniciamos a construção do nosso primeiro templo.',
        image: '/images/hero/resgate-cruz-08.jpg',
        tone: 'sepia(0.25) grayscale(0.2)',
    },
    {
        year: '2015',
        title: 'Crescendo em Propósito',
        text: 'Deus acrescentou pessoas, ministérios e sonhos. Vidas foram resgatadas e histórias transformadas.',
        image: '/images/hero/resgate-culto-05.jpg',
        tone: 'none',
    },
    {
        year: 'Hoje',
        title: 'Até aqui nos ajudou o Senhor',
        text: 'Continuamos firmes na missão de resgatar vidas, restaurar famílias e anunciar o amor de Jesus.',
        image: '/images/hero/resgate-louvor-10.jpg',
        tone: 'none',
    },
];

const journeyStats = [
    {
        value: '+18',
        label: 'Anos de História',
        text: 'Caminhando com fé e propósito.',
        icon: 'heart',
    },
    {
        value: '+2.500',
        label: 'Vidas Alcançadas',
        text: 'Pessoas que tiveram suas vidas transformadas por Jesus.',
        icon: 'people',
    },
    {
        value: '+25',
        label: 'Ministérios',
        text: 'Servindo com amor em diferentes frentes e propósitos.',
        icon: 'church',
    },
    {
        value: '1',
        label: 'Missão',
        text: 'Resgatar vidas, restaurar famílias e anunciar o Reino de Deus.',
        icon: 'globe',
    },
];


function goToPreviousHistorySlide() {
    activeHistorySlide.value = (activeHistorySlide.value - 1 + historySlides.length) % historySlides.length;
}

function goToNextHistorySlide() {
    activeHistorySlide.value = (activeHistorySlide.value + 1) % historySlides.length;
}

</script>

<template>
    <Head title="Sobre Nós" />

    <div ref="publicPageElement" class="public-home-screen public-route-page">
        <header class="public-header">
            <Link class="brand-mark" href="/inicio" aria-label="Família Resgate" @click="navigatePublicPage($event, '/inicio')">
                <img class="brand-logo" src="/images/brand/logo-resgate-gold.png" alt="Logo Ministério Resgate" />
                <span><small>Família</small><strong>Resgate</strong></span>
            </Link>
            <nav class="public-nav" aria-label="Navegação pública">
                <Link class="nav-item" href="/inicio" @click="navigatePublicPage($event, '/inicio')">Início</Link>
                <Link class="nav-item active" href="/sobre_nos" @click="navigatePublicPage($event, '/sobre_nos')">Sobre Nós</Link>
                <Link class="nav-item" href="/cultos" @click="navigatePublicPage($event, '/cultos')">Cultos</Link>
                <Link class="nav-item" href="/eventos" @click="navigatePublicPage($event, '/eventos')">Eventos</Link>
                <Link class="nav-item" href="/contato" @click="navigatePublicPage($event, '/contato')">Contato</Link>
            </nav>
            <Link v-if="canLogin" class="login-button" :href="$page.props.auth.user ? route('dashboard') : route('login')">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                Entrar
            </Link>
        </header>

<main class="public-stage about-panel">
            <section class="about-layout" aria-label="Sobre a Família Resgate">
                <article class="about-intro">
                    <p class="section-kicker">{{ aboutPage.kicker }}</p>
                    <h1>{{ aboutPage.title }}</h1>
                    <span class="about-title-line"></span>
                    <p>{{ aboutPage.text }}</p>
                </article>

                <section class="history-editorial" aria-label="Linha do tempo da Família Resgate">
                    <header class="history-heading">
                        <svg viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M8 10h14c4 0 6 2 6 6v22c0-4-2-6-6-6H8Z" />
                            <path d="M40 10H28c-4 0-6 2-6 6v22c0-4 2-6 6-6h12Z" />
                        </svg>
                        <div>
                            <h2>{{ aboutPage.storyLabel }}</h2>
                            <p>{{ aboutPage.storyText }}</p>
                        </div>
                    </header>

                    <div class="history-carousel">
                        <button type="button" class="history-arrow history-arrow-left" aria-label="Marco anterior" @click="goToPreviousHistorySlide">
                            ‹
                        </button>
                        <button type="button" class="history-arrow history-arrow-right" aria-label="Próximo marco" @click="goToNextHistorySlide">
                            ›
                        </button>

                        <div class="history-photo-strip">
                            <button
                                v-for="(slide, index) in historySlides"
                                :key="`${slide.year}-${slide.title}`"
                                type="button"
                                class="history-photo-card"
                                :class="{ active: activeHistorySlide === index }"
                                @click="activeHistorySlide = index"
                            >
                                <img :src="slide.image" :alt="slide.title" :style="{ filter: slide.tone }" />
                            </button>
                        </div>

                        <div class="history-line" aria-hidden="true">
                            <span
                                v-for="(slide, index) in historySlides"
                                :key="`${slide.year}-point`"
                                :class="{ active: activeHistorySlide === index }"
                            ></span>
                        </div>

                        <div class="history-items">
                            <button
                                v-for="(slide, index) in historySlides"
                                :key="`${slide.title}-content`"
                                type="button"
                                class="history-item"
                                :class="{ active: activeHistorySlide === index }"
                                @click="activeHistorySlide = index"
                            >
                                <strong>{{ slide.title }}</strong>
                                <small>{{ slide.year }}</small>
                                <p>{{ slide.text }}</p>
                            </button>
                        </div>
                    </div>
                </section>

                <article class="about-verse-card">
                    <span>“</span>
                    <p>{{ aboutPage.verse.text }}</p>
                    <strong>{{ aboutPage.verse.reference }}</strong>
                </article>

                <section class="journey-stats" aria-label="Marcos da caminhada">
                    <article v-for="stat in journeyStats" :key="stat.label" class="journey-stat-card">
                        <span class="journey-stat-icon">
                            <svg v-if="stat.icon === 'heart'" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M24 39s-14-8.5-14-20a8 8 0 0 1 14-5 8 8 0 0 1 14 5c0 11.5-14 20-14 20Z" />
                            </svg>
                            <svg v-else-if="stat.icon === 'people'" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M18 22a7 7 0 1 0 0-14 7 7 0 0 0 0 14ZM31 22a6 6 0 1 0 0-12" />
                                <path d="M6 40c1.5-9 7-14 12-14s10.5 5 12 14M29 28c6 1 11 5 13 12" />
                            </svg>
                            <svg v-else-if="stat.icon === 'church'" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M9 41h30V22L24 12 9 22Z" />
                                <path d="M24 7v15M18 13h12M19 41V30h10v11" />
                            </svg>
                            <svg v-else viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M24 42a18 18 0 1 0 0-36 18 18 0 0 0 0 36Z" />
                                <path d="M7 24h34M24 6c5 5 8 11 8 18s-3 13-8 18M24 6c-5 5-8 11-8 18s3 13 8 18" />
                            </svg>
                        </span>
                        <h2>{{ stat.value }}</h2>
                        <strong>{{ stat.label }}</strong>
                        <p>{{ stat.text }}</p>
                    </article>
                </section>

                <aside class="gratitude-strip">
                    <span></span>
                    <strong>{{ aboutPage.gratitude }}</strong>
                </aside>

                <footer class="about-footer">
                    <div class="about-footer-brand">
                        <img src="/images/brand/logo-resgate-gold.png" alt="Logo Família Resgate" />
                        <span>
                            <strong>Família Resgate</strong>
                            <small>Uma família que existe para resgatar vidas para Jesus.</small>
                        </span>
                    </div>
                    <div class="about-socials" aria-label="Redes sociais">
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">◎</a>
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">f</a>
                        <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="YouTube">▶</a>
                        <a href="https://wa.me/550012345678" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">◌</a>
                    </div>
                    <div class="about-marvvium-signature" aria-label="Plataforma desenvolvida por Marvvium">
                        <span>Plataforma desenvolvida por</span>
                        <img src="/images/brand/logo-marvvium.png" alt="Marvvium" />
                    </div>
                </footer>
            </section>
        </main>
    </div>
</template>

<style scoped>
.public-home-screen {
    position: relative;
    display: grid;
    grid-template-rows: 86px minmax(0, 1fr);
    height: 100vh;
    min-height: 720px;
    overflow: hidden;
    padding: 0 clamp(26px, 3.6vw, 56px) 12px;
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
        linear-gradient(90deg, rgba(217, 164, 65, 0.06) 1px, transparent 1px),
        linear-gradient(rgba(217, 164, 65, 0.05) 1px, transparent 1px);
    background-size: 84px 84px;
    mask-image: radial-gradient(circle at center, black, transparent 82%);
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
    flex: 0 0 auto;
    object-fit: contain;
    transform: scale(1.95);
    transform-origin: center;
    filter: drop-shadow(0 10px 18px rgba(217, 164, 65, 0.2));
}

.brand-mark span {
    display: grid;
    line-height: 1;
}

.brand-mark small {
    font-family: Georgia, 'Times New Roman', serif;
    color: #b87918;
    font-size: 0.76rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
}

.brand-mark strong {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(1.28rem, 1.75vw, 1.68rem);
    font-weight: 700;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

.public-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: clamp(24px, 3.6vw, 56px);
}

.nav-item {
    position: relative;
    border: 0;
    color: #08162e;
    background: transparent;
    font-size: 1rem;
    font-weight: 800;
    cursor: pointer;
    transition: color 280ms ease, transform 280ms ease;
}

.nav-item::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -16px;
    width: 66px;
    height: 2px;
    border-radius: 999px;
    background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    opacity: 0;
    transform: translateX(-50%) scaleX(0.65);
    transition: opacity 280ms ease, transform 280ms ease;
}

.nav-item:hover,
.nav-item.active {
    color: #d97706;
    transform: translateY(-2px);
}

.nav-item.active::after,
.nav-item:hover::after {
    opacity: 1;
    transform: translateX(-50%) scaleX(1);
}

.login-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    justify-self: end;
    min-width: 132px;
    min-height: 48px;
    border: 1px solid rgba(217, 164, 65, 0.5);
    border-radius: 16px;
    color: #fff8ea;
    background: linear-gradient(135deg, #f6c65b, #f59e0b 62%, #d97706);
    font-weight: 900;
    text-decoration: none;
    box-shadow: 0 16px 28px rgba(245, 158, 11, 0.22);
    transition: transform 280ms ease, box-shadow 280ms ease;
}

.login-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 22px 34px rgba(245, 158, 11, 0.3);
}

.login-button svg {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
}




.public-stage {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-rows: minmax(0, 1fr);
    height: calc(100vh - 98px);
    min-height: 0;
}

.about-panel {
    overflow: visible;
}

.hero-zone {
    position: relative;
    display: grid;
    grid-template-columns: minmax(350px, 38%) minmax(0, 62%);
    min-height: 0;
    overflow: hidden;
    border-radius: 32px 54px 0 0;
    background:
        linear-gradient(90deg, rgba(255, 248, 234, 1) 0%, rgba(255, 248, 234, 0.98) 35%, rgba(255, 248, 234, 0.22) 45%, transparent 57%),
        linear-gradient(180deg, rgba(255, 248, 234, 0.95), rgba(244, 232, 208, 0.72));
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
}

.hero-copy {
    position: relative;
    z-index: 6;
    display: flex;
    width: auto;
    height: 100%;
    flex-direction: column;
    justify-content: center;
    padding: clamp(28px, 3.2vw, 48px) clamp(20px, 2.4vw, 34px) clamp(32px, 3vw, 46px) clamp(34px, 4.2vw, 62px);
}

.hero-kicker {
    margin: 0;
    color: #08162e;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(1.02rem, 1.48vw, 1.38rem);
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.hero-copy h1 {
    display: grid;
    margin: 4px 0 8px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(2.45rem, 4vw, 3.8rem);
    font-weight: 800;
    line-height: 0.92;
    text-transform: uppercase;
}

.hero-copy h1 span {
    color: #06152f;
}

.hero-copy h1 strong {
    color: #e59b0b;
    font-weight: 800;
    text-shadow: 0 8px 18px rgba(217, 164, 65, 0.18);
}

.hero-mission {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: #08162e;
    font-size: clamp(0.72rem, 0.9vw, 0.94rem);
    font-weight: 900;
    line-height: 1.4;
    text-transform: uppercase;
}

.hero-mission svg {
    width: 23px;
    height: 23px;
    flex: 0 0 auto;
    fill: none;
    stroke: #e59b0b;
    stroke-width: 1.8;
}

.hero-text {
    max-width: 430px;
    margin: 12px 0 0;
    color: #1d2939;
    font-size: clamp(0.8rem, 0.9vw, 0.96rem);
    line-height: 1.48;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: clamp(12px, 1.6vw, 18px);
    padding-bottom: 4px;
}

.primary-cta,
.secondary-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-width: 168px;
    min-height: 44px;
    border-radius: 16px;
    font-weight: 900;
    cursor: pointer;
    transition: transform 280ms ease, box-shadow 280ms ease, border-color 280ms ease;
}

.primary-cta {
    border: 1px solid rgba(217, 164, 65, 0.7);
    color: #fff8ea;
    background: linear-gradient(135deg, #f6c65b, #f59e0b 64%, #d97706);
    box-shadow: 0 18px 28px rgba(245, 158, 11, 0.24);
}

.secondary-cta {
    border: 1px solid rgba(6, 21, 47, 0.52);
    color: #06152f;
    background: rgba(255, 248, 234, 0.7);
}

.primary-cta:hover,
.secondary-cta:hover {
    transform: translateY(-3px);
    border-color: #f59e0b;
    box-shadow: 0 20px 30px rgba(217, 164, 65, 0.2);
}

.primary-cta svg,
.secondary-cta svg {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
}

.hero-visual {
    position: relative;
    z-index: 2;
    width: 100%;
    min-width: 0;
    height: 100%;
    overflow: hidden;
    border-radius: 0 54px 0 0;
    background:
        radial-gradient(circle at 76% 16%, rgba(246, 198, 91, 0.24), transparent 24%),
        linear-gradient(135deg, #020817, #06152f 54%, #0a2342);
}

.hero-visual::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 2;
    background:
        radial-gradient(circle at 78% 18%, rgba(246, 198, 91, 0.2), transparent 30%),
        radial-gradient(circle at 18% 82%, rgba(246, 198, 91, 0.1), transparent 24%);
    opacity: 0.58;
    pointer-events: none;
}

.hero-visual::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 2;
    background-image:
        radial-gradient(circle at 82% 18%, rgba(246, 198, 91, 0.1), transparent 22%),
        repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.025) 0 1px, transparent 1px 5px);
    opacity: 0.24;
    pointer-events: none;
}

.hero-carousel {
    position: absolute;
    inset: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
}

.hero-slide {
    position: absolute;
    inset: 0;
    z-index: 1;
    overflow: hidden;
    opacity: 0;
    background: var(--hero-fallback);
    transform: scale(1.01);
    transition: opacity 1100ms ease, transform 4200ms ease;
}

.hero-slide.active {
    z-index: 4;
    opacity: 1;
    transform: scale(1);
}

.hero-slide::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 4;
    background:
        linear-gradient(90deg, rgba(2, 8, 23, 0.52) 0%, rgba(6, 21, 47, 0.2) 36%, rgba(2, 8, 23, 0.42) 100%),
        linear-gradient(180deg, rgba(2, 8, 23, 0.08) 0%, rgba(2, 8, 23, 0.02) 42%, rgba(2, 8, 23, 0.62) 100%);
    backdrop-filter: blur(1px) saturate(1.04);
    -webkit-backdrop-filter: blur(1px) saturate(1.04);
    pointer-events: none;
}

.hero-slide::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 5;
    background-image:
        radial-gradient(circle at 82% 18%, rgba(246, 198, 91, 0.1), transparent 22%),
        repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.018) 0 1px, transparent 1px 5px);
    opacity: 0.28;
    pointer-events: none;
}

.hero-slide img,
.hero-photo-placeholder {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: var(--hero-position, center);
    filter: saturate(1.08) contrast(1.04) brightness(0.94);
    transform: scale(1.035);
    transition: transform 4200ms ease;
}

.hero-slide.active img,
.hero-slide.active .hero-photo-placeholder {
    transform: scale(1.085);
}

.hero-photo-placeholder {
    background:
        radial-gradient(circle at 72% 18%, rgba(255, 248, 234, 0.34), transparent 22%),
        radial-gradient(circle at 58% 42%, rgba(246, 198, 91, 0.2), transparent 28%),
        radial-gradient(circle at 24% 82%, rgba(15, 59, 104, 0.86), transparent 32%),
        linear-gradient(115deg, rgba(2, 8, 23, 0.16), rgba(255, 255, 255, 0.05) 24%, rgba(2, 8, 23, 0.18) 25%, transparent 48%),
        var(--hero-fallback);
}

.hero-slide-copy {
    position: absolute;
    z-index: 20;
    left: clamp(22px, 2.6vw, 38px);
    bottom: clamp(24px, 3vw, 40px);
    display: grid;
    width: min(46%, 390px);
    max-width: calc(100% - 390px);
    gap: 6px;
    padding: clamp(13px, 1.25vw, 18px) clamp(15px, 1.55vw, 22px);
    border: 1px solid rgba(246, 198, 91, 0.48);
    border-left: 3px solid rgba(246, 198, 91, 0.88);
    border-radius: 0 18px 18px 0;
    color: #fff8ea;
    background:
        radial-gradient(circle at 96% 10%, rgba(246, 198, 91, 0.12), transparent 28%),
        linear-gradient(90deg, rgba(2, 8, 23, 0.98), rgba(6, 21, 47, 0.94));
    box-shadow: 0 26px 58px rgba(2, 8, 23, 0.62);
    backdrop-filter: blur(6px) saturate(1.02);
    -webkit-backdrop-filter: blur(6px) saturate(1.02);
}

.hero-slide-copy strong {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(1.12rem, 1.58vw, 1.72rem);
    line-height: 1.08;
    color: #fff8ea;
    text-shadow: 0 8px 24px rgba(0, 0, 0, 0.38);
}

.hero-slide-copy span {
    max-width: 320px;
    color: rgba(255, 248, 234, 0.96);
    font-size: clamp(0.74rem, 0.84vw, 0.9rem);
    line-height: 1.35;
}

.hero-slide-dots {
    position: absolute;
    z-index: 30;
    left: clamp(22px, 2.6vw, 38px);
    bottom: clamp(10px, 1.35vw, 18px);
    display: flex;
    gap: 7px;
}

.hero-slide-dots span {
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: rgba(255, 248, 234, 0.48);
    transition: width 320ms ease, background 320ms ease;
}

.hero-slide-dots span.active {
    width: 25px;
    background: #f6c65b;
}

.verse-card {
    position: absolute;
    z-index: 31;
    right: clamp(18px, 2.3vw, 34px);
    bottom: clamp(24px, 3vw, 40px);
    display: flex;
    width: clamp(218px, 19vw, 290px);
    min-height: auto;
    flex-direction: column;
    justify-content: center;
    padding: clamp(14px, 1.2vw, 18px);
    border: 1px solid rgba(246, 198, 91, 0.62);
    border-radius: 20px;
    color: #f8f2e6;
    background:
        radial-gradient(circle at 84% 76%, rgba(246, 198, 91, 0.16), transparent 30%),
        linear-gradient(135deg, rgba(10, 35, 66, 0.98), rgba(6, 21, 47, 0.98) 74%);
    box-shadow: 0 30px 64px rgba(2, 8, 23, 0.62);
    backdrop-filter: blur(6px) saturate(1.02);
    -webkit-backdrop-filter: blur(6px) saturate(1.02);
}

.verse-card .quote-mark {
    color: #f6c65b;
    font-size: clamp(1.75rem, 2.2vw, 2.35rem);
    font-family: Georgia, 'Times New Roman', serif;
    line-height: 0.5;
}

.verse-card h2 {
    margin: 4px 0 9px;
    color: #fff8ea;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(0.88rem, 0.98vw, 1.04rem);
    line-height: 1.18;
}

.verse-card p {
    margin: 0;
    color: rgba(248, 242, 230, 0.96);
    font-size: clamp(0.7rem, 0.78vw, 0.82rem);
    line-height: 1.36;
}

.verse-card strong {
    margin-top: 11px;
    color: #f6c65b;
    font-size: clamp(0.76rem, 0.84vw, 0.88rem);
}

.highlight-row {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    min-height: 0;
    overflow: hidden;
    border: 1px solid rgba(217, 164, 65, 0.35);
    border-radius: 16px;
    background: rgba(255, 248, 234, 0.72);
    box-shadow: 0 16px 34px rgba(10, 35, 66, 0.08);
}

.highlight-card {
    display: grid;
    grid-template-columns: auto 1fr;
    align-items: center;
    gap: clamp(9px, 1vw, 14px);
    min-width: 0;
    padding: clamp(9px, 1.1vw, 16px);
    border-right: 1px solid rgba(10, 35, 66, 0.12);
    transition: transform 280ms ease, background 280ms ease;
}

.highlight-card:last-child {
    border-right: 0;
}

.highlight-card:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.34);
}

.highlight-icon {
    display: grid;
    width: clamp(48px, 4.4vw, 66px);
    height: clamp(48px, 4.4vw, 66px);
    place-items: center;
    border-radius: 50%;
    background: #06152f;
    box-shadow: 0 14px 24px rgba(6, 21, 47, 0.16);
}

.highlight-icon svg {
    width: 58%;
    height: 58%;
    fill: none;
    stroke: #f6c65b;
    stroke-width: 1.7;
}

.highlight-card h2 {
    margin: 0 0 4px;
    color: #08162e;
    font-size: clamp(0.72rem, 0.84vw, 0.9rem);
    font-weight: 900;
    text-transform: uppercase;
}

.highlight-card p {
    margin: 0;
    color: #1d2939;
    font-size: clamp(0.7rem, 0.78vw, 0.84rem);
    line-height: 1.35;
}

.home-footer {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(420px, 0.52fr);
    align-items: center;
    gap: 14px;
    min-height: 0;
    overflow: hidden;
    border: 1px solid rgba(217, 164, 65, 0.28);
    border-radius: 16px;
    background: rgba(255, 248, 234, 0.72);
    box-shadow: 0 16px 34px rgba(10, 35, 66, 0.07);
}

.footer-verse {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    align-items: center;
    gap: clamp(12px, 1.5vw, 24px);
    padding-left: clamp(26px, 5vw, 74px);
    padding-right: 8px;
}

.footer-verse span {
    color: #e59b0b;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(2.35rem, 3.2vw, 3.55rem);
    line-height: 0.5;
}

.footer-verse p {
    margin: 0;
    color: #08162e;
    font-size: clamp(0.84rem, 1vw, 1.05rem);
    line-height: 1.28;
}

.footer-verse strong {
    border-left: 1px solid rgba(217, 164, 65, 0.34);
    padding-left: clamp(14px, 2vw, 26px);
    color: #d97706;
    font-size: clamp(0.76rem, 0.9vw, 0.92rem);
    text-transform: uppercase;
}

.footer-actions {
    display: grid;
    grid-template-columns: minmax(218px, 1fr) minmax(156px, 0.82fr);
    align-items: center;
    gap: 10px;
    min-width: 0;
    padding-right: 12px;
}

.tracking-card {
    display: grid;
    grid-template-columns: auto 1fr;
    align-items: center;
    gap: 10px;
    min-height: 52px;
    margin-right: 0;
    padding: 0 12px 0 0;
    border: 1px solid rgba(217, 164, 65, 0.34);
    border-radius: 12px;
    color: #f8f2e6;
    background: #06152f;
    text-align: left;
    cursor: pointer;
    transition: transform 280ms ease, box-shadow 280ms ease, border-color 280ms ease;
}

.tracking-card:hover {
    transform: translateY(-3px);
    border-color: #f6c65b;
    box-shadow: 0 20px 34px rgba(6, 21, 47, 0.18);
}

.tracking-card svg {
    width: 29px;
    height: 29px;
    margin-left: 14px;
    fill: none;
    stroke: #f6c65b;
    stroke-width: 1.7;
}

.tracking-card strong,
.tracking-card small {
    display: block;
}

.tracking-card strong {
    font-size: 0.88rem;
}

.tracking-card small {
    margin-top: 3px;
    color: rgba(248, 242, 230, 0.76);
    font-size: 0.66rem;
}

.marvvium-signature {
    display: grid;
    grid-template-columns: 68px 1fr;
    align-items: center;
    gap: 8px;
    min-width: 0;
    padding: 5px 8px;
    border-left: 1px solid rgba(217, 164, 65, 0.24);
    color: #08162e;
}

.marvvium-signature img {
    width: 68px;
    height: 42px;
    object-fit: contain;
    opacity: 0.9;
    transform: scale(1.65);
    transform-origin: center;
}

.marvvium-signature strong,
.marvvium-signature small {
    display: block;
}

.marvvium-signature strong {
    color: #08162e;
    font-size: 0.62rem;
    font-weight: 900;
    line-height: 1.15;
}

.marvvium-signature small {
    margin-top: 2px;
    color: #b87918;
    font-size: 0.6rem;
    font-weight: 800;
}

.about-panel {
    grid-template-rows: 1fr;
}

.about-layout {
    display: grid;
    grid-template-columns: minmax(280px, 30%) minmax(0, 1fr);
    grid-template-rows: minmax(280px, 1.25fr) minmax(138px, 0.72fr) minmax(48px, 0.22fr) minmax(72px, 0.34fr);
    gap: clamp(12px, 1.4vw, 18px) clamp(28px, 4vw, 58px);
    height: 100%;
    min-height: 0;
}

.about-intro {
    display: flex;
    min-height: 0;
    flex-direction: column;
    justify-content: center;
    padding-left: clamp(2px, 1vw, 12px);
}

.section-kicker {
    margin: 0 0 10px;
    color: #d97706;
    font-size: clamp(0.74rem, 0.86vw, 0.9rem);
    font-weight: 950;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.about-intro h1 {
    max-width: 430px;
    margin: 0;
    color: #06152f;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(2.05rem, 3.2vw, 3.35rem);
    line-height: 0.98;
}

.about-title-line {
    width: 52px;
    height: 2px;
    margin: 18px 0 20px;
    border-radius: 999px;
    background: #d97706;
}

.about-intro p:not(.section-kicker) {
    max-width: 430px;
    margin: 0;
    color: #1d2939;
    font-size: clamp(0.82rem, 0.92vw, 0.98rem);
    line-height: 1.55;
}

.history-editorial {
    display: grid;
    grid-template-rows: auto minmax(0, 1fr);
    min-height: 0;
}

.history-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    color: #d97706;
}

.history-heading svg {
    width: 34px;
    height: 34px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.6;
}

.history-heading h2 {
    margin: 0;
    color: #d97706;
    font-size: clamp(1rem, 1.15vw, 1.25rem);
    font-weight: 950;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.history-heading p {
    margin: 1px 0 0;
    color: #1d2939;
    font-size: clamp(0.68rem, 0.78vw, 0.82rem);
}

.history-carousel {
    position: relative;
    display: grid;
    grid-template-rows: minmax(92px, 0.9fr) 18px minmax(108px, 1fr);
    min-height: 0;
    overflow: hidden;
    padding: 14px 18px 16px;
    border: 1px solid rgba(246, 198, 91, 0.34);
    border-radius: 12px;
    background:
        radial-gradient(circle at 78% 10%, rgba(246, 198, 91, 0.14), transparent 28%),
        linear-gradient(135deg, #06152f, #071b3a 58%, #020817);
    box-shadow: 0 24px 48px rgba(6, 21, 47, 0.22);
}

.history-photo-strip {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 8px;
    min-height: 0;
}

.history-photo-card {
    min-width: 0;
    overflow: hidden;
    border: 2px solid transparent;
    border-radius: 9px;
    background: #020817;
    cursor: pointer;
    transition: transform 280ms ease, border-color 280ms ease, box-shadow 280ms ease;
}

.history-photo-card:hover,
.history-photo-card.active {
    border-color: #f6c65b;
    box-shadow: 0 14px 26px rgba(246, 198, 91, 0.2);
    transform: translateY(-3px);
}

.history-photo-card img {
    display: block;
    width: 100%;
    height: 100%;
    min-height: 96px;
    object-fit: cover;
    opacity: 0.92;
}

.history-line {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    align-items: center;
    margin-inline: 40px;
}

.history-line span {
    position: relative;
    height: 2px;
    background: rgba(246, 198, 91, 0.42);
}

.history-line span::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    width: 13px;
    height: 13px;
    border: 2px solid #fff8ea;
    border-radius: 50%;
    background: #f59e0b;
    transform: translate(-50%, -50%);
    transition: transform 280ms ease, box-shadow 280ms ease;
}

.history-line span.active::after {
    box-shadow: 0 0 0 5px rgba(246, 198, 91, 0.22);
    transform: translate(-50%, -50%) scale(1.16);
}

.history-items {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 8px;
    min-height: 0;
}

.history-item {
    display: flex;
    min-width: 0;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 8px 6px;
    border: 0;
    border-left: 1px solid rgba(246, 198, 91, 0.12);
    color: #fff8ea;
    background: transparent;
    text-align: center;
    cursor: pointer;
    opacity: 0.78;
    transition: opacity 280ms ease, transform 280ms ease;
}

.history-item:hover,
.history-item.active {
    opacity: 1;
    transform: translateY(-2px);
}

.history-item strong {
    min-height: 28px;
    color: #fff8ea;
    font-size: clamp(0.58rem, 0.72vw, 0.78rem);
    line-height: 1.08;
    text-transform: uppercase;
}

.history-item small {
    margin: 2px 0 7px;
    color: #f6c65b;
    font-size: clamp(0.58rem, 0.68vw, 0.72rem);
    font-weight: 950;
}

.history-item p {
    margin: 0;
    color: rgba(255, 248, 234, 0.9);
    font-size: clamp(0.56rem, 0.68vw, 0.73rem);
    line-height: 1.34;
}

.history-arrow {
    position: absolute;
    z-index: 4;
    top: 39%;
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border: 1px solid rgba(246, 198, 91, 0.58);
    border-radius: 50%;
    color: #f6c65b;
    background: #06152f;
    font-size: 2.3rem;
    line-height: 1;
    cursor: pointer;
    transform: translateY(-50%);
    transition: transform 280ms ease, box-shadow 280ms ease;
}

.history-arrow:hover {
    box-shadow: 0 14px 24px rgba(6, 21, 47, 0.28);
    transform: translateY(-50%) scale(1.06);
}

.history-arrow-left {
    left: -1px;
}

.history-arrow-right {
    right: -1px;
}

.about-verse-card {
    display: grid;
    align-content: center;
    min-height: 0;
    padding: clamp(18px, 2vw, 30px);
    border: 1px solid rgba(246, 198, 91, 0.34);
    border-radius: 12px;
    color: #fff8ea;
    background:
        radial-gradient(circle at 82% 20%, rgba(246, 198, 91, 0.12), transparent 30%),
        linear-gradient(135deg, #06152f, #020817);
    box-shadow: 0 20px 40px rgba(6, 21, 47, 0.16);
}

.about-verse-card span {
    color: #f6c65b;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(2rem, 3vw, 3.6rem);
    line-height: 0.55;
}

.about-verse-card p {
    margin: 6px 0 14px;
    max-width: 360px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(0.94rem, 1.05vw, 1.16rem);
    line-height: 1.32;
}

.about-verse-card strong {
    color: #f6c65b;
    font-size: 0.82rem;
}

.journey-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: clamp(14px, 1.8vw, 28px);
    min-height: 0;
}

.journey-stat-card {
    display: grid;
    min-width: 0;
    place-items: center;
    padding: clamp(12px, 1.4vw, 18px);
    border: 1px solid rgba(217, 164, 65, 0.2);
    border-radius: 12px;
    background: rgba(255, 248, 234, 0.72);
    text-align: center;
    box-shadow: 0 16px 32px rgba(10, 35, 66, 0.08);
    transition: transform 280ms ease, border-color 280ms ease, box-shadow 280ms ease;
}

.journey-stat-card:hover {
    border-color: rgba(217, 164, 65, 0.58);
    box-shadow: 0 22px 36px rgba(10, 35, 66, 0.12);
    transform: translateY(-4px);
}

.journey-stat-icon {
    display: grid;
    width: 42px;
    height: 42px;
    place-items: center;
    color: #d97706;
}

.journey-stat-icon svg {
    width: 100%;
    height: 100%;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.55;
}

.journey-stat-card h2 {
    margin: 2px 0 0;
    color: #06152f;
    font-size: clamp(1.28rem, 1.9vw, 2.1rem);
    line-height: 1;
}

.journey-stat-card strong {
    margin-top: 4px;
    color: #06152f;
    font-size: clamp(0.62rem, 0.76vw, 0.82rem);
    text-transform: uppercase;
}

.journey-stat-card p {
    margin: 5px 0 0;
    color: #1d2939;
    font-size: clamp(0.58rem, 0.7vw, 0.76rem);
    line-height: 1.32;
}

.gratitude-strip {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 14px;
    min-height: 0;
    padding: 0 clamp(20px, 2vw, 28px);
    border: 1px solid rgba(217, 164, 65, 0.18);
    border-radius: 12px;
    background: rgba(255, 248, 234, 0.66);
    box-shadow: 0 14px 28px rgba(10, 35, 66, 0.06);
}

.gratitude-strip span {
    width: 2px;
    height: 32px;
    border-radius: 999px;
    background: #d97706;
}

.gratitude-strip strong {
    color: #06152f;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(0.9rem, 1vw, 1.08rem);
}

.about-footer {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: minmax(250px, 1fr) auto minmax(270px, 0.72fr);
    align-items: center;
    min-height: 0;
    padding: 8px clamp(18px, 2.8vw, 36px);
    border-radius: 0;
    color: #fff8ea;
    background: linear-gradient(135deg, #06152f, #020817);
    box-shadow: 0 -10px 30px rgba(6, 21, 47, 0.16);
}

.about-footer-brand,
.about-marvvium-signature {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.about-footer-brand img {
    width: 58px;
    height: 42px;
    object-fit: contain;
    transform: scale(1.35);
}

.about-footer-brand span,
.about-marvvium-signature {
    display: grid;
}

.about-footer-brand strong {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1rem;
}

.about-footer-brand small {
    color: rgba(255, 248, 234, 0.82);
    font-size: 0.62rem;
}

.about-socials {
    display: flex;
    gap: 14px;
}

.about-socials a {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border: 1px solid rgba(246, 198, 91, 0.62);
    border-radius: 50%;
    color: #f6c65b;
    font-weight: 950;
    text-decoration: none;
    transition: transform 280ms ease, box-shadow 280ms ease;
}

.about-socials a:hover {
    box-shadow: 0 0 0 4px rgba(246, 198, 91, 0.12);
    transform: translateY(-3px);
}

.about-marvvium-signature {
    justify-self: end;
    grid-template-columns: auto 118px;
    color: rgba(255, 248, 234, 0.82);
    font-size: 0.62rem;
}

.about-marvvium-signature img {
    width: 118px;
    height: 42px;
    object-fit: contain;
}

@media (max-width: 1180px) {
    .public-home-screen {
        height: auto;
        min-height: 100vh;
        overflow: visible;
        padding-bottom: 24px;
    }

    .public-header {
        grid-template-columns: 1fr auto;
        min-height: 86px;
    }

    .public-nav {
        order: 3;
        grid-column: 1 / -1;
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 12px;
    }

    .public-stage {
        position: relative;
        grid-template-rows: auto;
        height: auto;
        gap: 14px;
    }
}

@media (max-width: 760px) {
    .public-home-screen {
        padding-inline: 16px;
    }

    .public-header {
        grid-template-columns: 1fr;
        gap: 12px;
        padding-top: 14px;
    }

    .login-button {
        justify-self: start;
    }

    .hero-zone {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: auto minmax(430px, auto);
        min-height: 0;
        overflow: visible;
        border-radius: 28px;
    }

    .hero-copy {
        width: 100%;
        height: auto;
        padding: 34px 24px 26px;
    }

    .hero-copy h1 {
        font-size: clamp(2.35rem, 13vw, 3.35rem);
    }

    .hero-actions {
        margin-bottom: 4px;
    }

    .hero-visual {
        width: 100%;
        height: 430px;
        min-height: 430px;
        border-radius: 0 0 28px 28px;
    }

    .hero-visual::after {
        background:
            linear-gradient(180deg, rgba(2, 8, 23, 0.12) 0%, rgba(2, 8, 23, 0.16) 34%, rgba(2, 8, 23, 0.58) 100%),
            radial-gradient(circle at 74% 18%, rgba(246, 198, 91, 0.24), transparent 30%);
    }

    .hero-slide-copy {
        left: 18px;
        right: 18px;
        bottom: 182px;
        width: auto;
        gap: 5px;
        padding: 13px 15px;
        max-width: none;
        border-radius: 16px;
    }

    .hero-slide-copy strong {
        font-size: clamp(1.08rem, 6vw, 1.55rem);
    }

    .hero-slide-copy span {
        font-size: 0.78rem;
        line-height: 1.34;
    }

    .hero-slide-dots {
        left: 18px;
        bottom: 164px;
    }

    .verse-card {
        right: 18px;
        top: auto;
        bottom: 18px;
        width: calc(100% - 36px);
        padding: 14px 16px;
    }

    .verse-card .quote-mark {
        font-size: 2rem;
    }

    .verse-card h2 {
        margin-bottom: 6px;
        font-size: 0.98rem;
    }

    .verse-card p {
        font-size: 0.74rem;
        line-height: 1.34;
    }

    .verse-card strong {
        margin-top: 8px;
        font-size: 0.78rem;
    }

    .history-photo-strip,
    .history-items {
        grid-template-columns: 1fr;
    }

    .history-photo-card:not(.active),
    .history-item:not(.active) {
        display: none;
    }

    .history-line {
        margin-inline: 24px;
    }

    .journey-stats {
        grid-template-columns: 1fr;
    }

    .about-footer-brand,
    .about-marvvium-signature {
        align-items: flex-start;
    }

    .footer-verse {
        grid-template-columns: auto 1fr;
    }

    .footer-verse strong {
        grid-column: 2;
        border-left: 0;
        padding-left: 0;
    }
}
</style>
