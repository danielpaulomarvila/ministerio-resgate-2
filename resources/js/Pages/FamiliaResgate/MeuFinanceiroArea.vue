<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue'

const props = defineProps({
  area: {
    type: String,
    required: true,
  },
  person: {
    type: String,
    default: null,
  },
  receipt: {
    type: String,
    default: null,
  },
})

// DADOS TEMPORÁRIOS PARA PROTÓTIPO VISUAL.
// Remover/substituir quando a integração real com backend for implementada.
// Futuramente será alimentado por financial_transactions, payments,
// receipts, canteen_debts, event_registrations, family_members,
// guardianships e permissões reais.
// Usuários só podem visualizar seus próprios registros e os filhos menores
// vinculados a eles como responsáveis.
// O backend deve validar acesso a filhos, impedir acesso por alteração de URL,
// validar permissões de downloads, proteger uploads e registrar correções em activity_logs.
const baseRoute = '/familia-resgate/meu-financeiro'

const areas = {
  historico: ['Histórico financeiro', 'Veja os lançamentos financeiros autorizados da sua família.', '▤'],
  recibos: ['Recibos', 'Consulte e baixe os recibos disponíveis para você e seus dependentes.', '☷'],
  comprovantes: ['Comprovantes', 'Acompanhe comprovantes enviados e seus status de validação.', '▧'],
  'enviar-comprovante': ['Enviar comprovante', 'Envie um comprovante para análise da equipe responsável.', '▧'],
  'solicitar-correcao': ['Solicitar correção', 'Peça uma revisão respeitosa de um lançamento financeiro.', '✎'],
  solicitacoes: ['Solicitações e Correções', 'Acompanhe pedidos de revisão enviados por você.', '☷'],
  cantina: ['Cantina da Família', 'Veja consumos e pendências da cantina vinculados a você e seus dependentes.', '▤'],
  eventos: ['Eventos, Retiros e Inscrições', 'Acompanhe inscrições, retiros, congressos e materiais vinculados à sua família.', '▣'],
  filhos: ['Financeiro dos Filhos', 'Acompanhe as responsabilidades financeiras dos filhos menores vinculados a você.', '♡'],
  'filho-detalhe': ['Financeiro do filho', 'Registros financeiros autorizados para acompanhamento familiar.', '♡'],
  pendencias: ['Pendências', 'Veja os valores em aberto seus e dos filhos menores vinculados a você.', '!'],
  creditos: ['Créditos', 'Acompanhe créditos disponíveis e possibilidades de abatimento.', '◈'],
  'download-recibo': ['Preparar download do recibo', 'Este recibo será gerado com segurança quando a integração real estiver ativa.', '☷'],
}

const people = [
  { slug: 'otto-marvila', initials: 'OM', name: 'Otto Marvila', age: 'Menor de 11 anos', access: 'Sem usuário próprio', pending: 4.5, canteen: 4.5, events: 0, receipts: 1, last: 'Cantina · 12/05/2026', rule: 'Vinculado ao responsável por ser menor de 11 anos.' },
  { slug: 'ana-marvila', initials: 'AM', name: 'Ana Marvila', age: 'Júnior Resgatados', access: 'Usuário supervisionado', pending: 10, canteen: 0, events: 10, receipts: 1, last: 'Evento jovem em análise', rule: 'Acompanhamento familiar autorizado por vínculo de responsabilidade.' },
  { slug: 'lucas-marvila', initials: 'LM', name: 'Lucas Marvila', age: 'Jovem Resgatado', access: 'Usuário próprio com acompanhamento familiar', pending: 57, canteen: 3, events: 54, receipts: 2, last: 'Retiro dos Jovens', rule: 'Menor de 18 com usuário próprio, acompanhado pelo responsável.' },
]

