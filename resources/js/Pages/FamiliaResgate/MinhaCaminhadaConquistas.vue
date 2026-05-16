<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const baseRoute = '/familia-resgate/minha-caminhada'

const props = defineProps({
  walkingAchievements: {
    type: Object,
    default: () => ({}),
  },
})

const statusLabels = {
  received: 'Recebida',
  in_progress: 'Em progresso',
  locked: 'Próxima',
  pending_validation: 'Em validação',
}

const categoryLabels = {
  bible: 'Bíblia',
  bible_challenge: 'Desafio bíblico',
  communion: 'Comunhão',
  devotional: 'Devocional',
  evangelism: 'Evangelismo',
  general: 'Geral',
  presence: 'Presença',
  service: 'Serviço',
}

const payload = computed(() => props.walkingAchievements || {})
const canSeeYouthJourney = computed(() => Boolean(payload.value.canSeeYouthJourney))
const currentJourneyType = ref('general')
const activeFilter = ref('all')
const emptyStates = computed(() => payload.value.emptyStates || {})

watch(canSeeYouthJourney, (canSeeYouth) => {
  if (!canSeeYouth && currentJourneyType.value === 'youth') {
    currentJourneyType.value = 'general'
  }
})

const currentJourneyData = computed(() => {
  if (currentJourneyType.value === 'youth' && canSeeYouthJourney.value) {
    return payload.value.youth || {}
  }

  return payload.value.general || {}
})

const journeyLabel = computed(() => currentJourneyType.value === 'youth' ? 'Caminhada Jovem' : 'Caminhada Geral')
const isAuthorized = computed(() => payload.value.authorized !== false)
const visibleCatalog = computed(() => currentJourneyData.value.catalog || [])
const receivedItems = computed(() => currentJourneyData.value.received || [])
const progressItems = computed(() => currentJourneyData.value.inProgress || [])
const lockedItems = computed(() => currentJourneyData.value.locked || [])
const categoryItems = computed(() => currentJourneyData.value.categories || [])
const realSummary = computed(() => currentJourneyData.value.summary || {})

const filters = computed(() => [
  { key: 'all', label: 'Todas' },
  { key: 'received', label: 'Recebidas' },
  { key: 'in_progress', label: 'Em progresso' },
  { key: 'locked', label: 'Próximas' },
  { key: 'categories', label: 'Categorias' },
])

const showAchievementLists = computed(() => activeFilter.value !== 'categories')
const currentCatalogList = computed(() => {
  if (activeFilter.value === 'received') return receivedItems.value
  if (activeFilter.value === 'in_progress') return progressItems.value
  if (activeFilter.value === 'locked') return lockedItems.value

  return visibleCatalog.value
})
const dashboardCards = computed(() => [
  { label: 'Catálogo visível', value: realSummary.value.total_catalog || 0, note: 'Conquistas disponíveis nesta jornada' },
  { label: 'Conquistas recebidas', value: realSummary.value.received_count || 0, note: 'Marcos reais concedidos' },
  { label: 'Em progresso', value: realSummary.value.in_progress_count || 0, note: 'Registros reais em andamento' },
  { label: 'Próximas', value: realSummary.value.locked_count || 0, note: 'Ainda não concedidas ou iniciadas' },
])

const categoryTitle = (category) => categoryLabels[category] || String(category || 'Geral')
const progressPercent = (item) => Math.max(0, Math.min(100, Number(item.progress_percent || 0)))
</script>

