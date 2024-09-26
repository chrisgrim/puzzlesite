<template>
    <div class="w-full flex flex-row" :class="[isShaking ? 'shake' : '']">
        <div 
            class="w-full relative h-[47rem] bg-[url('/images/Chapter1/Alarm_Clock_Background.jpg')] bg-[length:110%_auto] bg-[right_1rem_center] bg-no-repeat">
            <div class="w-full justify-center flex mt-[6rem]">
                <button 
                    @mousedown="startHourHold"
                    @mouseup="stopHourHold"
                    @mouseleave="stopHourHold"
                    class="button-10 w-32 h-20 ml-[-11rem] relative inline-block bg-[url('/images/Chapter1/Alarm_Clock_Hour_Up.png')] bg-cover bg-center transition-all duration-200"
                    :class="{ 'bg-[url(/images/Chapter1/Alarm_Clock_Hour_Down.png)]': isHourPressed }"
                    role="button">
                </button>
                <button 
                    @mousedown="startMinuteHold"
                    @mouseup="stopMinuteHold"
                    @mouseleave="stopMinuteHold"
                    class="button-10 w-32 h-20 relative inline-block bg-[url('/images/Chapter1/Alarm_Clock_Minute_Up.png')] bg-cover bg-center transition-all duration-200"
                    :class="{ 'bg-[url(/images/Chapter1/Alarm_Clock_Minute_Down.png)]': isMinutePressed }"
                    role="button">
                </button>
                <button 
                    @click="checkAnswer"
                    class="button-10 w-32 h-20 ml-12 relative inline-block bg-[url('/images/Chapter1/Alarm_Clock_Submit_Up.png')] bg-cover bg-center transition-all duration-200"
                    :class="{ 'bg-[url(/images/Chapter1/Alarm_Clock_Submit_Down.png)]': isMinutePressed }"
                    role="button">
                </button>
            </div>
             
            <div class="flex flex-col items-center p-8 w-full z-20">


                <div class="relative w-[38rem] mt-[4rem] ml-[-10rem] px-16 flex flex-col justify-end">
                    <div class="relative flex w-full h-40 justify-center items-center">
                        <template v-for="display in displays" :key="display.id">
                            <div 
                                class="w-20 h-36 mt-2 rounded-2xl flex flex-col items-center justify-center transition-all duration-200 cursor-pointer screen-element"
                                :class="[
                                    selectedDisplay === display.id ? 'border-blue-500' : 'border-black',
                                    { 'pointer-events-none': isBusy }
                                ]"
                                @click="selectDisplay(display.id)"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="80" viewBox="-1 -1 12 20" stroke="#c0a7a3" stroke-width=".25">
                                    <polygon v-for="segment in segments" :key="segment.id" 
                                            :id="segment.id" 
                                            :points="segment.points" 
                                            :class="{ 'active': display.activeSegments.includes(segment.id) }"
                                    />
                                </svg>
                            </div>
                            <!-- Vertical dots between hour and minute -->
                            <div v-if="display.id === 1" class="flex flex-col justify-center h-40 mx-4 gap-6">
                                <div class="w-3 h-3 bg-[#2b1d1d] rounded-full mb-2"></div>
                                <div class="w-3 h-3 bg-[#2b1d1d] rounded-full"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Text display for each screen -->
                    <div class="w-full h-20 p-6 flex select-none">
                        <div>
                            <p class="text-md m-0 w-[6rem]">Puzzle {{selectedPuzzle + 1}}:</p>
                        </div>
                        <div>
                            <p class="text-lg mt-0 font-mono">
                                <span v-html="puzzles[selectedPuzzle].text"></span>
                            </p>
                        </div>     
                    </div>

                    <!-- Dial -->
                    <div 
                        class="absolute right-[-6.8rem] top-[6.7rem] flex-shrink-0 w-44 h-44 cursor-pointer"
                        @click="handleRotate">
                        <div
                            class="w-full h-full bg-[url('/images/Chapter1/Alarm_Clock_Dial.png')] bg-cover bg-center transition-transform duration-300 ease-in-out"
                            :style="{ transform: `rotate(${rotation}deg)` }"
                        ></div>
                    </div>

                        
                    
                    <!-- Indicator -->
                     <div class="absolute right-[-5.5rem] top-0 w-32 flex justify-center items-center gap-2">
                        <div v-for="(puzzle, index) in puzzles" :key="index"
                            class="relative w-7 h-7">
                            <!-- New div for glow effect -->
                            <div
                                class="absolute inset-[-1px] rounded-full blur-[4px] z-10"
                                :class="{
                                    'bg-[#ff0000]': !puzzle.completed && isShaking && selectedPuzzle === index,
                                    'bg-[#f3b337]': !puzzle.completed && !isShaking && selectedPuzzle === index,
                                    'bg-green-500': puzzle.completed && selectedPuzzle === index,
                                    'opacity-0': selectedPuzzle !== index
                                }"
                            ></div>
                            <!-- Colored background div -->
                            <div
                                class="absolute inset-1 rounded-full z-20"
                                :class="{
                                    'bg-[#10ff68]': puzzle.completed && selectedPuzzle === index,
                                    'bg-[#ff4b4b]': !puzzle.completed && isShaking && selectedPuzzle === index,
                                    'bg-[#f9c216]': !puzzle.completed && !isShaking && selectedPuzzle === index,
                                    'bg-[#22c55e]': puzzle.completed && selectedPuzzle !== index,
                                    'bg-transparent': !puzzle.completed && selectedPuzzle !== index
                                }"
                            ></div>
                            <!-- Existing div for the light image with multiply effect -->
                            <div
                                class="absolute inset-0 bg-cover bg-center rounded-full z-30 mix-blend-multiply"
                                style="background-image: url('/images/Chapter1/Alarm_Clock_Light.png');"
                            ></div>
                        </div>
                    </div>
                </div>

               
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'

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

