<template>
    <div @mousemove="handleMouseMove">
        <div class="relative flex justify-center items-center h-[calc(90vh-10rem)]">
            <a href="/the-story" class="absolute z-50">
                <h2 ref="textElement" class="text-center">THE OVERLAP</h2>
            </a>
            <div :style="firstCircleStyle" class="absolute w-[60rem] h-[60rem] border-2 border-black rounded-full"></div>
            <div :style="secondCircleStyle" class="absolute w-[60rem] h-[60rem] bg-black bg-opacity-30 rounded-full blur-md"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from 'vue';

const textElement = ref(null);
const firstCircleStyle = reactive({ left: '50%', transform: 'translateX(-50%)' });
const secondCircleStyle = reactive({ left: '50%', transform: 'translateX(-50%)' });

function handleMouseMove(event) {
    if (textElement.value) {
        const textRect = textElement.value.getBoundingClientRect();
        const mouseX = event.clientX;
        const mouseY = event.clientY;
        const textCenterX = textRect.left + textRect.width / 2;
        const textCenterY = textRect.top + textRect.height / 2;

        // Calculate the Euclidean distance from the center of the text
        const distance = Math.sqrt(Math.pow(mouseX - textCenterX, 2) + Math.pow(mouseY - textCenterY, 2));
        const maxDistance = window.innerWidth / 2; // or another appropriate measure
        let overlapFactor = Math.max(0, Math.min(1, 1 - distance / maxDistance));

        // Calculate dynamic overlap percentages for the circle styles
        const maxOffset = 50; // Maximum offset in percentage of the element's width
        const offset = maxOffset * (1 - overlapFactor); // Dynamic offset from center

        firstCircleStyle.transform = `translateX(${offset - 50}%)`;
        secondCircleStyle.transform = `translateX(${-offset - 50}%)`;
    }
}

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<style>
/* Ensure any additional styling is correct */
</style>
