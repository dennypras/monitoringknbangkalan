<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKategori extends Model
{
    use HasFactory;

    protected $table = 'detail_kategori';

    protected $fillable = [
        'perkara_id',
        'tanggal_diterima_spdp',
        'nomor_berkas_perkara',
        'nomor_pengantar_berkas',
        'tanggal_berkas_tahap_1',
        'tanggal_p18',
        'tanggal_p19',
        'tanggal_berkas_kembali',
        'nomor_pengantar_berkas_kembali',
        'tanggal_p21',
        'file_pendukung',
    ];

    public function perkara()
    {
        return $this->belongsTo(Perkara::class);
    }
}
