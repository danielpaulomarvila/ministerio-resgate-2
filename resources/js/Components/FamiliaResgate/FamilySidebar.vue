<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    activeHref: { type: String, default: '/familia-resgate' },
});

const menu = [
    ['home', 'Central da Família', '/familia-resgate'],
    ['user', 'Meu Perfil', '/familia-resgate/meu-perfil'],
    ['wallet', 'Meu Financeiro', '/familia-resgate/meu-financeiro'],
    ['trophy', 'Minha Caminhada', '/familia-resgate/minha-caminhada'],
    ['book', 'Centro da Sabedoria', '/familia-resgate/centro-sabedoria'],
    ['link', 'Rede de Fé', '/familia-resgate/rede-fe'],
    ['users', 'Minha Família', '/familia-resgate/minha-familia'],
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
        crown: ['M3 7l4 4 5-7 5 7 4-4-2 12H5L3 7Z', 'M7 19h10'],
    };

    return paths[icon] ?? paths.diamond;
}
</script>

<template>
    <aside class="family-sidebar" aria-label="Menu do Centro da Família Resgate">
        <Link href="/familia-resgate" class="sidebar-logo">
            <img src="/images/brand/logo-resgate-gold.png" alt="Família Resgate" />
            <span>Família Resgate<small>Casa digital</small></span>
        </Link>

        <nav>
            <Link v-for="item in menu" :key="item[2]" :href="item[2]" class="sidebar-link" :class="{ active: item[2] === props.activeHref }">
                <b><svg viewBox="0 0 24 24" aria-hidden="true"><path v-for="path in iconPath(item[0])" :key="path" :d="path" /></svg></b>
                <span>{{ item[1] }}</span>
            </Link>
        </nav>
    </aside>
</template>

<style scoped>
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
.sidebar-link b svg{width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;filter:drop-shadow(0 2px 6px rgba(217,164,65,.22))}
.sidebar-link:hover,.sidebar-link.active{color:#fff8ea;background:rgba(255,255,255,.09);border-color:rgba(247,199,107,.28)}
.sidebar-link:hover b,.sidebar-link.active b{background:rgba(217,164,65,.16);color:#ffd777}
@media(max-width:760px){.family-sidebar{display:none}}
</style>
