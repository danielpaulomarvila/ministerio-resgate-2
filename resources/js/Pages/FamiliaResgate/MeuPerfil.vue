<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue';

const page = usePage();

const profile = {
    fullName: 'Daniel Paulo Marvila',
    status: 'Membro ativo',
    initials: 'DP',
    photo: '/images/familia-resgate/profile/daniel-paulo-marvila.svg',
    personalPhrase: 'Servo de Jesus, esposo, pai e apaixonado pela missão de alcançar vidas para Cristo.',
    memberSince: '15/02/2021',
    birthDate: '12/06/1993 (31 anos)',
    maritalStatus: 'Casado',
    phone: '(11) 98765-4321',
    email: 'daniel.marvila@resgate.com',
    address: 'São Paulo, SP',
};

// Dados temporários da Minha Caminhada.
// Futuramente serão alimentados por points, point_rules,
// point_events, attendance_records, bible_readings,
// devotionals e service_schedules.
// Os níveis devem representar crescimento espiritual,
// constância e serviço, sem incentivar competição tóxica.
const walkingSummary = {
    title: 'Minha Caminhada',
    currentLevel: 'Caminho do Discípulo',
    currentLevelNumber: 6,
    currentPoints: 1250,
    currentRangeStart: 1200,
    currentRangeEnd: 1699,
    nextLevel: 'Servo da Casa',
    pointsToNextLevel: 450,
    progressPercent: 10,
    phrase: 'Crescimento com constância',
    encouragement: 'Você está construindo uma caminhada firme, passo a passo.',
    badges: ['Presença Fiel', 'Palavra Viva', 'Servo Disponível'],
};

// A Minha Caminhada mostra nível, pontos e progresso.
// As estatísticas detalhadas ficam em Estatísticas pessoais
// para evitar duplicidade visual e manter cada bloco com função clara.

// Dados temporários do bloco Sobre mim.
// Futuramente serão alimentados por people, member_profiles
// ou profile_personal_notes.
// A edição deve respeitar permissões e pode passar por validação
// se o conteúdo for exibido publicamente dentro da Família Resgate.
const aboutProfile = {
    text: 'Amo servir, estar com minha família, adorar a Deus e ver vidas sendo transformadas pelo amor de Cristo. Minha caminhada é feita de recomeços, aprendizado, serviço e gratidão por tudo que Deus tem construído em minha casa.',
    tags: ['Fé', 'Família', 'Serviço', 'Comunhão'],
    verse: {
        text: 'Ensina-nos a contar os nossos dias, de tal maneira que alcancemos corações sábios.',
        reference: 'Salmos 90:12',
    },
};

const profileSignals = [
    ['Família', 'Marvila'],
    ['Cuidado', 'Secretaria validará dados oficiais'],
    ['Serviço', 'Louvor e jovens'],
];

// Dados temporários do Cartão Resgate.
// Futuramente serão alimentados por users, people, member_profiles,
// families, family_members, guardianships, emergency_contacts,
// departments quando permitido e permissões de acesso.
// Cada pessoa/usuário elegível deverá ter um Código Resgate único
// e um token seguro para QR Code.
// O download do cartão deverá gerar PDF/PNG com QR Code seguro,
// sem expor dados sensíveis indevidamente.
const rescueCard = {
    memberName: 'Daniel Paulo Marvila',
    displayName: 'Daniel Paulo',
    status: 'Ativo',
    bond: 'Membro ativo',
    rescueCode: 'MRES-000001',
    validUntil: '31/12/2026',
    qrCodeLabel: 'Validação segura',
    birthDate: '12/06/1993',
    maritalStatus: 'Casado',
    phone: '(11) 98765-4321',
    email: 'daniel.marvila@resgate.com',
    memberSince: '15/02/2021',
    family: 'Marvila',
    conversionDate: '20/01/2019',
    baptismDate: '15/03/2019',
    emergencyContact: {
        name: 'Ana Paula Marvila',
        relation: 'Esposa',
        phone: '(11) 9876-5432',
    },
    documentMasked: '***.456.789-**',
    registrationStatus: 'Conferido',
    lastUpdatedAt: '13/05/2026',
};

// Estrutura futura provável: rescue_cards/person_cards com rescue_code único,
// card_token único e não previsível, status, valid_until, issued_at,
// revoked_at e last_downloaded_at. O QR Code deverá usar card_token,
// nunca dados pessoais abertos nem apenas o código sequencial.
// Fluxo futuro do download: autenticar, verificar permissão, gerar PDF/PNG
// com dados permitidos e QR seguro, disponibilizar arquivo e registrar
// activity_log quando necessário.
const isRescueCardBack = ref(false);

const personalStats = [
    { icon: '♧', label: 'Presenças', value: 48, helper: 'últimos 60 dias', tone: 'communion', href: '/familia-resgate/minha-caminhada/presencas' },
    { icon: '▣', label: 'Leituras Bíblicas', value: 30, helper: 'capítulos este mês', tone: 'wisdom', href: '/familia-resgate/centro-sabedoria/leitura-biblica' },
    { icon: '◌', label: 'Devocionais', value: 22, helper: 'este mês', tone: 'fire', href: '/familia-resgate/centro-sabedoria/devocionais' },
    { icon: '♡', label: 'Serviços', value: 15, helper: 'este mês', tone: 'service', href: '/familia-resgate/escalas/meus-servicos' },
];

// Dados temporários de informações pessoais.
// Futuramente serão alimentados por people e member_profiles,
// respeitando permissões e privacidade.
// Documentos sensíveis devem ser mascarados conforme permissões.
const personalInfo = [
    { label: 'Documento', value: '123.456.789-00', icon: '▤' },
    { label: 'Profissão', value: 'Engenheiro', icon: '▧' },
    { label: 'Empresa', value: 'Tech Solutions', icon: '⌂' },
    { label: 'Escolaridade', value: 'Pós-graduação', icon: '♕' },
    { label: 'Conversão', value: '20/01/2019', icon: '✦' },
    { label: 'Batismo', value: '15/03/2019', icon: '◌' },
];

// Dados temporários de contatos de emergência.
// Futuramente serão alimentados por emergency_contacts,
// guardianships e dados familiares autorizados.
const emergencyContacts = [
    { initials: 'AP', name: 'Ana Paula Marvila', relation: 'Esposa', phone: '(11) 9876-5432', photo: '/images/familia-resgate/emergency-contacts/ana-paula-marvila.svg', primary: true },
    { initials: 'CM', name: 'Carlos Marvila', relation: 'Pai', phone: '(11) 97654-3210', photo: '/images/familia-resgate/emergency-contacts/carlos-marvila.svg', primary: false },
    { initials: 'MM', name: 'Maria Marvila', relation: 'Mãe', phone: '(11) 96543-2109', photo: '/images/familia-resgate/emergency-contacts/maria-marvila.svg', primary: false },
];

const quickActions = [
    ['Editar informações pessoais', '/familia-resgate/meu-perfil/editar'],
    ['Alterar foto de perfil', '/familia-resgate/meu-perfil/alterar-foto'],
    ['Alterar senha', '/familia-resgate/meu-perfil/alterar-senha'],
    ['Gerenciar notificações', '/familia-resgate/meu-perfil/notificacoes'],
    ['Configurar privacidade', '/familia-resgate/meu-perfil/privacidade'],
    ['Baixar meus dados', '/familia-resgate/meu-perfil/baixar-dados'],
    ['Histórico de atividades', '/familia-resgate/meu-perfil/historico-atividades'],
    ['Ajuda e suporte', '/familia-resgate/ajuda'],
];

