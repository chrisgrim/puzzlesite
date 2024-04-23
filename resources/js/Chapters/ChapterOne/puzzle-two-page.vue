<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <div class="flex items-center mb-20">
            <a href="/" class="uppercase">< Back to story</a>
        </div>

        <div id="Header" class="my-10 mb-40">
            <h2 class="text-9xl"> {{props.puzzle.title}} </h2>
            <div class="flex flex-row mt-14 gap-4">
                <div v-if="props.puzzle.difficulty > 0" 
                     v-for="index in props.puzzle.difficulty"
                     :key="index"
                     class="bg-red-500 w-10 h-10 rounded-full shadow-inner-shadow"
                ></div>
                <div v-for="index in 3 - props.puzzle.difficulty" 
                     :key="index"
                     class="bg-white w-10 h-10 rounded-full shadow-inner-shadow"
                ></div>
            </div>
            <div class="mt-14">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>

        <div class="my-40">
            <div class="relative mx-auto touch-none place-content-center w-full grid">
                <svg ref="svgElement" class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
                    <!-- Paths for Correct Answers -->
                    <path v-for="(path, index) in correctAnswerPaths" :key="'correct-path-' + index"
                          :d="path.d" stroke-linecap="round" stroke-width="12"
                          :stroke="path.color" fill="none"/>
                    <!-- Current Selection Path -->
                    <path v-show="selectedIndices.length" :d="svgPath" stroke-linecap="round"
                          stroke-width="12" stroke="#3B82F6" fill="none"/>
                </svg>
                <div class="grid grid-cols-6 gap-4 w-auto h-auto z-10 relative">
                    <div v-for="letterObj in letters" :key="letterObj.id" :data-id="letterObj.id"
                         class="w-14 h-14 flex justify-center items-center cursor-pointer select-none rounded-full text-4xl border-0"
                         :class="getClass(letterObj.id)"
                         @click="selectLetter(letterObj, $event)">
                        {{ letterObj.letter }}
                    </div>
                </div>
            </div>
            <div class="mt-4">
                Selected Letters: {{ selectedLetters.join('') }}
            </div>
        </div>


        <div class="relative h-[30rem]">
            <div class="border border-black absolute bg-stone-200 top-0 bottom-0 left-0 right-0">
                <div class="h-full bg-stone-200 flex flex-col justify-center p-4">
                    <p>What was hidden in the shapes?</p>
                    <div class="flex flex-row mt-4">
                        <input @input="clear" v-model="guess" type="text" name="Solution" class="bg-white rounded-md px-4 py-2 mr-2">
                        <button @click="submitGuess" class="bg-blue-500 text-white px-4 py-2 rounded-md">Guess</button>
                        <p v-if="message">{{ message }}</p>
                    </div>
                    <div v-if="userSolution && userSolution.guesses.length">
                        <p>Previous Guesses:</p>
                        <ul>
                            <!-- Reverse the order of guesses array and loop through -->
                            <li v-for="(g, index) in userSolution.guesses.slice().reverse()" :key="index">
                                {{ g }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, toRefs} from 'vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

let guess = ref('');
let message = ref('');
let userSolution = ref(props.solution);

if (props.solution && props.solution.solved) {
    guess.value = props.puzzle.solution;
}

async function submitGuess() {
    if (!guess.value) {
        message.value = 'Please enter a guess.';
        return;
    }

    message.value = 'Submitting...';
    
    try {
        const response = await axios.post(`/api/puzzles/${props.chapter.id}/${props.puzzle.order}/guess`, {
            guess: guess.value
        });

        const data = response.data;
        message.value = data.message;
        userSolution.value = data.solution;

    } catch (error) {
        message.value = `Error: ${error.response ? error.response.data.message : error.message}`;
    }
}

function clear() {
    message.value = ''
}

const puzzleLayout = [
    'X', 'E', 'C', 'H', 'R', 'F',
    'A', 'M', 'Y', 'S', 'I', 'L',
    'L', 'P', 'C', 'P', 'Q', 'R',
    'E', 'L', 'U', 'V', 'W', 'X',
    'Y', 'G', 'L', 'B', 'C', 'D',
    'U', 'Y', 'G', 'H', 'I', 'J',
    'K', 'L', 'M', 'N', 'O', 'P',
    'Q', 'R', 'S', 'T', 'U', 'V'
];


const letters = puzzleLayout.map((letter, index) => ({
    id: index + 1,
    letter
}));

const correctAnswers = ['EXAMPLE', 'LUCY', 'CHRIS', 'UGLY'];
const selectedLetters = ref([]);
const selectedIndices = ref([]);
const selectedIds = ref([]);
const svgElement = ref(null);
const svgStrokeColor = '#3B82F6'; 
const lastSelectedId = ref(null);
const correctSelections = ref(new Set());
const correctAnswerPaths = ref([]); // Stores positions of correctly answered letters


