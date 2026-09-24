<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_akhir_id',
        'jenis',
        'tgl_sidang',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'penguji1_id',
        'penguji2_id',
        'status',
        'berita_acara',
        'catatan_revisi',
        'nilai_akhir',
        'grade_huruf',
        'file_berita_acara',
    ];

    protected function casts(): array
    {
        return [
            'tgl_sidang' => 'date',
        ];
    }

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function penguji1()
    {
        return $this->belongsTo(Dosen::class, 'penguji1_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(Dosen::class, 'penguji2_id');
    }

    public function nilaiSidangs()
    {
        return $this->hasMany(NilaiSidang::class);
    }
}
