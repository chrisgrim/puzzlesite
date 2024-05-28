<template>
    <div class="flex flex-row mt-14 gap-4">
        <div v-if="difficulty > 0" 
             v-for="index in difficulty"
             :key="index"
             class="bg-black w-6 h-6 rounded-full"
        ></div>
        <div v-for="index in 3 - difficulty" 
             :key="index"
             class="bg-white w-6 h-6 rounded-full"
        ></div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    difficulty: {
        type: Number,
        required: true,
    }
});
</script>