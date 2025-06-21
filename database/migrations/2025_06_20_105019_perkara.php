<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('perkara', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('kategori_id');
    $table->string('nomor_spdp');
    $table->date('tanggal_spdp');
    $table->string('nama_jpu');
    $table->string('pasal');
    $table->string('tempat_kejadian');
    $table->string('instansi_penyidik');
    $table->string('nama_tersangka');
    $table->foreign('kategori_id')->references('id')->on('kategori')->cascadeOnDelete();
    $table->timestamps();
});
    }
};
