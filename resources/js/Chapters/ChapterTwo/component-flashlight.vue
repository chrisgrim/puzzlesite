<template>
  <div class="container" :class="{ 'dark-mode': isDarkMode }" @mousemove="updateMousePosition">
    <label class="switch">
      <input type="checkbox" v-model="isDarkMode">
      <span class="slider"></span>
    </label>
    <div class="circle circle1" :style="circleStyle1"></div>
    <div class="circle circle2" :style="circleStyle2"></div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const isDarkMode = ref(false);
const mouseX = ref(0);
const mouseY = ref(0);

const updateMousePosition = (event) => {
  mouseX.value = event.clientX;
  mouseY.value = event.clientY;
};

const calculateCircleStyle = (circleX, circleY) => {
  const angle = Math.atan2(mouseY.value - circleY, mouseX.value - circleX);
  const distance = Math.hypot(mouseX.value - circleX, mouseY.value - circleY);
  const shadowLength = Math.min(50, distance / 5); // Increased maximum length to 50px

  const shadowX = Math.cos(angle) * shadowLength;
  const shadowY = Math.sin(angle) * shadowLength;

  return {
    boxShadow: isDarkMode.value
      ? `${-shadowX}px ${-shadowY}px 20px 0 rgba(255, 255, 255, 0.3)`
      : 'none'
  };
};

const circleStyle1 = computed(() => calculateCircleStyle(window.innerWidth * 0.3 + 50, window.innerHeight * 0.3 + 50));
const circleStyle2 = computed(() => calculateCircleStyle(window.innerWidth * 0.7 - 50, window.innerHeight * 0.7 - 50));

</script>

<style scoped>
.container {
  position: relative;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  transition: background-color 0.3s ease;
}

.dark-mode {
  background-color: #1a1a1a;
}

.switch {
  position: absolute;
  top: 20px;
  left: 20px;
  width: 60px;
  height: 34px;
  z-index: 10;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.circle {
  position: absolute;
  width: 100px;
  height: 100px;
  background-color: #3498db;
  border-radius: 50%; /* This makes the div circular */
  transition: box-shadow 0.1s ease;
}

.circle1 {
  top: 30%;
  left: 30%;
}

.circle2 {
  bottom: 30%;
  right: 30%;
}
</style>