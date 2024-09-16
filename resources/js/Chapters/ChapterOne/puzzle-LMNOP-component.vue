<template>
    <div class="w-full flex flex-row items-center" :class="[isShaking ? 'shake' : '']">
        <div class="w-6 border-l-4 border-t-4 border-b-4 border-black h-40 rounded-2xl bg-gray-400" />
        <div class="w-full relative">
            <div class="trapezoidal-button h-[10rem] w-[calc(100%-1rem)] mx-auto justify-center gap-20 items-center flex bg-gray-500 rounded-2xl z-10 border-black border-4 absolute left-2 right-2">
                <button 
                    @mousedown="startHourHold"
                    @mouseup="stopHourHold"
                    @mouseleave="stopHourHold"
                    class="button-10 h-20 relative inline-block cursor-pointer outline-none border-0 align-middle text-inherit font-inherit font-semibold text-[#382b22] uppercase py-5 px-8 bg-white border-2 border-solid border-[#8d8d8d] rounded-xl transform transition-all duration-150 ease-in-out hover:bg-[#ebebeb] hover:translate-y-1 active:bg-[#ffe9e9] active:translate-y-3 before:absolute before:content-[''] before:w-full before:h-full before:top-0 before:left-0 before:right-0 before:bottom-0 before:bg-[#c4c4c4] before:rounded-inherit before:shadow-[0_0_0_2px_#828282,0_0.625em_0_0_#929292] before:translate-y-3 before:-translate-z-4 before:transition-transform before:duration-150 before:ease-in-out"
                    role="button"
                >
                    <span class="text relative z-10">Hour</span>
                </button>
                <button 
                    @mousedown="startMinuteHold"
                    @mouseup="stopMinuteHold"
                    @mouseleave="stopMinuteHold"
                    class="button-10 h-20 relative inline-block cursor-pointer outline-none border-0 align-middle text-inherit font-inherit font-semibold text-[#382b22] uppercase py-5 px-8 bg-white border-2 border-solid border-[#8d8d8d] rounded-xl transform transition-all duration-150 ease-in-out hover:bg-[#ebebeb] hover:translate-y-1 active:bg-[#ffe9e9] active:translate-y-3 before:absolute before:content-[''] before:w-full before:h-full before:top-0 before:left-0 before:right-0 before:bottom-0 before:bg-[#c4c4c4] before:rounded-inherit before:shadow-[0_0_0_2px_#828282,0_0.625em_0_0_#929292] before:translate-y-3 before:-translate-z-4 before:transition-transform before:duration-150 before:ease-in-out"
                    role="button"
                >
                    <span class="text relative z-10">Minute</span>
                </button>
                <button 
                    @click="checkAnswer"
                    class="button-10 h-20 relative inline-block cursor-pointer outline-none border-0 align-middle text-inherit font-inherit font-semibold text-[#382b22] uppercase py-5 px-8 bg-white border-2 border-solid border-[#8d8d8d] rounded-xl transform transition-all duration-150 ease-in-out hover:bg-[#ebebeb] hover:translate-y-1 active:bg-[#ffe9e9] active:translate-y-3 before:absolute before:content-[''] before:w-full before:h-full before:top-0 before:left-0 before:right-0 before:bottom-0 before:bg-[#c4c4c4] before:rounded-inherit before:shadow-[0_0_0_2px_#828282,0_0.625em_0_0_#929292] before:translate-y-3 before:-translate-z-4 before:transition-transform before:duration-150 before:ease-in-out"
                    role="button"
                >
                    <span class="text relative z-10">Submit</span>
                </button>
            </div>
            <div class="flex flex-col items-center p-8 w-full border-4 border-black rounded-2xl bg-gray-400 z-20 mt-[9rem]">
                <div class="relative w-full h-16 mt-4">
                    <div class="screw absolute w-12 h-12 rounded-full border-[#3e3e3e] shadow-[0px_4px_0px_1px_rgba(0,0,0,0.5)] border-2">
                        <div class="indent h-3 w-[2.9rem] mt-4 bg-[url('img/nail-head.jpg')] shadow-[inset_0px_1px_8px_#222] rotate-[150deg] rounded-[2px] -ml-[2px] border-b border-solid border-[rgba(255,255,255,0.3)]"/>
                    </div>
                    <div class="screw absolute w-12 h-12 rounded-full border-[#3e3e3e] shadow-[0px_4px_0px_1px_rgba(0,0,0,0.5)] border-2 right-0">
                        <div class="indent h-3 w-[2.9rem] mt-4 bg-[url('img/nail-head.jpg')] shadow-[inset_0px_1px_8px_#222] rotate-[120deg] rounded-[2px] -ml-[2px] border-b border-solid border-[rgba(255,255,255,0.3)]"/>
                    </div>
                </div>


                <div class="w-full px-16">
                    <div class="flex w-full mb-4 bg-gray-800 shadow-[inset_0_10px_1px_3px_rgba(0,0,0,0.5)] rounded-2xl px-8 justify-center items-center">
                        <template v-for="display in displays" :key="display.id">
                            <div 
                                class="w-40 h-60 mt-2 rounded-2xl flex flex-col items-center justify-center transition-all duration-200 cursor-pointer screen-element"
                                :class="[
                                    selectedDisplay === display.id ? 'border-blue-500' : 'border-black',
                                    { 'pointer-events-none': isBusy }
                                ]"
                                @click="selectDisplay(display.id)"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="240" viewBox="-1 -1 12 20" stroke="#19202b" stroke-width=".25">
                                    <polygon v-for="segment in segments" :key="segment.id" 
                                            :id="segment.id" 
                                            :points="segment.points" 
                                            :class="{ 'active': display.activeSegments.includes(segment.id) }"
                                    />
                                </svg>
                            </div>
                            <!-- Vertical dots between hour and minute -->
                            <div v-if="display.id === 1" class="flex flex-col justify-center h-80 mx-4 gap-16">
                                <div class="w-6 h-6 bg-[#F00] rounded-full mb-2"></div>
                                <div class="w-6 h-6 bg-[#F00] rounded-full"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Text display for each screen -->
                    <div class="w-full h-40 bg-gray-800 rounded-2xl p-4 mt-12 shadow-[inset_0_8px_1px_1px_rgba(0,0,0,0.5)]">
                        <p class="text-lg font-bold text-white">
                            <span v-html="puzzles[selectedPuzzle].text"></span>
                        </p>
                    </div>

                    <div class="flex items-center mt-8">
                        <!-- Dial -->
                        <div class="flex-shrink-0 relative w-32 h-32 cursor-pointer" @click="handleRotate">
                            <div
                                class="absolute w-full h-full bg-gray-600 rounded-full shadow-[inset_-2px_2px_0px_rgba(255,255,255,0.1),inset_2px_-2px_0px_rgba(17,17,17,0.2),-5px_5px_5px_#111,-10px_10px_10px_-5px_#111,-20px_20px_20px_-10px_#111,-25px_25px_25px_-10px_#111]"
                            >
                                <!-- Rotating Handle -->
                                <div
                                    class="absolute top-1/2 left-3/4 w-1/4 h-1 bg-white rounded transform -translate-y-1/2 origin-[calc(-100%)_50%] shadow-[1px_-1px_0px_rgba(17,17,17,0.2)]"
                                    :style="{ transform: `rotate(${rotation}deg) translateY(-50%)` }"
                                ></div>
                            </div>
                            <!-- Numbers around the dial -->
                            <div
                                v-for="(number, index) in [1, 2, 3, 4]"
                                :key="number"
                                class="absolute w-8 h-8 flex items-center justify-center text-black font-bold"
                                :style="getNumberPosition(index)"
                            >
                                {{ number }}
                            </div>
                        </div>
                        
                        <!-- Indicator -->
                        <div class="w-full flex justify-center items-center gap-8">
                            <div v-for="(puzzle, index) in puzzles" :key="index"
                                 class="relative w-10 h-10 rounded-full transition-all duration-300 shadow-md"
                                 :class="[
                                    'bg-gradient-to-br from-gray-100 to-gray-300',
                                    selectedPuzzle === index ? 'ring-4 ring-yellow-300 ring-opacity-50' : '',
                                    puzzle.completed ? '!ring-4 !ring-green-300 !ring-opacity-50' : '',
                                 ]"
                            >
                                <!-- Inner highlight -->
                                <div class="absolute inset-1 bg-gradient-to-br from-white to-transparent rounded-full opacity-75"></div>
                                
                                <!-- Glow effect -->
                                <div class="absolute -inset-2 rounded-full blur-md transition-opacity duration-300"
                                     :class="[
                                        selectedPuzzle === index ? 'bg-yellow-200 opacity-75' : 'opacity-0',
                                        puzzle.completed ? '!bg-green-200 !opacity-75' : '',
                                     ]"
                                ></div>
                                
                                <!-- Reflection -->
                                <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full opacity-75"></div>
                            </div>
                        </div>
                    </div>

                    

                </div>

                <div class="relative w-full h-12 mt-4">
                    <div class="screw absolute w-12 h-12 rounded-full border-[#3e3e3e] shadow-[0px_4px_0px_1px_rgba(0,0,0,0.5)] border-2">
                        <div class="indent h-3 w-[2.9rem] mt-4 bg-[url('img/nail-head.jpg')] shadow-[inset_0px_1px_8px_#222] rotate-[45deg] rounded-[2px] -ml-[2px] border-b border-solid border-[rgba(255,255,255,0.3)]"/>
                    </div>
                    <div class="screw absolute w-12 h-12 rounded-full border-[#3e3e3e] shadow-[0px_4px_0px_1px_rgba(0,0,0,0.5)] border-2 right-0">
                        <div class="indent h-3 w-[2.9rem] mt-4 bg-[url('img/nail-head.jpg')] shadow-[inset_0px_1px_8px_#222] rotate-[10deg] rounded-[2px] -ml-[2px] border-b border-solid border-[rgba(255,255,255,0.3)]"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-6 border-r-4 border-t-4 border-b-4 border-black h-40 rounded-2xl bg-gray-400" />
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { Jupiter, Earth, Saturn } from './planets.js';

