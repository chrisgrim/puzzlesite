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
                <p class="mt-14">Author stared at the frozen image, tracing the curves and lines with his eyes, dissecting the layers of paint that splashed across the urban canvas. The more he looked, the more certain he became: this was no ordinary tag or mindless act of vandalism. It was a puzzle, meticulously crafted and hidden in plain sight, masquerading as mere street art. The symbols, the arrangement of the colors, and the choice of location—it all pointed to someone communicating in a language of ciphers and riddles.</p>
                <p class="mt-14">With a growing sense of excitement mixed with a twinge of foreboding, Author reached for a notebook and began sketching the visible portions of the graffiti from memory, filling in gaps and extrapolating the obscured parts. Each stroke of his pen brought him closer to deciphering the message hidden in the chaos of color and form. As the city washed away the physical traces of the graffiti, Author worked feverishly to preserve and solve the ephemeral puzzle before the answer was lost to the swift brushes of the cleanup crews.</p>
            </div>
        </div>

        <div class="puzzle flex flex-col items-center justify-center">
            <div class="relative w-full max-w-4xl h-96 mx-auto">
                <div
                    v-for="(shape, index) in shapes"
                    :key="index"
                    :style="{ transform: `translate(${shape.x}px, ${shape.y}px)`, position: 'absolute' }"
                    class="cursor-move"
                    @mousedown="startDrag($event, shape)"
                >
                    <!-- Handle paths -->
                    <svg :viewBox="`${shape.minX} ${shape.minY} ${shape.boxWidth} ${shape.boxHeight}`" 
                         preserveAspectRatio="none"
                         :style="{ width: `${shape.width}px`, height: `${shape.height}px`, color: shape.color }"
                         fill="none" 
                         stroke="currentColor" 
                         :stroke-width="shape.strokeWidth">
                        <path :d="shape.d" :fill="shape.color" />
                    </svg>
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
                    <div v-if="props.solution && props.solution.guesses.length">
                        <p>Previous Guesses:</p>
                        <ul>
                            <!-- Reverse the order of guesses array and loop through -->
                            <li v-for="(g, index) in props.solution.guesses.slice().reverse()" :key="index">
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
import { ref, toRefs } from 'vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter:Object,
    solution: Object,
});

let userSolution = ref(props.solution);
let guess = ref('');
let message = ref('');

let currentShape = null;
let offsetX = 0;
let offsetY = 0;


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

function startDrag(event, shape) {
    currentShape = shape;
    offsetX = event.clientX - shape.x;
    offsetY = event.clientY - shape.y;
    document.addEventListener('mousemove', onDragging);
    document.addEventListener('mouseup', endDrag);
}

function onDragging(event) {
    if (currentShape) {
        currentShape.x = event.clientX - offsetX;
        currentShape.y = event.clientY - offsetY;
    }
}

function endDrag() {
    document.removeEventListener('mousemove', onDragging);
    document.removeEventListener('mouseup', endDrag);
    currentShape = null;
}

const shapes = ref([
  
{
    type: 'path', // Two Circles
    d: 'M99.976 100C44.758 99.987 0 55.22 0 0h200c0 55.22-44.758 99.987-99.976 100 55.218.013 99.976 44.78 99.976 100H0c0-55.22 44.758-99.987 99.976-100Z', 
    color: '#3498DB', // Blue color
    minX: 0,
    minY: 0,
    width: 100,
    height: 100,
    boxWidth: 200,
    boxHeight: 200,
    strokeWidth: 0,
    x: 400, // Adjust the position as needed
    y: 20 // Adjust the position as needed
},
{
    type: 'path', // Half Circle
    d: 'M200 150c0-55.228-44.772-100-100-100S0 94.772 0 150h200Z', 
    color: '#3498DB', // Blue color
    minX: 0,
    minY: 0,
    width: 100,
    height: 100,
    boxWidth: 200,
    boxHeight: 200,
    strokeWidth: 0,
    x: 230, // Adjust the position as needed
    y: 100 // Adjust the position as needed
},
{
    type: 'path', 
    d: 'M12 17v83c0 49.3 40.5 89.1 90.1 88 46.8-1.1 85-39.4 85.9-86.2.5-25-9.5-47.7-25.8-64C146.3 21.9 124.3 12 100 12H17c-2.7 0-5 2.3-5 5z',
    color: '#3498DB', // Blue color
    minX: 0,
    minY: 0,
    width: 50,
    height: 50,
    boxWidth: 200,
    boxHeight: 200,
    strokeWidth: 20,
    x: 50, // Adjust the position as needed
    y: 20 // Adjust the position as needed
},
{
    type: 'path', // U Shape
    d: 'M200 150h-56.25c0-24.162-19.588-43.75-43.75-43.75S56.25 125.838 56.25 150H0C0 94.772 44.772 50 100 50s100 44.772 100 100Z',
    color: '#3498DB', // Blue color
    minX: 0,
    minY: 0,
    width: 80,
    height: 80,
    boxWidth: 200,
    boxHeight: 200,
    strokeWidth: 0,
    x: 130, // Adjust the position as needed
    y: 20 // Adjust the position as needed
}
    // Add more paths as needed
]);
</script>