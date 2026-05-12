<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { promiseMessages } from '../data/promiseMessages';

// Caixinha da Promessa da Home pública.
// A escolha é local e temporária: no futuro pode receber filtros, favoritos e histórico pela API.
const storageKey = 'familia_resgate_last_promise';
const savedStorageKey = 'familia_resgate_saved_promises';
const cooldownStorageKey = 'familia_resgate_promise_cooldown_until';

const fallbackPromise = promiseMessages.find((message) => message.reference === 'Salmos 37:5') ?? promiseMessages[0];
const selectedPromise = ref(fallbackPromise);
const isRevealing = ref(false);
const isLaunchingPaper = ref(false);
const hasReceivedPromise = ref(false);
const saved = ref(false);
const nowTick = ref(Date.now());
const cooldownUntil = ref(0);
let cooldownTimer = null;

const wordCards = [
    {
        title: 'Coração',
        text: 'Deus conhece o seu coração e cuida de você.',
        icon: 'heart',
    },
    {
        title: 'Perseverança',
        text: 'A constância na fé nos leva às promessas.',
        icon: 'shield',
    },
    {
        title: 'Fé em Deus',
        text: 'Tudo é possível para aquele que crê.',
        icon: 'cross',
    },
    {
        title: 'Propósito',
        text: 'Deus tem um propósito lindo para sua vida.',
        icon: 'crown',
    },
];

const cooldownRemaining = computed(() => Math.max(0, Math.ceil((cooldownUntil.value - nowTick.value) / 1000)));
const cooldownText = computed(() => `Você poderá receber outra mensagem em ${String(cooldownRemaining.value).padStart(2, '0')}s`);

onMounted(() => {
    const lastPromiseId = readLastPromiseId();
    const lastPromise = promiseMessages.find((message) => message.id === lastPromiseId);

    if (lastPromise) {
        selectedPromise.value = lastPromise;
        hasReceivedPromise.value = true;
    }

    cooldownUntil.value = readCooldownUntil();
    cooldownTimer = window.setInterval(() => {
        nowTick.value = Date.now();
    }, 1000);
});

onBeforeUnmount(() => {
    if (cooldownTimer) {
        window.clearInterval(cooldownTimer);
    }
});

function readLastPromiseId() {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.localStorage.getItem(storageKey);
}

function readCooldownUntil() {
    if (typeof window === 'undefined') {
        return 0;
    }

    return Number(window.localStorage.getItem(cooldownStorageKey) ?? 0);
}

function startCooldown() {
    const nextCooldown = Date.now() + 60_000;

    cooldownUntil.value = nextCooldown;

    if (typeof window !== 'undefined') {
        window.localStorage.setItem(cooldownStorageKey, String(nextCooldown));
    }
}

function choosePromise() {
    if (cooldownRemaining.value > 0 || isLaunchingPaper.value) {
        return;
    }

    const lastPromiseId = readLastPromiseId();
    const availableMessages = promiseMessages.filter((message) => message.id !== lastPromiseId);
    const messagePool = availableMessages.length > 0 ? availableMessages : promiseMessages;
    const nextPromise = messagePool[Math.floor(Math.random() * messagePool.length)];

    saved.value = false;
    hasReceivedPromise.value = false;
    isRevealing.value = false;
    isLaunchingPaper.value = true;

    window.setTimeout(() => {
        selectedPromise.value = nextPromise;
        hasReceivedPromise.value = true;
        isRevealing.value = true;
        isLaunchingPaper.value = false;
        startCooldown();

        if (typeof window !== 'undefined') {
            window.localStorage.setItem(storageKey, nextPromise.id);
        }
    }, 620);
}

function closePromise() {
    hasReceivedPromise.value = false;
    isRevealing.value = false;
    saved.value = false;
}

function savePromise() {
    if (typeof window === 'undefined' || !hasReceivedPromise.value) {
        return;
    }

    const currentSavedMessages = JSON.parse(window.localStorage.getItem(savedStorageKey) ?? '[]');
    const nextSavedMessages = [
        selectedPromise.value,
        ...currentSavedMessages.filter((message) => message.id !== selectedPromise.value.id),
    ].slice(0, 12);

    window.localStorage.setItem(savedStorageKey, JSON.stringify(nextSavedMessages));
    saved.value = true;
}
</script>

