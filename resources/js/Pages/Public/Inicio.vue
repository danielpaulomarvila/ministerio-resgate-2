<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import PrayerRequestModal from '../../Components/PrayerRequestModal.vue';
import PromiseBox from '../../Components/PromiseBox.vue';
import { usePublicPageTransition } from '../../Composables/usePublicPageTransition';

defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
});

const showPrayerModal = ref(false);
const activeHeroSlide = ref(0);
const heroImageErrors = ref(new Set());
const publicPageElement = ref(null);
let heroCarouselTimer = null;

const { navigatePublicPage } = usePublicPageTransition(publicPageElement);

// Conteúdo provisório da página Início.
// Futuramente será carregado da Administração Geral → Site Público.
const highlightCards = [
    {
        title: 'Comunidade',
        text: 'Uma família que acolhe você.',
        icon: 'people',
    },
    {
        title: 'Cultos',
        text: 'Momentos de adoração e presença de Deus.',
        icon: 'church',
    },
    {
        title: 'Ensinamentos',
        text: 'Palavra que transforma e gera frutos.',
        icon: 'book',
    },
    {
        title: 'Intercessão',
        text: 'Oramos por você e com você.',
        icon: 'pray',
    },
    {
        title: 'Ação Social',
        text: 'Amor que se move e faz a diferença.',
        icon: 'care',
    },
];

// Imagens provisórias do carrossel principal.
// Futuramente serão substituídas por uploads da Administração Geral.
const heroSlides = [
    {
        image: '/images/hero/resgate-oracao-01.jpg',
        fallback: 'linear-gradient(135deg, #06152f 0%, #12345f 52%, #d8a241 100%)',
        position: 'center',
        title: 'Fui resgatado por Deus.',
        text: 'Quando eu não tinha forças, Cristo me levantou.',
    },
    {
        image: '/images/hero/resgate-adoracao-02.jpg',
        fallback: 'linear-gradient(135deg, #020817 0%, #183153 50%, #f59e0b 100%)',
        position: 'center',
        title: 'Cristo me libertou.',
        text: 'A verdade de Deus mudou minha história.',
    },
    {
        image: '/images/hero/resgate-familia-03.jpg',
        fallback: 'linear-gradient(135deg, #071b3a 0%, #604827 48%, #f2c15b 100%)',
        position: 'center',
        title: 'Deus restaurou minha família.',
        text: 'A graça de Deus trouxe paz para nossa casa.',
    },
    {
        image: '/images/hero/resgate-biblia-04.jpg',
        fallback: 'linear-gradient(135deg, #08162e 0%, #564021 48%, #fff1c2 100%)',
        position: 'center',
        title: 'Cristo trouxe paz ao meu coração.',
        text: 'Em Deus encontrei descanso e direção.',
    },
    {
        image: '/images/hero/resgate-culto-05.jpg',
        fallback: 'linear-gradient(135deg, #06152f 0%, #3a2a1b 46%, #f6c65b 100%)',
        position: 'center',
        title: 'Deus curou minhas feridas.',
        text: 'O amor de Cristo alcançou o que ninguém via.',
    },
    {
        image: '/images/hero/resgate-abraco-06.jpg',
        fallback: 'linear-gradient(135deg, #071b3a 0%, #253f4f 46%, #d97706 100%)',
        position: 'center',
        title: 'Deus transformou minha história.',
        text: 'O que era dor virou testemunho.',
    },
    {
        image: '/images/hero/resgate-grupo-orando-07.jpg',
        fallback: 'linear-gradient(135deg, #06152f 0%, #22425c 52%, #f6c65b 100%)',
        position: 'center',
        title: 'A graça de Deus me encontrou.',
        text: 'Quando eu pensei que era o fim, Deus me chamou.',
    },
    {
        image: '/images/hero/resgate-cruz-08.jpg',
        fallback: 'linear-gradient(135deg, #020817 0%, #0a2342 48%, #f2c15b 100%)',
        position: 'center 48%',
        title: 'Fui alcançado pelo amor de Cristo.',
        text: 'A luz de Deus me encontrou no caminho.',
    },
    {
        image: '/images/hero/resgate-recomeco-09.jpg',
        fallback: 'linear-gradient(135deg, #020817 0%, #17324d 48%, #ffd27a 100%)',
        position: 'center',
        title: 'Jesus me deu um novo começo.',
        text: 'A esperança voltou a nascer no meu coração.',
    },
    {
        image: '/images/hero/resgate-louvor-10.jpg',
        fallback: 'linear-gradient(135deg, #071b3a 0%, #442d1b 48%, #f59e0b 100%)',
        position: 'center',
        title: 'Deus me chamou de volta.',
        text: 'Hoje caminho com fé, propósito e esperança.',
    },
];

function openPrayerModal() {
    showPrayerModal.value = true;
}

function handlePrayerTracking(code) {
    if (typeof window !== 'undefined') {
        window.sessionStorage.setItem('familia_resgate_pending_prayer_tracking_code', code);
    }

    navigatePublicPage(null, '/acompanhar_oracao');
}