const selectedPuzzle = ref(0)
const selectedDisplay = ref(null)
const userInput = ref('0000')
const buttonState = ref('')
const isShaking = ref(false)
const isError = ref(false)

const isBusy = ref(false)

const isBlinking = ref(false)
const blinkingInterval = ref(null)

const hourValues = reactive([0, 0])
const minuteValues = reactive([0, 0])

const hourTimeoutId = ref(null)
const isHoldingHour = ref(false)
const isHourPressed = ref(false)

const minuteTimeoutId = ref(null)
const isHoldingMinute = ref(false)
const isMinutePressed = ref(false)
const holdDelay = 500

const rotation = ref(-30);

const angles = [-30, 0, 30, 60];

const segments = [
    { id: 'a', points: "1,1 2,0 8,0 9,1 8,2 2,2" },
    { id: 'b', points: "9,1 10,2 10,8 9,9 8,8 8,2" },
    { id: 'c', points: "9,9 10,10 10,16 9,17 8,16 8,10" },
    { id: 'd', points: "9,17 8,18 2,18 1,17 2,16 8,16" },
    { id: 'e', points: "1,17 0,16 0,10 1,9 2,10 2,16" },
    { id: 'f', points: "1,9 0,8 0,2 1,1 2,2 2,8" },
    { id: 'g', points: "1,9 2,8 8,8 9,9 8,10 2,10" },
]

