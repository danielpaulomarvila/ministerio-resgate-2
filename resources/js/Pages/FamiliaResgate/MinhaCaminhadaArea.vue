<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const props = defineProps({
  area: { type: String, default: 'mapa' },
  journey: { type: String, default: 'geral' },
  walkingLevel: { type: Object, default: null },
  walkingMap: { type: Object, default: null },
})

const baseRoute = '/familia-resgate/minha-caminhada'

const journeyConfigs = {
  geral: {
    slug: 'geral',
    title: 'Mapa da Caminhada Geral',
    breadcrumb: 'Caminhada Geral',
    subtitle: 'Veja os 20 marcos da caminhada geral e acompanhe cada igreja conquistada no caminho.',
    points: 0,
    currentLevel: 1,
    pointStep: 1,
  },
  jovem: {
    slug: 'jovem',
    title: 'Mapa Jovem dos Resgatados',
    breadcrumb: 'Resgatados',
    subtitle: 'Veja os 20 marcos da jornada jovem e acompanhe cada estação dos Resgatados no caminho.',
    points: 0,
    currentLevel: 1,
    pointStep: 1,
  },
}

const generalLevels = [
  { number: 1, name: 'Primeiros Passos', icon: '👣' },
  { number: 2, name: 'Coração Desperto', icon: '✚' },
  { number: 3, name: 'Semente da Palavra', icon: '📖' },
  { number: 4, name: 'Raízes da Fé', icon: '🌿' },
  { number: 5, name: 'Chama Interior', icon: '🔥' },
  { number: 6, name: 'Caminho do Discípulo', icon: '🚶' },
  { number: 7, name: 'Servo da Casa', icon: '🤝' },
  { number: 8, name: 'Voz de Esperança', icon: '📣' },
  { number: 9, name: 'Guardião da Comunhão', icon: '👥' },
  { number: 10, name: 'Mãos Disponíveis', icon: '🙌' },
  { number: 11, name: 'Caminho Firme', icon: '🛤️' },
  { number: 12, name: 'Farol da Família', icon: '🕯️' },
  { number: 13, name: 'Coração Missionário', icon: '💒' },
  { number: 14, name: 'Obreiro Aprovado', icon: '🧰' },
  { number: 15, name: 'Coluna da Casa', icon: '🏛️' },
  { number: 16, name: 'Semeador do Reino', icon: '🌾' },
  { number: 17, name: 'Constância Fiel', icon: '✅' },
  { number: 18, name: 'Multiplicador de Fé', icon: '✨' },
  { number: 19, name: 'Resgatador de Vidas', icon: '🕊️' },
  { number: 20, name: 'Legado Vivo', icon: '👑' },
]

const youthLevels = [
  { number: 1, name: 'Semente Resgatada', icon: '🌱' },
  { number: 2, name: 'Coração em Chamas', icon: '💛' },
  { number: 3, name: 'Voz que Desperta', icon: '📣' },
  { number: 4, name: 'Raiz Jovem', icon: '🌿' },
  { number: 5, name: 'Chama dos Resgatados', icon: '🔥' },
  { number: 6, name: 'Escudeiro da Palavra', icon: '🛡️' },
  { number: 7, name: 'Sentinela da Fé', icon: '🕯️' },
  { number: 8, name: 'Discípulo em Movimento', icon: '🚶' },
  { number: 9, name: 'Luz na Geração', icon: '✨' },
  { number: 10, name: 'Guardião da Chama', icon: '🔥' },
  { number: 11, name: 'Coragem de Daniel', icon: '🦁' },
  { number: 12, name: 'Flecha de Propósito', icon: '🏹' },
  { number: 13, name: 'Voz no Deserto', icon: '📯' },
  { number: 14, name: 'Servo Valente', icon: '🤝' },
  { number: 15, name: 'Chama Missionária', icon: '🌍' },
  { number: 16, name: 'Coluna Jovem', icon: '🏛️' },
  { number: 17, name: 'Multiplicador de Esperança', icon: '✨' },
  { number: 18, name: 'Resgatador da Geração', icon: '🕊️' },
  { number: 19, name: 'Legado dos Resgatados', icon: '👑' },
  { number: 20, name: 'Chama que Permanece', icon: '🔥' },
]

const statusLabels = {
  completed: 'Conquistado',
  current: 'Em progresso',
  next: 'Próximo marco',
  future: 'Próximo marco',
  locked: 'Bloqueado',
}

const statusNotes = {
  completed: 'Marco já alcançado na caminhada.',
  current: 'Você está aqui, siga firme.',
  next: 'Próximo nível real a alcançar.',
  future: 'Próxima estação visível.',
  locked: 'Será liberado mais à frente.',
}

const journeyDashboardMocks = {
  geral: {
    slug: 'geral',
    title: 'Detalhes da Caminhada Geral',
    breadcrumb: 'Caminhada Geral',
    subtitle: 'Veja como sua caminhada na igreja está avançando em presença, Palavra, serviço e comunhão.',
    label: 'Jornada geral',
    currentLevelName: 'Coração Desperto',
    currentLevel: 2,
    points: 380,
    pointsTarget: 800,
    progress: 48,
    nextLevelName: 'Semente da Palavra',
    positionLabel: 'Posição geral',
    position: '12º',
    badgeLabel: 'Badges gerais',
    badgesCount: 3,
    levelButton: 'Ver nível atual',
    mapButton: 'Ir ao mapa geral',
    levelRoute: `${baseRoute}/geral/niveis/2`,
    mapRoute: `${baseRoute}/geral/mapa`,
    growthTitle: 'Áreas de crescimento',
    growthHeading: 'Análise da caminhada',
    focusLabel: 'Foco da semana',
    focusTitle: 'Fortalecer Palavra e Devocional',
    focusSuggestion: 'Ler 1 capítulo por dia e registrar uma breve reflexão.',
    activitiesTitle: 'Atividades recentes',
    activitiesHeading: 'Movimentos da caminhada geral',
    achievementsTitle: 'Conquistas gerais',
    growthItems: [
      { icon: '⛪', name: 'Presença', percent: 82, points: 145, note: 'Constância nos cultos e encontros.' },
      { icon: '📖', name: 'Palavra', percent: 64, points: 88, note: 'Leitura bíblica em progresso.' },
      { icon: '🕯️', name: 'Devocional', percent: 52, points: 54, note: 'Tempo diário com Deus.' },
      { icon: '🤝', name: 'Serviço', percent: 70, points: 72, note: 'Mãos disponíveis na casa.' },
      { icon: '👥', name: 'Comunhão', percent: 58, points: 42, note: 'Caminhada junto da família.' },
      { icon: '🕊️', name: 'Evangelismo', percent: 36, points: 24, note: 'Compartilhando esperança.' },
      { icon: '🎓', name: 'Formação', percent: 44, points: 31, note: 'Crescendo em maturidade.' },
    ],
    activities: [
      { name: 'Presença no culto', points: 40 },
      { name: 'Leitura bíblica do dia', points: 18 },
      { name: 'Serviço em escala', points: 35 },
    ],
    achievements: ['Presença Fiel', 'Palavra Viva', 'Servo Disponível'],
  },
  jovem: {
    slug: 'jovem',
    title: 'Detalhes da Caminhada Jovem',
    breadcrumb: 'Resgatados',
    subtitle: 'Veja sua evolução nos Resgatados, desafios, encontros, Palavra, serviço e missões.',
    label: 'Resgatados',
    currentLevelName: 'Escudeiro da Palavra',
    currentLevel: 6,
    points: 920,
    pointsTarget: 1500,
    progress: 61,
    nextLevelName: 'Sentinela da Fé',
    positionLabel: 'Posição jovem',
    position: '5º',
    badgeLabel: 'Badges jovens',
    badgesCount: 6,
    levelButton: 'Ver nível jovem',
    mapButton: 'Ir ao mapa jovem',
    levelRoute: `${baseRoute}/jovem/niveis/6`,
    mapRoute: `${baseRoute}/jovem/mapa`,
    growthTitle: 'Áreas jovens',
    growthHeading: 'Ritmo dos Resgatados',
    focusLabel: 'Desafio da semana',
    focusTitle: 'Memorizar um versículo e responder o desafio bíblico.',
    focusSuggestion: 'Escolha um versículo, escreva uma aplicação e compartilhe no encontro.',
    activitiesTitle: 'Missões recentes',
    activitiesHeading: 'Movimentos dos Resgatados',
    achievementsTitle: 'Conquistas jovens',
    growthItems: [
      { icon: '🔥', name: 'Presença nos Resgatados', percent: 88, points: 210, note: 'Constância nos encontros jovens.' },
      { icon: '📖', name: 'Bíblia na mão', percent: 76, points: 165, note: 'Palavra como fundamento.' },
      { icon: '🧠', name: 'Desafios bíblicos', percent: 68, points: 138, note: 'Aprendizado em movimento.' },
      { icon: '🤝', name: 'Serviço jovem', percent: 62, points: 116, note: 'Servindo com propósito.' },
      { icon: '👥', name: 'Comunhão', percent: 74, points: 124, note: 'Crescendo com a geração.' },
      { icon: '🕊️', name: 'Evangelismo', percent: 46, points: 82, note: 'Chamando outros para perto.' },
      { icon: '🌍', name: 'Missões', percent: 40, points: 85, note: 'Propósito além de si.' },
    ],
    activities: [
      { name: 'Encontro dos Resgatados', points: 55 },
      { name: 'Desafio bíblico respondido', points: 35 },
      { name: 'Versículo memorizado', points: 28 },
      { name: 'Missão jovem concluída', points: 45 },
    ],
    achievements: ['Resgatado Presente', 'Bíblia na Mão', 'Chama Jovem', 'Desafio Cumprido'],
    notice: 'Esta jornada é separada da caminhada geral. Os pontos jovens não se misturam com os pontos gerais.',
  },
}

// Mock temporário do histórico: backend futuro deve enviar registros reais vindos de presença, leitura, devocional, serviço, visitantes, jovens, conquistas e ajustes administrativos. Jovens/resgatados possuem histórico jovem separado do histórico geral; frontend não é segurança final.
const historyFilters = ['Todos', 'Caminhada Geral', 'Caminhada Jovem', 'Presença', 'Palavra', 'Devocional', 'Serviço', 'Evangelismo', 'Conquistas']
const selectedHistoryFilter = ref('Todos')
const historySummaryCards = [
  { label: 'Pontos gerais', value: '380 pts', note: 'Confirmados na caminhada geral', scope: 'general' },
  { label: 'Pontos jovens', value: '920 pts', note: 'Separados para Resgatados', scope: 'youth' },
  { label: 'Ações registradas', value: '18', note: 'Entre presença, Palavra e serviço' },
  { label: 'Conquistas', value: '9', note: 'Badges e marcos desbloqueados' },
]
const historySupportStats = [
  { label: 'Pontos confirmados', value: '380 pts', scope: 'general' },
  { label: 'Pontos em validação', value: '80 pts', scope: 'general' },
  { label: 'Conquistas desbloqueadas', value: '9' },
]
const historyEvents = [
  { date: '15 mai', title: 'Presença no culto de domingo', journey: 'Geral', category: 'Presença', points: 40, status: 'Confirmado', statusKey: 'confirmed', description: 'Registro confirmado de participação no culto principal da família espiritual.' },
  { date: '14 mai', title: 'Encontro dos Resgatados', journey: 'Jovem', category: 'Presença', points: 55, status: 'Confirmado', statusKey: 'confirmed', description: 'Participação no encontro jovem com comunhão, Palavra e acompanhamento.' },
  { date: '13 mai', title: 'Desafio bíblico respondido', journey: 'Jovem', category: 'Palavra', points: 35, status: 'Em validação', statusKey: 'pending', description: 'Resposta enviada para validação da liderança dos Resgatados.' },
  { date: '12 mai', title: 'Leitura bíblica do dia', journey: 'Geral', category: 'Palavra', points: 18, status: 'Confirmado', statusKey: 'confirmed', description: 'Leitura diária registrada como avanço na área da Palavra.' },
  { date: '11 mai', title: 'Badge Palavra Viva desbloqueada', journey: 'Geral', category: 'Conquistas', points: 80, status: 'Conquista', statusKey: 'achievement', description: 'Conquista liberada por constância em leitura e reflexão bíblica.' },
  { date: '10 mai', title: 'Versículo memorizado', journey: 'Jovem', category: 'Devocional', points: 28, status: 'Confirmado', statusKey: 'confirmed', description: 'Versículo apresentado no desafio jovem da semana.' },
  { date: '09 mai', title: 'Serviço em escala', journey: 'Geral', category: 'Serviço', points: 35, status: 'Confirmado', statusKey: 'confirmed', description: 'Serviço registrado em escala ministerial da casa.' },
  { date: '08 mai', title: 'Visitante confirmado', journey: 'Geral', category: 'Evangelismo', points: 45, status: 'Ajustado', statusKey: 'adjusted', description: 'Registro revisado pela administração após confirmação do visitante.' },
  { date: '07 mai', title: 'Missão jovem concluída', journey: 'Jovem', category: 'Evangelismo', points: 45, status: 'Em validação', statusKey: 'pending', description: 'Missão enviada para conferência da liderança jovem.' },
  { date: '06 mai', title: 'Nível Coração Desperto alcançado', journey: 'Geral', category: 'Conquistas', points: 120, status: 'Conquista', statusKey: 'achievement', description: 'Marco espiritual alcançado na caminhada geral.' },
]

// Futuro backend:
// O Mentor da Caminhada deve começar como inteligência gratuita por regras,
// usando tipos de análise, respostas pré-aprovadas e histórico de respostas
// para evitar repetição por pessoa e família.
// IA externa real poderá ser estudada somente em fase futura.
const mentorWeeklyReading = {
  title: 'Leitura da semana',
  text: 'Você tem demonstrado constância em presença e serviço. O próximo passo é fortalecer devocional e Palavra no ritmo da semana.',
  fields: [
    { label: 'Área forte', value: 'Presença e serviço' },
    { label: 'Área a fortalecer', value: 'Devocional pessoal' },
    { label: 'Ritmo da semana', value: 'Estável' },
    { label: 'Próximo marco', value: 'Semente da Palavra' },
  ],
}
const mentorPlanSteps = [
  { category: 'Palavra', action: 'Ler 1 capítulo por dia', impact: 'Fortalece fundamento bíblico e clareza para a semana.', status: 'Sugerido', statusKey: 'suggested' },
  { category: 'Devocional', action: 'Registrar 3 devocionais na semana', impact: 'Cria constância sem peso e ajuda a perceber crescimento real.', status: 'Em andamento', statusKey: 'progress' },
  { category: 'Serviço', action: 'Confirmar disponibilidade em uma escala ou serviço', impact: 'Transforma maturidade em cuidado prático pela casa.', status: 'Sugerido', statusKey: 'suggested' },
  { category: 'Comunhão', action: 'Participar de um momento de comunhão da igreja', impact: 'Mantém vínculos, ensino e alinhamento com a família espiritual.', status: 'Sugerido', statusKey: 'suggested' },
]
const mentorJourneyFocusCards = [
  { journey: 'Caminhada Geral', focus: 'Presença, Palavra e constância.', note: 'Seu mentor acompanha presença, Palavra, devocional, serviço, comunhão, evangelismo e conquistas gerais.' },
  { journey: 'Caminhada Jovem', focus: 'Desafio bíblico e presença nos Resgatados.', note: 'Jornada jovem acompanhada separadamente da caminhada geral.', youthOnly: true },
]
const mentorGuidedQuestions = [
  'Como posso melhorar minha constância?',
  'Qual área da minha caminhada geral precisa de mais atenção?',
  'Como crescer em leitura bíblica esta semana?',
  'Qual próximo passo combina com meu momento?',
  'Como servir melhor sem perder equilíbrio?',
]
const mentorHowItWorks = {
  title: 'Como este mentor funciona',
  text: 'O Mentor da Caminhada usa registros da caminhada geral, como presença, Palavra, devocional, serviço, comunhão, evangelismo e conquistas, para sugerir próximos passos simples.',
  note: 'Nesta primeira fase, ele funciona por regras pastorais e respostas pré-aprovadas, sem IA externa real. Não julga espiritualidade, não substitui pastor/liderança e futuramente poderá ter orientação específica para jovens autorizados.',
}
const mentorCareLimits = [
  'Não substitui pastor, liderança, discipulado ou aconselhamento pastoral.',
  'Não diagnostica emoções nem trata crises graves sozinho.',
  'Não julga espiritualidade, acusa falta de fé ou promete resultado espiritual.',
  'Não expõe comparações entre pessoas ou famílias.',
]
const mentorVisibleJourneyFocusCards = computed(() => mentorJourneyFocusCards.filter((card) => !card.youthOnly || viewerContext.canSeeYouthJourney))