<template>
    <!-- Faixa central da Home: caixinha azul, pergaminho e palavras que edificam no mesmo painel compacto. -->
    <section class="promise-band" aria-label="Caixinha da Promessa">
        <!-- Área da Caixinha da Promessa, onde o usuário recebe uma mensagem devocional. -->
        <div class="promise-chest-panel">
            <div class="promise-panel-copy">
                <div class="promise-kicker">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20 7h-4.2A3.5 3.5 0 0 0 12 2.7 3.5 3.5 0 0 0 8.2 7H4a1 1 0 0 0-1 1v3h18V8a1 1 0 0 0-1-1Z" />
                        <path d="M4 13v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7H4Z" />
                        <path d="M12 7V21" />
                    </svg>
                    <span>Caixinha da Promessa</span>
                </div>
                <p>Receba uma palavra de fé para o seu dia.</p>
                <p class="promise-hint">Clique na caixinha e receba uma promessa de Deus.</p>
                <p v-if="cooldownRemaining > 0" class="promise-cooldown">
                    {{ cooldownText }}
                </p>
            </div>

            <button
                type="button"
                class="treasure-button"
                :class="{ 'is-launching': isLaunchingPaper }"
                :disabled="cooldownRemaining > 0"
                aria-label="Receber uma palavra da Caixinha de Promessa"
                @click="choosePromise"
            >
                <span class="chest-glow"></span>
                <span class="chest-lid">
                    <span class="chest-metal chest-metal-left"></span>
                    <span class="chest-metal chest-metal-right"></span>
                </span>
                <span class="chest-base">
                    <span class="chest-lock"></span>
                    <span class="chest-heart"></span>
                    <span class="chest-band chest-band-left"></span>
                    <span class="chest-band chest-band-right"></span>
                </span>
                <span class="flying-note">
                    <svg viewBox="0 0 42 42" aria-hidden="true">
                        <path d="M9 4 35 9 30 36 5 30Z" />
                        <path d="M21 17c-3-5-10-1-7 5 2 4 7 7 7 7s6-3 8-7c3-6-4-10-8-5Z" />
                    </svg>
                </span>
            </button>
        </div>

        <!-- Pergaminho premium que recebe a mensagem sorteada sem virar uma faixa amarela chapada. -->
        <div class="scroll-card">
            <span class="scroll-orbit"></span>
            <article class="parchment" :class="{ 'is-revealing': isRevealing, 'is-empty': !hasReceivedPromise }">
                <span class="scroll-roller scroll-roller-top"></span>
                <span class="scroll-roller scroll-roller-bottom"></span>
                <span class="scroll-seal">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10Z" />
                    </svg>
                </span>
                <template v-if="hasReceivedPromise">
                    <p class="promise-category">{{ selectedPromise.category }}</p>
                    <blockquote>{{ selectedPromise.text }}</blockquote>
                    <cite v-if="selectedPromise.reference">{{ selectedPromise.reference }}</cite>
                    <span v-else class="promise-type">{{ selectedPromise.type }}</span>
                    <div class="promise-actions">
                        <button type="button" class="promise-action" @click="savePromise">
                            {{ saved ? 'Guardada' : 'Guardar no coração' }}
                        </button>
                        <button type="button" class="promise-action subtle" @click="closePromise">
                            Fechar
                        </button>
                        <button
                            type="button"
                            class="promise-action another"
                            :disabled="cooldownRemaining > 0"
                            @click="choosePromise"
                        >
                            {{ cooldownRemaining > 0 ? cooldownText : 'Receber outra' }}
                        </button>
                    </div>
                </template>
                <template v-else>
                    <p class="promise-category">Promessa</p>
                    <blockquote>Clique na caixinha e receba uma palavra de fé para o seu dia.</blockquote>
                    <cite>Caixinha da Promessa</cite>
                </template>
                <span class="wax-seal">
                    <svg viewBox="0 0 32 32" aria-hidden="true">
                        <path d="M16 4 19 12h8l-6.5 4.7L23 25l-7-5.2L9 25l2.5-8.3L5 12h8Z" />
                    </svg>
                </span>
            </article>
        </div>

        <!-- Palavras que Edificam: quatro cards escuros/claros compactos como reforço visual da faixa. -->
        <div class="edifying-panel">
            <h2>
                <span></span>
                Palavras que Edificam
                <span></span>
            </h2>

            <div class="edifying-grid">
                <article v-for="card in wordCards" :key="card.title" class="edifying-card">
                    <svg v-if="card.icon === 'heart'" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M24 39s-15-9.5-15-21a8 8 0 0 1 15-4 8 8 0 0 1 15 4c0 11.5-15 21-15 21Z" />
                    </svg>
                    <svg v-else-if="card.icon === 'shield'" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M24 5 39 11v11c0 10-6 17-15 21C15 39 9 32 9 22V11Z" />
                        <path d="M24 15v16M16 23h16" />
                    </svg>
                    <svg v-else-if="card.icon === 'cross'" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M24 7v34M13 18h22" />
                    </svg>
                    <svg v-else viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M8 35h32l3-22-10 9-9-14-9 14-10-9Z" />
                        <path d="M12 40h24" />
                    </svg>
                    <h3>{{ card.title }}</h3>
                    <p>{{ card.text }}</p>
                </article>
            </div>
        </div>
    </section>
