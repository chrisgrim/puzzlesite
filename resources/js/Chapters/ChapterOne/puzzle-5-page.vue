<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Sectioin -->
        <Header :puzzle="props.puzzle" />

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle flex flex-col items-center justify-center">
            <div class="grid grid-cols-4 gap-4">
                <div v-for="wordObj in words" :key="wordObj.id" 
                    :class="getClass(wordObj.id)"
                    @click="selectWord(wordObj.id)">
                    {{ wordObj.word }}
                </div>
            </div>
            <div class="mt-4 flex justify-between">
                <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700" 
                        @click="checkSelection"
                        :disabled="selectedWords.length !== 4">
                    Check Words
                </button>
                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"
                        @click="shuffleWords">
                    Shuffle
                </button>
            </div>
        </div>

        <SubmissionSection 
            :question="'What was hidden in the shapes?'"
            :solution="props.solution" 
            :chapter-id="props.chapter.id"
            :puzzle-order="props.puzzle.order"
        />

    </div>
</template>

<script setup>
import { ref } from 'vue';
import Header from '@/Global/header.vue';
import SubmissionSection from '@/Global/submissionSection.vue';

// Modified words initialization to include ids
const words = ref([
    { id: 1, word: 'Apple' }, { id: 2, word: 'Book' }, { id: 3, word: 'Cat' }, { id: 4, word: 'Dog' },
    { id: 5, word: 'Elephant' }, { id: 6, word: 'Flower' }, { id: 7, word: 'Guitar' }, { id: 8, word: 'Hat' },
    { id: 9, word: 'Island' }, { id: 10, word: 'Juice' }, { id: 11, word: 'Kite' }, { id: 12, word: 'Lion' },
    { id: 13, word: 'Moon' }, { id: 14, word: 'Notebook' }, { id: 15, word: 'Orange' }, { id: 16, word: 'Piano' }
]);

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

// State to track selected word ids
const selectedWords = ref([]);

// Function to handle word selection based on id
const selectWord = (id) => {
    const selectedIndex = selectedWords.value.indexOf(id);
    if (selectedIndex === -1) {
        if (selectedWords.value.length < 4) {
            selectedWords.value.push(id);
        }
    } else {
        selectedWords.value.splice(selectedIndex, 1);
    }
};

const checkSelection = () => {
    // Example validation logic
    const isCorrect = selectedWords.value.length === 4;
    if (isCorrect) {
        alert('Correct selection!');
        selectedWords.value = [];
    } else {
        alert('Incorrect selection, please try again.');
    }
};

const getClass = (id) => ({
    'flex justify-center items-center h-24 text-center border border-stone-400 cursor-pointer h-40': true,
    'bg-stone-500 text-white': selectedWords.value.includes(id),
    '': !selectedWords.value.includes(id),
});

// Function to shuffle the words
const shuffleWords = () => {
    for (let i = words.value.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [words.value[i], words.value[j]] = [words.value[j], words.value[i]];
    }
};

shuffleWords();
</script>
