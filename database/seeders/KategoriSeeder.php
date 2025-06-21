<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            ['kode_kategori' => 'P-16', 'nama_kategori' => 'P-16'],
            ['kode_kategori' => 'P-17', 'nama_kategori' => 'P-17'],
            ['kode_kategori' => 'BT1',  'nama_kategori' => 'Berkas Tahap 1'],
            ['kode_kategori' => 'P-18', 'nama_kategori' => 'P-18'],
            ['kode_kategori' => 'P-19', 'nama_kategori' => 'P-19'],
            ['kode_kategori' => 'BT1K', 'nama_kategori' => 'Berkas Tahap 1 Kembali'],
            ['kode_kategori' => 'P-21', 'nama_kategori' => 'P-21'],
        ];

        foreach ($kategori as $i => $row) {
            DB::table('kategori')->insert([
                'id' => $i + 1,
                'kode_kategori' => $row['kode_kategori'],
                'nama_kategori' => $row['nama_kategori'],
                'urutan' => $i + 1,
                'detail_kategori_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