</template>

<style scoped>
.promise-band {
    display: grid;
    grid-template-columns: minmax(320px, 1.02fr) minmax(250px, 0.72fr) minmax(400px, 1.18fr);
    gap: 0;
    min-height: 0;
    overflow: hidden;
    border: 1px solid rgba(217, 164, 65, 0.32);
    border-radius: 18px;
    background:
        linear-gradient(90deg, rgba(255, 248, 234, 0.95), rgba(252, 243, 224, 0.92)),
        radial-gradient(circle at 34% 34%, rgba(246, 198, 91, 0.3), transparent 34%);
    box-shadow: 0 20px 42px rgba(10, 35, 66, 0.1);
}

.promise-chest-panel {
    position: relative;
    display: grid;
    grid-template-columns: 46% 54%;
    align-items: center;
    min-width: 0;
    overflow: hidden;
    padding: clamp(12px, 1.25vw, 22px);
    color: #f8f2e6;
    background:
        radial-gradient(circle at 24% 72%, rgba(245, 158, 11, 0.42), transparent 30%),
        linear-gradient(135deg, #071b3a 0%, #08264e 52%, #06152f 100%);
}

.promise-chest-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image:
        radial-gradient(circle, rgba(246, 198, 91, 0.86) 0 1px, transparent 1.8px),
        radial-gradient(circle, rgba(255, 248, 234, 0.55) 0 1px, transparent 1.6px);
    background-position: 24px 26px, 92px 78px;
    background-size: 88px 72px, 132px 112px;
    opacity: 0.26;
}

.promise-panel-copy {
    position: relative;
    z-index: 2;
    grid-column: 2;
    min-width: 0;
}

