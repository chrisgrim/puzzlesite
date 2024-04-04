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
                <svg class="fill-current h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M10 12.566l-6.879-5.27a1 1 0 0 1 1.276-1.54L10 10.565l5.603-4.238a1 1 0 1 1 1.276 1.54L10 12.566z"/>
                </svg>
            </button>
            <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10" @click="closeDropdown">
                <a href="/user/login" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Login</a>
                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Register</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dropdownOpen: false
        };
    },
    methods: {
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },
        closeDropdown(event) {
            // Check if the clicked element is inside the dropdown wrapper
            if (!this.$refs.dropdownWrapper.contains(event.target)) {
                this.dropdownOpen = false;
            }
        },
        // Close dropdown when clicking outside of it
        handleOutsideClick(event) {
            if (!this.$refs.dropdownWrapper.contains(event.target)) {
                this.dropdownOpen = false;
            }
        }
    },
    mounted() {
        // Add event listener for clicks outside the dropdown menu
        document.addEventListener('click', this.handleOutsideClick);
    },
    beforeDestroy() {
        // Remove event listener when component is destroyed
        document.removeEventListener('click', this.handleOutsideClick);
    }
};
</script>