<template>
  <Head title="Conquistas da Caminhada" />

  <div class="achievements-page">
    <FamilySidebar active-href="/familia-resgate/minha-caminhada" />

    <main class="achievements-main">
      <header class="achievements-hero">
        <div>
          <p>Minha Caminhada</p>
          <h1>Conquistas da Caminhada</h1>
          <span>Acompanhe marcos reais da sua caminhada, celebrando constância, serviço e crescimento sem comparação espiritual.</span>
        </div>
        <strong>{{ journeyLabel }}</strong>
      </header>

      <section v-if="!isAuthorized" class="empty-state" aria-label="Conquistas indisponíveis">
        <strong>{{ emptyStates.withoutPersonTitle }}</strong>
        <p>{{ emptyStates.withoutPersonText }}</p>
      </section>

      <template v-else>
        <nav v-if="canSeeYouthJourney" class="journey-tabs" aria-label="Jornadas de conquistas">
          <button type="button" :class="{ active: currentJourneyType === 'general' }" @click="currentJourneyType = 'general'">Geral</button>
          <button type="button" :class="{ active: currentJourneyType === 'youth' }" @click="currentJourneyType = 'youth'">Jovem</button>
        </nav>

        <section class="summary-strip" aria-label="Resumo das conquistas">
          <article v-for="card in dashboardCards" :key="card.label">
            <span>{{ card.label }}</span>
            <strong>{{ card.value }}</strong>
            <small>{{ card.note }}</small>
          </article>
        </section>

        <nav class="filters" aria-label="Filtros de conquistas">
          <button v-for="filter in filters" :key="filter.key" type="button" :class="{ active: activeFilter === filter.key }" @click="activeFilter = filter.key">
            {{ filter.label }}
          </button>
        </nav>

        <section v-if="showAchievementLists" class="achievement-groups">
          <article class="achievement-section">
            <header><span>Minhas conquistas</span><h2>Minhas conquistas</h2><p>Marcos já recebidos na sua caminhada.</p></header>
            <div v-if="receivedItems.length" class="achievement-grid">
              <article v-for="item in receivedItems" :key="item.person_achievement_id || item.id" class="achievement-card status-received">
                <i>{{ item.icon }}</i>
                <div><small>{{ categoryTitle(item.category) }}</small><strong>{{ item.name }}</strong><p>{{ item.description }}</p><em>{{ statusLabels[item.status] || 'Recebida' }}</em></div>
              </article>
            </div>
            <div v-else class="empty-inline"><strong>{{ emptyStates.withoutReceivedTitle }}</strong><p>{{ emptyStates.withoutReceivedText }}</p></div>
          </article>

          <article class="achievement-section">
            <header><span>Em progresso</span><h2>Em progresso</h2><p>Conquistas em andamento ou aguardando validação real.</p></header>
            <div v-if="progressItems.length" class="progress-grid">
              <article v-for="item in progressItems" :key="item.person_achievement_id || item.id" class="progress-card" :class="`status-${item.status}`">
                <header><i>{{ item.icon }}</i><div><small>{{ categoryTitle(item.category) }}</small><strong>{{ item.name }}</strong></div></header>
                <p>{{ item.description }}</p>
                <div class="progress-bar"><span :style="{ width: `${progressPercent(item)}%` }"></span></div>
                <footer><em>{{ statusLabels[item.status] || 'Em progresso' }}</em><small>{{ item.progress_current }} de {{ item.progress_target || 0 }}</small></footer>
              </article>
            </div>
            <div v-else class="empty-inline"><strong>{{ emptyStates.withoutProgressTitle }}</strong><p>{{ emptyStates.withoutProgressText }}</p></div>
          </article>
        </section>

        <section v-if="activeFilter === 'categories'" class="categories-section" aria-label="Categorias reais">
          <header><span>Categorias</span><h2>Categorias de {{ journeyLabel }}</h2><p>Áreas organizadas a partir do catálogo visível retornado pelo backend.</p></header>
          <div v-if="categoryItems.length" class="categories-grid">
            <article v-for="category in categoryItems" :key="category.key"><i>✦</i><strong>{{ category.title }}</strong><p>{{ category.count }} conquista(s) disponível(is).</p></article>
          </div>
          <div v-else class="empty-inline"><strong>{{ emptyStates.withoutCatalogTitle }}</strong></div>
        </section>

        <section v-else class="categories-section" aria-label="Catálogo visível">
          <header><span>Catálogo</span><h2>Catálogo visível de {{ journeyLabel }}</h2><p>Conquistas disponíveis para esta jornada conforme permissões reais.</p></header>
          <div v-if="currentCatalogList.length" class="categories-grid">
            <article v-for="item in currentCatalogList" :key="`${item.id}-${item.status || 'catalog'}`"><i>{{ item.icon }}</i><strong>{{ item.name }}</strong><p>{{ item.description }}</p></article>
          </div>
          <div v-else class="empty-inline"><strong>{{ emptyStates.withoutCatalogTitle }}</strong></div>
        </section>
      </template>

      <section class="explanation-grid">
        <article class="how-card">
          <span>Como funciona</span><h2>Como as conquistas funcionam</h2>
          <ul><li>Algumas conquistas podem ser automáticas.</li><li>Algumas precisam de validação da liderança ou secretaria.</li><li>Algumas podem ser concedidas internamente.</li><li>Algumas são privadas ou podem ficar ocultas.</li><li>Nenhuma conquista mede espiritualidade.</li></ul>
        </article>
        <article class="care-card">
          <span>Cuidado pastoral</span><h2>O que conquistas não são</h2>
          <div><section><strong>Não são</strong><ul><li>Troféus de espiritualidade.</li><li>Comparação com outras pessoas.</li><li>Disputa de serviço.</li><li>Prova de valor diante de Deus.</li></ul></section><section><strong>São para</strong><ul><li>Lembrar constância.</li><li>Celebrar marcos de crescimento.</li><li>Incentivar próximos passos.</li><li>Cuidar melhor da caminhada.</li></ul></section></div>
        </article>
      </section>

      <nav class="achievement-actions" aria-label="Atalhos das conquistas">
        <Link :href="baseRoute">Minha Caminhada</Link>
        <Link :href="`${baseRoute}/historico`">Histórico</Link>
        <Link :href="`${baseRoute}/regras`">Regras</Link>
        <Link :href="`${baseRoute}/mentor`">Mentor</Link>
        <Link :href="`${baseRoute}/ranking`">Destaques</Link>
      </nav>
    </main>
  </div>
