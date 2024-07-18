<template>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Manage Puzzles</h1>

        <!-- Add New Chapter Form -->
        <div class="bg-white shadow-md rounded p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-center">Add New Chapter</h2>
            <form @submit.prevent="addChapter" class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="chapterTitle">Title</label>
                    <input v-model="newChapter.title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="chapterTitle" type="text" placeholder="Title" required>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Add Chapter</button>
            </form>
        </div>

        <!-- Add New Puzzle Form -->
        <div class="bg-white shadow-md rounded p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-center">Add New Puzzle</h2>
            <form @submit.prevent="addPuzzle" class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="title">Title</label>
                    <input v-model="newPuzzle.title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" placeholder="Title" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="description">Description</label>
                    <textarea v-model="newPuzzle.description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" placeholder="Description" required></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="solution">Solution</label>
                    <input v-model="newPuzzle.solution" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="solution" type="text" placeholder="Solution">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="difficulty">Difficulty</label>
                    <input v-model="newPuzzle.difficulty" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="difficulty" type="text" placeholder="Difficulty">
                </div>
                <div class="col-span-full">
                    <label class="block text-gray-700 font-bold mb-2" for="chapter">Chapter</label>
                    <select v-model="newPuzzle.chapter_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="chapter" required>
                        <option value="" disabled>Select a Chapter</option>
                        <option v-for="chapter in chapters" :key="chapter.id" :value="chapter.id">{{ chapter.title }}</option>
                    </select>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4 md:mt-0 col-span-full" type="submit">Add Puzzle</button>
            </form>
        </div>

        <!-- Puzzles by Chapter -->
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-center">Puzzles by Chapter</h2>
            <div v-for="chapter in chapters" :key="chapter.id" class="mb-8">
                <div class="flex mb-4">
                    <h1>{{chapter.order}}</h1>
                    <input v-model="chapter.title" @change="updateChapter(chapter)" class="text-3xl bg-transparent focus:border-blue-500 focus:outline-none w-1/2" type="text" placeholder="Edit Chapter Title">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white shadow-md rounded">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase leading-normal">
                                <th class="py-3 px-6 text-left w-1/12">Title</th>
                                <th class="py-3 px-6 text-left w-3/12">Description</th>
                                <th class="py-3 px-6 text-left w-1/12">Solution</th>
                                <th class="py-3 px-6 text-center w-1/12">Difficulty</th>
                                <th class="py-3 px-6 text-center w-1/12">Actions</th>
                            </tr>
                        </thead>
                        <draggable v-model="chapter.puzzles" @end="reorderPuzzles(chapter)" tag="tbody" :component-data="{
                            class: 'text-gray-600 font-light'
                        }">
                            <template #item="{ element: puzzle }">
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap w-1/12">
                                        {{puzzle.order}}
                                        <input v-model="puzzle.title" @change="updatePuzzle(puzzle)" class="bg-transparent focus:border-blue-500 focus:outline-none w-full" type="text">
                                    </td>
                                    <td class="py-3 px-6 text-left w-2/6">
                                        <textarea v-model="puzzle.description" @change="updatePuzzle(puzzle)" class="bg-transparent focus:border-blue-500 focus:outline-none w-full" rows="2"></textarea>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap w-1/12">
                                        <input v-model="puzzle.solution" @change="updatePuzzle(puzzle)" class="bg-transparent focus:border-blue-500 focus:outline-none w-full" type="text">
                                    </td>
                                    <td class="py-3 px-6 text-center w-1/12">
                                        <input v-model="puzzle.difficulty" @change="updatePuzzle(puzzle)" class="bg-transparent focus:border-blue-500 focus:outline-none text-center w-full" type="text">
                                    </td>
                                    <td class="py-3 px-6 text-center w-1/12">
                                        <button @click="deletePuzzle(puzzle)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Delete</button>
                                    </td>
                                </tr>
                            </template>
                        </draggable>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import draggable from 'vuedraggable';

const newChapter = ref({ title: '' });
const newPuzzle = ref({
    title: '',
    description: '',
    solution: '',
    difficulty: null,
    chapter_id: null
});

const chapters = ref([]);

const fetchChapters = async () => {
    try {
        const response = await axios.get('/api/admin/chapters');
        chapters.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const addChapter = async () => {
    try {
        // Determine the next order number
        const nextOrder = chapters.value.length > 0 
            ? Math.max(...chapters.value.map(chapter => chapter.order)) + 1 
            : 1;

        // Add the new chapter with the determined order number
        await axios.post('/api/admin/chapter', { 
            ...newChapter.value, 
            order: nextOrder 
        });

        fetchChapters(); // Refresh the chapter list
        newChapter.value.title = '';
    } catch (error) {
        console.error(error);
    }
};

const addPuzzle = async () => {
    try {
        await axios.post('/api/admin/puzzles', newPuzzle.value);
        fetchChapters(); // Refresh the chapter list
        newPuzzle.value = {
            title: '',
            description: '',
            solution: '',
            difficulty: null,
            chapter_id: null
        };
    } catch (error) {
        console.error(error);
    }
};

const updateChapter = async (chapter) => {
    try {
        await axios.put(`/api/admin/chapters/${chapter.id}`, chapter);
    } catch (error) {
        console.error(error);
    }
};

const updatePuzzle = async (puzzle) => {
    try {
        await axios.put(`/api/admin/puzzles/${puzzle.order}`, puzzle);
    } catch (error) {
        console.error(error);
    }
};

const deletePuzzle = async (puzzle) => {
    try {
        await axios.delete(`/api/admin/puzzles/${puzzle.id}`);
        fetchChapters(); // Refresh the chapter list
    } catch (error) {
        console.error(error);
    }
};

const reorderPuzzles = async (chapter) => {
    try {
        const updatedPuzzles = chapter.puzzles.map((puzzle, index) => ({
            id: puzzle.id,
            order: index + 1 // Start order from 1
        }));

        await axios.put(`/api/admin/chapters/${chapter.id}/reorder`, { puzzles: updatedPuzzles });

        // Update local order immediately
        chapter.puzzles = chapter.puzzles.map((puzzle, index) => ({
            ...puzzle,
            order: index + 1 // Start order from 1
        }));
    } catch (error) {
        console.error(error);
    }
};


onMounted(fetchChapters);
</script>

<style>
/* Add any additional styles here */
</style>