const rulesPrinciplePillars = ['Encorajar, não comparar', 'Acompanhar, não expor', 'Crescer, não competir']
const rulesJourneyCards = [
  {
    scope: 'general',
    icon: '⛪',
    title: 'Caminhada Geral da Igreja',
    text: 'Esta jornada acompanha a constância geral da pessoa na vida da igreja.',
    criteriaTitle: 'Critérios gerais',
    criteria: [
      { icon: '⛪', name: 'Presença', text: 'Cultos e atividades confirmadas.' },
      { icon: '📖', name: 'Palavra', text: 'Leitura bíblica e registros.' },
      { icon: '🕯️', name: 'Devocional', text: 'Constância de reflexão.' },
      { icon: '🤝', name: 'Serviço', text: 'Escalas e ministérios.' },
      { icon: '👥', name: 'Comunhão', text: 'Vida com a família da fé.' },
      { icon: '🕊️', name: 'Evangelismo', text: 'Visitantes confirmados.' },
      { icon: '🎓', name: 'Formação', text: 'Estudos e discipulado.' },
      { icon: '🙏', name: 'Intercessão', text: 'Quando aplicável, com cuidado pastoral.' },
    ],
    notes: ['Pontos gerais', 'Níveis gerais', 'Conquistas gerais', 'Não depende dos desafios dos Resgatados'],
  },
  {
    scope: 'youth',
    icon: '🔥',
    title: 'Caminhada Jovem dos Resgatados',
    text: 'Esta jornada acompanha a participação dos jovens/resgatados nos encontros, desafios, Palavra, missões e ações próprias dos Resgatados.',
    criteriaTitle: 'Critérios jovens',
    criteria: [
      { icon: '🔥', name: 'Presença', text: 'Encontros dos Resgatados.' },
      { icon: '📖', name: 'Bíblia na mão', text: 'Palavra na jornada jovem.' },
      { icon: '🧠', name: 'Desafios bíblicos', text: 'Aprendizado em movimento.' },
      { icon: '🤝', name: 'Serviço jovem', text: 'Ações dos Resgatados.' },
      { icon: '👥', name: 'Comunhão jovem', text: 'Crescimento em grupo.' },
      { icon: '🕊️', name: 'Evangelismo jovem', text: 'Alcance da geração.' },
      { icon: '🌍', name: 'Missões', text: 'Propósito em ação.' },
    ],
    notes: ['Pontos jovens', 'Níveis jovens', 'Conquistas jovens', 'Não mistura com a Caminhada Geral'],
  },
]
const rulesYouthTrackNote = {
  icon: '🔥',
  title: 'Trilho jovem próprio',
  text: 'Os Resgatados possuem regras específicas no módulo jovem, com desafios, Palavra, missões e crescimento acompanhados separadamente.',
  notes: ['Não mistura com a Caminhada Geral', 'Visível apenas para perfis autorizados', 'Jovens e responsáveis conforme permissão'],
}
const rulesDefaultSeparationNote = 'A Caminhada Geral possui pontos, níveis e conquistas próprias. Trilhos jovens e equipes dos Resgatados pertencem ao módulo jovem e aparecem somente para perfis autorizados.'
const rulesFullSeparationNote = 'Os pontos gerais e jovens são separados. Um jovem pode caminhar nas duas jornadas, mas cada uma tem seus próprios critérios, níveis, marcos e conquistas.'
const rulesSeparationTracks = [
  { icon: '⛪', label: 'Caminhada Geral', value: 'pontos gerais', scope: 'general' },
  { icon: '🔥', label: 'Resgatados', value: 'trilho próprio no módulo jovem, conforme permissão', scope: 'youth-note' },
  { icon: '🔥', label: 'Caminhada Jovem', value: 'pontos jovens', scope: 'youth' },
  { icon: '🤝', label: 'Equipes dos Resgatados', value: 'pontos coletivos', scope: 'team' },
]
const rulesGuideCards = [
  { icon: '🗺️', title: 'Marcos da caminhada', text: 'Cada nível geral representa um marco simbólico, não superioridade.', actions: [{ label: 'Mapa geral', route: `${baseRoute}/geral/mapa`, scope: 'general' }, { label: 'Mapa do trilho jovem', route: `${baseRoute}/jovem/mapa`, scope: 'youth' }] },
  { icon: '🏅', title: 'Conquistas', text: 'Badges reconhecem constância e serviço sem envergonhar.', actions: [{ label: 'Ver conquistas', route: `${baseRoute}/conquistas` }] },
  { icon: '✅', title: 'Validação', text: 'Alguns registros podem ser automáticos; outros precisam de confirmação da Secretaria ou liderança.', statuses: ['Confirmado', 'Em validação', 'Ajustado'] },
]
const rulesValidationStatuses = ['Confirmado', 'Em validação', 'Ajustado', 'Cancelado', 'Conquista desbloqueada']
const rulesNotList = [
  'ranking de espiritualidade',
  'comparação entre pessoas',
  'substituto de discipulado',
  'medida de valor diante de Deus',
  'competição de serviço',
]
const rulesForList = [
  'encorajar',
  'acompanhar',
  'orientar próximos passos',
  'celebrar constância',
  'cuidar melhor',
]

// Futuro backend:
// Este contexto deve vir das permissões reais.
// Membro comum vê apenas Caminhada Geral e Destaques Gerais.
// Jovem/Resgatado vê Caminhada Geral + Caminhada Jovem.
// Equipes jovens aparecem apenas no módulo jovem ou para autorizados.
// Responsáveis veem dados jovens apenas dos filhos vinculados.
// Administração/liderança vê conforme policy.
// O Mentor não substitui pastor, liderança, discipulado ou aconselhamento pastoral.
// Pontos gerais, jovens e de equipe jovem são separados.
// Intercessores podem ter pontuação fixa mensal e pontuação por atendimento,
// com avaliação privada convertida em no máximo 3 pontos.
// Destaques não medem espiritualidade.
const viewerContext = {
  profileType: 'member',
  canSeeGeneralJourney: true,
  canSeeYouthJourney: false,
  canSeeGeneralHighlights: true,
  canSeeYouthHighlights: false,
  canSeeYouthTeams: false,
  youthMember: false,
  youthLeader: false,
  isGuardian: false,
  isAdmin: false,
  isPastoralLeadership: false,
}
const rankingPrinciples = ['Honrar sem comparar', 'Reconhecer sem expor', 'Encorajar sem competir']
const baseRankingFilters = ['Todos', 'Gerais', 'Presença', 'Palavra', 'Serviço', 'Evangelismo', 'Conquistas']
const youthRankingFilters = ['Resgatados']
const teamRankingFilters = ['Equipes']
const selectedRankingFilter = ref('Todos')
const rankingSeparationTracks = [
  { title: 'Caminhada Geral', result: 'destaques gerais', text: 'Membro comum visualiza reconhecimentos gerais da igreja.' },
  { title: 'Permissões futuras', result: 'jovens e equipes', text: 'Destaques jovens e equipes aparecem somente para perfis autorizados.' },
  { title: 'Sem mistura', result: 'trilhos separados', text: 'Pontos gerais, jovens e coletivos não se misturam.' },
]
const rankingGeneralHighlights = [
  { icon: '⛪', title: 'Constância em Presença', name: 'Ana Ribeiro', reason: 'Participação constante nos cultos e encontros.', journey: 'Caminhada Geral', category: 'Presença', seal: 'Presença Fiel' },
  { icon: '🤝', title: 'Serviço Disponível', name: 'Marcos Silva', reason: 'Apoio em escalas e ministérios.', journey: 'Caminhada Geral', category: 'Serviço', seal: 'Servo Disponível' },
  { icon: '📖', title: 'Palavra em Crescimento', name: 'Júlia Santos', reason: 'Constância em leitura bíblica e devocionais.', journey: 'Caminhada Geral', category: 'Palavra', seal: 'Palavra Viva' },
  { icon: '🕊️', title: 'Esperança Compartilhada', name: 'Beatriz Costa', reason: 'Cuidado com visitantes e convites feitos com amor.', journey: 'Caminhada Geral', category: 'Evangelismo', seal: 'Voz de Esperança' },
  { icon: '✅', title: 'Marco Celebrado', name: 'Pedro Martins', reason: 'Conquista desbloqueada por constância e pequenos passos.', journey: 'Caminhada Geral', category: 'Conquistas', seal: 'Caminho Firme' },
]
const rankingYouthHighlights = [
  { icon: '🔥', title: 'Jovem em Movimento', name: 'Lucas Almeida', reason: 'Participação nos Resgatados, desafios bíblicos e comunhão.', journey: 'Caminhada Jovem', category: 'Resgatados', seal: 'Chama Jovem' },
  { icon: '📖', title: 'Bíblia na Mão', name: 'Daniel Paulo', reason: 'Constância nos desafios e leitura bíblica jovem.', journey: 'Caminhada Jovem', category: 'Palavra', seal: 'Bíblia na Mão' },
  { icon: '🌍', title: 'Missão em Movimento', name: 'Lara Costa', reason: 'Participação em missões e ações dos Resgatados.', journey: 'Caminhada Jovem', category: 'Evangelismo', seal: 'Missão em Movimento' },
]
const rankingGeneralCriteria = ['Presença', 'Palavra', 'Devocional', 'Serviço', 'Comunhão', 'Evangelismo', 'Formação', 'Intercessão, quando aplicável']
const rankingYouthCriteria = ['Presença nos Resgatados', 'Bíblia na mão', 'Desafios bíblicos', 'Serviço jovem', 'Comunhão jovem', 'Evangelismo jovem', 'Missões']
const rankingTeamBadges = [
  { icon: '🌍', title: 'Missão em equipe', text: 'Ações coletivas dos Resgatados com propósito.' },
  { icon: '🧠', title: 'Desafio coletivo', text: 'Desafios bíblicos cumpridos em equipe.' },
  { icon: '🤲', title: 'Acolhimento jovem', text: 'Cuidado coletivo com novos jovens.' },
  { icon: '🤝', title: 'Serviço coletivo', text: 'Serviço jovem acompanhado como equipe.' },
]
const rankingSummaryCards = [
  { label: 'Pessoas reconhecidas', value: '12', note: 'Com cuidado e sem exposição' },
  { label: 'Selos entregues', value: '18', note: 'Reconhecimentos saudáveis' },
  { label: 'Trilho visível', value: 'Geral', note: 'Padrão de membro comum' },
  { label: 'Permissões futuras', value: 'Policies', note: 'Jovens e equipes por autorização' },
]
const rankingRecognitionAreas = [
  { icon: '⛪', title: 'Presença', text: 'Constância em cultos, encontros e atividades confirmadas.' },
  { icon: '📖', title: 'Palavra', text: 'Leitura bíblica e crescimento no fundamento.' },
  { icon: '🕯️', title: 'Devocional', text: 'Pequenos passos de reflexão e prática.' },
  { icon: '🤝', title: 'Serviço', text: 'Mãos disponíveis em escalas e ministérios.' },
  { icon: '👥', title: 'Comunhão', text: 'Caminhada junto da família da fé.' },
  { icon: '🕊️', title: 'Evangelismo', text: 'Cuidado com visitantes e alcance saudável.' },
  { icon: '🎓', title: 'Formação', text: 'Estudos, discipulado e maturidade.' },
  { icon: '🙏', title: 'Intercessão', text: 'Categoria geral futura com avaliação privada e cuidado pastoral.' },
]
const rankingYouthBadges = [
  { icon: '📖', title: 'Bíblia na Mão', text: 'Palavra presente nos encontros e desafios.' },
  { icon: '🧠', title: 'Desafio Cumprido', text: 'Resposta bíblica acompanhada com cuidado.' },
  { icon: '🔥', title: 'Chama Jovem', text: 'Constância e comunhão nos Resgatados.' },
  { icon: '🌍', title: 'Missão em Movimento', text: 'Ações jovens com propósito e responsabilidade.' },
]
const rankingCareItems = [
  'Não é ranking de espiritualidade.',
  'Não deve gerar exposição ou vergonha.',
  'Não substitui cuidado pastoral.',
  'Não transforma serviço em disputa.',
  'Não compara famílias ou pessoas.',
  'Não compara jovens com adultos.',
  'Não mistura pontos individuais com pontos de equipe.',
]

