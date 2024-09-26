<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">
        
        <!-- Header Sectioin -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>To the casual observer, the artwork appeared to simply be a geeky motivational poster. Arthur would often lean back from his work to sympathize with the cat drawn in Ascii art, which struggled to hang on to an Ascii tree limb. 
            </p>
            <p>Arthur took quiet pleasure in the fact that not a single colleague (other than Harold) had yet discerned the poster's true nature—a cipher puzzle hidden in plain view.
            </p>

        </div>

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle flex flex-row items-center justify-center py-16">
            <Puzzle></Puzzle>
        </div>

        <SubmissionSection 
            :question="'What was hidden in the shapes?'"
            :solution="props.solution" 
            :chapter="props.chapter"
            :puzzle="props.puzzle"
        />
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue';
import Header from '@/Global/header.vue';
import SubmissionSection from '@/Global/submissionSection.vue';
import Puzzle from './puzzle-motivational-poster-component.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

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
