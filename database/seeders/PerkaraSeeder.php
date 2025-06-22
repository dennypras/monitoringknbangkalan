<?php

// database/seeders/PerkaraSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perkara;
use App\Models\DetailKategori;
use App\Models\Kategori;
use Illuminate\Support\Str;

class PerkaraSeeder extends Seeder
{
    public function run()
    {
        $kategoriP16 = Kategori::where('nama_kategori', 'P-16')->first();

        for ($i = 1; $i <= 50; $i++) {
            $perkara = Perkara::create([
                'kategori_id' => $kategoriP16->id,
                'nomor_spdp' => 'SPDP-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '/V/2025',
                'nama_tersangka' => fake()->name(),
                'nama_jpu' => fake()->name(),
                'tanggal_spdp' => fake()->dateTimeBetween('-60 days', 'now'),
                'pasal' => 'Pasal ' . rand(340, 378) . ' KUHP',
                'tempat_kejadian' => fake()->city(),
                'instansi_penyidik' => 'Polres ' . fake()->city()
            ]);

            DetailKategori::create([
                'perkara_id' => $perkara->id,
                'tanggal_diterima_spdp' => fake()->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
}
