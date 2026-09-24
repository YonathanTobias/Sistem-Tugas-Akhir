<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeYudisium extends Model
{
    use HasFactory;

    protected $table = 'periode_yudisiums';

    protected $fillable = [
        'nama_periode',
        'tahun_akademik',
        'tgl_buka',
        'tgl_tutup',
        'tgl_pelaksanaan',
        'kuota',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tgl_buka' => 'date',
            'tgl_tutup' => 'date',
            'tgl_pelaksanaan' => 'date',
            'is_aktif' => 'boolean',
        ];
    }

    public function pendaftaranYudisiums()
    {
        return $this->hasMany(PendaftaranYudisium::class);
    }
}
