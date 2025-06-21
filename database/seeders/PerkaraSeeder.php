<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PerkaraSeeder extends Seeder
{
    public function run()
    {
        $namaTersangka = ['Anton', 'Budi', 'Citra', 'Dewi', 'Eka', 'Farhan', 'Gina', 'Hendri', 'Indra', 'Joko'];
        $namaJPU = ['Agus', 'Bambang', 'Cahya', 'Dina', 'Elisa', 'Fajar', 'Galih', 'Hari', 'Iqbal', 'Jamilah'];

        // Ambil kategori_id dari tabel kategori
        $kategoriIds = DB::table('kategori')->pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            DB::table('perkara')->insert([
                'kategori_id' => $kategoriIds[array_rand($kategoriIds)],
                'nomor_spdp' => 'SPDP-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '/V/RES.1.24./2025/Satreskrim',
                'tanggal_spdp' => Carbon::createFromDate(2025, rand(1, 6), rand(1, 28)),
                'nama_tersangka' => $namaTersangka[array_rand($namaTersangka)] . ' ' . Str::random(5),
                'nama_jpu' => $namaJPU[array_rand($namaJPU)] . ' ' . Str::random(4),
                'pasal' => 'Pasal ' . rand(300, 365) . ' KUHP',
                'tempat_kejadian' => 'Tempat Kejadian #' . $i,
                'instansi_penyidik' => 'Polres Kota ' . chr(65 + ($i % 26)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
