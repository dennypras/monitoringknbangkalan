<template>
  <header class="navbar">
    <div class="navbar-left">
      <button class="burger" @click="$emit('toggle-sidebar')">☰</button>
      <span class="brand">Web Monitoring</span>
    </div>
    <div class="navbar-right">
      <button class="icon" @click="$emit('toggle-theme')">🌓</button>
      <button class="icon" @click="$emit('logout')">Logout</button>
    </div>
  </header>
</template>

<script setup>
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 64px;
  background-color: #2e7d32; /* warna dari Home.vue */
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.navbar-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.brand {
  font-weight: bold;
  font-size: 1.2rem;
}

.burger {
  background: transparent;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.icon {
  background: transparent;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 1rem;
  transition: transform 0.2s;
}

.icon:hover {
  transform: scale(1.1);
  color: #c8e6c9;
}
</style>