const digitToSegments = {
    0: ['a', 'b', 'c', 'd', 'e', 'f'],
    1: ['b', 'c'],
    2: ['a', 'b', 'd', 'e', 'g'],
    3: ['a', 'b', 'c', 'd', 'g'],
    4: ['b', 'c', 'f', 'g'],
    5: ['a', 'c', 'd', 'f', 'g'],
    6: ['a', 'c', 'd', 'e', 'f', 'g'],
    7: ['a', 'b', 'c'],
    8: ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
    9: ['a', 'b', 'c', 'd', 'f', 'g'],
    10: ['a', 'b', 'c', 'd', 'e', 'f'],
    11: ['b', 'c'],
    12: ['a', 'b', 'd', 'e', 'g']
}

const handleRotate = () => {
    // Increment the rotation in steps corresponding to the angles
    const currentRotationIndex = (angles.indexOf(rotation.value) + 1) % angles.length;
    rotation.value = angles[currentRotationIndex];
    
    // Calculate the puzzle ID based on the current rotation index
    const puzzleId = currentRotationIndex;
    
    // Select the corresponding puzzle
    selectPuzzle(puzzleId);
};

const getNumberPosition = (index) => {
    const baseAngle = -30; // Starting angle for 2 o'clock
    const angle = baseAngle + index * 30; // Increment by 30 degrees for each number
    const radius = 70; // Set the radius for placing the numbers
    const x = 50 + radius * Math.cos((angle * Math.PI) / 180);
    const y = 50 + radius * Math.sin((angle * Math.PI) / 180);
    return {
        top: `${y}%`,
        left: `${x}%`,
        transform: 'translate(-50%, -50%)',
    };
};

