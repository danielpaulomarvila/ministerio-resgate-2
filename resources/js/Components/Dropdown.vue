<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'ltr:origin-top-left rtl:origin-top-right left-0';
    } else if (props.align === 'right') {
        return 'ltr:origin-top-right rtl:origin-top-left right-0';
    } else {
        return 'origin-top left-1/2 -translate-x-1/2';
    }
});

const open = ref(false);
const dropdownPosition = ref({ top: 0, left: 0 });

const toggleDropdown = () => {
    if (!open.value) {
        const trigger = document.querySelector('[data-dropdown-trigger]');
        if (trigger) {
            const rect = trigger.getBoundingClientRect();
            dropdownPosition.value = {
                top: rect.bottom + window.scrollY + 4,
                left: props.align === 'right' ? rect.right - 192 : rect.left
            };
        }
    }
    open.value = !open.value;
};
</script>

<template>
    <div class="relative">
        <div @click="toggleDropdown">
            <div data-dropdown-trigger>
                <slot name="trigger" />
            </div>
        </div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                class="fixed z-[100] rounded-md shadow-lg overflow-visible"
                :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }"
                :class="[widthClass]"
                @click="open = false"
            >
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5"
                    :class="contentClasses"
                >
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