const rotation = ref(-40);

const angles = [-40, -15, 15, 40];

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
    
    // Select the corresponding puzzle without running the activation sequence
    selectPuzzle(puzzleId, false);
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
    { id: 0, completed: false, text: 'She too ate nothing for lunch.', answer: '2804' },
    { id: 1, completed: false, text: `
        <div style="display: flex; gap: 3%; align-items: center;">
            <img src="/images/Chapter1/Planet1.png" alt="Saturn" style="width: 30px; height: 30px;" />
            <img src="/images/Chapter1/Planet2.png" alt="Jupiter" style="width: 44px; height: 30px;" />
            <img src="/images/Chapter1/Planet3.png" alt="Earth" style="width: 30px; height: 30px;" />
            <img src="/images/Chapter1/Planet4.png" alt="Earth" style="width: 56px; height: 30px;" />
        </div>
    `, answer: '4736' },
    { id: 2, completed: false, text: 'JUMP on a phone', answer: '5867' },
    { id: 3, completed: false, text: 'If ▲ = ►, and N = Z,<br> then _ m ∞ <span style="display:inline-block; transform:rotate(90deg);">∟</span> = ?', answer: '1387' },
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

const selectPuzzle = async (id, runSequence = true) => {
    if (isBusy.value) return;
    
    isBusy.value = true;
    selectedPuzzle.value = id;
    isError.value = false;
    
    if (puzzles[id].completed) {
        setSolvedSequence(id)
    } else {
        resetDisplays();
    }

    isBusy.value = false;
}


const selectDisplay = (id) => {
    if (puzzles[selectedPuzzle.value].completed) return;
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
    if (puzzles[selectedPuzzle.value].completed) return;
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
        minuteValues[0] = (minuteValues[0] + 1) % 10; // Changed from % 6 to % 10
    }

    // Check for NaN and reset if necessary
    minuteValues[0] = isNaN(minuteValues[0]) ? 0 : minuteValues[0];
    minuteValues[1] = isNaN(minuteValues[1]) ? 0 : minuteValues[1];

    updateDisplays();
}

const startMinuteHold = () => {
    if (puzzles[selectedPuzzle.value].completed) return;
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

        // Set the solved sequence as the default display
        setSolvedSequence(selectedPuzzle.value)
    } else if (currentPuzzle.completed) {
        // If the puzzle is already completed, just show the solved sequence
        setSolvedSequence(selectedPuzzle.value)
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
    
    isBusy.value = false
}

const setSolvedSequence = (index) => {
    const sequence = getActivationSequence(index)[0]
    displays.forEach((display, displayIndex) => {
        display.activeSegments = sequence.segments[displayIndex] || []
    })
}

const getActivationSequence = (screenIndex) => {
    const sequences = [
        [
            { segments: [['b', 'c'], ['e', 'c'], ['b', 'f'], ['g']], duration: 2000 }
        ],
        [
            { segments: [['e'], ['g'], ['c'], ['f', 'c']], duration: 2000 }
        ],
        [
            { segments: [['g'], ['a', 'f'], ['g'], ['a']], duration: 2000 }
        ],
        [
            { segments: [['d'], ['b'], ['d'], ['d']], duration: 2000 }
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
            displays.forEach((display, displayIndex) => {
                // Activate specified segments for each display
                display.activeSegments = step.segments[displayIndex] || []
            })
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

const setInitialDisplay = (hours, minutes) => {
    hourValues[0] = Math.floor(hours / 10);
    hourValues[1] = hours % 10;
    minuteValues[0] = Math.floor(minutes / 10);
    minuteValues[1] = minutes % 10;
    
    displays.forEach((display, index) => {
        if (index < 2) {
            display.value = hourValues[index];
            display.activeSegments = digitToSegments[hourValues[index]] || [];
        } else {
            display.value = minuteValues[index - 2];
            display.activeSegments = digitToSegments[minuteValues[index - 2]] || [];
        }
    });

    updateUserInput();
}


onMounted(() => {
    window.addEventListener('keypress', handleKeyPress)
    document.addEventListener('click', handleScreenClick)
    setInitialDisplay(6, 45)
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
    fill: #f3c9c9;
}

polygon.active {
    fill: #2b1d1d;
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