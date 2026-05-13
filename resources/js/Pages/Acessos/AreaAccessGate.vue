<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: { type: String, default: 'Acesso privado' },
    description: { type: String, default: 'Estamos preparando sua entrada com segurança. Em breve esta área será conectada às permissões reais.' },
    icon: { type: String, default: '▥' },
});
</script>

<template>
    <Head :title="title" />

    <main class="gate-screen">
        <!-- Tela de transição para áreas privadas; preserva autenticação e prepara futuras regras de permissão. -->
        <section class="gate-card">
            <span>{{ icon }}</span>
            <p>Acesso Privado</p>
            <h1>{{ title }}</h1>
            <strong>{{ description }}</strong>
            <Link href="/familia-resgate">Retornar ao Centro da Família</Link>
        </section>
    </main>
</template>

<style scoped>
.gate-screen{min-height:100vh;display:grid;place-items:center;padding:24px;background:linear-gradient(135deg,#061426,#071b33 58%,#1b3b61);color:#fff8ea}.gate-card{width:min(560px,100%);padding:36px;text-align:center;border-radius:30px;border:1px solid rgba(217,164,65,.36);background:rgba(255,255,255,.09);box-shadow:0 30px 80px rgba(0,0,0,.32);backdrop-filter:blur(18px)}.gate-card span{display:grid;place-items:center;width:78px;height:78px;margin:0 auto 18px;border-radius:28px;background:rgba(247,199,107,.16);color:#f7c76b;font-size:2.3rem}.gate-card p{margin:0;color:#f7c76b;text-transform:uppercase;letter-spacing:.18em;font-size:.72rem}.gate-card h1{margin:10px 0 12px;font:700 2.3rem/1.05 Georgia,serif}.gate-card strong{display:block;color:#f9ead0;font-weight:500;line-height:1.7}.gate-card a{display:inline-flex;margin-top:26px;padding:12px 18px;border-radius:999px;background:#f7c76b;color:#071b33;text-decoration:none;font-weight:900}
</style>
