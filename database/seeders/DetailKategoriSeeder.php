<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DetailKategoriSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua kategori yang memiliki detail_kategori_id null
        $kategoriList = DB::table('kategori')->whereNull('detail_kategori_id')->get();

        foreach ($kategoriList as $kategori) {
            $detailId = DB::table('detail_kategori')->insertGetId([
                'tanggal_diterima_spdp' => $kategori->kode_kategori === 'P-16' ? Carbon::now()->subDays(rand(1, 20)) : null,
                'tanggal_berkas_tahap_1' => $kategori->kode_kategori === 'BT1' ? Carbon::now()->subDays(rand(1, 20)) : null,
                'tanggal_p18' => $kategori->kode_kategori === 'P-18' ? Carbon::now()->subDays(rand(1, 20)) : null,
                'tanggal_p19' => $kategori->kode_kategori === 'P-19' ? Carbon::now()->subDays(rand(1, 20)) : null,
                'tanggal_berkas_kembali' => $kategori->kode_kategori === 'BT1K' ? Carbon::now()->subDays(rand(1, 20)) : null,
                'tanggal_p21' => $kategori->kode_kategori === 'P-21' ? Carbon::now()->subDays(rand(1, 20)) : null,
                'nomor_berkas_perkara' => $kategori->kode_kategori === 'BT1' ? 'BRK-' . rand(100, 999) : null,
                'nomor_pengantar_berkas' => $kategori->kode_kategori === 'BT1' ? 'PNTR-' . rand(100, 999) : null,
                'nomor_pengantar_berkas_kembali' => $kategori->kode_kategori === 'BT1K' ? 'KMBK-' . rand(100, 999) : null,
                'file_pendukung' => in_array($kategori->kode_kategori, ['P-18', 'P-19', 'P-21']) ? 'surat_pendukung/dummy.pdf' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update kategori untuk menautkan detail_kategori
            DB::table('kategori')
                ->where('id', $kategori->id)
                ->update(['detail_kategori_id' => $detailId]);
        }
    }
}
