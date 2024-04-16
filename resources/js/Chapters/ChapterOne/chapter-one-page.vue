<template>
    <div class="w-full flex p-8 max-w-screen-lg relative m-auto">
        <div class="w-4/6 mt-40">
            <div class="w-60 border-b bg-black h-1"></div>
            <div class="my-10 mb-40">
                <h2 class="text-9xl">Chapter 1.</h2>
            </div>
            <div class="typewriter text-lg">
                <p>In the quiet routine of his life, Walter Finch lived amidst the steady tick-tock of the clock. He was a man of habit, navigating through his days with a meticulousness that some might find dull. Yet beneath his unremarkable exterior, Walter possessed a mind obsessed. You see, Authur loved a good puzzle</p>
                <br>
                <p>Anytype of puzzle, crossword, etc . Author loved them all</p>
                <br>
                <p>To some, Arthur might be considered dull, a man whose life was as predictable as the tick of a clock, each second a step in a dance he had performed day in and day out for years. His days were a sequence of habits, a routine so ingrained that it seemed as if he had been born middle-aged, with a predilection for puzzles and a life devoid of surprises.</p>
                {{ displayText }}
                <span class="cursor">|</span>
            </div>
            <div class="relative min-h-40">
              <div class="flex flex-col absolute w-full">
                <div class="bg-stone-300 p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                    <a class="h-full flex items-center uppercase ml-[30rem]" href="/chapter-one/puzzle-one"><span class="text-5xl mr-10">01.</span><span class="font-source text-lg">Puzzle One</span></a>
                </div>
                <div class="bg-stone-300 p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                    <a class="h-full flex items-center uppercase ml-[30rem]" href="/chapter-one/puzzle-two"><span class="text-5xl mr-10">01.</span><span class="font-source text-lg">Puzzle Two</span></a>
                </div>
                <div class="bg-stone-300 p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                    <a class="h-full flex items-center uppercase ml-[30rem]" href="/chapter-one/puzzle-three"><span class="text-5xl mr-10">01.</span><span class="font-source text-lg">Puzzle Three</span></a>
                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: "",
            displayText: "",
            index: 0,
        };
    },
    mounted() {
        this.typeWriter();
    },
    methods: {
        typeWriter() {
            if (this.index < this.message.length) {
                this.displayText += this.message.charAt(this.index);
                this.index++;
                setTimeout(this.typeWriter, 15); // Adjust for typing speed
            }
        },
    },
};
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
