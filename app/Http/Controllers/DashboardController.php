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
    $query = DB::table('detail_kategori');

    if ($request->has('tanggal_mulai') && $request->has('tanggal_akhir')) {
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;

        $query->whereBetween('tanggal_diterima_spdp', [$tanggalMulai, $tanggalAkhir]);
    }

    $data = $query->get();

    // Perhitungan kategori berdasarkan keberadaan tanggal_* di setiap baris
    $statistik = [
        'P-16' => $data->count(), // semua data dihitung sebagai P-16
        'Berkas Tahap 1' => $data->whereNotNull('tanggal_berkas_tahap_1')->count(),
        'P-18' => $data->whereNotNull('tanggal_p18')->count(),
        'P-19' => $data->whereNotNull('tanggal_p19')->count(),
        'Pengembalian Berkas Tahap 1' => $data->whereNotNull('tanggal_berkas_kembali')->count(),
        'P-21' => $data->whereNotNull('tanggal_p21')->count(),
    ];

    // Format agar cocok dengan frontend
    $result = [];
    foreach ($statistik as $label => $jumlah) {
        $result[] = [
            'label' => $label,
            'jumlah' => $jumlah
        ];
    }

    return response()->json($result);
}
//    public function getStatistik(Request $request)
// {
//     $from = $request->query('from');
//     $to = $request->query('to');

//     $kategoriTanggalField = [
//         'P-16' => 'tanggal_diterima_spdp',
//         'Berkas Tahap 1' => 'tanggal_berkas_tahap_1',
//         'P-18' => 'tanggal_p18',
//         'P-19' => 'tanggal_p19',
//         'Berkas Tahap 1 Kembali' => 'tanggal_berkas_kembali',
//         'P-21' => 'tanggal_p21',
//     ];

//     $result = [];

//     foreach ($kategoriTanggalField as $kode => $tanggalField) {
//         $query = \App\Models\Perkara::whereHas('kategori', function ($q) use ($kode) {
//             $q->where('nama_kategori', $kode);
//         });

//         if ($tanggalField && $from && $to) {
//             $query->whereHas('kategori.detailKategori', function ($q) use ($tanggalField, $from, $to) {
//                 $q->whereBetween($tanggalField, [$from, $to]);
//             });
//         }

//         $jumlah = $query->count();

//         $result[] = [
//             'label' => $kode,
//             'jumlah' => $jumlah
//         ];
//     }

//     return response()->json($result);
// }

}
