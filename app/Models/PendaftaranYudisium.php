<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranYudisium extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_yudisiums';

    protected $fillable = [
        'mahasiswa_id',
        'periode_yudisium_id',
        'tugas_akhir_id',
        'nomor_sk',
        'tanggal_sk',
        'tgl_lulus',
        'ipk_final',
        'predikat',
        'status',
        'catatan_kelulusan',
        'skl_token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sk' => 'date',
            'tgl_lulus' => 'date',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function periodeYudisium()
    {
        return $this->belongsTo(PeriodeYudisium::class);
    }

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function berkasYudisiums()
    {
        return $this->hasMany(BerkasYudisium::class);
    }

    public function isSemuaBerkasValid(): bool
    {
        $syaratWajibIds = SyaratYudisium::where('is_wajib', true)->pluck('id');
        $uploadedValidCount = $this->berkasYudisiums()
            ->whereIn('syarat_yudisium_id', $syaratWajibIds)
            ->where('status', 'valid')
            ->count();

        return $uploadedValidCount >= $syaratWajibIds->count();
    }
}
