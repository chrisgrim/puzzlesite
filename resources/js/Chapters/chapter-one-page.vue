<template>
    <div class="w-full flex p-8 max-w-screen-lg relative">
        <div class="w-4/6 mt-20">
            <div class="my-10 mb-40">
                <h1 class="text-8xl mt-20">Chapter {{ chapter.id }}: <br>{{ chapter.title }}</h1>
            </div>
            <div class="typewriter text-lg">
                <p style="white-space: pre-wrap;">Arthur's passion for puzzles was not merely a hobby, but a reflection of his view of the world—a world where every problem held a solution, every confusion a clarifying moment, if only one could find the right solution at the right time.<br>
                As one would expect, Author lived in a world of puzzles and endless cryptograms. His apartment was a direct reflection of his mind—organized, intricate, and full of secrets waiting to be unlocked. Each corner of his living space was meticulously curated with puzzles that ranged from the ancient and mechanical to the modern and digital, each piece carefully selected not only for its complexity but also for its beauty.
                Walls lined with shelves displayed cryptic puzzle boxes crafted from exotic woods and metals, each bearing a history of mysteries solved and secrets contained. The tables were strewn with folded newspapers, their crosswords inked in halfway, awaiting completion. Above his desk, a large bulletin board was peppered with maps dotted with coded pins, charting unsolved mysteries and cryptic scavenger hunts that spanned the globe.
                </p>
            </div>

            <PuzzleItem 
                :puzzle="chapter.puzzles[0]" 
                :chapterId="chapter.id"
            />

            <div class="typewriter text-lg">
                <p>Author's mornings began in this sanctuary, precisely at 6:45 AM when the soft melody of a custom-made puzzle alarm clock filled the room. The clock, a marvel of engineering festooned with enigmatic symbols, required a daily solution to silence its waking call. Each morning, Author decoded a sequence intricately designed into the mechanics of the clock, aligning gears and deciphering patterns that mirrored the complexity of his own thoughts.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import PuzzleItem from '@/Global/puzzleItem.vue';

const props = defineProps({
    chapter: {
        type: Object,
        required: true,
    },
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