<template>
  <div class="game-container flex flex-col items-center mt-5" @mouseup="stopRotation">
    <div class="grid grid-cols-6 gap-0.5">
      <div v-for="(row, rowIndex) in grid" :key="rowIndex" class="grid-row">
        <div
          v-for="(cell, cellIndex) in row"
          :key="cellIndex"
          class="grid-cell relative"
          :class="{
            'w-12 h-12 flex items-center justify-center': true,
            'bg-brown-600': cell.isPath,
            'bg-blue-500': cell.isUser,
            'bg-yellow-400': cell.isStar
          }"
          @mousedown="startRotation($event)"
        >
          <div class="dot absolute bg-black rounded-full w-1 h-1" :style="{ top: '50%', left: '50%', transform: 'translate(-50%, -50%)' }"></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'GridGame',
  data() {
    return {
      grid: [],
      userPosition: { x: 0, y: 0 },
      gridSize: 6,
      isRotating: false,
      initialAngle: 0,
      currentRotationDegrees: 0, // Tracks current rotation relative to initial
      lastSnapRotation: 0, // Tracks last snapped rotation as a stable point
      gridElement: null,
    };
  },
  mounted() {
    this.initializeGrid();
    this.placeUser();
    this.placeStar();
    this.gridElement = document.querySelector('.grid'); // Assign grid element after mount
    window.addEventListener('mousemove', this.handleMouseMove);
    window.addEventListener('mouseup', this.stopRotation);
  },
  beforeDestroy() {
    window.removeEventListener('mousemove', this.handleMouseMove);
    window.removeEventListener('mouseup', this.stopRotation);
  },
  methods: {
    initializeGrid() {
      this.grid = Array.from({ length: this.gridSize }, () => Array.from({ length: this.gridSize }, () => ({
        isPath: Math.random() > 0.5,
        isUser: false,
        isStar: false
      })));
    },
    placeUser() {
      this.userPosition = { x: Math.floor(this.gridSize / 2), y: Math.floor(this.gridSize / 2) };
      this.grid[this.userPosition.y][this.userPosition.x].isUser = true;
    },
    placeStar() {
      let starPosition = { x: this.gridSize - 1, y: this.gridSize - 1 };
      this.grid[starPosition.y][starPosition.x].isStar = true;
    },
    startRotation(event) {
      if (!this.isRotating) {
        this.isRotating = true;
        this.initialAngle = this.getMouseAngle(event);
        this.currentRotationDegrees = this.lastSnapRotation; // Start from last snapped position
      }
    },
    handleMouseMove(event) {
      if (this.isRotating) {
        let currentAngle = this.getMouseAngle(event);
        let angleDelta = currentAngle - this.initialAngle;
        this.currentRotationDegrees += angleDelta;
        this.initialAngle = currentAngle; // Reset initial angle to new current angle after update
        this.gridElement.style.transform = `rotate(${this.currentRotationDegrees}deg)`;
      }
    },
    stopRotation() {
      if (this.isRotating) {
        this.isRotating = false;
        let snapAngle = Math.round(this.currentRotationDegrees / 90) * 90;
        this.gridElement.style.transform = `rotate(${snapAngle}deg)`;
        this.applyRotation(snapAngle);
        this.lastSnapRotation = snapAngle; // Update last snapped rotation
        this.currentRotationDegrees = snapAngle; // Reset current rotation to the snapped value
      }
    },
    getMouseAngle(event) {
      let rect = this.gridElement.getBoundingClientRect();
      let centerX = rect.left + rect.width / 2;
      let centerY = rect.top + rect.height / 2;
      let deltaX = event.clientX - centerX;
      let deltaY = event.clientY - centerY;
      return Math.atan2(deltaY, deltaX) * 180 / Math.PI; // Convert radian to degrees
    },
    applyRotation(angle) {
      let numRotations = ((angle / 90) % 4 + 4) % 4; // Normalize rotations
      for (let i = 0; i < numRotations; i++) {
        this.rotateGrid();
      }
    },
    rotateGrid() {
      let newGrid = [];
      for (let col = 0; col < this.gridSize; col++) {
        newGrid.push([]);
        for (let row = this.gridSize - 1; row >= 0; row--) {
          newGrid[col].push({ ...this.grid[row][col] });
        }
      }
      this.grid = newGrid;
      this.$nextTick(() => {
        this.updateUserPositionAfterRotation();
      });
    },
    updateUserPositionAfterRotation() {
      this.grid[this.userPosition.y][this.userPosition.x].isUser = false;
      let newUserPosition = {
        x: this.userPosition.y,
        y: this.gridSize - 1 - this.userPosition.x
      };
      this.userPosition = newUserPosition;
      this.grid[this.userPosition.y][this.userPosition.x].isUser = true;
    },
  }
}
</script>
