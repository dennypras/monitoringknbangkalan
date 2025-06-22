  <template>
  <div class="page-container">
    <!-- Tombol Tambah -->
    <div class="flex justify-between items-center mb-4">
      <button @click="showForm = true; resetForm()" class="btn-primary">+ Tambah Berkas</button>
      <button v-if="showForm" @click="showForm = false" class="btn-danger">×</button>
    </div>

    <!-- Modal Tambah Berkas -->
    <div v-if="showForm" class="modal-overlay">
      <div class="modal-wrapper">
        <div class="modal-content scrollable">
          <button class="modal-close" @click="showForm = false">Tutup</button>
          <h2 class="form-title">Tambah Berkas Perkara</h2>
          <form @submit.prevent="submitForm" class="form-grid">
            <div class="form-group">
              <label>Nomor SPDP</label>
              <input v-model="form.nomor_spdp" type="text" required />
            </div>
            <div class="form-group">
              <label>Nama JPU</label>
              <input v-model="form.nama_jpu" type="text" required />
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Tanggal SPDP</label>
                <input v-model="form.tanggal_spdp" type="date" required />
              </div>
              <div class="form-group">
                <label>Tanggal Diterima</label>
                <input v-model="form.tanggal_diterima_spdp" type="date" required />
              </div>
            </div>
            <div class="form-group">
              <label>Pasal yang Dilanggar</label>
              <input v-model="form.pasal" type="text" required />
            </div>
            <div class="form-group">
              <label>Tempat Kejadian Perkara</label>
              <input v-model="form.tempat_kejadian" type="text" required />
            </div>
            <div class="form-group">
              <label>Instansi Penyidik</label>
              <input v-model="form.instansi_penyidik" type="text" required />
            </div>
            <div class="form-group">
              <label>Nama Tersangka</label>
              <textarea v-model="form.nama_tersangka" rows="2" required></textarea>
            </div>
            <div class="form-group">
              <div class="text-center mt-2">
              <button type="submit" class="btn-primary w-full">Simpan Berkas (Status Awal: P-16)</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


    <!-- Daftar Berkas -->
    <div class="table-header">
      <h2 class="section-title text-center">Daftar Berkas</h2>
      <input v-model="searchQuery" type="text" class="input-search" placeholder="Cari Nomor SPDP / Nama JPU / Tersangka..."/>
    </div>
    <div class="table-container">
      <table class="daftar-table">
        <thead>
          <tr>
            <th>No</th>
            <th>No. SPDP</th>
            <th>Status</th>
            <th>Tersangka</th>
            <th>Nama JPU</th>
            <th>Tanggal SPDP</th>
            <th>Pasal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(berkas, index) in paginatedBerkas" :key="index">
            <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
            <td>{{ berkas.nomor_spdp}}</td>
            <td>{{ berkas.kategori.nama_kategori }}</td>
            <td>{{ berkas.nama_tersangka }}</td>
            <td>{{ berkas.nama_jpu }}</td>
            <td>{{ berkas.tanggal_spdp }}</td>
            <td>{{ berkas.pasal }}</td>
            <td>
              <div class="btn-group">
                <button @click="lihatDetail(berkas.id)" class="btn-small bg-green">Detail</button>
                <button @click="editBerkas(berkas.id)" class="btn-small bg-yellow">Update</button>
                <button @click="hapusBerkas(berkas.id)" class="btn-small bg-red">Hapus</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginasi -->
    <div class="mt-4 flex gap-2 justify-center">
      <button @click="prevPage" :disabled="currentPage === 1" class="btn-page">← Prev</button>
      <button v-for="page in totalPages" :key="page" @click="changePage(page)" :class="['btn-page', page === currentPage ? 'active-page' : '']">{{ page }}</button>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="btn-page">Next →</button>
    </div>

        <!-- Modal Update Berkas -->
    <div v-if="showUpdateForm" class="modal-overlay">
  <div class="modal-wrapper">
    <div class="modal-content scrollable relative">
      <button class="modal-close" @click="showUpdateForm = false">×</button>
      <h2 class="form-title mb-4">Update Berkas</h2>

      <form @submit.prevent="submitUpdate" class="form-grid">
        <div class="form-group">
          <label>Nomor SPDP</label>
          <input v-model="updateForm.nomor_spdp" type="text" readonly />
        </div>

        <div class="form-group">
          <label>Nama Tersangka</label>
          <input v-model="updateForm.nama_tersangka" type="text" required />
        </div>

        <div class="form-group">
          <label>Pasal</label>
          <input v-model="updateForm.pasal" type="text" required />
        </div>

        <div class="form-group">
          <label>Status</label>
          <select v-model="updateForm.kategori_id" class="input-text">
            <option 
              v-for="item in kategoriList" 
              :key="item.id" 
              :value="item.id"
              :disabled="item.urutan < updateForm.currentKategoriUrutan"
            >
              {{ item.nama_kategori }}
            </option>
          </select>
        </div>

        <!-- P-17 -->
        <div v-if="getKategoriKode(updateForm.kategori_id) === 'P-17'">
          <div class="form-group">
            <label>Nomor Surat</label>
            <input v-model="updateForm.nomor_surat" type="text" />
          </div>
          <div class="form-group">
            <label>Upload Surat P-17 (PDF)</label>
            <input type="file" accept="application/pdf" @change="handleUpdateFileUpload" />
          </div>
        </div>

        <!-- Berkas Tahap 1 -->
        <div v-if="getKategoriKode(updateForm.kategori_id) === 'Berkas Tahap 1'">
          <div class="form-group">
            <label>Nomor Berkas Perkara</label>
            <input v-model="updateForm.nomor_berkas_perkara" type="text" />
          </div>
          <div class="form-group">
            <label>Nomor Pengantar Berkas</label>
            <input v-model="updateForm.nomor_pengantar_berkas" type="text" />
          </div>
          <div class="form-group">
            <label>Tanggal Berkas Tahap 1</label>
            <input v-model="updateForm.tanggal_berkas_tahap_1" type="date" />
          </div>
        </div>

        <!-- Berkas Tahap 1 Kembali -->
        <div v-if="getKategoriKode(updateForm.kategori_id) === 'Berkas Tahap 1 Kembali'">
          <div class="form-group">
            <label>Tanggal Berkas Kembali</label>
            <input v-model="updateForm.tanggal_berkas_kembali" type="date" />
          </div>
          <div class="form-group">
            <label>Nomor Pengantar Berkas Kembali</label>
            <input v-model="updateForm.nomor_pengantar_berkas_kembali" type="text" />
          </div>
        </div>

        <div v-if="getKategoriKode(updateForm.kategori_id) === 'P-18'">
          <div class="form-group">
            <label>Tanggal P-18</label>
            <input v-model="updateForm.tanggal_p18" type="date" />
          </div>
        </div>
        <div v-if="getKategoriKode(updateForm.kategori_id) === 'P-19'">
          <div class="form-group">
            <label>Tanggal P-19</label>
            <input v-model="updateForm.tanggal_p19" type="date" />
          </div>
        </div>
        <div v-if="getKategoriKode(updateForm.kategori_id) === 'P-21'">
          <div class="form-group">
            <label>Tanggal P-21</label>
            <input v-model="updateForm.tanggal_p21" type="date" />
          </div>
        </div>
        <div v-if="['P-18', 'P-19', 'P-21'].includes(getKategoriKode(updateForm.kategori_id))">
          <div class="form-group">
            <label>Upload Surat Pendukung (PDF)</label>
            <input type="file" accept="application/pdf" @change="handleUpdateFileUpload" />
          </div>
        </div>

        <button type="submit" class="btn-primary w-full mt-4">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
  

 <!-- Modal Detail Berkas -->
    <div v-if="showDetail" class="modal-overlay">
      <div class="modal-wrapper">
        <div class="modal-content scrollable relative">
          <button class="modal-close" @click="showDetail = false">×</button>
          <h2 class="form-title mb-4">Detail Berkas</h2>
          <table class="w-full text-sm table-auto border">
            <tbody>
              <tr v-if="selectedBerkas.nomor_spdp">
                <td class="font-semibold">Nomor SPDP</td>
                <td>{{ selectedBerkas.nomor_spdp }}</td>
              </tr>

              <tr v-if="selectedBerkas.nama_jpu">
                <td class="font-semibold">Nama JPU</td>
                <td>{{ selectedBerkas.nama_jpu }}</td>
              </tr>

              <tr v-if="selectedBerkas.tanggal_spdp">
                <td class="font-semibold">Tanggal SPDP</td>
                <td>{{ formatDate(selectedBerkas.tanggal_spdp) }}</td>
              </tr>
              <tr v-if="selectedBerkas.detail_kategori?.tanggal_diterima_spdp">
                <td class="font-semibold">Tanggal Diterima SPDP</td>
                <td>{{ formatDate(selectedBerkas.detail_kategori.tanggal_diterima_spdp) }}</td>
              </tr>
              <tr v-if="selectedBerkas.pasal">
                <td class="font-semibold">Pasal</td>
                <td>{{ selectedBerkas.pasal }}</td>
              </tr>

              <tr v-if="selectedBerkas.tempat_kejadian">
                <td class="font-semibold">Tempat Kejadian</td>
                <td>{{ selectedBerkas.tempat_kejadian }}</td>
              </tr>

              <tr v-if="selectedBerkas.instansi_penyidik">
                <td class="font-semibold">Instansi Penyidik</td>
                <td>{{ selectedBerkas.instansi_penyidik }}</td>
              </tr>

              <tr v-if="selectedBerkas.kategori?.nama_kategori">
                <td class="font-semibold">Kategori</td>
                <td>{{ selectedBerkas.kategori.nama_kategori }}</td>
              </tr>

              <tr v-if="selectedBerkas.nama_tersangka">
                <td class="font-semibold">Nama Tersangka</td>
                <td>{{ selectedBerkas.nama_tersangka }}</td>
              </tr>

              <!-- Detail Kategori -->
              <tr v-if="selectedBerkas.detail_kategori?.nomor_berkas_perkara">
                <td class="font-semibold">Nomor Berkas Perkara</td>
                <td>{{ selectedBerkas.detail_kategori.nomor_berkas_perkara }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.nomor_pengantar_berkas">
                <td class="font-semibold">Nomor Pengantar Berkas</td>
                <td>{{ selectedBerkas.detail_kategori.nomor_pengantar_berkas }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.tanggal_berkas_tahap_1">
                <td class="font-semibold">Tanggal Berkas Tahap 1</td>
                <td>{{ formatDate(selectedBerkas.detail_kategori.tanggal_berkas_tahap_1) }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.tanggal_p18">
                <td class="font-semibold">Tanggal P-18</td>
                <td>{{ formatDate(selectedBerkas.detail_kategori.tanggal_p18) }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.tanggal_p19">
                <td class="font-semibold">Tanggal P-19</td>
                <td>{{ formatDate(selectedBerkas.detail_kategori.tanggal_p19) }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.tanggal_berkas_kembali">
                <td class="font-semibold">Tanggal Berkas Kembali</td>
                <td>{{ formatDate(selectedBerkas.detail_kategori.tanggal_berkas_kembali) }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.nomor_pengantar_berkas_kembali">
                <td class="font-semibold">Nomor Pengantar Berkas Kembali</td>
                <td>{{ selectedBerkas.detail_kategori.nomor_pengantar_berkas_kembali }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.tanggal_p21">
                <td class="font-semibold">Tanggal P-21</td>
                <td>{{ formatDate(selectedBerkas.detail_kategori.tanggal_p21) }}</td>
              </tr>

              <tr v-if="selectedBerkas.detail_kategori?.file_pendukung">
                <td class="font-semibold">File Pendukung</td>
                <td>
                  <a :href="getFileUrl(selectedBerkas.detail_kategori.file_pendukung)" target="_blank" class="text-blue-600 underline">
                    Download File
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import '@/assets/css/kelola-berkas.css'

const berkasList = ref([])
const kategoriList = ref([])
const form = ref({
  nomor_spdp: '',
  nama_jpu: '',
  tanggal_spdp: '',
  tanggal_diterima_spdp: '',
  pasal: '',
  tempat_kejadian: '',
  instansi_penyidik: '',
  nama_tersangka: ''
})

const showForm = ref(false)
const showUpdateForm = ref(false)
const showDetail = ref(false)
const selectedBerkas = ref({
  kategori: {},
  detail_kategori: {}
});
const updateForm = ref({
  id: null,
  nomor_spdp: '',
  nama_tersangka: '',
  pasal: '',
  kategori_id: null,
  nomor: '',
  file_pendukung: null,
  nomor_berkas_perkara: '',
  nomor_pengantar_berkas: '',
  tanggal_berkas_tahap_1: '',
  tanggal_berkas_kembali: '',
  nomor_pengantar_berkas_kembali: '',
})

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

function resetForm() {
  form.value = {
    nomor_spdp: '',
    nama_jpu: '',
    tanggal_spdp: '',
    tanggal_diterima_spdp: '',
    pasal: '',
    tempat_kejadian: '',
    instansi_penyidik: '',
    nama_tersangka: ''
  }
}

const currentKategoriUrutan = ref(1)
const filePendukung = ref(null)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = 12

const getFileUrl = (path) => `/storage/${path}`


const getKategoriKode = (id) => {
  const kategori = kategoriList.value.find(k => k.id === id)
  return kategori ? kategori.nama_kategori : ''
}

const paginatedBerkas = computed(() => {
  if (!berkasList.value || !Array.isArray(berkasList.value)) return []
  const filtered = berkasList.value.filter((b) => {
    const q = searchQuery.value.toLowerCase()
    return (
      b.nomor_spdp.toLowerCase().includes(q) ||
      b.nama_jpu.toLowerCase().includes(q) ||
      b.nama_tersangka.toLowerCase().includes(q)
    )
  })
  const start = (currentPage.value - 1) * perPage
  return filtered.slice(start, start + perPage)
})

const totalPages = computed(() => {
  return Math.ceil(
    berkasList.value.filter((b) => {
      const q = searchQuery.value.toLowerCase()
      return (
        b.nomor_spdp.toLowerCase().includes(q) ||
        b.nama_jpu.toLowerCase().includes(q) ||
        b.nama_tersangka.toLowerCase().includes(q)
      )
    }).length / perPage
  )
})

const changePage = (page) => currentPage.value = page
const prevPage = () => { if (currentPage.value > 1) currentPage.value-- }
const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++ }

async function fetchData() {
  try {
    const response = await axios.get('http://localhost:8000/api/berkas-perkara')
    console.log('✅ Data diterima:', response.data) // Debug
    berkasList.value = Array.isArray(response.data) ? response.data : []
  } catch (err) {
    console.error('❌ Gagal memuat data perkara:', err)
    berkasList.value = []
  }
}

async function fetchKategori() {
  try {
    const response = await axios.get('http://localhost:8000/api/kategori')
    kategoriList.value = response.data
  } catch (err) {
    console.error('Gagal memuat kategori:', err)
  }
}

async function submitForm() {
  console.log("🚀 Mengirim data:", form.value)

  try {
    const response = await axios.post('http://localhost:8000/api/berkas-perkara', form.value, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    alert("✅ Berkas berhasil ditambahkan")
    showForm.value = false
    resetForm()
    fetchData()

  } catch (error) {
    if (error.response) {
      // Jika error validasi dari Laravel
      const data = error.response.data
      if (data.errors) {
        const messages = Object.values(data.errors).flat().join('\n')
        alert("⚠️ Validasi Gagal:\n" + messages)
      } else {
        alert("❌ Gagal menambahkan berkas: " + (data.message || 'Terjadi kesalahan'))
      }
      console.error("🛑 Error Response:", data)
    } else {
      alert("❌ Gagal mengirim data. Periksa koneksi atau server.")
      console.error("🛑 Error:", error)
    }
  }
}



const detailMap = computed(() => {
  const d = selectedBerkas.value
  return {
    'Nomor SPDP': d.nomor_spdp,
    'Nama JPU': d.nama_jpu,
    'Tanggal SPDP': d.tanggal_spdp,
    'Tanggal Diterima SPDP': d.detail_kategori?.tanggal_diterima_spdp,
    'Pasal': d.pasal,
    'Tempat Kejadian': d.tempat_kejadian,
    'Instansi Penyidik': d.instansi_penyidik,
    'Kategori': d.kategori?.nama_kategori,
    'Nama Tersangka': d.nama_tersangka,
    'Nomor Berkas Perkara': d.detail_kategori?.nomor_berkas_perkara,
    'Nomor Pengantar Berkas': d.detail_kategori?.nomor_pengantar_berkas,
    'Tanggal Berkas Tahap 1': d.detail_kategori?.tanggal_berkas_tahap_1,
    'Tanggal P-18': d.detail_kategori?.tanggal_p18,
    'Tanggal P-19': d.detail_kategori?.tanggal_p19,
    'Tanggal Berkas Kembali': d.detail_kategori?.tanggal_berkas_kembali,
    'Nomor Pengantar Berkas Kembali': d.detail_kategori?.nomor_pengantar_berkas_kembali,
    'Tanggal P-21': d.detail_kategori?.tanggal_p21
  }
})

async function lihatDetail(id) {
  const realId = typeof id === 'object' && id?.id ? id.id : id;

  console.log("📦 ID yang dikirim:", realId, typeof realId);

  if (!realId || isNaN(realId)) {
    console.error("❌ ID tidak valid:", realId);
    alert("ID tidak valid.");
    return;
  }

  try {
    console.log("📥 Mengambil detail untuk ID:", realId);
    const response = await axios.get(`http://localhost:8000/api/berkas-perkara/${realId}`);
    selectedBerkas.value = response.data;
    showDetail.value = true;
  } catch (error) {
    console.error("❌ Gagal ambil detail:", error.response?.data || error);
    alert("Gagal mengambil detail berkas dari server.");
  }
}

async function editBerkas(id) {
  const realId = typeof id === 'object' && id?.id ? id.id : id;

  if (!realId || isNaN(realId)) {
    console.error("❌ ID untuk edit tidak valid:", realId);
    alert("ID tidak valid untuk edit.");
    return;
  }

  try {
    const { data } = await axios.get(`http://localhost:8000/api/berkas-perkara/${realId}`)
    selectedBerkas.value = data
    console.log("Kategori ID yang dikirim:", updateForm.value.kategori_id)
    
    updateForm.value = {
      id: data.id,
      nomor_spdp: data.nomor_spdp,
      nama_tersangka: data.nama_tersangka,
      pasal: data.pasal,
      kategori_id: data.kategori?.id || '',
      nomor: data.detail_kategori?.nomor || '',
      file_pendukung: null,
      nomor_berkas_perkara: data.detail_kategori?.nomor_berkas_perkara || '',
      nomor_pengantar_berkas: data.detail_kategori?.nomor_pengantar_berkas || '',
      tanggal_berkas_tahap_1: data.detail_kategori?.tanggal_berkas_tahap_1 || '',
      tanggal_berkas_kembali: data.detail_kategori?.tanggal_berkas_kembali || '',
      nomor_pengantar_berkas_kembali: data.detail_kategori?.nomor_pengantar_berkas_kembali || '',
    }

    console.log("✏️ Edit data untuk ID:", realId, typeof realId);
    currentKategoriUrutan.value = data.kategori.urutan
    showUpdateForm.value = true

  } catch (error) {
    console.error("❌ Gagal ambil data untuk edit:", error.response?.data || error)
    alert("Gagal mengambil data berkas untuk update.")
  }
}

function handleUpdateFileUpload(event) {
  filePendukung.value = event.target.files[0]
}

async function submitUpdate() {
  const formData = new FormData()

  // ✅ Kirim nama field sesuai validasi backend
  console.log("🧪 kategori_id yang dikirim:", updateForm.value.kategori_id)
  formData.append('kategori_id', updateForm.value.kategori_id)
  formData.append('nama_tersangka', updateForm.value.nama_tersangka)
  formData.append('pasal', updateForm.value.pasal)

  // ✅ Kirim data tambahan jika relevan
  if (updateForm.value.nomor_berkas_perkara) {
    formData.append('nomor_berkas', updateForm.value.nomor_berkas_perkara)
    formData.append('nomor_pengantar', updateForm.value.nomor_pengantar_berkas)
    formData.append('tanggal_berkas', updateForm.value.tanggal_berkas_tahap_1)
  }

  if (updateForm.value.tanggal_p18) formData.append('tanggal_p18', updateForm.value.tanggal_p18)
  if (updateForm.value.tanggal_p19) formData.append('tanggal_p19', updateForm.value.tanggal_p19)

  if (updateForm.value.tanggal_berkas_kembali) {
    formData.append('tanggal_berkas_kembali', updateForm.value.tanggal_berkas_kembali)
    formData.append('nomor_pengantar_berkas_kembali', updateForm.value.nomor_pengantar_berkas_kembali)
  }

  if (updateForm.value.tanggal_p21) formData.append('tanggal_p21', updateForm.value.tanggal_p21)

  if (filePendukung.value) formData.append('file_pendukung', filePendukung.value)

  try {
    const { data } = await axios.post(
      `http://localhost:8000/api/berkas-perkara/${updateForm.value.id}`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )

    alert("✅ Berkas berhasil diperbarui")
    showUpdateForm.value = false
    fetchData()

  } catch (error) {
    console.error("❌ Gagal update:", error.response?.data || error)
    alert("❌ Gagal memperbarui berkas.\n" + (error.response?.data?.message || 'Terjadi kesalahan'))
  }
}


async function hapusBerkas(id) {
  if (!confirm('Yakin ingin menghapus berkas ini?')) return

  console.log("🗑️ Hapus data ID:", id)
  try {
    await axios.delete(`http://localhost:8000/api/berkas-perkara/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    alert('✅ Berhasil dihapus')
    fetchData()
  } catch (err) {
    console.error('❌ Gagal menghapus:', err.response?.data || err)
    alert('❌ Gagal menghapus data.')
  }
}

onMounted(() => {
  fetchData()
  fetchKategori()
})
</script>


