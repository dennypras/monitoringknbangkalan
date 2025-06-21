<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perkara extends Model {
    use HasFactory;
    protected $table = 'perkara';
    protected $fillable = [
        'kategori_id',
        'nomor_spdp',
        'tanggal_spdp',
        'nama_jpu',
        'pasal',
        'tempat_kejadian',
        'instansi_penyidik',
        'nama_tersangka',
    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }
}
