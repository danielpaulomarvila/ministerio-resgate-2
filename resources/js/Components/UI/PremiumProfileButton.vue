<script setup>
defineProps({
    initial: { type: String, default: 'D' },
    name: { type: String, default: 'Daniel' },
});

const emit = defineEmits(['toggle']);
</script>

<template>
    <button type="button" class="premium-profile-button" @click="emit('toggle')">
        <span class="premium-profile-avatar">{{ initial }}</span>
        <span class="premium-profile-name">{{ name }}</span>
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </button>
</template>

<style scoped>
.premium-profile-button{position:relative;height:40px;min-height:40px;padding:3px 10px 3px 3px;border-radius:9999px;display:inline-flex;align-items:center;gap:10px;overflow:visible;background:linear-gradient(145deg,rgba(255,248,234,.94) 0%,rgba(247,239,225,.78) 58%,rgba(238,223,197,.58) 100%);border:1px solid rgba(255,255,255,.78);backdrop-filter:blur(16px) saturate(145%);box-shadow:0 8px 22px rgba(7,27,51,.08),inset 0 1px 0 rgba(255,255,255,.78);color:#071b33;transition:all 180ms ease;cursor:pointer}.premium-profile-button:before{content:"";position:absolute;inset:1px;border-radius:inherit;background:radial-gradient(circle at 28% 20%,rgba(255,255,255,.86),transparent 34%),radial-gradient(circle at 92% 92%,rgba(217,164,65,.18),transparent 38%);opacity:.72;pointer-events:none}.premium-profile-button:hover{transform:translateY(-1px);background:linear-gradient(145deg,rgba(255,248,234,.98) 0%,rgba(247,239,225,.84) 58%,rgba(247,199,107,.24) 100%);border-color:rgba(217,164,65,.55);box-shadow:0 12px 30px rgba(7,27,51,.13),0 0 0 3px rgba(217,164,65,.10),inset 0 1px 0 rgba(255,255,255,.85)}.premium-profile-button:active{transform:translateY(0) scale(.99);box-shadow:0 6px 16px rgba(7,27,51,.10),inset 0 2px 6px rgba(7,27,51,.10)}.premium-profile-avatar{position:relative;z-index:1;width:34px;height:34px;border-radius:9999px;border:2px solid #d9a441;background:linear-gradient(135deg,#071b33 0%,#0b2748 100%);color:#f7c76b;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;line-height:1;box-shadow:0 4px 12px rgba(7,27,51,.16),inset 0 1px 0 rgba(255,255,255,.16)}.premium-profile-name{position:relative;z-index:1;font-size:15px;font-weight:700;color:#071b33;line-height:1;letter-spacing:-.02em}.premium-profile-button svg{position:relative;z-index:1;width:13px;height:13px;fill:none;stroke:#071b33;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}@media(max-height:760px){.premium-profile-button{height:38px;min-height:38px;padding:2px 9px 2px 2px;gap:9px}.premium-profile-avatar{width:32px;height:32px;font-size:14px}.premium-profile-name{font-size:14px}}
</style>