const importantLinks = [
    ['Manual do Membro', 'Direitos e deveres', '/familia-resgate/documentos/manual-do-membro'],
    ['Política de Privacidade', 'Como seus dados são usados', '/familia-resgate/privacidade'],
    ['Código de Conduta', 'Princípios que nos guiam', '/familia-resgate/codigo-conduta'],
    ['Fale com a liderança', 'Entre em contato', '/familia-resgate/fale-com-lideranca'],
];

const avatarUrl = computed(() => page.props.auth?.user?.profile_photo_url || profile.photo);
</script>

<template>
    <Head title="Meu Perfil" />

    <main class="profile-screen">
        <FamilySidebar active-href="/familia-resgate/meu-perfil" />

        <section class="profile-main">
            <header class="profile-topbar">
                <div class="page-title">
                    <nav><Link href="/familia-resgate">Central da Família</Link><span>›</span><strong>Meu Perfil</strong></nav>
                    <h1>Meu Perfil</h1>
                    <p>Seus dados, sua história e seu vínculo com a Família Resgate.</p>
                    <small class="profile-devotional-line">✦ Identidade · cuidado · caminhada com Deus</small>
                </div>
                <article class="verse-card dark-card">
                    <span>“</span>
                    <div><strong>“Tudo posso naquele que me fortalece.”</strong><small>Filipenses 4:13</small></div>
                    <i>▣</i>
                    <Link href="/familia-resgate/meu-perfil/editar">Editar perfil</Link>
                </article>
            </header>

            <div class="profile-grid">
                <section class="identity-card glass-card">
                    <div class="avatar-frame">
                        <img v-if="avatarUrl" :src="avatarUrl" :alt="profile.fullName" />
                        <span v-else>{{ profile.initials }}</span>
                        <Link href="/familia-resgate/meu-perfil/alterar-foto" aria-label="Alterar foto de perfil">⌕</Link>
                    </div>
                    <div class="identity-copy">
                        <h2>{{ profile.fullName }} <i>✦</i></h2>
                        <mark>{{ profile.status }}</mark>
                        <p>{{ profile.personalPhrase }}</p>
                        <small class="identity-covenant">Chamado para servir com amor, constância e verdade.</small>
                        <dl>
                            <div><dt>Membro desde</dt><dd>{{ profile.memberSince }}</dd></div>
                            <div><dt>Data de nascimento</dt><dd>{{ profile.birthDate }}</dd></div>
                            <div><dt>Estado civil</dt><dd>{{ profile.maritalStatus }}</dd></div>
                            <div><dt>Telefone</dt><dd>{{ profile.phone }}</dd></div>
                            <div><dt>E-mail</dt><dd>{{ profile.email }}</dd></div>
                            <div><dt>Endereço</dt><dd>{{ profile.address }}</dd></div>
                        </dl>
                        <div class="profile-signals">
                            <span v-for="signal in profileSignals" :key="signal[0]"><b>{{ signal[0] }}</b><small>{{ signal[1] }}</small></span>
                        </div>
                    </div>
                </section>

                <section class="walking-card dark-card">
                    <header>
                        <div><h2>{{ walkingSummary.title }}</h2><small>{{ walkingSummary.phrase }}</small></div>
                        <div class="walk-top-actions">
                            <mark>Nível {{ walkingSummary.currentLevelNumber }} de 20</mark>
                            <Link href="/familia-resgate/minha-caminhada">Ver jornada <span>→</span></Link>
                        </div>
                    </header>
                    <div class="walk-main">
                        <div class="crest"><span>✝</span><i></i></div>
                        <div class="walk-data">
                            <small>Nível atual</small>
                            <strong>{{ walkingSummary.currentLevel }}</strong>
                            <b>{{ walkingSummary.currentPoints.toLocaleString('pt-BR') }} pontos de constância</b>
                            <p>{{ walkingSummary.encouragement }}</p>
                            <div class="walk-progress">
                                <div><span>{{ walkingSummary.currentPoints.toLocaleString('pt-BR') }} / {{ walkingSummary.currentRangeEnd.toLocaleString('pt-BR') }} pts</span><strong>{{ walkingSummary.progressPercent }}% deste nível</strong></div>
                                <em><i :style="{ width: walkingSummary.progressPercent + '%' }"></i></em>
                                <small>Faixa: {{ walkingSummary.currentRangeStart.toLocaleString('pt-BR') }} - {{ walkingSummary.currentRangeEnd.toLocaleString('pt-BR') }} pts · Próximo: {{ walkingSummary.nextLevel }} · faltam {{ walkingSummary.pointsToNextLevel }} pts</small>
                            </div>
                        </div>
                    </div>
                    <div class="walk-badges">
                        <span v-for="badge in walkingSummary.badges" :key="badge">✦ {{ badge }}</span>
                    </div>
                    <p class="walk-scripture">“A tua palavra é lâmpada para os meus pés.” <small>Salmos 119:105</small></p>
                </section>

                <section class="about-card glass-card small-card">
                    <header>
                        <div><h2><span>♡</span> Sobre mim</h2><small>Um pouco da minha história, fé e caminhada.</small></div>
                        <Link href="/familia-resgate/meu-perfil/sobre-mim/editar" aria-label="Editar sobre mim">✎</Link>
                    </header>
                    <article class="about-note">
                        <p>{{ aboutProfile.text }}</p>
                    </article>
                    <div class="about-tags">
                        <span v-for="tag in aboutProfile.tags" :key="tag">{{ tag }}</span>
                    </div>
                    <blockquote class="about-verse">
                        <b>“</b>
                        <p>{{ aboutProfile.verse.text }}</p>
                        <small>▣ {{ aboutProfile.verse.reference }}</small>
                    </blockquote>
                    <footer>Cada caminhada tem valor diante de Deus.</footer>
                </section>

                <section class="rescue-card glass-card small-card">
                    <header>
                        <div>
                            <h2>Meu Cartão Resgate</h2>
                            <p>Sua credencial digital da Família Resgate.</p>
                        </div>
                    </header>
                    <button type="button" class="digital-card" :class="{ back: isRescueCardBack }" @click="isRescueCardBack = !isRescueCardBack">
                        <div v-if="!isRescueCardBack" class="card-face card-front">
                            <div class="card-brand"><span>✦</span><strong>Família Resgate</strong><em>Digital</em></div>
                            <small class="card-type">Cartão Resgate Digital</small>
                            <div class="card-identity">
                                <span class="card-avatar">{{ profile.initials }}</span>
                                <h3>{{ rescueCard.memberName }}</h3>
                            </div>
                            <dl class="front-fields">
                                <div><dt>Código Resgate</dt><dd>{{ rescueCard.rescueCode }}</dd></div>
                                <div><dt>Vínculo</dt><dd>{{ rescueCard.bond }}</dd></div>
                                <div><dt>Status</dt><dd>{{ rescueCard.status }}</dd></div>
                                <div><dt>Validade</dt><dd>{{ rescueCard.validUntil }}</dd></div>
                            </dl>
                            <div class="card-qr-row">
                                <aside class="card-qr-column">
                                    <figure class="qr-mock" aria-label="QR Code visual do Cartão Resgate">
                                        <i></i><i></i><i></i><i></i><b></b><strong>QR</strong>
                                    </figure>
                                    <small>{{ rescueCard.qrCodeLabel }}</small>
                                </aside>
                            </div>
                            <p>Clique para virar.</p>
                        </div>
                        <div v-else class="card-face card-back">
                            <div class="card-brand"><span>↻</span><strong>Verso seguro</strong><em>{{ rescueCard.registrationStatus }}</em></div>
                            <dl>
                                <div><dt>Nascimento</dt><dd>{{ rescueCard.birthDate }}</dd></div>
                                <div><dt>Estado civil</dt><dd>{{ rescueCard.maritalStatus }}</dd></div>
                                <div><dt>Telefone</dt><dd>{{ rescueCard.phone }}</dd></div>
                                <div><dt>E-mail</dt><dd>{{ rescueCard.email }}</dd></div>
                                <div><dt>Membro desde</dt><dd>{{ rescueCard.memberSince }}</dd></div>
                                <div><dt>Família</dt><dd>{{ rescueCard.family }}</dd></div>
                                <div><dt>Conversão</dt><dd>{{ rescueCard.conversionDate }}</dd></div>
                                <div><dt>Batismo</dt><dd>{{ rescueCard.baptismDate }}</dd></div>
                                <div><dt>Emergência</dt><dd>{{ rescueCard.emergencyContact.name }}</dd></div>
                                <div><dt>Vínculo</dt><dd>{{ rescueCard.emergencyContact.relation }} · {{ rescueCard.emergencyContact.phone }}</dd></div>
                                <div><dt>Documento</dt><dd>{{ rescueCard.documentMasked }}</dd></div>
                                <div><dt>Atualização</dt><dd>{{ rescueCard.lastUpdatedAt }}</dd></div>
                            </dl>
                            <p>Voltar para frente.</p>
                        </div>
                    </button>
                    <div class="card-actions">
                        <Link href="/familia-resgate/meu-perfil/cartao-resgate">Ver cartão</Link>
                        <Link href="/familia-resgate/meu-perfil/cartao-resgate/download">Baixar cartão</Link>
                    </div>
                </section>

                <section class="info-card glass-card small-card">
                    <header><div><h2><span>▤</span> Informações pessoais</h2><small>Dados principais do seu cadastro.</small></div></header>
                    <dl class="info-grid">
                        <div v-for="item in personalInfo" :key="item.label">
                            <i>{{ item.icon }}</i>
                            <dt>{{ item.label }}</dt>
                            <dd>{{ item.value }}</dd>
                        </div>
                    </dl>
                    <div class="registration-status">
                        <span>▣ Cadastro conferido</span>
                        <small>Última atualização: {{ rescueCard.lastUpdatedAt }}</small>
                    </div>
                    <Link href="/familia-resgate/meu-perfil/documentos" class="info-action">Ver documentos <span>→</span></Link>
                </section>

                <section class="emergency-card glass-card small-card">
                    <header>
                        <div><h2><span>✚</span> Contatos de emergência</h2><small>Pessoas de confiança para contato.</small></div>
                        <Link href="/familia-resgate/meu-perfil/contatos-emergencia">Gerenciar</Link>
                    </header>
                    <div class="contact-list">
                        <article v-for="contact in emergencyContacts" :key="contact.name" :class="{ primary: contact.primary }">
                            <img :src="contact.photo" :alt="contact.name" />
                            <span><strong>{{ contact.name }}</strong><small><b>{{ contact.relation }}</b>{{ contact.phone }}</small></span>
                            <a :href="'tel:' + contact.phone.replace(/\\D/g, '')" aria-label="Ligar para contato de emergência">☎</a>
                        </article>
                    </div>
                    <div class="emergency-footer">
                        <small>Contato principal: Ana Paula · visível apenas para áreas autorizadas.</small>
                        <Link href="/familia-resgate/meu-perfil/contatos-emergencia/novo" class="text-action">Adicionar contato <span>＋</span></Link>
                    </div>
                </section>

                <section class="stats-card glass-card small-card">
                    <header><div><h2>Estatísticas pessoais</h2><small>Resumo da sua constância recente.</small></div></header>
                    <div class="stats-grid">
                        <Link v-for="item in personalStats" :key="item.label" :href="item.href" :class="'tone-' + item.tone">
                            <i>{{ item.icon }}</i><span>{{ item.label }}</span><strong>{{ item.value }}</strong><small>{{ item.helper }}</small>
                        </Link>
                    </div>
                </section>

                <section class="quick-card glass-card small-card">
                    <header><h2>Atalhos rápidos</h2></header>
                    <div>
                        <Link v-for="item in quickActions" :key="item[1]" :href="item[1]"><span>{{ item[0] }}</span><b>›</b></Link>
                    </div>
                </section>

                <section class="links-card glass-card small-card">
                    <header><h2>Links importantes</h2></header>
                    <div>
                        <Link v-for="item in importantLinks" :key="item[0]" :href="item[2]"><strong>{{ item[0] }}</strong><small>{{ item[1] }}</small><b>›</b></Link>
                    </div>
                </section>

                <footer class="profile-footer glass-card">
                    <span>♡ Sua vida faz parte desta história.</span>
                    <strong>Seja forte e corajoso. Tudo que fizer, faça com amor. <small>1 Coríntios 16:13-14</small></strong>
                </footer>
            </div>
        </section>
    </main>
