<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Perkara;
use App\Models\Kategori;

class DashboardController extends Controller
{
   public function getStatistik(Request $request)
{
    $tanggalMulai = $request->tanggal_mulai;
    $tanggalAkhir = $request->tanggal_akhir;

    // Ambil semua data dari tabel detail_kategori
    $data = DB::table('detail_kategori')->get();

    $statistik = [
        'P-16' => 0,
        'Berkas Tahap 1' => 0,
        'P-18' => 0,
        'P-19' => 0,
        'Pengembalian Berkas Tahap 1' => 0,
        'P-21' => 0,
    ];

    foreach ($data as $row) {
        // Cek masing-masing tanggal kategori terhadap rentang filter
        if ($tanggalMulai && $tanggalAkhir) {
            if ($row->tanggal_diterima_spdp >= $tanggalMulai && $row->tanggal_diterima_spdp <= $tanggalAkhir) {
                $statistik['P-16']++;
            }
            if ($row->tanggal_berkas_tahap_1 >= $tanggalMulai && $row->tanggal_berkas_tahap_1 <= $tanggalAkhir) {
                $statistik['Berkas Tahap 1']++;
            }
            if ($row->tanggal_p18 >= $tanggalMulai && $row->tanggal_p18 <= $tanggalAkhir) {
                $statistik['P-18']++;
            }
            if ($row->tanggal_p19 >= $tanggalMulai && $row->tanggal_p19 <= $tanggalAkhir) {
                $statistik['P-19']++;
            }
            if ($row->tanggal_berkas_kembali >= $tanggalMulai && $row->tanggal_berkas_kembali <= $tanggalAkhir) {
                $statistik['Pengembalian Berkas Tahap 1']++;
            }
            if ($row->tanggal_p21 >= $tanggalMulai && $row->tanggal_p21 <= $tanggalAkhir) {
                $statistik['P-21']++;
            }
        } else {
            // Jika tidak ada filter tanggal, hitung semua data (default)
            $statistik['P-16']++;
            if ($row->tanggal_berkas_tahap_1) $statistik['Berkas Tahap 1']++;
            if ($row->tanggal_p18) $statistik['P-18']++;
            if ($row->tanggal_p19) $statistik['P-19']++;
            if ($row->tanggal_berkas_kembali) $statistik['Pengembalian Berkas Tahap 1']++;
            if ($row->tanggal_p21) $statistik['P-21']++;
        }
    }

    // Format hasil untuk frontend
    $result = [];
    foreach ($statistik as $label => $jumlah) {
        $result[] = [
            'label' => $label,
            'jumlah' => $jumlah
        ];
    }

    return response()->json($result);
}

}
