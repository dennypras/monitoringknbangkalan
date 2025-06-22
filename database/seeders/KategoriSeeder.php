<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            'P-16', 'P-17', 'Berkas Tahap 1', 'P-18', 'P-19', 'Berkas Tahap 1 Kembali', 'P-21'
        ];

        foreach ($kategori as $index => $nama) {
            Kategori::create([
                'nama_kategori' => $nama,
                'urutan' => $index + 1
            ]);
        }
    }
}