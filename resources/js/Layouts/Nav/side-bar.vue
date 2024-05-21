<template>
    <div class="w-[30rem] h-screen fixed right-20 mt-40 z-50">
        <div class="relative h-full">
            <div class="absolute left-0 top-0 h-1/2 w-px bg-black"></div>
            <div class="w-full p-4 flex flex-col">
                <ul>
                    <li v-for="chapter in chapters" :key="chapter.id">
                        <div class="font-bold">Chapter {{ chapter.id }}: {{ chapter.title }}</div>
                        <ul class="ml-4">
					        <li v-for="puzzle in chapter.puzzles" :key="puzzle.id" class="mt-2">
					            <div :class="puzzle.is_accessible ? 'text-black' : 'text-gray-500'" class="flex justify-between items-center">
					                <div>
					                    <a v-if="puzzle.is_accessible" :href="'/puzzles/' + chapter.id + '/' + puzzle.order">
					                        {{ puzzle.title }}
					                    </a>
					                    <span v-else>
					                        {{ puzzle.title }} (Locked)
					                    </span>
					                </div>
					                <div v-if="puzzle.is_accessible && puzzle.is_solved" class="text-green-500">
					                    {{ puzzle.solution }}
					                </div>
					            </div>
					        </li>
					    </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        chapters: {
            type: Array,
            required: true,
        },
    },
};
</script>
