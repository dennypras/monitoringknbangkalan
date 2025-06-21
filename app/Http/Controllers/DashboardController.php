<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perkara;
use App\Models\Kategori;

class DashboardController extends Controller
{
   public function getStatistik(Request $request)
{
    $from = $request->query('from');
    $to = $request->query('to');

    $kategoriTanggalField = [
        'P-16' => 'tanggal_diterima_spdp',
        'Berkas Tahap 1' => 'tanggal_berkas_tahap_1',
        'P-18' => 'tanggal_p18',
        'P-19' => 'tanggal_p19',
        'Berkas Tahap 1 Kembali' => 'tanggal_berkas_kembali',
        'P-21' => 'tanggal_p21',
    ];

    $result = [];

    foreach ($kategoriTanggalField as $kode => $tanggalField) {
        $query = \App\Models\Perkara::whereHas('kategori', function ($q) use ($kode) {
            $q->where('nama_kategori', $kode);
        });

        if ($tanggalField && $from && $to) {
            $query->whereHas('kategori.detailKategori', function ($q) use ($tanggalField, $from, $to) {
                $q->whereBetween($tanggalField, [$from, $to]);
            });
        }

        $jumlah = $query->count();

        $result[] = [
            'label' => $kode,
            'jumlah' => $jumlah
        ];
    }

    return response()->json($result);
}

}
