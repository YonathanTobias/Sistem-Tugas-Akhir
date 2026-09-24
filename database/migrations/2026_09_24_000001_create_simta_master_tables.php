<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Program Studi
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prodi', 20)->unique();
            $table->string('nama_prodi', 100);
            $table->string('jenjang', 10)->default('S1'); // S1, D4, D3, S2
            $table->string('fakultas', 100)->default('Fakultas Ilmu Komputer');
            $table->string('kaprodi_nama')->nullable();
            $table->string('kaprodi_nip')->nullable();
            $table->string('gelar_lulusan', 30)->default('S.Kom.');
            $table->string('format_sk_prefix', 50)->nullable();
            $table->integer('min_bimbingan_acc')->default(8);
            $table->timestamps();
        });

        // Periode Akademik
        Schema::create('periode_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode', 50); // Contoh: 2026/2027 Ganjil
            $table->string('tahun_ajaran', 20); // 2026/2027
            $table->enum('semester', ['ganjil', 'genap']);
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });

        // Detail Dosen
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->string('nidn', 30)->unique()->nullable();
            $table->string('nip', 30)->unique()->nullable();
            $table->string('nama_lengkap');
            $table->string('gelar', 50)->nullable();
            $table->string('bidang_keahlian')->nullable();
            $table->integer('kuota_bimbingan')->default(10);
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });

        // Detail Mahasiswa
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->string('nim', 30)->unique();
            $table->string('nama_lengkap');
            $table->string('angkatan', 10);
            $table->integer('semester')->default(7);
            $table->decimal('ipk', 3, 2)->default(0.00);
            $table->integer('total_sks')->default(0);
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
        Schema::dropIfExists('dosens');
        Schema::dropIfExists('periode_akademiks');
        Schema::dropIfExists('prodis');
    }
};
