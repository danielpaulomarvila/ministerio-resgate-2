<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: { type: String, default: 'Área em preparação' },
    description: { type: String, default: 'Esta funcionalidade já possui rota própria e será conectada aos dados reais em uma próxima etapa.' },
    icon: { type: String, default: '✦' },
});
</script>

<template>
    <Head :title="title" />

    <main class="placeholder-screen">
        <!-- Placeholder oficial para áreas ainda não implementadas, mantendo experiência premium e sem link morto. -->
        <section class="placeholder-card">
            <span>{{ icon }}</span>
            <p>Centro da Família Resgate</p>
            <h1>{{ title }}</h1>
            <strong>{{ description }}</strong>
            <div>
                <Link href="/familia-resgate">Voltar para a central</Link>
                <Link href="/familia-resgate/notificacoes">Ver notificações</Link>
            </div>
        </section>
    </main>
</template>

<style scoped>
.placeholder-screen{min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(circle at 20% 10%,rgba(217,164,65,.28),transparent 28%),linear-gradient(135deg,#071b33,#0b2748 52%,#fff8ea);color:#fff8ea}.placeholder-card{width:min(620px,100%);padding:38px;border:1px solid rgba(247,199,107,.35);border-radius:28px;background:rgba(255,255,255,.1);box-shadow:0 30px 90px rgba(0,0,0,.28);backdrop-filter:blur(18px);text-align:center}.placeholder-card span{display:grid;place-items:center;width:76px;height:76px;margin:0 auto 18px;border-radius:24px;background:rgba(217,164,65,.18);color:#f7c76b;font-size:2.2rem}.placeholder-card p{margin:0;color:#f7c76b;text-transform:uppercase;letter-spacing:.16em;font-size:.72rem}.placeholder-card h1{margin:10px 0 12px;font:700 2.4rem/1.05 Georgia,serif}.placeholder-card strong{display:block;color:#f9ead0;font-weight:500;line-height:1.7}.placeholder-card div{display:flex;justify-content:center;gap:12px;margin-top:28px;flex-wrap:wrap}.placeholder-card a{padding:12px 18px;border-radius:999px;background:#f7c76b;color:#071b33;text-decoration:none;font-weight:800}.placeholder-card a+a{background:rgba(255,255,255,.12);color:#fff8ea;border:1px solid rgba(255,255,255,.24)}
</style>
