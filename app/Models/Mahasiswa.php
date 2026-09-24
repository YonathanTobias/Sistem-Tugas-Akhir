<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prodi_id',
        'nim',
        'nama_lengkap',
        'angkatan',
        'semester',
        'ipk',
        'total_sks',
        'no_hp',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class);
    }

    public function pendaftaranYudisium()
    {
        return $this->hasOne(PendaftaranYudisium::class);
    }
}
