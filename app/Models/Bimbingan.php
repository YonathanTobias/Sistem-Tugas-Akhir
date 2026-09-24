<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_akhir_id',
        'dosen_id',
        'tgl_bimbingan',
        'bab',
        'topik_bimbingan',
        'uraian_mahasiswa',
        'file_draft',
        'file_revisi_dosen',
        'catatan_dosen',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tgl_bimbingan' => 'date',
        ];
    }

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
