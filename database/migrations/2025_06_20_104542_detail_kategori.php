<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('detail_kategori', function (Blueprint $table) {
        $table->string('file_pendukung_p18')->nullable();
        $table->string('file_pendukung_p19')->nullable();
        $table->string('file_pendukung_p21')->nullable();
        // $table->id();
        // $table->foreignId('perkara_id')->constrained('perkara')->onDelete('cascade');
        // $table->date('tanggal_diterima_spdp')->nullable();
        // $table->string('nomor_berkas_perkara')->nullable();
        // $table->string('nomor_pengantar_berkas')->nullable();
        // $table->date('tanggal_berkas_tahap_1')->nullable();
        // $table->date('tanggal_p18')->nullable();
        // $table->date('tanggal_p19')->nullable();
        // $table->date('tanggal_berkas_kembali')->nullable();
        // $table->string('nomor_pengantar_berkas_kembali')->nullable();
        // $table->date('tanggal_p21')->nullable();
        // $table->string('file_pendukung')->nullable();
        // $table->timestamps();
    });
}
};
