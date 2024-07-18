<template>
    <div class="w-full fixed border-b border-stone-400 z-50 bg-stone-200">
        <div class="justify-between items-center flex h-32 max-w-screen-lg mx-auto px-6">
            <div>
                <a href="/">
                    <div class="relative h-32 w-32 flex items-center justify-center">
                        <div class="absolute bg-stone-500 rounded-full h-16 w-16 opacity-50" style="left: 0%;"></div>
                        <div class="absolute bg-stone-500 rounded-full h-16 w-16 opacity-50" style="right: 10%;"></div>
                    </div>
                </a>
            </div>
            <div class="relative" ref="dropdownWrapper" v-if="user">
                <div class="text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center cursor-pointer" @click="toggleDropdown">
                    <h2 class="hover:underline-animation">The Reader</h2>
                </div>
                <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 overflow-hidden shadow-xl z-10 bg-stone-200">
                    <a href="/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-400">Profile</a>
                    <a href="/admin" class="block px-4 py-2 text-gray-800 hover:bg-gray-400">admin</a>
                    <div @click="logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-400 cursor-pointer">Logout</div>
                </div>
            </div>
            <div class="relative" v-else>
                <div class="text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center cursor-pointer" @click="toggleDropdown">
                    <a href="/enter-the-story">
                        <h2>Enter</h2>
                    </a>
                </div>
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

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const logout = async () => {
    await axios.post('/logout');
    location.reload();
};

const closeDropdown = (event) => {
    if (!dropdownWrapper.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

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

<style>
    @keyframes underline-grow {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}

.hover\:underline-animation:hover::after {
    content: '';
    display: block;
    height: 1px;
    background-color: black; /* Customize the color as needed */
    animation: underline-grow 1s forwards;
    position: absolute;
    bottom: -2px; /* Adjust this value based on your layout */
    left: 0;
}

.hover\:underline-animation::after {
    content: '';
    display: block;
    width: 0;
    height: 1px;
    background-color: black; /* Customize the color as needed */
    position: absolute;
    bottom: -2px; /* Adjust this value based on your layout */
    left: 0;
}
</style>