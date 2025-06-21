<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('kategori', function (Blueprint $table) {
        $table->unsignedBigInteger('detail_kategori_id')->nullable()->after('urutan');
        $table->foreign('detail_kategori_id')->references('id')->on('detail_kategori')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('kategori', function (Blueprint $table) {
        $table->dropForeign(['detail_kategori_id']);
        $table->dropColumn('detail_kategori_id');
    });
}

};
