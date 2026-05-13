<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);
const accessPanelOpen = ref(false);
const accessRequestSent = ref(false);

// Conteúdo provisório da tela Entrar/Login.
// Futuramente será carregado da Administração Geral → Site Público.
const loginContent = {
    welcomeKicker: 'Bem-vindo de volta à',
    welcomeTitle: 'Família Resgate',
    welcomeText: 'Que bom ter você aqui novamente! Este espaço foi preparado com amor para você crescer, servir e viver o propósito de Deus.',
    mainVerse: 'Eu lhes darei um coração novo e porei dentro de vocês um espírito novo; tirarei de vocês o coração de pedra e lhes darei um coração de carne.',
    mainReference: 'Ezequiel 36:26',
    sideVerse: 'Porque onde estão dois ou três reunidos em meu nome, ali estou eu no meio deles.',
    sideReference: 'Mateus 18:20',
};

const pillars = [
    { title: 'Ambiente Seguro', text: 'Seus dados protegidos com cuidado.', icon: 'shield' },
    { title: 'Comunidade de Fé', text: 'Conecte-se com uma família que ora por você.', icon: 'heart' },
    { title: 'Propósito e Missão', text: 'Descubra seu chamado e ministério na igreja.', icon: 'flame' },
];

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const accessForm = ref({
    name: '',
    whatsapp: '',
    email: '',
    visitorType: 'visitante',
    notes: '',
});

function togglePasswordVisibility() {
    showPassword.value = !showPassword.value;
}

function openAccessRequestPanel() {
    accessPanelOpen.value = true;
    accessRequestSent.value = false;
}

function closeAccessRequestPanel() {
    accessPanelOpen.value = false;
}

