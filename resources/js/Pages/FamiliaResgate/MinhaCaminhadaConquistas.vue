<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const baseRoute = '/familia-resgate/minha-caminhada'

// Futuro backend:
// Este contexto deve vir das permissões reais.
// Membro comum vê conquistas gerais pessoais.
// Outros perfis autorizados podem receber trilhas complementares.
// Conquistas coletivas específicas aparecem apenas para autorizados.
// Conquistas administrativas, financeiras e sensíveis devem respeitar policies.
// Conquistas não medem espiritualidade nem valor diante de Deus.
const viewerContext = {
  profileType: 'member',
  canSeeGeneralAchievements: true,
  canSeeYouthAchievements: false,
  canSeeYouthTeamAchievements: false,
  canSeeAdministrativeAchievements: false,
  canSeeFinancialAchievements: false,
  canSeeSensitiveAchievements: false,
  youthMember: false,
  youthLeader: false,
  isGuardian: false,
  isAdmin: false,
  isPastoralLeadership: false,
}

// Futuro backend:
// As conquistas devem vir filtradas por policy antes de chegar ao Vue.
// O frontend não deve receber conquistas restritas, financeiras, administrativas
// ou sensíveis quando o perfil não tiver permissão.
// Estados como received, in_progress, pending_validation, locked e hidden
// devem vir do service de conquistas.
// Nenhuma conquista mede espiritualidade ou valor diante de Deus.
const achievements = [
  { id: 'presenca-fiel', type: 'general', status: 'received', title: 'Presença Fiel', category: 'Presença', icon: '👥', text: 'Constância nos cultos e encontros gerais.', detail: 'Recebida por participação constante na vida da igreja.', progress: 100 },
  { id: 'palavra-viva', type: 'general', status: 'received', title: 'Palavra Viva', category: 'Palavra', icon: '📖', text: 'Ritmo saudável de leitura bíblica.', detail: 'Recebida por constância em leitura e reflexão.', progress: 100 },
  { id: 'servo-disponivel', type: 'general', status: 'received', title: 'Servo Disponível', category: 'Serviço', icon: '🤝', text: 'Serviço confirmado em escala ou apoio.', detail: 'Recebida por disponibilidade prática na casa.', progress: 100 },
  { id: 'comunhao', type: 'general', status: 'received', title: 'Comunhão', category: 'Comunhão', icon: '🕊️', text: 'Participação em momentos da igreja.', detail: 'Recebida por caminhar junto da família da fé.', progress: 100 },
  { id: 'devocional-constante', type: 'general', status: 'in_progress', title: 'Devocional Constante', category: 'Devocional', icon: '🕯️', text: '2 de 3 registros nesta semana.', detail: 'Próximo passo: registrar mais um devocional.', progress: 66 },
  { id: 'coracao-missionario', type: 'general', status: 'in_progress', title: 'Coração Missionário', category: 'Evangelismo', icon: '💒', text: '1 de 2 acompanhamentos de visitante.', detail: 'Avanço saudável em cuidado e acolhimento.', progress: 50 },
  { id: 'formacao', type: 'general', status: 'in_progress', title: 'Formação', category: 'Formação', icon: '🎓', text: '50% de participação em aula ou curso.', detail: 'Crescimento em ensino, discipulado e maturidade.', progress: 50 },
  { id: 'intercessao-com-cuidado', type: 'general', status: 'locked', title: 'Intercessão com cuidado', category: 'Intercessão', icon: '🙏', text: 'Categoria futura para registros de serviço e oração.', detail: 'Sempre com sigilo, validação pastoral e sem exposição.', progress: 0 },
  { id: 'validacao-servico', type: 'general', status: 'pending_validation', title: 'Serviço em validação', category: 'Serviço', icon: '✅', text: 'Registro aguardando confirmação.', detail: 'Alguns marcos precisam de confirmação da liderança ou secretaria.', progress: 80 },
  { id: 'palavra-trilho-autorizado', type: 'youth', status: 'hidden', title: 'Palavra do trilho autorizado', category: 'Trilho autorizado', icon: '🔥', text: 'Preparação futura para perfis autorizados.', detail: 'Não é enviada para membro comum quando não houver permissão.', progress: 0 },
  { id: 'coletivo-autorizado-futuro', type: 'youth_team', status: 'hidden', title: 'Conquista coletiva autorizada', category: 'Coletivo autorizado', icon: '🤝', text: 'Preparação futura para módulo autorizado.', detail: 'Não aparece para membro comum e não compara grupos.', progress: 0 },
  { id: 'administrativo-futuro', type: 'administrative', status: 'hidden', title: 'Conquista administrativa interna', category: 'Administração', icon: '♙', text: 'Preparação futura para gestão autorizada.', detail: 'Não deve virar catálogo público comum.', progress: 0 },
  { id: 'financeiro-futuro', type: 'financial', status: 'hidden', title: 'Registro financeiro privado', category: 'Financeiro', icon: '◈', text: 'Preparação futura privada e controlada por policy.', detail: 'Não deve expor vida financeira nem espiritualizar regularidade.', progress: 0 },
  { id: 'pastoral-sensivel-futuro', type: 'pastoral_sensitive', status: 'hidden', title: 'Registro pastoral sensível', category: 'Cuidado pastoral', icon: '🕊️', text: 'Preparação futura para registros internos com sigilo.', detail: 'Pode nem aparecer como badge, conforme decisão pastoral.', progress: 0 },
]

