<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSidang extends Model
{
    use HasFactory;

    protected $table = 'nilai_sidangs';

    protected $fillable = [
        'sidang_id',
        'dosen_id',
        'peran',
        'nilai_presentasi',
        'nilai_materi',
        'nilai_tanya_jawab',
        'total_nilai',
        'catatan',
    ];

    public function sidang()
    {
        return $this->belongsTo(Sidang::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
