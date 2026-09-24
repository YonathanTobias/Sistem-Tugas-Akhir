<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasYudisium extends Model
{
    use HasFactory;

    protected $table = 'berkas_yudisiums';

    protected $fillable = [
        'pendaftaran_yudisium_id',
        'syarat_yudisium_id',
        'file_path',
        'status',
        'catatan_validator',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function pendaftaranYudisium()
    {
        return $this->belongsTo(PendaftaranYudisium::class);
    }

    public function syaratYudisium()
    {
        return $this->belongsTo(SyaratYudisium::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
