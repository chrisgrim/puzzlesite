<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle flex flex-row items-center justify-center py-16">
            <div class="w-1/2">
                <div class="w-full border border-black rounded-3xl overflow-hidden flex flex-col">
                    <div class="bg-gray-700 text-white text-center p-2">Union Daily</div>
                    <div class="mt-4 flex flex-col p-4">
                        Correct Answer:
                        <div v-for="(answer, index) in correctAnswersList" :key="index">
                            {{ answer }}
                        </div>
                    </div>
                </div>
                <div class="mt-12">
                    <p class="m-0">July 8, 2024</p>
                    <p class="text-bold m-0"><b>By .... . .-.. .-..</b></p>
                </div>
            </div>


            <div class="relative mx-auto touch-none place-content-center w-1/2 grid">
                <p class="text-center h-10 mb-8">{{ selectedLetters.join('') }}</p>
                <svg ref="svgElement" class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
                    <!-- Paths for Correct Answers -->
                    <path v-for="(path, index) in correctAnswerPaths" :key="'correct-path-' + index"
                          :d="path.d" stroke-linecap="round" stroke-width="12"
                          :stroke="path.color" fill="none"/>
                    <!-- Current Selection Path -->
                    <path v-show="selectedIndices.length" :d="svgPath" stroke-linecap="round"
                          stroke-width="12" stroke="#3B82F6" fill="none"/>
                </svg>
                <div class="grid grid-cols-6 gap-4 w-auto h-auto z-10 relative"
                     @mousedown="startDrag"
                     @mousemove="dragSelect"
                     @mouseup="endDrag"
                     @mouseleave="endDrag">
                    <div v-for="letterObj in letters" :key="letterObj.id" :data-id="letterObj.id"
                         class="w-16 h-16 flex justify-center items-center cursor-pointer select-none rounded-full text-4xl border-0"
                         :class="getClass(letterObj.id)"
                         @click="selectLetter(letterObj, $event)">
                        {{ letterObj.letter }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Section -->
        <SubmissionSection 
            :question="'What was hidden in the shapes?'"
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

let currentShape = null;
let offsetX = 0;
let offsetY = 0;

const puzzleLayout = [
    'A*', 'U*', 'N', 'I*', 'E*', 'E*',
    'N', 'L', 'V*', 'G', 'R*', 'N',
    'E', 'P', 'E', 'R*', 'Y*', 'I',
    'I*', 'N*', 'E*', 'S', 'D*', 'M*',
    'F', 'O', 'A', 'M*', 'S*', 'E*',
    'L', 'D*', 'O', 'I*', 'N', 'S*',
    'S', 'I*', 'N*', 'D*', 'O*', 'P',
    'A*', 'G*', 'O', 'E*', 'V', 'A*',
    'E', 'O*', 'R*', 'S', 'R*', 'L'
];

const letters = puzzleLayout.map((entry, index) => ({
    id: index + 1,
    letter: entry.replace('*', ''),
    color: entry.includes('*') ? 'black' : 'white'
}));

const correctAnswers = ['AEGIS', 'UNIVERSE', 'OVERLAPS', 'MANIFOLD', 'DOORS', 'ENERGY', 'PLANE', 'DIMENSION'];
const selectedLetters = ref([]);
const selectedIndices = ref([]);
const selectedIds = ref([]);
const svgElement = ref(null);
const svgStrokeColor = '#3B82F6';
const lastSelectedId = ref(null);
const correctSelections = ref(new Set());
const correctAnswerPaths = ref([]); // Stores positions of correctly answered letters
let isDragging = ref(false);
let dragStartElement = null;
let initialMouseDown = false;
let draggedLetters = ref([]);
const correctAnswersList = ref([]);

const getClass = (id) => {
    const letterObj = letters.find(letter => letter.id === id);
    const isSelected = selectedIds.value.includes(id);
    const isLastSelected = id === lastSelectedId.value;
    const isCorrect = correctSelections.value.has(id);
    const isDot = letterObj.color === 'black';
    const isGap = letterObj.color === 'white';

    return {
        'bg-blue-500 text-white': isSelected,
        'ring-gap !border-2 border-blue-500': isLastSelected,
        'cursor-default bg-green-500': isCorrect && isGap,
        'cursor-default bg-black text-white': isCorrect && isDot,
    };
};

const calculateNewPositions = () => {
    const letterIds = isDragging.value ? draggedLetters.value : selectedIds.value;
    return letterIds.map(id => {
        const letterElement = document.querySelector(`[data-id="${id}"]`);
        if (letterElement) {
            const rect = letterElement.getBoundingClientRect();
            const svgRect = svgElement.value.getBoundingClientRect();
            return { id, x: rect.left + rect.width / 2 - svgRect.left, y: rect.top + rect.height / 2 - svgRect.top };
        }
        return null;
    }).filter(pos => pos !== null);
};


const updateSvgPosition = (letterObj, event) => {
    if (svgElement.value) {
        const { left, top, width, height } = event.target.getBoundingClientRect();
        const { left: svgLeft, top: svgTop } = svgElement.value.getBoundingClientRect();
        selectedIndices.value.push({ id: letterObj.id, x: left + width / 2 - svgLeft, y: top + height / 2 - svgTop });
    }
    updatePositions();
};

const updatePositions = () => {
    const newPositions = calculateNewPositions();
    selectedIndices.value = newPositions;
};

const selectLetter = async (letterObj, event) => {
    // Checks if user is clicking on an already answered letter
    if (correctSelections.value.has(letterObj.id)) {
        return; // This prevents further processing of the click
    }

    // Checks if the letter is already in selectedIDs and then gets the last letter before this one
    const isSelectedIndex = selectedIds.value.indexOf(letterObj.id);
    const lastId = selectedIds.value.at(-1);

    // Resets if the user clicks outside the adjacent areas
    if (lastId !== null && isSelectedIndex === -1 && !isAdjacent(letterObj.id, lastId)) {
        resetSelections();
    }

    if (isSelectedIndex !== -1) {
        if (isSelectedIndex === selectedIds.value.length - 1) {
            if (selectedLetters.value.length > 1) {
                checkForSubmission('click');
                return;
            } else {
                selectedLetters.value.pop();
                selectedIds.value.pop();
                selectedIndices.value.pop();
                lastSelectedId.value = selectedIds.value.at(-1) || null;
                updatePositions();
                return;
            }
        } else {
            selectedLetters.value = selectedLetters.value.slice(0, isSelectedIndex + 1);
            selectedIds.value = selectedIds.value.slice(0, isSelectedIndex + 1);
            selectedIndices.value = selectedIndices.value.slice(0, isSelectedIndex + 1);
            lastSelectedId.value = letterObj.id;
            updatePositions();
            return;
        }
    }

    if (isSelectedIndex === -1) {
        selectedLetters.value.push(letterObj.letter);
        selectedIds.value.push(letterObj.id);
        lastSelectedId.value = letterObj.id;
        await nextTick();
        updateSvgPosition(letterObj, event);
    }
};

const startDrag = (event) => {
    initialMouseDown = true;
    dragStartElement = document.elementFromPoint(event.clientX, event.clientY);
    isDragging.value = false; // Reset dragging flag
};

const dragSelect = (event) => {
    if (!initialMouseDown) return; // Ignore if no initial mousedown

    const element = document.elementFromPoint(event.clientX, event.clientY);
    if (element && element !== dragStartElement) {
        if (!isDragging.value) {
            isDragging.value = true; // Set dragging flag if moving to a different element
            // Retroactively add the initial letter to the selection
            const initialLetterId = dragStartElement.getAttribute('data-id');
            const initialLetterObj = letters.find(letter => letter.id === Number(initialLetterId));
            if (initialLetterObj) {
                draggedLetters.value.push(initialLetterObj.id);
                dragSelectLetter(initialLetterObj, event); // Add initial letter to selection
            }
        }

        const letterId = element.getAttribute('data-id');
        const letterObj = letters.find(letter => letter.id === Number(letterId));
        if (letterObj && !draggedLetters.value.includes(letterObj.id)) {
            dragSelectLetter(letterObj, event); // Use drag selection function
        }
    }
};

const endDrag = (event) => {
    const endElement = document.elementFromPoint(event.clientX, event.clientY);
    if (isDragging.value) {
        // It was a drag
        checkForSubmission('drag');
    }

    initialMouseDown = false;
    isDragging.value = false;
    draggedLetters.value = [];
    dragStartElement = null;
};

const dragSelectLetter = async (letterObj, event) => {
    // Checks if user is dragging on an already answered letter
    if (correctSelections.value.has(letterObj.id)) {
        console.log('Already selected letter, skipping:', letterObj.id);
        return; // This prevents further processing of the click
    }

    // If it's the first letter, just add it
    if (draggedLetters.value.length === 0) {
        draggedLetters.value.push(letterObj.id);
        selectedLetters.value.push(letterObj.letter);
        selectedIds.value.push(letterObj.id);
        updateSvgPosition(letterObj, event);
        return;
    }

    // Check adjacency with the last letter in draggedLetters
    const lastId = draggedLetters.value.at(-1);
    if (!isAdjacent(letterObj.id, lastId)) {
        return; // Ignore if dragging and not adjacent
    }


    // Add the letter to the dragged letters
    draggedLetters.value.push(letterObj.id);
    selectedLetters.value.push(letterObj.letter);
    selectedIds.value.push(letterObj.id);

    // Update the SVG position directly in this function
    if (svgElement.value) {
        const element = document.querySelector(`[data-id="${letterObj.id}"]`);
        if (element) {
            const rect = element.getBoundingClientRect();
            const svgRect = svgElement.value.getBoundingClientRect();
            selectedIndices.value.push({ id: letterObj.id, x: rect.left + rect.width / 2 - svgRect.left, y: rect.top + rect.height / 2 - svgRect.top });
        }
    }
    updatePositions();
};

const isAdjacent = (currentId, lastId) => {
    const currentRow = Math.floor((currentId - 1) / 6);
    const currentCol = (currentId - 1) % 6;
    const lastRow = Math.floor((lastId - 1) / 6);
    const lastCol = (lastId - 1) % 6;
    const adjacent = Math.abs(currentRow - lastRow) <= 1 && Math.abs(currentCol - lastCol) <= 1;
    return adjacent;
};


const checkForSubmission = (type) => {
    let selectedString;
    if (type === 'drag') {
        selectedString = selectedLetters.value.join('');
    } else {
        selectedString = selectedLetters.value.join('');
    }

    if (correctAnswers.includes(selectedString)) {
        // Add IDs of selected letters to correctSelections for highlighting them
        selectedIds.value.forEach(id => correctSelections.value.add(id));

        // Prepare the SVG path data and color for the correct answer
        const pathData = selectedIndices.value.reduce((acc, curr, index) => {
            return `${acc} ${index === 0 ? 'M' : 'L'} ${curr.x},${curr.y}`;
        }, '').trim();
        correctAnswerPaths.value.push({ d: pathData, color: '#10B981' }); // Use green color for correct paths

        console.log('Correct answer:', selectedString);
        correctAnswersList.value.push(selectedString); // Add to correct answers list
        resetSelections();
    } else {
        console.log('Incorrect answer:', selectedString);
        resetSelections();
    }
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
