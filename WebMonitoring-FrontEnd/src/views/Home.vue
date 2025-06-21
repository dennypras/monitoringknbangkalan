<template>
  <!-- HERO Section -->
  <section class="hero" data-aos="fade-up">
    <div class="hero-container">
      <div class="hero-left">
        <div class="typing-wrapper">
          <h1 class="hero-title">Selamat Datang di</h1>
          <h2 class="hero-highlight">WebMonitoring <p>Kejaksaan Negeri Bangkalan</p></h2>
        </div>
        <div class="hero-features">
          <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
            <h4>Penuntutan</h4>
            <p>Profesional dan adil.</p>
          </div>
          <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
            <h4>Pengawasan</h4>
            <p>Menjaga ketertiban.</p>
          </div>
          <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
            <h4>Pelayanan</h4>
            <p>Hukum untuk masyarakat.</p>
          </div>
        </div>
      </div>
      <div class="hero-right" data-aos="fade-left">
        <div class="photo-container">
          <div class="circle-background"></div>
          <img src="/public/jaksaagung.png" alt="Jaksa Agung" class="hero-image" />
        </div>
      </div>
    </div>
  </section>

  <!-- DATA PERKARA Section -->
  <section class="perkara" data-aos="fade-up">
  <h2>Data Perkara</h2>
  <div class="controls">
    <input
      v-model="searchTerm"
      @input="onInputChange"
      type="text"
      placeholder="Cari nama tersangka atau pasal..."
      class="search-box"
    />
  </div>

  <div v-if="loading" class="loading">Loading...</div>

  <div v-else class="card-list">
    <div
      v-if="perkaraList.length"
      v-for="perkara in perkaraList"
      :key="perkara.id"
      class="card compact-card"
      data-aos="fade-up"
      data-aos-delay="100"
    >
      <h3 class="card-nama">{{ perkara.nama_tersangka }}</h3>

      <p>
        <strong>Kode Kategori:</strong>
        <span :class="['kode-kategori-badge', getKodeKategoriClass(perkara.kategori?.kode_kategori)]">
          {{ perkara.kategori?.kode_kategori || '-' }}
        </span>
      </p>

      <p><strong>No SPDP:</strong> {{ perkara.nomor_spdp }}</p>
      <p><strong>Tgl SPDP:</strong> {{ formatDate(perkara.tanggal_spdp) }}</p>
      <p><strong>Pasal:</strong> {{ perkara.pasal }}</p>
      <p><strong>Nama JPU:</strong> {{ perkara.nama_jpu }}</p>

      <div class="action-buttons-center">
        <button @click="lihatDetail(perkara)" class="btn-detail-small">Detail</button>
      </div>
    </div>

    <div v-else class="empty-state">📄 Data tidak ditemukan.</div>
  </div>

  <div class="pagination" v-if="totalPages > 1">
    <button @click="prevPage" :disabled="currentPage === 1">Sebelumnya</button>
    <span>Halaman {{ currentPage }} dari {{ totalPages }}</span>
    <button @click="nextPage" :disabled="currentPage === totalPages">Berikutnya</button>
  </div>
</section>

<!-- Modal Detail Berkas -->
<div v-if="showDetail" class="modal-overlay">
  <div class="modal-wrapper">
    <div class="modal-content scrollable relative">
      <button class="modal-close" @click="showDetail = false">×</button>
      <h2 class="form-title mb-4">Detail Berkas</h2>
      <table class="w-full text-sm table-auto border">
        <tbody>
          <tr><td class="font-semibold">Nomor SPDP</td><td>{{ selectedBerkas.nomor_spdp }}</td></tr>
          <tr><td class="font-semibold">Nama JPU</td><td>{{ selectedBerkas.nama_jpu }}</td></tr>
          <tr><td class="font-semibold">Tanggal SPDP</td><td>{{ formatDate(selectedBerkas.tanggal_spdp) }}</td></tr>
          <tr><td class="font-semibold">Tanggal Diterima SPDP</td><td>{{ formatDate(selectedBerkas.tanggal_diterima_spdp) }}</td></tr>
          <tr><td class="font-semibold">Pasal</td><td>{{ selectedBerkas.pasal }}</td></tr>
          <tr><td class="font-semibold">Tempat Kejadian</td><td>{{ selectedBerkas.tempat_kejadian }}</td></tr>
          <tr><td class="font-semibold">Instansi Penyidik</td><td>{{ selectedBerkas.instansi_penyidik }}</td></tr>
          <tr><td class="font-semibold">Kategori</td><td>{{ selectedBerkas.kategori.kode_kategori }}, {{ selectedBerkas.kategori.nama_kategori }}</td></tr>
          <tr><td class="font-semibold">Nama Tersangka</td><td>{{ selectedBerkas.nama_tersangka }}</td></tr>
          <tr v-if="selectedBerkas.nomor_berkas">
            <td class="font-semibold">Nomor Berkas</td><td>{{ selectedBerkas.nomor_berkas }}</td>
          </tr>
          <tr v-if="selectedBerkas.nomor_pengantar">
            <td class="font-semibold">Nomor Pengantar</td><td>{{ selectedBerkas.nomor_pengantar }}</td></tr>
          <!-- File pendukung tidak ditampilkan -->
        </tbody>
      </table>
    </div>
  </div>
