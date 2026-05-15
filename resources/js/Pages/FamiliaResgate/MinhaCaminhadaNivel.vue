<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const props = defineProps({
  journey: { type: String, default: 'geral' },
  level: { type: [Number, String], default: 1 },
})

// DADOS TEMPORÁRIOS PARA PROTÓTIPO VISUAL.
// Remover/substituir quando a integração real com backend for implementada.
const baseRoute = '/familia-resgate/minha-caminhada'

const journeyConfigs = {
  geral: {
    slug: 'geral',
    title: 'Caminhada Geral da Igreja',
    breadcrumb: 'Caminhada Geral',
    heroText: 'Um marco de fé, constância e direção na caminhada da igreja inteira.',
    points: 380,
    currentLevel: 2,
    pointStep: 400,
    statusDescriptions: {
      completed: 'Este marco já foi alcançado e permanece como testemunho da sua constância na família da igreja.',
      current: 'Você está caminhando neste marco agora. Continue firme nos pequenos passos de presença, Palavra e serviço.',
      future: 'Este é um próximo marco visível. Ele mostra para onde sua caminhada na igreja está avançando.',
      locked: 'Este marco ainda está mais à frente, guardado para uma próxima estação da jornada.',
    },
    howToAdvance: [
      { title: 'Presença no culto', text: 'Participe dos cultos e encontros com constância e coração disponível.', icon: '👥' },
      { title: 'Leitura bíblica', text: 'Separe tempo para ouvir a Palavra e registrar pequenos aprendizados.', icon: '📖' },
      { title: 'Devocional', text: 'Mantenha uma rotina simples de oração, reflexão e direção espiritual.', icon: '🕯️' },
      { title: 'Serviço', text: 'Sirva a casa e as pessoas com alegria, honra e responsabilidade.', icon: '🙌' },
      { title: 'Comunhão', text: 'Caminhe com a família espiritual, cuide de vínculos e permita ser cuidado.', icon: '🤝' },
      { title: 'Evangelismo', text: 'Compartilhe esperança com respeito, amor e sensibilidade.', icon: '📣' },
    ],
  },
  jovem: {
    slug: 'jovem',
    title: 'Caminhada Jovem Resgatado',
    breadcrumb: 'Resgatados',
    heroText: 'Um marco de propósito, coragem e chama acesa na caminhada dos jovens resgatados.',
    points: 920,
    currentLevel: 6,
    pointStep: 250,
    statusDescriptions: {
      completed: 'Este marco jovem já foi alcançado e fortalece a história de constância da geração resgatada.',
      current: 'Você está caminhando neste marco jovem agora. Permaneça aceso, firme e ensinável.',
      future: 'Este é um próximo marco da jornada jovem. Ele aponta para crescimento com propósito e coragem.',
      locked: 'Este marco jovem ainda está mais à frente, preparado para uma próxima estação da sua geração.',
    },
    howToAdvance: [
      { title: 'Encontro jovem', text: 'Participe dos encontros dos Resgatados com presença, alegria e compromisso.', icon: '🔥' },
      { title: 'Desafio bíblico', text: 'Responda aos desafios da Palavra e transforme aprendizado em prática.', icon: '📖' },
      { title: 'Oração e devocional', text: 'Separe tempo para buscar direção e fortalecer sua chama interior.', icon: '🕯️' },
      { title: 'Serviço com propósito', text: 'Sirva com disposição, respeito e excelência mesmo nos bastidores.', icon: '🙌' },
      { title: 'Comunhão saudável', text: 'Caminhe com amigos de fé, liderança e família espiritual.', icon: '🤝' },
      { title: 'Testemunho jovem', text: 'Compartilhe esperança na sua geração com coragem, amor e equilíbrio.', icon: '📣' },
    ],
  },
}

