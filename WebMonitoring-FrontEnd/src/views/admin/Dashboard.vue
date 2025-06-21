<template>
  <div class="p-6 bg-gray-50 min-h-screen"> 
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-align:center">Jumlah Kategori Berkas Saat ini : </h2>


<div class="flex gap-4 items-center mb-4">
  <label class="text-gray-700 dark:text-white">Dari:</label>
  <input type="date" v-model="tanggalAwal" class="border p-2 rounded" />
  
  <label class="text-gray-700 dark:text-white">Sampai:</label>
  <input type="date" v-model="tanggalAkhir" class="border p-2 rounded" />
  
  <button @click="filterStatistik" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    Cari
  </button>
</div>


 <div class="dashboard-container">
  <div v-for="(item, index) in statistik" :key="index" class="stat-card">
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
const tanggalAwal = ref('');
const tanggalAkhir = ref('');


const statistik = ref([])

const ambilStatistik = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/dashboard/statistik', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Cache-Control': 'no-cache'
      }
    })
    console.log('📊 Statistik dari API:', data)
    statistik.value = data
  } catch (error) {
    console.error("❌ Gagal ambil statistik:", error)
  }
}

const filterStatistik = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/dashboard/statistik', {
      params: {
        from: tanggalAwal.value,
        to: tanggalAkhir.value,
      }
    });
    statistik.value = data;
  } catch (error) {
    console.error('❌ Gagal filter statistik:', error);
    alert('Gagal memfilter data statistik.');
  }
};

onMounted(() => {
  ambilStatistik()
})
</script>
