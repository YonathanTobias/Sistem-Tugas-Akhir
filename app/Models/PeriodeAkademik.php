<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeAkademik extends Model
{
    use HasFactory;

    protected $table = 'periode_akademiks';

    protected $fillable = ['nama_periode', 'tahun_ajaran', 'semester', 'is_aktif'];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    public function tugasAkhirs()
    {
        return $this->hasMany(TugasAkhir::class);
    }
}
