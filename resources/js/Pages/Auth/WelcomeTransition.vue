<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    destination: {
        type: String,
        default: '/familia',
    },
    greetingName: {
        type: String,
        default: 'Família Resgate',
    },
});

const page = usePage();
const remainingSeconds = ref(8);
let redirectTimer = null;
let countdownTimer = null;

function getNameFromUser(user) {
    const person = user?.person ?? {};
    const preferredName = person.preferred_name || user?.preferred_name || user?.display_name || user?.first_name;

    if (preferredName) {
        return preferredName;
    }

    const fullName = person.full_name || user?.full_name || user?.name;

    if (!fullName) {
        return 'Família Resgate';
    }

    const nameParts = fullName.trim().split(/\s+/).filter(Boolean);

    if (nameParts.length >= 3) {
        return `${nameParts[0]} ${nameParts[nameParts.length - 1]}`;
    }

    return nameParts[0] || 'Família Resgate';
}

const displayName = computed(() => props.greetingName || getNameFromUser(page.props.auth?.user));
const finalDestination = computed(() => props.destination || '/familia');

function enterNow() {
    if (redirectTimer) {
        window.clearTimeout(redirectTimer);
    }

    if (countdownTimer) {
        window.clearInterval(countdownTimer);
    }

    router.visit(finalDestination.value, { preserveScroll: false });
}

onMounted(() => {
    countdownTimer = window.setInterval(() => {
        remainingSeconds.value = Math.max(0, remainingSeconds.value - 1);
    }, 1000);

    redirectTimer = window.setTimeout(() => {
        enterNow();
    }, 8000);
});

onBeforeUnmount(() => {
    if (redirectTimer) {
        window.clearTimeout(redirectTimer);
    }

    if (countdownTimer) {
        window.clearInterval(countdownTimer);
    }
});
</script>

<template>
    <Head title="Boas-vindas" />

    <main class="welcome-transition-screen">
        <div class="ambient-phrase phrase-one">comunhão • cuidado • propósito</div>
        <div class="ambient-phrase phrase-two">paz para a sua caminhada</div>

        <header class="transition-header" aria-label="Central Família Resgate">
            <img src="/images/brand/logo-resgate-gold.png" alt="Logo oficial Família Resgate" />
            <span><small>Central</small><strong>Família Resgate</strong></span>
        </header>

        <section class="transition-card" aria-label="Boas-vindas pós-login">
            <div class="gold-orb" aria-hidden="true">♥</div>
            <p class="transition-kicker">Boas-vindas à sua casa digital</p>
            <h1>Bem-vindo de volta, {{ displayName }}.</h1>
            <p class="transition-subtitle">Que bom ter você aqui novamente. A Central Família Resgate foi preparada para cuidar, organizar e fortalecer sua caminhada conosco.</p>

            <blockquote>
                <p>“Você não está entrando apenas em uma plataforma. Está entrando em um espaço de comunhão, serviço, cuidado e propósito.”</p>
                <cite>Mateus 18:20 · Onde dois ou três estiverem reunidos</cite>
            </blockquote>

            <div class="progress-area" aria-live="polite">
                <div class="progress-copy">
                    <span>Estamos preparando sua casa digital com carinho...</span>
                    <strong>{{ remainingSeconds }}s</strong>
                </div>
                <div class="progress-track" aria-hidden="true"><span></span></div>
            </div>

            <button class="enter-now-button" type="button" @click="enterNow">
                Entrar agora
                <span>→</span>
            </button>
        </section>

        <footer class="transition-footer" aria-label="Plataforma desenvolvida por Marvvium">
            <img src="/images/brand/logo-marvvium.png" alt="Logo Marvvium" />
            <span><strong>Marvvium</strong><small>Muito além de um sistema</small></span>
        </footer>
    </main>
</template>

