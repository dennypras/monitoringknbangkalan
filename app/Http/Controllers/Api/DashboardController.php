<?php

namespace App\Http\Controllers;
use App\Models\BerkasPerkara;

class DashboardController extends Controller
{

    public function getStatistik()
{
    $kategori = [
        'Penerimaan SPDP', 'Pengembalian SPDP','Tahap I', 'Belum Lengkap', 'Kembali ke Penyidik
', 'Berkas Kembali', 'Berkas Lengkap'];

    $result = [];

    foreach ($kategori as $k) {
        $jumlah = BerkasPerkara::whereHas('kategori', function ($q) use ($k) {
            $q->where('nama_kategori', $k);
        })->count();

        $result[] = [
            'label' => $k,
            'jumlah' => $jumlah
        ];
    }

    return response()->json($result)->header('Cache-Control', 'no-store');

}

}
