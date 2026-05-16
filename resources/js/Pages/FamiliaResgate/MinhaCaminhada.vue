<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed, onUnmounted, ref } from 'vue'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const props = defineProps({
  walkingDashboard: { type: Object, default: () => ({}) },
})

const baseRoute = '/familia-resgate/minha-caminhada'
const dashboard = computed(() => props.walkingDashboard || {})
const defaultViewerContext = {
  profileType: 'member',
  canSeeGeneralJourney: false,
  canSeeYouthJourney: false,
  canSeeYouthTeams: false,
  youthMember: false,
  youthLeader: false,
  isGuardian: false,
  isAdmin: false,
  isPastoralLeadership: false,
}
const viewerContext = computed(() => ({ ...defaultViewerContext, ...(dashboard.value.viewerContext || {}) }))

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

const journeyTitleMap = {
  general: { title: 'Caminhada Geral da Igreja', shortTitle: 'Geral da Igreja', route: `${baseRoute}/geral`, icon: '✚', scope: 'Geral' },
  youth: { title: 'Caminhada Jovem dos Resgatados', shortTitle: 'Jovens dos Resgatados', route: `${baseRoute}/jovem`, icon: '🔥', scope: 'Jovem' },
}
const categoryPresentation = {
  presence: { name: 'Presença', icon: '👥', color: '#0ea56b' },
  word: { name: 'Palavra', icon: '📖', color: '#2f80d1' },
  devotional: { name: 'Devocional', icon: '🕯️', color: '#d9a441' },
  service: { name: 'Serviço', icon: '🙌', color: '#69bd45' },
  evangelism: { name: 'Evangelismo', icon: '📣', color: '#f45b12' },
  communion: { name: 'Comunhão', icon: '👥', color: '#2f80d1' },
  formation: { name: 'Formação', icon: '🎓', color: '#e3aa2f' },
}
const statusLabels = {
  received: 'Recebida',
  in_progress: 'Em progresso',
  locked: 'Bloqueada',
  hidden: 'Oculta',
  pending_validation: 'Em validação',
}
const formatDate = (value) => value ? new Intl.DateTimeFormat('pt-PT', { day: '2-digit', month: '2-digit' }).format(new Date(value)) : 'Sem data'
const formatDateTime = (value) => {
  if (!value) {
    return null
  }

  const date = new Date(value)

  if (Number.isNaN(date.getTime())) {
    return null
  }

  return new Intl.DateTimeFormat('pt-PT', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(date)
}
const initials = (value) => String(value || 'MC').split(/\s+/).filter(Boolean).slice(0, 2).map((part) => part[0]).join('').toUpperCase()
const dashboardUpdatedLabel = computed(() => {
  const formattedDate = formatDateTime(dashboard.value.generatedAt || dashboard.value.generated_at)

  return formattedDate ? `Atualizado em ${formattedDate}` : 'Resumo gerado a partir dos dados disponíveis'
})
const levelCatalogFor = (type) => type === 'youth' ? youthLevels : generalLevels
const buildJourney = (payload, type) => {
  if (!payload?.authorized || !payload?.progress?.authorized) {
    return null
  }

  const config = journeyTitleMap[type]
  const levelProgress = payload.progress.level_progress || {}
  const currentLevel = levelProgress.current_level || { number: 1, name: 'Primeiros Passos' }
  const nextLevel = levelProgress.next_level
  const catalog = levelCatalogFor(type)
  const visualLevel = catalog.find((level) => level.number === currentLevel.number) || catalog[0]
  const visualNext = nextLevel ? (catalog.find((level) => level.number === nextLevel.number) || nextLevel) : null
  const points = Number(payload.progress.total_points || 0)
  const target = Number(nextLevel?.required_points || currentLevel.required_points || points || 0)

  return {
    type,
    title: payload.journey?.name || config.title,
    shortTitle: config.shortTitle,
    level: { ...visualLevel, ...currentLevel, icon: visualLevel?.icon || config.icon },
    points,
    pointsText: `${points} pontos ${type === 'youth' ? 'da caminhada jovem' : 'gerais'}`,
    target,
    progressText: nextLevel ? `${points} / ${target}` : `${points} pontos`,
    next: visualNext ? { ...visualNext, ...nextLevel, icon: visualNext?.icon || config.icon } : { ...visualLevel, ...currentLevel, icon: visualLevel?.icon || config.icon },
    missingText: nextLevel ? `Faltam ${Math.max(0, Number(nextLevel.required_points || 0) - points)} pontos` : 'Último nível alcançado',
    positionText: 'Sem ranking comparativo',
    route: config.route,
    icon: config.icon,
  }
}
const generalJourney = computed(() => buildJourney(dashboard.value.general, 'general'))
const youthJourney = computed(() => buildJourney(dashboard.value.youth, 'youth'))
const dashboardLogs = computed(() => [
  ...(dashboard.value.general?.recentLogs || []).map((log) => ({ ...log, scope: 'general' })),
  ...((viewerContext.value.canSeeYouthJourney ? dashboard.value.youth?.recentLogs : []) || []).map((log) => ({ ...log, scope: 'youth' })),
])
const dashboardAchievements = computed(() => [
  ...(dashboard.value.general?.achievements || []).map((achievement) => ({ ...achievement, scope: 'Geral' })),
  ...((viewerContext.value.canSeeYouthJourney ? dashboard.value.youth?.achievements : []) || []).map((achievement) => ({ ...achievement, scope: 'Jovem' })),
])
const progressAreas = computed(() => {
  const totals = dashboardLogs.value.reduce((carry, log) => {
    const key = log.category || 'presence'
    carry[key] = (carry[key] || 0) + Number(log.points || 0)
    return carry
  }, {})
  const maxPoints = Math.max(1, ...Object.values(totals))

  return Object.entries(totals).map(([category, points]) => {
    const presentation = categoryPresentation[category] || { name: category, icon: '✦', color: '#d9a441' }

    return {
      name: presentation.name,
      icon: presentation.icon,
      percent: Math.max(1, Math.round((points / maxPoints) * 100)),
      points: `${points} pts`,
      note: 'Com base em registros aprovados recentes',
      color: presentation.color,
    }
  })
})
const recentActivities = computed(() => dashboardLogs.value.map((log) => ({
  title: log.notes || categoryPresentation[log.category]?.name || log.category || 'Registro aprovado',
  date: formatDate(log.created_at),
  points: `+${log.points || 0} pts`,
  status: 'Confirmado',
  icon: initials(log.category),
  scope: log.scope,
})))
const badges = computed(() => dashboardAchievements.value.map((item) => ({
  name: item.achievement?.name || 'Conquista',
  detail: statusLabels[item.status] || item.status || 'Registrada',
  icon: item.achievement?.icon || '🏅',
  scope: item.scope,
})))
const mentorInsight = computed(() => {
  const mentor = dashboard.value.general?.mentor || {}

  return {
    summary: mentor.title || 'Sem leitura personalizada ainda.',
    strongArea: mentor.analysis_type || 'Aguardando registros aprovados.',
    nextStep: mentor.source === 'pre_approved_template' ? 'Orientação pré-aprovada disponível.' : 'Aguardando dados suficientes.',
    suggestion: mentor.body || 'Quando houver registros aprovados suficientes, o Mentor exibirá uma orientação simples e segura para sua caminhada. Isso não substitui acompanhamento pastoral ou discipulado.',
    route: `${baseRoute}/mentor`,
  }
})
const highlights = computed(() => (dashboard.value.general?.highlights || []).map((item) => ({
  title: item.title,
  name: item.category,
  detail: item.description || 'Destaque visível da caminhada.',
  icon: initials(item.category),
  scope: 'general',
})))
const nextSteps = computed(() => {
  const mentor = dashboard.value.general?.mentor

  if (!mentor?.authorized) {
    return []
  }

  return [{
    title: mentor.title,
    action: mentor.body,
    impact: mentor.tone || 'Orientação segura',
    icon: 'MC',
    scope: 'general',
  }]
})

const celebrationThemes = {
  fire: {
    name: 'Chama acesa',
    tone: 'fire',
    primary: '#f97316',
    secondary: '#f6c85f',
    aura: '🔥',
    particles: ['🔥', '✨', '🟠', '💫', '🔥', '✨', '🟡', '💥'],
    headline: 'Uma nova chama foi acesa',
    message: 'O fogo da constância está brilhando mais forte na sua caminhada.',
    blessing: 'Continue avançando com paixão, presença e propósito.',
  },
  nature: {
    name: 'Florescimento',
    tone: 'nature',
    primary: '#22c55e',
    secondary: '#f6c85f',
    aura: '🌿',
    particles: ['🌿', '🍃', '✨', '🌱', '🍃', '🌿', '🌼', '✨'],
    headline: 'Uma nova estação floresceu',
    message: 'Aquilo que foi plantado em secreto começou a criar raízes visíveis.',
    blessing: 'Permaneça firme: fé com raiz gera frutos que permanecem.',
  },
  word: {
    name: 'Palavra viva',
    tone: 'word',
    primary: '#60a5fa',
    secondary: '#f6c85f',
    aura: '📖',
    particles: ['📖', '✨', '🕯️', '💫', '📜', '✨', '🟦', '⭐'],
    headline: 'A Palavra iluminou seu caminho',
    message: 'Cada passo guiado pela verdade fortalece a sua jornada.',
    blessing: 'Que a luz da Palavra continue abrindo direção diante de você.',
  },
  warrior: {
    name: 'Coragem despertada',
    tone: 'warrior',
    primary: '#94a3b8',
    secondary: '#f6c85f',
    aura: '🛡️',
    particles: ['🛡️', '⚔️', '✨', '💥', '🛡️', '⚔️', '⭐', '✨'],
    headline: 'Você avançou com coragem',
    message: 'Sua fé foi fortalecida para permanecer firme nas batalhas certas.',
    blessing: 'Levante-se com honra: propósito também se protege com constância.',
  },
  light: {
    name: 'Luz em expansão',
    tone: 'light',
    primary: '#facc15',
    secondary: '#fff8ea',
    aura: '✨',
    particles: ['✨', '⭐', '💫', '🕯️', '✨', '⭐', '🌟', '💛'],
    headline: 'A sua luz alcançou uma nova medida',
    message: 'O brilho da sua caminhada inspira e aponta para algo maior.',
    blessing: 'Continue sendo farol: pequenos atos fiéis acendem grandes caminhos.',
  },
  crown: {
    name: 'Honra e legado',
    tone: 'crown',
    primary: '#f6c85f',
    secondary: '#fff8ea',
    aura: '👑',
    particles: ['👑', '✨', '⭐', '💫', '🕊️', '✨', '👑', '🌟'],
    headline: 'Um marco de honra foi alcançado',
    message: 'Sua constância deixou marcas que inspiram outras vidas.',
    blessing: 'O legado nasce quando a fidelidade vira testemunho.',
  },
}

const celebrationThemeByIcon = {
  '👣': 'light',
  '✚': 'light',
  '📖': 'word',
  '🌿': 'nature',
  '🔥': 'fire',
  '🚶': 'light',
  '🤝': 'light',
  '📣': 'light',
  '👥': 'light',
  '🙌': 'light',
  '🛤️': 'light',
  '🕯️': 'word',
  '💒': 'light',
  '🧰': 'warrior',
  '🏛️': 'crown',
  '🌾': 'nature',
  '✅': 'light',
  '✨': 'light',
  '🕊️': 'crown',
  '👑': 'crown',
  '🌱': 'nature',
  '💛': 'fire',
  '🛡️': 'warrior',
  '🦁': 'warrior',
  '🏹': 'warrior',
  '📯': 'light',
  '🌍': 'light',
}

const activeCelebration = ref(null)
let celebrationTimer = null

const buildCelebration = (journey) => {
  const level = journey.level
  const theme = celebrationThemes[celebrationThemeByIcon[level.icon] || 'light']
  return {
    ...theme,
    type: journey.type,
    journeyTitle: journey.shortTitle,
    level,
    title: `Nível ${level.number} desbloqueado`,
    subtitle: level.name,
    points: journey.pointsText,
  }
}

const closeCelebration = () => {
  activeCelebration.value = null
  if (celebrationTimer) {
    clearTimeout(celebrationTimer)
    celebrationTimer = null
  }
}

const showLevelCelebration = (journey = generalJourney.value) => {
  if (!journey) {
    return
  }

  closeCelebration()
  activeCelebration.value = buildCelebration(journey)
  celebrationTimer = setTimeout(closeCelebration, 15000)
}

onUnmounted(closeCelebration)

const activeMapType = ref('general')
const canSeeGeneralJourney = computed(() => viewerContext.value.canSeeGeneralJourney)
const canSeeYouthJourney = computed(() => viewerContext.value.canSeeYouthJourney)
const levelDescriptions = {
  1: 'Primeiros passos firmes na casa e na comunhão.',
  2: 'Despertar para uma caminhada mais consciente com Deus.',
  3: 'A Palavra começa a criar raiz e direção diária.',
  4: 'Fé criando raízes para permanecer com constância.',
  5: 'Uma chama interior acesa por presença e devoção.',
  6: 'Passos de discípulo com serviço e aprendizado.',
  7: 'Disponibilidade para servir a casa com alegria.',
  8: 'Esperança compartilhada por palavras e atitudes.',
  9: 'Comunhão guardada com cuidado e presença fiel.',
  10: 'Mãos disponíveis para cooperar e fortalecer pessoas.',
  11: 'Caminho firmado por constância e maturidade.',
  12: 'A família sendo iluminada pela caminhada de fé.',
  13: 'Coração voltado para alcançar e cuidar de vidas.',
  14: 'Serviço aprovado por fidelidade e responsabilidade.',
  15: 'Referência de firmeza, honra e cuidado com a casa.',
  16: 'Sementes lançadas com perseverança e propósito.',
  17: 'Constância que permanece mesmo em estações difíceis.',
  18: 'Fé multiplicada por testemunho, serviço e cuidado.',
  19: 'Vidas alcançadas por uma caminhada madura e disponível.',
  20: 'Legado vivo de fé, serviço, presença e amor.',
}
const activeMapJourney = computed(() => activeMapType.value === 'youth' && canSeeYouthJourney.value ? youthJourney.value : generalJourney.value)
const activeMapLevels = computed(() => activeMapType.value === 'youth' && canSeeYouthJourney.value ? youthLevels : generalLevels)
const mapLevels = computed(() => activeMapLevels.value.map((level) => {
  const journey = activeMapJourney.value

  if (!journey) {
    return null
  }

  const youthMapEnabled = activeMapType.value === 'youth' && canSeeYouthJourney.value
  const pointsRequired = level.number * (youthMapEnabled ? 250 : 400)
  const status = level.number < journey.level.number
    ? 'completed'
    : level.number === journey.level.number
      ? 'current'
      : level.number <= journey.level.number + 3
        ? 'future'
        : 'locked'
  const statusLabel = {
    completed: 'Conquistado',
    current: 'Em progresso',
    future: 'Próximo marco',
    locked: 'Bloqueado',
  }[status]

  return {
    ...level,
    slug: level.name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''),
    route: `${baseRoute}/${youthMapEnabled ? 'jovem' : 'geral'}/niveis/${level.number}`,
    status,
    statusLabel,
    pointsRequired,
    currentPoints: status === 'completed' ? pointsRequired : journey.points,
    progressText: status === 'completed' ? `${pointsRequired} pts alcançados` : `${Math.min(journey.points, pointsRequired)} / ${pointsRequired} pontos`,
    description: levelDescriptions[level.number],
    verse: youthMapEnabled ? '1 Timóteo 4:12' : level.number === 2 ? 'Salmo 119:105' : 'Filipenses 1:6',
  }
}).filter(Boolean))
const visibleJourneys = computed(() => [
  ...(canSeeGeneralJourney.value && generalJourney.value ? [generalJourney.value] : []),
  ...(canSeeYouthJourney.value && youthJourney.value ? [youthJourney.value] : []),
])
const visibleActivities = computed(() => recentActivities.value.filter((item) => item.scope === 'general' || canSeeYouthJourney.value))
const visibleBadges = computed(() => badges.value.filter((item) => item.scope === 'Geral' || canSeeYouthJourney.value))
const visibleHighlights = computed(() => highlights.value.filter((item) => item.scope !== 'youth' || canSeeYouthJourney.value))
const visibleNextSteps = computed(() => nextSteps.value.filter((item) => item.scope === 'general' || canSeeYouthJourney.value))
const canCelebrateRealProgress = (journey) => {
  if (!journey) {
    return false
  }

  const hasPoints = Number(journey.points || 0) > 0
  const hasRecentLog = dashboardLogs.value.some((log) => log.scope === journey.type)
  const hasAchievement = dashboardAchievements.value.some((achievement) => achievement.scope === (journey.type === 'youth' ? 'Jovem' : 'Geral'))
  const hasVisibleHighlight = journey.type === 'general' && visibleHighlights.value.length > 0

  return hasPoints || hasRecentLog || hasAchievement || hasVisibleHighlight
}
const journeyHeroTitle = computed(() => canSeeYouthJourney.value ? 'Duas jornadas, pontos separados.' : 'Sua caminhada geral')
const journeyHeroText = computed(() => canSeeYouthJourney.value ? 'Acompanhe seus trilhos sem misturar pontos gerais, jovens ou coletivos.' : 'Acompanhe seus pontos gerais, seu nível atual e os próximos passos da sua jornada na igreja.')
const journeySummaryTitle = computed(() => canSeeYouthJourney.value ? 'Resumo das Jornadas' : 'Resumo da Caminhada')
const activeMapRoute = computed(() => `${baseRoute}/${activeMapType.value === 'youth' && canSeeYouthJourney.value ? 'jovem' : 'geral'}/mapa`)
const journeyPercent = (journey) => Math.min(100, Math.round((journey.points / Math.max(1, journey.target)) * 100))
</script>

