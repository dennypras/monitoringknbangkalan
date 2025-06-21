<template>
  <div class="page-container">

    <!-- Tombol Tambah -->
    <div class="flex justify-between items-center mb-4">
      <button @click="showForm = true" class="btn-primary">+ Tambah Berkas</button>
      <button v-if="showForm" @click="showForm = false" class="btn-danger">×</button>
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
        <th>Nomor SPDP</th>
        <th>Kategori</th>
        <th>Tersangka</th>
        <th>Nama JPU</th>
        <th>Tanggal SPDP</th>
        <th>Pasal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(berkas, index) in paginatedBerkas" :key="berkas.id">
        <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
        <td>{{ berkas.nomor_spdp }}</td>
        <td>{{ berkas.kategori.nama_kategori }}</td>
        <td>{{ berkas.nama_tersangka }}</td>
        <td>{{ berkas.nama_jpu }}</td>
        <td>{{ berkas.tanggal_spdp }}</td>
        <td>{{ berkas.pasal }}</td>
        <td>
          <div class="btn-group">
            <button @click="lihatDetail(berkas)" class="btn-small bg-green">Detail</button>
            <button @click="editBerkas(berkas)" class="btn-small bg-yellow">Update</button>
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
      <button
        v-for="page in totalPages"
        :key="page"
        @click="changePage(page)"
        :class="['btn-page', page === currentPage ? 'active-page' : '']"
      >
        {{ page }}
      </button>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="btn-page">Next →</button>
    </div>

    <!-- Modal Tambah Berkas -->
    <div v-if="showForm" class="modal-overlay">
      <div class="modal-wrapper">
        <div class="modal-content scrollable">
          <button class="modal-close" @click="showForm = false">Tutup</button>
          <h2 class="form-title">Tambah Berkas Perkara</h2>

          <form @submit.prevent="submitForm" class="form-grid">
            <!-- Seluruh isian form -->
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

  </div>

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
          <tr><td class="font-semibold">Tanggal SPDP</td><td>{{ selectedBerkas.tanggal_spdp }}</td></tr>
          <tr><td class="font-semibold">Tanggal Diterima SPDP</td><td>{{ selectedBerkas.kategori.detail_kategori.tanggal_diterima_spdp }}</td></tr>
          <tr><td class="font-semibold">Pasal</td><td>{{ selectedBerkas.pasal }}</td></tr>
          <tr><td class="font-semibold">Tempat Kejadian</td><td>{{ selectedBerkas.tempat_kejadian }}</td></tr>
          <tr><td class="font-semibold">Instansi Penyidik</td><td>{{ selectedBerkas.instansi_penyidik }}</td></tr>
          <tr><td class="font-semibold">Kategori</td><td>{{ selectedBerkas.kategori.nama_kategori }} </td></tr>
          <tr><td class="font-semibold">Nama Tersangka</td><td>{{ selectedBerkas.nama_tersangka }}</td></tr>
          <tr v-if="selectedBerkas.kategori.detail_kategori.nomor_berkas_perkara">
  <td class="font-semibold">Nomor Berkas Perkara</td>
  <td>{{ selectedBerkas.kategori.detail_kategori.nomor_berkas_perkara }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.nomor_pengantar_berkas">
      <td class="font-semibold">Nomor Pengantar Berkas</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.nomor_pengantar_berkas }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.tanggal_berkas_tahap_1">
      <td class="font-semibold">Tanggal Berkas Tahap 1</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.tanggal_berkas_tahap_1 }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.tanggal_p18">
      <td class="font-semibold">Tanggal P-18</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.tanggal_p18 }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.tanggal_p19">
      <td class="font-semibold">Tanggal P-19</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.tanggal_p19 }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.tanggal_berkas_kembali">
      <td class="font-semibold">Tanggal Berkas Kembali</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.tanggal_berkas_kembali }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.tanggal_pengantar_berkas_kembali">
      <td class="font-semibold">Tanggal Pengantar Berkas Kembali</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.tanggal_pengantar_berkas_kembali }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.tanggal_p21">
      <td class="font-semibold">Tanggal P-21</td>
      <td>{{ selectedBerkas.kategori.detail_kategori.tanggal_p21 }}</td>
    </tr>
    <tr v-if="selectedBerkas.kategori.detail_kategori.file_pendukung">
      <td class="font-semibold">File Pendukung</td>
      <td>
        <a :href="getFileUrl(selectedBerkas.kategori.detail_kategori.file_pendukung)" target="_blank" class="text-blue-600 underline">Download File</a>
      </td>
    </tr>
        </tbody>
      </table>
    </div>
  </div>
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

        <!-- Nama Tersangka (editable) -->
        <div class="form-group">
          <label>Nama Tersangka</label>
          <input v-model="updateForm.nama_tersangka" type="text" required />
        </div>

        <!-- Pasal (editable) -->
        <div class="form-group">
          <label>Pasal</label>
          <input v-model="updateForm.pasal" type="text" required />
        </div>

        <!-- Kategori -->
        <div class="form-group">
          <label>Kategori</label>
          <select v-model="updateForm.kategori_id" required>
            <option v-for="kategori in kategoriList" :key="kategori.id" :value="kategori.id"
              :disabled="kategori.urutan < currentKategoriUrutan">
              {{ kategori.nama_kategori }}
            </option>
          </select>
        </div>

        <!-- Tambahan Berdasarkan Kategori -->
            <div class="form-group" v-if="getKategoriKode(updateForm.kategori_id) === 'BT1'">
              <label>Nomor Berkas</label>
              <input v-model="updateForm.nomor_berkas" type="text" />
              <label>Nomor Pengantar</label>
              <input v-model="updateForm.nomor_pengantar" type="text" />
              <label>Tanggal Berkas Tahap 1</label>
              <input v-model="updateForm.tanggal_berkas" type="date" />
            </div>

            <div class="form-group" v-if="getKategoriKode(updateForm.kategori_id) === 'P-18'">
              <label>Tanggal P-18</label>
              <input v-model="updateForm.tanggal_p18" type="date" />
            </div>

            <div class="form-group" v-if="getKategoriKode(updateForm.kategori_id) === 'P-19'">
              <label>Tanggal P-19</label>
              <input v-model="updateForm.tanggal_p19" type="date" />
            </div>

            <div class="form-group" v-if="getKategoriKode(updateForm.kategori_id) === 'BT1K'">
              <label>Tanggal Berkas Kembali</label>
              <input v-model="updateForm.tanggal_berkas_kembali" type="date" />
              <label>Nomor Pengantar Berkas Kembali</label>
              <input v-model="updateForm.tanggal_pengantar_berkas_kembali" type="text" />
            </div>

            <div class="form-group" v-if="getKategoriKode(updateForm.kategori_id) === 'P-21'">
              <label>Tanggal P-21</label>
              <input v-model="updateForm.tanggal_p21" type="date" />
            </div>

            <div class="form-group" v-if="['P-18', 'P-19', 'P-21'].includes(getKategoriKode(updateForm.kategori_id))">
              <label>Upload Surat Pendukung (PDF)</label>
              <input type="file" accept="application/pdf" @change="handleUpdateFileUpload" />
            </div>

        <button type="submit" class="btn-primary w-full mt-4">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