const generalCategories = [
  { icon: '👥', title: 'Presença', text: 'Constância em cultos e encontros gerais.' },
  { icon: '📖', title: 'Palavra', text: 'Leitura bíblica e crescimento no fundamento.' },
  { icon: '🕯️', title: 'Devocional', text: 'Pequenos registros de reflexão e prática.' },
  { icon: '🤝', title: 'Serviço', text: 'Escalas, apoio e disponibilidade na casa.' },
  { icon: '🕊️', title: 'Comunhão', text: 'Caminhada junto da família da fé.' },
  { icon: '💒', title: 'Evangelismo', text: 'Cuidado com visitantes e acolhimento.' },
  { icon: '🎓', title: 'Formação', text: 'Estudos, discipulado e maturidade.' },
  { icon: '🙏', title: 'Intercessão', text: 'Categoria futura com sigilo e validação pastoral.' },
]

const statusLabels = {
  received: 'Recebida',
  in_progress: 'Em progresso',
  locked: 'Próxima',
  pending_validation: 'Em validação',
}

const filters = computed(() => [
  { key: 'all', label: 'Todas' },
  { key: 'received', label: 'Recebidas' },
  { key: 'in_progress', label: 'Em progresso' },
  { key: 'locked', label: 'Próximas' },
  { key: 'categories', label: 'Categorias' },
  ...(viewerContext.canSeeYouthAchievements ? [{ key: 'youth', label: 'Trilho autorizado' }] : []),
  ...(viewerContext.canSeeYouthTeamAchievements ? [{ key: 'youth_team', label: 'Coletivas' }] : []),
  ...(viewerContext.canSeeAdministrativeAchievements ? [{ key: 'administrative', label: 'Administração' }] : []),
  ...(viewerContext.canSeeFinancialAchievements ? [{ key: 'financial', label: 'Financeiro' }] : []),
])
const activeFilter = ref('all')

const canSeeAchievement = (achievement) => {
  if (achievement.status === 'hidden') return false
  if (achievement.type === 'general') return viewerContext.canSeeGeneralAchievements
  if (achievement.type === 'youth') return viewerContext.canSeeYouthAchievements
  if (achievement.type === 'youth_team') return viewerContext.canSeeYouthTeamAchievements
  if (achievement.type === 'administrative') return viewerContext.canSeeAdministrativeAchievements
  if (achievement.type === 'financial') return viewerContext.canSeeFinancialAchievements
  return viewerContext.canSeeSensitiveAchievements
}