const rows = {
  historico: [
    ['12/05/2026', 'Otto Marvila', 'Cantina', 'Salgado + sumo', 2.5, 'Em aberto', 'open', `${baseRoute}/cantina`],
    ['10/05/2026', 'Daniel Paulo', 'Oferta', 'Oferta culto de domingo', 20, 'Pago', 'paid', `${baseRoute}/recibos/ofertas-maio-2026/baixar`],
    ['08/05/2026', 'Lucas Marvila', 'Retiro', 'Retiro dos Jovens', 45, 'Pendente', 'warning', `${baseRoute}/solicitar-correcao`],
  ],
  cantina: [
    ['12/05/2026', 'Otto Marvila', 'Cantina', 'Salgado + sumo', 2.5, 'Em aberto', 'open', `${baseRoute}/solicitar-correcao`],
    ['10/05/2026', 'Daniel Paulo', 'Cantina', 'Café + bolo', 1.8, 'Pago', 'paid', `${baseRoute}/historico`],
    ['11/05/2026', 'Lucas Marvila', 'Cantina', 'Água + lanche', 3, 'Em aberto', 'open', `${baseRoute}/enviar-comprovante`],
  ],
  eventos: [
    ['Vence em 5 dias', 'Lucas Marvila', 'Retiro', 'Retiro dos Jovens 2026', 45, 'Pendente', 'warning', `${baseRoute}/enviar-comprovante`],
    ['Recibo disponível', 'Ana Marvila', 'Evento', 'Congresso dos Jovens', 20, 'Pago', 'paid', `${baseRoute}/recibos/congresso-jovens/baixar`],
    ['Aguardando pagamento', 'Lucas Marvila', 'Inscrição', 'Camiseta Resgatados', 12, 'Em aberto', 'open', `${baseRoute}/solicitar-correcao`],
  ],
  pendencias: [
    ['Vence em 5 dias', 'Lucas Marvila', 'Retiro', 'Retiro dos Jovens', 45, 'Pendente', 'warning', `${baseRoute}/enviar-comprovante`],
    ['Desde 12/05/2026', 'Otto Marvila', 'Cantina', 'Cantina da Família', 4.5, 'Em aberto', 'open', `${baseRoute}/cantina`],
    ['Aguardando pagamento', 'Lucas Marvila', 'Camiseta', 'Camiseta Resgatados', 12, 'Em aberto', 'open', `${baseRoute}/solicitar-correcao`],
  ],
}

const receipts = [
  ['ofertas-maio-2026', 'Ofertas - Maio 2026', 'Daniel Paulo', 'Oferta', 20, 'Disponível', 'paid'],
  ['cantina-abril-2026', 'Cantina - Abril 2026', 'Otto Marvila', 'Cantina', 8.5, 'Disponível', 'paid'],
  ['retiro-dos-jovens', 'Retiro dos Jovens', 'Lucas Marvila', 'Retiro', 45, 'Aguardando validação', 'validation'],
]

const proofs = [
  ['Comprovante de oferta', 'Daniel Paulo', 'Contribuição', 20, 'Em análise', 'validation', 'Enviado em 10/05/2026'],
  ['Comprovante de retiro', 'Lucas Marvila', 'Retiro', 45, 'Validado', 'paid', 'Enviado em 08/05/2026'],
  ['Comprovante de cantina', 'Otto Marvila', 'Cantina', 4.5, 'Enviado', 'open', 'Enviado em 12/05/2026'],
]

const requests = [
  ['Oferta - 10/04/2026', 'Em análise', 'validation', 'Solicitado em 12/04/2026', 'A equipe está conferindo o lançamento.'],
  ['Cantina - 02/05/2026', 'Aberta', 'open', 'Solicitado em 03/05/2026', 'Aguardando análise inicial.'],
  ['Retiro - 15/03/2026', 'Aprovada', 'paid', 'Corrigida em 18/03/2026', 'Valor corrigido e histórico atualizado.'],
]

const correctionOptions = ['Valor incorreto', 'Pagamento já realizado', 'Lançamento duplicado', 'Pessoa errada', 'Recibo não apareceu', 'Outro motivo']
const formPeople = ['Daniel Paulo', 'Otto Marvila', 'Lucas Marvila', 'Ana Marvila']
const formTypes = ['Contribuição', 'Cantina', 'Evento', 'Retiro', 'Inscrição', 'Outro']
const filters = ['Todos', 'Minhas movimentações', 'Filhos', 'Cantina', 'Contribuições', 'Eventos', 'Pendentes', 'Pagos', 'Em análise']
const credit = { amount: 8, origin: 'Pagamento duplicado', uses: ['Cantina', 'Evento', 'Inscrição'], history: ['Gerado em 02/05/2026', 'Ainda não utilizado'] }