</template>

<style scoped>
.achievements-page { min-height: 100vh; background: radial-gradient(circle at 10% 0%, rgba(217,164,65,.18), transparent 30%), linear-gradient(135deg, #fff8ea, #f3e7d2); }
.achievements-main { margin-left: 80px; padding: 18px 22px 30px; }
a { text-decoration: none; }
.achievements-hero, .summary-strip, .journey-tabs, .filters, .achievement-groups, .categories-section, .explanation-grid, .achievement-actions, .empty-state { max-width: 1280px; margin-left: auto; margin-right: auto; }
.achievements-hero { display: flex; justify-content: space-between; gap: 18px; align-items: end; margin-bottom: 14px; padding: 22px; border: 1px solid rgba(246,200,95,.34); border-radius: 26px; background: radial-gradient(circle at 82% 18%, rgba(246,200,95,.2), transparent 34%), linear-gradient(135deg,#071b33,#0b2748); box-shadow: 0 22px 52px rgba(7,27,51,.2); }
.achievements-hero p, .categories-section span, .achievement-section span, .how-card > span, .care-card > span { margin: 0; color: #f6c85f; font-size: .72rem; font-weight: 950; letter-spacing: .12em; text-transform: uppercase; }
.achievements-hero h1 { margin: 7px 0; color: #fff8ea; font: 950 clamp(2rem, 4vw, 2.8rem)/.95 Georgia, serif; letter-spacing: -.05em; }
.achievements-hero span { display: block; max-width: 760px; color: rgba(255,248,234,.86); font-size: .86rem; font-weight: 760; line-height: 1.42; }
.achievements-hero strong { padding: 9px 14px; border: 1px solid rgba(246,200,95,.52); border-radius: 999px; color: #f6c85f; font-size: .74rem; font-weight: 950; white-space: nowrap; }
.summary-strip { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 12px; }
.summary-strip article, .achievement-section, .categories-section, .how-card { border: 1px solid rgba(217,164,65,.18); background: rgba(255,248,234,.86); box-shadow: 0 14px 30px rgba(7,27,51,.075), inset 0 1px 0 rgba(255,255,255,.64); }
.summary-strip article { padding: 13px; border-radius: 18px; }
.summary-strip span, .summary-strip small { color: #516070; font-size: .7rem; font-weight: 850; }
.summary-strip strong { display: block; margin: 5px 0 3px; color: #071b33; font-size: 1.15rem; font-weight: 950; line-height: 1.1; }
.journey-tabs, .filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
.journey-tabs button, .filters button { min-height: 34px; padding: 8px 12px; border: 1px solid rgba(217,164,65,.22); border-radius: 999px; background: rgba(255,248,234,.76); color: #071b33; font-size: .72rem; font-weight: 950; cursor: pointer; transition: transform .2s ease, background .2s ease, border-color .2s ease; }
.journey-tabs button.active, .journey-tabs button:hover, .filters button.active, .filters button:hover { border-color: rgba(217,164,65,.42); background: linear-gradient(135deg, #d9a441, #f6c85f); transform: translateY(-1px); }
.achievement-groups { display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); gap: 12px; margin-bottom: 12px; }
.achievement-section, .categories-section, .how-card, .care-card { padding: 16px; border-radius: 24px; }
.achievement-section header, .categories-section header { margin-bottom: 12px; }
.achievement-section h2, .categories-section h2, .how-card h2, .care-card h2 { margin: 4px 0 6px; color: #071b33; font: 950 clamp(1.25rem, 2.2vw, 1.8rem)/1 Georgia, serif; letter-spacing: -.045em; }
.achievement-section p, .categories-section header p, .how-card li, .care-card li, .care-card p { margin: 0; color: #445164; font-size: .78rem; font-weight: 780; line-height: 1.34; }
.achievement-grid, .progress-grid { display: grid; gap: 9px; }
.achievement-card, .progress-card, .categories-grid article { border: 1px solid rgba(217,164,65,.16); border-radius: 18px; background: rgba(255,255,255,.54); box-shadow: inset 0 1px 0 rgba(255,255,255,.64); }
.achievement-card { display: grid; grid-template-columns: 46px 1fr; gap: 10px; align-items: center; padding: 10px; }
.achievement-card i, .progress-card i, .categories-grid i { display: grid; place-items: center; width: 42px; height: 48px; border: 1px solid rgba(217,164,65,.26); border-radius: 15px 15px 21px 21px; background: linear-gradient(145deg, #071b33, #0b2748); color: #f6c85f; font-style: normal; font-size: 1.08rem; }
.achievement-card small, .progress-card small { color: #8a5b13; font-size: .62rem; font-weight: 950; text-transform: uppercase; letter-spacing: .06em; }
.achievement-card strong, .progress-card strong, .categories-grid strong { display: block; color: #071b33; font-size: .88rem; font-weight: 950; line-height: 1.1; }
.achievement-card em, .progress-card em { display: inline-flex; width: max-content; margin-top: 6px; padding: 4px 8px; border-radius: 999px; background: rgba(22,163,74,.12); color: #15803d; font-size: .62rem; font-style: normal; font-weight: 950; }
.progress-card { display: grid; gap: 9px; padding: 12px; }
.progress-card header { display: grid; grid-template-columns: 46px 1fr; gap: 10px; align-items: center; margin: 0; }
.progress-bar { height: 8px; overflow: hidden; border-radius: 999px; background: rgba(217,164,65,.16); }
.progress-bar span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #d9a441, #f6c85f); }
.progress-card footer { display: grid; gap: 5px; }
.progress-card.status-pending_validation em { background: rgba(96,165,250,.14); color: #1d4ed8; }
.progress-card.status-locked em { background: rgba(100,116,139,.12); color: #475569; }
.empty-state, .empty-inline { border: 1px dashed rgba(217,164,65,.38); border-radius: 20px; background: rgba(255,248,234,.72); color: #445164; }
.empty-state { padding: 22px; margin-bottom: 14px; }
.empty-inline { display: grid; gap: 5px; padding: 13px; }
.empty-state strong, .empty-inline strong { display: block; color: #071b33; font-weight: 950; }
.empty-state p, .empty-inline p { margin: 6px 0 0; color: #516070; font-size: .82rem; font-weight: 780; line-height: 1.35; }
.categories-section { margin-bottom: 12px; }
.categories-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }
.categories-grid article { display: grid; align-content: start; gap: 7px; min-height: 142px; padding: 12px; }
.categories-grid i { width: 38px; height: 38px; border-radius: 14px; background: rgba(217,164,65,.14); color: #8a5b13; }
.explanation-grid { display: grid; grid-template-columns: minmax(0, .85fr) minmax(0, 1.15fr); gap: 12px; margin-bottom: 14px; }
.how-card ul, .care-card ul { display: grid; gap: 7px; margin: 8px 0 0; padding-left: 18px; }
.care-card { border: 1px solid rgba(246,200,95,.2); background: radial-gradient(circle at 92% 10%, rgba(246,200,95,.12), transparent 34%), linear-gradient(135deg, #071b33, #0b2748); box-shadow: 0 16px 34px rgba(7,27,51,.13), inset 0 1px 0 rgba(255,248,234,.08); }
.care-card > span, .care-card strong { color: #f6c85f; }
.care-card h2 { color: #fff8ea; }
.care-card > div { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.care-card li { color: rgba(255,248,234,.86); }
.achievement-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; }
.achievement-actions a { display: inline-flex; align-items: center; justify-content: center; min-height: 36px; padding: 9px 13px; border: 1px solid rgba(217,164,65,.32); border-radius: 999px; background: #fff8ea; color: #071b33; font-size: .76rem; font-weight: 950; box-shadow: 0 10px 20px rgba(7,27,51,.1); }
.achievement-actions a:nth-child(2), .achievement-actions a:nth-child(4) { background: rgba(7,27,51,.92); color: #f6c85f; }
.achievement-actions a:nth-child(5) { background: linear-gradient(135deg, #d9a441, #f6c85f); }
@media (max-width: 1100px) { .summary-strip, .achievement-groups, .categories-grid, .explanation-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 760px) { .achievements-main { margin-left: 0; padding: 16px; } .achievements-hero { flex-direction: column; align-items: flex-start; } .summary-strip, .achievement-groups, .categories-grid, .explanation-grid, .care-card > div { grid-template-columns: 1fr; } }
</style>
