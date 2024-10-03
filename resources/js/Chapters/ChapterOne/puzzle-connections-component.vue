<template>
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
</template>

<script setup>
import { ref } from 'vue';

// State to hold words
const words = ref([
    { id: 1, word: 'Apple', group: 1 }, { id: 2, word: 'Book', group: 2 }, { id: 3, word: 'Cat', group: 3 }, { id: 4, word: 'Dog', group: 3 },
    { id: 5, word: 'Elephant', group: 4 }, { id: 6, word: 'Flower', group: 1 }, { id: 7, word: 'Guitar', group: 2 }, { id: 8, word: 'Hat', group: 4 },
    { id: 9, word: 'Island', group: 1 }, { id: 10, word: 'Juice', group: 1 }, { id: 11, word: 'Kite', group: 4 }, { id: 12, word: 'Lion', group: 3 },
    { id: 13, word: 'Moon', group: 4 }, { id: 14, word: 'Notebook', group: 2 }, { id: 15, word: 'Orange', group: 3 }, { id: 16, word: 'Piano', group: 2 }
]);

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

// Function to check the word selection
const checkSelection = () => {
    if (selectedWords.value.length !== 4) {
        alert('Please select exactly 4 words.');
        return;
    }

    const selectedWordObjects = selectedWords.value.map(id => 
        words.value.find(word => word.id === id)
    );

    const firstGroup = selectedWordObjects[0].group;
    const isCorrect = selectedWordObjects.every(word => word.group === firstGroup);

    if (isCorrect) {
        alert('Correct selection! All words belong to the same group.');
        selectedWords.value = [];
    } else {
        alert("Incorrect selection. These words don't belong to the same group. Please try again.");
    }
};

// Function to add classes based on word selection
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

// Shuffle words on load
shuffleWords();
</script>