function goToNextHeroSlide() {
    activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.length;
}

function markHeroImageError(index) {
    const nextErrors = new Set(heroImageErrors.value);
    nextErrors.add(index);
    heroImageErrors.value = nextErrors;
}

onMounted(() => {
    heroCarouselTimer = window.setInterval(goToNextHeroSlide, 4000);
});

onBeforeUnmount(() => {
    if (heroCarouselTimer) {
        window.clearInterval(heroCarouselTimer);
    }
});
</script>

<template>
    <Head title="Início" />

    <div ref="publicPageElement" class="public-home-screen public-route-page">
        <header class="public-header">
            <Link class="brand-mark" href="/inicio" aria-label="Família Resgate" @click="navigatePublicPage($event, '/inicio')">
                <img class="brand-logo" src="/images/brand/logo-resgate-gold.png" alt="Logo Ministério Resgate" />
                <span><small>Família</small><strong>Resgate</strong></span>
            </Link>
            <nav class="public-nav" aria-label="Navegação pública">
                <Link class="nav-item active" href="/inicio" @click="navigatePublicPage($event, '/inicio')">Início</Link>
                <Link class="nav-item" href="/sobre_nos" @click="navigatePublicPage($event, '/sobre_nos')">Sobre Nós</Link>
                <Link class="nav-item" href="/cultos" @click="navigatePublicPage($event, '/cultos')">Cultos</Link>
                <Link class="nav-item" href="/eventos" @click="navigatePublicPage($event, '/eventos')">Eventos</Link>
                <Link class="nav-item" href="/contato" @click="navigatePublicPage($event, '/contato')">Contato</Link>
            </nav>
            <Link v-if="canLogin" class="login-button" :href="$page.props.auth.user ? route('dashboard') : route('login')">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                Entrar
            </Link>
        </header>

<main id="inicio" class="public-stage">
            <!-- Hero da página Início com mensagem de boas-vindas, ações principais e cena espiritual clara. -->
            <section class="hero-zone" aria-label="Boas-vindas à Família Resgate">
                <div class="hero-copy">
                    <p class="hero-kicker">Bem-vindo à</p>
                    <h1>
                        <span>Família</span>
                        <strong>Resgate</strong>
                    </h1>
                    <p class="hero-mission">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10Z" />
                        </svg>
                        Um lugar de resgate, cuidado e recomeço.
                    </p>
                    <p class="hero-text">
                        Na Família Resgate, cremos que Cristo continua transformando histórias, restaurando vidas
                        e chamando pessoas para uma caminhada de fé, serviço e propósito.
                    </p>
                    <div class="hero-actions">
                        <button type="button" class="primary-cta" @click="openPrayerModal">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9 11V5a2 2 0 0 1 4 0v8M13 11V6a2 2 0 0 1 4 0v7" />
                                <path d="M7 13 5 9a2 2 0 0 0-3 2l3 7c1 2 3 3 5 3h5c3 0 5-2 5-5v-5" />
                            </svg>
                            Pedir Oração
                        </button>
                        <Link class="secondary-cta" href="/sobre_nos" @click="navigatePublicPage($event, '/sobre_nos')">
                            Conheça a Igreja
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </Link>
                    </div>
                </div>

                <div class="hero-visual" aria-label="Testemunhos visuais da Família Resgate">
                    <div class="hero-carousel">
                        <article
                            v-for="(slide, index) in heroSlides"
                            :key="slide.image"
                            class="hero-slide"
                            :class="{ active: activeHeroSlide === index }"
                            :style="{ '--hero-fallback': slide.fallback, '--hero-position': slide.position }"
                            :aria-hidden="activeHeroSlide !== index"
                        >
                            <img
                                v-if="!heroImageErrors.has(index)"
                                :src="slide.image"
                                :alt="slide.title"
                                loading="eager"
                                @error="markHeroImageError(index)"
                            />
                            <span v-else class="hero-photo-placeholder"></span>
                            <div class="hero-slide-copy">
                                <strong>{{ slide.title }}</strong>
                                <span>{{ slide.text }}</span>
                            </div>
                        </article>
                    </div>

                    <div class="hero-slide-dots" aria-hidden="true">
                        <span
                            v-for="(slide, index) in heroSlides"
                            :key="`${slide.image}-dot`"
                            :class="{ active: activeHeroSlide === index }"
                        ></span>
                    </div>

                    <article class="verse-card">
                        <span class="quote-mark">“</span>
                        <h2>Cristo se deu em resgate por todos.</h2>
                        <p>O qual se deu a si mesmo em resgate por todos, para servir de testemunho a seu tempo.</p>
                        <strong>1 Timóteo 2:6</strong>
                    </article>
                </div>
            </section>

            <PromiseBox />

            <!-- Cards compactos de destaque, todos dentro da mesma tela para manter o desktop sem scroll. -->
            <section class="highlight-row" aria-label="Destaques da igreja">
                <article v-for="card in highlightCards" :key="card.title" class="highlight-card">
                    <span class="highlight-icon">
                        <svg v-if="card.icon === 'people'" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M18 21a7 7 0 1 0 0-14 7 7 0 0 0 0 14ZM30 21a6 6 0 1 0 0-12" />
                            <path d="M5 40c1-9 7-14 13-14s12 5 13 14M28 27c6 1 11 5 12 13" />
                        </svg>
                        <svg v-else-if="card.icon === 'church'" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M8 40h32V20L24 10 8 20Z" />
                            <path d="M24 6v13M18 12h12M18 40V28h12v12" />
                        </svg>
                        <svg v-else-if="card.icon === 'book'" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M8 10h14c4 0 6 2 6 6v22c0-4-2-6-6-6H8Z" />
                            <path d="M40 10H28c-4 0-6 2-6 6v22c0-4 2-6 6-6h12Z" />
                            <path d="M24 15v22" />
                        </svg>
                        <svg v-else-if="card.icon === 'pray'" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M20 8v19l-7-9a4 4 0 0 0-6 5l10 15" />
                            <path d="M28 8v19l7-9a4 4 0 0 1 6 5L31 38" />
                        </svg>
                        <svg v-else viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M24 17c-4-8-15-3-10 6 3 6 10 10 10 10s7-4 10-10c5-9-6-14-10-6Z" />
                            <path d="M9 38c6-2 11-2 15 1 4-3 9-3 15-1" />
                        </svg>
                    </span>
                    <div>
                        <h2>{{ card.title }}</h2>
                        <p>{{ card.text }}</p>
                    </div>
                </article>
            </section>

            <!-- Rodapé visual compacto com versículo, acompanhamento e assinatura discreta da desenvolvedora. -->
            <footer class="home-footer">
                <div class="footer-verse">
                    <span>“</span>
                    <p>Confie no Senhor de todo o seu coração e não se apoie em seu próprio entendimento.</p>
                    <strong>Provérbios 3:5</strong>
                </div>
                <div class="footer-actions">
                    <Link class="tracking-card" href="/acompanhar_oracao" @click="navigatePublicPage($event, '/acompanhar_oracao')">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M10.5 18a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15ZM16 16l5 5" />
                        </svg>
                        <span>
                            <strong>Acompanhar Oração</strong>
                            <small>Já tem um código? Acompanhe seu pedido.</small>
                        </span>
                    </Link>

                    <div class="marvvium-signature" aria-label="Plataforma desenvolvida por Marvvium">
                        <img src="/images/brand/logo-marvvium.png" alt="Marvvium" />
                        <span>
                            <strong>Plataforma desenvolvida por Marvvium</strong>
                            <small>Muito além de um sistema</small>
                        </span>
                    </div>
                </div>
            </footer>
        </main>

        <PrayerRequestModal :show="showPrayerModal" @close="showPrayerModal = false" @track="handlePrayerTracking" />
    </div>
