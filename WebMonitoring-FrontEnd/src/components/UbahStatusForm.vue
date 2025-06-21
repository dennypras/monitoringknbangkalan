<template>
  <div class="p-4 bg-white shadow rounded max-w-xl mx-auto">
    <h2 class="text-lg font-semibold mb-4">Ubah Status Berkas</h2>

    <form @submit.prevent="submitKategoriUpdate" class="grid gap-4">
      <label>
        Pilih Status Baru:
        <select v-model="form.kategori" required>
          <option>P-17</option>
          <option>Berkas Tahap 1</option>
          <option>P-18</option>
          <option>P-19</option>
          <option>Berkas Tahap 1 Kembali</option>
          <option>P-21</option>
        </select>
      </label>

      <div v-if="['Berkas Tahap 1', 'Berkas Tahap 1 Kembali'].includes(form.kategori)">
        <input v-model="form.nomor_berkas" type="text" placeholder="Nomor Berkas" />
        <input v-model="form.nomor_pengantar" type="text" placeholder="Nomor Pengantar Berkas" />
      </div>

      <div v-if="['P-18', 'P-19', 'P-21'].includes(form.kategori)">
        <label>Upload Surat Pendukung (PDF):</label>
        <input type="file" accept="application/pdf" @change="handleFileUpload" />
      </div>

      <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
        Simpan Perubahan
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  id: Number // ID dari berkas yang ingin diubah
})

const form = ref({
  kategori: '',
  nomor_berkas: '',
  nomor_pengantar: '',
  file_pendukung: null
})

function handleFileUpload(e) {
  form.value.file_pendukung = e.target.files[0]
}

async function submitKategoriUpdate() {
  const formData = new FormData()
  formData.append('kategori', form.value.kategori)
  if (form.value.nomor_berkas) formData.append('nomor_berkas', form.value.nomor_berkas)
  if (form.value.nomor_pengantar) formData.append('nomor_pengantar', form.value.nomor_pengantar)
  if (form.value.file_pendukung) formData.append('file_pendukung', form.value.file_pendukung)

  try {
    const res = await axios.patch(`http://localhost:8000/api/berkas-perkara/${props.id}/kategori`, formData, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'multipart/form-data'
      }
    })

    alert('✅ Status berhasil diubah')
    console.log(res.data)
  } catch (error) {
    console.error('❌ Gagal update kategori', error.response?.data || error)
    alert('❌ Gagal update kategori')
  }
}
</script>
