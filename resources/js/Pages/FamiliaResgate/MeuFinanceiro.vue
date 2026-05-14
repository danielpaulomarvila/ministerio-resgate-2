<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FamilySidebar from '@/Components/FamiliaResgate/FamilySidebar.vue';

// DADOS TEMPORÁRIOS PARA PROTÓTIPO VISUAL.
// Remover/substituir quando a integração real com backend for implementada.
// Futuramente será alimentado por financial_transactions, payments,
// receipts, canteen_debts, event_registrations, family_members,
// guardianships e permissões reais.
// O usuário só pode ver seus próprios registros e os filhos menores
// vinculados a ele como responsável.
// A cantina deverá vincular consumos de menores aos responsáveis conforme idade.
// Jovens menores com usuário próprio continuam acompanhados pelos responsáveis.
// A segurança real ficará no backend por middleware, policies, vínculos familiares e auditoria.
const financialSummary = { registeredContributions: 125, openPending: 57.5, familyCanteenDebt: 12.5, availableReceipts: 8, waitingValidation: 2 };

const summaryCards = [
    ['Contribuições', financialSummary.registeredContributions, 'Este mês', '◈', 'currency', 'success'],
    ['Pendências', financialSummary.openPending, 'Em aberto', '!', 'currency', 'warning'],
    ['Cantina', financialSummary.familyCanteenDebt, 'Família', '▤', 'currency', 'canteen'],
    ['Recibos', financialSummary.availableReceipts, 'Disponíveis', '☷', 'number', 'receipt'],
    ['Validação', financialSummary.waitingValidation, 'Em análise', '◌', 'number', 'validation'],
];

const familyFinancialPeople = [
    { initials: 'DP', name: 'Daniel Paulo', relation: 'Eu', ageGroup: 'Adulto', chips: ['Responsável'], pendingTotal: 8, detail: 'Cantina · 4 recibos', route: '/familia-resgate/meu-financeiro/historico', action: 'Abrir', accent: 'self' },
    { initials: 'OM', name: 'Otto Marvila', relation: 'Filho', ageGroup: 'Menor de 11', chips: ['Criança', 'Responsável'], pendingTotal: 4.5, detail: 'Cantina · 1 recibo', responsible: 'Daniel Paulo Marvila', route: '/familia-resgate/meu-financeiro/filhos/otto-marvila', action: 'Detalhes', accent: 'child' },
    { initials: 'LM', name: 'Lucas Marvila', relation: 'Filho', ageGroup: 'Jovem menor', chips: ['Usuário próprio', 'Acompanhado'], pendingTotal: 57, detail: 'Retiro · Camiseta · Cantina', hasOwnUser: true, responsibleCanView: true, route: '/familia-resgate/meu-financeiro/filhos/lucas-marvila', action: 'Ver filho', accent: 'youth' },
    { initials: 'AM', name: 'Ana Marvila', relation: 'Filha', ageGroup: 'Júnior', chips: ['Supervisionada'], pendingTotal: 10, detail: 'Evento jovem · em análise', hasOwnUser: true, responsibleCanView: true, route: '/familia-resgate/meu-financeiro/filhos/ana-marvila', action: 'Detalhes', accent: 'junior' },
];

const visibleFamilyFinancialPeople = familyFinancialPeople.filter((person) => person.relation !== 'Eu').slice(0, 3);

const importantPendencies = [
    ['Retiro dos Jovens', 'Lucas · vence em 5 dias', 45, 'Pendente', 'warning', '/familia-resgate/meu-financeiro/pendencias'],
    ['Cantina', 'Otto · em aberto', 4.5, 'Em aberto', 'open', '/familia-resgate/meu-financeiro/cantina'],
    ['Comprovante enviado', 'Daniel · em análise', 20, 'Em análise', 'validation', '/familia-resgate/meu-financeiro/comprovantes'],
];