</template>

<style scoped>
.public-home-screen {
    position: relative;
    display: grid;
    grid-template-rows: auto 1fr;
    min-height: 100vh;
    overflow-x: hidden;
    padding: 0 clamp(26px, 3.6vw, 56px) 28px;
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
    grid-template-rows: auto auto auto auto;
    gap: 18px;
    min-height: 0;
    padding-top: 10px;
}

.hero-zone {
    position: relative;
    display: grid;
    grid-template-columns: minmax(350px, 38%) minmax(0, 62%);
    min-height: clamp(460px, 50vh, 560px);
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
    padding: clamp(22px, 2.5vw, 36px) clamp(20px, 2.4vw, 34px) clamp(22px, 2.5vw, 36px) clamp(34px, 4.2vw, 62px);
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
    bottom: clamp(22px, 2.4vw, 34px);
    display: grid;
    width: min(46%, 390px);
    max-width: calc(100% - 390px);
    gap: 6px;
    padding: clamp(11px, 1.05vw, 15px) clamp(14px, 1.35vw, 18px);
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
    bottom: clamp(8px, 1vw, 14px);
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
    bottom: clamp(22px, 2.4vw, 34px);
    display: flex;
    width: clamp(218px, 19vw, 290px);
    min-height: auto;
    flex-direction: column;
    justify-content: center;
    padding: clamp(12px, 1vw, 15px);
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
    min-height: 112px;
    overflow: visible;
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
    padding: clamp(8px, 0.9vw, 12px);
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
    width: clamp(42px, 3.6vw, 54px);
    height: clamp(42px, 3.6vw, 54px);
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
    grid-template-columns: minmax(0, 1fr) minmax(390px, 0.48fr);
    align-items: center;
    gap: 14px;
    min-height: 82px;
    overflow: visible;
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
    min-height: 46px;
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
    width: 25px;
    height: 25px;
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
    font-size: 0.78rem;
}

.tracking-card small {
    margin-top: 3px;
    color: rgba(248, 242, 230, 0.76);
    font-size: 0.58rem;
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
