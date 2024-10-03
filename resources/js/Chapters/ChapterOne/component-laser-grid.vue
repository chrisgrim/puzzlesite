<template>
    <div class="laser-grid relative">
        <div class="grid grid-cols-6">
            <div 
                v-for="(cell, index) in grid" 
                :key="index" 
                class="cell relative" 
                @click="rotateCell(index)">
                
                <!-- Rotating container with both image and cross -->
                <div :style="{ transform: `rotate(${cell.rotation}deg)` }" class="relative transition-transform duration-300">
                    <!-- Use images for each cell type -->
                    <img 
                        :src="getCellImage(cell.type)" 
                        :alt="cell.type" 
                        width="60" 
                        height="60"
                    />

                </div>

                <!-- Add SVG for emitter and lightbulb -->
                <svg v-if="cell.type === 'emitter' || cell.type === 'lightbulb'" 
                     :width="60" :height="60" 
                     class="absolute top-0 left-0">
                    <circle 
                        v-if="cell.type === 'emitter'" 
                        cx="30" cy="30" r="10" 
                        :fill="cell.color" stroke="white" stroke-width="2" />
                    <circle 
                        v-if="cell.type === 'lightbulb'" 
                        cx="30" cy="30" r="10" 
                        :fill="cell.hasLaser ? cell.color : cell.offColor" 
                        stroke="white" stroke-width="2" />
                </svg>
                
            </div>
        </div>
        <div class="laser-path-overlay">
            <svg :width="gridSize * cellSize" :height="gridSize * cellSize" xmlns="http://www.w3.org/2000/svg">
                <g v-for="(path, pathIndex) in laserPaths" :key="pathIndex">
                    <g v-for="(cell, cellIndex) in path.cells" :key="cellIndex">
                        <line 
                            :x1="cell.entry.x" 
                            :y1="cell.entry.y" 
                            :x2="cell.center.x" 
                            :y2="cell.center.y" 
                            :stroke="path.color" 
                            stroke-width="2"
                            class="laser-line"
                            :style="{
                                transitionDelay: `${cell.entryDelay}s`,
                                strokeDasharray: cell.entryLength,
                                strokeDashoffset: animationTriggered ? 0 : cell.entryLength
                            }"
                        />
                        <line v-for="(exit, exitIndex) in cell.exits" :key="exitIndex"
                            :x1="cell.center.x" 
                            :y1="cell.center.y" 
                            :x2="exit.x" 
                            :y2="exit.y" 
                            :stroke="path.color" 
                            stroke-width="2"
                            class="laser-line"
                            :style="{
                                transitionDelay: `${cell.exitDelay}s`,
                                strokeDasharray: exit.length,
                                strokeDashoffset: animationTriggered ? 0 : exit.length
                            }"
                        />
                    </g>
                </g>
            </svg>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted, computed } from 'vue';

const createCell = (type, color = null, offColor = null, rotation = 0) => ({
    type,
    color,
    offColor,
    rotation: Number(rotation),
    currentColor: null,
    openings: getOpeningsForType(type),
    hasLaser: false,
    laserPaths: [],
});

const animationTriggered = ref(false);
const entryDuration = .04; // Faster duration for entry-to-center animation
const exitDuration = 0.025;


const gridSize = 6;
const cellSize = 60;
const laserPaths = ref([]);

const getCellImage = (type) => {
    switch (type) {
        case 'corner': return '/images/Chapter1/Laser_Challenge_Corner.png';
        case 'straight': return '/images/Chapter1/Laser_Challenge_Flow.png';
        case 't-cross': return '/images/Chapter1/Laser_Challenge_T.png';
        case 'emitter': return '/images/Chapter1/Laser_Challenge_Emit.png';
        case 'lightbulb': return '/images/Chapter1/Laser_Challenge_Flow.png';
        case 'flow': return '/images/Chapter1/Laser_Challenge_Flow.png'; // New image for flow
        default: return '/images/default.png'; // Fallback image
    }
};


const getOpeningsForType = (type) => {
    switch (type) {
        case 'straight': return [0, 180];
        case 'corner': return [270, 0];
        case 't-cross': return [0, 90, 180];
        case 'emitter': return [0];
        case 'lightbulb': return [0, 90, 180, 270];
        case 'flow': return [0, 90, 180, 270]; // Flow allows all directions
        default: return [];
    }
};