</template>



<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import '@/assets/css/kelola-berkas.css'

const form = ref({
  kategori_id: null,
  nomor_spdp: '',
  nama_tersangka: '',
  nama_jpu: '',
  tanggal_spdp: '',
  tanggal_diterima_spdp: '',
  pasal: '',
  tempat_kejadian: '',
  instansi_penyidik: '',
  nomor_berkas: '',
  nomor_pengantar: '',
  file_pendukung: null,
});



const berkasList = ref([]);
const showForm = ref(false);
const kategoriList = ref([]);
const currentPage = ref(1);
const perPage = 12;
const selectedBerkas = ref(null);
const showDetail = ref(false);
const showUpdateForm = ref(false);
const updateForm = ref({});
const currentKategoriUrutan = ref(1);
const searchQuery = ref("");

async function fetchBerkas() {
  try {
    const res = await axios.get('http://localhost:8000/api/berkas-perkara', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    berkasList.value = res.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    console.log('Isi berkasList dari server:', res.data);
  } catch (error) {
    console.error('Gagal ambil data', error);
  }
}

async function fetchKategori() {
  try {
    const res = await axios.get('http://localhost:8000/api/kategori', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    kategoriList.value = res.data;
     console.log('Kategori berhasil diambil:', kategoriList.value);
  } catch (error) {
    console.error('Gagal ambil kategori:', error);
  }
}

async function submitForm() {
  const defaultKategori = kategoriList.value.find(k => k.kode_kategori === 'P-16');
  if (!defaultKategori) {
    alert('❌ Kategori P-16 tidak ditemukan.');
    return;
  }

  const formData = new FormData();
  formData.append('kategori_id', defaultKategori.id);

  // Kirim semua field KECUALI kategori_id dan tanggal_diterima_spdp
  for (const key in form.value) {
    if (form.value[key] !== null && key !== 'kategori_id' && key !== 'tanggal_diterima_spdp') {
      formData.append(key, form.value[key]);
    }
  }

  // Kirim tanggal_diterima_spdp secara terpisah (ke detail_kategori)
  if (form.value.tanggal_diterima_spdp) {
    formData.append('tanggal_diterima_spdp', form.value.tanggal_diterima_spdp);
  }

  try {
    await axios.post('http://localhost:8000/api/berkas-perkara', formData, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'multipart/form-data'
      }
    });

    alert('✅ Berkas berhasil disimpan!');
    await fetchBerkas();
    showForm.value = false;

    // Reset semua field
    Object.keys(form.value).forEach(key => form.value[key] = '');
  } catch (error) {
    console.error('❌ Gagal menyimpan:', error.response?.data || error);
    alert('❌ Terjadi kesalahan saat menyimpan data.');
  }
}



