<template>
  <div :class="['admin-layout', { 'sidebar-closed': !sidebarOpen }]">
    <AdminNavbar @toggle-sidebar="sidebarOpen = !sidebarOpen" @logout="logout" @toggle-theme="toggleDarkMode" />
    <Sidebar :isOpen="sidebarOpen" />
    <main class="admin-content">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminNavbar from '@/components/admin/AdminNavbar.vue'
import Sidebar from '@/components/admin/Sidebar.vue'

const sidebarOpen = ref(true)

function logout() {
  localStorage.removeItem('token')
  window.location.href = '/login'
}

function toggleDarkMode() {
  document.documentElement.classList.toggle('dark')
}

onMounted(() => {
  if (window.innerWidth <= 768) {
    sidebarOpen.value = false
  }
})
</script>

<style scoped>
.admin-layout {
  display: flex;
  flex-direction: column;
}

.admin-content {
  margin-left: 260px;
  margin-top: 64px;
  padding: 2rem;
  transition: margin-left 0.3s ease;
}

.sidebar-closed .admin-content {
  margin-left: 0;
}

.admin-content h2:hover,
.admin-content p:hover {
  color: #1b5e20;
  transform: translateX(2px);
  transition: all 0.2s ease-in-out;
}

.dark .admin-content {
  background-color: #121212;
  color: #e0e0e0;
}

@media screen and (max-width: 768px) {
  .admin-content {
    margin-left: 0 !important;
    padding: 1rem;
  }
}
</style>
