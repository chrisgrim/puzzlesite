<template>
    <div class="w-full flex p-8 max-w-screen-lg relative m-auto">
        <div class="w-4/6 mt-40">
            <div class="w-60 border-b bg-black h-1"></div>
            <div class="my-10 mb-40">
                <h2 class="text-9xl">Chapter {{ chapter.id }}.</h2>
            </div>
            <div class="typewriter text-lg">
                <p>Today, however, Author's attention was half on the puzzle and half on the morning news flickering on the small TV across his modest kitchen. The broadcast was troubling, centering not on routine city affairs but on a sudden and mysterious proliferation of graffiti that had appeared overnight across the city's walls. Intricate, cryptic sentences and symbols had been spray-painted in vibrant colors, turning the city into a canvas of unsolicited art. The contrast between the orderly black and white grid of his crossword and the colorful disarray of the world outside formed a dissonant backdrop to his morning.</p>
                <p>As Author inked in the answers to his puzzle, the city’s teams were already out there, painting over the vibrant graffiti, erasing the unsolved mystery as quickly as it had appeared. This unprecedented response from local agencies, which under normal circumstances could take months to replace even the smallest pothole, marked a stark departure from their usual sluggish pace.</p>
                <p>The usual bureaucratic lethargy, characterized by endless paperwork and interdepartmental wrangling, had vanished overnight, replaced by a swift, almost alarmist efficiency. Trucks and crews, usually a rare sight except in the aftermath of public outcry or media scrutiny, were now conspicuously present at every tagged site. High-powered sprayers erased the sprayed-on text before the paint had a chance to settle into the bricks and mortar of the city’s infrastructure.</p>
                <p>As Author watched the cleanup operation unfold, he paused the television on a brief shot that managed to slip through the otherwise tightly controlled news coverage. Behind a row of police officers, who stood guarding the scene as if the graffiti were a crime scene, the vibrant hues of the graffiti were just visible. What the hurried sweep of the camera caught was a wall painted with elaborate, swirling patterns that seemed random to the untrained eye. But to Author, a man who had dedicated countless hours to unraveling the most complex of puzzles, the configuration struck a familiar chord.</p>
                {{ displayText }}
                <span class="cursor">|</span>
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