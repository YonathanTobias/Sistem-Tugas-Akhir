<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prodi_id',
        'nidn',
        'nip',
        'nama_lengkap',
        'gelar',
        'bidang_keahlian',
        'kuota_bimbingan',
        'no_hp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function bimbinganTugasAkhir1()
    {
        return $this->hasMany(TugasAkhir::class, 'pembimbing1_id');
    }

    public function bimbinganTugasAkhir2()
    {
        return $this->hasMany(TugasAkhir::class, 'pembimbing2_id');
    }

    public function logbookBimbingan()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function getNamaGelarAttribute()
    {
        return $this->gelar ? "{$this->nama_lengkap}, {$this->gelar}" : $this->nama_lengkap;
    }
}