const generalLevels = [
  {
    number: 1,
    name: 'Primeiros Passos',
    icon: '👣',
    description: 'Este nível representa os primeiros passos firmes na casa, valorizando presença, acolhimento, comunhão e o início de uma rotina espiritual saudável.',
    verse: 'Zacarias 4:10',
    unlockedAt: '05/05/2026',
    badges: ['Cheguei para Adorar', 'Casa no Coração', 'Primeiro Marco'],
    activities: ['Presença no culto de domingo', 'Cadastro confirmado na Família Resgate', 'Primeiro devocional registrado'],
  },
  {
    number: 2,
    name: 'Coração Desperto',
    icon: '✚',
    description: 'Este nível representa o despertar para uma caminhada mais consciente com Deus, valorizando presença, Palavra, comunhão e constância.',
    verse: 'Salmo 119:105',
    unlockedAt: null,
    badges: ['Coração Atento', 'Palavra no Caminho', 'Constância Inicial'],
    activities: ['Leitura bíblica do dia', 'Presença no culto de celebração', 'Comunhão em família espiritual'],
  },
  {
    number: 3,
    name: 'Semente da Palavra',
    icon: '📖',
    description: 'Este nível aponta para a Palavra criando raiz no coração, formando direção, maturidade e decisões guiadas por Deus.',
    verse: 'Lucas 8:15',
    unlockedAt: null,
    badges: ['Palavra Viva', 'Semente Fiel', 'Leitura com Propósito'],
    activities: ['Ler um capítulo da Bíblia', 'Registrar uma reflexão bíblica', 'Participar de estudo bíblico'],
  },
  {
    number: 4,
    name: 'Raízes da Fé',
    icon: '🌿',
    description: 'Este nível celebra raízes espirituais mais profundas, com fé que permanece mesmo em semanas corridas ou estações difíceis.',
    verse: 'Colossenses 2:7',
    unlockedAt: null,
    badges: ['Raiz Firme', 'Fé em Crescimento', 'Permanência'],
    activities: ['Manter presença constante', 'Concluir devocionais da semana', 'Orar por sua família'],
  },
  {
    number: 5,
    name: 'Chama Interior',
    icon: '🔥',
    description: 'Este nível representa uma chama interior reacendida por devoção, serviço e desejo sincero de caminhar perto de Deus.',
    verse: 'Romanos 12:11',
    unlockedAt: null,
    badges: ['Chama Acesa', 'Fervor no Espírito', 'Altar Vivo'],
    activities: ['Concluir um plano devocional', 'Servir em uma escala', 'Participar de momento de oração'],
  },
  {
    number: 6,
    name: 'Caminho do Discípulo',
    icon: '🚶',
    description: 'Este nível destaca passos de discipulado, aprendizado, prática da Palavra e disposição para crescer com direção.',
    verse: 'Mateus 16:24',
    unlockedAt: null,
    badges: ['Discípulo em Movimento', 'Passos Firmes', 'Aprendiz do Reino'],
    activities: ['Participar de formação', 'Acompanhar mentoria espiritual', 'Aplicar uma direção da Palavra'],
  },
  {
    number: 7,
    name: 'Servo da Casa',
    icon: '🤝',
    description: 'Este nível reconhece disponibilidade para servir a casa de Deus com alegria, honra e responsabilidade.',
    verse: 'Josué 24:15',
    unlockedAt: null,
    badges: ['Servo Disponível', 'Mãos no Reino', 'Casa Servida'],
    activities: ['Servir em ministério', 'Apoiar uma equipe', 'Ajudar na organização de culto'],
  },
  {
    number: 8,
    name: 'Voz de Esperança',
    icon: '📣',
    description: 'Este nível representa palavras, atitudes e convites que carregam esperança para pessoas próximas.',
    verse: 'Romanos 10:15',
    unlockedAt: null,
    badges: ['Voz que Edifica', 'Esperança Compartilhada', 'Boa Notícia'],
    activities: ['Convidar alguém para o culto', 'Compartilhar uma mensagem bíblica', 'Encorajar uma pessoa'],
  },
  {
    number: 9,
    name: 'Guardião da Comunhão',
    icon: '👥',
    description: 'Este nível valoriza cuidado com vínculos, presença relacional, unidade e participação na família espiritual.',
    verse: 'Atos 2:42',
    unlockedAt: null,
    badges: ['Comunhão Viva', 'Guardião da Unidade', 'Família Presente'],
    activities: ['Participar de encontro em grupo', 'Acompanhar um irmão', 'Fortalecer vínculo familiar'],
  },
  {
    number: 10,
    name: 'Mãos Disponíveis',
    icon: '🙌',
    description: 'Este nível aponta para mãos prontas para cooperar, cuidar e fortalecer pessoas com humildade.',
    verse: 'Gálatas 6:9',
    unlockedAt: null,
    badges: ['Mãos Prontas', 'Cuidado em Ação', 'Serviço Fiel'],
    activities: ['Apoiar uma ação da igreja', 'Servir nos bastidores', 'Ajudar uma família'],
  },
  {
    number: 11,
    name: 'Caminho Firme',
    icon: '🛤️',
    description: 'Este nível representa constância madura, escolhas alinhadas e uma caminhada que não depende apenas de emoção.',
    verse: 'Provérbios 4:26',
    unlockedAt: null,
    badges: ['Passos Constantes', 'Direção Clara', 'Firmeza'],
    activities: ['Manter rotina semanal', 'Registrar progresso espiritual', 'Revisar próximos passos'],
  },
  {
    number: 12,
    name: 'Farol da Família',
    icon: '🕯️',
    description: 'Este nível destaca uma caminhada que ilumina a casa, influencia com amor e fortalece a família.',
    verse: 'Mateus 5:16',
    unlockedAt: null,
    badges: ['Farol da Casa', 'Luz no Lar', 'Família Edificada'],
    activities: ['Orar em família', 'Participar com familiares', 'Praticar cuidado no lar'],
  },
  {
    number: 13,
    name: 'Coração Missionário',
    icon: '💒',
    description: 'Este nível representa um coração voltado para alcançar, cuidar e participar da missão com amor.',
    verse: 'Marcos 16:15',
    unlockedAt: null,
    badges: ['Missão no Coração', 'Alcance com Amor', 'Cuidado Missionário'],
    activities: ['Evangelizar com respeito', 'Apoiar ação missionária', 'Interceder por visitantes'],
  },
  {
    number: 14,
    name: 'Obreiro Aprovado',
    icon: '🧰',
    description: 'Este nível valoriza serviço aprovado por fidelidade, preparo, responsabilidade e bom testemunho.',
    verse: '2 Timóteo 2:15',
    unlockedAt: null,
    badges: ['Obreiro Fiel', 'Excelência no Serviço', 'Responsabilidade'],
    activities: ['Cumprir escala com pontualidade', 'Participar de treinamento', 'Apoiar liderança'],
  },
  {
    number: 15,
    name: 'Coluna da Casa',
    icon: '🏛️',
    description: 'Este nível reconhece firmeza, honra, maturidade e cuidado consistente com a casa espiritual.',
    verse: '1 Timóteo 3:15',
    unlockedAt: null,
    badges: ['Coluna Firme', 'Honra da Casa', 'Referência'],
    activities: ['Cuidar de novos membros', 'Servir com constância', 'Fortalecer a cultura da casa'],
  },
  {
    number: 16,
    name: 'Semeador do Reino',
    icon: '🌾',
    description: 'Este nível aponta para sementes lançadas com perseverança, generosidade, Palavra e serviço.',
    verse: '2 Coríntios 9:6',
    unlockedAt: null,
    badges: ['Semeador Fiel', 'Fruto em Processo', 'Generosidade'],
    activities: ['Semear com serviço', 'Apoiar projetos da igreja', 'Compartilhar Palavra'],
  },
  {
    number: 17,
    name: 'Constância Fiel',
    icon: '✅',
    description: 'Este nível celebra fidelidade que permanece, mesmo quando ninguém está vendo ou a estação parece silenciosa.',
    verse: 'Apocalipse 2:10',
    unlockedAt: null,
    badges: ['Fidelidade Visível', 'Constância Secreta', 'Permaneceu'],
    activities: ['Manter presença mensal', 'Concluir ciclo devocional', 'Servir sem interrupções'],
  },
  {
    number: 18,
    name: 'Multiplicador de Fé',
    icon: '✨',
    description: 'Este nível representa fé que inspira outras pessoas, multiplica esperança e gera movimento saudável.',
    verse: '2 Timóteo 2:2',
    unlockedAt: null,
    badges: ['Fé que Multiplica', 'Influência Saudável', 'Esperança em Movimento'],
    activities: ['Discipular alguém', 'Compartilhar testemunho', 'Acompanhar novo convertido'],
  },
  {
    number: 19,
    name: 'Resgatador de Vidas',
    icon: '🕊️',
    description: 'Este nível honra uma caminhada madura, disponível para cuidar, alcançar e cooperar no resgate de vidas.',
    verse: 'Judas 1:23',
    unlockedAt: null,
    badges: ['Cuidado que Resgata', 'Vida Alcançada', 'Ponte de Amor'],
    activities: ['Acompanhar visitante', 'Interceder por famílias', 'Participar de ação evangelística'],
  },
  {
    number: 20,
    name: 'Legado Vivo',
    icon: '👑',
    description: 'Este nível representa legado de fé, serviço, presença e amor que permanece como testemunho para outras vidas.',
    verse: '2 Timóteo 4:7',
    unlockedAt: null,
    badges: ['Legado Vivo', 'Coroa de Constância', 'Testemunho Permanente'],
    activities: ['Celebrar testemunhos', 'Mentorear novos passos', 'Servir como referência saudável'],
  },
]