</template>

<style scoped>
:global(body){margin:0;min-height:100vh;overflow-y:auto;background:#f7efe1;color:#0d1b2a}
*{box-sizing:border-box}
a{text-decoration:none}
.profile-screen{position:relative;min-height:100vh;overflow:visible;background:radial-gradient(circle at 82% 8%,rgba(217,164,65,.16),transparent 24%),linear-gradient(135deg,#fff8ea 0%,#f7efe1 50%,#eadabd 100%)}
.profile-main{position:relative;z-index:1;min-width:0;margin-left:80px;padding:20px 26px 30px}.profile-topbar{display:grid;grid-template-columns:minmax(0,1fr) minmax(410px,500px);gap:18px;align-items:center;margin-bottom:16px}.page-title nav{display:flex;align-items:center;gap:8px;margin-bottom:7px;color:#516070;font-size:.72rem;font-weight:800}.page-title nav a{color:#516070}.page-title nav strong{color:#0d1b2a}.page-title h1{margin:0;color:#0d1b2a;font-size:2rem;line-height:1;font-weight:950;letter-spacing:-.045em}.page-title p{margin:7px 0 0;color:#516070;font-size:.84rem;font-weight:650}.dark-card{position:relative;overflow:hidden;border-radius:22px;background:linear-gradient(135deg,#071b33,#0b2748);border:1px solid rgba(217,164,65,.24);box-shadow:0 18px 42px rgba(7,27,51,.20);color:#fff8ea}.verse-card{min-height:82px;display:grid;grid-template-columns:28px 1fr 46px auto;align-items:center;gap:12px;padding:15px 18px}.verse-card>span{color:#d9a441;font:900 2rem Georgia,serif}.verse-card strong,.verse-card small{display:block}.verse-card strong{font-size:.76rem}.verse-card small{margin-top:5px;color:#f7c76b;font-size:.66rem}.verse-card i{display:grid;place-items:center;width:46px;height:46px;border-radius:999px;border:1px solid rgba(217,164,65,.24);color:#d9a441;font-style:normal;font-size:1.35rem}.verse-card a{height:38px;display:grid;place-items:center;padding:0 16px;border-radius:13px;background:linear-gradient(145deg,#f7c76b,#d9a441);color:#071b33;font-size:.72rem;font-weight:950}.glass-card{position:relative;overflow:hidden;border-radius:22px;background:rgba(255,248,234,.76);backdrop-filter:blur(16px) saturate(140%);border:1px solid rgba(255,255,255,.68);box-shadow:0 16px 38px rgba(7,27,51,.08);transition:box-shadow 160ms ease,border-color 160ms ease,background 160ms ease}.glass-card:before,.dark-card:before{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(115deg,transparent 0 42%,rgba(255,255,255,.20) 50%,transparent 58%),linear-gradient(0deg,transparent 0 49%,rgba(217,164,65,.10) 50%,transparent 51%);opacity:.22}.glass-card:after,.dark-card:after{content:"";position:absolute;left:18px;right:18px;bottom:10px;height:1px;background:linear-gradient(90deg,transparent,rgba(217,164,65,.44),transparent);pointer-events:none}.glass-card>*{position:relative;z-index:1}.glass-card:hover,.dark-card:hover{border-color:rgba(217,164,65,.34);box-shadow:0 18px 42px rgba(7,27,51,.11)}
.profile-grid{display:grid;grid-template-columns:1.45fr .95fr 1fr 1fr;gap:16px}.identity-card{grid-column:1/3;min-height:282px;max-height:330px;display:grid;grid-template-columns:136px 1fr;gap:20px;align-items:center;padding:18px 22px}.avatar-frame{position:relative;width:126px;height:126px;border-radius:999px;border:2px solid #d9a441;background:#071b33;box-shadow:0 14px 28px rgba(7,27,51,.18),0 0 0 7px rgba(217,164,65,.10)}.avatar-frame img,.avatar-frame>span{width:100%;height:100%;border-radius:inherit}.avatar-frame img{object-fit:cover}.avatar-frame>span{display:grid;place-items:center;color:#fff8ea;font-size:2.1rem;font-weight:950}.avatar-frame a{position:absolute;right:0;bottom:8px;display:grid;place-items:center;width:32px;height:32px;border-radius:999px;background:#071b33;color:#fff8ea;border:2px solid #fff8ea;box-shadow:0 8px 18px rgba(7,27,51,.24)}.identity-copy h2{display:flex;align-items:center;gap:7px;margin:0;color:#0d1b2a;font-size:clamp(1.38rem,1.85vw,1.75rem);font-weight:950;letter-spacing:-.04em}.identity-copy h2 i{color:#d9a441;font-style:normal}.identity-copy mark{display:inline-block;margin-top:6px;padding:4px 10px;border-radius:999px;background:rgba(22,163,74,.12);color:#15803d;font-size:.64rem;font-weight:900}.identity-copy p{max-width:620px;margin:10px 0;color:#334155;font-size:.8rem;line-height:1.48}.identity-copy dl{display:grid;grid-template-columns:repeat(3,1fr);gap:9px 16px;padding-top:10px;border-top:1px solid rgba(7,27,51,.08)}.identity-copy dt{color:#516070;font-size:.6rem;font-weight:800}.identity-copy dd{margin:3px 0 0;color:#0d1b2a;font-size:.7rem;font-weight:900}.profile-signals{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:10px}.profile-signals span{min-height:48px;padding:8px 10px;border-radius:15px;background:rgba(255,255,255,.44);border:1px solid rgba(255,255,255,.72)}.profile-signals b,.profile-signals small{display:block}.profile-signals b{color:#9a6819;font-size:.56rem;text-transform:uppercase;letter-spacing:.06em}.profile-signals small{margin-top:3px;color:#0d1b2a;font-size:.63rem;font-weight:850;line-height:1.22}
.walking-card{grid-column:3/5;min-height:0;max-height:none;display:grid;grid-template-rows:auto auto auto;gap:10px;padding:16px 18px;align-self:start;background:radial-gradient(circle at 12% 18%,rgba(217,164,65,.24),transparent 24%),radial-gradient(circle at 88% 6%,rgba(96,165,250,.16),transparent 26%),linear-gradient(135deg,#06172c,#0b2748 58%,#071b33);border-color:rgba(217,164,65,.34)}.walking-card header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.walking-card h2{margin:0;font-size:1rem;letter-spacing:-.02em}.walking-card header small{display:block;margin-top:3px;color:rgba(255,248,234,.66);font-size:.64rem;font-weight:800}.walking-card mark{padding:5px 9px;border-radius:999px;background:rgba(217,164,65,.14);border:1px solid rgba(217,164,65,.28);color:#f7c76b;font-size:.58rem;font-weight:950;white-space:nowrap}.walk-top-actions{display:flex;align-items:center;justify-content:flex-end;gap:8px;flex-wrap:wrap}.walk-top-actions a{height:32px;display:flex;align-items:center;justify-content:center;gap:6px;padding:0 12px;border-radius:999px;background:rgba(217,164,65,.12);border:1px solid rgba(217,164,65,.30);color:#f7c76b;font-size:.6rem;font-weight:950;white-space:nowrap;transition:transform 160ms ease,background 160ms ease,border-color 160ms ease}.walk-top-actions a:hover{transform:translateY(-1px);background:rgba(217,164,65,.18);border-color:rgba(217,164,65,.45)}.walk-main{display:grid;grid-template-columns:86px minmax(0,1fr);gap:14px;align-items:center}.crest{position:relative;display:grid;place-items:center;width:82px;height:82px;border-radius:26px;background:linear-gradient(145deg,#071b33,#0b2748);border:1px solid rgba(217,164,65,.55);box-shadow:0 16px 28px rgba(0,0,0,.24),inset 0 0 0 5px rgba(217,164,65,.10)}.crest span{position:relative;z-index:1;display:grid;place-items:center;width:38px;height:38px;border-radius:14px;background:linear-gradient(145deg,#fff8ea,#d9a441);color:#071b33;font-size:1.35rem;font-weight:950}.crest i{position:absolute;left:15px;right:15px;bottom:15px;height:18px;border-bottom:3px solid rgba(247,199,107,.78);border-radius:50%;transform:rotate(-13deg)}.walk-data small,.walk-data strong,.walk-data b,.walk-data p{display:block}.walk-data>small{color:#f7c76b;font-size:.54rem;font-weight:900;text-transform:uppercase;letter-spacing:.08em}.walk-data>strong{margin-top:2px;color:#fff8ea;font-size:clamp(1.2rem,1.9vw,1.55rem);line-height:1;font-weight:950;letter-spacing:-.04em}.walk-data>b{margin-top:5px;color:#f7c76b;font-size:.78rem}.walk-data>p{margin:5px 0 0;max-width:520px;color:rgba(255,248,234,.72);font-size:.65rem;line-height:1.35;font-weight:750}.walk-progress{margin-top:8px}.walk-progress>div{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:5px;color:rgba(255,248,234,.78);font-size:.58rem;font-weight:850}.walk-progress>div strong{color:#f7c76b}.walk-progress em{display:block;height:8px;overflow:hidden;border-radius:999px;background:rgba(255,255,255,.10);border:1px solid rgba(217,164,65,.14)}.walk-progress em i{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#d9a441,#f7c76b,#fff1b8);box-shadow:0 0 12px rgba(217,164,65,.42)}.walk-progress small{display:block;margin-top:5px;color:rgba(255,248,234,.66);font-size:.58rem;font-weight:800}.walk-badges{display:flex;flex-wrap:wrap;gap:6px}.walk-badges span{padding:5px 8px;border-radius:999px;background:rgba(255,255,255,.07);border:1px solid rgba(217,164,65,.20);color:rgba(255,248,234,.82);font-size:.55rem;font-weight:850}.small-card{min-height:0;padding:17px 18px}.small-card header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:12px}.small-card h2{margin:0;color:#0d1b2a;font-size:.92rem;font-weight:950}.small-card h2:before{content:"";display:inline-block;width:7px;height:7px;margin-right:8px;border-radius:999px;background:#d9a441;box-shadow:0 0 0 4px rgba(217,164,65,.10)}.small-card header a{color:#9a6819;font-size:.72rem;font-weight:900}.about-card p{margin:0;color:#334155;font-size:.78rem;line-height:1.5}.about-card blockquote{margin:12px 0 0;padding:12px 13px;border-left:3px solid #d9a441;border-radius:14px;background:rgba(255,255,255,.44);color:#0d1b2a;font-size:.72rem;line-height:1.42}.about-card blockquote b{color:#d9a441;font-size:1.2rem}.about-card small{display:block;margin-top:6px;color:#9a6819;font-weight:900}.info-card dl{display:grid;grid-template-columns:1fr 1fr;gap:10px 12px;margin:0}.info-card dt{color:#64748b;font-size:.58rem;font-weight:800}.info-card dd{margin:3px 0 0;color:#0d1b2a;font-size:.7rem;font-weight:900}.info-card>a,.text-action{display:inline-flex;margin-top:13px;color:#9a6819;font-size:.72rem;font-weight:950}.contact-list{display:grid;gap:8px}.contact-list article{display:grid;grid-template-columns:34px 1fr;align-items:center;gap:9px}.contact-list img{width:34px;height:34px;border-radius:999px;object-fit:cover;border:1px solid rgba(217,164,65,.40)}.contact-list strong,.contact-list small{display:block}.contact-list strong{color:#0d1b2a;font-size:.7rem}.contact-list small{margin-top:2px;color:#64748b;font-size:.5rem;font-weight:750}.stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:8px}.stats-grid a{min-height:70px;padding:10px;border-radius:15px;background:rgba(255,255,255,.44);border:1px solid rgba(255,255,255,.62);color:#0d1b2a}.stats-grid i,.stats-grid span,.stats-grid strong,.stats-grid small{display:block}.stats-grid i{color:#9a6819;font-style:normal}.stats-grid span{margin-top:3px;color:#64748b;font-size:.58rem;font-weight:800}.stats-grid strong{font-size:1.05rem}.stats-grid small{color:#64748b;font-size:.56rem}.quick-card>div,.links-card>div{display:grid;grid-template-columns:1fr 1fr;gap:8px}.quick-card a,.links-card a{min-height:38px;display:flex;align-items:center;justify-content:space-between;gap:8px;padding:9px 10px;border-radius:14px;background:rgba(255,255,255,.42);border:1px solid rgba(255,255,255,.62);color:#0d1b2a}.quick-card span{font-size:.68rem;font-weight:900}.quick-card b,.links-card b{color:#9a6819}.links-card strong,.links-card small{display:block}.links-card strong{font-size:.68rem}.links-card small{margin-top:2px;color:#64748b;font-size:.56rem}.profile-footer{grid-column:1/-1;min-height:64px;display:flex;align-items:center;justify-content:space-between;gap:18px;padding:14px 18px;color:#0d1b2a}.profile-footer span{color:#9a6819;font-size:.78rem;font-weight:950}.profile-footer strong{font-size:.78rem}.profile-footer small{color:#9a6819}
.stats-card header{margin-bottom:12px}.stats-card header small{display:block;margin-top:4px;color:#64748b;font-size:.62rem;font-weight:750}.stats-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.stats-grid a{min-height:82px;display:grid;grid-template-columns:34px 1fr;grid-template-rows:auto auto auto;gap:2px 9px;padding:12px;border-radius:20px;background:rgba(255,255,255,.58);border:1px solid rgba(217,164,65,.16);box-shadow:0 12px 28px rgba(7,27,51,.06);color:#0d1b2a;transition:transform 160ms ease,border-color 160ms ease,box-shadow 160ms ease}.stats-grid a:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.28);box-shadow:0 16px 32px rgba(7,27,51,.09)}.stats-grid i{grid-row:1/4;display:grid;place-items:center;width:34px;height:34px;border-radius:999px;font-style:normal;font-size:.95rem}.stats-grid span,.stats-grid strong,.stats-grid small{display:block}.stats-grid span{color:#516070;font-size:.58rem;font-weight:900}.stats-grid strong{color:#071b33;font-size:1.28rem;line-height:1;font-weight:950}.stats-grid small{color:#64748b;font-size:.56rem;font-weight:750;line-height:1.2}.stats-grid .tone-communion{background:linear-gradient(135deg,rgba(11,39,72,.08),rgba(255,248,234,.72))}.stats-grid .tone-communion i{background:rgba(11,39,72,.10);color:#0b2748}.stats-grid .tone-wisdom{background:linear-gradient(135deg,rgba(217,164,65,.13),rgba(255,248,234,.76))}.stats-grid .tone-wisdom i{background:rgba(217,164,65,.16);color:#9a6819}.stats-grid .tone-fire{background:linear-gradient(135deg,rgba(245,158,11,.12),rgba(255,248,234,.76))}.stats-grid .tone-fire i{background:rgba(245,158,11,.15);color:#c2410c}.stats-grid .tone-service{background:linear-gradient(135deg,rgba(22,163,74,.10),rgba(255,248,234,.76))}.stats-grid .tone-service i{background:rgba(22,163,74,.13);color:#15803d}.rescue-card{padding:14px}.rescue-card header{margin-bottom:8px}.rescue-card header p{margin:3px 0 0;color:#64748b;font-size:.6rem;font-weight:750}.digital-card{position:relative;width:100%;min-height:0;overflow:hidden;text-align:left;border-radius:18px;padding:10px;background:linear-gradient(135deg,#071b33,#0b2748);border:1px solid rgba(217,164,65,.35);box-shadow:0 18px 40px rgba(7,27,51,.18);color:#fff8ea;cursor:pointer;transition:transform 180ms ease,box-shadow 180ms ease,border-color 180ms ease}.digital-card:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.52);box-shadow:0 20px 42px rgba(7,27,51,.22)}.digital-card:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 90% 8%,rgba(217,164,65,.22),transparent 26%),linear-gradient(115deg,transparent 0 44%,rgba(255,255,255,.07) 52%,transparent 60%);pointer-events:none}.card-face{position:relative;z-index:1;display:grid;gap:7px}.card-brand{display:grid;grid-template-columns:22px minmax(0,1fr) auto;align-items:center;gap:6px;text-transform:uppercase}.card-brand span{display:grid;place-items:center;width:22px;height:22px;border-radius:8px;background:rgba(217,164,65,.18);color:#f7c76b}.card-brand strong{min-width:0;color:#f7c76b;font-size:.64rem;line-height:1.05;font-weight:950;letter-spacing:.12em}.card-brand em,.card-qr-column em{padding:3px 7px;border-radius:999px;background:rgba(22,163,74,.16);border:1px solid rgba(187,247,208,.18);font-style:normal;font-size:.5rem;font-weight:950;color:#bbf7d0;text-transform:uppercase}.card-type{display:block;margin-top:-4px;color:rgba(255,248,234,.66);font-size:.54rem;font-weight:850;letter-spacing:.03em}.card-identity{display:grid;grid-template-columns:40px minmax(0,1fr);gap:8px;align-items:center;padding:6px 0;border-top:1px solid rgba(217,164,65,.16);border-bottom:1px solid rgba(217,164,65,.16)}.card-avatar{display:grid;place-items:center;width:40px;height:40px;border-radius:14px;background:linear-gradient(145deg,#fff8ea,#d9a441);color:#071b33;font-size:1rem;font-weight:950;box-shadow:0 8px 18px rgba(0,0,0,.2)}.card-identity h3{min-width:0;margin:0;color:#fff8ea;font-size:.78rem;line-height:1.14;font-weight:950;letter-spacing:-.025em;word-break:normal;overflow-wrap:normal;hyphens:none}.front-fields{display:grid;grid-template-columns:1fr 1fr;gap:5px 9px;margin:0}.front-fields div:first-child{grid-column:1/-1}.front-fields dt,.card-back dt{color:rgba(255,248,234,.58);font-size:.48rem;font-weight:850;text-transform:uppercase;letter-spacing:.05em}.front-fields dd,.card-back dd{margin:2px 0 0;color:#fff8ea;font-size:.58rem;font-weight:900;line-height:1.18}.front-fields div:first-child dd{color:#f7c76b;font-size:.74rem;letter-spacing:.03em}.card-qr-row{display:grid;grid-template-columns:1fr;gap:4px;align-items:center;margin-top:0;padding:6px;border-radius:15px;background:rgba(255,255,255,.065);border:1px solid rgba(217,164,65,.18)}.card-qr-row>span{color:rgba(255,248,234,.68);font-size:.5rem;font-weight:750;line-height:1.35}.card-qr-column{width:100%;display:grid;grid-template-columns:54px 1fr;align-items:center;justify-items:start;gap:8px}.card-qr-column em{display:none}.qr-mock{position:relative;width:54px;height:54px;margin:0;padding:6px;border-radius:13px;background:#fff8ea;border:3px solid rgba(217,164,65,.42);display:grid;place-items:center;color:#071b33;box-shadow:0 12px 24px rgba(0,0,0,.18)}.qr-mock i{position:absolute;width:11px;height:11px;border:2px solid #071b33}.qr-mock i:nth-child(1){left:7px;top:7px}.qr-mock i:nth-child(2){right:7px;top:7px}.qr-mock i:nth-child(3){left:7px;bottom:7px}.qr-mock i:nth-child(4){right:8px;bottom:8px;width:7px;height:7px;border-width:2px}.qr-mock b{position:absolute;width:4px;height:4px;background:#071b33;box-shadow:8px 0 #071b33,0 8px #071b33,13px 13px #071b33,16px 4px #071b33}.qr-mock strong{font-size:.58rem}.card-qr-column small{color:rgba(255,248,234,.72);font-size:.48rem;font-weight:850;text-align:center}.digital-card p{margin:0;color:rgba(255,248,234,.68);font-size:.5rem;font-weight:750}.card-back{gap:10px}.card-back dl{display:grid;grid-template-columns:1fr 1fr;gap:5px 8px;margin:0;padding-top:3px;border-top:1px solid rgba(217,164,65,.18)}.card-back dd{font-size:.52rem;overflow-wrap:anywhere}.card-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px}.card-actions a{height:30px;display:grid;place-items:center;border-radius:12px;font-size:.62rem;font-weight:950}.card-actions a:first-child{background:linear-gradient(145deg,#f7c76b,#d9a441);color:#071b33}.card-actions a:last-child{background:rgba(255,255,255,.48);border:1px solid rgba(217,164,65,.22);color:#071b33}

.about-card{background:radial-gradient(circle at 12% 6%,rgba(217,164,65,.18),transparent 28%),rgba(255,248,234,.72);border-color:rgba(217,164,65,.24);display:grid;gap:12px}.about-card header{margin-bottom:0}.about-card h2{display:flex;align-items:center;gap:7px}.about-card h2 span{display:grid;place-items:center;width:24px;height:24px;border-radius:999px;background:rgba(217,164,65,.14);color:#9a6819;font-size:.82rem}.about-card header small{display:block;margin-top:4px;color:#64748b;font-size:.62rem;font-weight:750}.about-card header a{display:grid;place-items:center;width:34px;height:34px;border-radius:999px;background:rgba(255,255,255,.46);border:1px solid rgba(217,164,65,.22);color:#9a6819;font-size:.76rem;font-weight:950;transition:transform 160ms ease,border-color 160ms ease,box-shadow 160ms ease}.about-card header a:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.42);box-shadow:0 10px 20px rgba(7,27,51,.08)}.about-note{min-height:176px;padding:16px 17px;border-radius:20px;background:rgba(255,255,255,.48);border:1px solid rgba(217,164,65,.14);border-left:4px solid rgba(217,164,65,.75);box-shadow:inset 0 1px 0 rgba(255,255,255,.54)}.about-note p{margin:0;color:#0d1b2a;font-size:.78rem;line-height:1.66;font-weight:650}.about-tags{display:flex;flex-wrap:wrap;gap:7px}.about-tags span{padding:6px 10px;border-radius:999px;background:rgba(217,164,65,.11);border:1px solid rgba(217,164,65,.18);color:#9a6819;font-size:.58rem;font-weight:900;transition:transform 160ms ease,background 160ms ease}.about-tags span:hover{transform:translateY(-1px);background:rgba(217,164,65,.16)}.about-verse{position:relative;margin:0;padding:15px 16px 14px 48px;border-radius:20px;background:linear-gradient(135deg,rgba(217,164,65,.12),rgba(255,255,255,.56));border:1px solid rgba(217,164,65,.18);border-left:4px solid rgba(217,164,65,.70);box-shadow:0 12px 28px rgba(7,27,51,.06)}.about-verse b{position:absolute;left:15px;top:8px;color:rgba(217,164,65,.52);font:900 2.2rem Georgia,serif;line-height:1}.about-verse p{margin:0;color:#0d1b2a;font-size:.72rem;line-height:1.52;font-weight:800}.about-verse small{display:block;margin-top:8px;color:#9a6819;font-size:.6rem;font-weight:950}.about-card footer{color:#64748b;font-size:.6rem;font-weight:800}.about-card{grid-column:1/2}.rescue-card{grid-column:2/3}.info-card{grid-column:3/4}.emergency-card{grid-column:4/5}.stats-card{grid-column:1/2}.quick-card{grid-column:2/4}.links-card{grid-column:4/5}
.info-card,.emergency-card{display:grid;gap:12px;background:radial-gradient(circle at 12% 8%,rgba(217,164,65,.14),transparent 27%),rgba(255,248,234,.76);border-color:rgba(217,164,65,.20)}
.info-card header,.emergency-card header{margin-bottom:0}
.info-card h2,.emergency-card h2{display:flex;align-items:center;gap:7px;font-size:1.02rem;line-height:1.08}
.info-card h2 span,.emergency-card h2 span{display:grid;place-items:center;width:24px;height:24px;border-radius:999px;background:rgba(217,164,65,.14);color:#9a6819;font-size:.8rem;flex:0 0 auto}
.info-card header small,.emergency-card header small{display:block;margin-top:5px;color:#64748b;font-size:.64rem;font-weight:750;line-height:1.25}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:0}
.info-grid div{min-width:0;display:grid;grid-template-columns:28px minmax(0,1fr);grid-template-rows:auto auto;gap:1px 8px;padding:10px;border-radius:16px;background:rgba(255,255,255,.50);border:1px solid rgba(217,164,65,.16);box-shadow:0 10px 24px rgba(7,27,51,.05);transition:transform 160ms ease,border-color 160ms ease,box-shadow 160ms ease}
.info-grid div:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.32);box-shadow:0 14px 28px rgba(7,27,51,.08)}
.info-grid i{grid-row:1/3;display:grid;place-items:center;width:28px;height:28px;border-radius:10px;background:rgba(11,39,72,.08);color:#9a6819;font-style:normal;font-size:.8rem}
.info-grid dt{color:#516070;font-size:.68rem;font-weight:900;line-height:1.1}
.info-grid dd{min-width:0;margin:2px 0 0;color:#0d1b2a;font-size:.8rem;font-weight:950;line-height:1.18;overflow-wrap:anywhere}
.registration-status{display:grid;gap:4px;padding:10px 12px;border-radius:16px;background:linear-gradient(135deg,rgba(22,163,74,.10),rgba(255,255,255,.52));border:1px solid rgba(22,163,74,.18)}
.registration-status span{color:#15803d;font-size:.7rem;font-weight:950}
.registration-status small{color:#516070;font-size:.62rem;font-weight:800}
.info-action,.emergency-card header>a,.text-action{height:34px;display:inline-flex;align-items:center;justify-content:center;gap:7px;border-radius:999px;font-size:.68rem;font-weight:950;transition:transform 160ms ease,border-color 160ms ease,background 160ms ease,box-shadow 160ms ease}
.info-action{width:100%;background:linear-gradient(145deg,#f7c76b,#d9a441);color:#071b33;box-shadow:0 12px 22px rgba(217,164,65,.18)}
.info-action:hover,.emergency-card header>a:hover,.text-action:hover{transform:translateY(-1px)}
.emergency-card header>a{flex:0 0 auto;padding:0 12px;background:rgba(217,164,65,.10);border:1px solid rgba(217,164,65,.24);color:#9a6819}
.contact-list{display:grid;gap:8px}
.contact-list article{display:grid;grid-template-columns:48px minmax(0,1fr) 32px;align-items:center;gap:10px;padding:9px;border-radius:17px;background:rgba(255,255,255,.50);border:1px solid rgba(217,164,65,.14);box-shadow:0 10px 24px rgba(7,27,51,.05);transition:transform 160ms ease,border-color 160ms ease,box-shadow 160ms ease}
.contact-list article:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.30);box-shadow:0 14px 28px rgba(7,27,51,.08)}
.contact-list article.primary{background:linear-gradient(135deg,rgba(217,164,65,.13),rgba(255,255,255,.54))}
.contact-list img{width:48px;height:48px;border-radius:16px;object-fit:cover;border:2px solid rgba(217,164,65,.42);background:#071b33}
.contact-list strong{display:block;color:#0d1b2a;font-size:.84rem;font-weight:950;line-height:1.14}
.contact-list small{display:flex;align-items:center;gap:6px;margin-top:4px;color:#516070;font-size:.68rem;font-weight:800;line-height:1.2}
.contact-list small b{padding:3px 7px;border-radius:999px;background:rgba(217,164,65,.12);color:#9a6819;font-size:.56rem}
.contact-list article>a{display:grid;place-items:center;width:32px;height:32px;border-radius:999px;background:rgba(255,248,234,.64);border:1px solid rgba(217,164,65,.24);color:#0b2748;font-size:.72rem;font-weight:950;transition:transform 160ms ease,border-color 160ms ease,background 160ms ease}
.contact-list article>a:hover{transform:translateY(-1px);background:rgba(217,164,65,.15);border-color:rgba(217,164,65,.40)}
.emergency-footer{display:grid;gap:8px}
.emergency-footer>small{padding:9px 10px;border-radius:14px;background:rgba(11,39,72,.06);color:#516070;font-size:.61rem;font-weight:800;line-height:1.35}
.text-action{width:100%;background:rgba(255,255,255,.50);border:1px solid rgba(217,164,65,.22);color:#071b33}
.text-action:hover{border-color:rgba(217,164,65,.36);background:rgba(255,255,255,.68)}
.walking-card{min-height:282px;max-height:330px;align-self:stretch;grid-template-rows:auto 1fr auto;gap:12px;padding:18px 20px}
.walking-card h2{font-size:1.08rem}
.walking-card header small{font-size:.94rem;color:rgba(255,255,255,.86);font-weight:850}
.walking-card mark{padding:7px 11px;border-color:rgba(247,199,107,.42);color:#f6c85f;font-size:.86rem;font-weight:850}
.walk-top-actions a{height:38px;padding:0 15px;border-color:rgba(247,199,107,.42);color:#f6c85f;font-size:.88rem}
.walk-main{grid-template-columns:88px minmax(0,1fr);gap:14px;align-self:center}
.walk-data>small{font-size:.82rem;color:#f6c85f;letter-spacing:.07em}
.walk-data>strong{font-size:clamp(1.36rem,1.85vw,1.68rem)}
.walk-data>b{font-size:.95rem;color:#f6c85f}
.walk-data>p{font-size:.88rem;color:rgba(255,255,255,.84);line-height:1.42}
.walk-progress{margin-top:9px}
.walk-progress>div{margin-bottom:6px;font-size:.88rem;color:rgba(255,255,255,.86)}
.walk-progress>div strong{color:#f6c85f}
.walk-progress small{font-size:.86rem;color:rgba(255,255,255,.82);line-height:1.38}
.walk-badges{gap:7px}
.walk-badges span{padding:6px 10px;border-color:rgba(247,199,107,.34);color:rgba(255,255,255,.90);font-size:.84rem;font-weight:900}
.page-title nav{font-size:.82rem}.page-title p{font-size:.92rem;color:#475569}.verse-card strong{font-size:.88rem}.verse-card small{font-size:.82rem}.verse-card a{font-size:.86rem}.identity-copy p{font-size:.9rem;color:#334155}.identity-copy dt{font-size:.8rem;color:#475569}.identity-copy dd{font-size:.86rem}.profile-signals b{font-size:.76rem}.profile-signals small{font-size:.8rem;color:#334155}.stats-card header small,.about-card header small,.info-card header small,.emergency-card header small{font-size:.82rem;color:#475569}.stats-grid span{font-size:.8rem;color:#475569}.stats-grid small{font-size:.78rem;color:#475569}.about-note p{font-size:.9rem;color:#0d1b2a}.about-tags span{font-size:.8rem}.about-verse p{font-size:.86rem}.about-verse small{font-size:.82rem}.about-card footer{font-size:.8rem;color:#475569}.card-face p,.card-face small,.card-brand em,.rescue-card header p{font-size:.82rem}.front-fields dt,.card-back dt{font-size:.78rem}.front-fields dd,.card-back dd{font-size:.86rem}.info-grid dt{font-size:.82rem}.info-grid dd{font-size:.92rem}.registration-status span{font-size:.84rem}.registration-status small{font-size:.8rem}.info-action,.emergency-card header>a,.text-action{font-size:.86rem}.contact-list strong{font-size:.96rem}.contact-list small{font-size:.84rem}.contact-list small b{font-size:.78rem}.contact-list article>a{font-size:.86rem}.emergency-footer>small{font-size:.8rem}.quick-card a,.links-card a{font-size:.86rem}.links-card small{font-size:.8rem;color:#475569}.profile-footer span{font-size:.88rem}.profile-footer strong{font-size:.95rem}.profile-footer small{font-size:.82rem}
.info-card{gap:10px;padding:16px;background:linear-gradient(145deg,rgba(255,248,234,.84),rgba(255,255,255,.58));align-content:start}.info-card h2{font-size:1rem}.info-card h2 span{width:22px;height:22px}.info-card header small{margin-top:4px;font-size:.8rem}.info-grid{grid-template-columns:1fr;gap:6px}.info-grid div{min-height:46px;grid-template-columns:28px minmax(76px,.72fr) minmax(0,1fr);grid-template-rows:1fr;align-items:center;gap:8px;padding:8px 10px;border-radius:14px;box-shadow:0 8px 18px rgba(7,27,51,.045)}.info-grid i{grid-row:auto;width:28px;height:28px;border-radius:10px}.info-grid dt{font-size:.78rem;line-height:1.1}.info-grid dd{margin:0;font-size:.84rem;line-height:1.15;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;overflow-wrap:normal}.registration-status{gap:3px;padding:9px 11px;border-radius:15px}.registration-status span{font-size:.82rem}.registration-status small{font-size:.78rem}.info-action{height:36px;font-size:.84rem}
.profile-grid{grid-template-columns:repeat(12,minmax(0,1fr));gap:18px;align-items:stretch}.identity-card{grid-column:1/7}.walking-card{grid-column:7/13}.about-card{grid-column:1/5}.rescue-card{grid-column:5/9}.info-card{grid-column:9/13}.emergency-card{grid-column:1/5}.stats-card{grid-column:5/9}.quick-card{grid-column:9/13}.links-card{grid-column:1/7}.profile-footer{grid-column:7/13}.glass-card{border-color:rgba(255,255,255,.72);box-shadow:0 14px 34px rgba(7,27,51,.075),inset 0 1px 0 rgba(255,255,255,.72)}.small-card{padding:16px}.about-card,.rescue-card,.info-card,.emergency-card,.stats-card,.quick-card,.links-card{min-height:0}.quick-card>div,.links-card>div{gap:8px}.quick-card a,.links-card a{min-height:42px;border-radius:14px}.stats-grid a{min-height:76px}.about-note{min-height:132px}.about-verse{padding-top:13px;padding-bottom:13px}.card-actions{gap:8px}.profile-footer{min-height:86px;align-items:center}
.profile-devotional-line{display:inline-flex;width:max-content;margin-top:9px;padding:7px 12px;border-radius:999px;background:rgba(255,248,234,.64);border:1px solid rgba(217,164,65,.22);color:#8a642c;font-size:.78rem;font-weight:900;letter-spacing:.02em;box-shadow:0 10px 22px rgba(217,164,65,.08),inset 0 1px 0 rgba(255,255,255,.76)}
.identity-card{position:relative;background:radial-gradient(circle at 12% 12%,rgba(217,164,65,.18),transparent 30%),linear-gradient(135deg,rgba(255,248,234,.84),rgba(255,255,255,.56));border-color:rgba(217,164,65,.22)}
.identity-card:after{content:"✦";position:absolute;right:22px;top:18px;color:rgba(217,164,65,.24);font-size:1.55rem;font-weight:950;pointer-events:none}
.avatar-frame{box-shadow:0 16px 30px rgba(7,27,51,.18),0 0 0 7px rgba(217,164,65,.10),0 0 0 13px rgba(255,248,234,.62)}
.avatar-frame:before{content:"";position:absolute;inset:-12px;border-radius:999px;background:radial-gradient(circle,rgba(217,164,65,.18),transparent 68%);pointer-events:none}
.identity-covenant{display:inline-flex;margin:-2px 0 10px;padding:6px 10px;border-radius:999px;background:rgba(217,164,65,.11);border:1px solid rgba(217,164,65,.18);color:#8a642c;font-size:.76rem;font-weight:850;line-height:1.1}
.profile-signals span{background:linear-gradient(145deg,rgba(255,255,255,.60),rgba(255,248,234,.42));border-color:rgba(217,164,65,.16);box-shadow:0 10px 22px rgba(7,27,51,.045),inset 0 1px 0 rgba(255,255,255,.70)}
.walking-card:after{content:"";position:absolute;right:-32px;top:-32px;width:150px;height:150px;border-radius:999px;background:radial-gradient(circle,rgba(247,199,107,.16),transparent 68%);pointer-events:none}
.walk-scripture{margin:0;padding:10px 12px;border-radius:16px;background:rgba(255,255,255,.075);border:1px solid rgba(247,199,107,.18);color:rgba(255,248,234,.86);font-family:Georgia,'Times New Roman',serif;font-size:.82rem;font-style:italic;line-height:1.28}.walk-scripture small{display:block;margin-top:4px;color:#f7c76b;font-family:Inter,system-ui,sans-serif;font-size:.72rem;font-style:normal;font-weight:900}
.about-card:after,.rescue-card:after,.info-card:after,.emergency-card:after,.stats-card:after,.quick-card:after,.links-card:after{content:"";position:absolute;right:-24px;top:-24px;width:86px;height:86px;border-radius:999px;background:radial-gradient(circle,rgba(217,164,65,.12),transparent 70%);pointer-events:none}
.profile-footer{position:relative;overflow:hidden;background:radial-gradient(circle at 12% 12%,rgba(217,164,65,.16),transparent 32%),linear-gradient(135deg,rgba(255,248,234,.82),rgba(255,255,255,.56))}
.profile-footer:before{content:"✦";position:absolute;right:22px;top:18px;color:rgba(217,164,65,.22);font-size:1.5rem;font-weight:950}
.profile-main{padding:24px 30px 36px}
.profile-topbar{grid-template-columns:minmax(0,1.08fr) minmax(390px,480px);gap:20px;align-items:stretch;margin-bottom:20px}
.page-title{display:flex;flex-direction:column;justify-content:center;min-height:118px;padding:6px 0}
.page-title h1{font-size:clamp(2rem,2.6vw,2.72rem)}
.page-title p{max-width:760px;line-height:1.42}
.profile-devotional-line{max-width:100%;white-space:normal}
.verse-card{min-height:118px;grid-template-columns:32px minmax(0,1fr) 48px auto;gap:14px;padding:18px 20px;border-radius:24px;background:radial-gradient(circle at 12% 10%,rgba(247,199,107,.18),transparent 34%),linear-gradient(135deg,#06172c,#0b2748 58%,#071b33);border-color:rgba(247,199,107,.36);box-shadow:0 18px 42px rgba(7,27,51,.22),inset 0 1px 0 rgba(255,255,255,.11)}
.verse-card strong{font-family:Georgia,'Times New Roman',serif;font-size:.96rem!important;line-height:1.22}
.verse-card small{font-size:.82rem!important}
.verse-card i{background:rgba(255,255,255,.055);box-shadow:inset 0 1px 0 rgba(255,255,255,.10)}
.profile-grid{gap:20px}
.identity-card,.walking-card{min-height:356px;max-height:none}
.identity-card{grid-template-columns:150px minmax(0,1fr);gap:22px;padding:24px 26px;align-items:center}
.avatar-frame{width:138px;height:138px}
.identity-copy h2{font-size:clamp(1.48rem,2vw,1.92rem);line-height:1.04}
.identity-copy p{max-width:680px;margin:11px 0 9px;line-height:1.56}
.identity-copy dl{gap:10px 18px;margin-top:2px;padding-top:12px}
.profile-signals{gap:10px;margin-top:12px}
.profile-signals span{min-height:54px;padding:10px 12px}
.walking-card{grid-template-rows:auto auto auto auto;gap:13px;padding:22px 24px}
.walking-card header{align-items:center}
.walk-main{grid-template-columns:96px minmax(0,1fr);gap:18px}
.crest{width:92px;height:92px;border-radius:28px}
.walk-data>p{max-width:620px}
.walk-badges{margin-top:0}
.walk-scripture{padding:11px 13px}
.about-card,.rescue-card,.info-card,.emergency-card,.stats-card,.quick-card,.links-card{border-color:rgba(217,164,65,.18)}
.small-card{padding:18px}
.profile-footer{padding:18px 22px;border-color:rgba(217,164,65,.20)}
@media(max-width:1280px){.profile-topbar{grid-template-columns:1fr}.profile-grid{grid-template-columns:1fr 1fr}.identity-card,.walking-card,.profile-footer{grid-column:1/-1}.about-card,.rescue-card,.info-card,.emergency-card,.stats-card,.quick-card,.links-card{grid-column:auto}.identity-card,.walking-card{max-height:none}.links-card>div{grid-template-columns:1fr}}
@media(max-width:760px){.profile-main{margin-left:0;padding:16px}.profile-grid{grid-template-columns:1fr}.identity-card{grid-template-columns:1fr;max-height:none;text-align:center}.avatar-frame{margin:auto}.identity-copy dl,.profile-signals,.info-grid,.stats-grid,.quick-card>div,.links-card>div{grid-template-columns:1fr}.card-qr-row{grid-template-columns:72px minmax(0,1fr);padding:8px}.card-qr-column{width:72px}.qr-mock{width:64px;height:64px}.card-identity{grid-template-columns:48px minmax(0,1fr)}.card-avatar{width:48px;height:48px}.card-identity h3{font-size:.92rem}.walking-card{max-height:none}.walking-card header,.walk-progress>div{display:grid}.walk-main{grid-template-columns:1fr;text-align:center}.walk-data>p{margin-left:auto;margin-right:auto}.crest{margin:auto}.verse-card{grid-template-columns:1fr;text-align:left}.profile-footer{display:grid}}
</style>
