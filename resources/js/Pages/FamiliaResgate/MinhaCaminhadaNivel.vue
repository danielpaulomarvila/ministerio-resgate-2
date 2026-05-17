<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const props = defineProps({
  journey: { type: String, default: 'geral' },
  level: { type: [Number, String], default: 1 },
  walkingLevelDetail: { type: Object, default: null },
})

const baseRoute = '/familia-resgate/minha-caminhada'

const detail = computed(() => props.walkingLevelDetail || {})
const requestedJourneyType = computed(() => detail.value.requestedJourneyType === 'youth' || props.journey === 'jovem' ? 'youth' : 'general')
const journeySlug = computed(() => requestedJourneyType.value === 'youth' ? 'jovem' : 'geral')
const journeyTitle = computed(() => detail.value.journey?.name || (journeySlug.value === 'jovem' ? 'Caminhada Jovem Resgatado' : 'Caminhada Geral da Igreja'))
const journeyBreadcrumb = computed(() => journeySlug.value === 'jovem' ? 'Resgatados' : 'Caminhada Geral')
const levelData = computed(() => detail.value.level || null)
const progressData = computed(() => detail.value.progress || {})
const neighborLevels = computed(() => detail.value.neighborLevels || { previous: null, next: null })
const recentLogs = computed(() => Array.isArray(progressData.value.recentLogs) ? progressData.value.recentLogs : [])
const levelNumber = computed(() => Number(levelData.value?.number || props.level || 1))
const levelStatus = computed(() => levelData.value?.status || 'locked')
const hasRealAuthorizedLevel = computed(() => Boolean(detail.value.usesRealData && detail.value.authorized && detail.value.levelFound && levelData.value))
const mapRoute = computed(() => `${baseRoute}/${journeySlug.value}/mapa`)
const formatNumber = (value) => Number(value || 0).toLocaleString('pt-BR')

const statusLabels = {
  completed: 'Já ultrapassado',
  current: 'Nível atual',
  next: 'Próximo nível',
  locked: 'Bloqueado',
}

const statusDescriptions = {
  completed: 'Este nível já ficou atrás no percurso calculado pelos pontos aprovados reais.',
  current: 'Este é o nível atual calculado a partir dos pontos aprovados da sua caminhada.',
  next: 'Este é o próximo nível real a alcançar quando houver pontos aprovados suficientes.',
  locked: 'Este nível ainda está mais à frente e permanece protegido até haver progresso aprovado suficiente.',
}

const pageTitle = computed(() => {
  if (hasRealAuthorizedLevel.value) {
    return `Nível ${levelData.value.number} · ${levelData.value.name} · ${journeyTitle.value}`
  }

  return `Nível ${levelNumber.value} · Minha Caminhada`
})

const emptyState = computed(() => {
  if (hasRealAuthorizedLevel.value) {
    return null
  }

  const states = detail.value.emptyStates || {}
  const reason = detail.value.reason

  if (reason === 'without_person') {
    return {
      title: states.withoutPersonTitle || 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
      text: states.withoutPersonText || 'Assim que o cadastro for vinculado, os detalhes reais deste nível aparecerão aqui.',
    }
  }

  if (reason === 'unauthorized_youth') {
    return {
      title: states.unauthorizedYouthTitle || 'Nível jovem indisponível para este perfil.',
      text: states.unauthorizedYouthText || 'A caminhada jovem aparece somente para jovens/resgatados autorizados.',
    }
  }

  if (reason === 'missing_level') {
    return {
      title: states.missingLevelTitle || 'Nível não encontrado.',
      text: states.missingLevelText || 'Verifique o endereço acessado ou volte ao mapa da caminhada.',
    }
  }

  return {
    title: states.withoutJourneyTitle || 'Jornada indisponível no momento.',
    text: detail.value.message || states.withoutJourneyText || 'Assim que a jornada estiver disponível, os detalhes reais deste nível aparecerão aqui.',
  }
})

const pointsToReach = computed(() => Number(levelData.value?.pointsToReach || 0))
const requiredPoints = computed(() => Number(levelData.value?.requiredPoints || 0))
const currentPoints = computed(() => Number(progressData.value.points || 0))
const progressPercent = computed(() => {
  if (!hasRealAuthorizedLevel.value) {
    return 0
  }

  if (levelStatus.value === 'completed') {
    return 100
  }

  if (levelStatus.value === 'current') {
    return Number(progressData.value.progressPercent || 0)
  }

  if (requiredPoints.value <= 0) {
    return 0
  }

  return Math.min(100, Math.round((currentPoints.value / requiredPoints.value) * 100))
})

