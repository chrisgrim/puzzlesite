<template>
    <div class="w-full flex p-8 max-w-screen-lg relative m-auto">
        <div class="w-4/6 mt-40">
            <div class="w-60 border-b bg-black h-1"></div>
            <div class="my-10 mb-40">
                <h2 class="text-9xl">Chapter {{ chapter.id }}.</h2>
            </div>
            <div class="typewriter text-lg">
                <p class="text-lg font-semibold">This is a story about a man named Author Weaver...</p>
                <p class="mt-4 leading-relaxed">And his penchant for puzzles...</p>
                <p style="white-space: pre-wrap;">Arthur's passion for puzzles was not merely a hobby, but a reflection of his view of the world—a world where every problem held a solution, every confusion a clarifying moment, if only one could find the right solution at the right time.<br>

                As one would expect, Author lived in a world of puzzles and endless cryptograms. His apartment was a direct reflection of his mind—organized, intricate, and full of secrets waiting to be unlocked. Each corner of his living space was meticulously curated with puzzles that ranged from the ancient and mechanical to the modern and digital, each piece carefully selected not only for its complexity but also for its beauty.
                Walls lined with shelves displayed cryptic puzzle boxes crafted from exotic woods and metals, each bearing a history of mysteries solved and secrets contained. The tables were strewn with folded newspapers, their crosswords inked in halfway, awaiting completion. Above his desk, a large bulletin board was peppered with maps dotted with coded pins, charting unsolved mysteries and cryptic scavenger hunts that spanned the globe.
                </p>
            </div>

            <div v-for="puzzle in chapter.puzzles" :key="puzzle.id" class="relative min-h-28">
                <div class="flex absolute w-full">
                    <div :class="puzzle.is_accessible ? 'bg-stone-300' : 'bg-gray-300'" class="p-4 flex items-center mt-4 w-[60rem] ml-[-30rem]">
                        <a v-if="puzzle.is_accessible" class="h-full flex items-center uppercase ml-[30rem]" :href="'/puzzles/' + chapter.id + '/' + puzzle.order">
                            <span class="text-5xl mr-10">{{ puzzle.order < 10 ? '0' + puzzle.order : puzzle.order }}.</span>
                            <span class="font-source text-lg">{{ puzzle.title }}</span>
                        </a>
                        <div v-else class="h-full flex items-center uppercase ml-[30rem]">
                            <span class="text-5xl mr-10">{{ puzzle.order < 10 ? '0' + puzzle.order : puzzle.order }}.</span>
                            <span class="font-source text-lg">{{ puzzle.title }}</span>
                            <span class="text-red-500 ml-4">(Locked)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="typewriter text-lg">
                <p>Author's mornings began in this sanctuary, precisely at 6:45 AM when the soft melody of a custom-made puzzle alarm clock filled the room. The clock, a marvel of engineering festooned with enigmatic symbols, required a daily solution to silence its waking call. Each morning, Author decoded a sequence intricately designed into the mechanics of the clock, aligning gears and deciphering patterns that mirrored the complexity of his own thoughts.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        chapter: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        return {
            chapter: props.chapter,
        };
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