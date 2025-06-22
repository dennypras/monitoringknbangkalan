<template>
  <nav class="navbar" :class="{ dark: isDark }">
    <div class="navbar-container">
      <div class="navbar-brand">
        <RouterLink to="/" class="brand">
          <img src="/public/kejaksaan-logo.png" alt="Logo" class="logo" />
          <span class="brand-text">WebMonitoring</span>
        </RouterLink>
      </div>

      <div class="menu-toggle" :class="{ active: menuOpen }" @click="toggleMenu">
        <span></span><span></span><span></span>
      </div>

      <ul :class="['navbar-links', { 'show': menuOpen }]">
        <li><RouterLink to="/">Home</RouterLink></li>
        <li><RouterLink to="/login">Login</RouterLink></li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'

const menuOpen = ref(false)
const isDark = ref(false)

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}

</script>

<style scoped lang="scss">
@use "@/assets/scss/components/navbar" as *;
</style>
