<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('perkara', function (Blueprint $table) {
        $table->dropColumn('tanggal_diterima_spdp');
    });

    Schema::table('detail_kategori', function (Blueprint $table) {
        $table->date('tanggal_diterima_spdp')->nullable()->after('tanggal_spdp');
    });
}

public function down()
{
    Schema::table('perkara', function (Blueprint $table) {
        $table->date('tanggal_diterima_spdp')->nullable()->after('tanggal_spdp');
    });

    Schema::table('detail_kategori', function (Blueprint $table) {
        $table->dropColumn('tanggal_diterima_spdp');
    });
}

};