<template>
  <Head title="Minha Caminhada" />

  <div class="walk-page">
    <FamilySidebar active-href="/familia-resgate/minha-caminhada" />

    <main class="walk-main">
      <header class="walk-header">
        <div>
          <p>Central da Família <span>&gt;</span> Minha Caminhada</p>
          <h1>Minha Caminhada</h1>
          <small>Acompanhe sua jornada espiritual, conquistas e próximos passos.</small>
        </div>
        <span>{{ dashboardUpdatedLabel }}</span>
      </header>

      <section class="walk-grid">
        <div class="walk-content">
          <section class="journey-hero">
            <header class="journey-hero-header">
              <h2>{{ journeyHeroTitle }}</h2>
              <p>{{ journeyHeroText }}</p>
            </header>

            <div v-if="visibleJourneys.length" class="journey-list" :class="{ 'single-journey': !canSeeYouthJourney }">
              <article v-for="journey in visibleJourneys" :key="journey.type" class="journey-card" :class="journey.type">
                <div class="journey-seal">{{ journey.icon }}</div>
                <div class="journey-body">
                  <div class="journey-title">
                    <div>
                      <p>{{ journey.title }}</p>
                      <h3>{{ journey.level.name }}</h3>
                      <span>Nível {{ journey.level.number }} de 20</span>
                    </div>
                    <strong>{{ journey.points }}</strong>
                  </div>

                  <div class="journey-progress"><i :style="{ width: `${journeyPercent(journey)}%` }"></i></div>
                  <div class="journey-meta"><span>{{ journey.pointsText }}</span><span>{{ journey.progressText }}</span></div>

                  <footer>
                    <div>
                      <span>Próximo nível: <b>{{ journey.next.name }}</b></span>
                      <span>{{ journey.missingText }}</span>
                      <strong>{{ journey.positionText }}</strong>
                    </div>
                    <div class="journey-actions">
                      <button v-if="canCelebrateRealProgress(journey)" type="button" @click="showLevelCelebration(journey)">Celebrar conquista</button>
                      <Link :href="journey.route">Ver detalhes</Link>
                    </div>
                  </footer>
                </div>
              </article>
            </div>
            <div v-else class="empty-state dark">
              <strong>Caminhada ainda não disponível</strong>
              <span>Assim que seu cadastro estiver vinculado a uma pessoa, a jornada aparecerá aqui com dados reais.</span>
            </div>
          </section>

          <section class="areas-section">
            <h2>Sua caminhada por áreas</h2>
            <div v-if="progressAreas.length" class="areas-grid">
              <article v-for="area in progressAreas" :key="area.name" class="area-card" :style="{ '--area-color': area.color, '--area-percent': `${area.percent * 3.6}deg` }">
                <i>{{ area.icon }}</i>
                <div class="area-ring">
                  <strong>{{ area.percent }}%</strong>
                  <span>{{ area.points }}</span>
                </div>
                <small>{{ area.note }}</small>
                <b>{{ area.name }}</b>
              </article>
            </div>
            <div v-else class="empty-state">
              <strong>Sem áreas calculadas ainda</strong>
              <span>Quando houver registros aprovados, os pontos por área serão exibidos aqui.</span>
            </div>
          </section>

          <section class="mid-grid">
            <article class="panel-card activities-card">
              <header>
                <h2>Atividades recentes</h2>
                <Link :href="`${baseRoute}/historico`">Ver histórico completo</Link>
              </header>
              <div v-if="visibleActivities.length" class="activity-list">
                <div v-for="activity in visibleActivities" :key="activity.title" class="activity-item" :class="{ youth: activity.scope === 'youth' }">
                  <i>{{ activity.icon }}</i>
                  <div><strong>{{ activity.title }}</strong><span>{{ activity.date }}</span></div>
                  <b>{{ activity.points }}</b>
                  <small>{{ activity.status }}</small>
                </div>
              </div>
              <div v-else class="empty-state compact">
                <strong>Nenhuma atividade aprovada</strong>
                <span>O histórico real aparecerá depois dos primeiros registros aprovados.</span>
              </div>
            </article>

            <article class="panel-card badges-card">
              <header>
                <h2>Conquistas e badges</h2>
                <Link :href="`${baseRoute}/conquistas`">Ver todas as conquistas</Link>
              </header>
              <div v-if="visibleBadges.length" class="badges-grid">
                <div v-for="badge in visibleBadges" :key="`${badge.scope}-${badge.name}`" class="badge-item" :class="{ youth: badge.scope === 'Jovem' }">
                  <i>{{ badge.icon }}</i>
                  <strong>{{ badge.name }}</strong>
                  <span>{{ badge.detail }}</span>
                  <small>{{ badge.scope }}</small>
                </div>
              </div>
              <div v-else class="empty-state compact">
                <strong>Nenhuma conquista recebida</strong>
                <span>Conquistas reais serão listadas quando forem concedidas ou estiverem em progresso.</span>
              </div>
            </article>
          </section>

          <section v-if="canSeeGeneralJourney" class="walk-map-card" :class="{ 'is-youth': activeMapType === 'youth' && canSeeYouthJourney }">
            <div class="walk-map-background" aria-hidden="true"></div>
            <div class="walk-map-glow" aria-hidden="true"></div>
            <div class="walk-map-content">
              <header class="walk-map-header">
                <div>
                  <span>Mapa da Caminhada</span>
                  <h2>20 igrejas, 20 marcos de conquista</h2>
                  <p>{{ activeMapJourney.pointsText }} · Nível {{ activeMapJourney.level.number }} de 20 · Cada marco representa uma estação da sua história com Deus.</p>
                </div>
              </header>
              <nav class="walk-map-tabs" aria-label="Alternar visão do mapa">
                <button type="button" class="walk-map-tab" :class="{ active: activeMapType === 'general' }" @click="activeMapType = 'general'"><span>🛡️</span> Mapa geral</button>
                <button v-if="canSeeYouthJourney" type="button" class="walk-map-tab youth" :class="{ active: activeMapType === 'youth' }" @click="activeMapType = 'youth'"><span>🔥</span> Mapa do trilho jovem</button>
              </nav>
              <div class="walk-map-stage">
                <div class="walk-map-track-shell" aria-label="Mapa da caminhada com rolagem horizontal">
                  <div class="walk-map-track">
                    <div class="walk-map-path" aria-hidden="true"></div>
                    <Link v-for="level in mapLevels" :key="level.number" class="walk-map-level" :class="[`is-${level.status}`, { 'is-final': level.number === 20 }]" :href="level.route" :aria-label="`Abrir marco do nível ${level.number}: ${level.name}`">
                      <span v-if="level.status === 'completed'" class="walk-map-state-mark">✓</span>
                      <span v-else-if="level.status === 'locked'" class="walk-map-state-mark">🔒</span>
                      <span class="walk-map-level-number">Nível {{ level.number }}</span>
                      <span class="walk-map-church" aria-hidden="true">
                        <span class="walk-map-church-glow"></span>
                        <span class="walk-map-church-tower"><span></span></span>
                        <span class="walk-map-church-roof"></span>
                        <span class="walk-map-church-body">
                          <span class="walk-map-church-window"></span>
                          <span class="walk-map-church-door"></span>
                        </span>
                      </span>
                      <span v-if="level.status === 'current'" class="walk-map-current-badge">
                        <b>Você está aqui</b>
                        <em>Nível {{ level.number }} de 20</em>
                      </span>
                      <span class="walk-map-level-label">
                        <strong>{{ level.name }}</strong>
                        <small>{{ level.progressText }}</small>
                        <em>{{ level.statusLabel }}</em>
                      </span>
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>

        <aside class="walk-side">
          <section class="summary-card">
            <header><h2>{{ journeySummaryTitle }}</h2></header>
            <article v-for="journey in visibleJourneys" :key="journey.shortTitle" :class="journey.type">
              <i>{{ journey.icon }}</i>
              <div><strong>{{ journey.shortTitle }}</strong><b>{{ journey.points }} pts</b><span>Nível {{ journey.level.number }} · {{ journey.level.name }}</span></div>
            </article>
            <div v-if="!visibleJourneys.length" class="empty-state dark compact">
              <strong>Sem jornada liberada</strong>
              <span>Aguardando vínculo de pessoa para montar o resumo.</span>
            </div>
            <Link v-if="visibleJourneys.length" :href="activeMapRoute">Ver meu mapa da caminhada</Link>
          </section>

          <section class="mentor-card">
            <img src="/images/familia-resgate/minha-caminhada/mentor-caminhada.png" alt="Mentor da caminhada" />
            <div>
              <header><h2>Mentor da Caminhada</h2><span>Apoio</span></header>
              <dl>
                <div><dt>Resumo da semana</dt><dd>{{ mentorInsight.summary }}</dd></div>
                <div><dt>Área forte</dt><dd>{{ mentorInsight.strongArea }}</dd></div>
                <div><dt>Próximo passo</dt><dd>{{ mentorInsight.nextStep }}</dd></div>
                <div><dt>Sugestão espiritual</dt><dd>{{ mentorInsight.suggestion }}</dd></div>
              </dl>
              <Link :href="mentorInsight.route">Ver plano personalizado</Link>
            </div>
          </section>

          <section class="side-panel highlights-card">
            <header><h2>Destaques do mês</h2><Link :href="`${baseRoute}/ranking`">Ver destaques</Link></header>
            <article v-for="item in visibleHighlights" :key="item.title" :class="{ youth: item.scope === 'youth' }">
              <i>{{ item.icon }}</i>
              <div><span>{{ item.title }}</span><strong>{{ item.name }}</strong><small>{{ item.detail }}</small></div>
            </article>
            <p>{{ visibleHighlights.length ? 'Reconhecimento saudável de constância e serviço.' : 'Ainda não há destaques reais visíveis para esta caminhada.' }}</p>
          </section>

          <section class="recognition-card" aria-label="Reconhecimento saudável">
            <i>✦</i>
            <div>
              <span>{{ visibleHighlights.length ? 'Reconhecimento saudável' : 'Sem destaque ativo' }}</span>
              <h2>{{ visibleHighlights.length ? 'Reconhecimento saudável' : 'Ainda não há destaques visíveis' }}</h2>
              <p>{{ visibleHighlights.length ? 'Veja os destaques da caminhada sem competição espiritual, com foco em constância, serviço e crescimento.' : 'Quando houver registros aprovados e reconhecimento saudável, eles aparecerão aqui.' }}</p>
              <Link v-if="visibleHighlights.length" :href="`${baseRoute}/ranking`">Ver destaques da caminhada</Link>
            </div>
          </section>

          <section class="side-panel steps-card">
            <header><h2>Próximos passos com propósito</h2><Link :href="`${baseRoute}/regras`">Ver como avançar</Link></header>
            <ul v-if="visibleNextSteps.length">
              <li v-for="step in visibleNextSteps" :key="step.action" :class="{ youth: step.scope === 'youth' }">
                <i>{{ step.icon }}</i>
                <div><strong>{{ step.title }}</strong><span>{{ step.action }}</span><small>{{ step.impact }}</small></div>
              </li>
            </ul>
            <div v-else class="empty-state compact">
              <strong>Sem próximos passos calculados</strong>
              <span>O Mentor exibirá sugestões quando os dados reais forem suficientes.</span>
            </div>
          </section>
        </aside>
      </section>
    </main>

    <Transition name="level-celebration">
      <section
        v-if="activeCelebration"
        class="level-celebration-overlay"
        :class="`theme-${activeCelebration.tone}`"
        :style="{ '--celebration-primary': activeCelebration.primary, '--celebration-secondary': activeCelebration.secondary }"
        role="dialog"
        aria-modal="true"
        aria-live="polite"
      >
        <div class="celebration-sky" aria-hidden="true"></div>
        <div class="celebration-rays" aria-hidden="true"></div>
        <span v-for="(particle, index) in activeCelebration.particles" :key="`${particle}-${index}`" class="celebration-particle" :style="{ '--particle-index': index }">{{ particle }}</span>
        <article class="celebration-card">
          <div class="celebration-orbit" aria-hidden="true">
            <span>{{ activeCelebration.aura }}</span>
          </div>
          <p>{{ activeCelebration.journeyTitle }} · {{ activeCelebration.points }}</p>
          <strong>Parabéns!</strong>
          <h2>{{ activeCelebration.title }}</h2>
          <div class="celebration-level">
            <i>{{ activeCelebration.level.icon }}</i>
            <div>
              <span>Nível {{ activeCelebration.level.number }} de 20</span>
              <b>{{ activeCelebration.subtitle }}</b>
            </div>
          </div>
          <h3>{{ activeCelebration.headline }}</h3>
          <small>{{ activeCelebration.message }}</small>
          <em>{{ activeCelebration.blessing }}</em>
          <button type="button" @click="closeCelebration">Continuar caminhada</button>
        </article>
      </section>
    </Transition>
  </div>
