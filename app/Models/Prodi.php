<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_prodi', 
        'nama_prodi', 
        'jenjang', 
        'fakultas',
        'kaprodi_nama',
        'kaprodi_nip',
        'gelar_lulusan',
        'format_sk_prefix',
        'min_bimbingan_acc',
    ];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }
}