.promise-kicker {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #f8f2e6;
    font-size: clamp(0.78rem, 0.9vw, 1rem);
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.promise-kicker svg {
    width: 32px;
    height: 32px;
    fill: none;
    stroke: #f6c65b;
    stroke-width: 1.7;
}

.promise-panel-copy p {
    margin-top: 12px;
    max-width: 210px;
    color: rgba(248, 242, 230, 0.92);
    font-size: clamp(0.82rem, 0.92vw, 0.98rem);
    line-height: 1.55;
}

.promise-panel-copy .promise-hint {
    margin-top: 12px;
    color: #fff8ea;
    font-style: italic;
}

.promise-cooldown {
    padding: 8px 10px;
    border: 1px solid rgba(246, 198, 91, 0.28);
    border-radius: 12px;
    color: #ffd27a;
    background: rgba(6, 21, 47, 0.3);
    font-size: 0.72rem;
    font-weight: 800;
    line-height: 1.35;
}

.treasure-button {
    position: relative;
    z-index: 2;
    grid-column: 1;
    grid-row: 1;
    display: block;
    width: min(100%, 226px);
    height: clamp(132px, 13.5vw, 176px);
    justify-self: center;
    border: 0;
    background: transparent;
    cursor: pointer;
    filter: drop-shadow(0 18px 24px rgba(0, 0, 0, 0.38));
    transition: transform 320ms ease, filter 320ms ease;
}

.treasure-button:hover {
    transform: translateY(-4px);
    filter: drop-shadow(0 24px 34px rgba(245, 158, 11, 0.34));
}

.treasure-button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.treasure-button:disabled:hover {
    transform: none;
    filter: drop-shadow(0 18px 24px rgba(0, 0, 0, 0.32));
}

.chest-glow {
    position: absolute;
    left: 18%;
    right: 8%;
    top: 42%;
    height: 44%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 210, 122, 0.9), rgba(245, 158, 11, 0.34) 38%, transparent 68%);
    filter: blur(8px);
}

