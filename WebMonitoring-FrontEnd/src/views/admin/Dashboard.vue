<template>
  <div class="p-6 bg-gray-50 min-h-screen">

    <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center mb-6">
      Jumlah Kategori Berkas Saat ini :
    </h2>

  <!-- Filter Tanggal & Tombol Rekap dalam satu baris -->
<div class="flex justify-center mb-10">
  <div class="flex flex-wrap gap-6 items-center bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">

    <!-- Tanggal Mulai -->
    <label class="text-sm font-medium text-gray-700 dark:text-white whitespace-nowrap">
      Tanggal Mulai:
    </label>
    <input
      type="date"
      v-model="tanggalAwal"
      class="form-filter"
    />

    <!-- Tanggal Akhir -->
    <label class="text-sm font-medium text-gray-600 dark:text-white whitespace-nowrap">
      Tanggal Akhir:
    </label>
    <input
      type="date"
      v-model="tanggalAkhir"
      class="form-filter"
    />

    <!-- Tombol Rekap -->
    <button
      @click="filterStatistik"
      class="form-filter-btn"
    >
      Rekap
    </button>

  </div>
</div>



    <!-- Statistik Card -->
    <div class="dashboard-container">
      <div
        v-for="(item, index) in statistik"
        :key="index"
        class="stat-card hover:shadow-xl hover:-translate-y-1 transition duration-200 ease-in-out"
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

const filterStatistik = async () => {
  if (!tanggalAwal.value || !tanggalAkhir.value) {
    alert("Silakan pilih rentang tanggal terlebih dahulu.")
    return
  }

  if (tanggalAkhir.value < tanggalAwal.value) {
    alert("Tanggal akhir tidak boleh lebih awal dari tanggal mulai.")
    return
  }

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
