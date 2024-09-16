<template>
    <div class="relative w-full">
        <div>
        	<div class="absolute top-0">
                <img src="/images/puzzle-graffiti-back_01.png" class="w-full h-auto">
            </div>
            <div class="h-screen">
                <div class="flex h-full items-center justify-between mb-8">
                    <!-- Square 1: Red with triangle -->
                    <div class="w-40 h-40 bg-red-500 relative">
                        <svg viewBox="0 0 100 100" class="absolute inset-0 w-full h-full">
                            <polygon points="0,0 0,50 50,50" fill="white" />
                        </svg>
                    </div>
                    
                    <!-- Square 2: Blue with half-height square -->
                    <div class="w-40 h-40 bg-red-500 relative">
					    <svg viewBox="0 0 100 100" class="absolute inset-0 w-full h-full">
					        <polygon points="50,50 100,0 100,100 50,100" fill="white" />
					    </svg>
					</div>
                    
                    <div class="w-40 h-40 bg-red-500 relative">
					    <svg viewBox="0 0 100 100" class="absolute inset-0 w-full h-full">
					        <polygon points="0,100 100,100 50,50 0,50" fill="white" />
					    </svg>
					</div>
                    
                    <!-- Square 4: Yellow with diagonal line -->
                    <div class="w-40 h-40 bg-red-500 relative">
                        <svg viewBox="0 0 100 100" class="absolute inset-0 w-full h-full">
                            <polygon points="0,0 0,50 50,50" fill="white" />
                        </svg>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script setup>
// You can add any necessary imports or logic here
</script>