const statusSummary = computed(() => {
  if (!hasRealAuthorizedLevel.value) {
    return 'Dados reais indisponíveis para este nível.'
  }

  if (levelStatus.value === 'current' && requiredPoints.value === 0) {
    return 'Ponto de partida da caminhada real.'
  }

  if (pointsToReach.value > 0) {
    return `Faltam ${formatNumber(pointsToReach.value)} pontos aprovados para alcançar este nível.`
  }

  return 'Nível calculado a partir dos pontos aprovados disponíveis.'
})
</script>

<template>
  <Head :title="pageTitle" />

  <div class="level-page" :class="`journey-${journeySlug}`">
    <FamilySidebar active-href="/familia-resgate/minha-caminhada" />

    <main class="level-main">
      <header class="level-hero">
        <div>
          <p>Central da Família <span>&gt;</span> Minha Caminhada <span>&gt;</span> {{ journeyBreadcrumb }} <span>&gt;</span> Nível {{ levelNumber }}</p>
          <h1>{{ hasRealAuthorizedLevel ? levelData.name : emptyState.title }}</h1>
          <small>{{ hasRealAuthorizedLevel ? `${journeyTitle} · Dados reais de progresso aprovado.` : emptyState.text }}</small>
        </div>
        <strong :class="`is-${levelStatus}`">{{ hasRealAuthorizedLevel ? statusLabels[levelStatus] : 'Estado seguro' }}</strong>
      </header>

      <section v-if="emptyState" class="level-stage">
        <article class="level-sanctuary is-locked">
          <div class="sanctuary-visual">
            <div class="church-mark" aria-hidden="true">
              <span class="church-light"></span>
              <span class="church-icon">✦</span>
              <span class="church-tower"></span>
              <span class="church-roof"></span>
              <span class="church-body"><i></i></span>
            </div>
            <span>Nível {{ levelNumber }}</span>
          </div>

          <div class="sanctuary-content">
            <div class="level-kicker">
              <span>{{ journeyTitle }}</span>
              <small>Dados reais protegidos</small>
            </div>
            <h2>{{ emptyState.title }}</h2>
            <p>{{ emptyState.text }}</p>
          </div>
        </article>

        <nav class="level-actions" aria-label="Ações do nível">
          <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
          <Link :href="mapRoute">Ir ao mapa</Link>
          <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
        </nav>
      </section>

      <template v-else>
        <section class="level-stage">
          <article class="level-sanctuary" :class="`is-${levelStatus}`">
            <div class="sanctuary-visual">
              <div class="church-mark" aria-hidden="true">
                <span class="church-light"></span>
                <span class="church-icon">{{ levelData.icon }}</span>
                <span class="church-tower"></span>
                <span class="church-roof"></span>
                <span class="church-body"><i></i></span>
              </div>
              <span>Nível {{ levelData.number }}</span>
            </div>

            <div class="sanctuary-content">
              <div class="level-kicker">
                <span>{{ journeyTitle }}</span>
                <small>{{ statusSummary }}</small>
              </div>
              <h2>{{ levelData.name }}</h2>
              <p>{{ levelData.description || statusDescriptions[levelStatus] }}</p>

              <div class="progress-bar" aria-label="Progresso real do nível">
                <i :style="{ width: `${progressPercent}%` }"></i>
              </div>

              <div class="level-summary">
                <article><small>Pontos aprovados</small><strong>{{ formatNumber(currentPoints) }}</strong></article>
                <article><small>Necessário</small><strong>{{ formatNumber(requiredPoints) }}</strong></article>
                <article><small>Progresso</small><strong>{{ progressPercent }}%</strong></article>
              </div>

              <footer class="next-line">
                <span v-if="progressData.nextLevel">Próximo nível real: <b>{{ progressData.nextLevel.name }}</b></span>
                <span v-else>Não há próximo nível ativo cadastrado para esta jornada.</span>
              </footer>
            </div>
          </article>

          <nav class="level-actions" aria-label="Ações do nível">
            <Link :href="baseRoute">Voltar para Minha Caminhada</Link>
            <Link :href="mapRoute">Ir ao mapa</Link>
            <Link :href="`${baseRoute}/conquistas`">Ver conquistas</Link>
            <Link v-if="neighborLevels.next" :href="neighborLevels.next.route">Próximo nível</Link>
            <button v-else type="button" disabled>Sem próximo nível</button>
          </nav>
        </section>

        <section class="content-grid">
          <article class="info-card meaning-card">
            <header><span>✚</span><h2>O que este nível representa</h2></header>
            <p>{{ levelData.description || statusDescriptions[levelStatus] }}</p>
            <small>Este marco organiza dados reais aprovados; ele não mede valor espiritual nem substitui cuidado pastoral.</small>
          </article>

          <article class="info-card verse-card">
            <header><span>📊</span><h2>Resumo real do progresso</h2></header>
            <strong>{{ formatNumber(progressData.approvedLogsCount) }} registros aprovados</strong>
            <p>{{ statusDescriptions[levelStatus] }}</p>
          </article>
        </section>

        <section class="advance-section">
          <header>
            <span>Como avançar</span>
            <h2>Próximo passo baseado em dados aprovados</h2>
          </header>
          <div class="advance-grid">
            <article>
              <i>📌</i>
              <strong>Acompanhe registros aprovados</strong>
              <p>Somente registros validados entram no cálculo deste nível.</p>
            </article>
            <article>
              <i>🧭</i>
              <strong>Veja o próximo marco</strong>
              <p>{{ progressData.nextLevel ? `O próximo nível é ${progressData.nextLevel.name}.` : 'Esta jornada não possui próximo nível ativo.' }}</p>
            </article>
            <article>
              <i>⛪</i>
              <strong>Volte ao mapa</strong>
              <p>O mapa mostra a posição deste nível dentro da jornada real.</p>
            </article>
          </div>
        </section>

        <section class="bottom-grid">
          <article class="info-card evidence-card">
            <header><span>▤</span><h2>Registros recentes aprovados</h2></header>
            <div v-if="recentLogs.length" class="history-list">
              <div v-for="log in recentLogs" :key="log.id">
                <i></i>
                <p>{{ log.category }}<small>{{ log.notes || 'Registro aprovado sem observação pública.' }}</small></p>
                <strong>+{{ formatNumber(log.points) }}</strong>
              </div>
            </div>
            <p v-else>Ainda não há registros aprovados recentes para exibir neste nível.</p>
          </article>

          <article class="info-card evidence-card">
            <header><span>⇄</span><h2>Níveis vizinhos</h2></header>
            <div class="evidence-list">
              <Link v-if="neighborLevels.previous" :href="neighborLevels.previous.route">
                <i>←</i>
                <strong>Nível {{ neighborLevels.previous.number }}</strong>
                <small>{{ neighborLevels.previous.name }}</small>
              </Link>
              <div v-else>
                <i>•</i>
                <strong>Sem nível anterior</strong>
                <small>Este é o marco inicial ativo encontrado.</small>
              </div>
              <Link v-if="neighborLevels.next" :href="neighborLevels.next.route">
                <i>→</i>
                <strong>Nível {{ neighborLevels.next.number }}</strong>
                <small>{{ neighborLevels.next.name }}</small>
              </Link>
              <div v-else>
                <i>•</i>
                <strong>Sem próximo nível</strong>
                <small>Não há próximo marco ativo cadastrado.</small>
              </div>
            </div>
          </article>
        </section>
      </template>
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
.evidence-list { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
.evidence-list div, .evidence-list a { display: grid; justify-items: center; gap: 4px; padding: 10px 6px; border: 1px solid rgba(217,164,65,.16); border-radius: 16px; background: rgba(255,255,255,.56); text-align: center; }
.evidence-list i { display: grid; place-items: center; width: 42px; height: 48px; border: 2px solid #d9a441; border-radius: 15px 15px 22px 22px; background: linear-gradient(135deg, #071b33, #0b2748); color: #f6c85f; font-style: normal; font-size: .9rem; }
.evidence-list strong { color: #071b33; font-size: .78rem; font-weight: 950; line-height: 1.08; }
.evidence-list small { margin: 0; color: #8a5b13; font-size: .75rem; font-weight: 950; }
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
  .level-summary, .advance-grid, .evidence-list { grid-template-columns: 1fr; }
  .church-mark { width: 156px; height: 156px; }
  .level-sanctuary::after { display: none; }
}
</style>