const grid = ref([
    // Row 1
    createCell('corner', null, null, 90),
    createCell('flow'),
    createCell('flow'),
    createCell('corner', null, null, 90),
    createCell('lightbulb', 'green', '#82ff9b'),
    createCell('corner', null, null, 90),
    
    // Row 2
    createCell('t-cross'),
    createCell('lightbulb', 'green', '#82ff9b'),
    createCell('lightbulb', 'red', '#ffcccc'),
    createCell('t-cross'),
    createCell('flow'),
    createCell('t-cross'),
    
    // Row 3
    createCell('lightbulb', 'green', '#82ff9b'),
    createCell('corner', null, null, 90),
    createCell('corner', null, null, 90),
    createCell('emitter', 'green', null, 0),
    createCell('lightbulb', 'green', '#82ff9b'),
    createCell('corner', null, null, 90),
    
    // Row 4
    createCell('emitter', 'red', null, 0),
    createCell('t-cross'),
    createCell('t-cross'),
    createCell('lightbulb', 'red', '#ffcccc'),
    createCell('corner', null, null, 90),
    createCell('lightbulb', 'red', '#ffcccc'),
    
    // Row 5
    createCell('corner', null, null, 90),
    createCell('corner', null, null, 90),
    createCell('corner', null, null, 90),
    createCell('corner', null, null, 90),
    createCell('t-cross'),
    createCell('corner', null, null, 90),
    
    // Row 6
    createCell('lightbulb', 'blue', '#ccccff'),
    createCell('flow'),
    createCell('emitter', 'blue', null, 0),
    createCell('corner', null, null, 90),
    createCell('corner', null, null, 90),
    createCell('lightbulb', 'red', '#ffcccc')
]);



const isValidPath = (path) => {
    return path && path.start && path.end &&
        typeof path.start.x === 'number' && typeof path.start.y === 'number' &&
        typeof path.end.x === 'number' && typeof path.end.y === 'number';
};

const rotateCell = (index) => {
    const cell = grid.value[index];
    cell.rotation += 90;
    updateLaserPaths();
    console.log('----------------------------------------');
};

const updateLaserPaths = () => {
    grid.value.forEach(cell => {
        cell.currentColor = null;
        cell.hasLaser = false;
        cell.laserPaths = [];
    });

    laserPaths.value = []; // Clear previous paths

    grid.value.forEach((cell, index) => {
        if (cell.type === 'emitter') {
            const path = traceLaserPath(index);
            if (path.length > 0) {
                laserPaths.value.push({
                    color: cell.color,
                    cells: convertPathToCells(path)
                });
            }
        }
    });

    resetAnimation();
};

const traceLaserPath = (startIndex) => {
    const startCell = grid.value[startIndex];
    const color = startCell.color;
    const visitedCells = new Set();
    const maxDepth = gridSize * gridSize;
    const path = [];

    const propagateLaser = (index, entryDir, depth = 0) => {
        if (depth > maxDepth || visitedCells.has(index)) { return; }
        visitedCells.add(index);
        
        const currentCell = grid.value[index];
        if (!currentCell) { return; }
        
        // Check if it's a lightbulb
        if (currentCell.type === 'lightbulb') {
            // Light up the bulb if the laser color matches the bulb color
            if (currentCell.color === color) {
                currentCell.currentColor = color;
                currentCell.hasLaser = true;
            }
            return; // Stop propagation at any lightbulb, regardless of color
        }
        
        currentCell.currentColor = color;
        currentCell.hasLaser = true;
        
        const exitDirections = getExitDirections(currentCell, entryDir);
        
        path.push({
            cellIndex: index,
            entry: entryDir,
            exit: exitDirections
        });
        
        exitDirections.forEach(exitDir => {
            const [nextIndex, nextDirection] = getNextCell(index, exitDir);
            if (nextIndex !== -1 && nextDirection !== -1 && !visitedCells.has(nextIndex)) {
                propagateLaser(nextIndex, nextDirection, depth + 1);
            }
        });
    };
    
    propagateLaser(startIndex, (startCell.openings[0] + startCell.rotation) % 360);
    return path;
};

const getExitDirections = (cell, entryDir) => {
    const rotatedOpenings = cell.openings.map(opening => (opening + cell.rotation) % 360);

    switch (cell.type) {
        case 'straight':
            return rotatedOpenings.filter(opening => opening !== entryDir);

        case 'corner':
            return rotatedOpenings.filter(opening => opening !== (entryDir + 180) % 360);

        case 't-cross':

            // Default T-cross openings
            const defaultOpenings = [0, 90, 180];
            // Apply the rotation to these openings
            const rotatedTcrossOpenings = defaultOpenings.map(opening => (opening + cell.rotation) % 360);

            console.log('Rotated Openings for T-Cross:', rotatedTcrossOpenings);

            // Since you want to include all openings, just return the rotatedTcrossOpenings
            return rotatedTcrossOpenings;

        case 'flow':
            switch (entryDir) {
                case 270: return [90];
                case 90: return [270];
                case 0: return [180];
                case 180: return [0];
                default: return []; // Just in case of an unexpected entry direction
            }

        case 'lightbulb':
            return [];

        case 'emitter':
            return rotatedOpenings;

        default:
            return [];
    }
};


