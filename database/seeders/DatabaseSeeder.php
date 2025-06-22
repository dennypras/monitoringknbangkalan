<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PenggunaSeeder::class);
        // $this->call(BerkasPerkaraSeeder::class);
    $this->call([
        
    KategoriSeeder::class,
    PerkaraSeeder::class,
    PenggunaSeeder::class,
]);

    }   
}