const youthLevels = [
  { number: 1, name: 'Semente Resgatada', icon: '🌱', description: 'Este nível representa o início de uma caminhada jovem plantada em propósito, identidade e cuidado espiritual.', verse: 'Jeremias 1:5', unlockedAt: '05/05/2026', badges: ['Semente Resgatada', 'Primeiro Sim', 'Raiz de Propósito'], activities: ['Participar do encontro jovem', 'Registrar primeira reflexão', 'Conectar-se com liderança jovem'] },
  { number: 2, name: 'Coração em Chamas', icon: '💛', description: 'Este nível aponta para um coração jovem aquecido por presença, Palavra e desejo sincero de permanecer perto de Deus.', verse: 'Lucas 24:32', unlockedAt: '08/05/2026', badges: ['Coração Aceso', 'Chama Inicial', 'Presença Jovem'], activities: ['Participar de culto jovem', 'Concluir desafio de oração', 'Compartilhar aprendizado da Palavra'] },
  { number: 3, name: 'Voz que Desperta', icon: '📣', description: 'Este nível celebra uma voz que desperta esperança, encoraja amigos e começa a influenciar a geração com equilíbrio.', verse: 'Isaías 40:3', unlockedAt: '10/05/2026', badges: ['Voz Jovem', 'Coragem para Falar', 'Esperança Compartilhada'], activities: ['Convidar um amigo', 'Responder desafio bíblico', 'Publicar reflexão saudável'] },
  { number: 4, name: 'Raiz Jovem', icon: '🌿', description: 'Este nível representa raízes espirituais crescendo em uma fase de decisões, amizades e formação de identidade.', verse: 'Colossenses 2:7', unlockedAt: '12/05/2026', badges: ['Raiz Jovem', 'Fé em Crescimento', 'Identidade Firmada'], activities: ['Manter devocional jovem', 'Conversar com mentor', 'Participar de comunhão saudável'] },
  { number: 5, name: 'Chama dos Resgatados', icon: '🔥', description: 'Este nível reconhece uma chama coletiva acesa entre os Resgatados, com alegria, unidade e constância.', verse: 'Romanos 12:11', unlockedAt: '14/05/2026', badges: ['Chama dos Resgatados', 'Geração Acesa', 'Unidade Jovem'], activities: ['Participar dos Resgatados', 'Servir em equipe jovem', 'Concluir desafio da semana'] },
  { number: 6, name: 'Escudeiro da Palavra', icon: '🛡️', description: 'Este nível destaca proteção pela Palavra, coragem para aprender e maturidade para fazer boas escolhas.', verse: 'Salmo 119:9', unlockedAt: null, badges: ['Escudeiro da Palavra', 'Bíblia na Mão', 'Escolhas Protegidas'], activities: ['Ler passagem bíblica', 'Responder quiz jovem', 'Aplicar uma direção da Palavra'] },
  { number: 7, name: 'Sentinela da Fé', icon: '🕯️', description: 'Este nível representa vigilância espiritual, oração e atenção ao que fortalece ou enfraquece a caminhada jovem.', verse: '1 Pedro 5:8', unlockedAt: null, badges: ['Sentinela Jovem', 'Oração Atenta', 'Fé Guardada'], activities: ['Participar de oração', 'Registrar pedido de cuidado', 'Evitar hábito prejudicial'] },
  { number: 8, name: 'Discípulo em Movimento', icon: '🚶', description: 'Este nível celebra o jovem que aprende, pratica e caminha em movimento com propósito e humildade.', verse: 'Mateus 16:24', unlockedAt: null, badges: ['Discípulo Jovem', 'Passos de Propósito', 'Aprendiz em Movimento'], activities: ['Participar de discipulado', 'Servir em atividade jovem', 'Cumprir direção prática'] },
  { number: 9, name: 'Luz na Geração', icon: '✨', description: 'Este nível aponta para uma presença jovem que ilumina ambientes com respeito, alegria e bom testemunho.', verse: 'Mateus 5:14', unlockedAt: null, badges: ['Luz na Geração', 'Testemunho Vivo', 'Brilho Saudável'], activities: ['Dar bom testemunho', 'Encorajar colega', 'Participar de ação jovem'] },
  { number: 10, name: 'Guardião da Chama', icon: '🔥', description: 'Este nível honra constância para guardar a chama acesa, mesmo em pressões, distrações e dias difíceis.', verse: '2 Timóteo 1:6', unlockedAt: null, badges: ['Guardião da Chama', 'Fogo Constante', 'Permaneceu Aceso'], activities: ['Manter presença mensal', 'Concluir devocionais', 'Buscar apoio da liderança'] },
  { number: 11, name: 'Coragem de Daniel', icon: '🦁', description: 'Este nível representa coragem para permanecer fiel, respeitoso e firme mesmo em ambientes desafiadores.', verse: 'Daniel 6:10', unlockedAt: null, badges: ['Coragem de Daniel', 'Firme na Pressão', 'Fé sem Vergonha'], activities: ['Tomar decisão saudável', 'Compartilhar testemunho', 'Orar antes de escolhas'] },
  { number: 12, name: 'Flecha de Propósito', icon: '🏹', description: 'Este nível aponta para direção, foco e escolhas alinhadas com o propósito de Deus para a juventude.', verse: 'Salmo 127:4', unlockedAt: null, badges: ['Flecha de Propósito', 'Direção Clara', 'Alvo Saudável'], activities: ['Definir próximo passo', 'Conversar sobre vocação', 'Participar de formação jovem'] },
  { number: 13, name: 'Voz no Deserto', icon: '📯', description: 'Este nível celebra uma voz que chama para esperança, arrependimento saudável e direção em meio à confusão.', verse: 'Marcos 1:3', unlockedAt: null, badges: ['Voz no Deserto', 'Chamado Jovem', 'Direção com Amor'], activities: ['Encorajar amigo', 'Participar de evangelismo', 'Registrar reflexão bíblica'] },
  { number: 14, name: 'Servo Valente', icon: '🤝', description: 'Este nível reconhece serviço jovem com coragem, excelência e disposição para ajudar sem buscar palco.', verse: '1 Timóteo 4:12', unlockedAt: null, badges: ['Servo Valente', 'Serviço Jovem', 'Excelência nos Bastidores'], activities: ['Servir em equipe', 'Apoiar evento jovem', 'Ajudar novo participante'] },
  { number: 15, name: 'Chama Missionária', icon: '🌍', description: 'Este nível aponta para paixão por vidas, visão missionária e vontade de alcançar a geração com amor.', verse: 'Marcos 16:15', unlockedAt: null, badges: ['Chama Missionária', 'Geração Enviada', 'Amor por Vidas'], activities: ['Participar de ação missionária', 'Convidar visitante', 'Interceder pela geração'] },
  { number: 16, name: 'Coluna Jovem', icon: '🏛️', description: 'Este nível reconhece maturidade jovem, referência saudável e compromisso com a casa espiritual.', verse: '1 Timóteo 3:15', unlockedAt: null, badges: ['Coluna Jovem', 'Referência Saudável', 'Firmeza na Casa'], activities: ['Apoiar liderança jovem', 'Cuidar de novos jovens', 'Servir com constância'] },
  { number: 17, name: 'Multiplicador de Esperança', icon: '✨', description: 'Este nível representa esperança que se multiplica por influência, cuidado e testemunho entre amigos.', verse: 'Romanos 15:13', unlockedAt: null, badges: ['Multiplicador de Esperança', 'Influência Saudável', 'Amigo de Fé'], activities: ['Acompanhar amigo', 'Compartilhar testemunho', 'Participar de pequeno grupo'] },
  { number: 18, name: 'Resgatador da Geração', icon: '�️', description: 'Este nível honra o jovem que coopera no cuidado, resgate e fortalecimento da sua geração.', verse: 'Judas 1:23', unlockedAt: null, badges: ['Resgatador da Geração', 'Cuidado Jovem', 'Ponte de Amor'], activities: ['Acolher visitante jovem', 'Interceder por amigos', 'Participar de ação de cuidado'] },
  { number: 19, name: 'Legado dos Resgatados', icon: '👑', description: 'Este nível representa uma história jovem que inspira outros Resgatados a permanecerem firmes.', verse: '2 Timóteo 2:2', unlockedAt: null, badges: ['Legado dos Resgatados', 'História que Inspira', 'Fruto Permanente'], activities: ['Mentorear jovem novo', 'Celebrar testemunhos', 'Servir como referência'] },
  { number: 20, name: 'Chama que Permanece', icon: '🔥', description: 'Este nível representa uma chama madura, constante e viva, que permanece como testemunho para a geração.', verse: 'Apocalipse 2:10', unlockedAt: null, badges: ['Chama que Permanece', 'Coroa Jovem', 'Fidelidade Acesa'], activities: ['Concluir ciclo jovem', 'Compartilhar legado', 'Servir com constância final'] },
]

