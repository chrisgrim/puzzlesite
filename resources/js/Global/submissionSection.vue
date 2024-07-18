<template>
    <div class="relative h-[30rem]">
        <div class="border border-black absolute bg-stone-200 top-0 bottom-0 left-0 right-0">
            <div class="h-full bg-stone-200 flex flex-col justify-center p-4">
                <p>{{ question }}</p>
                <div class="flex flex-row mt-4">
                    <input @input="clearMessage" v-model="guess" type="text" name="Solution" class="bg-white rounded-md px-4 py-2 mr-2">
                    <button @click="submitGuess" class="bg-blue-500 text-white px-4 py-2 rounded-md">Guess</button>
                    <p v-if="message">{{ message }}</p>
                </div>
                <div v-if="solution && solution.guesses.length">
                    <p>Previous Guesses:</p>
                    <ul>
                        <li v-for="(g, index) in solution.guesses.slice().reverse()" :key="index">
                            {{ g }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { defineProps } from 'vue';
import axios from 'axios';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
    solution: {
        type: Object,
        required: true,
    },
    chapterId: {
        type: Number,
        required: true,
    },
    puzzleOrder: {
        type: Number,
        required: true,
    }
});

let guess = ref('');
let message = ref('');
let solution = ref(props.solution ? { ...props.solution } : { guesses: [] });

// Watch for changes in props.solution
watch(() => props.solution, (newSolution) => {
    solution.value = newSolution ? { ...newSolution } : { guesses: [] };
}, { immediate: true });

async function submitGuess() {
    if (!guess.value) {
        message.value = 'Please enter a guess.';
        return;
    }
    message.value = 'Submitting...';
    try {
        const response = await axios.post(`/api/puzzles/${props.chapterId}/${props.puzzleOrder}/guess`, {
            guess: guess.value
        });
        const data = response.data;
        guess.value = '';
        message.value = data.message;
        if (data.solution) {
            solution.value = { ...solution.value, ...data.solution };
        }
    } catch (error) {
        message.value = `Error: ${error.response ? error.response.data.message : error.message}`;
    }
}

function clearMessage() {
    message.value = '';
}
</script>