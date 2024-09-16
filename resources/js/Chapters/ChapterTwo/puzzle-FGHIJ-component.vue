<template>
    <div id="puzzle" class="puzzle flex flex-row items-center justify-center py-16">
        <div class="w-1/2">
            <div class="w-full border border-black rounded-3xl overflow-hidden flex flex-col">
                <div class="bg-gray-700 text-white text-center p-2">Union Daily</div>
                <div class="mt-4 flex flex-col p-4">
                    <div v-for="(answer, index) in correctAnswersList" :key="index">
                        {{ answer }}
                    </div>
                    <div>
                        {{ correctAnswersList.length }} of 8 found
                    </div>
                </div>
            </div>
            <div class="mt-12">
                <p class="m-0">July 8, 2024</p>
                <p class="text-bold m-0"><b>By Morris Kohd</b></p>
            </div>
        </div>

        <div class="relative mx-auto touch-none place-content-center w-1/2 grid">
            <p class="text-center h-10 mb-8">{{ selectedLetters.join('') }}</p>
            <svg ref="svgElement" class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
                <path v-for="(path, index) in morsePaths" :key="'correct-path-' + index"
                      :d="path.d" stroke-linecap="round" stroke-width="12"
                      stroke="black" fill="none"/>
                <path v-for="(path, index) in correctAnswerPaths" :key="'correct-path-' + index"
                      :d="path.d" stroke-linecap="round" stroke-width="12"
                      :stroke="path.color" fill="none"/>
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
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';

const puzzleLayout = [
    'A*', 'U*', 'N', 'I*', 'E*', 'E*',
    'N', 'L', 'V*', 'G', 'R*', 'N',
    'E', 'P', 'E', 'R*', 'Y*', 'I',
    'I*', 'N*', 'E*', 'S', 'D*', 'M*',
    'F', 'O*', 'A', 'M*', 'S*', 'E*',
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
const lastSelectedId = ref(null);
const correctSelections = ref(new Set());
const correctAnswerPaths = ref([]);
const morsePaths = ref([]);
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
        'cursor-default bg-[#919191]': isCorrect && isGap,
        'cursor-default bg-black text-white': isCorrect && isDot,
    };
};

const calculateNewPositions = () => {
    const letterIds = isDragging.value ? draggedLetters.value : selectedIds.value;
    return letterIds.map(id => {
        const letterElement = document.querySelector(`[data-id="${id}"]`);
        const letterObj = letters.find(letter => letter.id === id);
        if (letterElement && letterObj) {
            const rect = letterElement.getBoundingClientRect();
            const svgRect = svgElement.value.getBoundingClientRect();
            return { 
                id, 
                x: rect.left + rect.width / 2 - svgRect.left, 
                y: rect.top + rect.height / 2 - svgRect.top,
                color: letterObj.color
            };
        }
        return null;
    }).filter(pos => pos !== null);
};

const updateSvgPosition = (letterObj, event) => {
    if (svgElement.value) {
        const { left, top, width, height } = event.target.getBoundingClientRect();
        const { left: svgLeft, top: svgTop } = svgElement.value.getBoundingClientRect();
        selectedIndices.value.push({ id: letterObj.id, color: letterObj.id, x: left + width / 2 - svgLeft, y: top + height / 2 - svgTop });
    }
    updatePositions();
};

const updatePositions = () => {
    const newPositions = calculateNewPositions();
    selectedIndices.value = newPositions;
};

const selectLetter = async (letterObj, event) => {
    if (correctSelections.value.has(letterObj.id)) return;

    const isSelectedIndex = selectedIds.value.indexOf(letterObj.id);
    const lastId = selectedIds.value.at(-1);

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
    isDragging.value = false;
};

const dragSelect = (event) => {
    if (!initialMouseDown) return;

    const element = document.elementFromPoint(event.clientX, event.clientY);
    if (element && element !== dragStartElement) {
        if (!isDragging.value) {
            isDragging.value = true;
            const initialLetterId = dragStartElement.getAttribute('data-id');
            const initialLetterObj = letters.find(letter => letter.id === Number(initialLetterId));
            if (initialLetterObj) {
                draggedLetters.value.push(initialLetterObj.id);
                dragSelectLetter(initialLetterObj, event);
            }
        }

        const letterId = element.getAttribute('data-id');
        const letterObj = letters.find(letter => letter.id === Number(letterId));
        if (letterObj && !draggedLetters.value.includes(letterObj.id)) {
            dragSelectLetter(letterObj, event);
        }
    }
};

const endDrag = (event) => {
    const endElement = document.elementFromPoint(event.clientX, event.clientY);
    if (isDragging.value) {
        checkForSubmission('drag');
    }

    initialMouseDown = false;
    isDragging.value = false;
    draggedLetters.value = [];
    dragStartElement = null;
};

const dragSelectLetter = async (letterObj, event) => {
    if (correctSelections.value.has(letterObj.id)) return;

    if (draggedLetters.value.length === 0) {
        draggedLetters.value.push(letterObj.id);
        selectedLetters.value.push(letterObj.letter);
        selectedIds.value.push(letterObj.id);
        updateSvgPosition(letterObj, event);
        return;
    }

    const lastId = draggedLetters.value.at(-1);
    if (!isAdjacent(letterObj.id, lastId)) return;

    draggedLetters.value.push(letterObj.id);
    selectedLetters.value.push(letterObj.letter);
    selectedIds.value.push(letterObj.id);
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
    return Math.abs(currentRow - lastRow) <= 1 && Math.abs(currentCol - lastCol) <= 1;
};

const checkForSubmission = (type) => {
    let selectedString = selectedLetters.value.join('');

    if (correctAnswers.includes(selectedString)) {
        selectedIds.value.forEach(id => correctSelections.value.add(id));

        let currentPath = "";
        let currentColor = "";
        const pathSegments = [];
        const morseSegments = [];

        selectedIndices.value.forEach((curr, index, array) => {
            const next = array[index + 1];

            if (index === 0) {
                currentPath = `M ${curr.x},${curr.y}`;
                currentColor = '#919191';
            } else {
                currentPath += ` L ${curr.x},${curr.y}`;
                if (next) {
                    const nextColor = (curr.color === 'black' && next.color === 'black') ? '#000000' : '#919191';
                    if (nextColor !== currentColor || !next) {
                        if (currentColor === '#000000') {
                            morseSegments.push({ path: currentPath, color: currentColor });
                        } else {
                            pathSegments.push({ path: currentPath, color: currentColor });
                        }
                        currentPath = `M ${curr.x},${curr.y}`;
                        currentColor = nextColor;
                    }
                }
            }
        });

        if (currentColor === '#000000') {
            morseSegments.push({ path: currentPath, color: currentColor });
        } else {
            pathSegments.push({ path: currentPath, color: currentColor });
        }

        pathSegments.forEach(segment => {
            correctAnswerPaths.value.push({ d: segment.path, color: segment.color });
        });

        morseSegments.forEach(segment => {
            morsePaths.value.push({ d: segment.path, color: segment.color });
        });

        correctAnswersList.value.push(selectedString);
        resetSelections();
    } else {
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