const journeySlug = computed(() => props.journey === 'jovem' ? 'jovem' : 'geral')
const journeyConfig = computed(() => journeyConfigs[journeySlug.value])
const levelSeeds = computed(() => journeySlug.value === 'jovem' ? youthLevels : generalLevels)
const levels = computed(() => levelSeeds.value.map((level) => {
  const config = journeyConfig.value
  const status = level.number < config.currentLevel
    ? 'completed'
    : level.number === config.currentLevel
      ? 'current'
      : level.number <= config.currentLevel + 3
        ? 'future'
        : 'locked'
  const pointsRequired = level.number * config.pointStep

  return {
    ...level,
    slug: level.name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''),
    status,
    pointsRequired,
    currentPoints: status === 'completed' ? pointsRequired : Math.min(config.points, pointsRequired),
    nextLevel: level.number < 20 ? level.number + 1 : null,
    route: `${baseRoute}/${config.slug}/niveis/${level.number}`,
  }
}))

const statusLabels = {
  completed: 'Conquistado',
  current: 'Em progresso',
  future: 'Próximo marco',
  locked: 'Bloqueado',
}

const selectedLevelNumber = computed(() => {
  const parsed = Number.parseInt(String(props.level), 10)
  return Number.isInteger(parsed) && parsed >= 1 && parsed <= 20 ? parsed : 1
})
const level = computed(() => levels.value.find((item) => item.number === selectedLevelNumber.value) || levels.value[0])
const nextLevelData = computed(() => level.value.nextLevel ? levels.value.find((item) => item.number === level.value.nextLevel) : null)
const progressPercent = computed(() => Math.min(100, Math.round((level.value.currentPoints / level.value.pointsRequired) * 100)))
const pageTitle = computed(() => `Nível ${level.value.number} · ${level.value.name} · ${journeyConfig.value.title}`)
const mapRoute = computed(() => `${baseRoute}/${journeySlug.value}/mapa`)
</script>