const canteenFamilyItems = [
    ['Otto', 'Salgado + sumo', 2.5, 'Em aberto', 'open'],
    ['Daniel', 'Café + bolo', 1.8, 'Pago', 'paid'],
    ['Lucas', 'Água + lanche', 3, 'Em aberto', 'open'],
];

const eventFinancialItems = [
    ['Retiro dos Jovens 2026', 'Lucas', 45, 'Pendente', 'warning'],
    ['Congresso dos Jovens', 'Ana', 20, 'Pago', 'paid'],
    ['Camiseta Resgatados', 'Lucas', 12, 'Aguardando', 'open'],
];

const receipts = [
    ['Ofertas - Maio 2026', 'Disponível · 12/05', 'Baixar', '/familia-resgate/meu-financeiro/recibos/maio-2026-ofertas/baixar'],
    ['Cantina - Abril 2026', 'Disponível · 30/04', 'Baixar', '/familia-resgate/meu-financeiro/recibos/cantina-abril-2026/baixar'],
    ['Retiro dos Jovens', 'Em validação · 08/05', 'Ver', '/familia-resgate/meu-financeiro/comprovantes'],
];

const financialHistory = [
    ['12/05', 'Otto', 'Cantina', 'Salgado + sumo', 2.5, 'Em aberto', 'Ver'],
    ['10/05', 'Daniel', 'Oferta', 'Culto de domingo', 20, 'Pago', 'Recibo'],
    ['08/05', 'Lucas', 'Retiro', 'Jovens', 45, 'Pendente', 'Detalhes'],
    ['05/05', 'Ana', 'Evento', 'Jovens', 10, 'Em análise', 'Ver'],
];

const historyFilters = ['Todos', 'Filhos', 'Cantina', 'Contribuições', 'Eventos', 'Pendentes', 'Pagos'];
const correctionRequests = [['Oferta - 10/04/2026', 'Em análise', 'validation'], ['Cantina - 02/05/2026', 'Aberta', 'open'], ['Retiro - 15/03/2026', 'Aprovada', 'paid']];
const futureCredit = { amount: 8, origin: 'pagamento duplicado' };
const money = (value) => new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' }).format(value);
const valueOf = (card) => card[4] === 'currency' ? money(card[1]) : card[1];
</script>

