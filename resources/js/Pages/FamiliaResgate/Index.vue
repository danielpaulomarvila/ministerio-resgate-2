<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PremiumIconButton from '@/Components/UI/PremiumIconButton.vue';
import PremiumProfileButton from '@/Components/UI/PremiumProfileButton.vue';

const props = defineProps({
    greetingName: { type: String, default: 'Daniel Paulo' },
});

const page = usePage();
const avatarOpen = ref(false);
const name = computed(() => props.greetingName || page.props.auth?.user?.name || 'Daniel Paulo');
const firstName = computed(() => name.value.split(' ')[0] || 'Daniel');

// Menu oficial do Centro da Família Resgate com rotas próprias.
const menu = [
    ['home', 'Central da Família', '/familia-resgate'],
    ['user', 'Meu Perfil', '/familia-resgate/meu-perfil'],
    ['wallet', 'Meu Financeiro', '/familia-resgate/meu-financeiro'],
    ['trophy', 'Minha Caminhada', '/familia-resgate/minha-caminhada'],
    ['book', 'Centro da Sabedoria', '/familia-resgate/centro-sabedoria'],
    ['link', 'Rede de Fé', '/familia-resgate/rede-fe'],
    ['heart', 'Orações', '/familia-resgate/oracoes'],
    ['users', 'Grupos e Ministérios', '/familia-resgate/grupos-ministerios'],
    ['calendar', 'Calendário', '/familia-resgate/calendario'],
    ['sparkle', 'Obreiros e Serviços', '/familia-resgate/obreiros-servicos'],
    ['image', 'Galeria da Família', '/familia-resgate/galeria'],
    ['list', 'Minhas Solicitações', '/familia-resgate/minhas-solicitacoes'],
    ['diamond', 'Rede de Oportunidades', '/familia-resgate/rede-oportunidades'],
    ['bell', 'Notificações', '/familia-resgate/notificacoes'],
    ['lock', 'Acessos Privados', '/familia-resgate/acessos-privados'],
    ['settings', 'Configurações', '/familia-resgate/configuracoes'],
    ['crown', 'Liderança', '/familia-resgate/lideranca'],
];

const spiritualLevels = ['Primeiros Passos', 'Coração Desperto', 'Semente Plantada', 'Raiz Profunda', 'Servo em Construção', 'Discípulo Atento', 'Chama Acesa', 'Voz de Encorajamento', 'Guardião da Comunhão', 'Mãos ao Serviço', 'Farol da Família', 'Servo Comprometido', 'Caminho Firme', 'Obreiro de Valor', 'Coluna da Casa', 'Semeador de Esperança', 'Referência de Constância', 'Multiplicador de Fé', 'Resgatador de Vidas', 'Legado Vivo'];
const highlightBadges = ['Presença Fiel', 'Servo Disponível', 'Bíblia na Mão', 'Devocional Constante', 'Intercessor', 'Coração Missionário', 'Visitante Bem-vindo', 'Mãos que Servem', 'Família Presente', 'Discípulo em Crescimento', 'Semeador', 'Chama Acesa', 'Guardião da Comunhão', 'Resgatado em Missão'];

// Dados temporários dos Destaques da Família.
// Futuramente serão alimentados pelo sistema de pontuação,
// presença, leitura bíblica, devocionais, visitantes, serviços e validação da liderança.
const familyHighlights = {
    monthlyMember: {
        name: 'Daniel Paulo',
        initials: 'DP',
        level: spiritualLevels[11],
        points: '8.450',
        top: 'Top 5%',
        phrase: 'Constância que inspira.',
        badges: highlightBadges.slice(0, 2),
        route: '/familia-resgate/minha-caminhada/destaques/mensal',
    },
    readingHighlight: {
        name: 'Marília Paulo',
        initials: 'MP',
        indicator: 'Leitura Bíblica',
        progress: 92,
        chapters: 28,
        phrase: 'Amor pela Palavra.',
        route: '/familia-resgate/centro-sabedoria/leitura-biblica/destaques/mensal',
    },
};

// Dados temporários dos aniversariantes.
// Futuramente serão alimentados pelos cadastros de pessoas,
// respeitando permissões, privacidade e vínculos familiares.
const birthdays = {
    today: {
        name: 'Marília Paulo',
        initials: 'MP',
        age: '34 anos',
        phrase: 'Que Deus abençoe sua nova estação.',
        route: '/familia-resgate/aniversariantes/hoje',
        actionRoute: '/familia-resgate/aniversariantes/hoje/enviar-carinho',
    },
    month: [
        { name: 'João Silva', initials: 'JS', date: '08/05' },
        { name: 'Ana Costa', initials: 'AC', date: '14/05' },
        { name: 'Pedro Santos', initials: 'PS', date: '22/05' },
    ],
    extraCount: 5,
    monthRoute: '/familia-resgate/aniversariantes/mes',
};

// Dados temporários das fotos recentes.
// Futuramente serão alimentados pela Galeria da Família,
// respeitando permissões de privacidade e autorização de imagem.
const recentPhotos = [
    { title: 'Culto da Família', src: '/images/familia-resgate/gallery/culto-familia.svg', route: '/familia-resgate/galeria/fotos', featured: true },
    { title: 'Comunhão dos jovens', src: '/images/familia-resgate/gallery/comunhao-jovens.svg', route: '/familia-resgate/galeria/eventos' },
    { title: 'Equipe de louvor', src: '/images/familia-resgate/gallery/louvor-equipe.svg', route: '/familia-resgate/galeria/fotos' },
    { title: 'Estudo bíblico', src: '/images/familia-resgate/gallery/estudo-biblico.svg', route: '/familia-resgate/galeria/fotos' },
    { title: 'Família em comunhão', src: '/images/familia-resgate/gallery/familia-comunhao.svg', route: '/familia-resgate/galeria/eventos' },
];

// Dados temporários dos Destaques da Semana.
// Futuramente serão alimentados por cultos, eventos,
// flyers e comunicados aprovados pela Administração Geral.
const weeklyHighlights = {
    main: {
        title: 'Culto da Família',
        time: 'Sexta • 20:00',
        place: 'Sede Central',
        image: '/images/familia-resgate/weekly-highlights/culto-familia.svg',
        route: '/familia-resgate/destaques-semana/cultos',
    },
    items: [
        { title: 'Domingo de Celebração', time: 'Domingo • 10:00', image: '/images/familia-resgate/weekly-highlights/domingo-celebracao.svg', route: '/familia-resgate/destaques-semana/cultos' },
        { title: 'Encontro dos Jovens', time: 'Sábado • 19:30', image: '/images/familia-resgate/weekly-highlights/encontro-jovens.svg', route: '/familia-resgate/destaques-semana/eventos' },
    ],
};

const familySummary = {
    members: 5,
    avatars: ['D', 'M', 'A', 'J'],
    extra: 1,
    pending: '1 atualização cadastral',
    pendingRoute: '/familia-resgate/minha-familia/solicitar-alteracao',
};

const groupSummary = {
    current: 'Resgatados · Jovens',
    ministry: 'Louvor · Equipe de Violão',
    participation: 92,
    nextMeeting: 'Sexta • 20:00',
    route: '/familia-resgate/grupos-ministerios/meus-grupos',
};

const quick = [
    ['book', 'Bíblia', '/familia-resgate/centro-sabedoria/biblia'],
    ['sun', 'Devocional', '/familia-resgate/centro-sabedoria/devocionais/hoje'],
    ['link', 'Rede da Fé', '/familia-resgate/rede-fe'],
    ['plus', 'Registrar', '/familia-resgate/cultos/registrar-biblia'],
    ['calendar', 'Calendário', '/familia-resgate/calendario'],
    ['users', 'Grupos', '/familia-resgate/grupos-ministerios/meus-grupos'],
    ['file', 'Documentos', '/familia-resgate/documentos'],
    ['bell', 'Avisos', '/familia-resgate/notificacoes'],
];

const privateLinks = [
    ['clipboard', 'Secretaria', '/acesso/secretaria'],
    ['heart', 'Pastoral', '/acesso/centro-pastoral'],
    ['wallet', 'Financeiro', '/acesso/financeiro'],
    ['store', 'Cantina', '/acesso/cantina'],
    ['diamond', 'Intercessão', '/acesso/intercessao'],
    ['shield', 'Administração', '/acesso/admin-geral'],
];


