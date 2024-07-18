<template>
    <div class="w-[32rem] h-screen fixed left z-50 pl-32">
        <div class="relative h-full border-l border-stone-400">
            <div class="w-full flex flex-col">
                <ul class="m-0 mt-40">
                    <li class="border-t border-stone-400 p-2" v-for="chapter in chapters" :key="chapter.id">
                        <h2 class="text-md uppercase font-bold">Chapter {{ chapter.id }}: {{ chapter.title }}</h2>
                        <ul class="ml-4">
					        <li v-for="puzzle in chapter.puzzles" :key="puzzle.id" class="mt-2">
					            <div v-if="puzzle.is_accessible" :class="puzzle.is_accessible ? 'text-black' : 'text-gray-500'" class="flex justify-between items-center">
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
