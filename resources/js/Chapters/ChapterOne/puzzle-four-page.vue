<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <Header :puzzle="props.puzzle" />

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

        <SubmissionSection 
            :question="'What was the real meaning hidden?'"
            :solution="props.solution" 
            :chapter-id="props.chapter.id"
            :puzzle-order="props.puzzle.order"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import Header from '@/Global/header.vue';
import SubmissionSection from '@/Global/submissionSection.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

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
const lastSelectedId = ref(null);
const correctSelections = ref(new Set());
const correctAnswerPaths = ref([]); // Stores positions of correctly answered letters

const getClass = (id) => {
    return {
        'bg-blue-500 text-white': selectedIds.value.includes(id),
        'ring-gap !border-2 border-blue-500': id === lastSelectedId.value,
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
    if (svgElement.value) {
        const { left, top, width, height } = event.target.getBoundingClientRect();
        const { left: svgLeft, top: svgTop } = svgElement.value.getBoundingClientRect();
        selectedIndices.value.push({ id: letterObj.id, x: left + width / 2 - svgLeft + window.scrollX, y: top + height / 2 - svgTop + window.scrollY });
    }
    updatePositions();
};

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
