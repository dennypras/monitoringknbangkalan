<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perkara', function (Blueprint $table) {
            $table->bigIncrements('id');

            // ✅ Foreign key harus cocok: bigInteger unsigned
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');

            $table->string('nomor_spdp')->unique();
            $table->text('nama_tersangka');
            $table->string('nama_jpu');
            $table->date('tanggal_spdp');
            $table->string('pasal');
            $table->string('tempat_kejadian');
            $table->string('instansi_penyidik');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkara');
    }
};