const isLevelArea = computed(() => props.area === 'nivel')
const isJourneyDetailArea = computed(() => props.area === 'geral' || props.area === 'jovem')
const isHistoryArea = computed(() => props.area === 'historico')
const isMentorArea = computed(() => props.area === 'mentor')
const isRulesArea = computed(() => props.area === 'regras')
const isRankingArea = computed(() => props.area === 'ranking')
const journeySlug = computed(() => props.journey === 'jovem' ? 'jovem' : 'geral')
const journeyDetailSlug = computed(() => props.area === 'jovem' ? 'jovem' : 'geral')
const journeyConfig = computed(() => journeyConfigs[journeySlug.value])
const journeyDashboard = computed(() => journeyDashboardMocks[journeyDetailSlug.value])
const areaTitle = computed(() => isLevelArea.value ? 'Meu Nível Atual' : isRankingArea.value ? 'Destaques da Caminhada' : isRulesArea.value ? 'Regras da Caminhada' : isMentorArea.value ? 'Mentor da Caminhada' : isHistoryArea.value ? 'Histórico da Caminhada' : isJourneyDetailArea.value ? journeyDashboard.value.title : journeyConfig.value.title)
const areaBreadcrumb = computed(() => isLevelArea.value ? 'Meu Nível Atual' : isRankingArea.value ? 'Destaques' : isRulesArea.value ? 'Regras' : isMentorArea.value ? 'Mentor' : isHistoryArea.value ? 'Histórico' : isJourneyDetailArea.value ? journeyDashboard.value.breadcrumb : journeyConfig.value.breadcrumb)
const areaSubtitle = computed(() => isLevelArea.value ? 'Veja onde você está na sua caminhada e qual é o próximo marco da sua jornada.' : isRankingArea.value ? 'Reconheça constância, serviço, participação e crescimento sem transformar a fé em competição.' : isRulesArea.value ? 'Entenda como a pontuação, os níveis, as conquistas e os marcos espirituais ajudam a acompanhar sua constância com equilíbrio e cuidado.' : isMentorArea.value ? 'Receba uma orientação simples e pastoral com base na sua caminhada geral, seus registros e seus próximos passos.' : isHistoryArea.value ? 'Acompanhe as ações, pontos, conquistas e registros que formam sua jornada espiritual.' : isJourneyDetailArea.value ? journeyDashboard.value.subtitle : journeyConfig.value.subtitle)
const visibleRulesJourneyCards = computed(() => rulesJourneyCards.filter((card) => card.scope === 'general' || viewerContext.canSeeYouthJourney))
const showRulesYouthTrackNote = computed(() => !viewerContext.canSeeYouthJourney)
const rulesSeparationNote = computed(() => viewerContext.canSeeYouthJourney ? rulesFullSeparationNote : rulesDefaultSeparationNote)
const visibleRulesSeparationTracks = computed(() => rulesSeparationTracks.filter((track) => {
  if (track.scope === 'general') {
    return viewerContext.canSeeGeneralJourney
  }

  if (track.scope === 'youth-note') {
    return !viewerContext.canSeeYouthJourney
  }

  if (track.scope === 'youth') {
    return viewerContext.canSeeYouthJourney
  }

  return viewerContext.canSeeYouthTeams
}))
const visibleRulesGuideCards = computed(() => rulesGuideCards.map((card) => ({
  ...card,
  actions: card.actions?.filter((action) => !action.scope || action.scope === 'general' || viewerContext.canSeeYouthJourney),
})))
const rankingFilters = computed(() => [
  ...baseRankingFilters,
  ...(viewerContext.canSeeYouthHighlights ? youthRankingFilters : []),
  ...(viewerContext.canSeeYouthTeams ? teamRankingFilters : []),
])
const visibleRankingGeneralHighlights = computed(() => {
  if (!viewerContext.canSeeGeneralHighlights) {
    return []
  }

  if (selectedRankingFilter.value === 'Todos' || selectedRankingFilter.value === 'Gerais') {
    return rankingGeneralHighlights
  }

  return rankingGeneralHighlights.filter((highlight) => highlight.category === selectedRankingFilter.value || highlight.seal === selectedRankingFilter.value)
})
const visibleRankingYouthHighlights = computed(() => {
  if (!viewerContext.canSeeYouthHighlights) {
    return []
  }

  if (selectedRankingFilter.value === 'Todos' || selectedRankingFilter.value === 'Resgatados') {
    return rankingYouthHighlights
  }

  return rankingYouthHighlights.filter((highlight) => highlight.category === selectedRankingFilter.value || highlight.seal === selectedRankingFilter.value)
})
const hasVisibleRankingGeneralHighlights = computed(() => visibleRankingGeneralHighlights.value.length > 0)
const hasVisibleRankingYouthHighlights = computed(() => visibleRankingYouthHighlights.value.length > 0)
const showRankingGeneralSection = computed(() => viewerContext.canSeeGeneralHighlights && (selectedRankingFilter.value === 'Todos' || selectedRankingFilter.value === 'Gerais' || (selectedRankingFilter.value !== 'Resgatados' && selectedRankingFilter.value !== 'Equipes' && hasVisibleRankingGeneralHighlights.value)))
const showRankingYouthSection = computed(() => viewerContext.canSeeYouthHighlights && (selectedRankingFilter.value === 'Todos' || selectedRankingFilter.value === 'Resgatados' || (selectedRankingFilter.value !== 'Gerais' && selectedRankingFilter.value !== 'Equipes' && hasVisibleRankingYouthHighlights.value)))
const showRankingTeamsSection = computed(() => viewerContext.canSeeYouthTeams && (selectedRankingFilter.value === 'Todos' || selectedRankingFilter.value === 'Equipes'))
const visibleHistoryFilters = computed(() => historyFilters.filter((filter) => filter !== 'Caminhada Jovem' || viewerContext.canSeeYouthJourney))
const visibleHistorySummaryCards = computed(() => historySummaryCards.filter((card) => card.scope !== 'youth' || viewerContext.canSeeYouthJourney))
const visibleHistorySupportStats = computed(() => historySupportStats.filter((stat) => stat.scope !== 'youth' || viewerContext.canSeeYouthJourney))
const visibleHistoryEvents = computed(() => {
  const allowedEvents = historyEvents.filter((event) => event.journey !== 'Jovem' || viewerContext.canSeeYouthJourney)

  if (selectedHistoryFilter.value === 'Todos') {
    return allowedEvents
  }

  if (selectedHistoryFilter.value === 'Caminhada Geral') {
    return allowedEvents.filter((event) => event.journey === 'Geral')
  }

  if (selectedHistoryFilter.value === 'Caminhada Jovem') {
    return allowedEvents.filter((event) => event.journey === 'Jovem')
  }

  return allowedEvents.filter((event) => event.category === selectedHistoryFilter.value)
})
const hasRealMapData = computed(() => Boolean(props.walkingMap?.usesRealData))
const requestedMapJourneyType = computed(() => props.walkingMap?.requestedJourneyType === 'youth' || props.journey === 'jovem' ? 'youth' : 'general')
const canSeeYouthJourneyFromMap = computed(() => hasRealMapData.value ? Boolean(props.walkingMap?.canSeeYouthJourney) : viewerContext.canSeeYouthJourney)
const activeMapJourney = computed(() => requestedMapJourneyType.value === 'youth' ? 'youth' : 'general')
const currentMapData = computed(() => hasRealMapData.value ? props.walkingMap?.[activeMapJourney.value] || null : null)
const mapAuthorized = computed(() => Boolean(hasRealMapData.value && props.walkingMap?.authorized && currentMapData.value?.authorized))
const mapHeading = computed(() => {
  if (hasRealMapData.value) {
    if (!mapAuthorized.value) {
      return requestedMapJourneyType.value === 'youth' ? 'Mapa jovem indisponível' : 'Mapa da caminhada indisponível'
    }

    return requestedMapJourneyType.value === 'youth' ? 'Mapa Jovem dos Resgatados' : 'Mapa da Caminhada Geral'
  }

  return journeySlug.value === 'jovem' ? '20 marcos jovens, 20 estações de propósito' : '20 igrejas, 20 marcos espirituais'
})
const mapDescription = computed(() => {
  if (hasRealMapData.value) {
    if (!mapAuthorized.value) {
      return 'Este mapa só aparece quando a jornada está disponível e autorizada para o usuário autenticado.'
    }

    return 'Mapa calculado com níveis oficiais e pontos aprovados reais, sem dados simulados.'
  }

  return journeySlug.value === 'jovem' ? 'Cada estação representa presença, Palavra, comunhão e propósito na geração jovem.' : 'Cada igreja representa uma estação de presença, Palavra, serviço, comunhão e constância.'
})
const areaLevels = computed(() => journeySlug.value === 'jovem' ? youthLevels : generalLevels)
const currentLevelName = computed(() => areaLevels.value.find((level) => level.number === journeyConfig.value.currentLevel)?.name || areaLevels.value[0].name)
const hasRealLevelData = computed(() => Boolean(props.walkingLevel?.usesRealData))
const canSeeYouthJourney = computed(() => hasRealLevelData.value ? Boolean(props.walkingLevel?.canSeeYouthJourney) : viewerContext.canSeeYouthJourney)
const activeLevelJourney = computed(() => props.walkingLevel?.defaultJourneyType === 'youth' && canSeeYouthJourney.value ? 'youth' : 'general')
const formatNumber = (value) => Number(value || 0).toLocaleString('pt-BR')
const buildRealLevelCard = (journey, type) => {
  const currentLevel = journey?.currentLevel || null
  const nextLevel = journey?.nextLevel || null
  const points = Number(journey?.points || 0)
  const hasPoints = points > 0
  const slug = type === 'youth' ? 'jovem' : 'geral'
  const label = type === 'youth' ? 'Jornada jovem' : 'Jornada geral'

  return {
    slug,
    title: journey?.journey?.name || (type === 'youth' ? 'Caminhada Jovem' : 'Caminhada Geral'),
    label,
    icon: type === 'youth' ? '🔥' : '✚',
    description: hasPoints
      ? 'Nível calculado a partir dos pontos aprovados nesta caminhada.'
      : 'Ponto de partida da caminhada enquanto ainda não há pontos aprovados.',
    authorized: Boolean(journey?.authorized),
    points,
    approvedLogsCount: Number(journey?.approvedLogsCount || 0),
    hasPoints,
    currentLevelNumber: currentLevel?.number || null,
    currentLevelName: currentLevel?.name || 'Ponto de partida',
    currentLevelDescription: currentLevel?.description || null,
    nextLevelName: nextLevel?.name || 'Marco final da jornada',
    nextLevelRequiredPoints: Number(nextLevel?.requiredPoints || 0),
    pointsToNextLevel: Number(journey?.pointsToNextLevel || 0),
    progress: Number(journey?.progressPercent || 0),
    recentLogs: Array.isArray(journey?.recentLogs) ? journey.recentLogs : [],
  }
}
const levelJourneyCards = computed(() => {
  if (!hasRealLevelData.value || !props.walkingLevel?.authorized) {
    return []
  }

  const cards = []

  if (props.walkingLevel?.general?.authorized) {
    cards.push(buildRealLevelCard(props.walkingLevel.general, 'general'))
  }

  if (canSeeYouthJourney.value && props.walkingLevel?.youth?.authorized) {
    cards.push(buildRealLevelCard(props.walkingLevel.youth, 'youth'))
  }

  return cards
})
const currentLevelData = computed(() => props.walkingLevel?.[activeLevelJourney.value] || props.walkingLevel?.general || null)
const levelSummaryCards = computed(() => {
  if (!hasRealLevelData.value || !props.walkingLevel?.authorized || !currentLevelData.value?.authorized) {
    return []
  }

  return [
    { label: 'Pontos aprovados', value: `${formatNumber(currentLevelData.value.points)} pts`, note: 'Somente registros aprovados entram neste total.' },
    { label: 'Nível atual', value: currentLevelData.value.currentLevel?.name || 'Ponto de partida', note: currentLevelData.value.points > 0 ? `Nível ${currentLevelData.value.currentLevel?.number || 1}` : 'Ainda sem avanço por pontos aprovados.' },
    { label: 'Próximo nível', value: currentLevelData.value.nextLevel?.name || 'Marco final', note: currentLevelData.value.nextLevel ? `Faltam ${formatNumber(currentLevelData.value.pointsToNextLevel)} pts` : 'Não há próximo nível cadastrado.' },
    { label: 'Registros aprovados', value: formatNumber(currentLevelData.value.approvedLogsCount), note: 'Logs recentes aparecem sem metadata sensível.' },
  ]
})
const recentLevelLogs = computed(() => Array.isArray(currentLevelData.value?.recentLogs) ? currentLevelData.value.recentLogs : [])
const levelEmptyState = computed(() => {
  const states = props.walkingLevel?.emptyStates || {}

  if (!props.walkingLevel?.authorized) {
    return {
      title: states.withoutPersonTitle || 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
      text: states.withoutPersonText || 'Assim que o cadastro for vinculado, seu nível real aparecerá aqui.',
    }
  }

  if (!currentLevelData.value?.authorized) {
    return {
      title: states.withoutJourneyTitle || 'Caminhada indisponível no momento.',
      text: states.withoutJourneyText || 'Assim que a jornada estiver disponível, o nível real aparecerá aqui.',
    }
  }

  if (!currentLevelData.value?.summary?.has_points) {
    return {
      title: states.withoutPointsTitle || 'Ainda não há pontos aprovados nesta caminhada.',
      text: states.withoutPointsText || 'Quando houver registros aprovados, seu nível começará a avançar.',
    }
  }

  return null
})
const fallbackMapLevels = computed(() => areaLevels.value.map((level) => {
  const config = journeyConfig.value
  const pointsRequired = level.number * config.pointStep
  const status = level.number < config.currentLevel
    ? 'completed'
    : level.number === config.currentLevel
      ? 'current'
      : level.number <= config.currentLevel + 3
        ? 'future'
        : 'locked'

  return {
    ...level,
    status,
    statusLabel: statusLabels[status],
    statusNote: statusNotes[status],
    pointsRequired,
    route: `${baseRoute}/${config.slug}/niveis/${level.number}`,
    progressText: status === 'completed' ? `${pointsRequired} pts alcançados` : `${Math.min(config.points, pointsRequired)} / ${pointsRequired} pontos`,
  }
}))
const mapLevels = computed(() => {
  if (hasRealMapData.value) {
    return Array.isArray(currentMapData.value?.levels) ? currentMapData.value.levels.map((level) => ({
      ...level,
      statusLabel: statusLabels[level.status] || level.status,
      statusNote: level.description || statusNotes[level.status] || 'Marco da caminhada real.',
      progressText: level.isCompleted
        ? `${formatNumber(level.requiredPoints)} pts necessários`
        : level.isCurrent
          ? `${formatNumber(currentMapData.value?.points || 0)} pontos aprovados`
          : level.isNext
            ? `Faltam ${formatNumber(level.pointsToReach)} pts`
            : `${formatNumber(level.requiredPoints)} pts necessários`,
    })) : []
  }

  return fallbackMapLevels.value
})
const mapSummaryCards = computed(() => {
  if (!mapAuthorized.value) {
    return []
  }

  return [
    { label: 'Nível atual', value: currentMapData.value.currentLevel?.number || 'Início', note: currentMapData.value.currentLevel?.name || 'Ponto de partida' },
    { label: 'Pontos aprovados', value: formatNumber(currentMapData.value.points), note: 'Somente registros aprovados entram neste total.' },
    { label: 'Marcos concluídos', value: formatNumber(currentMapData.value.summary?.completed_count || 0), note: 'Calculado pelos níveis oficiais.' },
    { label: 'Próximo marco', value: currentMapData.value.nextLevel?.name || 'Marco final', note: currentMapData.value.nextLevel ? `Faltam ${formatNumber(currentMapData.value.pointsToNextLevel)} pts` : 'Sem próximo nível cadastrado.' },
  ]
})
const mapRecentLogs = computed(() => Array.isArray(currentMapData.value?.recentLogs) ? currentMapData.value.recentLogs : [])
const mapEmptyState = computed(() => {
  const states = props.walkingMap?.emptyStates || {}

  if (!hasRealMapData.value) {
    return null
  }

  if (!props.walkingMap?.authorized) {
    return {
      title: states.withoutPersonTitle || 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
      text: states.withoutPersonText || 'Assim que o cadastro for vinculado, o mapa da sua caminhada real aparecerá aqui.',
    }
  }

  if (!currentMapData.value?.authorized) {
    return {
      title: requestedMapJourneyType.value === 'youth' ? states.unauthorizedYouthTitle || 'Mapa jovem indisponível para este perfil.' : states.withoutJourneyTitle || 'Mapa indisponível no momento.',
      text: currentMapData.value?.message || (requestedMapJourneyType.value === 'youth' ? states.unauthorizedYouthText || 'A caminhada jovem aparece somente para jovens/resgatados autorizados.' : states.withoutJourneyText || 'Assim que a jornada estiver disponível, o mapa real aparecerá aqui.'),
    }
  }

  if (!currentMapData.value?.summary?.has_levels) {
    return {
      title: states.withoutLevelsTitle || 'Nenhum nível cadastrado para esta jornada.',
      text: states.withoutLevelsText || 'Assim que os níveis oficiais estiverem ativos, o mapa será exibido aqui.',
    }
  }

  if (!currentMapData.value?.summary?.has_points) {
    return {
      title: states.withoutPointsTitle || 'Ainda não há pontos aprovados nesta caminhada.',
      text: states.withoutPointsText || 'O mapa mostrará seu avanço conforme registros reais forem aprovados.',
    }
  }

  return null
})
const progressPercent = computed(() => hasRealMapData.value ? Number(currentMapData.value?.progressPercent || 0) : Math.min(100, Math.round((journeyConfig.value.points / (journeyConfig.value.currentLevel * journeyConfig.value.pointStep)) * 100)))
const completedCount = computed(() => mapLevels.value.filter((level) => level.status === 'completed').length)
const futureCount = computed(() => mapLevels.value.filter((level) => level.status === 'future' || level.status === 'next').length)
const lockedCount = computed(() => mapLevels.value.filter((level) => level.status === 'locked').length)
const mapHeroBadge = computed(() => {
  if (hasRealMapData.value) {
    if (!mapAuthorized.value) {
      return requestedMapJourneyType.value === 'youth' ? 'Acesso jovem protegido' : 'Mapa indisponível'
    }

    return currentMapData.value.currentLevel?.number ? `Nível ${currentMapData.value.currentLevel.number}` : 'Ponto de partida'
  }

  return `Nível ${journeyConfig.value.currentLevel} de 20`
})
</script>

