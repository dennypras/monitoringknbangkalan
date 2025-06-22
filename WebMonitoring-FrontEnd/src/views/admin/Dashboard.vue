<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center mb-6">
      Jumlah Kategori Berkas Saat ini :
    </h2>

    <!-- Filter tanggal -->
    <div class="flex flex-wrap gap-4 items-center justify-center mb-6">
      <label class="text-gray-700 dark:text-white">Tanggal Mulai:</label>
      <input type="date" v-model="tanggalAwal" class="border p-2 rounded" />

      <label class="text-gray-700 dark:text-white">Tanggal Akhir:</label>
      <input type="date" v-model="tanggalAkhir" class="border p-2 rounded" />

      <button
        @click="filterStatistik"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Rekap
      </button>
    </div>

    <!-- Statistik Card -->
    <div class="dashboard-container">
      <div
        v-for="(item, index) in statistik"
        :key="index"
        class="stat-card"
      >
        <div class="stat-number">{{ item.jumlah }}</div>
        <div class="stat-label">Total {{ item.label }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import '@/assets/css/dashboard.css'

const statistik = ref([])
const tanggalAwal = ref('')
const tanggalAkhir = ref('')

// Ambil seluruh statistik (tanpa filter)
const ambilStatistik = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/dashboard/statistik', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Cache-Control': 'no-cache',
      },
    })
    statistik.value = data
  } catch (error) {
    console.error('❌ Gagal ambil statistik:', error)
  }
}

// Filter statistik berdasarkan tanggal
const filterStatistik = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/dashboard/statistik', {
      params: {
        tanggal_mulai: tanggalAwal.value,
        tanggal_akhir: tanggalAkhir.value,
      },
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    statistik.value = data
  } catch (error) {
    console.error('❌ Gagal filter statistik:', error)
    alert('Gagal memfilter data statistik.')
  }
}

onMounted(() => {
  ambilStatistik()
})
</script>
