<template>
    <div class="w-full flex flex-row items-center" :class="[isShaking ? 'shake' : '']">
        <div class="w-6 border-l-4 border-t-4 border-b-4 border-black h-40 rounded-2xl bg-gray-400" />
        <div class="w-full relative">
            <div class="trapezoidal-button h-[15rem] w-[calc(100%-1rem)] mx-auto justify-center gap-20 items-center flex bg-gray-500 rounded-2xl z-10 border-black border-4 absolute left-2 right-2">
                <button 
                    @click="updateHour"
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
                <button 
                    @click="resetScreens"
                    class="button-10 h-20 w-20 relative inline-block cursor-pointer outline-none border-0 align-middle text-inherit font-inherit font-semibold text-[#382b22] uppercase py-5 px-8 bg-white border-2 border-solid border-[#8d8d8d] rounded-full transform transition-all duration-150 ease-in-out hover:bg-[#ebebeb] hover:translate-y-1 active:bg-[#ffe9e9] active:translate-y-3 before:absolute before:content-[''] before:w-full before:h-full before:top-0 before:left-0 before:right-0 before:bottom-0 before:bg-[#c4c4c4] before:rounded-inherit before:shadow-[0_0_0_2px_#828282,0_0.625em_0_0_#929292] before:translate-y-3 before:-translate-z-4 before:transition-transform before:duration-150 before:ease-in-out"
                    role="button"
                >
                    <span class="text relative z-10">↺</span>
                </button>
            </div>
            <div class="flex flex-col items-center p-8 w-full border-4 border-black rounded-2xl bg-gray-400 z-20 mt-[13rem]">
                <div class="relative w-full h-16 mt-4">
                    <div class="screw absolute w-12 h-12 rounded-full border-[#3e3e3e] shadow-[0px_4px_0px_1px_rgba(0,0,0,0.5)] border-2">
                        <div class="indent h-3 w-[2.9rem] mt-4 bg-[url('img/nail-head.jpg')] shadow-[inset_0px_1px_8px_#222] rotate-[150deg] rounded-[2px] -ml-[2px] border-b border-solid border-[rgba(255,255,255,0.3)]"/>
                    </div>
                    <div class="screw absolute w-12 h-12 rounded-full border-[#3e3e3e] shadow-[0px_4px_0px_1px_rgba(0,0,0,0.5)] border-2 right-0">
                        <div class="indent h-3 w-[2.9rem] mt-4 bg-[url('img/nail-head.jpg')] shadow-[inset_0px_1px_8px_#222] rotate-[120deg] rounded-[2px] -ml-[2px] border-b border-solid border-[rgba(255,255,255,0.3)]"/>
                    </div>
                </div>

                <div class="w-full px-16">
                    <div class="flex w-full mb-4 bg-gray-800 shadow-[inset_0_10px_1px_3px_rgba(0,0,0,0.5)] rounded-2xl p-8 justify-center items-center">
                        <template v-for="(screen, index) in screens" :key="index">
                            <div 
                                class="w-52 h-80 mt-2 rounded-2xl flex flex-col items-center justify-center transition-all duration-200"
                                :class="[
                                    selectedScreen === index ? 'border-blue-500' : 'border-black'
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="192" height="320" viewBox="-1 -1 12 20" stroke="#19202b" stroke-width=".25">
                                    <polygon v-for="segment in segments" :key="segment.id" 
                                            :id="segment.id" 
                                            :points="segment.points" 
                                            :class="{ 'active': screen.activeSegments.includes(segment.id) }"
                                    />
                                </svg>
                            </div>
                            <!-- Vertical dots between hour and minute -->
                            <div v-if="index === 1" class="flex flex-col justify-center h-80 mx-4 gap-16">
                                <div class="w-6 h-6 bg-[#F00] rounded-full mb-2"></div>
                                <div class="w-6 h-6 bg-[#F00] rounded-full"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Text display for each screen -->
                    <div class="w-full bg-gray-800 rounded-2xl p-4  mt-12 shadow-[inset_0_8px_1px_1px_rgba(0,0,0,0.5)]">
                        <p class="text-lg font-bold text-white">{{ screens[selectedScreen].text }}</p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-4 mt-8">
                        <button 
                            v-for="(screen, index) in screens"
                            :key="index"
                            @click="selectScreen(index)"
                            class="button-10 relative inline-block cursor-pointer outline-none border-0 align-middle text-inherit font-inherit font-semibold text-[#382b22] uppercase py-5 px-8 bg-[#b4bdcc] border-2 border-solid border-[#8d8d8d] rounded-xl transform transition-all duration-150 ease-in-out hover:bg-[#ebebeb] hover:translate-y-1 active:bg-[#ffe9e9] active:translate-y-3 before:absolute before:content-[''] before:w-full before:h-full before:top-0 before:left-0 before:right-0 before:bottom-0 before:bg-[#c4c4c4] before:rounded-inherit before:shadow-[0_0_0_2px_#828282,0_0.625em_0_0_#929292] before:translate-y-3 before:-translate-z-4 before:transition-transform before:duration-150 before:ease-in-out"
                            role="button"
                        >
                            <span class="text relative z-10">Letter {{ index + 1 }}</span>
                        </button>
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

const answers = ['1111', '1222', '0303', '4444']
const selectedScreen = ref(0)
const userInput = ref('')
const buttonState = ref('')
const isShaking = ref(false)
const isError = ref(false)

const hourValues = reactive([0, 0])
const minuteValues = reactive([0, 0])

const minuteTimeoutId = ref(null)
const isHoldingMinute = ref(false)
const isMinutePressed = ref(false)
const holdDelay = 500

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
    10: ['a', 'b', 'c', 'd', 'e', 'f'], // Added for 10
    11: ['b', 'c'], // Added for 11
    12: ['a', 'b', 'd', 'e', 'g'] // Added for 12
}