<style scoped>
.welcome-transition-screen{position:relative;display:grid;grid-template-rows:auto minmax(0,1fr) auto;height:100vh;min-height:100vh;max-height:100vh;overflow:hidden;padding:18px clamp(18px,3vw,46px);color:#fff8e8;background:radial-gradient(circle at 20% 22%,rgba(246,198,91,.36),transparent 22%),radial-gradient(circle at 82% 74%,rgba(225,104,37,.18),transparent 24%),linear-gradient(135deg,#fff8e8 0%,#efe0c4 42%,#071a35 43%,#041022 100%)}.welcome-transition-screen:before{content:'';position:absolute;inset:0;pointer-events:none;background:linear-gradient(120deg,rgba(255,255,255,.16),transparent 42%),repeating-linear-gradient(145deg,rgba(7,26,53,.05) 0,rgba(7,26,53,.05) 1px,transparent 1px,transparent 28px);opacity:.5}.ambient-phrase{position:absolute;z-index:0;pointer-events:none;color:rgba(122,75,12,.09);font-family:Georgia,serif;font-size:clamp(2rem,5vw,5.8rem);font-weight:900;letter-spacing:-.08em;white-space:nowrap}.phrase-one{left:3%;top:16%}.phrase-two{right:-6%;bottom:14%;color:rgba(255,248,232,.06)}.transition-header,.transition-card,.transition-footer{position:relative;z-index:1}.transition-header{display:inline-flex;align-items:center;gap:11px;color:#06152f;font-family:Georgia,serif}.transition-header img{width:48px;height:48px;object-fit:contain}.transition-header small,.transition-header strong{display:block;line-height:1}.transition-header small{color:#9a6a16;font-size:.68rem;font-weight:900;letter-spacing:.16em;text-transform:uppercase}.transition-header strong{font-size:1.38rem}.transition-card{align-self:center;justify-self:center;width:min(650px,92vw);max-height:calc(100vh - 132px);padding:clamp(22px,3.2vw,38px);border:1px solid rgba(246,198,91,.48);border-radius:28px;text-align:center;background:linear-gradient(145deg,rgba(7,26,53,.98),rgba(4,16,34,.96));box-shadow:0 28px 72px rgba(4,16,34,.4),inset 0 1px 0 rgba(255,248,232,.16);animation:cardReveal .75s ease both}.gold-orb{display:grid;place-items:center;width:52px;height:52px;margin:0 auto 12px;border-radius:50%;color:#071a35;background:radial-gradient(circle,#fff8e8 0%,#f6c65b 62%,#c7831f 100%);box-shadow:0 0 26px rgba(246,198,91,.36);font-size:1.35rem}.transition-kicker{margin:0 0 9px;color:#f6c65b;font-size:.68rem;font-weight:900;letter-spacing:.18em;text-transform:uppercase}.transition-card h1{margin:0;color:#fff8e8;font-family:Georgia,serif;font-size:clamp(1.85rem,4vw,3.25rem);line-height:1;letter-spacing:-.045em}.transition-subtitle{max-width:560px;margin:14px auto 0;color:#d9e4f6;font-size:.96rem;line-height:1.55}.transition-card blockquote{margin:18px auto 0;padding:16px 18px;border:1px solid rgba(246,198,91,.2);border-radius:18px;background:rgba(255,248,232,.06)}.transition-card blockquote p{margin:0;color:#fff4cf;font-family:Georgia,serif;font-size:1rem;line-height:1.5}.transition-card cite{display:block;margin-top:9px;color:#f6c65b;font-size:.75rem;font-style:normal;font-weight:900}.progress-area{margin-top:18px}.progress-copy{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px;color:#d9e4f6;font-size:.84rem;font-weight:800}.progress-copy strong{color:#f6c65b}.progress-track{height:7px;overflow:hidden;border-radius:999px;background:rgba(255,248,232,.14)}.progress-track span{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#f6c65b,#e16825,#fff4cf);box-shadow:0 0 20px rgba(246,198,91,.4);animation:progressFill 8s linear forwards}.enter-now-button{display:inline-flex;align-items:center;justify-content:center;gap:10px;margin-top:18px;padding:13px 22px;border:0;border-radius:14px;color:#071a35;background:linear-gradient(135deg,#fff4cf,#f6c65b 58%,#c7831f);box-shadow:0 16px 30px rgba(246,198,91,.22);font-size:.94rem;font-weight:950;cursor:pointer;transition:transform .2s ease,box-shadow .2s ease}.enter-now-button:hover{transform:translateY(-2px);box-shadow:0 18px 34px rgba(246,198,91,.32)}.transition-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;color:#fff8e8}.transition-footer img{width:30px;height:30px;object-fit:contain}.transition-footer span{display:grid}.transition-footer strong{font-size:.78rem}.transition-footer small{color:#f6c65b;font-size:.6rem;font-weight:800}@keyframes progressFill{from{width:0}to{width:100%}}@keyframes cardReveal{from{opacity:0;transform:translateY(14px) scale(.985)}to{opacity:1;transform:translateY(0) scale(1)}}@media (max-width:860px){.welcome-transition-screen{padding:14px 16px;background:linear-gradient(160deg,#fff8e8 0%,#efe0c4 32%,#071a35 33%,#041022 100%)}.transition-card{width:min(580px,94vw);padding:22px 18px;border-radius:24px}.transition-subtitle{font-size:.9rem}.transition-footer{justify-content:center}.ambient-phrase{display:none}}@media (max-width:520px){.transition-header img{width:40px;height:40px}.transition-header strong{font-size:1.1rem}.transition-card h1{font-size:clamp(1.55rem,8vw,2.25rem)}.transition-card blockquote p{font-size:.9rem}.progress-copy{font-size:.76rem}.enter-now-button{width:100%}}@media (max-height:700px){.welcome-transition-screen{padding-top:10px;padding-bottom:10px}.transition-header img{width:38px;height:38px}.transition-header strong{font-size:1.1rem}.transition-card{max-height:calc(100vh - 98px);padding:18px;border-radius:22px}.gold-orb{width:42px;height:42px;margin-bottom:8px;font-size:1.1rem}.transition-kicker{margin-bottom:6px;font-size:.6rem}.transition-card h1{font-size:clamp(1.45rem,4vw,2.5rem)}.transition-subtitle{margin-top:10px;font-size:.84rem;line-height:1.38}.transition-card blockquote{margin-top:12px;padding:12px}.transition-card blockquote p{font-size:.84rem;line-height:1.36}.transition-card cite{margin-top:7px;font-size:.66rem}.progress-area{margin-top:12px}.enter-now-button{margin-top:12px;padding:10px 18px}.transition-footer img{width:24px;height:24px}.transition-footer small{display:none}}@media (max-height:560px){.transition-card blockquote{display:none}.gold-orb{display:none}.transition-subtitle{max-width:520px}.transition-card{padding:16px}.progress-area{margin-top:10px}.enter-now-button{margin-top:10px}.ambient-phrase{display:none}}
</style>
