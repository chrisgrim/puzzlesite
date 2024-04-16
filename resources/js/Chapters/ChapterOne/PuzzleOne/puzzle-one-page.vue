<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">
        <div class="flex items-center mb-20">
            <a href="/" class="uppercase">< Back to story</a>
        </div>

        <div id="Header" class="my-10 mb-40">
            <h2 class="text-9xl">Morning Coffee</h2>
            <div class="flex flex-row mt-14 gap-4">
                <div class="bg-red-200 w-10 h-10 rounded-full shadow-inner-shadow"></div>
                <div class="bg-red-200 w-10 h-10 rounded-full shadow-inner-shadow"></div>
                <div class="bg-white w-10 h-10 rounded-full shadow-inner-shadow"></div>
            </div>
            <p class="mt-14">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim <span class="selection:text-fuchsia-900 selection:bg-fuchsia-300">tedz</span>id est laborum.</p>
        </div>

        <div id="Puzzle" class="max-w-[50rem] m-auto">
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

        <div id="Solution" class="mt-40 p-8">
            <div class="border-r border">
                <div>
                    <p>What did ALbert get</p>
                </div>
                <div class="flex flex-row">
                    <input type="text" name="Solutin" class="mt-12 bg-white"><button>Guess</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Modified words initialization to include ids
const words = ref([
    { id: 1, word: 'Apple' }, { id: 2, word: 'Book' }, { id: 3, word: 'Cat' }, { id: 4, word: 'Dog' },
    { id: 5, word: 'Elephant' }, { id: 6, word: 'Flower' }, { id: 7, word: 'Guitar' }, { id: 8, word: 'Hat' },
    { id: 9, word: 'Island' }, { id: 10, word: 'Juice' }, { id: 11, word: 'Kite' }, { id: 12, word: 'Lion' },
    { id: 13, word: 'Moon' }, { id: 14, word: 'Notebook' }, { id: 15, word: 'Orange' }, { id: 16, word: 'Piano' }
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