<template>
  <Head :title="areaTitle" />

  <div class="journey-area-page" :class="[`journey-${journeySlug}`, { 'is-level-area': isLevelArea }]">
    <FamilySidebar active-href="/familia-resgate/minha-caminhada" />

    <main class="journey-area-main">
      <header class="area-hero" :class="{ 'is-detail-hero': isJourneyDetailArea || isHistoryArea || isMentorArea || isRulesArea || isRankingArea }">
        <div>
          <p>Central da Família <span>&gt;</span> Minha Caminhada <span>&gt;</span> {{ areaBreadcrumb }}<template v-if="!isLevelArea && !isJourneyDetailArea && !isHistoryArea && !isMentorArea && !isRulesArea && !isRankingArea"> <span>&gt;</span> Mapa</template></p>
          <h1>{{ areaTitle }}</h1>
          <small>{{ areaSubtitle }}</small>
        </div>
        <strong v-if="isLevelArea">{{ canSeeYouthJourney ? 'Jornadas separadas' : 'Jornada geral' }}</strong>
        <strong v-else-if="isJourneyDetailArea">{{ journeyDashboard.label }}</strong>
        <strong v-else-if="isHistoryArea">Histórico completo</strong>
        <strong v-else-if="isMentorArea">Apoio pastoral</strong>
        <strong v-else-if="isRulesArea">Caminhada saudável</strong>
        <strong v-else-if="isRankingArea">Reconhecimento saudável</strong>
        <strong v-else>{{ mapHeroBadge }}</strong>
      </header>

      <section v-if="isLevelArea" class="level-overview" aria-label="Meu nível atual">
        <div v-if="canSeeYouthJourney" class="level-separation-note">
          <span>Jornadas separadas</span>
          <strong>Você acompanha duas jornadas, com pontos gerais e jovens organizados separadamente.</strong>
        </div>

        <div v-if="levelSummaryCards.length" class="level-summary-grid">
          <article v-for="card in levelSummaryCards" :key="card.label">
            <span>{{ card.label }}</span>
            <strong>{{ card.value }}</strong>
            <small>{{ card.note }}</small>
          </article>
        </div>

        <article v-if="levelEmptyState" class="level-empty-state">
          <strong>{{ levelEmptyState.title }}</strong>
          <p>{{ levelEmptyState.text }}</p>
        </article>

        <div v-if="levelJourneyCards.length" class="level-cards">
          <article v-for="card in levelJourneyCards" :key="card.slug" class="level-current-card" :class="{ 'is-youth-card': card.slug === 'jovem' }">
            <header>
              <span>{{ card.label }}</span>
              <h2>{{ card.title }}</h2>
              <p>{{ card.description }}</p>
            </header>

            <div class="level-current-main">
              <div class="level-emblem">
                <span>{{ card.icon }}</span>
                <small>{{ card.currentLevelNumber ? `Nível ${card.currentLevelNumber}` : 'Início' }}</small>
              </div>
              <div>
                <small>Nível atual</small>
                <strong>{{ card.currentLevelName }}</strong>
                <p>{{ formatNumber(card.points) }} pontos aprovados nesta caminhada</p>
              </div>
            </div>

            <div class="level-progress-row">
              <div>
                <span>Progresso do marco</span>
                <strong>{{ card.progress }}%</strong>
              </div>
              <em><i :style="{ width: `${card.progress}%` }"></i></em>
            </div>

            <div class="level-next-row">
              <span>Próximo nível</span>
              <strong>{{ card.nextLevelName }}</strong>
            </div>

            <footer class="level-real-details">
              <span>{{ card.nextLevelRequiredPoints ? `${formatNumber(card.nextLevelRequiredPoints)} pts para o próximo nível` : 'Sem próximo nível cadastrado' }}</span>
              <strong>{{ card.pointsToNextLevel ? `Faltam ${formatNumber(card.pointsToNextLevel)} pts` : 'Progresso completo no marco atual' }}</strong>
            </footer>
          </article>
        </div>

        <section v-if="recentLevelLogs.length" class="level-recent-logs">
          <header>
            <span>Registros recentes</span>
            <h2>Pontos aprovados considerados no nível</h2>
          </header>
          <div>
            <article v-for="log in recentLevelLogs" :key="log.id">
              <strong>{{ log.category }}</strong>
              <small>{{ formatNumber(log.points) }} pts</small>
              <p>{{ log.notes || 'Registro aprovado sem observação pública.' }}</p>
            </article>
          </div>
        </section>

        <nav class="level-extra-actions" aria-label="Ações do nível atual">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
        </nav>
      </section>

      <section v-else-if="isJourneyDetailArea" class="journey-dashboard" :class="{ 'is-youth-dashboard': journeyDashboard.slug === 'jovem' }" :aria-label="journeyDashboard.title">
        <section class="detail-summary-grid" aria-label="Resumo compacto da jornada">
          <article>
            <span>{{ journeyDashboard.slug === 'jovem' ? 'Nível jovem' : 'Nível atual' }}</span>
            <strong>{{ journeyDashboard.currentLevelName }}</strong>
            <small>Nível {{ journeyDashboard.currentLevel }} de 20</small>
          </article>
          <article>
            <span>{{ journeyDashboard.slug === 'jovem' ? 'Pontos jovens' : 'Pontos gerais' }}</span>
            <strong>{{ journeyDashboard.points.toLocaleString('pt-BR') }} / {{ journeyDashboard.pointsTarget.toLocaleString('pt-BR') }}</strong>
            <small>{{ journeyDashboard.progress }}% do próximo marco</small>
          </article>
          <article>
            <span>Próximo marco</span>
            <strong>{{ journeyDashboard.nextLevelName }}</strong>
            <small>Continue avançando com constância</small>
          </article>
          <article>
            <span>{{ journeyDashboard.positionLabel }}</span>
            <strong>{{ journeyDashboard.position }}</strong>
            <small>{{ journeyDashboard.badgeLabel }}: {{ journeyDashboard.badgesCount }}</small>
          </article>
        </section>

        <section class="weekly-focus-card">
          <div>
            <span>{{ journeyDashboard.focusLabel }}</span>
            <h2>{{ journeyDashboard.focusTitle }}</h2>
            <p>{{ journeyDashboard.focusSuggestion }}</p>
          </div>
          <nav aria-label="Ações do foco da semana">
            <Link :href="`${baseRoute}/regras`">Ver regras</Link>
            <Link :href="`${baseRoute}/historico`">Ver histórico</Link>
          </nav>
        </section>

        <section class="dashboard-section compact-analysis-section">
          <header>
            <span>{{ journeyDashboard.growthTitle }}</span>
            <h2>{{ journeyDashboard.growthHeading }}</h2>
          </header>
          <div class="growth-grid">
            <article v-for="item in journeyDashboard.growthItems" :key="item.name" class="growth-card">
              <div>
                <strong>{{ item.name }}</strong>
                <small>{{ item.percent }}%</small>
              </div>
              <p>{{ item.note }}</p>
              <footer>
                <em><i :style="{ width: `${item.percent}%` }"></i></em>
                <small>{{ item.points }} pts</small>
              </footer>
            </article>
          </div>
        </section>

        <section class="dashboard-grid">
          <article class="dashboard-panel">
            <header>
              <span>{{ journeyDashboard.activitiesTitle }}</span>
              <h2>{{ journeyDashboard.activitiesHeading }}</h2>
            </header>
            <div class="activity-list">
              <div v-for="activity in journeyDashboard.activities" :key="activity.name">
                <strong>{{ activity.name }}</strong>
                <small>+{{ activity.points }} pts {{ journeyDashboard.slug === 'jovem' ? 'jovens' : 'gerais' }}</small>
              </div>
            </div>
          </article>

          <article class="dashboard-panel">
            <header>
              <span>Conquistas</span>
              <h2>{{ journeyDashboard.achievementsTitle }}</h2>
            </header>
            <div class="badge-list">
              <strong v-for="achievement in journeyDashboard.achievements" :key="achievement">{{ achievement }}</strong>
            </div>
          </article>
        </section>

        <article v-if="journeyDashboard.notice" class="dashboard-notice">
          <span>Jornada separada</span>
          <strong>{{ journeyDashboard.notice }}</strong>
        </article>

        <nav class="journey-shortcuts" aria-label="Atalhos da jornada">
          <Link :href="journeyDashboard.levelRoute">{{ journeyDashboard.levelButton }}</Link>
          <Link :href="journeyDashboard.mapRoute">{{ journeyDashboard.mapButton }}</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
        </nav>
      </section>

      <section v-else-if="isHistoryArea" class="history-dashboard" aria-label="Histórico da Caminhada">
        <section class="history-filter-card" aria-label="Filtros do histórico">
          <div>
            <span>Filtros visuais</span>
            <strong>{{ selectedHistoryFilter }}</strong>
          </div>
          <nav aria-label="Selecionar filtro do histórico">
            <button
              v-for="filter in visibleHistoryFilters"
              :key="filter"
              type="button"
              :class="{ active: selectedHistoryFilter === filter }"
              @click="selectedHistoryFilter = filter"
            >
              {{ filter }}
            </button>
          </nav>
        </section>

        <section class="history-summary-grid" aria-label="Resumo rápido do histórico">
          <article v-for="card in visibleHistorySummaryCards" :key="card.label">
            <span>{{ card.label }}</span>
            <strong>{{ card.value }}</strong>
            <small>{{ card.note }}</small>
          </article>
        </section>

        <section class="history-content-grid">
          <article class="history-timeline-card">
            <header>
              <span>Linha do tempo</span>
              <h2>Registros recentes da jornada</h2>
            </header>

            <div class="history-timeline">
              <article
                v-for="event in visibleHistoryEvents"
                :key="`${event.date}-${event.title}`"
                class="history-event"
                :class="[`status-${event.statusKey}`, { 'is-youth-event': event.journey === 'Jovem' }]"
              >
                <time>{{ event.date }}</time>
                <div>
                  <header>
                    <strong>{{ event.title }}</strong>
                    <span>{{ event.status }}</span>
                  </header>
                  <p>{{ event.description }}</p>
                  <footer>
                    <small>{{ event.journey }}</small>
                    <small>{{ event.category }}</small>
                    <b>+{{ event.points }} pts</b>
                  </footer>
                </div>
              </article>
            </div>
          </article>

          <aside class="history-support-card">
            <span>Guia do histórico</span>
            <h2>Como o histórico funciona</h2>
            <p>Cada registro pode ser automático, enviado por líderes ou validado pela Secretaria/Administração. Alguns pontos podem aparecer como “em validação” antes de serem confirmados.</p>
            <div>
              <article v-for="stat in visibleHistorySupportStats" :key="stat.label">
                <small>{{ stat.label }}</small>
                <strong>{{ stat.value }}</strong>
              </article>
            </div>
          </aside>
        </section>

        <nav class="history-actions" aria-label="Atalhos do histórico">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="`${baseRoute}/geral`">Ver caminhada geral</Link>
          <Link v-if="viewerContext.canSeeYouthJourney" :href="`${baseRoute}/jovem`">Ver caminhada jovem</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
        </nav>
      </section>

      <section v-else-if="isMentorArea" class="mentor-dashboard" aria-label="Mentor da Caminhada">
        <section class="mentor-main-grid">
          <article class="mentor-reading-card">
            <div class="mentor-reading-content">
              <span>{{ mentorWeeklyReading.title }}</span>
              <h2>{{ mentorWeeklyReading.title }}</h2>
              <p>{{ mentorWeeklyReading.text }}</p>
              <div class="mentor-reading-fields">
                <article v-for="field in mentorWeeklyReading.fields" :key="field.label">
                  <small>{{ field.label }}</small>
                  <strong>{{ field.value }}</strong>
                </article>
              </div>
            </div>
            <figure aria-label="Imagem do Mentor da Caminhada">
              <img src="/images/familia-resgate/minha-caminhada/mentor-caminhada.png" alt="Mentor da Caminhada" />
            </figure>
          </article>

          <aside class="mentor-care-card">
            <span>Cuidado importante</span>
            <h2>Cuidado importante</h2>
            <p>O Mentor da Caminhada oferece orientação de apoio com base nos registros da jornada geral. Ele não substitui pastor, liderança, discipulado, aconselhamento pastoral, acompanhamento espiritual humano ou ajuda profissional quando necessária.</p>
            <ul>
              <li v-for="limit in mentorCareLimits" :key="limit">{{ limit }}</li>
            </ul>
          </aside>
        </section>

        <section class="mentor-how-card" aria-label="Como este mentor funciona">
          <span>{{ mentorHowItWorks.title }}</span>
          <h2>{{ mentorHowItWorks.title }}</h2>
          <p>{{ mentorHowItWorks.text }}</p>
          <p>{{ mentorHowItWorks.note }}</p>
        </section>

        <section class="mentor-plan-card" aria-label="Plano personalizado">
          <header>
            <span>Plano personalizado</span>
            <h2>Próximos passos sugeridos</h2>
          </header>
          <div class="mentor-plan-grid">
            <article v-for="step in mentorPlanSteps" :key="step.action" :class="`status-${step.statusKey}`">
              <header>
                <small>{{ step.category }}</small>
                <span>{{ step.status }}</span>
              </header>
              <strong>{{ step.action }}</strong>
              <p>{{ step.impact }}</p>
            </article>
          </div>
        </section>

        <section class="mentor-focus-grid" aria-label="Foco por jornada">
          <article v-for="card in mentorVisibleJourneyFocusCards" :key="card.journey">
            <span>{{ card.journey }}</span>
            <strong>Foco sugerido: {{ card.focus }}</strong>
            <p>{{ card.note }}</p>
          </article>
        </section>

        <section class="mentor-questions-card" aria-label="Perguntas guiadas">
          <header>
            <span>Perguntas guiadas</span>
            <h2>Perguntas para uma leitura da caminhada com cuidado</h2>
          </header>
          <div>
            <article v-for="question in mentorGuidedQuestions" :key="question">
              {{ question }}
            </article>
          </div>
        </section>

        <nav class="mentor-actions" aria-label="Atalhos do mentor">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="`${baseRoute}/historico`">Ver histórico</Link>
          <Link :href="`${baseRoute}/regras`">Ver regras</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
        </nav>
      </section>

      <section v-else-if="isRulesArea" class="rules-dashboard" aria-label="Regras da Caminhada">
        <article class="rules-principle-card">
          <div>
            <span>Princípio da Caminhada</span>
            <h2>Princípio da Caminhada</h2>
            <p>A caminhada não mede espiritualidade nem valor diante de Deus. Ela serve para incentivar constância, participação, serviço, Palavra, comunhão e crescimento saudável.</p>
          </div>
          <div class="rules-pillars">
            <strong v-for="pillar in rulesPrinciplePillars" :key="pillar">{{ pillar }}</strong>
          </div>
        </article>

        <section class="rules-journey-grid" :class="{ 'member-view': showRulesYouthTrackNote }" aria-label="Regras por jornada">
          <article v-for="card in visibleRulesJourneyCards" :key="card.title">
            <header>
              <i>{{ card.icon }}</i>
              <div>
                <span>{{ card.title }}</span>
                <h2>{{ card.title }}</h2>
                <p>{{ card.text }}</p>
              </div>
            </header>
            <div class="rules-journey-badges">
              <strong v-for="note in card.notes" :key="note">{{ note }}</strong>
            </div>
            <div class="rules-criteria-columns">
              <strong>{{ card.criteriaTitle }}</strong>
              <div class="rules-criteria-grid">
                <article v-for="item in card.criteria" :key="item.name">
                  <i>{{ item.icon }}</i>
                  <div>
                    <strong>{{ item.name }}</strong>
                    <small>{{ item.text }}</small>
                  </div>
                </article>
              </div>
            </div>
          </article>
          <article v-if="showRulesYouthTrackNote" class="rules-youth-note-card">
            <header>
              <i>{{ rulesYouthTrackNote.icon }}</i>
              <div>
                <span>{{ rulesYouthTrackNote.title }}</span>
                <h2>{{ rulesYouthTrackNote.title }}</h2>
                <p>{{ rulesYouthTrackNote.text }}</p>
              </div>
            </header>
            <div class="rules-journey-badges">
              <strong v-for="note in rulesYouthTrackNote.notes" :key="note">{{ note }}</strong>
            </div>
          </article>
        </section>

        <article class="rules-separation-card">
          <div>
            <span>Jornadas separadas</span>
            <strong>{{ rulesSeparationNote }}</strong>
          </div>
          <div class="rules-separation-tracks">
            <article v-for="track in visibleRulesSeparationTracks" :key="track.label">
              <i>{{ track.icon }}</i>
              <span>{{ track.label }}</span>
              <strong>{{ track.value }}</strong>
            </article>
          </div>
        </article>

        <section class="rules-info-grid" aria-label="Marcos, conquistas e validação">
          <article v-for="card in visibleRulesGuideCards" :key="card.title" class="rules-info-card">
            <i>{{ card.icon }}</i>
            <span>{{ card.title }}</span>
            <h2>{{ card.title }}</h2>
            <p>{{ card.text }}</p>
            <nav v-if="card.actions" :aria-label="card.title">
              <Link v-for="action in card.actions" :key="action.label" :href="action.route">{{ action.label }}</Link>
            </nav>
            <div v-if="card.statuses" class="rules-status-mini">
              <strong v-for="status in card.statuses" :key="status">{{ status }}</strong>
            </div>
          </article>
        </section>

        <section class="rules-bottom-grid">
          <article class="rules-validation-card" aria-label="Validação dos registros">
            <div>
              <span>Validação dos registros</span>
              <h2>Validação dos registros</h2>
              <p>Registros podem nascer automáticos, enviados por líderes ou confirmados pela Secretaria/Administração.</p>
            </div>
            <div>
              <strong v-for="status in rulesValidationStatuses" :key="status">{{ status }}</strong>
            </div>
          </article>

          <article class="rules-not-card" aria-label="O que a caminhada não é">
            <header>
              <span>Cuidado pastoral</span>
              <h2>O que a caminhada não é</h2>
            </header>
            <div class="rules-care-columns">
              <div>
                <strong>Não é</strong>
                <ul>
                  <li v-for="item in rulesNotList" :key="item">{{ item }}</li>
                </ul>
              </div>
              <div>
                <strong>É para</strong>
                <ul>
                  <li v-for="item in rulesForList" :key="item">{{ item }}</li>
                </ul>
              </div>
            </div>
          </article>
        </section>

        <nav class="rules-actions" aria-label="Atalhos das regras">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="`${baseRoute}/historico`">Ver histórico</Link>
          <Link :href="`${baseRoute}/mentor`">Ver mentor</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
          <Link :href="`${baseRoute}/geral/mapa`">Ver mapa geral</Link>
        </nav>
      </section>

      <section v-else-if="isRankingArea" class="ranking-dashboard" aria-label="Destaques da Caminhada">
        <article class="ranking-principle-card">
          <div>
            <span>Como os destaques funcionam</span>
            <h2>Como os destaques funcionam</h2>
            <p>Os destaques não medem espiritualidade nem valor diante de Deus. Eles ajudam a reconhecer constância, participação, serviço, Palavra, comunhão e crescimento saudável.</p>
          </div>
          <div class="ranking-principles">
            <strong v-for="principle in rankingPrinciples" :key="principle">{{ principle }}</strong>
          </div>
        </article>

        <section class="ranking-filter-card" aria-label="Filtros dos destaques">
          <div>
            <span>Filtros visuais</span>
            <strong>{{ selectedRankingFilter }}</strong>
          </div>
          <nav aria-label="Selecionar filtro dos destaques">
            <button
              v-for="filter in rankingFilters"
              :key="filter"
              type="button"
              :class="{ active: selectedRankingFilter === filter }"
              @click="selectedRankingFilter = filter"
            >
              {{ filter }}
            </button>
          </nav>
        </section>

        <section class="ranking-summary-grid" aria-label="Visão geral saudável">
          <article v-for="card in rankingSummaryCards" :key="card.label">
            <span>{{ card.label }}</span>
            <strong>{{ card.value }}</strong>
            <small>{{ card.note }}</small>
          </article>
        </section>

        <section class="ranking-separation-card" aria-label="Reconhecimentos separados">
          <div>
            <span>Reconhecimentos separados</span>
            <h2>Trilhos separados, cuidado preservado</h2>
            <p>Nesta visualização de membro comum aparecem apenas os destaques gerais da igreja. Destaques jovens e reconhecimentos de equipe pertencem a trilhos próprios e dependem de permissões futuras.</p>
          </div>
          <div class="ranking-separation-tracks">
            <article v-for="track in rankingSeparationTracks" :key="track.title">
              <strong>{{ track.title }}</strong>
              <span>{{ track.result }}</span>
              <p>{{ track.text }}</p>
            </article>
          </div>
        </section>

        <section v-if="showRankingGeneralSection" class="ranking-highlights-section" aria-label="Destaques Gerais da Igreja">
          <header>
            <span>Destaques Gerais da Igreja</span>
            <h2>Destaques Gerais da Igreja</h2>
          </header>
          <div v-if="hasVisibleRankingGeneralHighlights" class="ranking-highlight-grid">
            <article v-for="highlight in visibleRankingGeneralHighlights" :key="highlight.title">
              <i>{{ highlight.icon }}</i>
              <div>
                <span>{{ highlight.seal }}</span>
                <h3>{{ highlight.title }}</h3>
                <strong>{{ highlight.name }}</strong>
                <p>{{ highlight.reason }}</p>
                <footer>
                  <small>{{ highlight.journey }}</small>
                  <small>{{ highlight.category }}</small>
                </footer>
              </div>
            </article>
          </div>
          <p v-else class="ranking-empty-note">Nenhum destaque geral neste filtro. Os trilhos continuam separados.</p>
          <div class="ranking-criteria-list">
            <strong>Critérios gerais</strong>
            <span v-for="criterion in rankingGeneralCriteria" :key="criterion">{{ criterion }}</span>
          </div>
        </section>

        <section v-if="showRankingYouthSection" class="ranking-highlights-section ranking-youth-section" aria-label="Destaques Jovens dos Resgatados">
          <header>
            <span>Destaques Jovens dos Resgatados</span>
            <h2>Destaques Jovens dos Resgatados</h2>
          </header>
          <div v-if="hasVisibleRankingYouthHighlights" class="ranking-highlight-grid">
            <article v-for="highlight in visibleRankingYouthHighlights" :key="highlight.title" class="is-youth-highlight">
              <i>{{ highlight.icon }}</i>
              <div>
                <span>{{ highlight.seal }}</span>
                <h3>{{ highlight.title }}</h3>
                <strong>{{ highlight.name }}</strong>
                <p>{{ highlight.reason }}</p>
                <footer>
                  <small>{{ highlight.journey }}</small>
                  <small>{{ highlight.category }}</small>
                </footer>
              </div>
            </article>
          </div>
          <p v-else class="ranking-empty-note">Nenhum destaque jovem neste filtro. Jovens não competem com membros comuns.</p>
          <div class="ranking-criteria-list">
            <strong>Critérios jovens</strong>
            <span v-for="criterion in rankingYouthCriteria" :key="criterion">{{ criterion }}</span>
          </div>
        </section>

        <section class="ranking-areas-section" aria-label="Áreas de reconhecimento">
          <header>
            <span>Áreas de reconhecimento</span>
            <h2>Onde a constância pode ser reconhecida</h2>
          </header>
          <div class="ranking-areas-grid">
            <article v-for="area in rankingRecognitionAreas" :key="area.title">
              <i>{{ area.icon }}</i>
              <strong>{{ area.title }}</strong>
              <p>{{ area.text }}</p>
            </article>
          </div>
        </section>

        <section v-if="showRankingTeamsSection" class="ranking-youth-card" aria-label="Equipes dos Resgatados">
          <div>
            <span>Equipes dos Resgatados</span>
            <h2>Equipes dos Resgatados</h2>
            <p>As equipes jovens poderão ter reconhecimentos coletivos próprios, como presença coletiva, missões em equipe, desafios bíblicos e acolhimento de novos jovens. Esses pontos são coletivos e não se misturam com os pontos individuais.</p>
            <strong>Trilho futuro separado: equipe jovem não soma nos pontos gerais nem nos pontos jovens individuais.</strong>
          </div>
          <div class="ranking-youth-grid">
            <article v-for="badge in rankingTeamBadges" :key="badge.title">
              <i>{{ badge.icon }}</i>
              <strong>{{ badge.title }}</strong>
              <p>{{ badge.text }}</p>
            </article>
          </div>
        </section>

        <section v-if="showRankingYouthSection" class="ranking-youth-card ranking-resgatados-note" aria-label="Destaques dos Resgatados">
          <div>
            <span>Destaques dos Resgatados</span>
            <h2>Destaques dos Resgatados</h2>
            <p>Os jovens podem ter reconhecimentos próprios ligados à presença nos encontros, desafios bíblicos, serviço jovem, comunhão, missões e crescimento com responsabilidade.</p>
            <strong>Esses reconhecimentos jovens não se misturam com os destaques gerais.</strong>
          </div>
          <div class="ranking-youth-grid">
            <article v-for="badge in rankingYouthBadges" :key="badge.title">
              <i>{{ badge.icon }}</i>
              <strong>{{ badge.title }}</strong>
              <p>{{ badge.text }}</p>
            </article>
          </div>
        </section>

        <article class="ranking-care-card" aria-label="Reconhecimento com cuidado">
          <div>
            <span>Reconhecimento com cuidado</span>
            <h2>Reconhecimento com cuidado</h2>
            <p>Os destaques existem para encorajar, não para comparar pessoas. A caminhada não mede fé, valor espiritual ou importância diante de Deus.</p>
          </div>
          <ul>
            <li v-for="item in rankingCareItems" :key="item">{{ item }}</li>
          </ul>
        </article>

        <nav class="ranking-actions" aria-label="Atalhos dos destaques">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="`${baseRoute}/regras`">Ver regras</Link>
          <Link :href="`${baseRoute}/historico`">Ver histórico</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
        </nav>
      </section>

      <template v-else>
        <section v-if="mapSummaryCards.length" class="map-summary" aria-label="Resumo do mapa da caminhada">
          <article v-for="card in mapSummaryCards" :key="card.label">
            <span>{{ card.label }}</span>
            <strong>{{ card.value }}</strong>
            <small>{{ card.note }}</small>
          </article>
        </section>

        <section v-else-if="!hasRealMapData" class="map-summary" aria-label="Resumo do mapa da caminhada">
          <article>
            <span>Atual</span>
            <strong>{{ journeyConfig.currentLevel }}</strong>
            <small>{{ currentLevelName }}</small>
          </article>
          <article>
            <span>Pontos</span>
            <strong>{{ journeyConfig.points }}</strong>
            <small>{{ progressPercent }}% do marco atual</small>
          </article>
          <article>
            <span>Conquistados</span>
            <strong>{{ completedCount }}</strong>
            <small>marcos já alcançados</small>
          </article>
          <article>
            <span>Próximos</span>
            <strong>{{ futureCount }}</strong>
            <small>marcos visíveis</small>
          </article>
        </section>

        <article v-if="mapEmptyState" class="map-empty-state">
          <strong>{{ mapEmptyState.title }}</strong>
          <p>{{ mapEmptyState.text }}</p>
        </article>

        <section v-if="!hasRealMapData || (mapAuthorized && mapLevels.length)" class="map-card">
          <div class="map-sky" aria-hidden="true"></div>
          <div class="map-glow" aria-hidden="true"></div>

          <header class="map-card-header">
            <div>
              <span>Mapa completo</span>
              <h2>{{ mapHeading }}</h2>
              <p>{{ mapDescription }}</p>
            </div>
            <nav aria-label="Ações do mapa">
              <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
              <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
            </nav>
          </header>

          <div class="map-track-shell" aria-label="Mapa horizontal com 20 marcos da caminhada">
            <div class="map-track">
              <div class="map-path" aria-hidden="true"></div>
              <component
                :is="hasRealMapData ? 'article' : Link"
                v-for="level in mapLevels"
                :key="level.id || level.number"
                class="map-level"
                :class="[`is-${level.status}`, { 'is-final': level.number === 20 }]"
                :href="level.route"
                :aria-label="`${hasRealMapData ? 'Nível real' : 'Abrir nível'} ${level.number}: ${level.name}`"
              >
                <span v-if="level.status === 'completed'" class="state-mark">✓</span>
                <span v-else-if="level.status === 'next'" class="state-mark">→</span>
                <span v-else-if="level.status === 'locked'" class="state-mark">🔒</span>
                <span class="level-number">Nível {{ level.number }}</span>
                <span class="church" aria-hidden="true">
                  <span class="church-glow"></span>
                  <span class="church-icon">{{ level.icon }}</span>
                  <span class="church-tower"><span></span></span>
                  <span class="church-roof"></span>
                  <span class="church-body"><span></span></span>
                </span>
                <span v-if="level.status === 'current'" class="current-badge">
                  <b>Você está aqui</b>
                  <em>{{ level.progressText }}</em>
                </span>
                <span class="level-label">
                  <strong>{{ level.name }}</strong>
                  <small>{{ level.statusLabel }}</small>
                  <em>{{ level.statusNote }}</em>
                </span>
              </component>
            </div>
          </div>
        </section>

        <section class="map-footer-grid">
          <article class="legend-card">
            <header>
              <span>Legenda</span>
              <h2>Estados da caminhada</h2>
            </header>
            <div class="legend-list">
              <div><i class="completed"></i><strong>Conquistado</strong><small>Marco já alcançado.</small></div>
              <div><i class="current"></i><strong>Em progresso</strong><small>Nível atual da jornada.</small></div>
              <div><i class="future"></i><strong>Próximo marco</strong><small>Etapas visíveis à frente.</small></div>
              <div><i class="locked"></i><strong>Bloqueado</strong><small>Estação futura protegida.</small></div>
            </div>
          </article>

          <article class="future-card">
            <span>{{ hasRealMapData ? 'Dados reais' : 'Próxima integração' }}</span>
            <h2>{{ hasRealMapData ? (currentMapData?.nextLevel?.name || 'Mapa atualizado com dados reais') : 'Páginas personalizadas para cada marco' }}</h2>
            <p>{{ hasRealMapData ? 'Este mapa usa níveis oficiais, pontos aprovados e logs recentes sem metadata sensível.' : 'Cada marco terá uma página personalizada com significado, conquistas, atividades e histórico da caminhada.' }}</p>
            <strong>{{ hasRealMapData ? `${formatNumber(lockedCount)} marcos protegidos à frente` : `${lockedCount} marcos aguardam as próximas estações.` }}</strong>
          </article>

          <article v-if="mapRecentLogs.length" class="map-recent-logs">
            <span>Registros recentes</span>
            <h2>Pontos considerados no mapa</h2>
            <div>
              <section v-for="log in mapRecentLogs" :key="log.id">
                <strong>{{ log.category }}</strong>
                <small>{{ formatNumber(log.points) }} pts</small>
              </section>
            </div>
          </article>
        </section>
      </template>
    </main>
  </div>
