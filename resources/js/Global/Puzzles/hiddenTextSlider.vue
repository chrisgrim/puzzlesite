<template>
    <div class="relative overflow-hidden" style="white-space: pre-wrap;" ref="container">
        <input 
            v-if="showHiddenArea"
            type="range" 
            min="0" 
            max="100" 
            v-model="sliderPosition"
            class="slider absolute top-0 h-full w-full opacity-0 cursor-ew-resize z-20"
        />
        <div 
            v-if="showHiddenArea"
            class="slider-handle absolute top-0 bottom-0 w-2 bg-blue-500 z-10"
            :style="{ left: `${sliderPosition}%` }"
        />
        <p class="original-text" :style="{ opacity: 1 - sliderPosition / 100 }">
            <slot name="original-text"></slot>
        </p>
        <div 
            class="hidden-area absolute top-0 left-0 right-0 bottom-0 bg-stone-200 shadow-inner-shadow"
            :style="{ clipPath: `inset(0 ${100 - sliderPosition}% 0 0)` }"
        >
            <slot name="hidden-text"></slot>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const showHiddenArea = ref(false);
const sliderPosition = ref(0);
const clickedWords = ref(new Set());

const emit = defineEmits(['shake']);

function handleTestWordClick(wordIndex) {
    clickedWords.value.add(wordIndex);
    if (clickedWords.value.size === 3) {
        showHiddenArea.value = true;
        sliderPosition.value = 5;
        emit('shake');
    } else {
        showHiddenArea.value = false;
        sliderPosition.value = 0;
    }
}

defineExpose({ handleTestWordClick });
</script>

<style scoped>
.slider {
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 10px;
    height: 100%;
    background: transparent;
    cursor: ew-resize;
}

.slider::-moz-range-thumb {
    width: 10px;
    height: 100%;
    background: transparent;
    cursor: ew-resize;
}

.slider-handle {
    pointer-events: none;
    transition: left 0.1s ease;
}

.original-text {
    transition: opacity 0.1s ease;
}

.hidden-area {
    transition: clip-path 0.1s ease;
}

.no-select {
    user-select: none;
}
</style>