<template>
    <Head title="Minha Vida Financeira" />
    <main class="finance-screen">
        <FamilySidebar active-href="/familia-resgate/meu-financeiro" />
        <section class="finance-main">
            <header class="finance-topbar">
                <div class="finance-title">
                    <nav><Link href="/familia-resgate">Central da Família</Link><span>›</span><strong>Meu Financeiro</strong></nav>
                    <h1>Minha Vida Financeira</h1>
                    <p>Acompanhe suas contribuições, pendências, recibos e responsabilidades familiares.</p>
                </div>
                <div class="finance-actions"><span>Atualizado hoje às 10:42</span><Link href="/familia-resgate/meu-financeiro/solicitar-correcao">Solicitar correção</Link></div>
            </header>

            <section class="summary-grid" aria-label="Resumo financeiro rápido">
                <article v-for="card in summaryCards" :key="card[0]" class="summary-card glass-card" :class="'tone-' + card[5]">
                    <span>{{ card[3] }}</span><div><small>{{ card[0] }}</small><strong>{{ valueOf(card) }}</strong><p>{{ card[2] }}</p></div>
                </article>
            </section>

            <section class="finance-layout">
                <section class="family-finance glass-card span-8">
                    <header class="section-heading"><div><h2>Financeiro da Família</h2><p>Filhos menores vinculados a você.</p></div><Link href="/familia-resgate/meu-financeiro/filhos">Ver todos</Link></header>
                    <div class="family-people-grid">
                        <article v-for="person in visibleFamilyFinancialPeople" :key="person.name" class="person-card" :class="'accent-' + person.accent">
                            <div class="avatar">{{ person.initials }}</div>
                            <div class="person-copy"><strong>{{ person.name }}</strong><small>{{ person.relation }} · {{ person.ageGroup }}</small><div><em v-for="chip in person.chips" :key="chip">{{ chip }}</em></div></div>
                            <mark>{{ money(person.pendingTotal) }}</mark><p>{{ person.detail }}</p><Link :href="person.route">{{ person.action }}</Link>
                        </article>
                    </div>
                </section>

                <section class="attention-card glass-card span-4">
                    <header class="section-heading"><div><h2>Pendências importantes</h2><p>Prioridades da semana.</p></div><Link href="/familia-resgate/meu-financeiro/pendencias">Ver todas</Link></header>
                    <div class="line-list priority-list"><Link v-for="item in importantPendencies" :key="item[0]" :href="item[5]"><i>!</i><span><strong>{{ item[0] }}</strong><small>{{ item[1] }}</small></span><b>{{ money(item[2]) }}</b><em :class="'status-' + item[4]">{{ item[3] }}</em></Link></div>
                </section>

                <section class="panel-card glass-card span-4"><header class="section-heading compact"><div><h2>Cantina da Família</h2><p>Você e dependentes.</p></div><Link href="/familia-resgate/meu-financeiro/cantina">Ver consumo</Link></header><div class="mini-list"><article v-for="item in canteenFamilyItems" :key="item[0] + item[1]"><span><strong>{{ item[0] }}</strong><small>{{ item[1] }}</small></span><b>{{ money(item[2]) }}</b><em :class="'status-' + item[4]">{{ item[3] }}</em></article></div></section>

                <section class="panel-card glass-card span-4"><header class="section-heading compact"><div><h2>Eventos e Inscrições</h2><p>Retiros, congressos e materiais.</p></div><Link href="/familia-resgate/meu-financeiro/eventos">Ver todos</Link></header><div class="mini-list"><article v-for="item in eventFinancialItems" :key="item[0]"><span><strong>{{ item[0] }}</strong><small>{{ item[1] }}</small></span><b>{{ money(item[2]) }}</b><em :class="'status-' + item[4]">{{ item[3] }}</em></article></div></section>

                <section class="panel-card glass-card span-4"><header class="section-heading compact"><div><h2>Recibos e Comprovantes</h2><p>Downloads e validações.</p></div><Link href="/familia-resgate/meu-financeiro/recibos">Ver todas</Link></header><div class="receipt-list"><article v-for="receipt in receipts" :key="receipt[0]"><span><strong>{{ receipt[0] }}</strong><small>{{ receipt[1] }}</small></span><Link :href="receipt[3]">{{ receipt[2] }}</Link></article></div><div class="receipt-actions"><Link href="/familia-resgate/meu-financeiro/comprovantes">Comprovantes</Link><Link href="/familia-resgate/meu-financeiro/enviar-comprovante">Enviar</Link></div></section>

                <section class="history-card glass-card span-8">
                    <header class="section-heading"><div><h2>Histórico financeiro</h2><p>Movimentações recentes autorizadas.</p></div><Link href="/familia-resgate/meu-financeiro/historico">Ver histórico completo</Link></header>
                    <div class="filter-row"><Link v-for="filter in historyFilters" :key="filter" href="/familia-resgate/meu-financeiro/historico">{{ filter }}</Link></div>
                    <div class="history-table"><div class="history-head"><span>Data</span><span>Pessoa</span><span>Tipo</span><span>Valor</span><span>Status</span><span>Ação</span></div><Link v-for="item in financialHistory" :key="item[0] + item[1] + item[2]" href="/familia-resgate/meu-financeiro/historico" class="history-row"><span>{{ item[0] }}</span><span>{{ item[1] }}</span><span><b>{{ item[2] }}</b><small>{{ item[3] }}</small></span><strong>{{ money(item[4]) }}</strong><em>{{ item[5] }}</em><i>{{ item[6] }}</i></Link></div>
                </section>

                <section class="corrections-card glass-card span-4">
                    <header class="section-heading compact"><div><h2>Solicitações e Correções</h2><p>Pedidos e análises.</p></div><Link href="/familia-resgate/meu-financeiro/solicitacoes">Ver todas</Link></header>
                    <div class="request-list"><article v-for="request in correctionRequests" :key="request[0]"><span>{{ request[0] }}</span><em :class="'status-' + request[2]">{{ request[1] }}</em></article></div>
                    <div class="credit-strip"><span>Crédito disponível: <b>{{ money(futureCredit.amount) }}</b></span><small>Origem: {{ futureCredit.origin }}</small><Link href="/familia-resgate/meu-financeiro/creditos">Ver créditos</Link></div>
                    <Link href="/familia-resgate/meu-financeiro/solicitar-correcao" class="primary-action">Nova correção</Link>
                </section>
            </section>
        </section>
    </main>
