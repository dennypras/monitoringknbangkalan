<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kategori'); // contoh: P-16, P-17, dst
        $table->string('nama_kategori'); // contoh: "Penerimaan SPDP"
        $table->integer('urutan'); // untuk validasi kemajuan status
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::create('kategori', function (Blueprint $table) {
        // $table->id();
        // $table->string('kode_kategori'); // Contoh: P-16, P-17, dst
        // $table->string('nama_kategori');
        // $table->integer('urutan'); // Digunakan untuk validasi urutan kategori
        // $table->timestamps();
    // });
    Schema::create('kategori', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kategori');
    $table->unsignedBigInteger('detail_kategori_id')->nullable();
    $table->foreign('detail_kategori_id')->references('id')->on('detail_kategori')->nullOnDelete();
    $table->timestamps();
});
    }
    
};