const visibleAchievements = computed(() => achievements.filter((achievement) => {
  if (!canSeeAchievement(achievement)) return false
  if (activeFilter.value === 'all' || activeFilter.value === 'categories') return true
  if (['received', 'in_progress', 'locked'].includes(activeFilter.value)) return achievement.status === activeFilter.value
  return achievement.type === activeFilter.value
}))
const receivedAchievements = computed(() => visibleAchievements.value.filter((achievement) => achievement.status === 'received'))
const progressAchievements = computed(() => visibleAchievements.value.filter((achievement) => ['in_progress', 'pending_validation', 'locked'].includes(achievement.status)))
const nextAchievement = computed(() => progressAchievements.value.find((achievement) => achievement.status === 'in_progress') || progressAchievements.value[0])
const featuredCategory = computed(() => nextAchievement.value?.category || 'Serviço')
const showAchievementLists = computed(() => activeFilter.value !== 'categories')
const summaryCards = computed(() => [
  { label: 'Conquistas recebidas', value: receivedAchievements.value.length, note: 'Marcos gerais pessoais' },
  { label: 'Em progresso', value: progressAchievements.value.filter((item) => item.status !== 'locked').length, note: 'Próximos passos visíveis' },
  { label: 'Próxima conquista', value: nextAchievement.value?.title || 'Palavra Viva', note: 'Sem pressa e sem comparação' },
  { label: 'Categoria em destaque', value: featuredCategory.value, note: 'Foco pastoral da semana' },
])
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
          <span>Acompanhe marcos saudáveis da sua caminhada geral, celebrando constância, serviço e crescimento sem comparação espiritual.</span>
        </div>
        <strong>Caminhada Geral</strong>
      </header>

      <section class="summary-strip" aria-label="Resumo das conquistas">
        <article v-for="card in summaryCards" :key="card.label">
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
          <header><span>Minhas conquistas</span><h2>Minhas conquistas</h2><p>Marcos já recebidos na sua caminhada geral.</p></header>
          <div class="achievement-grid">
            <article v-for="achievement in receivedAchievements" :key="achievement.id" class="achievement-card status-received">
              <i>{{ achievement.icon }}</i>
              <div><small>{{ achievement.category }}</small><strong>{{ achievement.title }}</strong><p>{{ achievement.text }}</p><em>{{ statusLabels[achievement.status] }}</em></div>
            </article>
          </div>
        </article>

        <article class="achievement-section">
          <header><span>Em progresso</span><h2>Em progresso</h2><p>Conquistas próximas, em validação ou aguardando uma etapa futura.</p></header>
          <div class="progress-grid">
            <article v-for="achievement in progressAchievements" :key="achievement.id" class="progress-card" :class="`status-${achievement.status}`">
              <header><i>{{ achievement.icon }}</i><div><small>{{ achievement.category }}</small><strong>{{ achievement.title }}</strong></div></header>
              <p>{{ achievement.text }}</p>
              <div class="progress-bar"><span :style="{ width: `${achievement.progress}%` }"></span></div>
              <footer><em>{{ statusLabels[achievement.status] }}</em><small>{{ achievement.detail }}</small></footer>
            </article>
          </div>
        </article>
      </section>

      <section class="categories-section" aria-label="Categorias gerais">
        <header><span>Categorias gerais</span><h2>Categorias da Caminhada Geral</h2><p>Áreas que organizam os marcos sem transformar a caminhada em competição.</p></header>
        <div class="categories-grid">
          <article v-for="category in generalCategories" :key="category.title"><i>{{ category.icon }}</i><strong>{{ category.title }}</strong><p>{{ category.text }}</p></article>
        </div>
      </section>

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
.achievements-hero, .summary-strip, .filters, .achievement-groups, .categories-section, .explanation-grid, .achievement-actions { max-width: 1280px; margin-left: auto; margin-right: auto; }
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
.filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
.filters button { min-height: 34px; padding: 8px 12px; border: 1px solid rgba(217,164,65,.22); border-radius: 999px; background: rgba(255,248,234,.76); color: #071b33; font-size: .72rem; font-weight: 950; cursor: pointer; transition: transform .2s ease, background .2s ease, border-color .2s ease; }
.filters button.active, .filters button:hover { border-color: rgba(217,164,65,.42); background: linear-gradient(135deg, #d9a441, #f6c85f); transform: translateY(-1px); }
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
