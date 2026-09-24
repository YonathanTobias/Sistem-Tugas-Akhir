<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyaratYudisium extends Model
{
    use HasFactory;

    protected $table = 'syarat_yudisiums';

    protected $fillable = [
        'nama_syarat',
        'kode_syarat',
        'kategori',
        'deskripsi',
        'is_wajib',
    ];

    protected function casts(): array
    {
        return [
            'is_wajib' => 'boolean',
        ];
    }

    public function berkasYudisiums()
    {
        return $this->hasMany(BerkasYudisium::class);
    }
}