const screens = reactive([
    { completed: false, activeSegments: [], text: 'THis is a puzzle. Please solve me by entering 1111 above.' },
    { completed: false, activeSegments: [], text: 'Hour (ones)' },
    { completed: false, activeSegments: [], text: 'Minute (tens)' },
    { completed: false, activeSegments: [], text: 'Minute (ones)' },
])

const buttonClass = computed(() => {
    if (buttonState.value === 'correct') return 'bg-green-500 text-white'
    if (buttonState.value === 'incorrect') return 'bg-red-500 text-white'
    return 'bg-blue-500 text-white'
})

const selectScreen = (index) => {
    selectedScreen.value = index
    isError.value = false
}

const updateHour = () => {
    hourValues[1] = (hourValues[1] + 1) % 13
    if (hourValues[1] === 0) {
        hourValues[1] = 1  // Skip 0, go directly to 1
    }
    if (hourValues[1] === 10) {
        hourValues[0] = 1  // Set tens digit to 1 for hours 10-12
    } else if (hourValues[1] < 10) {
        hourValues[0] = 0  // Set tens digit to 0 for hours 1-9
    }
    updateScreens()
}

const updateMinute = () => {
    minuteValues[1] = (minuteValues[1] + 1) % 10
    if (minuteValues[1] === 0) {
        minuteValues[0] = (minuteValues[0] + 1) % 6
    }
    updateScreens()
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

const updateScreens = () => {
    screens[0].activeSegments = digitToSegments[hourValues[0]] || []
    screens[1].activeSegments = digitToSegments[hourValues[1]] || []
    screens[2].activeSegments = digitToSegments[minuteValues[0]] || []
    screens[3].activeSegments = digitToSegments[minuteValues[1]] || []

    // Format hour to ensure it's always two digits (01-12)
    const formattedHour = hourValues[1] === 0 ? '12' : hourValues[1].toString().padStart(2, '0')
    const formattedMinute = `${minuteValues[0]}${minuteValues[1]}`
    userInput.value = `${formattedHour}${formattedMinute}`
    console.log('Current time:', userInput.value)  // Debug log
}

const resetScreens = () => {
    hourValues[0] = 0
    hourValues[1] = 0
    minuteValues[0] = 0
    minuteValues[1] = 0
    updateScreens()
}

const checkAnswer = () => {
    if (userInput.value === answers[selectedScreen.value]) {
        buttonState.value = 'correct'
        screens[selectedScreen.value].completed = true
        
        setTimeout(() => {
            buttonState.value = ''
        }, 1000)

        // Clear other screens and run activation sequence for the correct screen
        screens.forEach((screen, index) => {
            if (index !== selectedScreen.value) {
                screen.activeSegments = []
            }
        })
        runActivationSequence(selectedScreen.value)
    } else {
        buttonState.value = 'incorrect'
        isError.value = true
        isShaking.value = true
        setTimeout(() => {
            buttonState.value = ''
            isShaking.value = false
        }, 500)
    }
    
    if (screens.every(screen => screen.completed)) {
        alert('Congratulations! You completed all puzzles!')
    }
}

const getActivationSequence = (screenIndex) => {
    const sequences = [
        [
            { segments: ['e', 'f'], duration: 1000 },
            { segments: ['b', 'c'], duration: 1000 },
            { segments: ['g'], duration: 1000 },
            { segments: ['a', 'b', 'c', 'd', 'e', 'f'], duration: 500, allScreens: true }, 
            { segments: [], duration: 500, allScreens: true },
            { segments: ['a', 'b', 'c', 'd', 'e', 'f'], duration: 500, allScreens: true }, 
            { segments: [], duration: 500, allScreens: true }

        ],
        [
            { segments: ['a', 'b', 'c'], duration: 1000 },
            { segments: ['d', 'e', 'f'], duration: 1000 },
            { segments: ['g'], duration: 1000 },
            { segments: ['a', 'b', 'c', 'd', 'e', 'f'], duration: 500, allScreens: true }, // All screens show 0
            { segments: [], duration: 500, allScreens: true } // All screens blank

        ],
        [
            { segments: ['a', 'g'], duration: 1000 },
            { segments: ['b', 'f'], duration: 1000 },
            { segments: ['c', 'e'], duration: 1000 },
            { segments: ['a', 'b', 'c', 'd', 'e', 'f'], duration: 500, allScreens: true },
            { segments: [], duration: 500, allScreens: true },
            { segments: ['a', 'b', 'c', 'd', 'e', 'f'], duration: 500, allScreens: true }, 
            { segments: [], duration: 500, allScreens: true }

        ],
        [
            { segments: ['a', 'b', 'c', 'd'], duration: 1000 },
            { segments: ['e', 'f', 'g'], duration: 1000 },
            { segments: ['a', 'b', 'c', 'd', 'e', 'f'], duration: 500, allScreens: true }, // All screens show 0
            { segments: [], duration: 500, allScreens: true } // All screens blank

        ]
    ]

    return sequences[screenIndex]
}

const runActivationSequence = (index) => {
    const sequence = getActivationSequence(index)
    let currentStep = 0

    const runStep = () => {
        if (currentStep < sequence.length) {
            const step = sequence[currentStep]
            if (step.allScreens) {
                screens.forEach(screen => {
                    screen.activeSegments = [...step.segments]
                })
            } else {
                screens[index].activeSegments = step.segments
            }
            currentStep++
            setTimeout(runStep, step.duration)
        } else {
            updateScreens() // Reset to current values after sequence
        }
    }

    runStep()
}

onUnmounted(() => {
    if (minuteTimeoutId.value) {
        clearTimeout(minuteTimeoutId.value)
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