<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Perkara;
use App\Models\Kategori;
use App\Models\DetailKategori;

class PerkaraController extends Controller
{
    public function index()
    {
        $perkara = Perkara::with(['kategori', 'detailKategori'])->latest()->get();
        return response()->json($perkara);
    }

    public function show($id)
    {
        $perkara = Perkara::with(['kategori', 'detailKategori'])->findOrFail($id);
        return response()->json($perkara);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_spdp' => 'required|string|unique:perkara',
            'nama_jpu' => 'required|string',
            'tanggal_spdp' => 'required|date',
            'tanggal_diterima_spdp' => 'required|date',
            'pasal' => 'required|string',
            'tempat_kejadian' => 'required|string',
            'instansi_penyidik' => 'required|string',
            'nama_tersangka' => 'required|string'
        ]);

        $kategori = Kategori::where('nama_kategori', 'P-16')->firstOrFail();

        $perkara = Perkara::create([
            'kategori_id' => $kategori->id,
            'nomor_spdp' => $request->nomor_spdp,
            'nama_tersangka' => $request->nama_tersangka,
            'nama_jpu' => $request->nama_jpu,
            'tanggal_spdp' => $request->tanggal_spdp,
            'pasal' => $request->pasal,
            'tempat_kejadian' => $request->tempat_kejadian,
            'instansi_penyidik' => $request->instansi_penyidik
        ]);

        DetailKategori::create([
            'perkara_id' => $perkara->id,
            'tanggal_diterima_spdp' => $request->tanggal_diterima_spdp
        ]);

        return response()->json(['message' => 'Perkara berhasil ditambahkan', 'data' => $perkara]);
    }

   public function updateKategori(Request $request, $id)
{
    $request->validate([
        'kategori_id' => 'required|integer',
        'nama_tersangka' => 'required|string',
        'pasal' => 'required|string',
        'nomor_berkas' => 'nullable|string|max:100',
        'nomor_pengantar' => 'nullable|string|max:100',
        'tanggal_berkas' => 'nullable|date',
        'tanggal_p18' => 'nullable|date',
        'tanggal_p19' => 'nullable|date',
        'tanggal_berkas_kembali' => 'nullable|date',
        'nomor_pengantar_berkas_kembali' => 'nullable|string|max:100',
        'tanggal_p21' => 'nullable|date',
        'file_pendukung' => 'nullable|file|mimes:pdf|max:4096',
    ]);

    $perkara = Perkara::findOrFail($id);
    $kategoriBaru = Kategori::findOrFail($request->kategori_id);

    $detail = $perkara->detailKategori ?? new DetailKategori(['perkara_id' => $perkara->id]);

    switch ($kategoriBaru->nama_kategori) {
        case 'Berkas Tahap 1':
            $detail->nomor_berkas_perkara = $request->nomor_berkas;
            $detail->nomor_pengantar_berkas = $request->nomor_pengantar;
            $detail->tanggal_berkas_tahap_1 = $request->tanggal_berkas;
            break;
        case 'P-18':
            $detail->tanggal_p18 = $request->tanggal_p18;
            break;
        case 'P-19':
            $detail->tanggal_p19 = $request->tanggal_p19;
            break;
        case 'Berkas Tahap 1 Kembali':
            $detail->tanggal_berkas_kembali = $request->tanggal_berkas_kembali;
            $detail->nomor_pengantar_berkas_kembali = $request->nomor_pengantar_berkas_kembali;
            break;
        case 'P-21':
            $detail->tanggal_p21 = $request->tanggal_p21;
            break;
    }

    if ($request->hasFile('file_pendukung')) {
        $detail->file_pendukung = $request->file('file_pendukung')->store('surat_pendukung', 'public');
    }

    $detail->save();

    $perkara->kategori_id = $kategoriBaru->id;
    $perkara->nama_tersangka = $request->nama_tersangka;
    $perkara->pasal = $request->pasal;
    $perkara->save();

    return response()->json(['message' => 'Kategori dan detail berhasil diperbarui', 'data' => $perkara]);
}


    public function destroy($id)
    {
        $perkara = Perkara::findOrFail($id);

        if ($perkara->detailKategori && $perkara->detailKategori->file_pendukung) {
            Storage::disk('public')->delete($perkara->detailKategori->file_pendukung);
        }

        $perkara->delete();
        return response()->json(['message' => 'Berkas berhasil dihapus']);
    }
}
