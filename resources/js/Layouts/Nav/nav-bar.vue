<template>
    <div class="justify-between items-center flex h-24 max-w-screen-lg mx-auto px-6">
        <div>
            <a href="/">
                <h2>Puzzle Site</h2>
            </a>
        </div>
        <div class="relative" ref="dropdownWrapper">
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center" @click="toggleDropdown">
                Profile
                <!-- SVG icon remains unchanged -->
            </button>
            <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                <!-- Conditionally render links based on user's authentication status -->
                <template v-if="user">
                    <a href="/user/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Profile</a>
                    <a href="/user/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Logout</a>
                </template>
                <template v-else>
                    <a href="/user/login" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Login</a>
                    <a href="/user/register" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Register</a>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineProps } from 'vue';

// Define props with defineProps
const props = defineProps({
    user: {
        type: [Object, null],
        default: () => null,
    },
});

const dropdownOpen = ref(false);
const dropdownWrapper = ref(null);

// Toggle dropdown method
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

// Close dropdown method
const closeDropdown = (event) => {
    if (!dropdownWrapper.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

// Method to handle outside click
const handleOutsideClick = (event) => {
    if (!dropdownWrapper.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

// Lifecycle hooks for mounted and beforeUnmount
onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener('click', handleOutsideClick);
});
</script>