<template>
  <Head :title="pageTitle" />

  <div class="level-page" :class="`journey-${journeySlug}`">
    <FamilySidebar active-href="/familia-resgate/minha-caminhada" />

    <main class="level-main">
      <header class="level-hero">
        <div>
          <p>Central da Família <span>&gt;</span> Minha Caminhada <span>&gt;</span> {{ journeyConfig.breadcrumb }} <span>&gt;</span> Nível {{ level.number }}</p>
          <h1>{{ level.name }}</h1>
          <small>{{ journeyConfig.title }} · {{ journeyConfig.heroText }}</small>
        </div>
        <strong :class="`is-${level.status}`">{{ statusLabels[level.status] }}</strong>
      </header>

      <section class="level-stage">
        <article class="level-sanctuary" :class="`is-${level.status}`">
          <div class="sanctuary-visual">
            <div class="church-mark" aria-hidden="true">
              <span class="church-light"></span>
              <span class="church-icon">{{ level.icon }}</span>
              <span class="church-tower"></span>
              <span class="church-roof"></span>
              <span class="church-body"><i></i></span>
            </div>
            <span>Nível {{ level.number }} de 20</span>
          </div>

          <div class="sanctuary-content">
            <div class="level-kicker">
              <span>{{ journeyConfig.title }}</span>
              <small>{{ level.unlockedAt ? `Desbloqueado em ${level.unlockedAt}` : 'Caminhada em desenvolvimento' }}</small>
            </div>
            <h2>{{ level.name }}</h2>
            <p>{{ journeyConfig.statusDescriptions[level.status] }}</p>

            <div class="progress-bar" aria-label="Progresso do nível">
              <i :style="{ width: `${progressPercent}%` }"></i>
            </div>

            <div class="level-summary">
              <article><small>Atual</small><strong>{{ level.currentPoints }}</strong></article>
              <article><small>Necessário</small><strong>{{ level.pointsRequired }}</strong></article>
              <article><small>Progresso</small><strong>{{ progressPercent }}%</strong></article>
            </div>

            <footer class="next-line">
              <span v-if="nextLevelData">Próximo nível: <b>{{ nextLevelData.name }}</b></span>
              <span v-else>Você chegou ao marco final desta caminhada.</span>
            </footer>
          </div>
        </article>

        <nav class="level-actions" aria-label="Ações do nível">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="mapRoute">Ir ao mapa</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
          <Link v-if="nextLevelData" :href="nextLevelData.route">Próximo nível</Link>
          <button v-else type="button" disabled>Ver legado da caminhada</button>
        </nav>
      </section>

      <section class="content-grid">
        <article class="info-card meaning-card">
          <header><span>✚</span><h2>O que este nível representa</h2></header>
          <p>{{ level.description }}</p>
          <small>Este marco não mede valor espiritual; ele organiza passos saudáveis de presença, Palavra, serviço e comunhão.</small>
        </article>

        <article class="info-card verse-card">
          <header><span>📖</span><h2>Versículo de direção</h2></header>
          <strong>{{ level.verse }}</strong>
          <p>Uma palavra para acompanhar este ponto da caminhada e lembrar que cada avanço precisa produzir vida, amor e constância.</p>
        </article>
      </section>

      <section class="advance-section">
        <header>
          <span>Como avançar</span>
          <h2>Pequenos passos que fortalecem este marco</h2>
        </header>
        <div class="advance-grid">
          <article v-for="item in journeyConfig.howToAdvance" :key="item.title">
            <i>{{ item.icon }}</i>
            <strong>{{ item.title }}</strong>
            <p>{{ item.text }}</p>
          </article>
        </div>
      </section>

      <section class="bottom-grid">
        <article class="info-card badges-card">
          <header><span>🏆</span><h2>Conquistas relacionadas</h2></header>
          <div class="badge-list">
            <div v-for="badge in level.badges" :key="badge">
              <i>{{ level.icon }}</i>
              <strong>{{ badge }}</strong>
              <small>{{ statusLabels[level.status] }}</small>
            </div>
          </div>
        </article>

        <article class="info-card history-card">
          <header><span>▤</span><h2>Histórico deste nível</h2></header>
          <div class="history-list">
            <div v-for="activity in level.activities.slice(0, 3)" :key="activity">
              <i></i>
              <p>{{ activity }}<small>{{ level.unlockedAt || 'Em acompanhamento' }}</small></p>
              <strong>+{{ Math.max(10, level.number * 3) }}</strong>
            </div>
          </div>
        </article>
      </section>
    </main>
  </div>