</template>

<style scoped>
.walk-page { min-height: 100vh; background: radial-gradient(circle at 12% 0%, rgba(217,164,65,.13), transparent 28%), radial-gradient(circle at 92% 12%, rgba(7,27,51,.06), transparent 26%), linear-gradient(135deg, #fff8ea, #f7efe1); }
.walk-main { margin-left: 80px; padding: 16px 22px 26px; }
.walk-header { max-width: 1280px; margin: 0 auto 12px; display: flex; justify-content: space-between; align-items: end; gap: 12px; }
.walk-header p { margin: 0; color: #334155; font-size: .72rem; font-weight: 800; }
.walk-header p span { color: #d9a441; }
.walk-header h1 { margin: 2px 0 1px; color: #071b33; font: 900 2rem/1 Georgia, serif; letter-spacing: -.04em; }
.walk-header small { color: #516070; font-size: .75rem; font-weight: 720; }
.walk-header > span { margin-bottom: 4px; padding: 7px 11px; border: 1px solid rgba(217,164,65,.28); border-radius: 999px; background: rgba(255,248,234,.86); color: #071b33; font-size: .68rem; font-weight: 900; white-space: nowrap; box-shadow: 0 10px 22px rgba(7,27,51,.06); }
.walk-grid { max-width: 1280px; margin: 0 auto; display: grid; grid-template-columns: minmax(0, 1fr) 286px; gap: 16px; align-items: stretch; }
.walk-content { display: grid; gap: 12px; align-content: start; }
.walk-side { display: grid; grid-template-rows: repeat(5, auto); gap: 12px; align-content: start; }
a { text-decoration: none; }

.journey-hero, .summary-card { border: 1px solid rgba(246,200,95,.22); border-radius: 22px; background: linear-gradient(135deg, #071b33, #0b2748); color: #fff8ea; box-shadow: 0 18px 38px rgba(7,27,51,.16); }
.journey-hero { padding: 14px; }
.journey-hero-header { display: grid; gap: 4px; margin-bottom: 11px; }
.journey-hero h2 { margin: 0; color: #fff8ea; font: 850 .92rem/1.1 Georgia, serif; }
.journey-hero-header p { margin: 0; max-width: 620px; color: rgba(255,248,234,.78); font-size: .72rem; font-weight: 760; line-height: 1.35; }
.journey-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.journey-list.single-journey { grid-template-columns: minmax(0, 1fr); }
.journey-card { display: grid; grid-template-columns: 72px 1fr; gap: 12px; min-height: 166px; padding: 12px; border: 1px solid rgba(246,200,95,.18); border-radius: 18px; background: radial-gradient(circle at 86% 10%, rgba(246,200,95,.13), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.055), rgba(255,248,234,.02)); box-shadow: inset 0 1px 0 rgba(255,248,234,.08); }
.journey-card.youth { background: radial-gradient(circle at 86% 10%, rgba(245,158,11,.24), transparent 36%), linear-gradient(135deg, rgba(255,248,234,.06), rgba(255,248,234,.025)); }
.journey-seal { display: grid; place-items: center; width: 66px; height: 78px; border: 2px solid rgba(217,164,65,.78); border-radius: 19px 19px 28px 28px; background: linear-gradient(160deg, rgba(11,39,72,.95), rgba(7,27,51,.62)); color: #f6c85f; font-size: 1.82rem; box-shadow: inset 0 0 22px rgba(246,200,95,.13), 0 10px 20px rgba(0,0,0,.14); }
.journey-card.youth .journey-seal { border-color: rgba(245,158,11,.8); color: #f59e0b; }
.journey-body { display: grid; gap: 7px; }
.journey-title, .journey-meta, .journey-card footer { display: flex; justify-content: space-between; gap: 8px; }
.journey-title p, .journey-title span, .journey-meta span, .journey-card footer span, .journey-card footer strong { margin: 0; color: rgba(255,248,234,.9); font-size: .69rem; font-weight: 820; }
.journey-title h3 { margin: 2px 0 3px; color: #f6c85f; font: 850 .94rem/1.08 Georgia, serif; }
.journey-card.youth .journey-title h3 { color: #f59e0b; }
.journey-title > strong { color: #fff8ea; font-size: 1.42rem; align-self: center; letter-spacing: -.04em; text-shadow: 0 8px 20px rgba(0,0,0,.22); }
.journey-progress { height: 9px; padding: 1px; overflow: hidden; border-radius: 999px; background: rgba(255,248,234,.16); box-shadow: inset 0 1px 4px rgba(0,0,0,.28); }
.journey-progress i { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #d9a441, #f6c85f); box-shadow: 0 0 18px rgba(246,200,95,.4); }
.journey-card.youth .journey-progress i { background: linear-gradient(90deg, #f59e0b, #f6c85f); box-shadow: 0 0 18px rgba(245,158,11,.42); }
.journey-card footer { align-items: end; }
.journey-card footer div { display: grid; gap: 2px; }
.journey-card footer b { color: #fff8ea; }
.journey-actions { display: grid; justify-items: end; gap: 6px; }
.journey-actions button { padding: 7px 11px; border: 1px solid rgba(246,200,95,.54); border-radius: 999px; background: linear-gradient(135deg, rgba(246,200,95,.18), rgba(255,248,234,.08)); color: #f6c85f; font-size: .64rem; font-weight: 950; white-space: nowrap; cursor: pointer; box-shadow: inset 0 1px 0 rgba(255,248,234,.12), 0 8px 16px rgba(0,0,0,.1); }
.journey-card footer a, .summary-card > a, .mentor-card a { padding: 7px 11px; border: 1px solid rgba(246,200,95,.5); border-radius: 999px; color: #f6c85f; font-size: .66rem; font-weight: 900; white-space: nowrap; }
.journey-card.youth .journey-actions button { border-color: rgba(245,158,11,.62); color: #f59e0b; background: linear-gradient(135deg, rgba(245,158,11,.18), rgba(255,248,234,.08)); }
.journey-card.youth footer a { border-color: rgba(245,158,11,.62); color: #f59e0b; }

.summary-card { padding: 13px; box-shadow: 0 16px 34px rgba(7,27,51,.16), inset 0 1px 0 rgba(255,248,234,.08); }
.summary-card h2, .side-panel h2, .mentor-card h2, .recognition-card h2, .panel-card h2, .areas-section h2, .walk-map-card h2 { margin: 0; font: 850 .96rem/1.1 Georgia, serif; }
.summary-card article { display: grid; grid-template-columns: 44px 1fr; gap: 10px; align-items: center; margin-top: 9px; padding: 10px; border: 1px solid rgba(246,200,95,.2); border-radius: 15px; background: linear-gradient(135deg, rgba(255,248,234,.1), rgba(255,248,234,.045)); box-shadow: inset 0 1px 0 rgba(255,248,234,.08); }
.summary-card i { display: grid; place-items: center; width: 44px; height: 50px; border: 2px solid #d9a441; border-radius: 14px 14px 20px 20px; color: #f6c85f; font-style: normal; font-size: 1.2rem; background: rgba(7,27,51,.32); }
.summary-card .youth i { border-color: #f59e0b; color: #f59e0b; }
.summary-card div { display: grid; gap: 2px; }
.summary-card strong, .summary-card b, .summary-card span { color: #fff8ea; font-size: .7rem; }
.summary-card b { color: #f6c85f; font-size: .9rem; }
.summary-card > a { display: flex; justify-content: center; margin-top: 10px; background: rgba(255,248,234,.08); color: #f6c85f; border-color: rgba(246,200,95,.42); box-shadow: inset 0 1px 0 rgba(255,248,234,.08); }

.areas-section, .panel-card, .side-panel { padding: 13px; border: 1px solid rgba(217,164,65,.16); border-radius: 20px; background: rgba(255,248,234,.82); box-shadow: 0 12px 28px rgba(7,27,51,.075), inset 0 1px 0 rgba(255,255,255,.58); }
.side-panel { display: grid; align-content: start; }
.areas-section h2, .panel-card h2, .side-panel h2 { color: #071b33; }
.empty-state { display: grid; gap: 7px; margin-top: 10px; padding: 16px; border: 1px dashed rgba(217,164,65,.3); border-radius: 16px; background: rgba(255,255,255,.48); color: #071b33; }
.empty-state.dark { border-color: rgba(246,200,95,.28); background: rgba(255,248,234,.08); color: #fff8ea; }
.empty-state.compact { padding: 11px; }
.empty-state strong { color: inherit; font-size: .74rem; font-weight: 950; }
.empty-state span { color: inherit; opacity: .78; font-size: .66rem; font-weight: 780; line-height: 1.35; }
.areas-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 9px; margin-top: 10px; }
.area-card { display: grid; justify-items: center; align-content: start; min-height: 152px; padding: 11px 7px 12px; border: 1px solid rgba(217,164,65,.16); border-radius: 16px; background: linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,248,234,.68)); text-align: center; box-shadow: 0 10px 22px rgba(7,27,51,.055), inset 0 1px 0 rgba(255,255,255,.72); }
.area-card i { position: relative; z-index: 2; display: grid; place-items: center; width: 38px; height: 38px; margin-bottom: -2px; border-radius: 50%; background: color-mix(in srgb, var(--area-color) 14%, #fff8ea); color: var(--area-color); font-style: normal; font-size: 1.08rem; box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--area-color) 18%, transparent), 0 7px 16px rgba(7,27,51,.06); }
.area-ring { position: relative; display: grid; place-items: center; align-content: center; width: 72px; height: 72px; margin-top: -3px; border-radius: 50%; background: conic-gradient(var(--area-color) var(--area-percent), rgba(217,164,65,.12) 0); box-shadow: 0 8px 18px rgba(7,27,51,.055); }
.area-ring::before { content: ''; position: absolute; inset: 5px; border-radius: inherit; background: linear-gradient(180deg, #fffdf8, #fff8ea); box-shadow: inset 0 2px 8px rgba(7,27,51,.04); }
.area-ring strong, .area-ring span { position: relative; z-index: 1; }
.area-ring strong { color: #071b33; font-size: .94rem; font-weight: 950; letter-spacing: -.04em; line-height: 1; }
.area-ring span { margin-top: 3px; color: #334155; font-size: .55rem; font-weight: 900; line-height: 1; }
.area-card small { margin-top: 2px; color: var(--area-color); font-size: .6rem; font-weight: 950; line-height: 1.15; }
.area-card b { margin-top: 9px; color: #071b33; font-size: .7rem; font-weight: 950; line-height: 1.1; }

.mid-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; align-items: stretch; }
.mid-grid > .panel-card { min-height: 260px; }
.panel-card header, .side-panel header, .walk-map-header, .mentor-card header { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
.panel-card header a, .side-panel header a { color: #071b33; font-size: .64rem; font-weight: 900; }
.activity-list { display: grid; gap: 8px; margin-top: 12px; }
.activity-item { display: grid; grid-template-columns: 42px minmax(0, 1fr) 92px 82px; gap: 10px; align-items: center; min-height: 38px; padding: 5px 0; border-bottom: 1px solid rgba(217,164,65,.1); }
.activity-item:last-child { border-bottom: 0; }
.activity-item i { display: grid; place-items: center; width: 36px; height: 36px; border: 1px solid rgba(217,164,65,.22); border-radius: 13px 13px 18px 18px; background: linear-gradient(145deg, rgba(217,164,65,.16), rgba(255,248,234,.8)); color: #8a5b13; font: 950 .62rem/1 Inter, system-ui, sans-serif; letter-spacing: .04em; box-shadow: inset 0 1px 0 rgba(255,255,255,.7), 0 8px 16px rgba(7,27,51,.055); }
.activity-item.youth i { border-color: rgba(245,158,11,.28); background: linear-gradient(145deg, rgba(245,158,11,.17), rgba(255,248,234,.78)); color: #c2410c; }
.activity-item div { display: grid; }
.activity-item strong, .activity-item span, .activity-item b, .activity-item small { color: #071b33; font-size: .68rem; }
.activity-item strong { font-weight: 950; }
.activity-item span { color: #64748b; }
.activity-item b { color: #0b2748; text-align: right; }
.activity-item small { justify-self: end; padding: 4px 9px; border-radius: 999px; background: rgba(22,163,74,.1); color: #16a34a; font-size: .62rem; font-weight: 950; text-align: right; }

.badges-card { min-height: 218px; background: radial-gradient(circle at 50% 0%, rgba(246,200,95,.1), transparent 38%), rgba(255,248,234,.86); }
.badges-grid { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 10px; margin-top: 12px; }
.badge-item { display: grid; justify-items: center; align-content: start; gap: 6px; min-height: 142px; padding: 2px 4px 7px; text-align: center; }
.badge-item i { position: relative; display: grid; place-items: center; width: 58px; height: 66px; border: 2px solid #d9a441; border-radius: 19px 19px 27px 27px; background: radial-gradient(circle at 50% 18%, rgba(246,200,95,.18), transparent 40%), linear-gradient(135deg, #071b33, #0b2748); color: #f6c85f; font-size: 1.28rem; font-style: normal; box-shadow: 0 12px 22px rgba(7,27,51,.13), inset 0 0 18px rgba(246,200,95,.12); }
.badge-item i::after { content: ''; position: absolute; inset: 6px; border: 1px solid rgba(255,248,234,.1); border-radius: 17px 17px 25px 25px; }
.badge-item.youth i { border-color: #f59e0b; color: #f59e0b; box-shadow: 0 14px 26px rgba(7,27,51,.16), 0 0 18px rgba(245,158,11,.18), inset 0 0 20px rgba(245,158,11,.13); }
.badge-item strong { max-width: 94px; color: #071b33; font-size: .7rem; font-weight: 950; line-height: 1.08; }
.badge-item span { color: #64748b; font-size: .61rem; font-weight: 850; }
.badge-item small { padding: 4px 9px; border-radius: 999px; background: rgba(217,164,65,.14); color: #8a5b13; font-size: .58rem; font-weight: 950; }
.badge-item.youth small { background: rgba(245,158,11,.16); color: #c2410c; }

.mentor-card { position: relative; min-height: 252px; overflow: hidden; border: 1px solid rgba(246,200,95,.3); border-radius: 20px; background: #071b33; color: #fff8ea; box-shadow: 0 16px 34px rgba(7,27,51,.16); isolation: isolate; }
.mentor-card img { position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; object-fit: cover; object-position: center center; opacity: 1; filter: saturate(1.12) contrast(1.04) brightness(1.04); }
.mentor-card::before { content: ''; position: absolute; inset: 0; z-index: 2; background: linear-gradient(90deg, rgba(2,10,28,.78), rgba(2,10,28,.5) 36%, rgba(2,10,28,.1) 74%, rgba(2,10,28,.04)), linear-gradient(180deg, rgba(2,10,28,.1), transparent 42%, rgba(2,10,28,.42)); pointer-events: none; }
.mentor-card::after { content: ''; position: absolute; inset: 10px auto 10px 10px; z-index: 3; width: min(360px, 72%); border-radius: 16px; background: linear-gradient(90deg, rgba(2,10,28,.58), rgba(2,10,28,.26), transparent); pointer-events: none; }
.mentor-card > div { position: relative; z-index: 4; display: grid; gap: 7px; max-width: 350px; padding: 13px; }
.mentor-card header span { padding: 3px 7px; border-radius: 999px; background: #d9a441; color: #071b33; font-size: .6rem; font-weight: 950; }
.mentor-card dl { display: grid; gap: 5px; margin: 0; }
.mentor-card dl div { padding: 6px 8px; border: 1px solid rgba(246,200,95,.22); border-radius: 11px; background: rgba(2,10,28,.48); backdrop-filter: blur(4px); box-shadow: inset 0 1px 0 rgba(255,248,234,.07), 0 7px 16px rgba(0,0,0,.12); }
.mentor-card dt { color: #f6c85f; font-size: .6rem; font-weight: 950; }
.mentor-card dd { margin: 2px 0 0; color: #fff8ea; font-size: .68rem; font-weight: 780; line-height: 1.35; text-shadow: 0 2px 7px rgba(0,0,0,.62); }
.mentor-card a { justify-self: start; background: linear-gradient(135deg, #d9a441, #f6c85f); color: #071b33; border-color: rgba(255,248,234,.28); box-shadow: 0 10px 20px rgba(7,27,51,.2); }
.highlights-card, .steps-card { min-height: 0; align-content: start; }
.side-panel article { display: grid; grid-template-columns: 40px 1fr; gap: 10px; align-items: center; min-height: 58px; margin-top: 8px; padding: 4px 0; border-bottom: 1px solid rgba(217,164,65,.1); }
.side-panel article:last-of-type { border-bottom: 0; }
.side-panel article i, .steps-card li i { display: grid; place-items: center; width: 36px; height: 36px; border: 1px solid rgba(217,164,65,.22); border-radius: 13px 13px 18px 18px; background: linear-gradient(145deg, rgba(217,164,65,.15), rgba(255,248,234,.82)); color: #8a5b13; font: 950 .62rem/1 Inter, system-ui, sans-serif; letter-spacing: .04em; box-shadow: inset 0 1px 0 rgba(255,255,255,.68), 0 8px 16px rgba(7,27,51,.055); }
.side-panel article.youth i, .steps-card li.youth i { border-color: rgba(245,158,11,.28); background: linear-gradient(145deg, rgba(245,158,11,.17), rgba(255,248,234,.82)); color: #c2410c; }
.side-panel article div, .steps-card li div { display: grid; gap: 2px; }
.side-panel span, .side-panel strong, .side-panel small, .side-panel p { color: #071b33; font-size: .68rem; }
.side-panel span { color: #8a5b13; font-weight: 900; }
.side-panel p { margin: 10px 0 0; color: #516070; line-height: 1.35; }
.recognition-card { display: grid; grid-template-columns: 36px 1fr; gap: 10px; align-items: center; padding: 10px 11px; border: 1px solid rgba(217,164,65,.22); border-radius: 18px; background: radial-gradient(circle at 90% 8%, rgba(246,200,95,.18), transparent 34%), linear-gradient(135deg, rgba(255,248,234,.94), rgba(255,241,228,.88)); box-shadow: 0 12px 26px rgba(7,27,51,.075), inset 0 1px 0 rgba(255,255,255,.68); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
.recognition-card:hover { border-color: rgba(217,164,65,.38); box-shadow: 0 18px 34px rgba(7,27,51,.11), 0 0 0 3px rgba(246,200,95,.08), inset 0 1px 0 rgba(255,255,255,.74); transform: translateY(-2px); }
.recognition-card > i { display: grid; place-items: center; width: 36px; height: 40px; border: 1px solid rgba(217,164,65,.36); border-radius: 14px 14px 19px 19px; background: linear-gradient(145deg, #071b33, #0b2748); color: #f6c85f; font-style: normal; font-size: .95rem; box-shadow: inset 0 0 16px rgba(246,200,95,.14), 0 8px 16px rgba(7,27,51,.11); }
.recognition-card div { display: grid; gap: 3px; }
.recognition-card span { color: #8a5b13; font-size: .55rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; }
.recognition-card h2 { color: #071b33; font-size: .86rem; }
.recognition-card p { margin: 0; color: #445164; font-size: .64rem; font-weight: 780; line-height: 1.28; }
.recognition-card a { justify-self: start; margin-top: 2px; padding: 6px 9px; border: 1px solid rgba(217,164,65,.38); border-radius: 999px; background: rgba(255,255,255,.56); color: #071b33; font-size: .6rem; font-weight: 950; box-shadow: 0 7px 16px rgba(217,164,65,.12); transition: transform .2s ease, background .2s ease, box-shadow .2s ease; }
.recognition-card a:hover { background: linear-gradient(135deg, #d9a441, #f6c85f); box-shadow: 0 11px 22px rgba(217,164,65,.18); transform: translateY(-1px); }
.steps-card { padding-bottom: 13px; }
.steps-card header h2 { max-width: 176px; }
.steps-card ul { display: grid; gap: 8px; margin: 10px 0 0; padding: 0; list-style: none; }
.steps-card li { display: grid; grid-template-columns: 40px 1fr; gap: 10px; align-items: center; min-height: 58px; padding: 4px 0; border-bottom: 1px solid rgba(217,164,65,.1); }
.steps-card li:last-child { border-bottom: 0; }
.steps-card li strong, .steps-card li span, .steps-card li small { color: #071b33; font-size: .68rem; }
.steps-card li span { font-weight: 900; }
.steps-card li small { color: #64748b; }


.walk-map-card { position: relative; min-width: 0; min-height: 374px; overflow: hidden; border: 1px solid rgba(246,200,95,.32); border-radius: 24px; background: #020a1c; box-shadow: 0 20px 44px rgba(7,27,51,.2), inset 0 1px 0 rgba(255,248,234,.14); isolation: isolate; }
.walk-map-card::before { content: ''; position: absolute; inset: 0; z-index: 2; background: radial-gradient(circle at 50% 46%, rgba(246,200,95,.22), transparent 30%), linear-gradient(90deg, rgba(2,10,28,.64), rgba(2,10,28,.18) 30%, rgba(2,10,28,.16) 70%, rgba(2,10,28,.62)), linear-gradient(180deg, rgba(3,14,31,.22), rgba(3,14,31,.08) 42%, rgba(2,10,28,.72)); pointer-events: none; }
.walk-map-card::after { content: ''; position: absolute; inset: 0; z-index: 3; background: radial-gradient(ellipse at center, transparent 36%, rgba(2,10,28,.3) 72%, rgba(2,10,28,.76) 100%); pointer-events: none; }
.walk-map-background { position: absolute; inset: 0; z-index: 1; background: url('/images/familia-resgate/minha-caminhada/mapa-caminhada-cinematico.png') center 48% / cover no-repeat; filter: saturate(1.18) contrast(1.05) brightness(1.08); opacity: .96; }
.walk-map-card.is-youth .walk-map-background { background-image: url('/images/familia-resgate/minha-caminhada/mapa-caminhada-jovens%20-%20cinematico.png'); filter: saturate(1.2) contrast(1.06) brightness(1.05); }
.walk-map-glow { position: absolute; inset: 0; z-index: 4; display: block; background: radial-gradient(circle at 50% 58%, rgba(246,200,95,.34), transparent 18%), radial-gradient(circle at 18% 78%, rgba(96,165,250,.18), transparent 24%), linear-gradient(180deg, rgba(11,40,76,.2), transparent 48%); pointer-events: none; }
.walk-map-content { position: relative; z-index: 5; display: grid; grid-template-rows: auto auto 1fr; min-width: 0; min-height: 374px; padding: 16px 18px 14px; color: #fff8ea; }
.walk-map-header { z-index: 6; display: flex !important; justify-content: space-between; align-items: flex-start; gap: 14px; max-width: none; padding: 11px 13px; border: 1px solid rgba(255,248,234,.09); border-radius: 17px; background: linear-gradient(90deg, rgba(2,10,28,.68), rgba(7,27,51,.34), rgba(2,10,28,.14)); box-shadow: 0 12px 24px rgba(0,0,0,.14), inset 0 1px 0 rgba(255,248,234,.08); backdrop-filter: blur(7px); }
.walk-map-header::before { content: none; }
.walk-map-header > div:first-child { min-width: 0; }
.walk-map-header > div > span { display: inline-flex; margin-bottom: 5px; color: #f6c85f; font-size: .68rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; text-shadow: 0 2px 12px rgba(0,0,0,.45); }
.walk-map-header h2 { font-size: clamp(1.08rem, 2vw, 1.5rem); letter-spacing: -.035em; }
.walk-map-header p { max-width: 660px; color: rgba(255,248,234,.9); font-size: .72rem; line-height: 1.42; }
.walk-map-tabs { position: relative; z-index: 8; display: inline-flex; align-items: center; gap: 7px; width: max-content; margin: 8px 0 0 13px; padding: 4px; border: 1px solid rgba(246,200,95,.24); border-radius: 999px; background: rgba(2,10,28,.56); box-shadow: inset 0 1px 0 rgba(255,248,234,.1), 0 9px 18px rgba(0,0,0,.18); backdrop-filter: blur(8px); }
.walk-map-tab { appearance: none; display: inline-flex; align-items: center; justify-content: center; gap: 7px; min-width: 108px; padding: 7px 11px; border: 1px solid transparent; border-radius: 999px; background: transparent; color: rgba(255,248,234,.86); font-size: .64rem; font-weight: 950; cursor: pointer; transition: transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease; }
.walk-map-tab span { color: #f6c85f; font-size: .86rem; }
.walk-map-tab:hover { border-color: rgba(246,200,95,.44); background: rgba(246,200,95,.1); color: #fff8ea; transform: translateY(-1px); }
.walk-map-tab.active { border-color: rgba(246,200,95,.8); background: linear-gradient(135deg, rgba(246,200,95,.28), rgba(2,10,28,.54)); color: #fff8ea; box-shadow: 0 0 0 3px rgba(246,200,95,.09); }
.walk-map-tab.youth span { color: #f59e0b; }
.walk-map-stage { position: relative; align-self: end; min-width: 0; max-width: 100%; overflow: hidden; padding: 10px 16px 0; }
.walk-map-track-shell { position: relative; display: block; overflow-x: auto; overflow-y: hidden; width: 100%; max-width: 100%; min-width: 0; min-height: 224px; border-radius: 19px; scroll-behavior: smooth; scrollbar-width: auto; scrollbar-color: rgba(246,200,95,.76) rgba(2,10,28,.42); overscroll-behavior-x: contain; touch-action: pan-x; -webkit-overflow-scrolling: touch; }
.walk-map-track-shell::-webkit-scrollbar { height: 12px; }
.walk-map-track-shell::-webkit-scrollbar-track { border-radius: 999px; background: rgba(2,10,28,.42); box-shadow: inset 0 1px 0 rgba(255,248,234,.08); }
.walk-map-track-shell::-webkit-scrollbar-thumb { border: 2px solid rgba(2,10,28,.42); border-radius: 999px; background: linear-gradient(90deg, rgba(246,200,95,.78), rgba(255,248,234,.92), rgba(246,200,95,.78)); }
.walk-map-track { position: relative; z-index: 2; display: grid; grid-template-columns: repeat(20, 104px); align-items: end; gap: 12px; width: max-content; min-width: max-content; padding: 20px 34px 26px; }
.walk-map-path { position: absolute; left: 58px; right: 58px; bottom: 102px; height: 4px; border-radius: 999px; background: linear-gradient(90deg, rgba(246,200,95,.22), rgba(246,200,95,.78) 20%, rgba(255,248,234,.72) 48%, rgba(246,200,95,.5) 74%, rgba(96,165,250,.18)); box-shadow: 0 0 14px rgba(246,200,95,.42), 0 0 28px rgba(255,248,234,.12); }
.walk-map-level { position: relative; z-index: 3; display: grid; flex: 0 0 98px; justify-items: center; gap: 5px; min-height: 166px; padding: 5px 5px 8px; border: 1px solid rgba(255,248,234,.08); border-radius: 15px; background: linear-gradient(180deg, rgba(2,10,28,.1), rgba(2,10,28,.3)); color: #fff8ea; text-align: center; text-decoration: none; scroll-snap-align: center; transition: transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease, opacity .2s ease; }
.walk-map-level:hover { border-color: rgba(246,200,95,.5); background: linear-gradient(180deg, rgba(246,200,95,.1), rgba(2,10,28,.52)); box-shadow: 0 0 0 3px rgba(246,200,95,.08), 0 20px 34px rgba(0,0,0,.24); transform: translateY(-4px); }
.walk-map-state-mark { position: absolute; top: 9px; right: 9px; z-index: 4; display: grid; place-items: center; min-width: 20px; height: 20px; padding: 0 4px; border: 1px solid rgba(255,248,234,.42); border-radius: 999px; background: rgba(2,10,28,.58); color: #f6c85f; font-size: .62rem; font-weight: 950; box-shadow: 0 7px 14px rgba(0,0,0,.18); }
.walk-map-level-number { color: rgba(255,248,234,.84); font-size: .62rem; font-weight: 950; letter-spacing: .06em; text-transform: uppercase; text-shadow: 0 2px 9px rgba(0,0,0,.7); }
.walk-map-church { position: relative; display: grid; justify-items: center; align-items: end; width: 58px; height: 66px; margin-top: 3px; filter: drop-shadow(0 10px 12px rgba(0,0,0,.3)); }
.walk-map-church-glow { position: absolute; inset: 16px 4px 2px; border-radius: 50%; background: radial-gradient(circle, rgba(246,200,95,.34), transparent 68%); filter: blur(7px); opacity: .64; }
.walk-map-church-tower { position: absolute; top: 2px; display: grid; justify-items: center; width: 18px; height: 38px; border: 1px solid rgba(255,248,234,.38); border-radius: 7px 7px 2px 2px; background: linear-gradient(180deg, #fff8ea, #d8b66a 48%, #8f6930); box-shadow: inset 0 1px 0 rgba(255,255,255,.5); }
.walk-map-church-tower::before { content: ''; position: absolute; top: -9px; width: 17px; height: 14px; clip-path: polygon(50% 0, 100% 100%, 0 100%); background: linear-gradient(180deg, #f6c85f, #8a5b13); }
.walk-map-church-tower::after { content: ''; position: absolute; top: -15px; width: 2px; height: 8px; border-radius: 999px; background: rgba(255,248,234,.86); box-shadow: 0 0 8px rgba(246,200,95,.58); }
.walk-map-church-tower span { position: absolute; top: 11px; width: 8px; height: 12px; border-radius: 999px 999px 3px 3px; background: rgba(7,27,51,.68); box-shadow: inset 0 0 7px rgba(246,200,95,.28); }
.walk-map-church-roof { position: absolute; top: 32px; width: 55px; height: 21px; clip-path: polygon(50% 0, 100% 100%, 0 100%); background: linear-gradient(180deg, #f6c85f, #a96f1a 72%, #57340f); }
.walk-map-church-body { position: absolute; bottom: 0; display: grid; place-items: end center; width: 48px; height: 33px; border: 1px solid rgba(255,248,234,.4); border-radius: 7px 7px 10px 10px; background: linear-gradient(180deg, #fff8ea, #d6b677 52%, #806034); box-shadow: inset 0 1px 0 rgba(255,255,255,.56); }
.walk-map-church-window { position: absolute; top: 8px; width: 9px; height: 11px; border-radius: 999px 999px 3px 3px; background: rgba(7,27,51,.68); box-shadow: 0 0 10px rgba(246,200,95,.28), inset 0 0 7px rgba(246,200,95,.18); }
.walk-map-church-door { width: 14px; height: 19px; border-radius: 999px 999px 3px 3px; background: linear-gradient(180deg, #6b4016, #2d1c10); box-shadow: inset 0 0 7px rgba(0,0,0,.26); }
.walk-map-level-label { display: grid; justify-items: center; gap: 3px; min-height: 60px; }
.walk-map-level-label strong { max-width: 92px; color: #fff8ea; font-size: .61rem; font-weight: 950; line-height: 1.08; text-shadow: 0 2px 9px rgba(0,0,0,.8); }
.walk-map-level-label small { color: rgba(255,248,234,.74); font-size: .5rem; font-weight: 850; line-height: 1.12; }
.walk-map-level-label em { padding: 2px 7px; border: 1px solid rgba(255,248,234,.18); border-radius: 999px; background: rgba(2,10,28,.38); color: #f6c85f; font-size: .48rem; font-style: normal; font-weight: 950; }
.walk-map-current-badge { display: grid; gap: 1px; margin: -2px 0 0; padding: 5px 9px; border-radius: 999px; background: linear-gradient(135deg, #f6c85f, #fff1b5); color: #071b33; box-shadow: 0 10px 20px rgba(0,0,0,.22), 0 0 18px rgba(246,200,95,.34); }
.walk-map-current-badge b, .walk-map-current-badge em { color: #071b33; font-size: .5rem; font-style: normal; font-weight: 950; line-height: 1.05; }
.walk-map-level.is-completed .walk-map-church-glow { background: radial-gradient(circle, rgba(34,197,94,.42), transparent 68%); opacity: .86; }
.walk-map-level.is-completed .walk-map-church-body, .walk-map-level.is-completed .walk-map-church-tower { border-color: rgba(177,241,201,.78); box-shadow: 0 0 18px rgba(34,197,94,.32), inset 0 1px 0 rgba(255,255,255,.62); }
.walk-map-level.is-current { z-index: 5; transform: translateY(-4px); border-color: rgba(246,200,95,.62); box-shadow: 0 0 0 2px rgba(246,200,95,.1), 0 17px 28px rgba(0,0,0,.24); }
.walk-map-level.is-current .walk-map-church { transform: scale(1.04); }
.walk-map-level.is-current .walk-map-church-glow { inset: -8px -12px -4px; background: radial-gradient(circle, rgba(246,200,95,.76), transparent 66%); opacity: 1; }
.walk-map-level.is-future .walk-map-church { filter: drop-shadow(0 12px 16px rgba(0,0,0,.28)) saturate(.9); }
.walk-map-level.is-locked { opacity: .72; }
.walk-map-level.is-locked .walk-map-church { filter: drop-shadow(0 10px 14px rgba(0,0,0,.24)) grayscale(.24) saturate(.72); }
.walk-map-level.is-final .walk-map-church-glow { background: radial-gradient(circle, rgba(246,200,95,.62), transparent 68%); opacity: 1; }
.level-celebration-overlay { position: fixed; inset: 0; z-index: 1000; display: grid; place-items: center; overflow: hidden; padding: 22px; background: radial-gradient(circle at 50% 38%, color-mix(in srgb, var(--celebration-primary) 28%, transparent), transparent 28%), linear-gradient(135deg, rgba(2,10,28,.88), rgba(7,27,51,.76)); backdrop-filter: blur(13px) saturate(1.15); color: #fff8ea; isolation: isolate; }
.celebration-sky { position: absolute; inset: -12%; z-index: -3; background: radial-gradient(circle at 50% 44%, color-mix(in srgb, var(--celebration-secondary) 28%, transparent), transparent 18%), radial-gradient(circle at 18% 20%, color-mix(in srgb, var(--celebration-primary) 18%, transparent), transparent 20%), radial-gradient(circle at 82% 18%, rgba(255,248,234,.12), transparent 18%), linear-gradient(180deg, #020a1c, #071b33 54%, #020a1c); animation: celebrationSky 15s ease-in-out infinite; }
.celebration-rays { position: absolute; inset: -28%; z-index: -2; background: conic-gradient(from 0deg, transparent, color-mix(in srgb, var(--celebration-primary) 18%, transparent), transparent 18%, color-mix(in srgb, var(--celebration-secondary) 14%, transparent), transparent 34%); filter: blur(18px); opacity: .78; animation: celebrationRays 15s linear infinite; }
.celebration-card { position: relative; display: grid; justify-items: center; width: min(620px, 94vw); padding: 34px 32px 28px; border: 1px solid color-mix(in srgb, var(--celebration-secondary) 58%, transparent); border-radius: 30px; background: radial-gradient(circle at 50% 0%, color-mix(in srgb, var(--celebration-primary) 20%, transparent), transparent 38%), linear-gradient(135deg, rgba(255,248,234,.14), rgba(255,248,234,.055)), rgba(2,10,28,.66); text-align: center; box-shadow: 0 0 0 1px rgba(255,248,234,.08), 0 32px 90px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,248,234,.18); backdrop-filter: blur(16px); animation: celebrationCard 15s ease both; }
.celebration-card::before { content: ''; position: absolute; inset: 14px; z-index: -1; border: 1px solid color-mix(in srgb, var(--celebration-secondary) 30%, transparent); border-radius: 24px; pointer-events: none; }
.celebration-card p { margin: 0 0 8px; color: color-mix(in srgb, var(--celebration-secondary) 78%, #fff8ea); font-size: .74rem; font-weight: 950; letter-spacing: .08em; text-transform: uppercase; text-shadow: 0 3px 16px rgba(0,0,0,.42); }
.celebration-card > strong { color: #fff8ea; font: 950 1.1rem/1 Georgia, serif; text-shadow: 0 0 22px color-mix(in srgb, var(--celebration-primary) 56%, transparent); }
.celebration-card h2 { margin: 8px 0 16px; color: #fff8ea; font: 950 clamp(2rem, 5vw, 3.5rem)/.92 Georgia, serif; letter-spacing: -.055em; text-shadow: 0 4px 0 rgba(0,0,0,.12), 0 0 34px color-mix(in srgb, var(--celebration-primary) 44%, transparent); }
.celebration-level { display: grid; grid-template-columns: 78px 1fr; gap: 14px; align-items: center; width: min(430px, 100%); margin: 0 0 18px; padding: 12px 16px; border: 1px solid color-mix(in srgb, var(--celebration-secondary) 45%, transparent); border-radius: 20px; background: rgba(2,10,28,.48); box-shadow: inset 0 1px 0 rgba(255,248,234,.12), 0 18px 34px rgba(0,0,0,.22); text-align: left; }
.celebration-level i { display: grid; place-items: center; width: 70px; height: 70px; border: 2px solid color-mix(in srgb, var(--celebration-secondary) 78%, transparent); border-radius: 50%; background: radial-gradient(circle at 32% 22%, #fff8ea, color-mix(in srgb, var(--celebration-primary) 72%, #f6c85f) 36%, rgba(2,10,28,.94)); font-style: normal; font-size: 2rem; box-shadow: 0 0 0 10px color-mix(in srgb, var(--celebration-primary) 14%, transparent), 0 0 44px color-mix(in srgb, var(--celebration-primary) 68%, transparent); animation: celebrationOrb 2.6s ease-in-out infinite; }
.celebration-level span { display: block; color: color-mix(in srgb, var(--celebration-secondary) 84%, #fff8ea); font-size: .74rem; font-weight: 950; text-transform: uppercase; letter-spacing: .06em; }
.celebration-level b { display: block; margin-top: 3px; color: #fff8ea; font: 900 1.08rem/1.08 Georgia, serif; }
.celebration-card h3 { margin: 0; color: color-mix(in srgb, var(--celebration-secondary) 88%, #fff8ea); font: 900 1.25rem/1.1 Georgia, serif; }
.celebration-card small { max-width: 500px; margin-top: 9px; color: rgba(255,248,234,.94); font-size: .9rem; font-weight: 780; line-height: 1.5; text-shadow: 0 2px 12px rgba(0,0,0,.42); }
.celebration-card em { max-width: 470px; margin-top: 10px; color: rgba(255,248,234,.82); font-size: .78rem; font-style: normal; font-weight: 820; line-height: 1.45; }
.celebration-card button { margin-top: 20px; padding: 11px 19px; border: 1px solid color-mix(in srgb, var(--celebration-secondary) 70%, transparent); border-radius: 999px; background: linear-gradient(135deg, color-mix(in srgb, var(--celebration-primary) 72%, #f6c85f), var(--celebration-secondary)); color: #071b33; font-size: .74rem; font-weight: 950; cursor: pointer; box-shadow: 0 14px 30px rgba(0,0,0,.25), 0 0 22px color-mix(in srgb, var(--celebration-primary) 34%, transparent); }
.celebration-orbit { position: absolute; top: -58px; display: grid; place-items: center; width: 118px; height: 118px; border: 1px solid color-mix(in srgb, var(--celebration-secondary) 56%, transparent); border-radius: 50%; background: radial-gradient(circle, rgba(255,248,234,.18), rgba(2,10,28,.7)); box-shadow: 0 0 55px color-mix(in srgb, var(--celebration-primary) 54%, transparent); animation: celebrationFloat 4.6s ease-in-out infinite; }
.celebration-orbit span { display: grid; place-items: center; width: 76px; height: 76px; border-radius: 50%; background: radial-gradient(circle at 34% 22%, #fff8ea, color-mix(in srgb, var(--celebration-primary) 72%, #f6c85f)); font-size: 2.1rem; box-shadow: inset 0 0 20px rgba(255,248,234,.18), 0 0 32px color-mix(in srgb, var(--celebration-primary) 58%, transparent); }
.celebration-particle { position: absolute; left: calc(10% + (var(--particle-index) * 10%)); bottom: -42px; z-index: -1; font-size: clamp(1.2rem, 2.7vw, 2rem); filter: drop-shadow(0 0 16px color-mix(in srgb, var(--celebration-primary) 70%, transparent)); opacity: .88; animation: celebrationParticle 15s ease-in-out infinite; animation-delay: calc(var(--particle-index) * -1.35s); }
.theme-nature .celebration-card { background: radial-gradient(circle at 50% 0%, rgba(34,197,94,.2), transparent 38%), linear-gradient(135deg, rgba(255,248,234,.14), rgba(255,248,234,.055)), rgba(2,28,18,.7); }
.theme-warrior .celebration-card { background: radial-gradient(circle at 50% 0%, rgba(148,163,184,.2), transparent 38%), linear-gradient(135deg, rgba(255,248,234,.14), rgba(255,248,234,.055)), rgba(15,23,42,.7); }
.theme-crown .celebration-card { box-shadow: 0 0 0 1px rgba(255,248,234,.1), 0 36px 100px rgba(0,0,0,.48), 0 0 70px rgba(246,200,95,.22), inset 0 1px 0 rgba(255,248,234,.2); }

.level-celebration-enter-active, .level-celebration-leave-active { transition: opacity .48s ease, transform .48s ease; }
.level-celebration-enter-from, .level-celebration-leave-to { opacity: 0; transform: scale(1.015); }

@keyframes celebrationSky {
  0%, 100% { transform: scale(1) translateY(0); filter: saturate(1); }
  50% { transform: scale(1.04) translateY(-1.4%); filter: saturate(1.22); }
}

@keyframes celebrationRays {
  to { transform: rotate(360deg); }
}

@keyframes celebrationCard {
  0% { opacity: 0; transform: translateY(22px) scale(.94); }
  10%, 88% { opacity: 1; transform: translateY(0) scale(1); }
  100% { opacity: .96; transform: translateY(-3px) scale(.992); }
}

@keyframes celebrationOrb {
  0%, 100% { transform: scale(.98); }
  50% { transform: scale(1.08); }
}

@keyframes celebrationFloat {
  0%, 100% { transform: translateY(0) rotate(-2deg); }
  50% { transform: translateY(-8px) rotate(2deg); }
}

@keyframes celebrationParticle {
  0% { transform: translate3d(-10px, 0, 0) scale(.7) rotate(-8deg); opacity: 0; }
  14% { opacity: .86; }
  82% { opacity: .76; }
  100% { transform: translate3d(18px, -112vh, 0) scale(1.24) rotate(18deg); opacity: 0; }
}

@media (prefers-reduced-motion: reduce) {
  .celebration-sky,
  .celebration-rays,
  .celebration-card,
  .celebration-level i,
  .celebration-orbit,
  .celebration-particle {
    animation: none;
  }
}

@media (max-width: 1180px) {
  .walk-grid, .journey-list, .mid-grid { grid-template-columns: 1fr; }
  .walk-side { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .areas-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

@media (max-width: 760px) {
  .walk-main { margin-left: 0; padding: 16px; }
  .walk-header, .journey-title, .journey-card footer, .walk-map-header { flex-direction: column; align-items: flex-start; }
  .walk-side, .areas-grid, .badges-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .walk-map-card, .walk-map-content { min-height: 300px; }
  .journey-card, .activity-item { grid-template-columns: 1fr; }
}
</style>
