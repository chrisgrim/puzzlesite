<template>
  <div class="relative flex justify-center items-center w-screen overflow-hidden" 
       :style="{ height: 'calc(100vh - 8rem)' }" 
       @mousemove="updateCirclePosition">
    <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid meet">
      <defs>
        <clipPath id="circle-clip">
          <circle :cx="circlePosition.x" :cy="circlePosition.y" :r="circleRadius" />
        </clipPath>
      </defs>
      
      <text x="50" y="50" text-anchor="middle" dominant-baseline="central"
            class="text-[2vw] md:text-[2vw] uppercase h2">OVERLAP</text>
      
      <text x="50" y="50" text-anchor="middle" dominant-baseline="central"
            class="text-[2vw] md:text-[2vw] uppercase h2 fill-white"
            clip-path="url(#circle-clip)">OVERLAP</text>
      
      <circle :cx="circlePosition.x" :cy="circlePosition.y" :r="circleRadius"
              fill="none" stroke="black" stroke-width="0.1" />
    </svg>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const circlePosition = ref({ x: 50, y: 50 });
const circleRadius = ref(30);

const updateCirclePosition = (event) => {
  const svgRect = event.currentTarget.getBoundingClientRect();
  circlePosition.value = {
    x: ((event.clientX - svgRect.left) / svgRect.width) * 100,
    y: ((event.clientY - svgRect.top) / svgRect.height) * 100
  };
};

onMounted(() => {
  window.addEventListener('mousemove', updateCirclePosition);
});

onUnmounted(() => {
  window.removeEventListener('mousemove', updateCirclePosition);
});
</script>

<style scoped>
.h2 {
  font-family: inherit;
}
</style>