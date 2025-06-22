<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->bigIncrements('id'); // ✅ tipe ID yang cocok untuk relasi
            $table->string('nama_kategori')->unique();
            $table->unsignedInteger('urutan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
