<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: { type: String, required: true },
    label: { type: String, required: true },
    badge: { type: [String, Number], default: null },
});
</script>

<template>
    <Link :href="href" class="premium-icon-button" :aria-label="label">
        <slot />
        <span v-if="badge !== null" class="premium-button-badge">{{ badge }}</span>
    </Link>
</template>

<style scoped>
.premium-icon-button{position:relative;display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;min-width:40px;min-height:40px;padding:0;overflow:visible;border-radius:9999px;background:linear-gradient(145deg,rgba(255,248,234,.92) 0%,rgba(247,239,225,.74) 48%,rgba(238,223,197,.58) 100%);border:1px solid rgba(255,255,255,.78);backdrop-filter:blur(16px) saturate(145%);box-shadow:0 8px 22px rgba(7,27,51,.08),inset 0 1px 0 rgba(255,255,255,.78),inset 0 -1px 0 rgba(217,164,65,.12);color:#071b33;text-decoration:none;transition:all 180ms ease}.premium-icon-button:before{content:"";position:absolute;inset:1px;border-radius:inherit;background:radial-gradient(circle at 30% 20%,rgba(255,255,255,.88),transparent 32%),radial-gradient(circle at 82% 86%,rgba(217,164,65,.22),transparent 38%);opacity:.72;pointer-events:none}.premium-icon-button:hover{transform:translateY(-1px) scale(1.02);background:linear-gradient(145deg,rgba(255,248,234,.98) 0%,rgba(247,239,225,.84) 48%,rgba(247,199,107,.24) 100%);border-color:rgba(217,164,65,.55);box-shadow:0 12px 30px rgba(7,27,51,.13),0 0 0 3px rgba(217,164,65,.10),inset 0 1px 0 rgba(255,255,255,.85)}.premium-icon-button:active{transform:translateY(0) scale(.98);box-shadow:0 6px 16px rgba(7,27,51,.10),inset 0 2px 6px rgba(7,27,51,.10)}.premium-icon-button :deep(svg){position:relative;z-index:1;width:16px;height:16px;fill:none;stroke:#071b33;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}.premium-button-badge{position:absolute;z-index:2;top:-4px;right:-3px;display:inline-flex;align-items:center;justify-content:center;min-width:16px;height:16px;padding:0 4px;border-radius:9999px;background:linear-gradient(135deg,#f7c76b 0%,#d9a441 100%);color:#071b33;font-size:9px;font-weight:800;border:2px solid #fff8ea;box-shadow:0 5px 12px rgba(7,27,51,.14),inset 0 1px 0 rgba(255,255,255,.55);line-height:1;pointer-events:none}@media(max-height:760px){.premium-icon-button{width:38px;height:38px;min-width:38px;min-height:38px}.premium-icon-button :deep(svg){width:16px;height:16px}.premium-button-badge{min-width:16px;height:16px;font-size:9px}}
</style>