const getClass = (id) => {
    return {
        // 'ring-gap' added for the gap effect around selected letters, including the last selected one
        'bg-blue-500 text-white': selectedIds.value.includes(id),
        // Special style for the last selected letter
        'ring-gap !border-2 border-blue-500': id === lastSelectedId.value,
        // Apply green background for correct selections
        'cursor-default bg-green-500': correctSelections.value.has(id),
    };
};

const calculateNewPositions = () => {
    return selectedIds.value.map(id => {
        const letterElement = document.querySelector(`[data-id="${id}"]`);
        if (letterElement) {
            const rect = letterElement.getBoundingClientRect();
            const svgRect = svgElement.value.getBoundingClientRect();
            return { id, x: rect.left + rect.width / 2 - svgRect.left + window.scrollX, y: rect.top + rect.height / 2 - svgRect.top + window.scrollY };
        }
        return null;
    }).filter(pos => pos !== null);
};

const updatePositions = () => {
    const newPositions = calculateNewPositions();
    selectedIndices.value = newPositions;
};



const isAdjacent = (currentId, lastId) => {
    const currentRow = Math.floor((currentId - 1) / 6);
    const currentCol = (currentId - 1) % 6;
    const lastRow = Math.floor((lastId - 1) / 6);
    const lastCol = (lastId - 1) % 6;
    return Math.abs(currentRow - lastRow) <= 1 && Math.abs(currentCol - lastCol) <= 1;
};

const selectLetter = async (letterObj, event) => {
    if (correctSelections.value.has(letterObj.id)) {
        return; // This prevents further processing of the click
    }
    const isSelectedIndex = selectedIds.value.indexOf(letterObj.id);
    const lastId = selectedIds.value.at(-1);

    // Start a new selection if clicked letter is not adjacent to the last selected letter
    if (lastId !== null && isSelectedIndex === -1 && !isAdjacent(letterObj.id, lastId)) {
        resetSelections();
    }

    // If the clicked letter is already selected
    if (isSelectedIndex !== -1) {
        // If it's the last selected letter
        if (isSelectedIndex === selectedIds.value.length - 1) {
            // If more than one letter is selected, check for submission, otherwise deselect it
            if (selectedLetters.value.length > 1) {
                checkForSubmission();
                return;
            } else {
                // Deselect the last selected letter
                selectedLetters.value.pop();
                selectedIds.value.pop();
                selectedIndices.value.pop();
                lastSelectedId.value = selectedIds.value.at(-1) || null;
                updatePositions();
                return;
            }
        } else {
            // Deselect it and any letters selected after it
            selectedLetters.value = selectedLetters.value.slice(0, isSelectedIndex + 1);
            selectedIds.value = selectedIds.value.slice(0, isSelectedIndex + 1);
            selectedIndices.value = selectedIndices.value.slice(0, isSelectedIndex + 1);
            lastSelectedId.value = letterObj.id;
            updatePositions();
            return;
        }
    }

    // For a new selection
    if (isSelectedIndex === -1) {
        selectedLetters.value.push(letterObj.letter);
        selectedIds.value.push(letterObj.id);
        lastSelectedId.value = letterObj.id;
        await nextTick();
        updateSvgPosition(letterObj, event);
    }
};

const checkForSubmission = () => {
    const selectedString = selectedLetters.value.join('');
    if (correctAnswers.includes(selectedString)) {
        // Add IDs of selected letters to correctSelections for highlighting them
        selectedIds.value.forEach(id => correctSelections.value.add(id));

        // Prepare the SVG path data and color for the correct answer
        const pathData = selectedIndices.value.reduce((acc, curr, index) => `${acc} ${index === 0 ? 'M' : 'L'} ${curr.x},${curr.y}`, '').trim();
        correctAnswerPaths.value.push({ d: pathData, color: '#10B981' }); // Use green color for correct paths
        
        console.log('Correct answer:', selectedString);
        resetSelections();
    } else {
        console.log('Incorrect answer:', selectedString);
        resetSelections();
    }
};

const updateSvgPosition = (letterObj, event) => {
    // Add the SVG position updating logic here
    if (svgElement.value) {
        const { left, top, width, height } = event.target.getBoundingClientRect();
        const { left: svgLeft, top: svgTop } = svgElement.value.getBoundingClientRect();
        selectedIndices.value.push({ id: letterObj.id, x: left + width / 2 - svgLeft + window.scrollX, y: top + height / 2 - svgTop + window.scrollY });
    }
    updatePositions();
};

// Remember to update the resetSelections method to reset lastSelectedId as well
const resetSelections = () => {
    selectedLetters.value = [];
    selectedIndices.value = [];
    selectedIds.value = [];
    lastSelectedId.value = null;
};

const svgPath = computed(() => {
    return selectedIndices.value.reduce((acc, curr, index) => `${acc} ${index === 0 ? 'M' : 'L'} ${curr.x},${curr.y}`, '').trim();
});


onMounted(() => {
    window.addEventListener('resize', updatePositions);
    nextTick(updatePositions);
});

onUnmounted(() => {
    window.removeEventListener('resize', updatePositions);
});
</script>

<style>
.ring-gap {
    box-shadow: 0 0 0 2px #3B82F6;
    border: 2px solid white !important;
}
</style>

