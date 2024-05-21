<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">
        <div class="flex items-center mb-20">
            <a href="/" class="uppercase">&lt; Back to story</a>
        </div>

        <div id="Header" class="my-10 mb-40">
            <h2 class="text-9xl">{{ puzzle.title }}</h2>
            <div class="flex flex-row mt-14 gap-4">
                <div
                    v-if="puzzle.difficulty > 0"
                    v-for="index in puzzle.difficulty"
                    :key="index"
                    class="bg-red-500 w-10 h-10 rounded-full shadow-inner-shadow"
                ></div>
                <div
                    v-for="index in 3 - puzzle.difficulty"
                    :key="index"
                    class="bg-white w-10 h-10 rounded-full shadow-inner-shadow"
                ></div>
            </div>
            <p class="mt-14">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim <span class="selection:text-fuchsia-900 selection:bg-fuchsia-300">tedz</span>id est laborum.
            </p>
        </div>

        <div class="game-container flex flex-col items-center mt-5" @mouseup="stopRotation">
            <div class="grid grid-cols-6 gap-0.5">
                <div v-for="(row, rowIndex) in grid" :key="rowIndex" class="grid-row">
                    <div
                        v-for="(cell, cellIndex) in row"
                        :key="cellIndex"
                        class="grid-cell relative"
                        :class="{
                            'w-12 h-12 flex items-center justify-center': true,
                            'bg-brown-600': cell.isPath,
                            'bg-blue-500': cell.isUser,
                            'bg-yellow-400': cell.isStar
                        }"
                        @mousedown="startRotation"
                    >
                        <div
                            class="dot absolute bg-black rounded-full w-1 h-1"
                            :style="{ top: '50%', left: '50%', transform: 'translate(-50%, -50%)' }"
                        ></div>
                    </div>
                </div>
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
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

let guess = ref('');
let message = ref('');
let userSolution = ref(props.solution);


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
        guess = '';
        message.value = data.message;
        userSolution.value = data.solution;

    } catch (error) {
        message.value = `Error: ${error.response ? error.response.data.message : error.message}`;
    }
}

function clear() {
    message.value = ''
}

const grid = ref([]);
const userPosition = reactive({ x: 0, y: 0 });
const gridSize = ref(6);
const isRotating = ref(false);
const initialAngle = ref(0);
const currentRotationDegrees = ref(0);
const lastSnapRotation = ref(0);
let gridElement = null;

const initializeGrid = () => {
    grid.value = Array.from({ length: gridSize.value }, () =>
        Array.from({ length: gridSize.value }, () => ({
            isPath: Math.random() > 0.5,
            isUser: false,
            isStar: false
        }))
    );
};

const placeUser = () => {
    userPosition.x = Math.floor(gridSize.value / 2);
    userPosition.y = Math.floor(gridSize.value / 2);
    grid.value[userPosition.y][userPosition.x].isUser = true;
};

const placeStar = () => {
    let starPosition = { x: gridSize.value - 1, y: gridSize.value - 1 };
    grid.value[starPosition.y][starPosition.x].isStar = true;
};

const startRotation = (event) => {
    if (!isRotating.value) {
        isRotating.value = true;
        initialAngle.value = getMouseAngle(event);
        currentRotationDegrees.value = lastSnapRotation.value;
    }
};

const handleMouseMove = (event) => {
    if (isRotating.value) {
        let currentAngle = getMouseAngle(event);
        let angleDelta = currentAngle - initialAngle.value;
        currentRotationDegrees.value += angleDelta;
        initialAngle.value = currentAngle;
        gridElement.style.transform = `rotate(${currentRotationDegrees.value}deg)`;
    }
};

const stopRotation = () => {
    if (isRotating.value) {
        isRotating.value = false;
        let snapAngle = Math.round(currentRotationDegrees.value / 90) * 90;
        gridElement.style.transform = `rotate(${snapAngle}deg)`;
        applyRotation(snapAngle);
        lastSnapRotation.value = snapAngle;
        currentRotationDegrees.value = snapAngle;
    }
};

const getMouseAngle = (event) => {
    let rect = gridElement.getBoundingClientRect();
    let centerX = rect.left + rect.width / 2;
    let centerY = rect.top + rect.height / 2;
    let deltaX = event.clientX - centerX;
    let deltaY = event.clientY - centerY;
    return Math.atan2(deltaY, deltaX) * 180 / Math.PI;
};

const applyRotation = (angle) => {
    let numRotations = ((angle / 90) % 4 + 4) % 4;
    for (let i = 0; i < numRotations; i++) {
        rotateGrid();
    }
};

const rotateGrid = () => {
    let newGrid = [];
    for (let col = 0; col < gridSize.value; col++) {
        newGrid.push([]);
        for (let row = gridSize.value - 1; row >= 0; row--) {
            newGrid[col].push({ ...grid.value[row][col] });
        }
    }
    grid.value = newGrid;
    updateUserPositionAfterRotation();
};

const updateUserPositionAfterRotation = () => {
    grid.value[userPosition.y][userPosition.x].isUser = false;
    let newUserPosition = {
        x: userPosition.y,
        y: gridSize.value - 1 - userPosition.x
    };
    userPosition.x = newUserPosition.x;
    userPosition.y = newUserPosition.y;
    grid.value[userPosition.y][userPosition.x].isUser = true;
};

onMounted(() => {
    initializeGrid();
    placeUser();
    placeStar();
    gridElement = document.querySelector('.grid');
    window.addEventListener('mousemove', handleMouseMove);
    window.addEventListener('mouseup', stopRotation);
});

onBeforeUnmount(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    window.removeEventListener('mouseup', stopRotation);
});
</script>

<style>
.cursor {
    animation: blink 1s infinite step-start;
}

@keyframes blink {
    0%,
    50% {
        opacity: 1;
    }
    50.01%,
    100% {
        opacity: 0;
    }
}
</style>
