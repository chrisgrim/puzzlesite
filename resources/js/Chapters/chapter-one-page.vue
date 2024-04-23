<template>
    <div class="w-full flex p-8 max-w-screen-lg relative m-auto">
        <div class="w-4/6 mt-40">
            <div class="w-60 border-b bg-black h-1"></div>
            <div class="my-10 mb-40">
                <h2 class="text-9xl">Chapter 1.</h2>
            </div>
            <div class="typewriter text-lg">
                <p class="text-lg font-semibold">This is a story about a man named Author Weaver...</p>
                <p class="mt-4 leading-relaxed">And his penchant for puzzles...</p>
                <p>Arthur's passion for puzzles was not merely a hobby, but a reflection of his view of the world—a world where every problem held a solution, every confusion a clarifying moment, if only one could find the right solution at the right time.
                As one would expect, Author lived in a world of puzzles and endless cryptograms. His apartment was a direct reflection of his mind—organized, intricate, and full of secrets waiting to be unlocked. Each corner of his living space was meticulously curated with puzzles that ranged from the ancient and mechanical to the modern and digital, each piece carefully selected not only for its complexity but also for its beauty.
                Walls lined with shelves displayed cryptic puzzle boxes crafted from exotic woods and metals, each bearing a history of mysteries solved and secrets contained. The tables were strewn with folded newspapers, their crosswords inked in halfway, awaiting completion. Above his desk, a large bulletin board was peppered with maps dotted with coded pins, charting unsolved mysteries and cryptic scavenger hunts that spanned the globe.
                </p>
                <span class="cursor">|</span>
            </div>
            <div class="relative min-h-28">
              <div class="flex absolute w-full">
                <div class="bg-stone-300 p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                    <a class="h-full flex items-center uppercase ml-[30rem]" href="/puzzles/1/1"><span class="text-5xl mr-10">01.</span><span class="font-source text-lg">Shaping Up</span></a>
                </div>
              </div>
            </div>

            <div class="typewriter text-lg">
                <p>Author's mornings began in this sanctuary, precisely at 6:45 AM when the soft melody of a custom-made puzzle alarm clock filled the room. The clock, a marvel of engineering festooned with enigmatic symbols, required a daily solution to silence its waking call. Each morning, Author decoded a sequence intricately designed into the mechanics of the clock, aligning gears and deciphering patterns that mirrored the complexity of his own thoughts.
                </p>
                <span class="cursor">|</span>
            </div>
            <div class="relative min-h-28">
              <div class="flex absolute w-full">
                <div class="bg-stone-300 p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                    <a class="h-full flex items-center uppercase ml-[30rem]" href="/puzzles/1/2"><span class="text-5xl mr-10">01.</span><span class="font-source text-lg">Shaping Up</span></a>
                </div>
              </div>
            </div>

            <div class="typewriter text-lg">
                <p>Author's mornings began in this sanctuary, precisely at 6:45 AM when the soft melody of a custom-made puzzle alarm clock filled the room. The clock, a marvel of engineering festooned with enigmatic symbols, required a daily solution to silence its waking call. Each morning, Author decoded a sequence intricately designed into the mechanics of the clock, aligning gears and deciphering patterns that mirrored the complexity of his own thoughts.
                </p>
                <span class="cursor">|</span>
            </div>
            <div class="relative min-h-28">
              <div class="flex absolute w-full">
                <div class="bg-stone-300 p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                    <a class="h-full flex items-center uppercase ml-[30rem]" href="/puzzles/3"><span class="text-5xl mr-10">01.</span><span class="font-source text-lg">Shaping Up</span></a>
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