const money = (value) => new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' }).format(value)
const readableSlug = (value) => (value || 'recibo-preparado').split('-').map((word) => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
const activeArea = computed(() => areas[props.area] ? props.area : 'historico')
const selectedPerson = computed(() => people.find((item) => item.slug === props.person) || people[0])
const selectedReceipt = computed(() => receipts.find((item) => item[0] === props.receipt) || [props.receipt || 'recibo-preparado', readableSlug(props.receipt), 'Família Resgate', 'Recibo', 0, 'Preparação futura', 'validation'])
const pageTitle = computed(() => activeArea.value === 'filho-detalhe' ? `Financeiro de ${selectedPerson.value.name}` : areas[activeArea.value][0])
const pageSubtitle = computed(() => activeArea.value === 'filho-detalhe' ? areas['filho-detalhe'][1] : areas[activeArea.value][1])
const visibleRows = computed(() => rows[activeArea.value] || rows.historico)
const summaryCount = computed(() => {
  if (activeArea.value === 'recibos') return receipts.length
  if (activeArea.value === 'comprovantes') return proofs.length
  if (activeArea.value === 'solicitacoes') return requests.length
  if (['filhos', 'filho-detalhe'].includes(activeArea.value)) return people.length
  if (activeArea.value === 'creditos') return money(credit.amount)
  if (activeArea.value === 'download-recibo') return 'PDF'
  return visibleRows.value.length
})
const summaryLabel = computed(() => {
  if (activeArea.value === 'creditos') return 'Crédito disponível'
  if (activeArea.value === 'download-recibo') return 'Formato futuro'
  if (activeArea.value === 'filhos') return 'Filhos acompanhados'
  return 'Registros principais'
})
</script>

<template>
  <Head :title="pageTitle" />

  <div class="finance-area-shell">
    <FamilySidebar active-href="/familia-resgate/meu-financeiro" />

    <main class="finance-area-main">
      <header class="finance-area-hero">
        <div>
          <nav class="finance-area-kicker">
            <Link href="/familia-resgate">Central da Família</Link>
            <span>&gt;</span>
            <Link :href="baseRoute">Meu Financeiro</Link>
            <span>&gt;</span>
            <strong>{{ pageTitle }}</strong>
          </nav>
          <h1>{{ pageTitle }}</h1>
          <p>{{ pageSubtitle }}</p>
        </div>
        <aside>
          <span>{{ areas[activeArea][2] }}</span>
          <Link :href="baseRoute" class="finance-area-back">Voltar para Minha Vida Financeira</Link>
        </aside>
      </header>

      <section class="finance-summary-grid">
        <article class="finance-area-card">
          <strong>{{ summaryCount }}</strong>
          <span>{{ summaryLabel }}</span>
          <p>{{ activeArea === 'filho-detalhe' ? selectedPerson.access : 'Dados temporários organizados' }}</p>
        </article>
        <article class="finance-area-card finance-blue-card">
          <strong>Seguro</strong>
          <span>Integração futura</span>
          <p>Permissões, downloads, uploads e logs serão validados no backend.</p>
        </article>
        <article class="finance-area-card">
          <strong>Família</strong>
          <span>Acompanhamento autorizado</span>
          <p>Somente registros próprios e filhos menores vinculados ao responsável.</p>
        </article>
      </section>

      <section v-if="['historico', 'cantina', 'eventos', 'pendencias'].includes(activeArea)" class="finance-area-card content-card">
        <header class="section-heading">
          <div><h2>{{ pageTitle }}</h2><p>Lista compacta com status, valores e ações reais de navegação.</p></div>
          <Link :href="`${baseRoute}/solicitar-correcao`">Solicitar correção</Link>
        </header>
        <div class="filter-row">
          <Link v-for="filter in filters" :key="filter" :href="`${baseRoute}/${activeArea}`">{{ filter }}</Link>
        </div>
        <div class="finance-table">
          <div class="table-head"><span>Data</span><span>Pessoa</span><span>Tipo</span><span>Valor</span><span>Status</span><span>Ação</span></div>
          <Link v-for="row in visibleRows" :key="row[0] + row[1] + row[3]" :href="row[7]" class="table-row">
            <span>{{ row[0] }}</span>
            <span>{{ row[1] }}</span>
            <span><b>{{ row[2] }}</b><small>{{ row[3] }}</small></span>
            <strong>{{ money(row[4]) }}</strong>
            <em :class="`status-${row[6]}`">{{ row[5] }}</em>
            <i>Ver detalhes</i>
          </Link>
        </div>
      </section>

      <section v-else-if="activeArea === 'recibos'" class="content-grid">
        <article v-for="receiptItem in receipts" :key="receiptItem[0]" class="finance-area-card item-card">
          <header><span>{{ receiptItem[3] }}</span><em :class="`status-${receiptItem[6]}`">{{ receiptItem[5] }}</em></header>
          <h2>{{ receiptItem[1] }}</h2>
          <p>{{ receiptItem[2] }} · {{ money(receiptItem[4]) }}</p>
          <div class="card-actions">
            <Link :href="`${baseRoute}/recibos/${receiptItem[0]}/baixar`">Baixar</Link>
            <Link :href="`${baseRoute}/solicitar-correcao`">Segunda via</Link>
          </div>
        </article>
      </section>

      <section v-else-if="activeArea === 'comprovantes'" class="content-grid">
        <article v-for="proof in proofs" :key="proof[0]" class="finance-area-card item-card">
          <header><span>{{ proof[2] }}</span><em :class="`status-${proof[5]}`">{{ proof[4] }}</em></header>
          <h2>{{ proof[0] }}</h2>
          <p>{{ proof[1] }} · {{ money(proof[3]) }}</p>
          <small>{{ proof[6] }}</small>
          <div class="card-actions"><Link :href="`${baseRoute}/enviar-comprovante`">Enviar novo</Link><Link :href="`${baseRoute}/solicitar-correcao`">Revisar</Link></div>
        </article>
      </section>

      <section v-else-if="activeArea === 'enviar-comprovante'" class="finance-area-card form-card">
        <header class="section-heading"><div><h2>Formulário visual preparado</h2><p>Não envia dados ainda; upload real será seguro e validado no backend.</p></div></header>
        <div class="form-grid">
          <label><span>Pessoa relacionada</span><select><option v-for="item in formPeople" :key="item">{{ item }}</option></select></label>
          <label><span>Tipo</span><select><option v-for="item in formTypes" :key="item">{{ item }}</option></select></label>
          <label><span>Valor</span><input value="€ 20,00" readonly></label>
          <label><span>Data do pagamento</span><input value="10/05/2026" readonly></label>
          <label class="wide"><span>Descrição</span><textarea rows="3" value="Pagamento realizado para validação futura."></textarea></label>
          <label class="upload wide"><span>Upload de comprovante</span><b>Selecionar arquivo futuramente</b><small>PDF, PNG ou JPG com validação segura.</small></label>
          <label class="wide"><span>Observações</span><textarea rows="3" value="Observação para a equipe responsável."></textarea></label>
        </div>
        <div class="result-note"><strong>Resultado esperado:</strong><span>Após integrar o backend, o envio cairá em Comprovantes com status “Enviado” ou “Em análise”.</span></div>
        <div class="card-actions"><Link :href="`${baseRoute}/comprovantes`">Cancelar</Link><Link :href="`${baseRoute}/comprovantes`">Enviar para análise</Link></div>
      </section>

      <section v-else-if="activeArea === 'solicitar-correcao'" class="finance-area-card form-card correction-card">
        <header class="section-heading"><div><h2>Solicitar correção financeira</h2><p>Você está no lugar certo. Preencha os campos abaixo e envie o pedido para análise.</p></div></header>
        <div class="correction-intro"><strong>Comece aqui</strong><span>Escolha o lançamento que está errado, informe o motivo e descreva o que precisa ser corrigido.</span></div>
        <div class="form-grid correction-form">
          <label class="wide"><span>1. Lançamento que precisa de correção</span><select><option>Cantina · Otto Marvila · € 2,50 · Em aberto</option><option>Oferta · Daniel Paulo · € 20,00 · Pago</option><option>Retiro · Lucas Marvila · € 45,00 · Pendente</option></select></label>
          <label><span>2. Motivo da correção</span><select><option v-for="item in correctionOptions" :key="item">{{ item }}</option></select></label>
          <label><span>3. Pessoa relacionada</span><select><option v-for="item in formPeople" :key="item">{{ item }}</option></select></label>
          <label class="upload wide"><span>4. Anexar comprovante, se tiver</span><b>Selecionar arquivo</b><small>PDF, PNG ou JPG. O envio real será validado com segurança.</small></label>
          <label class="wide"><span>5. Explique a correção solicitada</span><textarea rows="4" placeholder="Exemplo: este pagamento já foi realizado no dia 10/05, mas ainda aparece como pendente."></textarea></label>
        </div>
        <div class="result-note"><strong>Depois de enviar:</strong><span>O pedido aparecerá em Solicitações e Correções com status inicial “Aberta”.</span></div>
        <div class="card-actions"><Link :href="baseRoute">Cancelar</Link><Link :href="`${baseRoute}/solicitacoes`">Enviar solicitação de correção</Link></div>
      </section>

      <section v-else-if="activeArea === 'solicitacoes'" class="content-grid">
        <article v-for="request in requests" :key="request[0]" class="finance-area-card item-card">
          <header><span>{{ request[3] }}</span><em :class="`status-${request[2]}`">{{ request[1] }}</em></header>
          <h2>{{ request[0] }}</h2><p>{{ request[4] }}</p>
          <div class="card-actions"><Link :href="`${baseRoute}/solicitar-correcao`">Nova solicitação</Link><Link :href="`${baseRoute}/historico`">Ver histórico</Link></div>
        </article>
      </section>

      <section v-else-if="activeArea === 'filhos'" class="content-grid">
        <article v-for="child in people" :key="child.slug" class="finance-area-card person-card">
          <b>{{ child.initials }}</b><h2>{{ child.name }}</h2><p>{{ child.age }} · {{ child.access }}</p>
          <strong>{{ money(child.pending) }} em aberto</strong><small>{{ child.last }}</small>
          <Link :href="`${baseRoute}/filhos/${child.slug}`">Ver financeiro do filho</Link>
        </article>
      </section>

      <section v-else-if="activeArea === 'filho-detalhe'" class="content-grid">
        <article class="finance-area-card finance-blue-card span-2"><h2>{{ selectedPerson.name }}</h2><p>{{ selectedPerson.age }} · {{ selectedPerson.access }}</p><strong>{{ money(selectedPerson.pending) }} pendente</strong><small>{{ selectedPerson.rule }}</small></article>
        <article v-for="block in ['Pendências', 'Cantina', 'Eventos', 'Recibos', 'Histórico']" :key="block" class="finance-area-card item-card"><h2>{{ block }}</h2><p>{{ selectedPerson.last }}</p><strong>{{ block === 'Cantina' ? money(selectedPerson.canteen) : block === 'Eventos' ? money(selectedPerson.events) : selectedPerson.receipts }}</strong><Link :href="block === 'Cantina' ? `${baseRoute}/cantina` : block === 'Eventos' ? `${baseRoute}/eventos` : `${baseRoute}/historico`">Abrir</Link></article>
      </section>

      <section v-else-if="activeArea === 'creditos'" class="content-grid">
        <article class="finance-area-card finance-blue-card"><h2>Crédito disponível</h2><strong>{{ money(credit.amount) }}</strong><p>Origem: {{ credit.origin }}</p></article>
        <article class="finance-area-card item-card"><h2>Pode ser usado em</h2><em v-for="item in credit.uses" :key="item" class="pill">{{ item }}</em></article>
        <article class="finance-area-card item-card"><h2>Histórico</h2><p v-for="item in credit.history" :key="item">{{ item }}</p><Link :href="`${baseRoute}/solicitar-correcao`">Solicitar uso</Link></article>
      </section>

      <section v-else-if="activeArea === 'download-recibo'" class="finance-area-card form-card">
        <header class="section-heading"><div><h2>Download seguro futuro</h2><p>O arquivo PDF real será gerado apenas após validação de permissão.</p></div></header>
        <div class="download-box"><span>PDF</span><h2>{{ selectedReceipt[1] }}</h2><p>{{ selectedReceipt[2] }} · {{ selectedReceipt[3] }}</p><strong>{{ selectedReceipt[4] ? money(selectedReceipt[4]) : 'Valor definido no backend' }}</strong><em :class="`status-${selectedReceipt[6]}`">{{ selectedReceipt[5] }}</em><small>Dados sensíveis não devem ser expostos sem autorização.</small></div>
        <div class="result-note"><strong>Resultado esperado:</strong><span>Quando o backend estiver ativo, esta etapa validará a permissão e entregará o PDF autorizado.</span></div>
        <div class="card-actions"><Link :href="`${baseRoute}/recibos`">Voltar para recibos</Link><Link :href="`${baseRoute}/solicitar-correcao`">Informar problema</Link></div>
      </section>
    </main>
  </div>
</template>

<style scoped>
.finance-area-shell{min-height:100vh;display:flex;background:radial-gradient(circle at 10% 8%,rgba(217,164,65,.18),transparent 28%),radial-gradient(circle at 90% 0,rgba(245,158,11,.12),transparent 32%),linear-gradient(135deg,#fff8ea,#f7efe1 54%,#fff4db);color:#071b33;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.finance-area-main{width:100%;margin-left:80px;padding:24px 26px 34px}.finance-area-hero{display:flex;justify-content:space-between;gap:18px;align-items:center;margin-bottom:15px;padding:20px 22px;border-radius:28px;border:1px solid rgba(217,164,65,.22);background:linear-gradient(135deg,rgba(255,255,255,.82),rgba(255,248,234,.58));box-shadow:0 18px 42px rgba(7,27,51,.08);backdrop-filter:blur(18px) saturate(145%)}.finance-area-hero h1{margin:8px 0 8px;font:850 clamp(2rem,4vw,3.25rem)/1 Georgia,serif;letter-spacing:-.045em}.finance-area-hero p{max-width:760px;margin:0;color:#516070;font-size:1rem;line-height:1.55}.finance-area-hero aside{display:grid;justify-items:end;gap:12px}.finance-area-hero aside>span{display:grid;place-items:center;width:58px;height:58px;border-radius:20px;background:linear-gradient(135deg,rgba(7,27,51,.94),rgba(11,39,72,.86));border:1px solid rgba(217,164,65,.24);box-shadow:0 22px 48px rgba(7,27,51,.22),inset 0 1px 0 rgba(255,255,255,.1);color:#f7c76b;font-size:1.45rem}.finance-area-kicker{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin:0;color:#8a5b13;font-size:.8rem;font-weight:900;letter-spacing:.06em;text-transform:uppercase}.finance-area-kicker a{color:#8a5b13;text-decoration:none}.finance-area-card{position:relative;overflow:hidden;border-radius:24px;border:1px solid rgba(255,255,255,.72);background:linear-gradient(135deg,rgba(255,255,255,.78),rgba(255,248,234,.48));box-shadow:0 18px 42px rgba(7,27,51,.075),inset 0 1px 0 rgba(255,255,255,.72);padding:18px;backdrop-filter:blur(18px) saturate(145%)}.finance-area-card:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 10% 0,rgba(255,255,255,.68),transparent 28%),linear-gradient(125deg,rgba(217,164,65,.11),transparent 44%);pointer-events:none}.finance-area-card>*{position:relative}.blue-card{background:linear-gradient(135deg,rgba(7,27,51,.94),rgba(11,39,72,.86));border:1px solid rgba(217,164,65,.24);box-shadow:0 22px 48px rgba(7,27,51,.22),inset 0 1px 0 rgba(255,255,255,.1);color:#fff8ea}.blue-card p,.blue-card small,.blue-card span{color:#f9ead0}.finance-summary-grid,.content-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:15px}.finance-summary-grid strong{display:block;font-size:1.35rem}.finance-summary-grid span{display:block;margin-top:4px;font-weight:900}.finance-summary-grid p,.finance-area-card p,.finance-area-card small{color:#516070;font-size:.91rem;line-height:1.45}.content-card,.form-card{margin-bottom:15px}.section-heading{display:flex;justify-content:space-between;gap:14px;align-items:flex-start;margin-bottom:14px}.section-heading h2,.item-card h2,.person-card h2{margin:0;color:#071b33;font-size:1.12rem}.section-heading p{margin:4px 0 0}.section-heading>a,.finance-area-back,.card-actions a,.card-actions button,.person-card>a,.item-card>a{display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:9px 14px;border:0;border-radius:999px;background:#071b33;color:#fff8ea;text-decoration:none;font-size:.9rem;font-weight:900;cursor:pointer}.filter-row{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}.filter-row a,.pill,.step-grid em{padding:7px 10px;border-radius:999px;border:1px solid rgba(217,164,65,.16);background:rgba(255,255,255,.58);color:#0b2748;text-decoration:none;font-size:.82rem;font-weight:850;font-style:normal}.finance-table{display:grid;gap:8px}.table-head,.table-row{display:grid;grid-template-columns:120px 1fr 1.25fr 90px 110px 96px;gap:10px;align-items:center}.table-head{padding:0 10px;color:#516070;font-size:.8rem;font-weight:950}.table-row{padding:11px;border-radius:17px;border:1px solid rgba(217,164,65,.14);background:rgba(255,255,255,.54);color:#071b33;text-decoration:none}.table-row small{display:block}.table-row i{font-style:normal;font-weight:900;color:#0b2748}.item-card,.person-card{display:grid;gap:10px}.item-card header{display:flex;justify-content:space-between;gap:10px;align-items:center}.item-card header span{font-weight:900;color:#8a5b13}.item-card strong,.person-card strong,.download-box strong{font-size:1.15rem}.person-card>b{display:grid;place-items:center;width:46px;height:46px;border-radius:17px;background:#071b33;color:#f7c76b}.card-actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:8px}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.form-grid label{display:grid;gap:6px}.form-grid .wide{grid-column:1/-1}.form-grid span{font-size:.9rem;font-weight:900;color:#071b33}.form-grid input,.form-grid select,.form-grid textarea{width:100%;border:1px solid rgba(217,164,65,.22);border-radius:16px;background:rgba(255,255,255,.72);padding:11px 12px;color:#071b33;font:inherit}.upload{padding:14px;border-radius:18px;border:1px dashed rgba(217,164,65,.42);background:rgba(255,255,255,.46)}.step-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.step-grid article{padding:14px;border-radius:18px;background:rgba(255,255,255,.55);border:1px solid rgba(217,164,65,.15)}.step-grid article strong{display:block;margin-bottom:10px}.step-grid em{display:inline-flex;margin:0 6px 6px 0}.result-note{display:flex;gap:10px;align-items:flex-start;margin-top:14px;padding:12px 14px;border-radius:18px;border:1px solid rgba(217,164,65,.2);background:linear-gradient(135deg,rgba(255,255,255,.68),rgba(255,248,234,.42));color:#071b33}.result-note strong{white-space:nowrap;color:#8a5b13}.result-note span{color:#516070;font-size:.92rem;line-height:1.45}.download-box{display:grid;gap:8px;padding:18px;border-radius:22px;background:rgba(255,255,255,.56);border:1px solid rgba(217,164,65,.16)}.download-box>span{width:max-content;padding:8px 12px;border-radius:999px;background:#071b33;color:#f7c76b;font-weight:950}.status-paid,.status-open,.status-warning,.status-validation{display:inline-flex;width:max-content;padding:6px 9px;border-radius:999px;font-size:.78rem;font-style:normal;font-weight:950}.status-paid{background:rgba(22,163,74,.13);color:#15803d}.status-open,.status-warning{background:rgba(245,158,11,.14);color:#a16207}.status-validation{background:rgba(11,39,72,.1);color:#0b2748}.span-2{grid-column:span 2}@media(max-width:1120px){.finance-summary-grid,.content-grid,.step-grid{grid-template-columns:1fr 1fr}.span-2{grid-column:1/-1}.table-head{display:none}.table-row{grid-template-columns:1fr 1fr}}@media(max-width:760px){.finance-area-main{margin-left:0;padding:18px 14px 28px}.finance-area-hero,.section-heading,.result-note{flex-direction:column;align-items:flex-start}.finance-area-hero aside{justify-items:start}.finance-summary-grid,.content-grid,.step-grid,.form-grid{grid-template-columns:1fr}.table-row{grid-template-columns:1fr}.finance-area-hero h1{font-size:2.2rem}}
.correction-card {
  border-color: rgba(217, 164, 65, 0.28) !important;
}

.correction-card .section-heading h2 {
  font-size: 1.34rem;
}

.correction-intro {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0 0 14px;
  padding: 14px 16px;
  border-radius: 18px;
  background: linear-gradient(135deg, rgba(7, 27, 51, 0.96), rgba(11, 39, 72, 0.9));
  border: 1px solid rgba(217, 164, 65, 0.28);
  color: rgba(255, 255, 255, 0.92);
  box-shadow: 0 16px 34px rgba(7, 27, 51, 0.12);
}

.correction-intro strong {
  flex: 0 0 auto;
  padding: 8px 11px;
  border-radius: 999px;
  background: #f6c85f;
  color: #071b33;
  font-size: 0.84rem;
  font-weight: 950;
}

.correction-intro span {
  color: rgba(255, 255, 255, 0.88);
  font-weight: 750;
  line-height: 1.45;
}

.correction-form label {
  background: rgba(255, 255, 255, 0.66);
  border: 1px solid rgba(217, 164, 65, 0.18);
  border-radius: 18px;
  padding: 12px;
  box-shadow: 0 10px 24px rgba(7, 27, 51, 0.05);
}

.correction-form label > span {
  display: block;
  margin-bottom: 8px;
  color: #071b33;
  font-weight: 950;
}

.correction-form select,
.correction-form textarea {
  width: 100%;
  border: 1px solid rgba(7, 27, 51, 0.12);
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.82);
  color: #071b33;
  font-weight: 750;
}

.correction-card .card-actions a:last-child {
  background: linear-gradient(135deg, #071b33, #0b2748);
  color: #fff8ea;
  border-color: rgba(7, 27, 51, 0.22);
  box-shadow: 0 14px 28px rgba(7, 27, 51, 0.18);
}

.finance-blue-card,
.blue-card {
  background:
    radial-gradient(circle at 15% 10%, rgba(255, 255, 255, 0.14), transparent 28%),
    radial-gradient(circle at 85% 0%, rgba(217, 164, 65, 0.18), transparent 32%),
    linear-gradient(135deg, rgba(7, 27, 51, 0.96), rgba(11, 39, 72, 0.90)) !important;
  border: 1px solid rgba(217, 164, 65, 0.28) !important;
  box-shadow:
    0 22px 48px rgba(7, 27, 51, 0.22),
    inset 0 1px 0 rgba(255, 255, 255, 0.12) !important;
  color: rgba(255, 255, 255, 0.96) !important;
}

.finance-blue-card h1,
.finance-blue-card h2,
.finance-blue-card h3,
.finance-blue-card strong,
.finance-blue-card span,
.blue-card h1,
.blue-card h2,
.blue-card h3,
.blue-card strong,
.blue-card span,
.finance-blue-title {
  color: rgba(255, 255, 255, 0.96) !important;
}

.finance-blue-card p,
.finance-blue-card small,
.blue-card p,
.blue-card small,
.finance-blue-text {
  color: rgba(255, 255, 255, 0.82) !important;
}

.finance-blue-card .muted,
.blue-card .muted,
.finance-blue-muted {
  color: rgba(255, 255, 255, 0.72) !important;
}

.finance-blue-card strong,
.blue-card strong,
.finance-blue-value,
.finance-blue-gold {
  color: #f6c85f !important;
}

.finance-blue-card::before,
.blue-card::before {
  opacity: 0.28;
}
</style>
