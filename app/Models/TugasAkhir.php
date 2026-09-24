<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhirs';

    protected $fillable = [
        'mahasiswa_id',
        'periode_akademik_id',
        'pembimbing1_id',
        'pembimbing2_id',
        'judul',
        'bidang_kajian',
        'abstrak',
        'file_proposal',
        'status',
        'tgl_pengajuan',
        'tgl_disetujui',
        'catatan_prodi',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pengajuan' => 'datetime',
            'tgl_disetujui' => 'datetime',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function periodeAkademik()
    {
        return $this->belongsTo(PeriodeAkademik::class);
    }

    public function pembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing1_id');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing2_id');
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function sidangs()
    {
        return $this->hasMany(Sidang::class);
    }

    public function sempro()
    {
        return $this->hasOne(Sidang::class)->where('jenis', 'sempro');
    }

    public function sidangAkhir()
    {
        return $this->hasOne(Sidang::class)->where('jenis', 'sidang_akhir');
    }

    // Helper count ACC bimbingan
    public function totalAccBimbingan(): int
    {
        return $this->bimbingans()->where('status', 'acc')->count();
    }
}
