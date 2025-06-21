<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Perkara;
use App\Models\Kategori;
use App\Models\DetailKategori;

class PerkaraController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nomor_spdp' => 'required|string|max:100',
            'nama_tersangka' => 'required|string',
            'nama_jpu' => 'required|string|max:100',
            'tanggal_spdp' => 'required|date',
            'tanggal_diterima_spdp' => 'required|date',
            'pasal' => 'required|string|max:255',
            'tempat_kejadian' => 'required|string',
            'instansi_penyidik' => 'required|string|max:100',
        ]);

        $berkas = new Perkara();
        $berkas->fill($validated);
        $berkas->save();

        // Tambahkan detail_kategori otomatis untuk P-16
        $kategori = Kategori::find($validated['kategori_id']);
        if ($kategori->kode_kategori === 'P-16') {
            $detail = new DetailKategori();
            $detail->tanggal_diterima_spdp = $validated['tanggal_diterima_spdp'];
            $detail->save();

            $kategori->detail_kategori_id = $detail->id;
            $kategori->save();
        }

        return response()->json(['message' => 'Berkas berhasil disimpan', 'data' => $berkas]);
    }

    public function updateKategori(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:P-17,Berkas Tahap 1,P-18,P-19,Berkas Tahap 1 Kembali,P-21',
            'nomor_berkas' => 'nullable|string|max:100',
            'nomor_pengantar' => 'nullable|string|max:100',
            'tanggal_berkas' => 'nullable|date',
            'tanggal_p18' => 'nullable|date',
            'tanggal_p19' => 'nullable|date',
            'tanggal_berkas_kembali' => 'nullable|date',
            'tanggal_pengantar_berkas_kembali' => 'nullable|string|max:100',
            'tanggal_p21' => 'nullable|date',
            'file_pendukung' => 'nullable|file|mimes:pdf|max:4048',
        ]);

        $berkas = Perkara::findOrFail($id);

        $urutan = [
            'P-16' => 1,
            'P-17' => 2,
            'Berkas Tahap 1' => 3,
            'P-18' => 4,
            'P-19' => 5,
            'Berkas Tahap 1 Kembali' => 6,
            'P-21' => 7
        ];

        $current = $berkas->kategori->nama_kategori;
        $next = $validated['kategori'];

        if ($urutan[$next] <= $urutan[$current]) {
            return response()->json(['message' => 'Tidak bisa kembali ke status sebelumnya'], 422);
        }

        // Simpan file pendukung jika ada
        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('surat_pendukung', 'public');
        }

        $kategori = Kategori::where('nama_kategori', $next)->first();
        $detail = $kategori->detailKategori ?: new DetailKategori();

        switch ($next) {
            case 'Berkas Tahap 1':
                $detail->nomor_berkas_perkara = $validated['nomor_berkas'];
                $detail->nomor_pengantar_berkas = $validated['nomor_pengantar'];
                $detail->tanggal_berkas_tahap_1 = $validated['tanggal_berkas'];
                break;
            case 'P-18':
                $detail->tanggal_p18 = $validated['tanggal_p18'];
                break;
            case 'P-19':
                $detail->tanggal_p19 = $validated['tanggal_p19'];
                break;
            case 'Berkas Tahap 1 Kembali':
                $detail->tanggal_berkas_kembali = $validated['tanggal_berkas_kembali'];
                $detail->nomor_pengantar_berkas_kembali = $validated['tanggal_pengantar_berkas_kembali'];
                break;
            case 'P-21':
                $detail->tanggal_p21 = $validated['tanggal_p21'];
                break;
        }

        if ($filePath) {
            $detail->file_pendukung = $filePath;
        }

        $detail->save();
        $kategori->detail_kategori_id = $detail->id;
        $kategori->save();

        $berkas->kategori_id = $kategori->id;
        $berkas->save();

        return response()->json(['message' => 'Status kategori berhasil diperbarui', 'data' => $berkas]);
    }

    public function index()
    {
        $berkas = Perkara::with('kategori.detailKategori')->latest()->get();
        return response()->json($berkas);
    }
}
