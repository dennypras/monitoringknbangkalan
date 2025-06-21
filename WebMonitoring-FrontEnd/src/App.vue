<template>
  <Navbar v-if="!isAdminPage" />
  <RouterView />
</template>

<script setup>
import { useRoute } from 'vue-router'
import { computed } from 'vue'
import Navbar from '@/components/Navbar.vue'
import 'aos/dist/aos.css'

const route = useRoute()
const isAdminPage = computed(() => route.path.startsWith('/admin'))
</script>

<style scoped>
main {
  padding: 2rem;
  text-align: center;
}
</style>