const puzzles = reactive([
    { id: 0, completed: false, text: 'She ate nothing for lunch too.', answer: '8042' },
    { id: 1, completed: false, text: `
        <div style="display: flex; align-items: center; gap: 2%;">
            ${Saturn}
            ${Jupiter}
            ${Earth}
        </div>
    `, answer: '1111' },
    { id: 2, completed: false, text: 'Jump on a phone', answer: '5867' },
    { id: 3, completed: false, text: 'If ▲ = ►, and N = Z, then _ m ∞ <span style="display:inline-block; transform:rotate(-90deg);">7</span>', answer: '1387' },
])

const displays = reactive([
    { id: 0, activeSegments: [] },
    { id: 1, activeSegments: [] },
    { id: 2, activeSegments: [] },
    { id: 3, activeSegments: [] },
])

const buttonClass = computed(() => {
    if (buttonState.value === 'correct') return 'bg-green-500 text-white'
    if (buttonState.value === 'incorrect') return 'bg-red-500 text-white'
    return 'bg-blue-500 text-white'
})

const selectPuzzle = async (id) => {
    if (isBusy.value) return;
    
    isBusy.value = true;
    selectedPuzzle.value = id;
    isError.value = false;
    
    if (puzzles[id].completed) {
        displays.forEach((display) => {
            display.activeSegments = [];
            display.value = 0;
        });

        await runActivationSequence(id);
    } else {
        // Ensure hour and minute values are reset properly
        resetDisplays();
    }

    isBusy.value = false;
}


const selectDisplay = (id) => {
    selectedDisplay.value = id
    isError.value = false

    // Increment the number by 1 each time the display is clicked
    let currentValue = displays[id].value || 0
    currentValue = (currentValue + 1) % 10 // Ensure it wraps around after 9

    displays[id].value = currentValue
    displays[id].activeSegments = digitToSegments[currentValue] || []

    // Update hour and minute values depending on which display is clicked
    if (id < 2) {
        hourValues[id] = currentValue
    } else {
        minuteValues[id - 2] = currentValue
    }

    updateUserInput()
}

const handleClickAway = () => {
    if (isBlinking.value && selectedDisplay.value !== null) {
        toggleBlinking(selectedDisplay.value, false)
    }
}

const handleScreenClick = (event) => {
    if (!event.target.closest('.screen-element')) {
        handleClickAway()
    }
}

const toggleBlinking = (id, shouldBlink = true) => {
    if (blinkingInterval.value) {
        clearInterval(blinkingInterval.value)
        blinkingInterval.value = null
    }

    isBlinking.value = shouldBlink

    if (shouldBlink) {
        const toggleSegments = () => {
            const currentDisplay = displays[id]
            currentDisplay.activeSegments = currentDisplay.activeSegments.length > 0 
                ? [] 
                : ['a', 'b', 'c', 'd', 'e', 'f']
        }

        toggleSegments() // Initial state
        blinkingInterval.value = setInterval(toggleSegments, 500)
    } else {
        // Reset the segments to the current value when stopping blinking
        const currentValue = displays[id].value || 0
        displays[id].activeSegments = digitToSegments[currentValue] || []
    }
}

const setNumber = (number) => {
    if (selectedDisplay.value !== null && number >= 0 && number <= 9) {
        displays[selectedDisplay.value].value = number
        displays[selectedDisplay.value].activeSegments = digitToSegments[number]

        if (selectedDisplay.value < 2) {
            hourValues[selectedDisplay.value] = number
        } else {
            minuteValues[selectedDisplay.value - 2] = number
        }

        updateUserInput()
    }
}

const syncValuesWithDisplays = () => {
    hourValues[0] = displays[0].value
    hourValues[1] = displays[1].value
    minuteValues[0] = displays[2].value
    minuteValues[1] = displays[3].value
    
    updateDisplays()
}

const updateUserInput = () => {
    // Ensure fallback of 0 for undefined or NaN values
    const h1 = isNaN(hourValues[0]) ? 0 : hourValues[0];
    const h2 = isNaN(hourValues[1]) ? 0 : hourValues[1];
    const m1 = isNaN(minuteValues[0]) ? 0 : minuteValues[0];
    const m2 = isNaN(minuteValues[1]) ? 0 : minuteValues[1];

    // Update the userInput with proper values
    userInput.value = `${h1}${h2}${m1}${m2}`;
}