</template>

<style scoped>
.level-page { min-height: 100vh; background: radial-gradient(circle at 12% 0%, rgba(217,164,65,.14), transparent 26%), radial-gradient(circle at 88% 8%, rgba(7,27,51,.08), transparent 28%), linear-gradient(135deg, #fff8ea, #f5ead8); }
.level-page.journey-jovem { background: radial-gradient(circle at 12% 0%, rgba(249,115,22,.14), transparent 26%), radial-gradient(circle at 86% 8%, rgba(217,164,65,.14), transparent 28%), linear-gradient(135deg, #fff8ea, #f5ead8); }
.level-main { margin-left: 80px; padding: 14px 20px 22px; }
a { text-decoration: none; }
.level-hero, .level-stage, .content-grid, .advance-section, .bottom-grid { max-width: 1240px; margin-left: auto; margin-right: auto; }
.level-hero { display: flex; justify-content: space-between; align-items: center; gap: 14px; margin-bottom: 10px; padding: 15px 18px; border: 1px solid rgba(246,200,95,.26); border-radius: 22px; background: radial-gradient(circle at 82% 16%, rgba(246,200,95,.18), transparent 32%), linear-gradient(135deg, #071b33, #0b2748); box-shadow: 0 16px 34px rgba(7,27,51,.15); }
.journey-jovem .level-hero { border-color: rgba(249,115,22,.3); background: radial-gradient(circle at 82% 16%, rgba(249,115,22,.24), transparent 32%), radial-gradient(circle at 22% 16%, rgba(246,200,95,.14), transparent 30%), linear-gradient(135deg, #071b33, #0b2748); }
.level-hero p { margin: 0; color: rgba(255,248,234,.82); font-size: .75rem; font-weight: 850; }
.level-hero p span { color: #f6c85f; }
.level-hero h1 { margin: 4px 0 3px; color: #fff8ea; font: 950 clamp(1.7rem, 3.8vw, 3rem)/.92 Georgia, serif; letter-spacing: -.055em; }
.level-hero small { display: block; max-width: 660px; color: rgba(255,248,234,.9); font-size: .82rem; font-weight: 760; line-height: 1.35; }
.level-hero > strong { padding: 8px 12px; border: 1px solid rgba(246,200,95,.5); border-radius: 999px; background: rgba(255,248,234,.1); color: #f6c85f; font-size: .75rem; font-weight: 950; white-space: nowrap; }
.level-hero > strong.is-current { color: #fff8ea; background: linear-gradient(135deg, rgba(246,200,95,.32), rgba(255,248,234,.1)); }
.level-hero > strong.is-locked { color: rgba(255,248,234,.7); border-color: rgba(255,248,234,.18); }
.level-stage { display: grid; gap: 10px; margin-bottom: 10px; }
.level-sanctuary { position: relative; overflow: hidden; display: grid; grid-template-columns: 186px minmax(0, 1fr); gap: 14px; min-height: 210px; padding: 13px; border: 1px solid rgba(217,164,65,.24); border-radius: 24px; background: radial-gradient(circle at 17% 42%, rgba(246,200,95,.25), transparent 24%), radial-gradient(circle at 92% 8%, rgba(217,164,65,.16), transparent 26%), linear-gradient(135deg, rgba(255,248,234,.96), rgba(255,248,234,.74)); box-shadow: 0 16px 34px rgba(7,27,51,.11), inset 0 1px 0 rgba(255,255,255,.72); }
.level-sanctuary::after { content: 'Cada passo fiel ilumina o caminho.'; position: absolute; top: 12px; right: 14px; color: rgba(138,91,19,.58); font-size: .58rem; font-weight: 950; letter-spacing: .04em; text-transform: uppercase; }
.level-sanctuary.is-locked { filter: saturate(.9); }
.sanctuary-visual { display: grid; gap: 6px; align-content: center; justify-items: center; min-width: 0; }
.sanctuary-visual > span { color: #8a5b13; font-size: .75rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; }
.church-mark { position: relative; display: grid; place-items: end center; width: 162px; height: 162px; border: 1px solid rgba(255,248,234,.1); border-radius: 22px; background: radial-gradient(circle at 50% 50%, rgba(246,200,95,.34), transparent 35%), linear-gradient(180deg, #0b2748, #020a1c); box-shadow: inset 0 1px 0 rgba(255,248,234,.12), 0 12px 24px rgba(7,27,51,.14); }
.church-light { position: absolute; inset: 22px; border-radius: 50%; background: radial-gradient(circle, rgba(246,200,95,.46), transparent 64%); filter: blur(8px); }
.church-icon { position: absolute; top: 14px; display: grid; place-items: center; width: 40px; height: 40px; border: 1px solid rgba(246,200,95,.68); border-radius: 50%; background: rgba(2,10,28,.56); font-size: 1.16rem; box-shadow: 0 0 22px rgba(246,200,95,.22); }
.church-tower { position: absolute; bottom: 54px; width: 27px; height: 61px; border: 1px solid rgba(255,248,234,.58); border-radius: 10px 10px 3px 3px; background: linear-gradient(180deg, #fff8ea, #d8b66a 52%, #76511e); box-shadow: inset 0 1px 0 rgba(255,255,255,.68), 0 0 16px rgba(246,200,95,.16); }
.church-tower::before { content: ''; position: absolute; top: -20px; left: -6px; width: 39px; height: 25px; clip-path: polygon(50% 0, 100% 100%, 0 100%); background: linear-gradient(180deg, #f6c85f, #8a5b13); }
.church-tower::after { content: ''; position: absolute; top: 19px; left: 9px; width: 9px; height: 15px; border-radius: 999px 999px 3px 3px; background: rgba(7,27,51,.72); box-shadow: inset 0 0 8px rgba(246,200,95,.24); }
.church-roof { position: absolute; bottom: 46px; width: 96px; height: 37px; clip-path: polygon(50% 0, 100% 100%, 0 100%); background: linear-gradient(180deg, #f6c85f, #a96f1a 70%, #57340f); }
.church-body { position: absolute; bottom: 22px; display: grid; place-items: end center; width: 84px; height: 46px; border: 1px solid rgba(255,248,234,.48); border-radius: 9px 9px 15px 15px; background: linear-gradient(180deg, #fff8ea, #d6b677 54%, #806034); box-shadow: inset 0 1px 0 rgba(255,255,255,.7); }
.church-body i { width: 21px; height: 28px; border-radius: 999px 999px 4px 4px; background: linear-gradient(180deg, #6b4016, #2d1c10); box-shadow: inset 0 0 8px rgba(0,0,0,.28); }
.sanctuary-content { display: grid; align-content: center; gap: 7px; min-width: 0; }
.level-kicker { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
.level-kicker span { padding: 5px 9px; border: 1px solid rgba(217,164,65,.36); border-radius: 999px; background: rgba(217,164,65,.12); color: #8a5b13; font-size: .75rem; font-weight: 950; }
.level-kicker small { color: #516070; font-size: .75rem; font-weight: 850; }
.sanctuary-content h2 { margin: 0; color: #071b33; font: 950 clamp(1.55rem, 3.4vw, 2.6rem)/.92 Georgia, serif; letter-spacing: -.055em; }
.sanctuary-content p { margin: 0; max-width: 620px; color: #334155; font-size: .84rem; font-weight: 780; line-height: 1.38; }
.progress-bar { height: 8px; overflow: hidden; padding: 2px; border-radius: 999px; background: rgba(7,27,51,.1); box-shadow: inset 0 1px 4px rgba(7,27,51,.12); }
.progress-bar i { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #d9a441, #f6c85f); box-shadow: 0 0 18px rgba(246,200,95,.42); }
.journey-jovem .progress-bar i { background: linear-gradient(90deg, #f97316, #f6c85f); box-shadow: 0 0 18px rgba(249,115,22,.34); }
.level-summary { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 7px; }
.level-summary article { padding: 7px 10px; border: 1px solid rgba(217,164,65,.18); border-radius: 14px; background: rgba(255,255,255,.58); }
.level-summary small { display: block; color: #445164; font-size: .75rem; font-weight: 900; }
.level-summary strong { display: block; margin-top: 2px; color: #071b33; font-size: 1.1rem; font-weight: 950; letter-spacing: -.04em; }
.next-line span { color: #445164; font-size: .8rem; font-weight: 850; }
.next-line b { color: #071b33; }
.level-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; padding: 8px; border: 1px solid rgba(217,164,65,.18); border-radius: 18px; background: linear-gradient(135deg, rgba(255,248,234,.82), rgba(255,255,255,.52)); box-shadow: 0 10px 22px rgba(7,27,51,.06), inset 0 1px 0 rgba(255,255,255,.58); }
.level-actions a, .level-actions button { display: inline-flex; justify-content: center; align-items: center; min-height: 34px; padding: 8px 11px; border: 1px solid rgba(217,164,65,.28); border-radius: 999px; background: rgba(255,255,255,.58); color: #071b33; font-size: .82rem; font-weight: 950; box-shadow: inset 0 1px 0 rgba(255,255,255,.58); }
.level-actions a:first-child { background: linear-gradient(135deg, #071b33, #0b2748); color: #f6c85f; border-color: rgba(246,200,95,.4); }
.level-actions button { cursor: not-allowed; opacity: .66; }
.content-grid, .bottom-grid { display: grid; grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr); gap: 10px; margin-bottom: 10px; }
.info-card, .advance-section { border: 1px solid rgba(217,164,65,.16); border-radius: 20px; background: rgba(255,248,234,.84); box-shadow: 0 12px 26px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.62); }
.info-card { padding: 13px; }
.info-card header, .advance-section header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.info-card header span { display: grid; place-items: center; width: 32px; height: 32px; border-radius: 12px 12px 16px 16px; background: linear-gradient(135deg, #071b33, #0b2748); color: #f6c85f; font-size: .82rem; }
.info-card h2, .advance-section h2 { margin: 0; color: #071b33; font: 900 .98rem/1.08 Georgia, serif; }
.info-card p, .info-card small { color: #334155; font-size: .82rem; font-weight: 760; line-height: 1.38; }
.info-card small { display: block; margin-top: 6px; color: #516070; }
.verse-card { background: radial-gradient(circle at 85% 8%, rgba(246,200,95,.18), transparent 34%), linear-gradient(135deg, #071b33, #0b2748); }
.verse-card h2, .verse-card p { color: #fff8ea; }
.verse-card strong { display: block; margin: 9px 0 5px; color: #f6c85f; font: 900 1.22rem/1.05 Georgia, serif; }
.advance-section { margin-bottom: 10px; padding: 12px; }
.advance-section header { display: block; }
.advance-section header span { color: #8a5b13; font-size: .75rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; }
.advance-section h2 { margin-top: 3px; }
.advance-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 9px; }
.advance-grid article { display: grid; grid-template-columns: 34px minmax(0, 1fr); grid-template-rows: auto 1fr; column-gap: 9px; row-gap: 3px; align-items: start; min-height: 82px; padding: 10px; border: 1px solid rgba(217,164,65,.16); border-radius: 16px; background: linear-gradient(135deg, rgba(255,255,255,.72), rgba(255,248,234,.68)); box-shadow: inset 0 1px 0 rgba(255,255,255,.68); }
.advance-grid i { grid-row: 1 / span 2; display: grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; background: rgba(217,164,65,.14); color: #8a5b13; font-style: normal; font-size: .96rem; }
.advance-grid strong { display: block; min-width: 0; color: #071b33; font-size: .88rem; font-weight: 950; line-height: 1.12; }
.advance-grid p { margin: 0; min-width: 0; color: #445164; font-size: .78rem; font-weight: 760; line-height: 1.28; }
.badge-list { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
.badge-list div { display: grid; justify-items: center; gap: 4px; padding: 10px 6px; border: 1px solid rgba(217,164,65,.16); border-radius: 16px; background: rgba(255,255,255,.56); text-align: center; }
.badge-list i { display: grid; place-items: center; width: 42px; height: 48px; border: 2px solid #d9a441; border-radius: 15px 15px 22px 22px; background: linear-gradient(135deg, #071b33, #0b2748); color: #f6c85f; font-style: normal; font-size: .9rem; }
.badge-list strong { color: #071b33; font-size: .78rem; font-weight: 950; line-height: 1.08; }
.badge-list small { margin: 0; color: #8a5b13; font-size: .75rem; font-weight: 950; }
.history-list { display: grid; gap: 7px; }
.history-list div { display: grid; grid-template-columns: 14px 1fr auto; gap: 8px; align-items: center; padding-bottom: 7px; border-bottom: 1px solid rgba(217,164,65,.12); }
.history-list div:last-child { border-bottom: 0; padding-bottom: 0; }
.history-list i { width: 8px; height: 8px; border-radius: 50%; background: #d9a441; box-shadow: 0 0 0 4px rgba(217,164,65,.14); }
.history-list p { display: grid; gap: 1px; margin: 0; color: #071b33; font-size: .82rem; font-weight: 850; line-height: 1.2; }
.history-list p small { margin: 0; color: #516070; font-size: .75rem; }
.history-list strong { color: #0b2748; font-size: .82rem; font-weight: 950; }

@media (max-width: 1180px) {
  .level-sanctuary, .content-grid, .bottom-grid { grid-template-columns: 1fr; }
  .advance-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 760px) {
  .level-main { margin-left: 0; padding: 16px; }
  .level-hero { flex-direction: column; align-items: flex-start; }
  .level-sanctuary { gap: 12px; padding: 13px; }
  .level-summary, .advance-grid, .badge-list { grid-template-columns: 1fr; }
  .church-mark { width: 156px; height: 156px; }
  .level-sanctuary::after { display: none; }
}
</style>
