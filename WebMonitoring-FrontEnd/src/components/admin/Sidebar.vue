<template>
  <aside class="sidebar" :class="{ closed: !isOpen }">
    <section class="menu-group">
      <h4 class="section-title">Web Monitoring</h4>
      <router-link to="/admin/dashboard" class="menu-item" active-class="active">
        <span class="icon">📊</span>
        <span class="label">Dashboard</span>
      </router-link>
      <router-link to="/admin/kelola-berkas" class="menu-item" active-class="active">
        <span class="icon">📁</span>
        <span class="label">Kelola Berkas</span>
      </router-link>
    </section>
  </aside>
</template>

<script setup>
defineProps({ isOpen: Boolean })
</script>

<style scoped>
.sidebar {
  width: 260px;
  background: #1b5e20;
  color: white;
  position: fixed;
  top: 64px;
  bottom: 0;
  left: 0;
  overflow-y: auto;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  z-index: 1000;
}

.sidebar.closed {
  transform: translateX(-100%);
}

.menu-group {
  padding: 1rem;
}

.section-title {
  font-size: 0.75rem;
  color: #a5d6a7;
  font-weight: bold;
  margin-bottom: 0.75rem;
  text-transform: uppercase;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.65rem 0.85rem;
  border-radius: 6px;
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.2s;
}

.menu-item:hover {
  background-color: #2e7d32;
}

.menu-item.active {
  background-color: #388e3c;
  font-weight: bold;
}

.icon {
  font-size: 1.1rem;
}
.label {
  font-size: 0.95rem;
}

@media screen and (max-width: 768px) {
  .sidebar {
    width: 100%;
    max-width: 260px;
    box-shadow: 2px 0 6px rgba(0, 0, 0, 0.2);
  }
}
</style>