function submitAccessRequest() {
    if (!accessForm.value.name || !accessForm.value.whatsapp) {
        return;
    }

    const savedRequests = JSON.parse(localStorage.getItem('familia_resgate_access_requests') ?? '[]');
    localStorage.setItem(
        'familia_resgate_access_requests',
        JSON.stringify([
            {
                name: accessForm.value.name,
                whatsapp: accessForm.value.whatsapp,
                email: accessForm.value.email,
                visitorType: accessForm.value.visitorType,
                notes: accessForm.value.notes,
                created_at: new Date().toISOString(),
            },
            ...savedRequests,
        ]),
    );

    accessRequestSent.value = true;
    accessForm.value = { name: '', whatsapp: '', email: '', visitorType: 'visitante', notes: '' };
}

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Entrar" />

    <main class="login-screen">
        <header class="login-header">
            <Link class="brand-mark" href="/inicio" aria-label="Família Resgate">
                <img src="/images/brand/logo-resgate-gold.png" alt="Logo oficial Família Resgate" />
                <span><small>Família</small><strong>Resgate</strong></span>
            </Link>

            <nav class="public-nav" aria-label="Navegação pública">
                <Link href="/inicio">Início</Link>
                <Link href="/sobre_nos">Sobre Nós</Link>
                <Link href="/cultos">Cultos</Link>
                <Link href="/eventos">Eventos</Link>
                <Link href="/contato">Contato</Link>
            </nav>

            <Link class="login-pill active" :href="route('login')">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                Entrar
            </Link>
        </header>

        <section class="login-layout" aria-label="Entrar na Central Família Resgate">
            <div class="welcome-panel">
                <p class="welcome-kicker">{{ loginContent.welcomeKicker }}</p>
                <h1>{{ loginContent.welcomeTitle }}</h1>
                <p class="welcome-text">{{ loginContent.welcomeText }}</p>
                <div class="gold-divider"><span></span><strong>♥</strong><span></span></div>
                <blockquote>“{{ loginContent.mainVerse }}”<cite>{{ loginContent.mainReference }}</cite></blockquote>

                <div class="pillar-grid">
                    <article v-for="pillar in pillars" :key="pillar.title">
                        <span class="pillar-icon">
                            <svg v-if="pillar.icon === 'shield'" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 5 6v6c0 4.6 3 7.5 7 9 4-1.5 7-4.4 7-9V6Z" /><path d="M12 8v8M9 11h6" /></svg>
                            <svg v-else-if="pillar.icon === 'heart'" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10Z" /></svg>
                            <svg v-else viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21c4-2 6-5 6-8 0-4-3-6-4-10-1 3-4 4-6 7-2 3-1 8 4 11Z" /></svg>
                        </span>
                        <strong>{{ pillar.title }}</strong>
                        <p>{{ pillar.text }}</p>
                    </article>
                </div>
            </div>

            <form class="login-card" @submit.prevent="submit">
                <img class="card-logo" src="/images/brand/logo-resgate-gold.png" alt="Família Resgate" />
                <h2>Acesse sua conta</h2>
                <p>Entre para continuar sua jornada conosco</p>

                <div v-if="status" class="status-message">{{ status }}</div>

                <label class="field-group" for="email">
                    <span>E-mail</span>
                    <div class="input-shell" :class="{ invalid: form.errors.email }">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4Z" /><path d="m4 7 8 6 8-6" /></svg>
                        <input id="email" v-model="form.email" type="email" required autofocus autocomplete="username" placeholder="E-mail" aria-describedby="email-error" />
                    </div>
                    <small v-if="form.errors.email" id="email-error" class="field-error">{{ form.errors.email }}</small>
                </label>

                <label class="field-group" for="password">
                    <span>Senha</span>
                    <div class="input-shell" :class="{ invalid: form.errors.password }">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 10h12v10H6Z" /><path d="M8 10V8a4 4 0 0 1 8 0v2" /></svg>
                        <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" placeholder="Senha" aria-describedby="password-error" />
                        <button type="button" class="show-password" :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'" @click="togglePasswordVisibility">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6S2 12 2 12Z" /><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" /></svg>
                        </button>
                    </div>
                    <small v-if="form.errors.password" id="password-error" class="field-error">{{ form.errors.password }}</small>
                </label>

                <div class="form-row">
                    <label class="remember-row"><input v-model="form.remember" type="checkbox" name="remember" /><span>Lembrar de mim</span></label>
                    <Link v-if="canResetPassword" class="forgot-link" :href="route('password.request')">Esqueci minha senha</Link>
                </div>

                <button class="submit-button" type="submit" :disabled="form.processing" :class="{ processing: form.processing }">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                    Entrar na Central Família
                    <span>→</span>
                </button>

                <div class="separator"><span></span><em>ou</em><span></span></div>

                <button class="access-button" type="button" @click="openAccessRequestPanel">
                    <strong>Ainda não tenho acesso</strong>
                    <small>Quero fazer parte da Família Resgate</small>
                </button>

                <footer class="secure-footer">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 10h12v10H6Z" /><path d="M8 10V8a4 4 0 0 1 8 0v2" /></svg>
                    <span><strong>Ambiente seguro e protegido</strong><small>Seus dados estão sempre protegidos.</small></span>
                </footer>
            </form>

            <aside class="verse-panel">
                <span>“</span>
                <p>{{ loginContent.sideVerse }}</p>
                <strong>{{ loginContent.sideReference }}</strong>
            </aside>
        </section>

        <aside class="marvvium-mark" aria-label="Plataforma desenvolvida por Marvvium">
            <img src="/images/brand/logo-marvvium.png" alt="Logo Marvvium" />
            <span><strong>Marvvium</strong><small>Muito além de um sistema</small></span>
        </aside>

        <div v-if="accessPanelOpen" class="access-backdrop" role="presentation" @click.self="closeAccessRequestPanel">
            <article class="access-modal" role="dialog" aria-modal="true" aria-label="Ainda não tenho acesso">
                <button type="button" class="modal-close" aria-label="Fechar solicitação de acesso" @click="closeAccessRequestPanel">×</button>
                <p class="modal-kicker">Central Família Resgate</p>
                <h2>Ainda não tenho acesso</h2>
                <p>Fale conosco para solicitar seu acesso ou demonstrar interesse em fazer parte da Família Resgate.</p>

                <form v-if="!accessRequestSent" class="access-form" @submit.prevent="submitAccessRequest">
                    <label>Nome<input v-model="accessForm.name" type="text" required autocomplete="name" /></label>
                    <label>WhatsApp<input v-model="accessForm.whatsapp" type="tel" required autocomplete="tel" /></label>
                    <label>E-mail<input v-model="accessForm.email" type="email" autocomplete="email" /></label>
                    <label>Minha situação<select v-model="accessForm.visitorType"><option value="visitante">Sou visitante</option><option value="frequenta">Já frequento a igreja</option><option value="familia">Quero fazer parte</option><option value="oracao">Fiz um pedido de oração</option></select></label>
                    <label>Observação<textarea v-model="accessForm.notes" rows="3"></textarea></label>
                    <p class="access-note">Nesta etapa, a solicitação é apenas temporária. Futuramente será enviada para Administração Geral/Secretaria aprovar o acesso.</p>
                    <button type="submit">Solicitar acesso</button>
                </form>

                <p v-else class="success-message">Recebemos sua solicitação. Futuramente ela será encaminhada para a equipe responsável aprovar o acesso com segurança.</p>
            </article>
        </div>
    </main>