</template>

<style scoped>
.journey-area-page { min-height: 100vh; background: radial-gradient(circle at 12% 0%, rgba(217,164,65,.16), transparent 28%), radial-gradient(circle at 88% 8%, rgba(7,27,51,.08), transparent 30%), linear-gradient(135deg, #fff8ea, #f3e7d2); }
.journey-area-page.journey-jovem { background: radial-gradient(circle at 12% 0%, rgba(249,115,22,.14), transparent 28%), radial-gradient(circle at 88% 8%, rgba(217,164,65,.14), transparent 30%), linear-gradient(135deg, #fff8ea, #f3e7d2); }
.journey-area-main { margin-left: 80px; padding: 18px 22px 30px; }
a { text-decoration: none; }
.area-hero, .level-overview, .journey-dashboard, .history-dashboard, .mentor-dashboard, .rules-dashboard, .ranking-dashboard, .map-summary, .map-card, .map-footer-grid { max-width: 1380px; margin-left: auto; margin-right: auto; }
.area-hero { display: flex; justify-content: space-between; align-items: center; gap: 18px; margin-bottom: 14px; padding: 22px; border: 1px solid rgba(246,200,95,.32); border-radius: 26px; background: radial-gradient(circle at 82% 16%, rgba(246,200,95,.2), transparent 34%), linear-gradient(135deg, #071b33, #0b2748); box-shadow: 0 22px 50px rgba(7,27,51,.18); }
.journey-jovem .area-hero { border-color: rgba(249,115,22,.3); background: radial-gradient(circle at 82% 16%, rgba(249,115,22,.24), transparent 34%), radial-gradient(circle at 22% 16%, rgba(246,200,95,.14), transparent 30%), linear-gradient(135deg, #071b33, #0b2748); }
.area-hero p { margin: 0; color: rgba(255,248,234,.84); font-size: .78rem; font-weight: 850; }
.area-hero p span { color: #f6c85f; }
.area-hero h1 { margin: 6px 0 5px; color: #fff8ea; font: 950 clamp(2rem, 4vw, 3.4rem)/.92 Georgia, serif; letter-spacing: -.055em; }
.area-hero small { display: block; max-width: 720px; color: rgba(255,248,234,.9); font-size: .9rem; font-weight: 760; line-height: 1.42; }
.area-hero > strong { padding: 10px 14px; border: 1px solid rgba(246,200,95,.48); border-radius: 999px; background: rgba(255,248,234,.1); color: #f6c85f; font-size: .82rem; font-weight: 950; white-space: nowrap; }
.area-hero.is-detail-hero { padding: 16px 18px; border-radius: 22px; margin-bottom: 12px; box-shadow: 0 16px 36px rgba(7,27,51,.14); }
.area-hero.is-detail-hero h1 { margin: 4px 0; font-size: clamp(1.75rem, 3vw, 2.55rem); }
.area-hero.is-detail-hero small { max-width: 760px; font-size: .86rem; }
.area-hero.is-detail-hero > strong { padding: 8px 12px; font-size: .78rem; }
.level-overview { display: grid; gap: 14px; }
.level-separation-note { display: flex; justify-content: space-between; align-items: center; gap: 14px; padding: 14px 16px; border: 1px solid rgba(249,115,22,.22); border-radius: 20px; background: linear-gradient(135deg, rgba(255,248,234,.92), rgba(255,241,220,.86)); box-shadow: 0 14px 32px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.7); }
.level-separation-note span { color: #9a4b12; font-size: .74rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.level-separation-note strong { color: #071b33; font-size: .9rem; font-weight: 900; line-height: 1.3; }
.level-summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.level-summary-grid article, .level-empty-state, .level-recent-logs { border: 1px solid rgba(217,164,65,.16); background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.level-summary-grid article { padding: 13px; border-radius: 18px; }
.level-summary-grid span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.level-summary-grid strong { display: block; margin: 5px 0 2px; color: #071b33; font-size: 1rem; font-weight: 950; line-height: 1.08; letter-spacing: -.025em; }
.level-summary-grid small { color: #536174; font-size: .76rem; font-weight: 820; }
.level-empty-state { padding: 16px; border-style: dashed; border-radius: 20px; }
.level-empty-state strong { color: #071b33; font-size: .95rem; font-weight: 950; }
.level-empty-state p { margin: 6px 0 0; color: #536174; font-size: .82rem; font-weight: 820; line-height: 1.34; }
.level-cards { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.level-current-card { position: relative; overflow: hidden; display: grid; gap: 15px; padding: 18px; border: 1px solid rgba(217,164,65,.24); border-radius: 26px; background: radial-gradient(circle at 86% 10%, rgba(246,200,95,.22), transparent 34%), linear-gradient(145deg, rgba(255,248,234,.96), rgba(246,238,221,.9)); box-shadow: 0 22px 48px rgba(7,27,51,.1), inset 0 1px 0 rgba(255,255,255,.72); }
.level-current-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(7,27,51,.04), transparent 38%); pointer-events: none; }
.level-current-card.is-youth-card { border-color: rgba(249,115,22,.28); background: radial-gradient(circle at 86% 10%, rgba(249,115,22,.22), transparent 34%), linear-gradient(145deg, rgba(255,248,234,.96), rgba(255,239,222,.9)); }
.level-current-card header, .level-current-main, .level-progress-row, .level-next-row, .level-card-actions { position: relative; }
.level-current-card header span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .12em; text-transform: uppercase; }
.level-current-card.is-youth-card header span { color: #b45309; }
.level-current-card h2 { margin: 4px 0 5px; color: #071b33; font: 950 clamp(1.45rem, 3vw, 2.1rem)/.98 Georgia, serif; letter-spacing: -.05em; }
.level-current-card header p { margin: 0; color: #445164; font-size: .86rem; font-weight: 780; line-height: 1.42; }
.level-current-main { display: grid; grid-template-columns: auto minmax(0, 1fr); align-items: center; gap: 14px; padding: 14px; border: 1px solid rgba(217,164,65,.16); border-radius: 22px; background: rgba(255,255,255,.56); }
.level-emblem { display: grid; place-items: center; gap: 4px; width: 86px; height: 86px; border: 1px solid rgba(246,200,95,.46); border-radius: 24px; background: radial-gradient(circle, rgba(246,200,95,.28), rgba(7,27,51,.06)); box-shadow: inset 0 1px 0 rgba(255,255,255,.6); }
.is-youth-card .level-emblem { border-color: rgba(249,115,22,.34); background: radial-gradient(circle, rgba(249,115,22,.24), rgba(7,27,51,.06)); }
.level-emblem span { font-size: 2rem; }
.level-emblem small { color: #8a5b13; font-size: .7rem; font-weight: 950; }
.level-current-main div:last-child small { color: #64748b; font-size: .72rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; }
.level-current-main div:last-child strong { display: block; margin: 3px 0; color: #071b33; font-size: 1.45rem; font-weight: 950; letter-spacing: -.04em; }
.level-current-main div:last-child p { margin: 0; color: #445164; font-size: .84rem; font-weight: 820; }
.level-progress-row { display: grid; gap: 8px; }
.level-progress-row div { display: flex; justify-content: space-between; gap: 12px; color: #071b33; font-weight: 900; }
.level-progress-row span { color: #64748b; font-size: .78rem; font-weight: 900; }
.level-progress-row strong { color: #8a5b13; font-size: .9rem; font-weight: 950; }
.level-progress-row em { overflow: hidden; height: 10px; border-radius: 999px; background: rgba(7,27,51,.1); }
.level-progress-row i { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #d9a441, #f6c85f); box-shadow: 0 0 18px rgba(217,164,65,.28); }
.is-youth-card .level-progress-row i { background: linear-gradient(90deg, #f97316, #f6c85f); }
.level-next-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 11px 13px; border: 1px solid rgba(7,27,51,.08); border-radius: 18px; background: rgba(7,27,51,.04); }
.level-next-row span { color: #64748b; font-size: .76rem; font-weight: 950; text-transform: uppercase; }
.level-next-row strong { color: #071b33; font-size: .9rem; font-weight: 950; text-align: right; }
.level-real-details { display: grid; gap: 5px; padding: 10px 12px; border-radius: 16px; background: rgba(217,164,65,.1); }
.level-real-details span { color: #64748b; font-size: .72rem; font-weight: 900; }
.level-real-details strong { color: #071b33; font-size: .82rem; font-weight: 950; }
.level-recent-logs { padding: 14px; border-radius: 22px; }
.level-recent-logs header { margin-bottom: 10px; }
.level-recent-logs header span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.level-recent-logs h2 { margin: 3px 0 0; color: #071b33; font: 900 1.08rem/1.08 Georgia, serif; }
.level-recent-logs > div { display: grid; gap: 7px; }
.level-recent-logs article { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 6px 12px; padding: 9px 10px; border: 1px solid rgba(217,164,65,.12); border-radius: 13px; background: rgba(255,255,255,.52); }
.level-recent-logs article strong { color: #071b33; font-size: .82rem; font-weight: 900; }
.level-recent-logs article small { color: #8a5b13; font-size: .72rem; font-weight: 950; white-space: nowrap; }
.level-recent-logs article p { grid-column: 1 / -1; margin: 0; color: #536174; font-size: .76rem; font-weight: 780; line-height: 1.28; }
.level-card-actions, .level-extra-actions { display: flex; flex-wrap: wrap; gap: 8px; }
.level-card-actions a, .level-extra-actions a { display: inline-flex; justify-content: center; align-items: center; min-height: 38px; padding: 10px 14px; border: 1px solid rgba(217,164,65,.24); border-radius: 999px; background: #071b33; color: #fff8ea; font-size: .82rem; font-weight: 950; box-shadow: 0 10px 22px rgba(7,27,51,.14); }
.level-card-actions a:first-child { background: linear-gradient(135deg, #d9a441, #f6c85f); color: #071b33; border-color: rgba(217,164,65,.32); }
.is-youth-card .level-card-actions a:first-child { background: linear-gradient(135deg, #f97316, #f6c85f); }
.level-extra-actions { justify-content: center; padding-top: 2px; }
.level-extra-actions a { background: rgba(255,248,234,.9); color: #071b33; }
.level-extra-actions a:last-child { background: #071b33; color: #f6c85f; border-color: rgba(246,200,95,.3); }
.journey-dashboard { display: grid; gap: 12px; }
.detail-summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.detail-summary-grid article { padding: 13px; border: 1px solid rgba(217,164,65,.18); border-radius: 18px; background: rgba(255,248,234,.86); box-shadow: 0 12px 26px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.66); }
.is-youth-dashboard .detail-summary-grid article { border-color: rgba(249,115,22,.18); background: rgba(255,246,235,.88); }
.detail-summary-grid span, .weekly-focus-card span, .dashboard-section header span, .dashboard-panel header span, .dashboard-notice span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.is-youth-dashboard .detail-summary-grid span, .is-youth-dashboard .weekly-focus-card span, .is-youth-dashboard .dashboard-section header span, .is-youth-dashboard .dashboard-panel header span, .is-youth-dashboard .dashboard-notice span { color: #b45309; }
.detail-summary-grid strong { display: block; margin: 5px 0 2px; color: #071b33; font-size: 1rem; font-weight: 950; line-height: 1.08; letter-spacing: -.025em; }
.detail-summary-grid small { color: #536174; font-size: .76rem; font-weight: 820; }
.weekly-focus-card { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px; border: 1px solid rgba(246,200,95,.28); border-radius: 22px; background: radial-gradient(circle at 86% 12%, rgba(246,200,95,.2), transparent 34%), linear-gradient(135deg, #071b33, #0b2748); box-shadow: 0 18px 40px rgba(7,27,51,.14), inset 0 1px 0 rgba(255,248,234,.12); }
.is-youth-dashboard .weekly-focus-card { border-color: rgba(249,115,22,.3); background: radial-gradient(circle at 86% 12%, rgba(249,115,22,.24), transparent 34%), radial-gradient(circle at 20% 16%, rgba(246,200,95,.14), transparent 34%), linear-gradient(135deg, #071b33, #0b2748); }
.weekly-focus-card span { color: #f6c85f; }
.is-youth-dashboard .weekly-focus-card span { color: #fdba74; }
.weekly-focus-card h2 { margin: 5px 0; color: #fff8ea; font: 950 clamp(1.2rem, 2.4vw, 1.75rem)/1 Georgia, serif; letter-spacing: -.04em; }
.weekly-focus-card p { margin: 0; max-width: 720px; color: rgba(255,248,234,.86); font-size: .86rem; font-weight: 780; line-height: 1.36; }
.weekly-focus-card nav, .journey-shortcuts { display: flex; flex-wrap: wrap; gap: 8px; }
.weekly-focus-card a, .journey-shortcuts a { display: inline-flex; justify-content: center; align-items: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(246,200,95,.34); border-radius: 999px; background: #fff8ea; color: #071b33; font-size: .8rem; font-weight: 950; box-shadow: 0 10px 20px rgba(7,27,51,.12); }
.weekly-focus-card a:last-child, .journey-shortcuts a:nth-child(2), .journey-shortcuts a:nth-child(4) { background: rgba(7,27,51,.92); color: #f6c85f; }
.is-youth-dashboard .weekly-focus-card a:first-child, .is-youth-dashboard .journey-shortcuts a:first-child { background: linear-gradient(135deg, #f97316, #f6c85f); color: #071b33; border-color: rgba(249,115,22,.28); }
.dashboard-section, .dashboard-panel, .dashboard-notice { padding: 14px; border: 1px solid rgba(217,164,65,.16); border-radius: 22px; background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.compact-analysis-section { padding-bottom: 13px; }
.dashboard-section header, .dashboard-panel header { margin-bottom: 10px; }
.dashboard-section h2, .dashboard-panel h2 { margin: 3px 0 0; color: #071b33; font: 900 1.08rem/1.08 Georgia, serif; }
.growth-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 8px; }
.growth-card { display: grid; gap: 7px; padding: 10px; border: 1px solid rgba(217,164,65,.12); border-radius: 16px; background: rgba(255,255,255,.52); }
.growth-card div { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
.growth-card strong { color: #071b33; font-size: .78rem; font-weight: 950; line-height: 1.08; }
.growth-card div small { color: #8a5b13; font-size: .72rem; font-weight: 950; }
.is-youth-dashboard .growth-card div small, .is-youth-dashboard .growth-card footer small { color: #b45309; }
.growth-card p { margin: 0; min-height: 34px; color: #445164; font-size: .72rem; font-weight: 760; line-height: 1.22; }
.growth-card footer { display: grid; gap: 5px; }
.growth-card footer em { overflow: hidden; height: 6px; border-radius: 999px; background: rgba(7,27,51,.1); }
.growth-card footer i { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #d9a441, #f6c85f); box-shadow: 0 0 14px rgba(246,200,95,.24); }
.is-youth-dashboard .growth-card footer i { background: linear-gradient(90deg, #f97316, #f6c85f); }
.growth-card footer small { color: #8a5b13; font-size: .68rem; font-weight: 950; }
.dashboard-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.activity-list { display: grid; gap: 7px; }
.activity-list div { display: flex; justify-content: space-between; gap: 12px; padding: 9px 10px; border: 1px solid rgba(217,164,65,.12); border-radius: 13px; background: rgba(255,255,255,.52); }
.activity-list strong { color: #071b33; font-size: .82rem; font-weight: 900; }
.activity-list small { color: #8a5b13; font-size: .72rem; font-weight: 950; white-space: nowrap; }
.is-youth-dashboard .activity-list small { color: #b45309; }
.badge-list { display: flex; flex-wrap: wrap; gap: 8px; }
.badge-list strong { padding: 8px 10px; border: 1px solid rgba(217,164,65,.2); border-radius: 999px; background: rgba(217,164,65,.12); color: #071b33; font-size: .76rem; font-weight: 950; }
.is-youth-dashboard .badge-list strong { border-color: rgba(249,115,22,.2); background: rgba(249,115,22,.12); }
.dashboard-notice { display: flex; justify-content: space-between; align-items: center; gap: 12px; border-color: rgba(249,115,22,.2); background: linear-gradient(135deg, rgba(255,248,234,.92), rgba(255,239,222,.86)); }
.dashboard-notice strong { color: #071b33; font-size: .88rem; font-weight: 900; line-height: 1.3; }
.journey-shortcuts { justify-content: center; padding-top: 1px; }
.history-dashboard { display: grid; gap: 12px; }
.history-filter-card, .history-summary-grid article, .history-timeline-card, .history-support-card { border: 1px solid rgba(217,164,65,.16); background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.history-filter-card { display: grid; grid-template-columns: minmax(180px, .22fr) minmax(0, 1fr); align-items: center; gap: 12px; padding: 14px; border-radius: 22px; }
.history-filter-card span, .history-summary-grid span, .history-timeline-card > header span, .history-support-card > span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.history-filter-card strong { display: block; margin-top: 3px; color: #071b33; font-size: 1.08rem; font-weight: 950; }
.history-filter-card nav { display: flex; flex-wrap: wrap; gap: 7px; }
.history-filter-card button { min-height: 34px; padding: 8px 11px; border: 1px solid rgba(217,164,65,.2); border-radius: 999px; background: rgba(255,255,255,.56); color: #071b33; font-size: .76rem; font-weight: 900; cursor: pointer; transition: .2s ease; }
.history-filter-card button.active, .history-filter-card button:hover { background: linear-gradient(135deg, #d9a441, #f6c85f); border-color: rgba(217,164,65,.32); box-shadow: 0 10px 20px rgba(217,164,65,.16); }
.history-summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.history-summary-grid article { padding: 13px; border-radius: 18px; }
.history-summary-grid strong { display: block; margin: 5px 0 2px; color: #071b33; font-size: 1.08rem; font-weight: 950; letter-spacing: -.025em; }
.history-summary-grid small { color: #536174; font-size: .76rem; font-weight: 820; line-height: 1.24; }
.history-content-grid { display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(300px, .55fr); gap: 12px; align-items: start; }
.history-timeline-card, .history-support-card { border-radius: 24px; padding: 16px; }
.history-timeline-card > header { margin-bottom: 12px; }
.history-timeline-card h2, .history-support-card h2 { margin: 4px 0 0; color: #071b33; font: 900 1.18rem/1.08 Georgia, serif; }
.history-timeline { position: relative; display: grid; gap: 10px; padding-left: 16px; }
.history-timeline::before { content: ''; position: absolute; left: 5px; top: 4px; bottom: 4px; width: 2px; border-radius: 999px; background: linear-gradient(180deg, rgba(217,164,65,.2), rgba(217,164,65,.76), rgba(7,27,51,.16)); }
.history-event { position: relative; display: grid; grid-template-columns: 70px minmax(0, 1fr); gap: 10px; padding: 11px; border: 1px solid rgba(217,164,65,.12); border-radius: 18px; background: rgba(255,255,255,.56); }
.history-event::before { content: ''; position: absolute; left: -15px; top: 18px; width: 10px; height: 10px; border: 2px solid #fff8ea; border-radius: 50%; background: #60a5fa; box-shadow: 0 0 0 4px rgba(96,165,250,.14); }
.history-event.status-confirmed::before { background: #16a34a; box-shadow: 0 0 0 4px rgba(22,163,74,.14); }
.history-event.status-pending::before { background: #60a5fa; box-shadow: 0 0 0 4px rgba(96,165,250,.14); }
.history-event.status-achievement::before { background: #f6c85f; box-shadow: 0 0 0 4px rgba(246,200,95,.18); }
.history-event.status-adjusted::before { background: #64748b; box-shadow: 0 0 0 4px rgba(100,116,139,.14); }
.history-event.is-youth-event { border-color: rgba(249,115,22,.18); background: linear-gradient(135deg, rgba(255,255,255,.62), rgba(255,243,229,.64)); }
.history-event time { color: #8a5b13; font-size: .74rem; font-weight: 950; text-transform: uppercase; }
.history-event header { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.history-event header strong { color: #071b33; font-size: .9rem; font-weight: 950; line-height: 1.16; }
.history-event header span { padding: 5px 8px; border-radius: 999px; background: rgba(96,165,250,.14); color: #1d4ed8; font-size: .66rem; font-weight: 950; white-space: nowrap; }
.history-event.status-confirmed header span { background: rgba(22,163,74,.12); color: #15803d; }
.history-event.status-achievement header span { background: rgba(217,164,65,.16); color: #8a5b13; }
.history-event.status-adjusted header span { background: rgba(100,116,139,.12); color: #475569; }
.history-event p { margin: 5px 0 8px; color: #445164; font-size: .8rem; font-weight: 780; line-height: 1.32; }
.history-event footer { display: flex; flex-wrap: wrap; gap: 7px; align-items: center; }
.history-event footer small, .history-event footer b { padding: 5px 8px; border-radius: 999px; background: rgba(7,27,51,.06); color: #071b33; font-size: .68rem; font-weight: 950; }
.history-event footer b { background: rgba(217,164,65,.14); color: #8a5b13; }
.history-support-card { position: sticky; top: 18px; display: grid; gap: 11px; background: radial-gradient(circle at 88% 8%, rgba(246,200,95,.18), transparent 34%), rgba(255,248,234,.9); }
.history-support-card p { margin: 0; color: #445164; font-size: .84rem; font-weight: 780; line-height: 1.38; }
.history-support-card div { display: grid; gap: 8px; }
.history-support-card article { padding: 10px 11px; border: 1px solid rgba(217,164,65,.14); border-radius: 14px; background: rgba(255,255,255,.56); }
.history-support-card small { color: #64748b; font-size: .72rem; font-weight: 950; text-transform: uppercase; }
.history-support-card article strong { display: block; margin-top: 3px; color: #071b33; font-size: .98rem; font-weight: 950; }
.history-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; }
.history-actions a { display: inline-flex; justify-content: center; align-items: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(246,200,95,.34); border-radius: 999px; background: #fff8ea; color: #071b33; font-size: .8rem; font-weight: 950; box-shadow: 0 10px 20px rgba(7,27,51,.12); }
.history-actions a:nth-child(2), .history-actions a:nth-child(4) { background: rgba(7,27,51,.92); color: #f6c85f; }
.mentor-dashboard { display: grid; gap: 12px; }
.mentor-main-grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(290px, .65fr); gap: 12px; align-items: stretch; }
.mentor-reading-card, .mentor-care-card, .mentor-how-card, .mentor-plan-card, .mentor-focus-grid article, .mentor-questions-card { border: 1px solid rgba(217,164,65,.16); background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.mentor-reading-card { overflow: hidden; display: grid; grid-template-columns: minmax(0, 1fr) 240px; min-height: 260px; border-radius: 26px; background: radial-gradient(circle at 82% 10%, rgba(246,200,95,.18), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.94), rgba(247,238,220,.9)); }
.mentor-reading-content { display: grid; align-content: center; gap: 10px; padding: 18px; }
.mentor-reading-content > span, .mentor-care-card > span, .mentor-how-card > span, .mentor-plan-card > header span, .mentor-focus-grid span, .mentor-questions-card > header span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.mentor-reading-content h2, .mentor-care-card h2, .mentor-how-card h2, .mentor-plan-card h2, .mentor-questions-card h2 { margin: 0; color: #071b33; font: 950 clamp(1.35rem, 2.6vw, 2.05rem)/1 Georgia, serif; letter-spacing: -.045em; }
.mentor-reading-content p, .mentor-care-card p, .mentor-how-card p, .mentor-focus-grid p, .mentor-questions-card article, .mentor-plan-grid p { margin: 0; color: #445164; font-size: .86rem; font-weight: 780; line-height: 1.38; }
.mentor-reading-fields { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
.mentor-reading-fields article { padding: 10px; border: 1px solid rgba(217,164,65,.14); border-radius: 15px; background: rgba(255,255,255,.58); }
.mentor-reading-fields small, .mentor-plan-grid small { color: #64748b; font-size: .68rem; font-weight: 950; letter-spacing: .06em; text-transform: uppercase; }
.mentor-reading-fields strong { display: block; margin-top: 4px; color: #071b33; font-size: .86rem; font-weight: 950; line-height: 1.12; }
.mentor-reading-card figure { position: relative; overflow: hidden; margin: 0; background: radial-gradient(circle at 50% 30%, rgba(246,200,95,.24), transparent 55%), linear-gradient(135deg, #071b33, #0b2748); }
.mentor-reading-card figure::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(7,27,51,.06), rgba(7,27,51,.28)); pointer-events: none; }
.mentor-reading-card img { width: 100%; height: 100%; object-fit: cover; filter: saturate(1.08) contrast(1.02); }
.mentor-care-card { display: grid; align-content: center; gap: 10px; padding: 18px; border-radius: 26px; background: radial-gradient(circle at 90% 12%, rgba(249,115,22,.12), transparent 34%), linear-gradient(135deg, #071b33, #0b2748); }
.mentor-care-card > span { color: #f6c85f; }
.mentor-care-card h2 { color: #fff8ea; }
.mentor-care-card p { color: rgba(255,248,234,.86); }
.mentor-care-card ul { display: grid; gap: 6px; margin: 0; padding-left: 18px; color: rgba(255,248,234,.84); font-size: .74rem; font-weight: 780; line-height: 1.3; }
.mentor-how-card { display: grid; gap: 8px; padding: 14px 16px; border-radius: 22px; background: linear-gradient(135deg, rgba(255,248,234,.92), rgba(255,241,228,.78)); }
.mentor-plan-card, .mentor-questions-card { padding: 16px; border-radius: 24px; }
.mentor-plan-card > header, .mentor-questions-card > header { margin-bottom: 12px; }
.mentor-plan-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.mentor-plan-grid article { display: grid; gap: 8px; padding: 12px; border: 1px solid rgba(217,164,65,.14); border-radius: 17px; background: rgba(255,255,255,.56); }
.mentor-plan-grid header { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
.mentor-plan-grid header span { padding: 5px 8px; border-radius: 999px; background: rgba(96,165,250,.14); color: #1d4ed8; font-size: .64rem; font-weight: 950; white-space: nowrap; }
.mentor-plan-grid .status-progress header span { background: rgba(217,164,65,.16); color: #8a5b13; }
.mentor-plan-grid .status-done header span { background: rgba(22,163,74,.12); color: #15803d; }
.mentor-plan-grid strong { color: #071b33; font-size: .88rem; font-weight: 950; line-height: 1.14; }
.mentor-focus-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.mentor-focus-grid article { padding: 14px; border-radius: 20px; }
.mentor-focus-grid strong { display: block; margin: 5px 0 6px; color: #071b33; font-size: .98rem; font-weight: 950; }
.mentor-focus-grid article:nth-child(2) { border-color: rgba(249,115,22,.18); background: linear-gradient(135deg, rgba(255,248,234,.9), rgba(255,241,228,.88)); }
.mentor-questions-card div { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
.mentor-questions-card article { padding: 11px; border: 1px solid rgba(217,164,65,.14); border-radius: 16px; background: rgba(255,255,255,.56); }
.mentor-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; }
.mentor-actions a { display: inline-flex; justify-content: center; align-items: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(246,200,95,.34); border-radius: 999px; background: #fff8ea; color: #071b33; font-size: .8rem; font-weight: 950; box-shadow: 0 10px 20px rgba(7,27,51,.12); }
.mentor-actions a:nth-child(2), .mentor-actions a:nth-child(4) { background: rgba(7,27,51,.92); color: #f6c85f; }
.mentor-actions a:nth-child(3) { background: linear-gradient(135deg, #d9a441, #f6c85f); color: #071b33; }
.rules-dashboard { display: grid; gap: 10px; }
.rules-principle-card, .rules-section, .rules-journey-grid > article, .rules-separation-card, .rules-info-card, .rules-validation-card, .rules-not-card { border: 1px solid rgba(217,164,65,.16); background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.rules-principle-card { display: grid; grid-template-columns: minmax(0, 1fr) minmax(360px, .8fr); gap: 12px; align-items: center; padding: 15px 16px; border-radius: 24px; background: radial-gradient(circle at 88% 10%, rgba(246,200,95,.18), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.94), rgba(247,238,220,.9)); }
.rules-principle-card span, .rules-section header span, .rules-journey-grid span, .rules-separation-card span, .rules-info-card span, .rules-validation-card span, .rules-not-card span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.rules-principle-card h2, .rules-section h2, .rules-journey-grid h2, .rules-info-card h2, .rules-validation-card h2, .rules-not-card h2 { margin: 3px 0 5px; color: #071b33; font: 950 clamp(1.18rem, 2vw, 1.7rem)/1 Georgia, serif; letter-spacing: -.045em; }
.rules-principle-card p, .rules-section header p, .rules-points-grid p, .rules-journey-grid p, .rules-validation-card p { margin: 0; color: #445164; font-size: .82rem; font-weight: 780; line-height: 1.32; }
.rules-pillars { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 7px; }
.rules-pillars strong { display: flex; align-items: center; min-height: 42px; padding: 8px 10px; border: 1px solid rgba(217,164,65,.16); border-radius: 14px; background: rgba(255,255,255,.58); color: #071b33; font-size: .78rem; font-weight: 950; line-height: 1.18; }
.rules-section { padding: 12px; border-radius: 22px; }
.rules-section header { display: flex; justify-content: space-between; gap: 14px; align-items: flex-end; margin-bottom: 9px; }
.rules-section header p { max-width: 520px; text-align: right; font-size: .8rem; }
.rules-points-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 7px; }
.rules-points-grid article { display: grid; grid-template-columns: auto 1fr; gap: 5px 7px; align-content: start; min-height: 112px; padding: 9px; border: 1px solid rgba(217,164,65,.14); border-radius: 15px; background: rgba(255,255,255,.56); }
.rules-points-grid i { font-style: normal; font-size: 1.05rem; line-height: 1; }
.rules-points-grid strong { color: #071b33; font-size: .8rem; font-weight: 950; line-height: 1.08; }
.rules-points-grid p { grid-column: 1 / -1; font-size: .72rem; line-height: 1.24; }
.rules-journey-grid, .rules-info-grid, .rules-bottom-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
.rules-journey-grid.member-view { grid-template-columns: minmax(0, 1.24fr) minmax(280px, .76fr); align-items: stretch; }
.rules-journey-grid article, .rules-info-card { padding: 13px; border-radius: 20px; }
.rules-journey-grid > article:nth-child(2) { border-color: rgba(249,115,22,.18); background: linear-gradient(135deg, rgba(255,248,234,.9), rgba(255,241,228,.88)); }
.rules-youth-note-card { align-content: center; border-style: dashed; }
.rules-youth-note-card header { align-items: center; }
.rules-youth-note-card p { font-size: .78rem; line-height: 1.3; }
.rules-journey-grid header { display: grid; grid-template-columns: auto 1fr; gap: 10px; align-items: start; }
.rules-journey-grid header > i, .rules-info-card > i { display: grid; place-items: center; width: 36px; height: 36px; border-radius: 14px; background: rgba(217,164,65,.14); font-style: normal; font-size: 1.15rem; }
.rules-journey-badges { display: flex; flex-wrap: wrap; gap: 6px; margin: 10px 0; }
.rules-journey-badges strong { padding: 6px 8px; border-radius: 999px; background: rgba(7,27,51,.08); color: #071b33; font-size: .68rem; font-weight: 950; }
.rules-criteria-columns { display: grid; gap: 7px; margin-top: 4px; }
.rules-criteria-columns > strong { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; }
.rules-criteria-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px; }
.rules-criteria-grid article { display: grid; grid-template-columns: auto 1fr; gap: 6px; align-items: start; padding: 7px; border: 1px solid rgba(217,164,65,.12); border-radius: 13px; background: rgba(255,255,255,.48); }
.rules-criteria-grid i { font-style: normal; font-size: .92rem; line-height: 1; }
.rules-criteria-grid strong { display: block; color: #071b33; font-size: .74rem; font-weight: 950; line-height: 1.1; }
.rules-criteria-grid small { display: block; margin-top: 2px; color: #445164; font-size: .68rem; font-weight: 760; line-height: 1.18; }
.rules-journey-grid ul, .rules-info-card ul, .rules-not-card ul { display: grid; gap: 5px; margin: 0; padding-left: 17px; color: #445164; font-size: .8rem; font-weight: 780; line-height: 1.28; }
.rules-separation-card { display: grid; grid-template-columns: minmax(0, 1fr) minmax(300px, .75fr); gap: 12px; align-items: center; padding: 12px 14px; border-radius: 18px; background: linear-gradient(135deg, rgba(7,27,51,.94), rgba(11,39,72,.92)); }
.rules-separation-card span { color: #f6c85f; white-space: nowrap; }
.rules-separation-card > div:first-child > strong { display: block; color: rgba(255,248,234,.9); font-size: .82rem; font-weight: 850; line-height: 1.32; }
.rules-separation-tracks { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
.rules-separation-tracks article { display: grid; grid-template-columns: auto 1fr; gap: 2px 7px; align-items: center; padding: 8px; border: 1px solid rgba(246,200,95,.2); border-radius: 14px; background: rgba(255,248,234,.08); }
.rules-separation-tracks i { grid-row: span 2; font-style: normal; }
.rules-separation-tracks span { color: rgba(255,248,234,.8); font-size: .68rem; font-weight: 850; letter-spacing: 0; text-transform: none; white-space: normal; }
.rules-separation-tracks strong { color: #f6c85f; font-size: .74rem; font-weight: 950; }
.rules-info-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.rules-info-card { display: grid; align-content: start; gap: 5px; }
.rules-info-card p { margin: 0; color: #445164; font-size: .78rem; font-weight: 780; line-height: 1.28; }
.rules-info-card nav { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
.rules-info-card a { display: inline-flex; align-items: center; min-height: 30px; padding: 7px 10px; border-radius: 999px; background: rgba(7,27,51,.92); color: #f6c85f; font-size: .74rem; font-weight: 950; }
.rules-info-card a:nth-child(2) { background: linear-gradient(135deg, #d9a441, #f6c85f); color: #071b33; }
.rules-status-mini { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
.rules-status-mini strong { padding: 6px 8px; border-radius: 999px; background: rgba(96,165,250,.14); color: #1d4ed8; font-size: .68rem; font-weight: 950; }
.rules-bottom-grid { align-items: stretch; margin-top: 2px; }
.rules-validation-card { display: grid; gap: 10px; align-content: space-between; min-height: 176px; padding: 15px; border-radius: 20px; background: linear-gradient(135deg, rgba(255,248,234,.92), rgba(247,238,220,.9)); }
.rules-validation-card div:last-child { display: flex; flex-wrap: wrap; gap: 7px; }
.rules-validation-card strong { padding: 6px 8px; border-radius: 999px; background: rgba(96,165,250,.14); color: #1d4ed8; font-size: .7rem; font-weight: 950; }
.rules-validation-card strong:nth-child(2) { background: rgba(217,164,65,.16); color: #8a5b13; }
.rules-validation-card strong:nth-child(3) { background: rgba(249,115,22,.12); color: #c2410c; }
.rules-validation-card strong:nth-child(4) { background: rgba(100,116,139,.12); color: #475569; }
.rules-validation-card strong:nth-child(5) { background: rgba(22,163,74,.12); color: #15803d; }
.rules-not-card { display: grid; grid-template-columns: minmax(180px, .45fr) minmax(0, 1fr); gap: 14px; align-items: start; min-height: 176px; padding: 15px; border-radius: 20px; background: radial-gradient(circle at 92% 12%, rgba(246,200,95,.12), transparent 36%), linear-gradient(135deg, #12345a, #0b2748); box-shadow: 0 14px 30px rgba(7,27,51,.12), inset 0 1px 0 rgba(255,248,234,.1); }
.rules-not-card span { color: #f6c85f; }
.rules-not-card h2 { color: #fff8ea; font-size: clamp(1.15rem, 1.7vw, 1.52rem); line-height: 1.04; letter-spacing: -.035em; }
.rules-care-columns { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
.rules-care-columns strong { display: block; margin-bottom: 5px; color: #f6c85f; font-size: .74rem; font-weight: 950; }
.rules-not-card ul { gap: 5px; color: rgba(255,248,234,.86); font-size: .74rem; }
.rules-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 4px; padding-top: 2px; }
.rules-actions a { display: inline-flex; justify-content: center; align-items: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(246,200,95,.34); border-radius: 999px; background: #fff8ea; color: #071b33; font-size: .8rem; font-weight: 950; box-shadow: 0 10px 20px rgba(7,27,51,.12); }
.rules-actions a:nth-child(2), .rules-actions a:nth-child(4) { background: rgba(7,27,51,.92); color: #f6c85f; }
.rules-actions a:nth-child(3) { background: linear-gradient(135deg, #d9a441, #f6c85f); color: #071b33; }
.ranking-dashboard { display: grid; gap: 10px; }
.ranking-principle-card, .ranking-filter-card, .ranking-summary-grid article, .ranking-separation-card, .ranking-highlights-section, .ranking-areas-section, .ranking-youth-card, .ranking-care-card { border: 1px solid rgba(217,164,65,.16); background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
.ranking-principle-card:hover, .ranking-filter-card:hover, .ranking-summary-grid article:hover, .ranking-separation-card:hover, .ranking-highlights-section:hover, .ranking-areas-section:hover { border-color: rgba(217,164,65,.26); box-shadow: 0 16px 34px rgba(7,27,51,.09), inset 0 1px 0 rgba(255,255,255,.72); transform: translateY(-1px); }
.ranking-principle-card { display: grid; grid-template-columns: minmax(0, 1fr) minmax(360px, .82fr); gap: 12px; align-items: center; padding: 15px 16px; border-radius: 24px; background: radial-gradient(circle at 88% 10%, rgba(246,200,95,.18), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.94), rgba(247,238,220,.9)); }
.ranking-principle-card span, .ranking-filter-card span, .ranking-summary-grid span, .ranking-separation-card span, .ranking-highlights-section > header span, .ranking-highlight-grid span, .ranking-areas-section > header span, .ranking-youth-card span, .ranking-care-card span { color: #8a5b13; font-size: .72rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.ranking-principle-card h2, .ranking-separation-card h2, .ranking-highlights-section h2, .ranking-areas-section h2, .ranking-youth-card h2, .ranking-care-card h2 { margin: 3px 0 5px; color: #071b33; font: 950 clamp(1.18rem, 2vw, 1.7rem)/1 Georgia, serif; letter-spacing: -.045em; }
.ranking-principle-card p, .ranking-separation-card p, .ranking-highlight-grid p, .ranking-areas-grid p, .ranking-youth-card p, .ranking-care-card p { margin: 0; color: #445164; font-size: .82rem; font-weight: 780; line-height: 1.32; }
.ranking-principles { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 7px; }
.ranking-principles strong { display: flex; align-items: center; min-height: 42px; padding: 8px 10px; border: 1px solid rgba(217,164,65,.16); border-radius: 14px; background: rgba(255,255,255,.58); color: #071b33; font-size: .78rem; font-weight: 950; line-height: 1.18; box-shadow: inset 0 1px 0 rgba(255,255,255,.68); }
.ranking-filter-card { display: grid; grid-template-columns: minmax(180px, .22fr) minmax(0, 1fr); align-items: center; gap: 12px; padding: 13px; border-radius: 22px; }
.ranking-filter-card strong { display: block; margin-top: 3px; color: #071b33; font-size: 1.02rem; font-weight: 950; }
.ranking-filter-card nav { display: flex; flex-wrap: wrap; gap: 7px; }
.ranking-filter-card button { min-height: 32px; padding: 8px 11px; border: 1px solid rgba(217,164,65,.2); border-radius: 999px; background: rgba(255,255,255,.56); color: #071b33; font-size: .74rem; font-weight: 900; cursor: pointer; box-shadow: inset 0 1px 0 rgba(255,255,255,.68); transition: transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease; }
.ranking-filter-card button.active, .ranking-filter-card button:hover { background: linear-gradient(135deg, #d9a441, #f6c85f); border-color: rgba(217,164,65,.38); box-shadow: 0 10px 20px rgba(217,164,65,.16); transform: translateY(-1px); }
.ranking-summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.ranking-summary-grid article { padding: 13px; border-radius: 18px; }
.ranking-summary-grid strong { display: block; margin: 5px 0 2px; color: #071b33; font-size: 1.18rem; font-weight: 950; letter-spacing: -.025em; }
.ranking-summary-grid small { color: #536174; font-size: .76rem; font-weight: 820; line-height: 1.24; }
.ranking-separation-card { display: grid; grid-template-columns: minmax(0, .72fr) minmax(0, 1.28fr); gap: 12px; align-items: center; padding: 15px; border-radius: 22px; background: radial-gradient(circle at 92% 10%, rgba(34,197,94,.12), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.94), rgba(240,247,232,.9)); }
.ranking-separation-tracks { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
.ranking-separation-tracks article { display: grid; gap: 6px; min-height: 112px; padding: 11px; border: 1px solid rgba(34,197,94,.16); border-radius: 16px; background: rgba(255,255,255,.52); }
.ranking-separation-tracks strong { color: #071b33; font-size: .82rem; font-weight: 950; }
.ranking-separation-tracks span { color: #166534; letter-spacing: .04em; }
.ranking-separation-tracks p { font-size: .73rem; line-height: 1.24; }
.ranking-highlights-section, .ranking-areas-section { padding: 14px; border-radius: 22px; }
.ranking-youth-section { border-color: rgba(249,115,22,.18); background: radial-gradient(circle at 94% 6%, rgba(249,115,22,.13), transparent 30%), rgba(255,248,234,.88); }
.ranking-highlights-section > header, .ranking-areas-section > header { margin-bottom: 10px; }
.ranking-highlight-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.ranking-highlight-grid article { display: grid; grid-template-columns: auto 1fr; gap: 8px; align-items: start; padding: 11px; border: 1px solid rgba(217,164,65,.14); border-radius: 18px; background: rgba(255,255,255,.54); box-shadow: inset 0 1px 0 rgba(255,255,255,.64); transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
.ranking-highlight-grid article:hover { border-color: rgba(217,164,65,.28); box-shadow: 0 12px 24px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.72); transform: translateY(-2px); }
.ranking-highlight-grid article.is-youth-highlight { border-color: rgba(249,115,22,.18); background: linear-gradient(135deg, rgba(255,255,255,.62), rgba(255,243,229,.64)); }
.ranking-highlight-grid i, .ranking-areas-grid i, .ranking-youth-grid i { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 13px; background: rgba(217,164,65,.14); font-style: normal; font-size: 1rem; }
.ranking-highlight-grid h3 { margin: 3px 0 2px; color: #071b33; font: 950 1rem/1.02 Georgia, serif; letter-spacing: -.035em; }
.ranking-highlight-grid strong { display: block; color: #071b33; font-size: .84rem; font-weight: 950; }
.ranking-highlight-grid p { margin-top: 5px; font-size: .76rem; line-height: 1.26; }
.ranking-highlight-grid footer { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 9px; }
.ranking-highlight-grid small { padding: 5px 8px; border-radius: 999px; background: rgba(7,27,51,.06); color: #071b33; font-size: .66rem; font-weight: 950; }
.ranking-highlight-grid small:first-child { background: rgba(217,164,65,.14); color: #8a5b13; }
.ranking-empty-note { margin: 0; padding: 10px 11px; border: 1px solid rgba(217,164,65,.14); border-radius: 14px; background: rgba(255,255,255,.52); color: #536174; font-size: .78rem; font-weight: 850; }
.ranking-criteria-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
.ranking-criteria-list strong, .ranking-criteria-list span { display: inline-flex; align-items: center; min-height: 28px; padding: 6px 9px; border-radius: 999px; font-size: .7rem; font-weight: 950; }
.ranking-criteria-list strong { background: rgba(7,27,51,.92); color: #f6c85f; }
.ranking-criteria-list span { border: 1px solid rgba(217,164,65,.14); background: rgba(255,255,255,.54); color: #071b33; }
.ranking-areas-grid { display: grid; grid-template-columns: repeat(8, minmax(0, 1fr)); gap: 8px; }
.ranking-areas-grid article { display: grid; gap: 7px; align-content: start; min-height: 128px; padding: 10px; border: 1px solid rgba(217,164,65,.12); border-radius: 16px; background: rgba(255,255,255,.52); box-shadow: inset 0 1px 0 rgba(255,255,255,.62); transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
.ranking-areas-grid article:hover { border-color: rgba(217,164,65,.26); box-shadow: 0 10px 22px rgba(7,27,51,.065), inset 0 1px 0 rgba(255,255,255,.72); transform: translateY(-2px); }
.ranking-areas-grid strong { color: #071b33; font-size: .8rem; font-weight: 950; }
.ranking-areas-grid p { font-size: .72rem; line-height: 1.22; }
.ranking-youth-card { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 12px; align-items: center; padding: 15px; border-radius: 22px; background: radial-gradient(circle at 92% 8%, rgba(249,115,22,.16), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.92), rgba(255,241,228,.86)); }
.ranking-resgatados-note { background: radial-gradient(circle at 92% 8%, rgba(217,164,65,.16), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.92), rgba(247,238,220,.86)); }
.ranking-youth-card > div:first-child > strong { display: inline-flex; margin-top: 10px; padding: 7px 9px; border-radius: 999px; background: rgba(249,115,22,.12); color: #b45309; font-size: .72rem; font-weight: 950; }
.ranking-youth-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
.ranking-youth-grid article { display: grid; gap: 7px; padding: 10px; border: 1px solid rgba(249,115,22,.14); border-radius: 16px; background: rgba(255,255,255,.52); }
.ranking-youth-grid strong { color: #071b33; font-size: .8rem; font-weight: 950; }
.ranking-youth-grid p { font-size: .72rem; line-height: 1.22; }
.ranking-care-card { display: grid; grid-template-columns: minmax(220px, .55fr) minmax(0, 1fr); gap: 14px; align-items: start; padding: 15px; border-radius: 20px; background: radial-gradient(circle at 92% 12%, rgba(246,200,95,.12), transparent 36%), linear-gradient(135deg, #12345a, #0b2748); box-shadow: 0 14px 30px rgba(7,27,51,.12), inset 0 1px 0 rgba(255,248,234,.1); }
.ranking-care-card span { color: #f6c85f; }
.ranking-care-card h2 { color: #fff8ea; }
.ranking-care-card p { color: rgba(255,248,234,.86); }
.ranking-care-card ul { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px 12px; margin: 0; padding-left: 17px; color: rgba(255,248,234,.86); font-size: .76rem; font-weight: 780; line-height: 1.28; }
.ranking-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 4px; padding-top: 2px; }
.ranking-actions a { display: inline-flex; justify-content: center; align-items: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(246,200,95,.34); border-radius: 999px; background: #fff8ea; color: #071b33; font-size: .8rem; font-weight: 950; box-shadow: 0 10px 20px rgba(7,27,51,.12); transition: transform .2s ease, box-shadow .2s ease, background .2s ease; }
.ranking-actions a:hover { box-shadow: 0 14px 26px rgba(7,27,51,.14); transform: translateY(-1px); }
.ranking-actions a:nth-child(2), .ranking-actions a:nth-child(4) { background: rgba(7,27,51,.92); color: #f6c85f; }
.ranking-actions a:nth-child(3) { background: linear-gradient(135deg, #d9a441, #f6c85f); color: #071b33; }
.map-summary { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 14px; }
.map-summary article { padding: 14px; border: 1px solid rgba(217,164,65,.18); border-radius: 18px; background: rgba(255,248,234,.84); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.map-summary span { display: block; color: #8a5b13; font-size: .76rem; font-weight: 950; letter-spacing: .06em; text-transform: uppercase; }
.map-summary strong { display: block; margin-top: 3px; color: #071b33; font-size: 1.5rem; font-weight: 950; letter-spacing: -.05em; }
.map-summary small { color: #445164; font-size: .8rem; font-weight: 820; }
.map-empty-state { max-width: 1380px; margin: 0 auto 14px; padding: 14px 16px; border: 1px solid rgba(217,164,65,.18); border-radius: 20px; background: rgba(255,248,234,.88); box-shadow: 0 12px 28px rgba(7,27,51,.07), inset 0 1px 0 rgba(255,255,255,.64); }
.map-empty-state strong { display: block; color: #071b33; font-size: 1rem; font-weight: 950; }
.map-empty-state p { margin: 4px 0 0; color: #445164; font-size: .84rem; font-weight: 780; line-height: 1.34; }
.map-card { position: relative; overflow: hidden; margin-bottom: 14px; padding: 18px; border: 1px solid rgba(246,200,95,.34); border-radius: 28px; background: #020a1c; box-shadow: 0 24px 58px rgba(7,27,51,.2), inset 0 1px 0 rgba(255,248,234,.14); isolation: isolate; }
.map-sky { position: absolute; inset: 0; z-index: -3; background: url('/images/familia-resgate/minha-caminhada/mapa-caminhada-cinematico.png') center 48% / cover no-repeat; opacity: .64; filter: saturate(1.12) contrast(1.08) brightness(.9); }
.journey-jovem .map-sky { background-image: url('/images/familia-resgate/minha-caminhada/mapa-caminhada-jovens%20-%20cinematico.png'); opacity: .72; filter: saturate(1.18) contrast(1.08) brightness(.92); }
.map-card::before { content: ''; position: absolute; inset: 0; z-index: -2; background: radial-gradient(circle at 50% 42%, rgba(246,200,95,.24), transparent 30%), linear-gradient(90deg, rgba(2,10,28,.82), rgba(2,10,28,.32) 32%, rgba(2,10,28,.32) 70%, rgba(2,10,28,.82)), linear-gradient(180deg, rgba(2,10,28,.24), rgba(2,10,28,.66)); }
.map-card::after { content: ''; position: absolute; inset: 12px; z-index: -1; border: 1px solid rgba(246,200,95,.18); border-radius: 22px; pointer-events: none; }
.map-glow { position: absolute; inset: auto 10% 28px; height: 130px; z-index: -1; background: radial-gradient(ellipse, rgba(246,200,95,.24), transparent 66%); filter: blur(18px); }
.map-card-header { display: flex; justify-content: space-between; gap: 18px; align-items: flex-start; margin-bottom: 16px; }
.map-card-header span { color: #f6c85f; font-size: .75rem; font-weight: 950; letter-spacing: .12em; text-transform: uppercase; }
.map-card-header h2 { margin: 4px 0 5px; color: #fff8ea; font: 950 clamp(1.45rem, 3vw, 2.4rem)/.96 Georgia, serif; letter-spacing: -.045em; }
.map-card-header p { margin: 0; max-width: 700px; color: rgba(255,248,234,.86); font-size: .88rem; font-weight: 760; line-height: 1.42; }
.map-card-header nav { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
.map-card-header a { display: inline-flex; align-items: center; justify-content: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(246,200,95,.46); border-radius: 999px; background: rgba(255,248,234,.1); color: #f6c85f; font-size: .82rem; font-weight: 950; }
.map-card-header a:first-child { background: #fff8ea; color: #071b33; border-color: rgba(255,248,234,.72); }
.map-track-shell { position: relative; min-height: 430px; overflow-x: auto; overflow-y: hidden; padding: 18px 10px 8px; border-radius: 22px; scroll-behavior: smooth; scrollbar-color: rgba(246,200,95,.72) rgba(2,10,28,.36); }
.map-track-shell::-webkit-scrollbar { height: 12px; }
.map-track-shell::-webkit-scrollbar-track { border-radius: 999px; background: rgba(2,10,28,.42); }
.map-track-shell::-webkit-scrollbar-thumb { border-radius: 999px; background: linear-gradient(90deg, #d9a441, #f6c85f); }
.map-track { position: relative; width: 2300px; min-height: 390px; }
.map-path { position: absolute; left: 60px; right: 60px; top: 204px; height: 4px; border-radius: 999px; background: linear-gradient(90deg, rgba(246,200,95,.22), rgba(246,200,95,.72), rgba(246,200,95,.22)); box-shadow: 0 0 24px rgba(246,200,95,.24); }
.map-level { position: absolute; top: calc(150px + (var(--offset, 0px))); left: calc(28px + ((var(--level-index) - 1) * 112px)); display: grid; justify-items: center; gap: 6px; width: 105px; color: #fff8ea; }
.map-level:nth-of-type(2n) { --offset: -54px; }
.map-level:nth-of-type(3n) { --offset: 44px; }
.map-level:nth-of-type(4n) { --offset: -28px; }
.map-level:nth-of-type(5n) { --offset: 62px; }
.map-level:nth-of-type(1) { --level-index: 1; }
.map-level:nth-of-type(2) { --level-index: 2; }
.map-level:nth-of-type(3) { --level-index: 3; }
.map-level:nth-of-type(4) { --level-index: 4; }
.map-level:nth-of-type(5) { --level-index: 5; }
.map-level:nth-of-type(6) { --level-index: 6; }
.map-level:nth-of-type(7) { --level-index: 7; }
.map-level:nth-of-type(8) { --level-index: 8; }
.map-level:nth-of-type(9) { --level-index: 9; }
.map-level:nth-of-type(10) { --level-index: 10; }
.map-level:nth-of-type(11) { --level-index: 11; }
.map-level:nth-of-type(12) { --level-index: 12; }
.map-level:nth-of-type(13) { --level-index: 13; }
.map-level:nth-of-type(14) { --level-index: 14; }
.map-level:nth-of-type(15) { --level-index: 15; }
.map-level:nth-of-type(16) { --level-index: 16; }
.map-level:nth-of-type(17) { --level-index: 17; }
.map-level:nth-of-type(18) { --level-index: 18; }
.map-level:nth-of-type(19) { --level-index: 19; }
.map-level:nth-of-type(20) { --level-index: 20; }
.level-number { padding: 4px 8px; border: 1px solid rgba(246,200,95,.32); border-radius: 999px; background: rgba(2,10,28,.62); color: #f6c85f; font-size: .68rem; font-weight: 950; }
.church { position: relative; display: grid; place-items: end center; width: 82px; height: 86px; filter: drop-shadow(0 12px 16px rgba(0,0,0,.32)); }
.church-glow { position: absolute; inset: 0; border-radius: 50%; background: radial-gradient(circle, rgba(246,200,95,.32), transparent 66%); opacity: .72; }
.church-icon { position: absolute; top: -2px; z-index: 3; display: grid; place-items: center; width: 25px; height: 25px; border: 1px solid rgba(246,200,95,.72); border-radius: 50%; background: rgba(2,10,28,.72); font-size: .8rem; }
.church-tower { position: absolute; bottom: 30px; z-index: 2; width: 18px; height: 40px; border: 1px solid rgba(255,248,234,.58); border-radius: 8px 8px 2px 2px; background: linear-gradient(180deg, #fff8ea, #d6b677 58%, #806034); }
.church-tower::before { content: ''; position: absolute; top: -14px; left: -4px; width: 26px; height: 18px; clip-path: polygon(50% 0, 100% 100%, 0 100%); background: linear-gradient(180deg, #f6c85f, #8a5b13); }
.church-tower span { position: absolute; top: 13px; left: 6px; width: 6px; height: 10px; border-radius: 999px 999px 2px 2px; background: #071b33; }
.church-roof { position: absolute; bottom: 26px; z-index: 1; width: 62px; height: 28px; clip-path: polygon(50% 0, 100% 100%, 0 100%); background: linear-gradient(180deg, #f6c85f, #a96f1a 72%, #57340f); }
.church-body { position: absolute; bottom: 7px; z-index: 2; display: grid; place-items: end center; width: 54px; height: 34px; border: 1px solid rgba(255,248,234,.48); border-radius: 8px 8px 13px 13px; background: linear-gradient(180deg, #fff8ea, #d6b677 58%, #806034); }
.church-body span { width: 13px; height: 20px; border-radius: 999px 999px 3px 3px; background: linear-gradient(180deg, #6b4016, #2d1c10); }
.state-mark { position: absolute; top: 24px; right: 11px; z-index: 4; display: grid; place-items: center; width: 24px; height: 24px; border-radius: 50%; background: #fff8ea; color: #071b33; font-size: .75rem; font-weight: 950; box-shadow: 0 8px 18px rgba(0,0,0,.22); }
.current-badge { display: grid; gap: 1px; padding: 6px 8px; border: 1px solid rgba(246,200,95,.58); border-radius: 12px; background: rgba(2,10,28,.72); text-align: center; box-shadow: 0 0 0 4px rgba(246,200,95,.12); }
.current-badge b { color: #f6c85f; font-size: .68rem; font-weight: 950; }
.current-badge em { color: rgba(255,248,234,.84); font-size: .58rem; font-style: normal; font-weight: 820; }
.level-label { display: grid; gap: 2px; width: 116px; padding: 8px; border: 1px solid rgba(255,248,234,.16); border-radius: 14px; background: rgba(2,10,28,.62); text-align: center; backdrop-filter: blur(10px); }
.level-label strong { color: #fff8ea; font-size: .72rem; font-weight: 950; line-height: 1.08; }
.level-label small { color: #f6c85f; font-size: .62rem; font-weight: 950; }
.level-label em { color: rgba(255,248,234,.74); font-size: .58rem; font-style: normal; font-weight: 760; line-height: 1.16; }
.map-level.is-completed .church-glow, .map-level.is-current .church-glow { opacity: 1; background: radial-gradient(circle, rgba(246,200,95,.6), transparent 68%); }
.map-level.is-current .church { transform: translateY(-4px) scale(1.08); }
.map-level.is-future .church, .map-level.is-next .church { filter: drop-shadow(0 12px 16px rgba(0,0,0,.3)) saturate(.9); }
.map-level.is-locked { opacity: .72; }
.map-level.is-locked .church { filter: drop-shadow(0 10px 14px rgba(0,0,0,.24)) grayscale(.28) saturate(.72); }
.map-level.is-final .church-glow { background: radial-gradient(circle, rgba(246,200,95,.7), transparent 68%); }
.map-footer-grid { display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr); gap: 12px; }
.legend-card, .future-card, .map-recent-logs { padding: 16px; border: 1px solid rgba(217,164,65,.18); border-radius: 22px; background: rgba(255,248,234,.86); box-shadow: 0 14px 32px rgba(7,27,51,.08), inset 0 1px 0 rgba(255,255,255,.64); }
.legend-card header span, .future-card span, .map-recent-logs > span { color: #8a5b13; font-size: .74rem; font-weight: 950; letter-spacing: .1em; text-transform: uppercase; }
.legend-card h2, .future-card h2, .map-recent-logs h2 { margin: 4px 0 12px; color: #071b33; font: 900 1.2rem/1.08 Georgia, serif; }
.legend-list { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.legend-list div { display: grid; gap: 3px; padding: 11px; border: 1px solid rgba(217,164,65,.14); border-radius: 16px; background: rgba(255,255,255,.56); }
.legend-list i { width: 16px; height: 16px; border-radius: 50%; box-shadow: 0 0 0 4px rgba(217,164,65,.12); }
.legend-list i.completed { background: #16a34a; }
.legend-list i.current { background: #f6c85f; }
.legend-list i.future { background: #60a5fa; }
.legend-list i.locked { background: #64748b; }
.legend-list strong { color: #071b33; font-size: .82rem; font-weight: 950; }
.legend-list small, .future-card p { color: #445164; font-size: .8rem; font-weight: 780; line-height: 1.32; }
.future-card p { margin: 0 0 10px; }
.future-card strong { display: inline-flex; padding: 8px 10px; border: 1px solid rgba(217,164,65,.2); border-radius: 999px; background: rgba(217,164,65,.12); color: #8a5b13; font-size: .78rem; font-weight: 950; }
.map-recent-logs div { display: grid; gap: 8px; }
.map-recent-logs section { display: flex; justify-content: space-between; gap: 10px; padding: 9px 10px; border: 1px solid rgba(217,164,65,.14); border-radius: 14px; background: rgba(255,255,255,.54); }
.map-recent-logs strong { color: #071b33; font-size: .82rem; font-weight: 950; }
.map-recent-logs small { color: #8a5b13; font-size: .76rem; font-weight: 950; }
@media (max-width: 1100px) {
  .map-summary, .legend-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .level-cards { grid-template-columns: 1fr; }
  .detail-summary-grid, .history-summary-grid, .mentor-reading-fields, .mentor-plan-grid, .mentor-questions-card div, .rules-points-grid, .rules-info-grid, .ranking-summary-grid, .ranking-highlight-grid, .ranking-separation-tracks, .ranking-youth-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .dashboard-grid, .history-content-grid, .history-filter-card, .mentor-main-grid, .rules-principle-card, .rules-validation-card, .ranking-principle-card, .ranking-filter-card, .ranking-separation-card, .ranking-youth-card, .ranking-care-card { grid-template-columns: 1fr; }
  .growth-grid, .ranking-areas-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .weekly-focus-card { flex-direction: column; align-items: flex-start; }
  .history-support-card { position: static; }
  .map-footer-grid { grid-template-columns: 1fr; }
  .map-card-header { flex-direction: column; }
  .map-card-header nav { justify-content: flex-start; }
}
@media (max-width: 760px) {
  .journey-area-main { margin-left: 0; padding: 16px; }
  .area-hero, .level-separation-note, .dashboard-notice { flex-direction: column; align-items: flex-start; }
  .map-summary, .legend-list, .detail-summary-grid, .history-summary-grid, .mentor-reading-card, .mentor-reading-fields, .mentor-plan-grid, .mentor-focus-grid, .mentor-questions-card div, .rules-points-grid, .rules-pillars, .rules-journey-grid, .rules-criteria-columns, .rules-criteria-grid, .rules-separation-card, .rules-separation-tracks, .rules-info-grid, .rules-bottom-grid, .rules-not-card, .rules-care-columns, .rules-not-card ul, .ranking-principles, .ranking-summary-grid, .ranking-highlight-grid, .ranking-separation-tracks, .ranking-areas-grid, .ranking-youth-grid, .ranking-care-card ul, .growth-grid { grid-template-columns: 1fr; }
  .rules-separation-card span { white-space: normal; }
  .rules-section header { flex-direction: column; align-items: flex-start; }
  .rules-section header p { max-width: none; text-align: left; }
  .activity-list div, .history-event header { flex-direction: column; }
  .history-event { grid-template-columns: 1fr; }
  .mentor-reading-card figure { min-height: 220px; }
  .level-current-main { grid-template-columns: 1fr; }
  .map-track { width: 2220px; }
}
</style>
