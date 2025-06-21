<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Perkara;

class Kategori extends Model {
    use HasFactory;
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori', 'detail_kategori_id'];

    public function detailKategori() {
        return $this->belongsTo(DetailKategori::class);
    }

    public function perkara() {
        return $this->hasMany(Perkara::class);
    }
}

// class Kategori extends Model
// {
//     use HasFactory;

//     protected $table = 'kategori';
//     protected $fillable = ['kode_kategori', 'nama_kategori', 'urutan'];

//     public function berkas()
//     {
//         return $this->hasMany(BerkasPerkara::class);
//     }
// }