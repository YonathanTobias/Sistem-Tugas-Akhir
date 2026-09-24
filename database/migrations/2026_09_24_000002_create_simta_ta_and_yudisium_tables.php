<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tugas Akhir / Skripsi
        Schema::create('tugas_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('periode_akademik_id')->nullable()->constrained('periode_akademiks')->nullOnDelete();
            $table->foreignId('pembimbing1_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->foreignId('pembimbing2_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->string('judul');
            $table->string('bidang_kajian')->nullable();
            $table->text('abstrak')->nullable();
            $table->string('file_proposal')->nullable();
            $table->enum('status', [
                'pengajuan', 
                'revisi_judul', 
                'disetujui', 
                'bimbingan_proposal', 
                'siap_sempro',
                'lulus_sempro', 
                'bimbingan_skripsi', 
                'siap_sidang',
                'lulus_sidang', 
                'selesai', 
                'ditolak'
            ])->default('pengajuan');
            $table->timestamp('tgl_pengajuan')->nullable();
            $table->timestamp('tgl_disetujui')->nullable();
            $table->text('catatan_prodi')->nullable();
            $table->timestamps();
        });

        // Logbook Bimbingan
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhirs')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->date('tgl_bimbingan');
            $table->string('bab', 50); // e.g. "Bab 1", "Bab 1-3", "Bab 4 & 5", "Keseluruhan"
            $table->string('topik_bimbingan');
            $table->text('uraian_mahasiswa');
            $table->string('file_draft')->nullable();
            $table->string('file_revisi_dosen')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->enum('status', ['menunggu', 'revisi', 'acc'])->default('menunggu');
            $table->timestamps();
        });

        // Sidang (Sempro & Sidang Akhir)
        Schema::create('sidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhirs')->onDelete('cascade');
            $table->enum('jenis', ['sempro', 'sidang_akhir']);
            $table->date('tgl_sidang')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('ruangan')->nullable();
            $table->foreignId('penguji1_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->foreignId('penguji2_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->enum('status', ['menunggu_jadwal', 'dijadwalkan', 'selesai', 'lulus', 'revisi', 'tidak_lulus'])->default('menunggu_jadwal');
            $table->text('berita_acara')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('grade_huruf', 5)->nullable();
            $table->string('file_berita_acara')->nullable();
            $table->timestamps();
        });

        // Penilaian Sidang oleh Dosen (Pembimbing & Penguji)
        Schema::create('nilai_sidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sidang_id')->constrained('sidangs')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->enum('peran', ['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2']);
            $table->decimal('nilai_presentasi', 5, 2)->default(0);
            $table->decimal('nilai_materi', 5, 2)->default(0);
            $table->decimal('nilai_tanya_jawab', 5, 2)->default(0);
            $table->decimal('total_nilai', 5, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Periode Yudisium
        Schema::create('periode_yudisiums', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode'); // e.g. "Yudisium Gasal Periode II 2026/2027"
            $table->string('tahun_akademik', 20);
            $table->date('tgl_buka');
            $table->date('tgl_tutup');
            $table->date('tgl_pelaksanaan');
            $table->integer('kuota')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        // Master Syarat Bebas Tanggungan Yudisium
        Schema::create('syarat_yudisiums', function (Blueprint $table) {
            $table->id();
            $table->string('nama_syarat');
            $table->string('kode_syarat', 30)->unique();
            $table->enum('kategori', ['perpustakaan', 'keuangan', 'laboratorium', 'akademik', 'lainnya']);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_wajib')->default(true);
            $table->timestamps();
        });

        // Pendaftaran Yudisium
        Schema::create('pendaftaran_yudisiums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('periode_yudisium_id')->constrained('periode_yudisiums')->onDelete('cascade');
            $table->foreignId('tugas_akhir_id')->nullable()->constrained('tugas_akhirs')->nullOnDelete();
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->date('tgl_lulus')->nullable();
            $table->decimal('ipk_final', 3, 2)->default(0.00);
            $table->enum('predikat', ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan'])->nullable();
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'lulus', 'ditolak'])->default('diajukan');
            $table->text('catatan_kelulusan')->nullable();
            $table->string('skl_token', 64)->unique(); // Token untuk verifikasi QR Code SKL
            $table->timestamps();
        });

        // Upload Berkas Bebas Tanggungan
        Schema::create('berkas_yudisiums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_yudisium_id')->constrained('pendaftaran_yudisiums')->onDelete('cascade');
            $table->foreignId('syarat_yudisium_id')->constrained('syarat_yudisiums')->onDelete('cascade');
            $table->string('file_path');
            $table->enum('status', ['menunggu', 'valid', 'ditolak'])->default('menunggu');
            $table->text('catatan_validator')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // Pengumuman Kampus
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('konten');
            $table->string('kategori', 50)->default('Umum'); // e.g. "Tugas Akhir", "Sidang", "Yudisium", "Umum"
            $table->string('file_lampiran')->nullable();
            $table->enum('target_role', ['all', 'mahasiswa', 'dosen'])->default('all');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumumans');
        Schema::dropIfExists('berkas_yudisiums');
        Schema::dropIfExists('pendaftaran_yudisiums');
        Schema::dropIfExists('syarat_yudisiums');
        Schema::dropIfExists('periode_yudisiums');
        Schema::dropIfExists('nilai_sidangs');
        Schema::dropIfExists('sidangs');
        Schema::dropIfExists('bimbingans');
        Schema::dropIfExists('tugas_akhirs');
    }
};