const handleKeyPress = (event) => {
    const key = parseInt(event.key)
    if (!isNaN(key) && key >= 0 && key <= 9) {
        setNumber(key)
    }
}

const updateHour = () => {
    hourValues[1] = (hourValues[1] + 1) % 10;
    if (hourValues[1] === 0) {
        hourValues[0] = (hourValues[0] + 1) % 10;
    }

    // Check for NaN and reset if necessary
    hourValues[0] = isNaN(hourValues[0]) ? 0 : hourValues[0];
    hourValues[1] = isNaN(hourValues[1]) ? 0 : hourValues[1];

    updateDisplays();
}

const startHourHold = () => {
    isHourPressed.value = true
    isHoldingHour.value = false
    hourTimeoutId.value = setTimeout(() => {
        isHoldingHour.value = true
        updateHour()
        hourTimeoutId.value = setTimeout(function repeat() {
            if (isHoldingHour.value) {
                updateHour()
                hourTimeoutId.value = setTimeout(repeat, 100)
            }
        }, 200)
    }, holdDelay)
}

const stopHourHold = () => {
    if (hourTimeoutId.value) {
        clearTimeout(hourTimeoutId.value)
        hourTimeoutId.value = null
    }
    if (isHourPressed.value && !isHoldingHour.value) {
        updateHour() // Single increment for a quick press
    }
    isHoldingHour.value = false
    isHourPressed.value = false
}

const updateMinute = () => {
    minuteValues[1] = (minuteValues[1] + 1) % 10;
    if (minuteValues[1] === 0) {
        minuteValues[0] = (minuteValues[0] + 1) % 6;
    }

    // Check for NaN and reset if necessary
    minuteValues[0] = isNaN(minuteValues[0]) ? 0 : minuteValues[0];
    minuteValues[1] = isNaN(minuteValues[1]) ? 0 : minuteValues[1];

    updateDisplays();
}


const startMinuteHold = () => {
    isMinutePressed.value = true
    isHoldingMinute.value = false
    minuteTimeoutId.value = setTimeout(() => {
        isHoldingMinute.value = true
        updateMinute()
        minuteTimeoutId.value = setTimeout(function repeat() {
            if (isHoldingMinute.value) {
                updateMinute()
                minuteTimeoutId.value = setTimeout(repeat, 100)
            }
        }, 200)
    }, holdDelay)
}

const stopMinuteHold = () => {
    if (minuteTimeoutId.value) {
        clearTimeout(minuteTimeoutId.value)
        minuteTimeoutId.value = null
    }
    if (isMinutePressed.value && !isHoldingMinute.value) {
        updateMinute() // Single increment for a quick press
    }
    isHoldingMinute.value = false
    isMinutePressed.value = false
}

const updateDisplays = () => {
    displays[0].value = hourValues[0]
    displays[1].value = hourValues[1]
    displays[2].value = minuteValues[0]
    displays[3].value = minuteValues[1]
    
    displays[0].activeSegments = digitToSegments[displays[0].value] || []
    displays[1].activeSegments = digitToSegments[displays[1].value] || []
    displays[2].activeSegments = digitToSegments[displays[2].value] || []
    displays[3].activeSegments = digitToSegments[displays[3].value] || []
    
    updateUserInput()
}

const resetDisplays = () => {
    hourValues[0] = 0;
    hourValues[1] = 0;
    minuteValues[0] = 0;
    minuteValues[1] = 0;
    
    syncValuesWithDisplays(); // Update displays to reflect 00:00
}

const checkAnswer = async () => {
    if (isBusy.value) return
    
    isBusy.value = true
    const currentPuzzle = puzzles[selectedPuzzle.value]
    if (userInput.value === currentPuzzle.answer) {
        buttonState.value = 'correct'
        currentPuzzle.completed = true
        
        setTimeout(() => {
            buttonState.value = ''
        }, 1000)

        // Make all screens except the one at puzzle index go black
        displays.forEach((display, index) => {
            if (index !== selectedPuzzle.value) {
                display.activeSegments = []
            }
        })

        await runActivationSequence(selectedPuzzle.value)
    } else {
        buttonState.value = 'incorrect'
        isError.value = true
        isShaking.value = true
        await new Promise(resolve => setTimeout(() => {
            buttonState.value = ''
            isShaking.value = false
            resolve()
        }, 500))
    }
    
    if (puzzles.every(puzzle => puzzle.completed)) {
        alert('Congratulations! You completed all puzzles!')
    }
    
    isBusy.value = false
}