const getNextCell = (currentIndex, direction) => {
    const row = Math.floor(currentIndex / gridSize);
    const col = currentIndex % gridSize;

    let nextRow = row;
    let nextCol = col;

    switch (direction) {
        case 0: nextRow--; break; // Up
        case 90: nextCol++; break; // Right
        case 180: nextRow++; break; // Down
        case 270: nextCol--; break; // Left
    }

    if (nextRow < 0 || nextRow >= gridSize || nextCol < 0 || nextCol >= gridSize) {
        return [-1, -1];
    }

    const nextIndex = nextRow * gridSize + nextCol;
    const nextCell = grid.value[nextIndex];
    if (!nextCell) {
        return [-1, -1];
    }

    const entryDirection = (direction + 180) % 360;
    const rotatedOpenings = nextCell.openings.map(opening => (opening + nextCell.rotation) % 360);

    if (rotatedOpenings.includes(entryDirection)) {
        return [nextIndex, entryDirection];
    }

    return [-1, -1];
};

const lineLengths = computed(() => {
    return laserLines.value.map(line => 
        Math.sqrt(Math.pow(line.x2 - line.x1, 2) + Math.pow(line.y2 - line.y1, 2))
    );
});

const resetAnimation = () => {
    animationTriggered.value = false;
    setTimeout(() => {
        animationTriggered.value = true;
    }, 20);
};

const convertPathToCells = (path) => {
    let totalDelay = 0;
    return path.map(cell => {
        const row = Math.floor(cell.cellIndex / gridSize);
        const col = cell.cellIndex % gridSize;
        const centerX = col * cellSize + cellSize / 2;
        const centerY = row * cellSize + cellSize / 2;

        const entryPoint = getPointOnCellEdge(centerX, centerY, cell.entry);
        const entryLength = calculateLength(entryPoint.x, entryPoint.y, centerX, centerY);
        
        const exitDirections = Array.isArray(cell.exit) ? cell.exit : [cell.exit];
        const exits = exitDirections.map(exitDir => {
            const exitPoint = getPointOnCellEdge(centerX, centerY, exitDir);
            return {
                x: exitPoint.x,
                y: exitPoint.y,
                length: calculateLength(centerX, centerY, exitPoint.x, exitPoint.y)
            };
        });

        const cellData = {
            entry: entryPoint,
            center: { x: centerX, y: centerY },
            exits: exits,
            entryLength: entryLength,
            entryDelay: totalDelay,
            exitDelay: totalDelay + entryDuration,
            entryDuration: entryDuration,
            exitDuration: exitDuration
        };

        totalDelay += entryDuration + exitDuration * exits.length;
        return cellData;
    });
};

const laserCells = computed(() => {
    const cells = [];
    let totalDelay = 0;


    laserPath.value.forEach(cell => {
        const row = Math.floor(cell.cellIndex / gridSize);
        const col = cell.cellIndex % gridSize;
        const centerX = col * cellSize + cellSize / 2;
        const centerY = row * cellSize + cellSize / 2;

        const entryPoint = getPointOnCellEdge(centerX, centerY, cell.entry);
        const entryLength = calculateLength(entryPoint.x, entryPoint.y, centerX, centerY);
        
        const exitDirections = Array.isArray(cell.exit) ? cell.exit : [cell.exit];
        const exits = exitDirections.map(exitDir => {
            const exitPoint = getPointOnCellEdge(centerX, centerY, exitDir);
            return {
                x: exitPoint.x,
                y: exitPoint.y,
                length: calculateLength(centerX, centerY, exitPoint.x, exitPoint.y)
            };
        });

        cells.push({
            entry: entryPoint,
            center: { x: centerX, y: centerY },
            exits: exits,
            entryLength: entryLength,
            entryDelay: totalDelay,
            exitDelay: totalDelay + entryDuration, // Start exit animation right after entry completes
            entryDuration: entryDuration,
            exitDuration: exitDuration,
            color: grid.value[cell.cellIndex].currentColor // Use the cell's current color
        });

        totalDelay += entryDuration + exitDuration * exits.length;
    });
    return cells;
});

const calculateLength = (x1, y1, x2, y2) => {
    return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
};



const getPointOnCellEdge = (centerX, centerY, direction) => {
    const halfCell = cellSize / 2;
    switch(direction) {
        case 0: return { x: centerX, y: centerY - halfCell }; // Top
        case 90: return { x: centerX + halfCell, y: centerY }; // Right
        case 180: return { x: centerX, y: centerY + halfCell }; // Bottom
        case 270: return { x: centerX - halfCell, y: centerY }; // Left
        default: return { x: centerX, y: centerY };
    }
};





onMounted(() => {
    updateLaserPaths();
});
</script>

<style scoped>
.laser-grid {
    /* Add any necessary styling */
}
.cell {
    width: 60px;
    height: 60px;
    user-select: none; /* Prevent text/image selection */
    -webkit-user-select: none; /* For Safari */
    -ms-user-select: none; /* For older IE versions */
}

.laser-path-overlay {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 10;
  pointer-events: none; /* This allows clicks to pass through to the grid beneath */
}

.transition-transform {
    transition: transform 0.3s ease-in-out;
}

.laser-line {
    transition: stroke-dashoffset 0.1s linear;
}

.animate-laser {
    animation-play-state: running;
}

.cell svg:first-child {
    opacity: 0.5;
}
</style>