.chest-lid,
.chest-base {
    position: absolute;
    left: 4%;
    right: 5%;
    border: 1px solid rgba(255, 210, 122, 0.7);
    background:
        linear-gradient(90deg, rgba(255, 210, 122, 0.26), transparent 18% 82%, rgba(255, 210, 122, 0.2)),
        linear-gradient(135deg, #151014, #4a2b17 44%, #151014);
}

.chest-lid {
    top: 15%;
    height: 34%;
    border-radius: 18px 18px 8px 8px;
    transform: perspective(280px) rotateX(24deg) rotate(-5deg);
    transform-origin: bottom;
    box-shadow: inset 0 12px 22px rgba(255, 210, 122, 0.2);
}

.treasure-button:hover .chest-lid,
.treasure-button.is-launching .chest-lid {
    transform: perspective(280px) rotateX(46deg) rotate(-7deg) translateY(-8px);
}

.chest-base {
    bottom: 5%;
    height: 58%;
    border-radius: 12px 12px 18px 18px;
    box-shadow:
        inset 0 0 22px rgba(255, 210, 122, 0.18),
        inset 0 -18px 24px rgba(0, 0, 0, 0.38);
}

.chest-metal,
.chest-band {
    position: absolute;
    width: 13%;
    inset-block: 0;
    background: linear-gradient(#f6c65b, #a96a12 56%, #ffd27a);
    opacity: 0.85;
}

.chest-metal-left,
.chest-band-left {
    left: 18%;
}

.chest-metal-right,
.chest-band-right {
    right: 18%;
}

.chest-lock {
    position: absolute;
    left: 50%;
    top: 30%;
    width: 34px;
    height: 26px;
    border: 2px solid #ffd27a;
    border-radius: 8px;
    transform: translateX(-50%);
    background: linear-gradient(#ffd27a, #b87918);
}

.chest-heart {
    position: absolute;
    left: 50%;
    bottom: 22%;
    width: 42px;
    height: 42px;
    transform: translateX(-50%) rotate(45deg);
    border-right: 2px solid #ffd27a;
    border-bottom: 2px solid #ffd27a;
    border-radius: 7px;
    background: radial-gradient(circle at 30% 30%, #fff8ea, #f59e0b 48%, #7a4608);
    box-shadow: 0 0 24px rgba(245, 158, 11, 0.85);
}

.chest-heart::before,
.chest-heart::after {
    content: '';
    position: absolute;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: inherit;
}

.chest-heart::before {
    left: -20px;
}

.chest-heart::after {
    top: -20px;
}

.flying-note {
    position: absolute;
    right: 8%;
    bottom: 36%;
    width: 52px;
    opacity: 0;
    transform: translate(0, 0) rotate(-10deg) scale(0.72);
    pointer-events: none;
}

.flying-note svg {
    fill: #fff0c7;
    filter: drop-shadow(0 8px 12px rgba(0, 0, 0, 0.24));
}

.flying-note path + path {
    fill: none;
    stroke: #d9a441;
    stroke-width: 2;
}

.treasure-button.is-launching .flying-note {
    animation: paper-launch 660ms cubic-bezier(0.2, 0.9, 0.2, 1) both;
}

.scroll-card {
    position: relative;
    display: grid;
    place-items: center;
    min-width: 0;
    padding: 8px 14px;
    background: linear-gradient(135deg, rgba(255, 248, 234, 0.98), rgba(244, 232, 208, 0.75));
}

.scroll-card::before,
.scroll-card::after {
    content: '';
    position: absolute;
    inset-block: 18px;
    width: 1px;
    background: linear-gradient(transparent, rgba(184, 121, 24, 0.3), transparent);
}

.scroll-card::before {
    left: 0;
}

.scroll-card::after {
    right: 0;
}

.scroll-orbit {
    position: absolute;
    width: 94%;
    height: 72%;
    border: 1px solid rgba(245, 158, 11, 0.26);
    border-radius: 50%;
    transform: rotate(-13deg);
    box-shadow: 0 0 26px rgba(245, 158, 11, 0.2);
}

.parchment {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: min(94%, 260px);
    min-height: clamp(154px, 17vw, 208px);
    padding: 34px 20px 24px;
    color: #3b2710;
    text-align: center;
    background:
        radial-gradient(circle at 35% 20%, rgba(255, 255, 255, 0.58), transparent 24%),
        linear-gradient(135deg, #f6deb0, #fff0c7 42%, #e6bf74);
    border: 1px solid rgba(184, 121, 24, 0.34);
    border-radius: 18px 14px 20px 16px;
    clip-path: polygon(5% 2%, 94% 0, 98% 8%, 95% 19%, 99% 31%, 96% 43%, 99% 56%, 94% 68%, 98% 82%, 93% 98%, 7% 96%, 2% 88%, 5% 75%, 1% 63%, 4% 51%, 1% 39%, 5% 27%, 2% 13%);
    box-shadow: 0 22px 36px rgba(83, 47, 7, 0.18);
    transform: rotate(-2deg);
    transition: transform 360ms ease, filter 360ms ease;
}

.parchment.is-revealing {
    animation: reveal-scroll 460ms ease both;
}

.parchment.is-empty {
    filter: saturate(0.95);
}

.scroll-roller {
    position: absolute;
    left: 8%;
    right: 8%;
    height: 22px;
    border-radius: 999px;
    background: linear-gradient(90deg, #b87918, #f5d08c 18% 82%, #a96a12);
    box-shadow: inset 0 2px 6px rgba(255, 255, 255, 0.45), 0 4px 12px rgba(83, 47, 7, 0.18);
}

.scroll-roller-top {
    top: -9px;
}

.scroll-roller-bottom {
    bottom: -9px;
}

.scroll-seal {
    position: absolute;
    top: 12px;
    left: 50%;
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border-radius: 50%;
    background: linear-gradient(#f6c65b, #b87918);
    transform: translateX(-50%);
    box-shadow: 0 6px 16px rgba(184, 121, 24, 0.4);
}

.scroll-seal svg {
    width: 20px;
    height: 20px;
    fill: none;
    stroke: #fff8ea;
    stroke-width: 1.8;
}

.promise-category {
    margin: 0 0 8px;
    color: #b87918;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

blockquote {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(0.82rem, 0.96vw, 1.02rem);
    font-weight: 700;
    line-height: 1.45;
}

cite,
.promise-type {
    margin-top: 8px;
    color: #5a3914;
    font-size: 0.9rem;
    font-style: normal;
    font-weight: 700;
}

.promise-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 6px;
    margin-top: 10px;
}

.promise-action {
    min-height: 28px;
    border: 1px solid rgba(184, 121, 24, 0.34);
    border-radius: 999px;
    padding: 0 10px;
    color: #7a4608;
    background: rgba(255, 248, 234, 0.54);
    font-size: 0.62rem;
    font-weight: 900;
    line-height: 1.15;
    cursor: pointer;
    transition: transform 250ms ease, border-color 250ms ease, background 250ms ease;
}

.promise-action:hover {
    transform: translateY(-2px);
    border-color: #d97706;
    background: rgba(255, 248, 234, 0.8);
}

.promise-action.subtle {
    color: #5a3914;
}

.promise-action.another {
    max-width: 100%;
    color: #06152f;
    background: rgba(246, 198, 91, 0.42);
}

.promise-action:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.promise-action:disabled:hover {
    transform: none;
    border-color: rgba(184, 121, 24, 0.34);
}

.wax-seal {
    position: absolute;
    right: -5px;
    bottom: 5px;
    display: grid;
    width: 48px;
    height: 48px;
    place-items: center;
    border-radius: 50%;
    background: radial-gradient(circle at 34% 30%, #ffd27a, #a96a12 68%);
    box-shadow: 0 8px 14px rgba(83, 47, 7, 0.26);
}

.wax-seal svg {
    width: 25px;
    height: 25px;
    fill: none;
    stroke: #fff0c7;
    stroke-width: 1.7;
}

.edifying-panel {
    display: flex;
    min-width: 0;
    flex-direction: column;
    justify-content: center;
    gap: clamp(8px, 0.8vw, 14px);
    padding: clamp(10px, 1.1vw, 20px);
    background: rgba(255, 248, 234, 0.72);
}

.edifying-panel h2 {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin: 0;
    color: #08162e;
    font-size: clamp(0.82rem, 1vw, 1.04rem);
    font-weight: 900;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.edifying-panel h2 span {
    width: 32px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #d9a441, transparent);
}

.edifying-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: clamp(7px, 0.8vw, 12px);
}

.edifying-card {
    display: flex;
    min-width: 0;
    min-height: 136px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 10px 9px;
    text-align: center;
    border: 1px solid rgba(217, 164, 65, 0.42);
    border-radius: 14px;
    background: linear-gradient(180deg, rgba(255, 248, 234, 0.96), rgba(248, 238, 218, 0.72));
    box-shadow: 0 12px 20px rgba(10, 35, 66, 0.08);
    transition: transform 280ms ease, border-color 280ms ease, box-shadow 280ms ease;
}

.edifying-card:hover {
    transform: translateY(-4px);
    border-color: rgba(245, 158, 11, 0.84);
    box-shadow: 0 18px 28px rgba(217, 164, 65, 0.16);
}

.edifying-card svg {
    width: clamp(32px, 2.8vw, 48px);
    height: clamp(32px, 2.8vw, 48px);
    fill: none;
    stroke: #e59b0b;
    stroke-width: 1.8;
    filter: drop-shadow(0 8px 12px rgba(217, 164, 65, 0.18));
}

.edifying-card h3 {
    margin: 9px 0 5px;
    color: #c77d0e;
    font-size: clamp(0.72rem, 0.78vw, 0.86rem);
    font-weight: 900;
    text-transform: uppercase;
}

.edifying-card p {
    margin: 0;
    color: #1d2939;
    font-size: clamp(0.66rem, 0.72vw, 0.76rem);
    line-height: 1.38;
}

@keyframes paper-launch {
    0% {
        opacity: 0;
        transform: translate(-12px, 18px) rotate(-18deg) scale(0.55);
    }

    28% {
        opacity: 1;
        transform: translate(18px, -26px) rotate(12deg) scale(0.9);
    }

    72% {
        opacity: 1;
        transform: translate(90px, -62px) rotate(-8deg) scale(1.08);
    }

    100% {
        opacity: 0;
        transform: translate(132px, -72px) rotate(4deg) scale(1.22);
    }
}

@keyframes reveal-scroll {
    from {
        opacity: 0.5;
        transform: translateY(10px) scale(0.96) rotate(-4deg);
        filter: brightness(1.1);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1) rotate(-2deg);
        filter: brightness(1);
    }
}

@media (max-width: 1180px) {
    .promise-band {
        grid-template-columns: 1fr;
        overflow: visible;
    }

    .promise-chest-panel,
    .edifying-panel {
        min-height: 260px;
    }
}
</style>
