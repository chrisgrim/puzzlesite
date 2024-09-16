<template>
    <div class="relative p-16 w-full">
        <div class="absolute top-[-3rem] right-[-6rem] z-10 w-60 h-60 rounded-tl-[50px] bg-[#fefabc] p-6 shadow-[0_8px_10px_-7px_black] overflow-hidden
                  after:content-[''] after:absolute after:bottom-0 after:right-0 after:w-full after:h-[10px] after:rounded-br-[60px]
                  transform rotate-[-15deg] origin-top-right">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-100/30 via-yellow-200/20 to-yellow-300/30"></div>
            <div class="absolute -top-[1px] -left-[1px] w-[45px] h-[45px] 
                        bg-[linear-gradient(135deg,_#e7e5e4_50%,_transparent_50%)]
                        shadow-[3px_3px_2px_#dbd581]">
            </div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(0,0,0,0.05)_0%,_rgba(0,0,0,0)_70%)] pointer-events-none"></div>
            <h2 class="text-center font-['Reenie_Beanie'] text-2xl mt-20 relative z-10">check out unusual login</h2>
        </div>
        <div class="poster relative w-full p-[7%] bg-[#231f20] border-white border-[2rem] shadow-lg saturate-[70%] contrast-[85%] overflow-hidden">
            <!-- Grid section -->
            <div class="grid grid-cols-9 gap-2 relative w-full z-10">
                <div
                    v-for="(letter, index) in lettersGrid"
                    :key="index"
                    class="font-sans text-2xl p-2 text-center w-full text-white h-10 w-10 flex items-center justify-center"
                >
                    {{ letter || ' ' }}
                </div>
            </div>
            
            <!-- Text section -->
            <div class="mt-4 text-black relative z-10">
                <div class="flex h-40 w-80 items-center gap-4">
                    <svg class="w-1/3 h-40" viewBox="0 0 180 180" preserveAspectRatio="xMidYMid meet">
                        <polygon points="0 0, 180 0, 180 180, 120 180, 120 120, 60 120, 60 60, 0 60" fill="white"></polygon>
                    </svg>
                    <div class="flex flex-col gap-2">
                        <span class="text-white text-7xl">ASCII</span>
                        <p class="text-white mt-0 text-md">and you shall receive</p>
                    </div>
                </div>
            </div>
            <!-- Edge effects -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/10 via-black/10 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-black/5 to-transparent"></div>
            <div class="absolute top-0 left-0 w-1/2 h-1/3 bg-gradient-to-br from-black/15 to-transparent"></div>
            <div class="absolute top-0 right-0 w-1/2 h-1/3 bg-gradient-to-bl from-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-1/2 h-1/3 bg-gradient-to-tr from-black/15 to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-1/2 h-1/3 bg-gradient-to-tl from-black/15 to-transparent"></div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const lettersGrid = ref([
    '8', '4', '', '1', '0', '4', '', '1', '0',
    '1', '', '3', '2', '', '1', '1', '1', '',
    '1', '1', '2', '', '1', '1', '1', '', '1',
    '0', '5', '', '1', '1', '6', '', '1', '0',
    '1', '', '1', '1', '5', '', '1', '1', '6',
    '', '1', '0', '1', '', '3', '2', '', '1',
    '1', '1', '', '1', '1', '1', '', '1', '1',
    '1', '', '1', '1', '5', '', '1', '0', '2',
    '', '3', '2', '', '1', '1', '1', '', '1'
]);
</script>

<style scoped>
.poster::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 4%;
    left: 0;
    bottom: -4%;
    background-repeat: no-repeat;
    background-image: 
        linear-gradient(177deg, rgba(0,0,0,0.22) 10%, transparent 50%),
        linear-gradient(-177deg, rgba(0,0,0,0.22) 10%, transparent 50%);
    background-size: 49% 100%;
    background-position: 2% 0, 98% 0;
}
</style>