const getActivationSequence = (screenIndex) => {
    const sequences = [
        [
            { segments: ['e', 'f'], duration: 1000 },
            { segments: ['b', 'c'], duration: 1000 },
            { segments: ['g'], duration: 1000 },

        ],
        [
            { segments: ['a', 'b', 'c'], duration: 1000 },
            { segments: ['d', 'e', 'f'], duration: 1000 },
            { segments: ['g'], duration: 1000 },

        ],
        [
            { segments: ['a', 'g'], duration: 1000 },
            { segments: ['b', 'f'], duration: 1000 },
            { segments: ['c', 'e'], duration: 1000 },

        ],
        [
            { segments: ['a', 'b', 'c', 'd'], duration: 1000 },
            { segments: ['e', 'f', 'g'], duration: 1000 },

        ]
    ]

    return sequences[screenIndex]
}

const runActivationSequence = (index, isCompleted = false) => {
    const sequence = getActivationSequence(index)
    let currentStep = 0

    // Store the current user input before running the sequence
    const originalInput = userInput.value

    const runStep = () => {
        if (currentStep < sequence.length) {
            const step = sequence[currentStep]
            if (step.allScreens) {
                displays.forEach((display, displayIndex) => {
                    display.activeSegments = [...step.segments]
                })
            } else {
                displays[index].activeSegments = step.segments
            }
            currentStep++
            setTimeout(runStep, step.duration)
        } else {
            // After sequence, revert to the user's input
            displays.forEach((display, displayIndex) => {
                if (displayIndex < originalInput.length) {
                    const inputNumber = parseInt(originalInput[displayIndex])
                    display.value = inputNumber
                    display.activeSegments = digitToSegments[inputNumber] || []
                } else {
                    display.activeSegments = []
                    display.value = null
                }
            })
        }
    }

    runStep()
}


onMounted(() => {
    window.addEventListener('keypress', handleKeyPress)
    document.addEventListener('click', handleScreenClick)
})

onUnmounted(() => {
    window.removeEventListener('keypress', handleKeyPress)
    document.removeEventListener('click', handleScreenClick)
    if (minuteTimeoutId.value) {
        clearTimeout(minuteTimeoutId.value)
    }
    if (hourTimeoutId.value) {
        clearTimeout(hourTimeoutId.value)
    }
    if (selectedDisplay.value !== null) {
        toggleBlinking(selectedDisplay.value, false)
    }
})
</script>

<style scoped>
.trapezoidal-button {
    transform: perspective(500px) rotateX(5deg);
}
polygon {
    fill: #19202b;
}

polygon.active {
    fill: #F00;
}

.button-10 {
  transform-style: preserve-3d;
}

.button-10::before {
  border-radius: inherit;
  box-shadow: 0 0 0 2px #828282, 0 0.625em 0 0 #636a78;
  transform: translate3d(0, 0.75em, -1em);
  transition: transform 150ms cubic-bezier(0, 0, 0.58, 1), box-shadow 150ms cubic-bezier(0, 0, 0.58, 1);
}

.button-selected {
  transform: translate3d(0, 0.45em, 0);
}

.button-10:not(.button-selected):hover {
  transform: translate3d(0, 0.25em, 0);
}

.button-selected::before {
  box-shadow: 0 0 0 2px #828282, 0 0.5em 0 0 #636a78;
  transform: translate3d(0, 0.3em, -1em);
}

.button-10:not(.button-selected):hover::before {
  box-shadow: 0 0 0 2px #828282, 0 0.5em 0 0 #636a78;
  transform: translate3d(0, 0.8rem, -1em);
}

.button-10:active::before {
  box-shadow: 0 0 0 2px #b18597, 0 0 #ffe3e2;
  transform: translate3d(0, 0.3em, -1em);
}

@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  50% { transform: translateX(5px); }
  75% { transform: translateX(-5px); }
  100% { transform: translateX(0); }
}

.shake {
  animation: shake 0.5s ease-in-out;
}
</style>