function iconPath(icon) {
    const paths = {
        home: ['M3 11.5 12 4l9 7.5', 'M5 10.5V20h14v-9.5', 'M9 20v-6h6v6'],
        user: ['M20 21a8 8 0 0 0-16 0', 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z'],
        wallet: ['M4 7h15a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h13', 'M16 13h5'],
        trophy: ['M8 4h8v3a4 4 0 0 1-8 0V4Z', 'M8 6H5a3 3 0 0 0 3 3', 'M16 6h3a3 3 0 0 1-3 3', 'M12 11v5', 'M8 20h8', 'M10 16h4'],
        book: ['M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z', 'M4 5.5v15', 'M8 7h8'],
        link: ['M10 13a5 5 0 0 0 7.1 0l2-2a5 5 0 0 0-7.1-7.1l-1.1 1.1', 'M14 11a5 5 0 0 0-7.1 0l-2 2A5 5 0 0 0 12 20.1l1.1-1.1'],
        heart: ['M20.8 5.6a5.5 5.5 0 0 0-7.8 0L12 6.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 22l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z'],
        users: ['M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2', 'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z', 'M22 21v-2a4 4 0 0 0-3-3.8', 'M16 3.2a4 4 0 0 1 0 7.6'],
        calendar: ['M7 2v4', 'M17 2v4', 'M3 9h18', 'M5 5h14a2 2 0 0 1 2 2v13H3V7a2 2 0 0 1 2-2Z'],
        sparkle: ['M12 2l1.8 5.2L19 9l-5.2 1.8L12 16l-1.8-5.2L5 9l5.2-1.8L12 2Z', 'M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15Z'],
        image: ['M4 5h16v14H4V5Z', 'M8 13l2.5-2.5L16 16', 'M14 12l2-2 4 4'],
        list: ['M8 6h13', 'M8 12h13', 'M8 18h13', 'M3 6h.01', 'M3 12h.01', 'M3 18h.01'],
        diamond: ['M12 3 21 12 12 21 3 12 12 3Z'],
        bell: ['M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9', 'M10 21h4'],
        lock: ['M7 11V8a5 5 0 0 1 10 0v3', 'M5 11h14v10H5V11Z'],
        settings: ['M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5Z', 'M19.4 15a1.8 1.8 0 0 0 .36 2l.06.06-2 3.46-.08-.02a1.8 1.8 0 0 0-1.9.74L15.8 22h-4l-.04-.08a1.8 1.8 0 0 0-1.9-.74l-.08.02-2-3.46.06-.06a1.8 1.8 0 0 0 .36-2L8.16 15 5 13v-4l3.16-2 .04-.08a1.8 1.8 0 0 0-.36-2l-.06-.06 2-3.46.08.02a1.8 1.8 0 0 0 1.9-.74L11.8 0h4l.04.08a1.8 1.8 0 0 0 1.9.74l.08-.02 2 3.46-.06.06a1.8 1.8 0 0 0-.36 2l.04.08L23 9v4l-3.6 2Z'],
        sun: ['M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z', 'M12 2v2', 'M12 20v2', 'M4.9 4.9l1.4 1.4', 'M17.7 17.7l1.4 1.4', 'M2 12h2', 'M20 12h2', 'M4.9 19.1l1.4-1.4', 'M17.7 6.3l1.4-1.4'],
        plus: ['M12 5v14', 'M5 12h14'],
        file: ['M14 2H6a2 2 0 0 0-2 2v16h16V8l-6-6Z', 'M14 2v6h6', 'M8 13h8', 'M8 17h6'],
        clipboard: ['M9 4h6', 'M8 2h8v4H8V2Z', 'M6 5H4v17h16V5h-2'],
        store: ['M4 10h16l-1-6H5l-1 6Z', 'M5 10v10h14V10', 'M9 20v-6h6v6'],
        shield: ['M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z'],
    };

    return paths[icon] ?? paths.diamond;
}

</script>

<template>
    <Head title="Centro da Família Resgate" />

    <main class="family-screen">
        <!-- Sidebar recolhível por hover: compacta fechada, completa aberta, sem alterar a grade principal. -->
        <aside class="family-sidebar" aria-label="Menu do Centro da Família Resgate">
            <Link href="/familia-resgate" class="sidebar-logo">
                <img src="/images/brand/logo-resgate-gold.png" alt="Família Resgate" />
                <span>Família Resgate<small>Casa digital</small></span>
            </Link>

            <nav>
                <Link v-for="item in menu" :key="item[2]" :href="item[2]" class="sidebar-link" :class="{ active: item[2] === '/familia-resgate' }">
                    <b><svg viewBox="0 0 24 24" aria-hidden="true"><path v-for="path in iconPath(item[0])" :key="path" :d="path" /></svg></b>
                    <span>{{ item[1] }}</span>
                </Link>
            </nav>
        </aside>

        <section class="family-main">
            <!-- Topbar apenas com ações; o título oficial fica somente no hero para evitar duplicidade visual. -->
            <header class="topbar">
                <div class="top-actions">
                    <PremiumIconButton href="/familia-resgate/busca" label="Buscar">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6" /><path d="m16 16 4 4" /></svg>
                    </PremiumIconButton>
                    <PremiumIconButton href="/familia-resgate/notificacoes" label="Notificações" badge="3">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" /><path d="M10 21h4" /></svg>
                    </PremiumIconButton>
                    <PremiumIconButton href="/familia-resgate/mensagens" label="Mensagens" badge="2">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z" /><path d="M8 9h8" /><path d="M8 13h5" /></svg>
                    </PremiumIconButton>

                    <div class="avatar-wrap">
                        <PremiumProfileButton :initial="firstName.charAt(0)" :name="firstName" @toggle="avatarOpen = !avatarOpen" />
                        <div v-if="avatarOpen" class="user-dropdown">
                            <Link href="/familia-resgate/meu-perfil">Meu perfil</Link>
                            <Link href="/familia-resgate/configuracoes">Configurações</Link>
                            <Link :href="route('logout')" method="post" as="button">Sair</Link>
                        </div>
                    </div>
                </div>
            </header>

            <div class="dashboard-grid reference-layout">
                <section class="hero-card hero-open">
                    <div class="hero-copy">
                        <p>Bem-vindo ao</p>
                        <h1>Centro da Família Resgate</h1>
                        <span>Aqui você acompanha sua jornada espiritual, sua família, seus compromissos e tudo que conecta você à vida da igreja.</span>
                    </div>
                </section>

                <Link href="/familia-resgate/centro-sabedoria/versiculo-do-dia" class="verse-card verse-reference">
                    <strong>“Tudo posso naquele que me fortalece.”</strong>
                    <small>Filipenses 4:13</small>
                </Link>

                <Link href="/familia-resgate/meu-perfil" class="profile-card profile-reference glass-card">
                    <i><img v-if="page.props.auth?.user?.profile_photo_url" :src="page.props.auth.user.profile_photo_url" :alt="name" /><span v-else>{{ firstName.charAt(0) }}</span></i>
                    <div>
                        <h2>{{ name }}</h2>
                        <p class="profile-pill-line"><span>Membro</span></p>
                        <p class="profile-meta-line"><span>Família Marvila</span></p>
                        <p class="profile-meta-line"><span>Líder de Jovens</span><span>Ministério de Louvor</span></p>
                    </div>
                </Link>

                <section class="service-card service-reference glass-card">
                    <Link href="/familia-resgate/calendario/proximo-culto"><i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v4"/><path d="M17 2v4"/><path d="M3 9h18"/><path d="M5 5h14a2 2 0 0 1 2 2v13H3V7a2 2 0 0 1 2-2Z"/></svg></i><span>Próximo Culto</span><strong>Sexta, 20:00</strong><small>Culto da Família</small></Link>
                    <Link href="/familia-resgate/calendario/proximo-culto"><i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg></i><span>Local</span><strong>Sede Central</strong><small>Auditório Principal</small></Link>
                    <Link href="/familia-resgate/minha-caminhada"><i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.5-4.6-9.2-10.1C1.4 6.4 4.7 3 8.6 5.2c1.4.8 2.2 2 3.4 3.3 1.2-1.3 2-2.5 3.4-3.3 3.9-2.2 7.2 1.2 5.8 5.7C19.5 16.4 12 21 12 21Z"/></svg></i><span>Clima Espiritual</span><strong>Firme</strong><small>Continue assim!</small></Link>
                </section>

                <Link href="/familia-resgate/cultos/confirmar-presenca" class="presence-card dark-card"><i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 12 4 4 8-8" /></svg></i><span>Confirmar<br>Presença</span></Link>

                <section class="journey-card journey-reference dark-card">
                    <div class="card-header"><h2>Minha Caminhada</h2><Link href="/familia-resgate/minha-caminhada">Ver detalhes</Link></div>
                    <div class="journey-stats">
                        <Link href="/familia-resgate/minha-caminhada/nivel"><span>Nível Atual</span><strong>28</strong><small>Servo Comprometido</small></Link>
                        <Link href="/familia-resgate/minha-caminhada/ranking"><span>Ranking Geral</span><strong>#12</strong><small>Entre 320 jovens · Top 5%</small></Link>
                    </div>
                    <div class="xp-label"><span>2.350 / 3.000 XP</span><b>78%</b></div>
                    <div class="xp-bar"><span></span></div>
                    <div class="mini-metrics">
                        <span>XP Hoje<b>+150</b></span><span>Sequência<b>12 dias</b></span><Link href="/familia-resgate/minha-caminhada/conquistas">Conquistas<b>18</b></Link><span>Pontos Totais<b>8.450</b></span>
                    </div>
                    <Link href="/familia-resgate/minha-caminhada/pontuacao" class="primary-dark-button outline-gold">Ver sistema de pontuação</Link>
                </section>

                <section class="wisdom-card wisdom-reference glass-card">
                    <div class="card-header"><h2>Centro da Sabedoria</h2><Link href="/familia-resgate/centro-sabedoria">Ver tudo</Link></div>
                    <div class="wisdom-summary">
                        <div><span>Leitura Bíblica</span><strong>João 15</strong><small>Última leitura</small></div>
                        <div><span>Plano Anual</span><strong>42%</strong><small>Progresso atual</small></div>
                    </div>
                    <div class="small-progress"><span></span></div>
                    <div class="wisdom-actions">
                        <Link href="/familia-resgate/centro-sabedoria/biblia"><b>▣</b>Ler Bíblia<small>Várias versões</small></Link>
                        <Link href="/familia-resgate/centro-sabedoria/devocionais"><b>◌</b>Devocionais<small>Guiados e diários</small></Link>
                        <Link href="/familia-resgate/centro-sabedoria/perguntas-da-leitura"><b>?</b>Perguntas<small>Sobre sua leitura</small></Link>
                        <Link href="/familia-resgate/centro-sabedoria/leitura-biblica"><b>▤</b>Leitura<small>Seu progresso</small></Link>
                    </div>
                    <Link href="/familia-resgate/centro-sabedoria/devocionais/hoje" class="devotional-card"><i>▣</i><span><b>Devocional de Hoje</b><strong>Caminhando com Jesus</strong><small>Dia 45 • O amor que permanece</small></span><em>Continuar</em></Link>
                </section>

                <section class="list-card highlights-reference glass-card">
                    <div class="highlights-titlebar">
                        <span class="highlights-emblem"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8v4a4 4 0 0 1-8 0V4Z" /><path d="M8 6H5a3 3 0 0 0 3 3" /><path d="M16 6h3a3 3 0 0 1-3 3" /><path d="M12 12v4" /><path d="M8 21h8" /><path d="M10 16h4" /></svg></span>
                        <span><h2>Destaques da Família</h2><small>Reconhecemos frutos, não vaidade.</small></span>
                        <Link href="/familia-resgate/minha-caminhada/ranking">Ver ranking <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg></Link>
                    </div>
                    <Link :href="familyHighlights.monthlyMember.route" class="featured-member-highlight">
                        <div class="featured-portrait">
                            <span class="month-ribbon">MÊS</span>
                            <i class="featured-avatar">
                                <img v-if="page.props.auth?.user?.profile_photo_url" :src="page.props.auth.user.profile_photo_url" :alt="familyHighlights.monthlyMember.name" />
                                <span v-else>{{ familyHighlights.monthlyMember.initials }}</span>
                            </i>
                            <b aria-hidden="true" class="featured-crown"><svg viewBox="0 0 24 24"><path d="M5 16 3 6l5 4 4-7 4 7 5-4-2 10H5Z" /><path d="M5 20h14" /></svg></b>
                            <em aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3 14.5 8l5.5.8-4 3.9.95 5.5L12 15.6l-4.95 2.6.95-5.5-4-3.9 5.5-.8L12 3Z" /></svg></em>
                        </div>
                        <span class="featured-copy">
                            <small><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0" /><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /></svg>Membro Destaque do Mês</small>
                            <strong>{{ familyHighlights.monthlyMember.name }}</strong>
                            <em><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" /><path d="m9 12 2 2 4-4" /></svg>{{ familyHighlights.monthlyMember.level }}</em>
                            <p><b>“</b>{{ familyHighlights.monthlyMember.phrase }}</p>
                            <span class="highlight-badges"><i v-for="badge in familyHighlights.monthlyMember.badges" :key="badge">{{ badge }}</i></span>
                        </span>
                        <span class="featured-score">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8v4a4 4 0 0 1-8 0V4Z" /><path d="M8 6H5a3 3 0 0 0 3 3" /><path d="M16 6h3a3 3 0 0 1-3 3" /><path d="M12 12v4" /><path d="M8 21h8" /><path d="M10 16h4" /></svg>
                            <strong>{{ familyHighlights.monthlyMember.points }}</strong>
                            <small>pontos</small>
                            <mark><span>TOP</span>{{ familyHighlights.monthlyMember.top.replace('Top ', '') }}</mark>
                            <b><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg></b>
                        </span>
                    </Link>
                    <Link :href="familyHighlights.readingHighlight.route" class="reading-highlight">
                        <i class="reading-avatar"><span>{{ familyHighlights.readingHighlight.initials }}</span><b><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z" /><path d="M4 5.5v15" /></svg></b></i>
                        <span class="reading-copy">
                            <small><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z" /><path d="M4 5.5v15" /><path d="M8 7h8" /></svg>Destaque em Leitura do Mês</small>
                            <strong>{{ familyHighlights.readingHighlight.name }}</strong>
                            <em><span>{{ familyHighlights.readingHighlight.indicator }}</span><b>{{ familyHighlights.readingHighlight.progress }}%</b> do plano mensal <b>{{ familyHighlights.readingHighlight.chapters }}</b> capítulos lidos</em>
                            <b><i :style="{ width: `${familyHighlights.readingHighlight.progress}%` }"></i></b>
                            <mark>{{ familyHighlights.readingHighlight.phrase }}</mark>
                        </span>
                        <span class="reading-art"><svg viewBox="0 0 64 64" aria-hidden="true"><path d="M8 18c10-4 17-1 24 5 7-6 14-9 24-5v31c-10-4-17-1-24 5-7-6-14-9-24-5V18Z" /><path d="M32 23v31" /><path d="M14 25c6-1 10 0 14 4" /><path d="M50 25c-6-1-10 0-14 4" /></svg><b><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg></b></span>
                    </Link>
                </section>

                <section class="media-weekly-reference glass-card">
                    <div class="media-weekly-title">
                        <span><h2>Fotos Recentes e Destaques da Semana</h2><small>Momentos da nossa família, cultos e comunicados em destaque.</small></span>
                    </div>
                    <div class="media-weekly-grid">
                        <article class="recent-photos-panel">
                            <div class="panel-heading">
                                <i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4V5Z" /><path d="M8 13l2.5-2.5L16 16" /><path d="M14 12l2-2 4 4" /></svg></i>
                                <span><h3>Fotos Recentes <b>Novo</b></h3><small>Novos momentos da nossa família.</small></span>
                                <Link href="/familia-resgate/galeria">Ver galeria <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg></Link>
                            </div>
                            <div class="photo-mosaic">
                                <Link v-for="photo in recentPhotos" :key="photo.title" :href="photo.route" :class="{ featured: photo.featured }">
                                    <img :src="photo.src" :alt="photo.title" />
                                    <span v-if="photo.featured">Novo</span>
                                </Link>
                            </div>
                            <p><b>12 novas fotos adicionadas</b><small>Última atualização: hoje</small></p>
                        </article>
                        <article class="weekly-panel">
                            <div class="panel-heading">
                                <i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11h4l9-5v12l-9-5H3v-2Z" /><path d="M19 9a4 4 0 0 1 0 6" /></svg></i>
                                <span><h3>Destaques da Semana <b>Esta semana</b></h3><small>Cultos, eventos e comunicados que estão em destaque.</small></span>
                                <Link href="/familia-resgate/destaques-semana">Ver destaques <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg></Link>
                            </div>
                            <div class="weekly-flyers">
                                <Link :href="weeklyHighlights.main.route" class="main-flyer">
                                    <img :src="weeklyHighlights.main.image" :alt="weeklyHighlights.main.title" />
                                    <span><b>Esta semana</b><strong>{{ weeklyHighlights.main.title }}</strong><small>{{ weeklyHighlights.main.time }} · {{ weeklyHighlights.main.place }}</small></span>
                                </Link>
                                <Link v-for="item in weeklyHighlights.items" :key="item.title" :href="item.route">
                                    <img :src="item.image" :alt="item.title" />
                                    <span><strong>{{ item.title }}</strong><small>{{ item.time }}</small></span>
                                </Link>
                            </div>
                        </article>
                    </div>
                </section>

                <div class="compact-side-stack">
                    <section class="family-card family-reference glass-card">
                        <div class="compact-card-header"><h2>Minha Família</h2><Link href="/familia-resgate/minha-familia">Ver família</Link></div>
                        <div class="family-avatars"><span v-for="avatar in familySummary.avatars" :key="avatar">{{ avatar }}</span><b>+{{ familySummary.extra }}</b></div>
                        <p><strong>{{ familySummary.members }} membros</strong></p>
                        <Link :href="familySummary.pendingRoute" class="pending-box"><i></i><span><b>Pendência</b><small>{{ familySummary.pending }}</small></span><em>›</em></Link>
                    </section>

                    <section class="small-card groups-reference glass-card">
                        <div class="compact-card-header"><h2>Grupos e Ministérios</h2><Link href="/familia-resgate/grupos-ministerios">Ver</Link></div>
                        <Link :href="groupSummary.route" class="compact-group-lines">
                            <span><small>Grupo atual</small><b>{{ groupSummary.current }}</b></span>
                            <span><small>Ministério</small><b>{{ groupSummary.ministry }}</b></span>
                            <span><small>Participação</small><b>{{ groupSummary.participation }}%</b><i><em :style="{ width: `${groupSummary.participation}%` }"></em></i></span>
                            <span><small>Próximo encontro</small><b>{{ groupSummary.nextMeeting }}</b></span>
                        </Link>
                    </section>
                </div>

                <section class="small-card birthday-card birthday-reference glass-card">
                    <div class="birthday-header">
                        <i><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12v9H4v-9" /><path d="M2 7h20v5H2V7Z" /><path d="M12 7v14" /><path d="M12 7C9 7 7 5.8 7 4.2 7 3.1 7.8 2.4 8.8 2.4 10.5 2.4 12 5 12 7Z" /><path d="M12 7c3 0 5-1.2 5-2.8 0-1.1-.8-1.8-1.8-1.8C13.5 2.4 12 5 12 7Z" /></svg></i>
                        <span><h2>Aniversariantes da Família</h2><small>Celebrando vidas da nossa família.</small></span>
                        <Link href="/familia-resgate/aniversariantes">Ver mês <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg></Link>
                    </div>
                    <Link :href="birthdays.today.route" class="birthday-featured">
                        <i class="birthday-avatar"><span>{{ birthdays.today.initials }}</span><b><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11h16v10H4V11Z" /><path d="M8 11V8a4 4 0 0 1 8 0v3" /><path d="M8 17h8" /></svg></b></i>
                        <span class="birthday-copy">
                            <small><b>HOJE</b>Aniversariante do dia</small>
                            <strong>{{ birthdays.today.name }}</strong>
                            <em>{{ birthdays.today.age }}</em>
                            <p>{{ birthdays.today.phrase }}</p>
                        </span>
                    </Link>
                    <div class="birthday-actions">
                        <Link :href="birthdays.today.actionRoute" class="birthday-button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 5.6a5.5 5.5 0 0 0-7.8 0L12 6.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 22l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z" /></svg>Enviar carinho</Link>
                    </div>
                    <Link :href="birthdays.monthRoute" class="birthday-month">
                        <strong><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v4" /><path d="M17 2v4" /><path d="M3 9h18" /><path d="M5 5h14a2 2 0 0 1 2 2v13H3V7a2 2 0 0 1 2-2Z" /></svg>Aniversariantes do mês</strong>
                        <span v-for="person in birthdays.month" :key="person.name"><i>{{ person.initials }}</i><b>{{ person.name }}</b><small>{{ person.date }}</small></span>
                        <em>+{{ birthdays.extraCount }} neste mês</em>
                    </Link>
                </section>
            </div>

            <footer class="access-footer">
                <section>
                    <h2>Acessos Rápidos</h2>
                    <div>
                        <Link v-for="item in quick" :key="item[2]" :href="item[2]"><b><svg viewBox="0 0 24 24" aria-hidden="true"><path v-for="path in iconPath(item[0])" :key="path" :d="path" /></svg></b><span>{{ item[1] }}</span></Link>
                    </div>
                </section>
                <section>
                    <h2>Acessos Privados <small>Apenas áreas liberadas</small></h2>
                    <div>
                        <Link v-for="item in privateLinks" :key="item[2]" :href="item[2]"><b><svg viewBox="0 0 24 24" aria-hidden="true"><path v-for="path in iconPath(item[0])" :key="path" :d="path" /></svg></b><span>{{ item[1] }}</span></Link>
                    </div>
                </section>
            </footer>
        </section>
    </main>
</template>

<style scoped>
:global(html){scroll-behavior:smooth}
:global(body){min-height:100vh;overflow-y:auto;background:#f7efe1}
:global(body::-webkit-scrollbar){width:8px}
:global(body::-webkit-scrollbar-track){background:rgba(7,27,51,.05)}
:global(body::-webkit-scrollbar-thumb){background:linear-gradient(180deg,rgba(217,164,65,.55),rgba(7,27,51,.30));border-radius:999px;border:2px solid rgba(255,248,234,.82)}
*{box-sizing:border-box}
.family-screen{position:relative;min-height:100vh;overflow:visible;color:#071b33;background:radial-gradient(circle at 78% 10%,rgba(245,158,11,.20),transparent 26%),radial-gradient(circle at 13% 18%,rgba(7,27,51,.12),transparent 30%),radial-gradient(circle at 54% 80%,rgba(217,164,65,.13),transparent 35%),linear-gradient(135deg,#fff8ea 0%,#f7efe1 44%,#eadabd 100%)}
.family-screen:before{content:"";position:fixed;inset:-18%;background:radial-gradient(circle at 72% 18%,rgba(255,214,120,.28),transparent 20%),radial-gradient(circle at 18% 70%,rgba(7,27,51,.09),transparent 22%),conic-gradient(from 22deg at 50% 18%,transparent 0 20deg,rgba(217,164,65,.08) 20deg 22deg,transparent 22deg 90deg,rgba(255,255,255,.12) 90deg 92deg,transparent 92deg 360deg);filter:blur(26px);opacity:.86;pointer-events:none}
.family-screen:after{content:"";position:fixed;inset:0;background:linear-gradient(115deg,transparent 0 35%,rgba(255,255,255,.26) 45%,transparent 55%),repeating-linear-gradient(90deg,rgba(7,27,51,.018) 0 1px,transparent 1px 72px),repeating-linear-gradient(0deg,rgba(217,164,65,.018) 0 1px,transparent 1px 88px),radial-gradient(circle at 50% 12%,transparent 0 112px,rgba(217,164,65,.045) 113px 114px,transparent 115px),radial-gradient(circle at 50% 12%,transparent 0 198px,rgba(7,27,51,.035) 199px 200px,transparent 201px);opacity:.66;pointer-events:none}
.family-sidebar{position:fixed;inset:0 auto 0 0;z-index:20;width:80px;height:100vh;overflow:hidden;background:linear-gradient(180deg,rgba(4,17,32,.99),rgba(7,27,51,.98) 55%,rgba(5,18,34,.99));border-right:1px solid rgba(247,199,107,.55);box-shadow:20px 0 58px rgba(7,27,51,.30),inset -1px 0 0 rgba(255,255,255,.08);transition:width .28s cubic-bezier(.2,.8,.2,1)}
.family-sidebar:hover{width:276px}
.family-sidebar:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 50% 0,rgba(247,199,107,.32),transparent 25%),linear-gradient(90deg,rgba(255,255,255,.07),transparent),repeating-linear-gradient(180deg,transparent 0 64px,rgba(247,199,107,.06) 64px 65px);pointer-events:none}
.sidebar-logo,.sidebar-link{position:relative;display:flex;align-items:center;color:#fff8ea;text-decoration:none}
.sidebar-logo{height:68px;gap:13px;padding:9px 16px}
.sidebar-logo img{width:46px;height:46px;object-fit:contain;filter:drop-shadow(0 0 16px rgba(247,199,107,.62))}
.sidebar-logo span,.sidebar-link span{opacity:0;white-space:nowrap;transition:opacity .18s ease}
.family-sidebar:hover span{opacity:1}
.sidebar-logo small{display:block;color:#f7c76b;font-size:.67rem}
.family-sidebar nav{display:grid;gap:5px;padding:9px 10px 16px}
.sidebar-link{height:42px;gap:13px;padding:0 13px;border-radius:15px;border:1px solid transparent;color:rgba(255,248,234,.78);font-size:.82rem;font-weight:750}
.sidebar-link b{display:inline-flex;align-items:center;justify-content:center;width:32px;min-width:32px;height:32px;border-radius:12px;color:#f7c76b;background:rgba(255,255,255,.05)}
.sidebar-link b svg,.access-footer a b svg{width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;filter:drop-shadow(0 2px 6px rgba(217,164,65,.22))}
.sidebar-link:hover,.sidebar-link.active{color:#fff8ea;background:rgba(255,255,255,.09);border-color:rgba(247,199,107,.28)}
.sidebar-link:hover b,.sidebar-link.active b{background:rgba(217,164,65,.16);color:#ffd777}
.family-main{position:relative;z-index:1;margin-left:80px;min-height:100vh;padding:22px 26px 38px;display:grid;grid-template-rows:44px auto auto;gap:18px}
.family-main:before{content:"";position:fixed;right:22px;top:82px;width:360px;height:360px;border-radius:999px;background:radial-gradient(circle,rgba(255,255,255,.24),transparent 58%);border:1px solid rgba(217,164,65,.08);box-shadow:0 0 0 42px rgba(217,164,65,.025),0 0 0 84px rgba(7,27,51,.018);opacity:.72;pointer-events:none}
.topbar{display:flex;justify-content:flex-end;align-items:center;min-height:44px}
.top-actions{display:flex;align-items:center;gap:10px}
.avatar-wrap{position:relative}
.user-dropdown{position:absolute;right:0;top:50px;z-index:30;min-width:176px;padding:8px;border-radius:18px;background:rgba(7,27,51,.94);border:1px solid rgba(247,199,107,.24);box-shadow:0 22px 48px rgba(7,27,51,.25);backdrop-filter:blur(18px)}
.user-dropdown a,.user-dropdown button{display:block;width:100%;padding:10px 12px;border:0;background:transparent;color:#fff8ea;text-align:left;text-decoration:none;border-radius:12px;font-size:.82rem;font-weight:700;cursor:pointer}
.user-dropdown a:hover,.user-dropdown button:hover{background:rgba(247,199,107,.12);color:#f7c76b}
.dashboard-grid{position:relative;display:grid;grid-template-columns:minmax(260px,1fr) minmax(300px,1.08fr) minmax(260px,.94fr) minmax(235px,.82fr);grid-template-rows:150px 110px minmax(330px,auto) minmax(230px,auto);gap:18px;align-items:stretch;isolation:isolate}
.dashboard-grid:before{content:"";position:absolute;inset:-12px;z-index:0;border-radius:34px;background:linear-gradient(90deg,transparent 0 31%,rgba(217,164,65,.06) 31.08% 31.18%,transparent 31.26% 65%,rgba(7,27,51,.035) 65.08% 65.18%,transparent 65.26%),linear-gradient(180deg,transparent 0 39%,rgba(217,164,65,.06) 39.08% 39.18%,transparent 39.26% 74%,rgba(7,27,51,.03) 74.08% 74.18%,transparent 74.26%),radial-gradient(circle at 87% 8%,rgba(247,199,107,.14),transparent 20%);pointer-events:none}
.dashboard-grid>*{position:relative;z-index:1}
.glass-card,.verse-card{position:relative;overflow:hidden;border-radius:24px;background:linear-gradient(135deg,rgba(255,251,242,.80),rgba(255,248,234,.60) 50%,rgba(238,223,197,.38));border:1px solid rgba(255,255,255,.74);box-shadow:0 16px 38px rgba(7,27,51,.08),0 0 0 1px rgba(217,164,65,.055),inset 0 1px 0 rgba(255,255,255,.86),inset 0 -1px 0 rgba(217,164,65,.08);backdrop-filter:blur(20px) saturate(150%);transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease,background .22s ease}
.glass-card:before{content:"";position:absolute;inset:1px;border-radius:inherit;background:radial-gradient(circle at 16% 12%,rgba(255,255,255,.66),transparent 34%),radial-gradient(circle at 96% 92%,rgba(217,164,65,.12),transparent 34%),linear-gradient(135deg,rgba(255,255,255,.24),transparent 34%,rgba(217,164,65,.035));pointer-events:none}
.glass-card>*{position:relative;z-index:1}
.dark-card{position:relative;overflow:hidden;border-radius:24px;background:radial-gradient(circle at 14% 10%,rgba(247,199,107,.16),transparent 34%),linear-gradient(135deg,#06172c 0%,#08223f 52%,#103557 100%);border:1px solid rgba(247,199,107,.38);box-shadow:0 20px 46px rgba(7,27,51,.25),0 0 0 1px rgba(247,199,107,.08),inset 0 1px 0 rgba(255,255,255,.13);color:#fff8ea}
.dark-card:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 18% 12%,rgba(247,199,107,.18),transparent 32%),linear-gradient(115deg,rgba(255,255,255,.10),transparent 42%),repeating-linear-gradient(135deg,transparent 0 42px,rgba(255,255,255,.025) 42px 43px);pointer-events:none}
a.glass-card:hover,.glass-card:hover,.dark-card:hover,.verse-card:hover{transform:translateY(-2px);border-color:rgba(217,164,65,.34);box-shadow:0 22px 50px rgba(7,27,51,.12),inset 0 1px 0 rgba(255,255,255,.88)}
.hero-open{grid-column:1/3;grid-row:1/2;display:flex;align-items:center;padding:2px 4px;background:transparent;border:0;box-shadow:none;backdrop-filter:none}
.hero-copy p{margin:0 0 4px;color:#8a642c;font-size:.86rem;font-weight:800;letter-spacing:.02em;text-transform:uppercase}
.hero-copy h1{margin:0;color:#071b33;font-size:clamp(40px,3.8vw,56px);line-height:.95;letter-spacing:-.058em;text-wrap:balance}
.hero-copy h1:after{content:"";display:block;width:132px;height:3px;margin-top:14px;border-radius:999px;background:linear-gradient(90deg,#d9a441,rgba(217,164,65,.18),transparent);box-shadow:0 6px 16px rgba(217,164,65,.18)}
.hero-copy span{display:block;max-width:720px;margin-top:10px;color:rgba(7,27,51,.72);font-size:.88rem;font-weight:650;line-height:1.4}
.verse-reference{grid-column:3/5;grid-row:1/2;isolation:isolate;display:grid;align-content:center;padding:28px 36px;text-decoration:none;background-image:linear-gradient(90deg,rgba(255,248,234,.95) 0%,rgba(255,248,234,.78) 40%,rgba(255,248,234,.22) 100%),url('/images/familia-resgate/verse-cross-mountain.jpg');background-size:cover;background-position:center right;background-repeat:no-repeat;color:#071b33}
.verse-reference:before{content:"";position:absolute;inset:0;z-index:0;background:radial-gradient(circle at 78% 28%,rgba(245,158,11,.18),transparent 24%),linear-gradient(90deg,rgba(255,248,234,.18),transparent 58%);pointer-events:none}
.verse-reference strong,.verse-reference small{position:relative;z-index:1}
.verse-reference strong{max-width:62%;font-family:Georgia,'Times New Roman',serif;font-size:clamp(18px,1.4vw,22px);font-weight:600;font-style:italic;line-height:1.28;text-shadow:0 1px 0 rgba(255,255,255,.45)}
.verse-reference strong:before{content:'“';display:block;margin-bottom:4px;color:#d9a441;font-size:24px;line-height:.8;font-style:normal}
.verse-reference small{margin-top:10px;color:#7a4f18;font-weight:800;font-size:.76rem}
.profile-reference{grid-column:1/2;grid-row:2/3;display:flex;align-items:center;gap:16px;padding:14px 20px;text-decoration:none;color:#071b33;border-radius:19px}
.profile-reference i{display:flex;align-items:center;justify-content:center;width:74px;height:74px;min-width:74px;border-radius:999px;border:2px solid #d9a441;background:radial-gradient(circle at 34% 22%,#16456f 0%,#071b33 68%);color:#f7c76b;font-style:normal;box-shadow:0 10px 22px rgba(7,27,51,.16),0 0 0 4px rgba(217,164,65,.095),inset 0 1px 0 rgba(255,255,255,.18)}
.profile-reference i img{width:100%;height:100%;border-radius:inherit;object-fit:cover}
.profile-reference i span{font-size:23px;font-weight:900;text-shadow:0 2px 8px rgba(0,0,0,.24)}
.profile-reference h2{margin:0 0 7px;color:#071b33;font-size:18px;font-weight:850;line-height:1.05;letter-spacing:-.025em}
.profile-reference p{display:flex;align-items:center;gap:8px;margin:0;line-height:1.15}
.profile-pill-line span{padding:3px 9px;border-radius:999px;background:rgba(247,199,107,.18);border:1px solid rgba(217,164,65,.26);color:#071b33;font-size:10px;font-weight:850;box-shadow:inset 0 1px 0 rgba(255,255,255,.68)}
.profile-meta-line{margin-top:5px!important}
.profile-meta-line span{position:relative;color:rgba(7,27,51,.72);font-size:11px;font-weight:700}
.profile-meta-line span+span{padding-left:14px}
.profile-meta-line span+span:before{content:"";position:absolute;left:4px;top:50%;width:4px;height:4px;border-radius:50%;background:rgba(217,164,65,.70);transform:translateY(-50%)}
.service-reference{grid-column:2/4;grid-row:2/3;display:grid;grid-template-columns:repeat(3,1fr);align-items:center;padding:0 14px;border-radius:19px}
.service-reference a{position:relative;display:grid;grid-template-columns:38px 1fr;grid-template-rows:auto auto auto;align-content:center;align-items:center;height:100%;padding:0 17px;column-gap:11px;text-decoration:none;color:#071b33;transition:transform .18s ease}
.service-reference a:hover{transform:translateY(-1px)}
.service-reference a:not(:last-child):after{content:"";position:absolute;right:0;top:21px;bottom:21px;width:1px;background:linear-gradient(180deg,transparent,rgba(217,164,65,.22),rgba(7,27,51,.08),transparent)}
.service-reference i{grid-row:1/4;display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:999px;background:linear-gradient(145deg,rgba(255,248,234,.88),rgba(247,199,107,.22));border:1px solid rgba(217,164,65,.22);box-shadow:0 7px 16px rgba(217,164,65,.10),0 0 0 4px rgba(217,164,65,.045),inset 0 1px 0 rgba(255,255,255,.78);transition:box-shadow .18s ease,transform .18s ease}
.service-reference a:hover i{transform:scale(1.03);box-shadow:0 9px 18px rgba(217,164,65,.16),0 0 0 5px rgba(217,164,65,.07),inset 0 1px 0 rgba(255,255,255,.84)}
.service-reference svg{width:17px;height:17px;fill:none;stroke:#071b33;stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round}
.service-reference a:nth-child(3) svg{stroke:#d08a1e}
.service-reference span{font-size:10px;color:rgba(7,27,51,.54);font-weight:750;line-height:1.1}
.service-reference strong{margin-top:2px;color:#071b33;font-size:14px;font-weight:850;line-height:1.1;letter-spacing:-.01em}
.service-reference small{margin-top:3px;color:rgba(7,27,51,.66);font-size:10px;line-height:1.1}
.presence-card{grid-column:4/5;grid-row:2/3;display:flex;align-items:center;justify-content:center;gap:14px;width:100%;height:100%;padding:0 20px;text-decoration:none;border-radius:20px}
.presence-card i{position:relative;z-index:1;display:flex;align-items:center;justify-content:center;width:42px;height:42px;min-width:42px;border-radius:999px;border:1px solid rgba(247,199,107,.78);background:linear-gradient(145deg,rgba(247,199,107,.16),rgba(7,27,51,.28));box-shadow:0 8px 20px rgba(0,0,0,.20),0 0 0 4px rgba(247,199,107,.055),inset 0 1px 0 rgba(255,255,255,.14)}
.presence-card svg{width:19px;height:19px;fill:none;stroke:#f7c76b;stroke-width:2.25;stroke-linecap:round;stroke-linejoin:round}
.presence-card span{position:relative;z-index:1;color:#fff8ea;font-size:17px;font-weight:850;line-height:1.04;text-shadow:0 2px 12px rgba(0,0,0,.22)}
.card-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px}
.card-header h2{display:flex;align-items:center;gap:8px;margin:0;color:inherit;font-size:1rem;font-weight:850;letter-spacing:-.025em;line-height:1.15}
.card-header h2:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(145deg,#d9a441,#f7c76b);box-shadow:0 0 0 4px rgba(217,164,65,.10)}
.card-header a{padding:6px 8px;border-radius:999px;color:#b17818;text-decoration:none;font-size:.72rem;font-weight:850;white-space:nowrap;transition:background .18s ease,color .18s ease,transform .18s ease}
.card-header a:hover{transform:translateX(2px);background:rgba(217,164,65,.12);color:#071b33}
.dark-card .card-header a{color:#f7c76b}
.journey-reference{grid-column:1/2;grid-row:3/4;padding:22px;display:flex;flex-direction:column;gap:13px}
.journey-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.journey-stats a{padding:14px;border-radius:18px;background:rgba(255,255,255,.075);border:1px solid rgba(255,255,255,.11);text-decoration:none;color:#fff8ea}
.journey-stats span,.xp-label span,.mini-metrics span,.mini-metrics a{color:rgba(255,248,234,.68);font-size:.68rem;font-weight:750;text-decoration:none}
.journey-stats strong{display:block;margin:4px 0 2px;color:#f7c76b;font-size:1.45rem;font-weight:900;line-height:1}
.journey-stats small{color:rgba(255,248,234,.76);font-size:.66rem;font-weight:650}
.xp-label{display:flex;align-items:center;justify-content:space-between;margin-top:2px;color:#fff8ea;font-size:.75rem;font-weight:800}
.xp-label b{color:#f7c76b}
.xp-bar,.small-progress{height:9px;border-radius:999px;background:rgba(7,27,51,.10);overflow:hidden}
.xp-bar{background:rgba(255,255,255,.12)}
.xp-bar span,.small-progress span{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#d9a441,#f7c76b)}
.xp-bar span{width:78%}
.small-progress span{width:42%}
.mini-metrics{display:grid;grid-template-columns:repeat(2,1fr);gap:8px}
.mini-metrics span,.mini-metrics a{display:flex;align-items:center;justify-content:space-between;padding:10px 11px;border-radius:14px;background:rgba(255,255,255,.065);border:1px solid rgba(255,255,255,.08)}
.mini-metrics b{color:#fff8ea;font-size:.78rem}
.primary-dark-button{display:flex;align-items:center;justify-content:center;text-decoration:none;font-weight:850;border-radius:16px;transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease}
.primary-dark-button{height:42px;margin-top:auto;color:#f7c76b;border:1px solid rgba(217,164,65,.56);background:rgba(217,164,65,.08);font-size:.78rem}
.primary-dark-button:hover{transform:translateY(-1px);border-color:rgba(247,199,107,.62)}
.wisdom-reference{grid-column:2/3;grid-row:3/4;padding:22px;display:flex;flex-direction:column;gap:12px}
.wisdom-summary{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.wisdom-summary div{padding:13px;border-radius:18px;background:rgba(255,255,255,.48);border:1px solid rgba(255,255,255,.62)}
.wisdom-summary span,.wisdom-summary small{display:block;color:rgba(7,27,51,.56);font-size:.68rem;font-weight:750}
.wisdom-summary strong{display:block;margin:4px 0;color:#071b33;font-size:1.16rem;font-weight:900}
.wisdom-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.wisdom-actions a{display:grid;grid-template-columns:30px 1fr;align-items:center;min-height:58px;padding:10px;border-radius:17px;background:rgba(255,255,255,.46);border:1px solid rgba(255,255,255,.62);color:#071b33;text-decoration:none;font-size:.78rem;font-weight:850;line-height:1.05}
.wisdom-actions b{grid-row:1/3;display:flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:10px;background:rgba(217,164,65,.16);color:#b17818;font-size:.8rem}
.wisdom-actions small{display:block;margin-top:4px;color:rgba(7,27,51,.58);font-size:.62rem;font-weight:700}
.devotional-card{display:grid;grid-template-columns:44px 1fr auto;align-items:center;gap:12px;margin-top:auto;padding:13px;border-radius:20px;background:linear-gradient(135deg,rgba(7,27,51,.94),rgba(14,49,82,.92));border:1px solid rgba(217,164,65,.28);color:#fff8ea;text-decoration:none;box-shadow:0 14px 28px rgba(7,27,51,.16)}
.devotional-card i{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:14px;background:rgba(247,199,107,.16);color:#f7c76b;font-style:normal}
.devotional-card b,.devotional-card strong,.devotional-card small{display:block}
.devotional-card b{font-size:.7rem;color:#f7c76b}
.devotional-card strong{margin-top:2px;font-size:.86rem;color:#fff8ea}
.devotional-card small{margin-top:3px;color:rgba(255,248,234,.70);font-size:.66rem}
.devotional-card em{padding:8px 10px;border-radius:999px;background:rgba(247,199,107,.16);color:#f7c76b;font-style:normal;font-size:.66rem;font-weight:850}
.list-card{padding:22px;display:flex;flex-direction:column;gap:11px}
.highlights-reference{grid-column:3/5;grid-row:3/4;padding:16px 18px 18px;display:grid;grid-template-rows:auto minmax(0,1fr) auto;gap:10px;background:linear-gradient(135deg,rgba(255,248,234,.82),rgba(255,255,255,.58));border-color:rgba(255,255,255,.72);box-shadow:0 18px 42px rgba(7,27,51,.09),inset 0 1px 0 rgba(255,255,255,.86)}
.highlights-titlebar{display:grid;grid-template-columns:42px 1fr auto;align-items:center;gap:12px;padding:0 2px}
.highlights-emblem{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:15px;background:linear-gradient(145deg,rgba(247,199,107,.25),rgba(255,248,234,.72));border:1px solid rgba(217,164,65,.22);color:#d9a441;box-shadow:inset 0 1px 0 rgba(255,255,255,.80)}
.highlights-emblem svg,.highlights-titlebar a svg,.featured-crown svg,.featured-portrait em svg,.featured-copy svg,.featured-score svg,.featured-score b svg,.reading-avatar svg,.reading-copy svg,.reading-art svg{fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.highlights-emblem svg{width:22px;height:22px;filter:drop-shadow(0 3px 8px rgba(217,164,65,.24))}
.highlights-titlebar h2{margin:0;color:#071b33;font-size:1.22rem;font-weight:950;letter-spacing:-.045em;line-height:1}
.highlights-titlebar small{display:block;margin-top:4px;color:rgba(7,27,51,.62);font-size:.74rem;font-weight:750}
.highlights-titlebar a{display:flex;align-items:center;gap:7px;color:#9a6819;text-decoration:none;font-size:.78rem;font-weight:900;white-space:nowrap}
.highlights-titlebar a svg{width:16px;height:16px;color:#d9a441}
.featured-member-highlight,.reading-highlight{position:relative;overflow:hidden;display:grid;text-decoration:none;color:#071b33;border-radius:22px;border:1px solid rgba(255,255,255,.68);transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease}
.featured-member-highlight{grid-template-columns:160px minmax(0,1fr) 136px;align-items:center;gap:18px;min-height:178px;padding:16px 18px;background:linear-gradient(135deg,rgba(255,248,234,.68),rgba(255,255,255,.44));box-shadow:inset 5px 0 0 rgba(217,164,65,.70),inset 0 1px 0 rgba(255,255,255,.82)}
.featured-member-highlight:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 18% 10%,rgba(217,164,65,.18),transparent 28%),radial-gradient(circle at 96% 85%,rgba(247,199,107,.12),transparent 34%);pointer-events:none}
.featured-portrait,.featured-copy,.featured-score,.reading-avatar,.reading-copy,.reading-art{position:relative;z-index:1}
.featured-portrait{display:flex;align-items:center;justify-content:center;height:146px}
.featured-avatar{display:flex;align-items:center;justify-content:center;width:132px;height:132px;border-radius:999px;border:4px solid rgba(217,164,65,.86);background:radial-gradient(circle at 35% 25%,#d7dde2,#66727e 58%,#071b33 100%);box-shadow:0 14px 26px rgba(7,27,51,.18),0 0 0 6px rgba(247,199,107,.12),inset 0 1px 0 rgba(255,255,255,.50);font-style:normal;overflow:hidden}
.featured-avatar img{width:100%;height:100%;border-radius:inherit;object-fit:cover}
.featured-avatar span{color:#fff8ea;font-size:1.55rem;font-weight:950;text-shadow:0 3px 12px rgba(7,27,51,.38)}
.month-ribbon{position:absolute;left:8px;top:2px;z-index:3;padding:9px 10px 12px;border-radius:8px 8px 4px 4px;background:linear-gradient(180deg,#f7c76b,#d9a441);border:1px solid rgba(122,79,24,.24);color:#071b33;font-size:.72rem;font-weight:950;box-shadow:0 8px 16px rgba(217,164,65,.26)}
.month-ribbon:after{content:"";position:absolute;left:50%;bottom:-7px;border-left:11px solid transparent;border-right:11px solid transparent;border-top:8px solid #d9a441;transform:translateX(-50%)}
.featured-crown{position:absolute;left:15px;bottom:30px;z-index:3;display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:15px;background:linear-gradient(145deg,#f7c76b,#d9a441);border:2px solid rgba(255,248,234,.88);color:#071b33;box-shadow:0 12px 22px rgba(122,79,24,.22)}
.featured-crown svg{width:25px;height:25px;stroke-width:1.7}
.featured-portrait em{position:absolute;right:23px;bottom:2px;z-index:4;display:flex;align-items:center;justify-content:center;width:54px;height:54px;border-radius:999px;background:linear-gradient(145deg,#f7c76b,#fff1be 48%,#d9a441);border:3px solid rgba(255,248,234,.92);color:#9a6819;box-shadow:0 13px 24px rgba(122,79,24,.22);font-style:normal}
.featured-portrait em svg{width:25px;height:25px;fill:rgba(217,164,65,.22)}
.featured-copy small{display:flex;align-items:center;gap:8px;color:#9a6819;font-size:.72rem;font-weight:900}
.featured-copy small svg{width:18px;height:18px;padding:4px;border-radius:999px;background:rgba(217,164,65,.16);color:#b17818}
.featured-copy strong{display:block;margin-top:9px;color:#071b33;font-size:clamp(1.45rem,2.45vw,2.35rem);font-weight:950;line-height:.95;letter-spacing:-.055em}
.featured-copy em{display:inline-flex;align-items:center;gap:7px;margin-top:10px;padding:6px 11px;border-radius:999px;background:#071b33;border:1px solid rgba(217,164,65,.36);color:#f7c76b;font-size:.76rem;font-style:normal;font-weight:850;box-shadow:0 8px 18px rgba(7,27,51,.12)}
.featured-copy em svg{width:16px;height:16px;color:#f7c76b}
.featured-copy p{display:flex;align-items:center;gap:9px;margin:12px 0 0;color:rgba(7,27,51,.68);font-family:Georgia,'Times New Roman',serif;font-size:.88rem;font-style:italic;line-height:1.2}
.featured-copy p b{color:#d9a441;font-size:1.5rem;line-height:0}
.highlight-badges{display:flex;gap:9px;margin-top:13px;flex-wrap:wrap}
.highlight-badges i{padding:7px 10px;border-radius:999px;background:rgba(255,255,255,.56);border:1px solid rgba(217,164,65,.18);color:#071b33;font-size:.66rem;font-style:normal;font-weight:850;box-shadow:inset 0 1px 0 rgba(255,255,255,.72)}
.featured-score{height:100%;min-height:138px;padding-left:16px;border-left:1px solid rgba(217,164,65,.18);display:flex;align-items:center;justify-content:center;flex-direction:column;text-align:center}
.featured-score>svg{width:29px;height:29px;color:#d9a441;margin-bottom:8px}
.featured-score strong{color:#071b33;font-size:2rem;font-weight:950;line-height:.92;letter-spacing:-.055em}
.featured-score small{margin-top:6px;color:rgba(7,27,51,.58);font-size:.78rem;font-weight:750}
.featured-score mark{display:grid;place-items:center;margin-top:14px;width:70px;height:70px;border-radius:999px;background:radial-gradient(circle,rgba(255,248,234,.84),rgba(247,199,107,.16));border:1px solid rgba(217,164,65,.22);color:#071b33;font-size:1.2rem;font-weight:950;line-height:1}
.featured-score mark span{display:block;color:#8a642c;font-size:.62rem;font-weight:900;letter-spacing:.06em}
.featured-score b{position:absolute;right:10px;top:50%;display:flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:999px;color:#d9a441;transform:translateY(-50%);font-style:normal}
.featured-score b svg{width:20px;height:20px;stroke-width:2.2}
.reading-highlight{grid-template-columns:72px minmax(0,1fr) 110px;align-items:center;gap:16px;min-height:104px;padding:12px 16px;background:linear-gradient(135deg,rgba(247,199,107,.16),rgba(255,248,234,.72) 50%,rgba(255,255,255,.48));border-color:rgba(217,164,65,.20)}
.reading-highlight:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 86% 30%,rgba(217,164,65,.14),transparent 30%);pointer-events:none}
.reading-avatar{display:flex;align-items:center;justify-content:center;width:62px;height:62px;border-radius:999px;background:radial-gradient(circle at 36% 24%,#fff8ea,#ba8950 42%,#071b33 100%);border:3px solid rgba(217,164,65,.72);box-shadow:0 10px 20px rgba(7,27,51,.13);font-style:normal}
.reading-avatar span{color:#fff8ea;font-size:.9rem;font-weight:950;text-shadow:0 2px 8px rgba(7,27,51,.30)}
.reading-avatar b{position:absolute;right:-6px;bottom:2px;display:flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:999px;background:#071b33;border:2px solid rgba(247,199,107,.78);color:#f7c76b}
.reading-avatar b svg{width:16px;height:16px}
.reading-copy small{display:flex;align-items:center;gap:7px;color:#2f6f3b;font-size:.72rem;font-weight:900}
.reading-copy small svg{width:16px;height:16px;color:#15803d}
.reading-copy strong{display:block;margin-top:5px;color:#071b33;font-size:1.08rem;font-weight:950;line-height:1}
.reading-copy em{display:flex;align-items:center;gap:9px;margin-top:8px;color:rgba(7,27,51,.68);font-size:.72rem;font-style:normal;font-weight:750;line-height:1.12;flex-wrap:wrap}
.reading-copy em span:before{content:"";display:inline-block;width:8px;height:8px;margin-right:7px;border-radius:999px;background:#15803d}
.reading-copy em b{display:inline;color:#15803d;font-size:.92rem;font-weight:950}
.reading-copy>b{display:block;height:7px;margin-top:8px;border-radius:999px;background:rgba(21,128,61,.12);overflow:hidden;max-width:360px}
.reading-copy>b i{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#15803d,#6ea24a,#d9a441)}
.reading-copy mark{display:block;width:max-content;margin-top:7px;color:#2f6f3b;background:transparent;font-family:Georgia,'Times New Roman',serif;font-size:.72rem;font-style:italic;font-weight:700}
.reading-art{display:flex;align-items:center;justify-content:flex-end;gap:10px;color:#d9a441}
.reading-art>svg{width:68px;height:68px;color:#d9a441;fill:rgba(217,164,65,.10);stroke-width:1.5;filter:drop-shadow(0 8px 14px rgba(122,79,24,.12))}
.reading-art b{display:flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:999px;color:#d9a441}
.reading-art b svg{width:20px;height:20px;stroke-width:2.2}
.featured-member-highlight:hover,.reading-highlight:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.38);box-shadow:0 20px 46px rgba(7,27,51,.11)}
.media-weekly-reference{grid-column:1/3;grid-row:4/5;padding:20px;display:flex;flex-direction:column;gap:14px;background:radial-gradient(circle at 12% 8%,rgba(247,199,107,.18),transparent 34%),radial-gradient(circle at 92% 88%,rgba(7,27,51,.08),transparent 36%),linear-gradient(135deg,rgba(255,248,234,.84),rgba(255,255,255,.54));border-color:rgba(255,255,255,.74);box-shadow:0 20px 48px rgba(7,27,51,.10),inset 0 1px 0 rgba(255,255,255,.86)}
.media-weekly-reference:after{content:"";position:absolute;inset:0;background:linear-gradient(115deg,transparent 0 34%,rgba(255,255,255,.18) 44%,transparent 54%);opacity:.24;pointer-events:none}
.media-weekly-title{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:0 2px}
.media-weekly-title h2{margin:0;color:#071b33;font-size:1.18rem;font-weight:950;letter-spacing:-.042em;line-height:1}
.media-weekly-title h2:before{content:"";display:inline-block;width:10px;height:10px;margin-right:9px;border-radius:999px;background:linear-gradient(145deg,#f7c76b,#d9a441);box-shadow:0 0 0 5px rgba(217,164,65,.12),0 0 18px rgba(217,164,65,.34)}
.media-weekly-title small{display:block;margin-top:6px;color:rgba(7,27,51,.62);font-size:.72rem;font-weight:750}
.media-weekly-grid{display:grid;grid-template-columns:minmax(0,1.06fr) minmax(0,.94fr);gap:14px;min-height:0;flex:1}
.recent-photos-panel,.weekly-panel{display:flex;flex-direction:column;gap:11px;min-width:0;padding:14px;border-radius:22px;background:linear-gradient(145deg,rgba(255,255,255,.56),rgba(255,248,234,.34));border:1px solid rgba(255,255,255,.68);box-shadow:0 12px 28px rgba(7,27,51,.06),inset 0 1px 0 rgba(255,255,255,.76);transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease}
.recent-photos-panel:hover,.weekly-panel:hover{transform:translateY(-2px);border-color:rgba(217,164,65,.28);box-shadow:0 18px 38px rgba(7,27,51,.10),inset 0 1px 0 rgba(255,255,255,.88)}
.panel-heading{display:grid;grid-template-columns:32px 1fr auto;align-items:center;gap:9px}
.panel-heading i{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:13px;background:linear-gradient(145deg,#071b33,#0b2748);color:#f7c76b;font-style:normal;box-shadow:0 8px 18px rgba(7,27,51,.16),inset 0 1px 0 rgba(255,255,255,.12)}
.panel-heading svg,.photo-mosaic svg,.weekly-flyers svg,.compact-card-header svg{fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.panel-heading i svg{width:16px;height:16px}
.panel-heading h3{display:flex;align-items:center;gap:7px;margin:0;color:#071b33;font-size:.86rem;font-weight:950;letter-spacing:-.02em}
.panel-heading h3 b{padding:3px 7px;border-radius:999px;background:linear-gradient(145deg,rgba(247,199,107,.22),rgba(217,164,65,.12));border:1px solid rgba(217,164,65,.22);color:#9a6819;font-size:.54rem;text-transform:uppercase;letter-spacing:.04em;box-shadow:0 6px 12px rgba(217,164,65,.10)}
.panel-heading small{display:block;margin-top:3px;color:rgba(7,27,51,.58);font-size:.6rem;font-weight:750;line-height:1.15}
.panel-heading a{display:flex;align-items:center;gap:5px;padding:7px 9px;border-radius:999px;color:#9a6819;text-decoration:none;font-size:.62rem;font-weight:900;white-space:nowrap;transition:transform .18s ease,background .18s ease,color .18s ease}
.panel-heading a:hover{transform:translateX(2px);background:rgba(217,164,65,.12);color:#071b33}
.panel-heading a svg{width:13px;height:13px;color:#d9a441}
.photo-mosaic{display:grid;grid-template-columns:1.25fr .72fr .72fr;grid-template-rows:76px 76px;gap:9px}
.photo-mosaic a{position:relative;overflow:hidden;border-radius:17px;border:1px solid rgba(255,255,255,.64);background:#071b33;box-shadow:0 10px 20px rgba(7,27,51,.12);isolation:isolate;transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease}
.photo-mosaic a.featured{grid-column:1/2;grid-row:1/3}
.photo-mosaic a:before{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(180deg,rgba(255,248,234,.04),rgba(7,27,51,.18));pointer-events:none}
.photo-mosaic a:after{content:"";position:absolute;inset:-45%;z-index:2;background:linear-gradient(115deg,transparent 34%,rgba(255,255,255,.32),transparent 64%);opacity:0;transform:translateX(-24%);transition:opacity 160ms ease,transform 160ms ease;pointer-events:none}
.photo-mosaic img,.weekly-flyers img{width:100%;height:100%;display:block;object-fit:cover;transition:transform 160ms ease,filter 160ms ease}
.photo-mosaic span{position:absolute;left:9px;top:9px;z-index:3;padding:5px 8px;border-radius:999px;background:linear-gradient(145deg,#f7c76b,#d9a441);color:#071b33;font-size:.54rem;font-weight:950;text-transform:uppercase;box-shadow:0 8px 18px rgba(122,79,24,.20)}
.photo-mosaic a:hover,.weekly-flyers a:hover{transform:translateY(-2px);border-color:rgba(217,164,65,.36);box-shadow:0 16px 28px rgba(7,27,51,.16)}
.photo-mosaic a:hover:after{opacity:1;transform:translateX(34%)}
.photo-mosaic a:hover img,.weekly-flyers a:hover img{transform:scale(1.07);filter:saturate(1.12) contrast(1.04)}
.recent-photos-panel p{display:flex;align-items:center;justify-content:space-between;gap:8px;margin:0;padding:9px 11px;border-radius:15px;background:linear-gradient(135deg,rgba(217,164,65,.13),rgba(255,255,255,.34));border:1px solid rgba(217,164,65,.17);box-shadow:inset 0 1px 0 rgba(255,255,255,.62)}
.recent-photos-panel p b,.recent-photos-panel p small{display:block}
.recent-photos-panel p b{color:#071b33;font-size:.67rem;font-weight:900}
.recent-photos-panel p small{color:rgba(7,27,51,.58);font-size:.58rem;font-weight:750}
.weekly-flyers{display:grid;grid-template-columns:1.18fr .82fr;grid-template-rows:1fr 1fr;gap:9px;flex:1;min-height:164px}
.weekly-flyers a{position:relative;overflow:hidden;border-radius:17px;background:#071b33;border:1px solid rgba(255,255,255,.62);text-decoration:none;color:#fff8ea;box-shadow:0 10px 20px rgba(7,27,51,.14);isolation:isolate;transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease}
.weekly-flyers .main-flyer{grid-row:1/3}
.weekly-flyers a:before{content:"";position:absolute;inset:0;z-index:1;background:radial-gradient(circle at 84% 16%,rgba(247,199,107,.22),transparent 30%),linear-gradient(180deg,transparent 28%,rgba(7,27,51,.86));pointer-events:none}
.weekly-flyers a:after{content:"";position:absolute;inset:-50%;z-index:2;background:linear-gradient(118deg,transparent 35%,rgba(247,199,107,.22),transparent 62%);opacity:.18;pointer-events:none}
.weekly-flyers span{position:absolute;z-index:3;left:10px;right:10px;bottom:10px;display:grid;gap:3px}
.weekly-flyers b{width:max-content;padding:4px 7px;border-radius:999px;background:rgba(247,199,107,.18);border:1px solid rgba(247,199,107,.32);color:#f7c76b;font-size:.54rem;text-transform:uppercase;letter-spacing:.04em}
.weekly-flyers strong{color:#fff8ea;font-size:.72rem;font-weight:950;line-height:1.05;text-shadow:0 2px 8px rgba(0,0,0,.30)}
.main-flyer strong{font-size:1.05rem}
.weekly-flyers small{color:rgba(255,248,234,.82);font-size:.58rem;font-weight:750}
.compact-side-stack{grid-column:3/4;grid-row:4/5;display:grid;grid-template-rows:minmax(0,.82fr) minmax(0,1.18fr);gap:12px;min-height:0}
.compact-side-stack .family-reference,.compact-side-stack .groups-reference{grid-column:auto;grid-row:auto;padding:16px;display:flex;flex-direction:column;min-height:0;background:linear-gradient(145deg,rgba(255,255,255,.58),rgba(255,248,234,.38));transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease}
.compact-side-stack .family-reference:hover,.compact-side-stack .groups-reference:hover{transform:translateY(-2px);border-color:rgba(217,164,65,.30);box-shadow:0 16px 32px rgba(7,27,51,.10),inset 0 1px 0 rgba(255,255,255,.84)}
.compact-card-header{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px}
.compact-card-header h2{margin:0;color:#071b33;font-size:.88rem;font-weight:950;letter-spacing:-.025em;line-height:1.05}
.compact-card-header a{padding:5px 7px;border-radius:999px;color:#9a6819;text-decoration:none;font-size:.62rem;font-weight:900;white-space:nowrap;transition:background .18s ease,color .18s ease}
.compact-card-header a:hover{background:rgba(217,164,65,.12);color:#071b33}
.compact-group-lines{display:grid;gap:8px;text-decoration:none;color:#071b33}
.compact-group-lines span{display:grid;gap:4px;padding:9px 10px;border-radius:15px;background:rgba(255,255,255,.48);border:1px solid rgba(255,255,255,.62);transition:transform .18s ease,border-color .18s ease,background .18s ease}
.compact-group-lines span:hover{transform:translateX(2px);border-color:rgba(217,164,65,.22);background:rgba(255,255,255,.62)}
.compact-group-lines small{color:rgba(7,27,51,.54);font-size:.58rem;font-weight:850}
.compact-group-lines b{color:#071b33;font-size:.7rem;font-weight:900;line-height:1.1}
.compact-group-lines i{height:6px;border-radius:999px;background:rgba(7,27,51,.10);overflow:hidden}
.compact-group-lines i em{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#15803d,#d9a441);box-shadow:0 0 14px rgba(21,128,61,.22)}
.birthday-reference{grid-column:4/5;grid-row:4/5;padding:18px;display:flex;flex-direction:column;gap:11px;background:linear-gradient(135deg,rgba(255,248,234,.78),rgba(255,255,255,.50));border-color:rgba(255,255,255,.68)}
.birthday-reference:after{content:"";position:absolute;right:-18px;top:22%;width:120px;height:120px;border-radius:999px;background:radial-gradient(circle,rgba(247,199,107,.16),transparent 68%);pointer-events:none}
.birthday-header{position:relative;z-index:1;display:grid;grid-template-columns:34px 1fr auto;align-items:center;gap:10px}
.birthday-header>i{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:12px;background:linear-gradient(145deg,#f7c76b,#d9a441);color:#fff8ea;box-shadow:0 8px 16px rgba(217,164,65,.20);font-style:normal}
.birthday-header svg,.birthday-featured svg,.birthday-button svg,.birthday-month svg{fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.birthday-header>i svg{width:18px;height:18px}
.birthday-header h2{margin:0;color:#071b33;font-size:.96rem;font-weight:950;letter-spacing:-.03em;line-height:1}
.birthday-header small{display:block;margin-top:4px;color:rgba(7,27,51,.58);font-size:.61rem;font-weight:750;line-height:1.15}
.birthday-header a{display:flex;align-items:center;gap:5px;color:#9a6819;text-decoration:none;font-size:.66rem;font-weight:900;white-space:nowrap}
.birthday-header a svg{width:14px;height:14px;color:#d9a441}
.birthday-featured{position:relative;z-index:1;display:grid;grid-template-columns:70px 1fr;align-items:center;gap:12px;min-height:96px;padding:12px;border-radius:20px;background:linear-gradient(135deg,rgba(255,248,234,.76),rgba(255,255,255,.46));border:1px solid rgba(255,255,255,.64);text-decoration:none;color:#071b33;overflow:hidden;box-shadow:inset 0 1px 0 rgba(255,255,255,.78)}
.birthday-featured:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 13% 12%,rgba(247,199,107,.22),transparent 28%),radial-gradient(circle at 94% 80%,rgba(217,164,65,.12),transparent 34%);pointer-events:none}
.birthday-avatar,.birthday-copy{position:relative;z-index:1}
.birthday-avatar{display:flex;align-items:center;justify-content:center;width:66px;height:66px;border-radius:999px;border:3px solid rgba(217,164,65,.78);background:radial-gradient(circle at 34% 25%,#fff8ea,#ba8950 45%,#071b33 100%);box-shadow:0 12px 22px rgba(7,27,51,.14),0 0 0 5px rgba(247,199,107,.10);font-style:normal}
.birthday-avatar span{color:#fff8ea;font-size:.9rem;font-weight:950;text-shadow:0 2px 8px rgba(7,27,51,.36)}
.birthday-avatar b{position:absolute;right:-5px;bottom:-4px;display:flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:999px;background:linear-gradient(145deg,#f7c76b,#d9a441);border:2px solid rgba(255,248,234,.90);color:#071b33;box-shadow:0 8px 14px rgba(122,79,24,.18)}
.birthday-avatar b svg{width:16px;height:16px}
.birthday-copy small{display:flex;align-items:center;gap:6px;color:#9a6819;font-size:.62rem;font-weight:900}
.birthday-copy small b{padding:3px 7px;border-radius:999px;background:rgba(217,164,65,.16);border:1px solid rgba(217,164,65,.18);color:#9a6819;font-size:.58rem;letter-spacing:.04em}
.birthday-copy strong{display:block;margin-top:5px;color:#071b33;font-size:1.03rem;font-weight:950;line-height:1;letter-spacing:-.03em}
.birthday-copy em{display:block;margin-top:5px;color:#7a4f18;font-size:.7rem;font-style:normal;font-weight:850}
.birthday-copy p{margin:7px 0 0;color:rgba(7,27,51,.66);font-family:Georgia,'Times New Roman',serif;font-size:.72rem;font-style:italic;line-height:1.18}
.birthday-actions{position:relative;z-index:1;display:flex}
.birthday-button{display:flex;align-items:center;justify-content:center;gap:8px;min-height:37px;width:100%;border-radius:999px;background:#071b33;color:#f7c76b;text-decoration:none;font-size:.72rem;font-weight:900;box-shadow:0 10px 20px rgba(7,27,51,.15)}
.birthday-button svg{width:15px;height:15px}
.birthday-month{position:relative;z-index:1;display:grid;grid-template-columns:1fr;gap:7px;margin-top:auto;padding:12px;border-radius:20px;background:rgba(255,255,255,.44);border:1px solid rgba(255,255,255,.62);text-decoration:none;color:#071b33}
.birthday-month>strong{display:flex;align-items:center;gap:7px;margin-bottom:1px;color:#071b33;font-size:.75rem;font-weight:950}
.birthday-month>strong svg{width:15px;height:15px;color:#d9a441}
.birthday-month span{display:grid;grid-template-columns:28px 1fr auto;align-items:center;gap:8px;min-height:30px}
.birthday-month span i{display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:999px;background:linear-gradient(135deg,#0b2748,#d9a441);border:2px solid rgba(255,248,234,.90);color:#fff8ea;font-size:.58rem;font-style:normal;font-weight:950}
.birthday-month span b{color:#071b33;font-size:.7rem;font-weight:900;line-height:1}
.birthday-month span small{color:rgba(7,27,51,.60);font-size:.67rem;font-weight:800}
.birthday-month>em{display:block;margin-top:2px;padding:7px;border-radius:14px;background:rgba(217,164,65,.10);border:1px solid rgba(217,164,65,.16);color:#7a4f18;text-align:center;font-size:.66rem;font-style:normal;font-weight:900}
.birthday-featured:hover,.birthday-button:hover,.birthday-month:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.30)}
.family-avatars{display:flex;align-items:center;margin-top:2px}
.family-avatars span,.family-avatars b{display:flex;align-items:center;justify-content:center;width:40px;height:40px;margin-right:-8px;border-radius:999px;border:2px solid rgba(255,248,234,.92);background:linear-gradient(135deg,#0b2748,#d9a441);color:#fff8ea;font-size:.78rem;font-weight:900;box-shadow:0 8px 16px rgba(7,27,51,.12);transition:transform .18s ease,box-shadow .18s ease}
.family-avatars span:hover,.family-avatars b:hover{transform:translateY(-2px) scale(1.04);box-shadow:0 12px 22px rgba(7,27,51,.16)}
.family-avatars b{background:#071b33;color:#f7c76b}
.family-card p{margin:16px 0 12px;color:rgba(7,27,51,.68);font-size:.78rem;font-weight:750}
.family-card p strong{color:#071b33;font-size:1rem}
.pending-box{display:grid;grid-template-columns:30px 1fr auto;align-items:center;gap:9px;margin-top:auto;padding:11px;border-radius:17px;background:rgba(255,255,255,.46);border:1px solid rgba(255,255,255,.62);color:#071b33;text-decoration:none;transition:transform .18s ease,border-color .18s ease,background .18s ease}
.pending-box:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.26);background:rgba(255,255,255,.64)}
.pending-box i{width:32px;height:32px;border-radius:999px;background:radial-gradient(circle at 35% 30%,#f7c76b,#d9a441)}
.pending-box b,.pending-box small{display:block}
.pending-box b{font-size:.78rem;color:#071b33}
.pending-box small{margin-top:3px;color:rgba(7,27,51,.58);font-size:.66rem;font-weight:700}
.pending-box em{color:#d9a441;font-size:1.2rem;font-style:normal;font-weight:950;line-height:1}
.access-footer{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:0;padding-bottom:4px}
.access-footer section{padding:20px;border-radius:24px;background:linear-gradient(135deg,rgba(255,251,242,.72),rgba(255,248,234,.46));border:1px solid rgba(255,255,255,.66);box-shadow:0 14px 34px rgba(7,27,51,.075),inset 0 1px 0 rgba(255,255,255,.82);backdrop-filter:blur(18px) saturate(145%)}
.access-footer h2{display:flex;align-items:center;gap:8px;margin:0 0 14px;color:#071b33;font-size:.95rem;font-weight:850;letter-spacing:-.02em}
.access-footer h2 small{color:rgba(7,27,51,.55);font-size:.65rem;font-weight:750}
.access-footer section:nth-child(2) h2:before{content:"";width:20px;height:20px;border-radius:8px;background:linear-gradient(135deg,rgba(217,164,65,.22),rgba(7,27,51,.10));border:1px solid rgba(217,164,65,.20)}
.access-footer div{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.access-footer a{display:flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:0 10px;border-radius:15px;background:rgba(255,255,255,.44);border:1px solid rgba(255,255,255,.62);color:#071b33;text-decoration:none;font-size:.72rem;font-weight:850;transition:transform .18s ease,border-color .18s ease,background .18s ease}
.access-footer a b{display:flex;align-items:center;justify-content:center;width:20px;height:20px;min-width:20px;color:#d9a441}
.access-footer a:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.32);background:rgba(255,255,255,.58)}
.access-footer a:hover b{color:#b17818}
@media(max-width:1280px){.dashboard-grid{grid-template-columns:repeat(2,minmax(0,1fr));grid-template-rows:auto}.hero-open,.verse-reference,.profile-reference,.service-reference,.presence-card,.journey-reference,.wisdom-reference,.highlights-reference,.media-weekly-reference,.compact-side-stack,.birthday-reference{grid-column:auto;grid-row:auto}.hero-open,.verse-reference,.service-reference,.highlights-reference,.media-weekly-reference{grid-column:1/-1}.compact-side-stack{grid-template-columns:1fr 1fr;grid-template-rows:auto}.dashboard-grid{gap:16px}.access-footer{grid-template-columns:1fr}.access-footer div{grid-template-columns:repeat(4,1fr)}}
@media(max-width:760px){.family-sidebar{display:none}.family-main{margin-left:0;padding:18px 14px 30px}.dashboard-grid{grid-template-columns:1fr}.hero-open,.verse-reference,.service-reference{grid-column:auto}.service-reference{grid-template-columns:1fr;padding:10px}.service-reference a{min-height:72px}.service-reference a:after{display:none}.media-weekly-grid,.compact-side-stack{grid-template-columns:1fr;grid-template-rows:auto}.photo-mosaic,.weekly-flyers{grid-template-columns:1fr}.photo-mosaic a.featured,.weekly-flyers .main-flyer{grid-row:auto}.access-footer div{grid-template-columns:repeat(2,1fr)}.journey-stats,.wisdom-summary,.wisdom-actions{grid-template-columns:1fr}.verse-reference strong{max-width:82%}}
</style>
