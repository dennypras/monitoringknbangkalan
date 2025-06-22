<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perkara extends Model
{
    use HasFactory;

    protected $table = 'perkara';

    protected $fillable = [
        'kategori_id',
        'nomor_spdp',
        'nama_tersangka',
        'nama_jpu',
        'tanggal_spdp',
        'pasal',
        'tempat_kejadian',
        'instansi_penyidik',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detailKategori()
    {
        return $this->hasOne(DetailKategori::class);
    }
}