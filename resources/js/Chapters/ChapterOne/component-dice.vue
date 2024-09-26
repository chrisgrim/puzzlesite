<template>
    <div>
        <div class="w-full h-[50rem]">
            <div 
                class="w-full relative h-[50rem] bg-[url('/images/Chapter1/Dice.jpg')] bg-cover bg-no-repeat">
            </div>
        </div>
        <div>
            <p>
                Centered among these letter dice sat two circular metal discs, one slightly smaller and perched atop the other. Their edges hinted at the possibility of rotation and alignment, a tantalizing mechanic begging to be explored. Completing this puzzling tableau was a small slate bearing nine seemingly random letters, its purpose yet another riddle to unravel.
            </p>
        </div>
        <div>
            <!-- Spinning cylinders component -->
            <div class="relative w-full h-[50rem] flex items-center justify-center bg-[url('/images/Chapter1/Rotating_Disc_Background.jpg')] bg-cover bg-no-repeat">
                <div 
                    class="absolute w-[44rem] h-[44rem] rounded-full bg-cover bg-center transition-transform duration-1000 ease-in-out cursor-pointer ml-[-37rem]"
                    :style="{ 
                        transform: `rotate(${bottomRotation}deg)`,
                        backgroundImage: 'url(/images/Chapter1/Rotating_Disc_Large.png)'
                    }"
                    @click="rotateBottom(45)"
                    @contextmenu.prevent="rotateBottom(-45)"
                >
                </div>
                <div 
                    class="absolute w-[28rem] h-[28rem] rounded-full bg-cover bg-center transition-transform duration-1000 ease-in-out cursor-pointer ml-[-37rem]"
                    :style="{ 
                        transform: `rotate(${topRotation}deg)`,
                        backgroundImage: 'url(/images/Chapter1/Rotating_Disc_Small.png)'
                    }"
                    @click="rotateTop(45)"
                    @contextmenu.prevent="rotateTop(-45)"
                >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const bottomRotation = ref(0);
const topRotation = ref(180);

const rotateBottom = (degree) => {
    bottomRotation.value = (bottomRotation.value + degree + 360) % 360;
};

const rotateTop = (degree) => {
    topRotation.value = (topRotation.value + degree + 360) % 360;
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>