</template>

<style scoped>
.finance-screen{min-height:100vh;background:radial-gradient(circle at 14% 8%,rgba(217,164,65,.18),transparent 27%),radial-gradient(circle at 84% 2%,rgba(245,158,11,.10),transparent 31%),linear-gradient(135deg,#fff8ea 0%,#f7efe1 54%,#fff3d9 100%);color:#0d1b2a;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.finance-main{margin-left:80px;padding:22px 24px 30px;max-width:1520px}.glass-card{position:relative;overflow:hidden;background:linear-gradient(135deg,rgba(255,255,255,.70),rgba(255,248,234,.46));backdrop-filter:blur(18px) saturate(145%);border:1px solid rgba(255,255,255,.66);box-shadow:0 18px 42px rgba(7,27,51,.075),inset 0 1px 0 rgba(255,255,255,.62);border-radius:22px}.glass-card:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 10% 0,rgba(255,255,255,.58),transparent 30%),linear-gradient(120deg,rgba(217,164,65,.10),transparent 42%);pointer-events:none}.glass-card>*{position:relative}.finance-topbar{display:flex;justify-content:space-between;gap:18px;align-items:center;margin-bottom:14px;padding:18px 20px;border-radius:26px;background:linear-gradient(135deg,rgba(7,27,51,.97),rgba(11,39,72,.92));box-shadow:0 18px 44px rgba(7,27,51,.15);color:#fff8ea;overflow:hidden;position:relative}.finance-topbar:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 88% 10%,rgba(217,164,65,.22),transparent 28%),linear-gradient(115deg,transparent 0 48%,rgba(255,248,234,.06) 56%,transparent 66%)}.finance-title,.finance-actions{position:relative}.finance-title nav{display:flex;gap:8px;align-items:center;margin-bottom:8px;font-size:.86rem}.finance-title nav a{color:#f7c76b;text-decoration:none;font-weight:850}.finance-title nav span{color:rgba(255,248,234,.62)}.finance-title h1{margin:0;font:800 clamp(1.9rem,3.6vw,3.4rem)/1 Georgia,serif;letter-spacing:-.045em}.finance-title p{max-width:690px;margin:9px 0 0;color:#f9ead0;font-size:.96rem;line-height:1.45}.finance-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end}.finance-actions span{padding:8px 11px;border-radius:999px;background:rgba(255,255,255,.1);border:1px solid rgba(247,199,107,.22);font-size:.84rem;font-weight:850;color:#f9ead0}.finance-actions a,.section-heading>a,.primary-action{display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:9px 13px;border-radius:999px;background:#d9a441;color:#071b33;text-decoration:none;font-size:.88rem;font-weight:900}.section-heading>a{background:rgba(7,27,51,.92);color:#fff8ea}.summary-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-bottom:15px}.summary-card{display:flex;align-items:center;gap:11px;min-height:86px;padding:13px 14px}.summary-card>span{display:grid;place-items:center;width:38px;min-width:38px;height:38px;border-radius:14px;background:rgba(217,164,65,.14);font-weight:950}.summary-card small{font-size:.78rem;font-weight:900;color:#516070}.summary-card strong{display:block;margin:2px 0;color:#071b33;font-size:1.22rem}.summary-card p{margin:0;color:#516070;font-size:.82rem}.tone-success>span{color:#16a34a}.tone-warning>span{color:#dc2626}.tone-canteen>span{color:#f59e0b}.tone-receipt>span,.tone-validation>span{color:#0b2748}
.finance-layout{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:15px}.span-8{grid-column:span 8}.span-4{grid-column:span 4}.family-finance,.attention-card,.panel-card,.history-card,.corrections-card{padding:16px}.section-heading{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:12px}.section-heading.compact{margin-bottom:10px}.section-heading h2{margin:0;color:#071b33;font-size:1.1rem;letter-spacing:-.02em}.section-heading p{margin:4px 0 0;color:#516070;font-size:.86rem}.family-people-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:11px}.person-card{display:grid;grid-template-columns:auto 1fr;gap:9px;align-items:start;padding:12px;border-radius:18px;background:linear-gradient(135deg,rgba(255,255,255,.58),rgba(255,248,234,.36));border:1px solid rgba(217,164,65,.15);box-shadow:0 10px 22px rgba(7,27,51,.05);transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease}.person-card:hover,.line-list a:hover,.mini-list article:hover,.receipt-list article:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.36);box-shadow:0 14px 28px rgba(7,27,51,.08)}.avatar{display:grid;place-items:center;width:38px;height:38px;border-radius:14px;background:rgba(7,27,51,.94);color:#f7c76b;font-size:.82rem;font-weight:950}.person-copy strong{display:block;font-size:.93rem}.person-copy small,.person-card p{color:#516070;font-size:.8rem}.person-copy div{display:flex;gap:5px;flex-wrap:wrap;margin-top:6px}.person-copy em{font-style:normal;padding:4px 7px;border-radius:999px;background:rgba(217,164,65,.13);color:#8a5b13;font-size:.72rem;font-weight:900}.person-card mark{grid-column:1/3;width:max-content;background:rgba(255,255,255,.76);border:1px solid rgba(217,164,65,.18);border-radius:999px;padding:6px 9px;color:#071b33;font-size:.86rem;font-weight:950}.person-card p{grid-column:1/3;margin:0}.person-card>a{grid-column:1/3;min-height:34px;display:inline-flex;align-items:center;justify-content:center;border-radius:12px;background:rgba(217,164,65,.12);color:#0b2748;text-decoration:none;font-size:.84rem;font-weight:950}
.line-list,.mini-list,.receipt-list,.request-list{display:grid;gap:8px}.line-list a,.mini-list article,.receipt-list article,.request-list article{display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:9px;padding:10px 11px;border-radius:16px;background:linear-gradient(135deg,rgba(255,255,255,.55),rgba(255,248,234,.32));border:1px solid rgba(217,164,65,.13);text-decoration:none;color:#0d1b2a}.priority-list a{grid-template-columns:auto 1fr auto}.priority-list i{display:grid;place-items:center;width:28px;height:28px;border-radius:12px;background:rgba(245,158,11,.12);color:#a16207;font-style:normal;font-weight:950}.line-list strong,.mini-list strong,.receipt-list strong{display:block;font-size:.88rem}.line-list small,.mini-list small,.receipt-list small{display:block;color:#516070;font-size:.78rem}.line-list b,.mini-list b{font-size:.9rem}.line-list em,.mini-list em,.request-list em,.history-row em{font-style:normal;padding:6px 8px;border-radius:999px;background:rgba(217,164,65,.14);font-size:.76rem;font-weight:900;color:#8a5b13;white-space:nowrap}.status-paid{background:rgba(22,163,74,.12)!important;color:#15803d!important}.status-open,.status-warning{background:rgba(245,158,11,.13)!important;color:#a16207!important}.status-validation{background:rgba(11,39,72,.10)!important;color:#0b2748!important}.receipt-list article{grid-template-columns:1fr auto}.receipt-list a,.receipt-actions a,.credit-strip a{color:#0b2748;text-decoration:none;font-size:.84rem;font-weight:950}.receipt-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}.receipt-actions a{min-height:34px;padding:8px 11px;border-radius:999px;background:rgba(217,164,65,.13)}
.filter-row{display:flex;gap:7px;flex-wrap:wrap;margin-bottom:10px}.filter-row a{padding:7px 10px;border-radius:999px;background:rgba(255,255,255,.5);border:1px solid rgba(217,164,65,.13);color:#0b2748;text-decoration:none;font-size:.78rem;font-weight:850}.history-table{display:grid;gap:7px}.history-head,.history-row{display:grid;grid-template-columns:76px 1fr 1.25fr 92px 112px 72px;gap:9px;align-items:center}.history-head{padding:0 10px;color:#516070;font-size:.78rem;font-weight:950}.history-row{padding:10px 11px;border-radius:16px;background:rgba(255,255,255,.48);border:1px solid rgba(217,164,65,.13);color:#0d1b2a;text-decoration:none;font-size:.84rem}.history-row b,.history-row strong{color:#071b33}.history-row small{display:block;color:#516070}.history-row i{font-style:normal;color:#0b2748;font-weight:950}.request-list article{grid-template-columns:1fr auto}.request-list span{font-size:.86rem;font-weight:850}.credit-strip{display:grid;gap:4px;margin-top:10px;padding:10px;border-radius:16px;background:rgba(7,27,51,.94);color:#fff8ea}.credit-strip span{font-size:.86rem}.credit-strip small{color:#f9ead0}.credit-strip a{color:#f7c76b}.primary-action{margin-top:10px}@media(max-width:1280px){.summary-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.span-8,.span-4{grid-column:1/-1}.family-people-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:760px){.finance-main{margin-left:0;padding:18px 14px 30px}.finance-topbar,.section-heading{flex-direction:column;align-items:flex-start}.finance-actions{justify-content:flex-start}.summary-grid,.family-people-grid{grid-template-columns:1fr}.line-list a,.mini-list article{grid-template-columns:1fr;align-items:start}.history-head{display:none}.history-row{grid-template-columns:1fr;gap:5px}.finance-title h1{font-size:2.15rem}}

.finance-screen{background:radial-gradient(circle at 16% 8%,rgba(217,164,65,.18),transparent 26%),radial-gradient(circle at 86% 2%,rgba(245,158,11,.12),transparent 31%),linear-gradient(135deg,#fff8ea 0%,#f8efe0 52%,#fff4db 100%);color:#102033}.finance-main{max-width:1540px;padding:24px 26px 32px}.glass-card{border-color:rgba(255,255,255,.72);border-radius:24px;background:linear-gradient(135deg,rgba(255,255,255,.78),rgba(255,248,234,.48));box-shadow:0 18px 42px rgba(7,27,51,.075),inset 0 1px 0 rgba(255,255,255,.72)}.glass-card:before{background:radial-gradient(circle at 10% 0,rgba(255,255,255,.68),transparent 28%),linear-gradient(125deg,rgba(217,164,65,.11),transparent 44%)}.finance-topbar{min-height:118px;padding:20px 22px;border:1px solid rgba(217,164,65,.22);background:radial-gradient(circle at 7% 0,rgba(217,164,65,.16),transparent 30%),linear-gradient(135deg,rgba(255,255,255,.84),rgba(255,248,234,.58));box-shadow:0 18px 42px rgba(7,27,51,.08);color:#102033;backdrop-filter:blur(18px) saturate(145%)}.finance-topbar:before{background:radial-gradient(circle at 88% 10%,rgba(217,164,65,.14),transparent 28%),linear-gradient(115deg,transparent 0 48%,rgba(255,255,255,.35) 56%,transparent 66%)}.finance-title nav{color:#9b6c1d;font-size:.76rem;font-weight:950;letter-spacing:.08em;text-transform:uppercase}.finance-title nav a{color:#9b6c1d}.finance-title nav span{color:rgba(155,108,29,.5)}.finance-title h1{color:#071b33;font-weight:850}.finance-title p{color:#526172;font-size:.94rem}.finance-actions span{border-color:rgba(217,164,65,.22);background:rgba(255,255,255,.7);color:#526172}.finance-actions a,.primary-action{background:linear-gradient(135deg,#d9a441,#b98222);box-shadow:0 12px 24px rgba(185,130,34,.18);color:#071b33}.section-heading>a{box-shadow:0 10px 22px rgba(7,27,51,.12)}.summary-grid{gap:12px;margin-bottom:15px}.summary-card{grid-template-columns:auto 1fr;min-height:88px;padding:14px;border-color:rgba(217,164,65,.16)}.summary-card>span{width:40px;min-width:40px;height:40px;border:1px solid rgba(217,164,65,.2);border-radius:16px;background:rgba(255,250,239,.86);box-shadow:inset 0 1px 0 rgba(255,255,255,.8)}.summary-card strong{font-size:1.2rem;letter-spacing:-.03em}.finance-layout{gap:15px}.section-heading h2{font-size:1.08rem;letter-spacing:-.025em}.section-heading p{color:#6b7280;font-size:.84rem}.family-people-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.person-card{min-height:190px;padding:14px;border-color:rgba(217,164,65,.16);border-radius:20px;background:linear-gradient(135deg,rgba(255,255,255,.68),rgba(255,248,234,.38));box-shadow:0 12px 26px rgba(7,27,51,.055)}.avatar{width:42px;height:42px;border:2px solid rgba(217,164,65,.26);border-radius:16px;background:linear-gradient(135deg,#071b33,#0f3358)}.person-copy strong{color:#071b33;font-size:.96rem}.person-copy small,.person-card p{color:#6b7280}.person-copy em{font-size:.7rem;font-weight:950}.person-card mark{margin-top:3px;padding:7px 10px;font-size:.9rem}.person-card>a{margin-top:auto;background:rgba(217,164,65,.13)}.line-list a,.mini-list article,.receipt-list article,.request-list article,.history-row{background:linear-gradient(135deg,rgba(255,255,255,.62),rgba(255,248,234,.34));transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease}.history-row:hover,.request-list article:hover{transform:translateY(-1px);border-color:rgba(217,164,65,.34);box-shadow:0 16px 30px rgba(7,27,51,.085)}.credit-strip{padding:11px;border-radius:17px;background:radial-gradient(circle at 86% 0,rgba(217,164,65,.26),transparent 34%),linear-gradient(135deg,#071b33,#0f3358)}@media(max-width:1280px){.family-people-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:900px){.family-people-grid{grid-template-columns:1fr}}@media(max-width:760px){.finance-main{padding:18px 14px 30px}.finance-topbar{min-height:auto}.family-people-grid{grid-template-columns:1fr}}
.credit-strip {
    background:
        radial-gradient(circle at 15% 10%, rgba(255, 255, 255, 0.14), transparent 28%),
        radial-gradient(circle at 85% 0%, rgba(217, 164, 65, 0.18), transparent 32%),
        linear-gradient(135deg, rgba(7, 27, 51, 0.96), rgba(11, 39, 72, 0.90)) !important;
    border: 1px solid rgba(217, 164, 65, 0.28);
    color: rgba(255, 255, 255, 0.96) !important;
}

.credit-strip span,
.credit-strip b {
    color: rgba(255, 255, 255, 0.96) !important;
}

.credit-strip small {
    color: rgba(255, 255, 255, 0.82) !important;
}

.credit-strip a {
    color: #f6c85f !important;
}
</style>