</div>


  <!-- ABOUT Section -->
  <section class="about" data-aos="fade-up">
    <h2>Tentang Kejaksaan Negeri Bangkalan</h2>
    <p>
      Kejaksaan Negeri Bangkalan adalah lembaga penegak hukum yang bertugas melaksanakan kekuasaan negara di
      bidang penuntutan serta tugas-tugas lain berdasarkan peraturan perundang-undangan.
    </p>
    <img src="/public/kejaksaan-logo.png" alt="Logo Kejaksaan" class="logo-kejaksaan" />
  </section>

  <!-- MISI KEJAKSAAN Section -->
  <section class="misi" data-aos="fade-up">
    <h2>Misi Kejaksaan</h2>
    <ul>
      <li>Meningkatkan penegakan hukum yang profesional, proporsional dan akuntabel.</li>
      <li>Memulihkan kepercayaan publik melalui pelayanan publik berbasis teknologi.</li>
      <li>Memperkuat koordinasi dan kerja sama antar lembaga penegak hukum.</li>
      <li>Meningkatkan SDM Kejaksaan yang berintegritas, kompeten, dan inovatif.</li>
      <li>Mewujudkan Kejaksaan Digital di era globalisasi dan Revolusi Industri 4.0.</li>
    </ul>
  </section>

  <!-- FOOTER Section -->
  <footer class="footer">
    <p>© 2025 WebMonitoring Kejaksaan Negeri Bangkalan. All rights reserved.</p>
  </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AOS from 'aos'
import 'aos/dist/aos.css'
import '@/assets/css/home.css'

onMounted(() => {
  AOS.init({
    duration: 1000,
    once: true,
  })
})

const perkaraList = ref([])
const loading = ref(false)
const searchTerm = ref('')
const currentPage = ref(1)
const itemsPerPage = 12
const totalPages = ref(1)
const showDetail = ref(false)
const selectedBerkas = ref(null)

const statusOptions = [
  'P-16',
  'P-17',
  'Berkas Tahap 1',
  'P-18',
  'P-19',
  'Pengembalian Berkas Tahap 1',
  'P-21',
]

const getKodeKategoriClass = (kode) => {
  switch (kode) {
    case 'P-16': return 'kode-p16'
    case 'P-17': return 'kode-p17'
    case 'Berkas Tahap 1': return 'kode-tahap1'
    case 'P-18': return 'kode-p18'
    case 'P-19': return 'kode-p19'
    case 'Pengembalian Berkas Tahap 1': return 'kode-pengembalian'
    case 'P-21': return 'kode-p21'
    default: return ''
  }
}

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const d = new Date(dateStr)
  return d.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  })
}

const fetchPerkara = async () => {
  loading.value = true
  try {
    const response = await axios.get('http://localhost:8000/api/berkas-perkara', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    let data = response.data

    if (!Array.isArray(data)) {
      console.error('Data perkara bukan array:', data)
      loading.value = false
      return
    }

    // Filter kategori yang valid
    data = data.filter(item => item.kategori !== null && item.kategori !== undefined)

    // Sort descending berdasarkan tanggal penambahan (created_at)
    data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))

    // Filter pencarian
    if (searchTerm.value) {
      const term = searchTerm.value.toLowerCase()
      data = data.filter(
        item =>
          item.nama_tersangka.toLowerCase().includes(term) ||
          item.pasal.toLowerCase().includes(term)
      )
    }

    totalPages.value = Math.ceil(data.length / itemsPerPage)
    const start = (currentPage.value - 1) * itemsPerPage
    perkaraList.value = data.slice(start, start + itemsPerPage)
  } catch (error) {
    console.error('Gagal mengambil data perkara:', error)
  } finally {
    loading.value = false
  }
}


const lihatDetail = async (perkara) => {
  try {
    const response = await axios.get(`http://localhost:8000/api/berkas-perkara/${perkara.id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    })
    selectedBerkas.value = response.data
    showDetail.value = true
  } catch (error) {
    console.error('Gagal ambil detail:', error)
    alert('Gagal mengambil data detail')
  }
}

let debounceTimeout = null
const onInputChange = () => {
  clearTimeout(debounceTimeout)
  debounceTimeout = setTimeout(() => {
    currentPage.value = 1
    fetchPerkara()
  }, 300)
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++
  fetchPerkara()
}

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--
  fetchPerkara()
}

onMounted(() => {
  fetchPerkara()
})
</script>