async function lihatDetail(berkas) {
  try {
    const response = await axios.get(`http://localhost:8000/api/berkas-perkara/${berkas.id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    });

    console.log("HASIL DETAIL:", response.data);
    selectedBerkas.value = response.data;
    showDetail.value = true;
  } catch (error) {
    console.error("❌ Gagal ambil detail:", error.response?.data || error);
    alert("❌ Gagal mengambil data detail dari server.");
  }
  console.log("SET showDetail = true");
}

function editBerkas(berkas) {
  const detail = berkas.kategori.detail_kategori || {};
  updateForm.value = {
    id: berkas.id,
    kategori_id: berkas.kategori.id,
    kategori_nama: berkas.kategori.nama_kategori,
    urutan: berkas.kategori.urutan,
    nomor_spdp: berkas.nomor_spdp,
    nama_tersangka: berkas.nama_tersangka,
    nama_jpu: berkas.nama_jpu,
    pasal: berkas.pasal,
    nomor_berkas: detail.nomor_berkas_perkara || '',
    nomor_pengantar: detail.nomor_pengantar_berkas || '',
    tanggal_berkas: detail.tanggal_berkas_tahap_1 || '',
    tanggal_p18: detail.tanggal_p18 || '',
    tanggal_p19: detail.tanggal_p19 || '',
    tanggal_p21: detail.tanggal_p21 || '',
    tanggal_berkas_kembali: detail.tanggal_berkas_kembali || '',
    tanggal_pengantar_berkas_kembali: detail.nomor_pengantar_berkas_kembali || '',
    file_pendukung: null
  };
  currentKategoriUrutan.value = berkas.kategori.urutan;
  showUpdateForm.value = true;
}

async function submitUpdate() {
  const formData = new FormData();
  formData.append('kategori', getKategoriKode(updateForm.value.kategori_id));
  formData.append('pasal', updateForm.value.pasal);
  formData.append('nama_tersangka', updateForm.value.nama_tersangka);

  const kode = getKategoriKode(updateForm.value.kategori_id);

  if (kode === 'BT1') {
    formData.append('nomor_berkas', updateForm.value.nomor_berkas);
    formData.append('nomor_pengantar', updateForm.value.nomor_pengantar);
    formData.append('tanggal_berkas', updateForm.value.tanggal_berkas);
  }
  if (kode === 'P-18') {
    formData.append('tanggal_p18', updateForm.value.tanggal_p18);
  }
  if (kode === 'P-19') {
    formData.append('tanggal_p19', updateForm.value.tanggal_p19);
  }
  if (kode === 'BT1K') {
    formData.append('tanggal_berkas_kembali', updateForm.value.tanggal_berkas_kembali);
    formData.append('tanggal_pengantar_berkas_kembali', updateForm.value.tanggal_pengantar_berkas_kembali);
  }
  if (kode === 'P-21') {
    formData.append('tanggal_p21', updateForm.value.tanggal_p21);
  }
  if (updateForm.value.file_pendukung) {
    formData.append('file_pendukung', updateForm.value.file_pendukung);
  }

  try {
    await axios.post(
      `http://localhost:8000/api/berkas-perkara/${updateForm.value.id}?_method=PUT`,
      formData,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
          'Content-Type': 'multipart/form-data'
        }
      }
    );
    alert('✅ Berkas berhasil diupdate');
    showUpdateForm.value = false;
    await fetchBerkas();
  } catch (error) {
    console.error('❌ Error update:', error);
    alert('❌ Gagal update data.');
  }
}

function getFileUrl(path) {
  return `http://localhost:8000/storage/${path}`;
}


function getKategoriKode(id) {
  const kategori = kategoriList.value.find(k => k.id === id);
  return kategori ? kategori.kode_kategori : '';
}

function handleUpdateFileUpload(e) {
  updateForm.value.file_pendukung = e.target.files[0];
}


async function hapusBerkas(id) {
  const konfirmasi = confirm("Apakah kamu yakin ingin menghapus data ini?");
  if (!konfirmasi) return;

  try {
    await axios.delete(`http://localhost:8000/api/berkas-perkara/${id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    alert('🗑️ Berkas berhasil dihapus.');
    await fetchBerkas();
  } catch (error) {
    console.error('❌ Gagal menghapus:', error);
    alert('❌ Terjadi kesalahan saat menghapus.');
  }
}

//Paginasi
const filteredBerkas = computed(() => {
  const q = searchQuery.value.toLowerCase()
  return berkasList.value.filter(b =>
    b.nomor_spdp?.toLowerCase().includes(q) ||
    b.nama_jpu?.toLowerCase().includes(q) ||
    b.nama_tersangka?.toLowerCase().includes(q)
  )
})

const totalPages = computed(() =>
  Math.ceil(filteredBerkas.value.length / perPage)
)

const paginatedBerkas = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredBerkas.value.slice(start, start + perPage)
})

function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}
function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++
}
function prevPage() {
  if (currentPage.value > 1) currentPage.value--
}

onMounted(() => {
  fetchBerkas();
  fetchKategori();
});
</script>