</template>

<style scoped>
.login-screen{position:relative;height:100vh;min-height:100vh;max-height:100vh;overflow:hidden;padding:24px clamp(28px,4vw,64px);color:#06152f;background:radial-gradient(circle at 18% 48%,rgba(255,241,194,.72),transparent 22%),radial-gradient(circle at 42% 86%,rgba(246,198,91,.24),transparent 28%),linear-gradient(105deg,#fff8e8 0%,#efe0c4 48%,#071a35 49%,#041022 100%)}.login-screen:before{content:'';position:absolute;inset:0;pointer-events:none;background:linear-gradient(115deg,rgba(255,255,255,.1),transparent 42%),repeating-linear-gradient(148deg,rgba(8,22,46,.05) 0,rgba(8,22,46,.05) 1px,transparent 1px,transparent 24px);opacity:.45}.login-header{position:relative;z-index:2;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:28px}.brand-mark,.public-nav a,.login-pill{color:inherit;text-decoration:none}.brand-mark{display:inline-flex;align-items:center;gap:12px;font-family:Georgia,serif}.brand-mark img{width:54px;height:54px;object-fit:contain}.brand-mark small,.brand-mark strong{display:block;line-height:1}.brand-mark small{color:#9a6a16;font-size:.74rem;font-weight:900;letter-spacing:.16em;text-transform:uppercase}.brand-mark strong{font-size:1.72rem;color:#071b3a}.public-nav{display:flex;justify-content:center;gap:clamp(18px,3vw,46px)}.public-nav a{color:#fff8e8;font-size:.92rem;font-weight:900;text-shadow:0 2px 8px rgba(4,16,34,.62)}.public-nav a:nth-child(-n+2){color:#06152f;text-shadow:0 1px 0 rgba(255,255,255,.55)}.login-pill{display:inline-flex;align-items:center;gap:8px;justify-self:end;padding:11px 18px;border:1px solid rgba(246,198,91,.42);border-radius:12px;color:#fff8e8;background:rgba(6,21,47,.36);font-weight:900}.login-pill svg,.pillar-icon svg,.input-shell svg,.secure-footer svg,.submit-button svg{fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.8}.login-pill svg{width:18px}.login-layout{position:relative;z-index:1;display:grid;grid-template-columns:minmax(340px,1fr) minmax(340px,420px) minmax(170px,240px);align-items:center;gap:clamp(22px,3.2vw,52px);height:calc(100vh - 102px);min-height:0}.welcome-panel{max-width:640px;padding-top:10px}.welcome-kicker{margin:0 0 8px;color:#9a6a16;font-size:clamp(1.15rem,2vw,1.55rem);font-weight:800}.welcome-panel h1{margin:0;font-family:Georgia,serif;font-size:clamp(3.4rem,6vw,5.5rem);line-height:.95}.welcome-text{max-width:540px;color:rgba(6,21,47,.86);font-size:1rem;line-height:1.55}.gold-divider{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:16px;width:min(340px,100%);margin:28px 0 20px;color:#b9821f}.gold-divider span{height:1px;background:linear-gradient(90deg,transparent,rgba(184,130,31,.72))}.gold-divider span:last-child{background:linear-gradient(90deg,rgba(184,130,31,.72),transparent)}blockquote{max-width:470px;margin:0;color:rgba(6,21,47,.78);font-family:Georgia,serif;font-size:1rem;font-style:italic;line-height:1.6}blockquote cite{display:block;margin-top:10px;color:#a56e15;font-style:normal;font-weight:900}.pillar-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:620px;margin-top:72px}.pillar-grid article{display:grid;justify-items:center;text-align:center;gap:7px}.pillar-icon{display:grid;width:54px;height:54px;place-items:center;color:#9a6a16}.pillar-icon svg{width:42px}.pillar-grid strong{font-size:.92rem}.pillar-grid p{margin:0;color:rgba(6,21,47,.72);font-size:.78rem;line-height:1.45}.login-card{display:grid;justify-items:stretch;gap:14px;padding:30px;border:1px solid rgba(246,198,91,.5);border-radius:24px;color:#fff8e8;background:radial-gradient(circle at 50% 0%,rgba(246,198,91,.12),transparent 36%),linear-gradient(155deg,rgba(6,21,47,.98),rgba(2,8,23,.96));box-shadow:0 32px 90px rgba(2,8,23,.42)}.card-logo{width:112px;height:112px;justify-self:center;object-fit:contain}.login-card h2{margin:0;color:#f0c46b;text-align:center;font-size:1.45rem}.login-card>p{margin:-6px 0 6px;color:rgba(255,248,232,.78);text-align:center;font-size:.9rem}.status-message{padding:11px 14px;border-radius:14px;color:#d1fae5;background:rgba(16,185,129,.14);font-size:.88rem;text-align:center}.field-group{display:grid;gap:8px}.field-group>span{font-size:.78rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.input-shell{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:10px;min-height:52px;padding:0 14px;border:1px solid rgba(255,248,232,.22);border-radius:12px;background:rgba(2,8,23,.26)}.input-shell.invalid{border-color:rgba(248,113,113,.8)}.input-shell svg{width:19px;color:rgba(255,248,232,.84)}.input-shell input{width:100%;border:0;outline:0;color:#fff8e8;background:transparent;font:inherit}.input-shell input::placeholder{color:rgba(255,248,232,.52)}.input-shell:focus-within{border-color:#f0c46b;box-shadow:0 0 0 3px rgba(240,196,107,.12)}.show-password{display:grid;width:32px;height:32px;place-items:center;border:0;border-radius:999px;color:rgba(255,248,232,.8);background:transparent;cursor:pointer}.field-error{color:#fecaca;font-size:.8rem}.form-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:2px}.remember-row{display:flex;align-items:center;gap:8px;color:rgba(255,248,232,.78);font-size:.84rem}.remember-row input{width:15px;height:15px;accent-color:#d89418}.forgot-link{color:#f0c46b;font-size:.84rem;font-weight:800;text-decoration:none}.submit-button,.access-form button{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:54px;border:0;border-radius:12px;color:#fff8e8;background:linear-gradient(135deg,#bf7d18,#d89a2c);box-shadow:0 16px 34px rgba(184,109,18,.28);font-weight:900;cursor:pointer}.submit-button svg{width:20px}.submit-button.processing{opacity:.62;cursor:wait}.separator{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:12px;color:rgba(255,248,232,.82)}.separator span{height:1px;background:rgba(255,248,232,.18)}.separator em{font-style:normal;font-size:.82rem}.access-button{display:grid;gap:3px;min-height:58px;border:1px solid rgba(240,196,107,.54);border-radius:12px;color:#fff8e8;background:rgba(2,8,23,.14);cursor:pointer}.access-button small{color:rgba(255,248,232,.74)}.secure-footer{display:flex;align-items:center;justify-content:center;gap:10px;color:rgba(255,248,232,.64);text-align:left}.secure-footer svg{width:18px}.secure-footer strong,.secure-footer small{display:block}.secure-footer small{font-size:.74rem}.verse-panel{align-self:center;max-width:220px;color:#fff8e8}.verse-panel span{color:rgba(240,196,107,.76);font-family:Georgia,serif;font-size:5rem;line-height:.7}.verse-panel p{margin:0;font-family:Georgia,serif;font-size:clamp(1.2rem,1.9vw,1.7rem);font-weight:700;line-height:1.22}.verse-panel strong{display:block;margin-top:18px;color:#d89a2c}.marvvium-mark{position:absolute;right:clamp(32px,5vw,76px);bottom:28px;z-index:2;display:flex;align-items:center;gap:12px;color:#fff8e8}.marvvium-mark img{width:62px;height:auto;object-fit:contain}.marvvium-mark strong,.marvvium-mark small{display:block}.marvvium-mark strong{color:#f0c46b;font-size:1.05rem;letter-spacing:.16em;text-transform:uppercase}.marvvium-mark small{color:rgba(255,248,232,.68);font-size:.72rem}.access-backdrop{position:fixed;inset:0;z-index:20;display:grid;place-items:center;padding:22px;background:rgba(2,8,23,.66);backdrop-filter:blur(12px)}.access-modal{position:relative;width:min(560px,100%);padding:30px;border:1px solid rgba(246,198,91,.34);border-radius:26px;color:#08162e;background:#fff8e8;box-shadow:0 30px 90px rgba(2,8,23,.4)}.modal-close{position:absolute;top:14px;right:14px;display:grid;width:38px;height:38px;place-items:center;border:0;border-radius:999px;color:#fff8e8;background:#071b3a;cursor:pointer}.modal-kicker{margin:0 0 8px;color:#b9821f;font-size:.78rem;font-weight:900;letter-spacing:.16em;text-transform:uppercase}.access-modal h2{margin:0;font-family:Georgia,serif;font-size:2rem}.access-modal>p:not(.modal-kicker):not(.success-message){color:rgba(8,22,46,.72);line-height:1.55}.access-form{display:grid;gap:12px}.access-form label{display:grid;gap:7px;font-size:.78rem;font-weight:900;letter-spacing:.06em;text-transform:uppercase}.access-form input,.access-form select,.access-form textarea{width:100%;box-sizing:border-box;border:1px solid rgba(8,22,46,.12);border-radius:14px;padding:12px 13px;color:#08162e;background:#fff;font:inherit}.access-note,.success-message{margin:0;padding:12px 14px;border-radius:16px;color:#73510e;background:rgba(246,198,91,.18);font-size:.88rem;line-height:1.5}@media(max-width:1180px){.login-screen{overflow-y:auto;background:linear-gradient(135deg,#fff8e8 0%,#efe0c4 48%,#071a35 100%)}.login-layout{grid-template-columns:1fr;gap:26px;padding:36px 0 120px}.welcome-panel,.verse-panel{max-width:none}.login-card{max-width:520px;width:100%;justify-self:center}.verse-panel{padding:24px;border-radius:22px;background:rgba(2,8,23,.34)}}@media(max-width:820px){.login-screen{padding:18px}.login-header{grid-template-columns:1fr}.public-nav{justify-content:flex-start;flex-wrap:wrap;gap:14px 22px}.login-pill{justify-self:start}.pillar-grid{grid-template-columns:1fr;margin-top:34px}.form-row{align-items:flex-start;flex-direction:column}.marvvium-mark{position:relative;right:auto;bottom:auto;margin-top:-88px}}
</style>
