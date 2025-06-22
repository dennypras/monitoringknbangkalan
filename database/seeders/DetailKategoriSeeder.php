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
        // Ambil semua data perkara yang belum memiliki detail_kategori
        $perkaraList = DB::table('perkara')->whereNull('detail_kategori_id')->get();

        foreach ($perkaraList as $perkara) {
            // Buat detail_kategori baru
            $detailId = DB::table('detail_kategori')->insertGetId([
                'tanggal_diterima_spdp' => $perkara->tanggal_diterima_spdp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update perkara dengan detail_kategori_id
            DB::table('perkara')->where('id', $perkara->id)->update([
                'detail_kategori_id' => $detailId,
                'updated_at' => now(),
            ]);
        }
    }
}
