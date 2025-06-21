<template>
    <button class="dark-mode-toggle" @click="$emit('toggle')">
      {{ isDark ? '🌙' : '☀️' }}
    </button>
  </template>
  
  <script setup>
  defineProps({
    isDark: Boolean
  })
  </script>
  
  <style scoped>
  .dark-mode-toggle {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
  }
  </style>
  