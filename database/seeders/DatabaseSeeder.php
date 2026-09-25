<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Prodi;
use App\Models\PeriodeAkademik;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Bimbingan;
use App\Models\Sidang;
use App\Models\NilaiSidang;
use App\Models\PeriodeYudisium;
use App\Models\SyaratYudisium;
use App\Models\PendaftaranYudisium;
use App\Models\BerkasYudisium;
use App\Models\Pengumuman;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Data 3 Program Studi STIKes Panti Waluya Malang
        $prodiKep = Prodi::create([
            'kode_prodi' => 'KEP',
            'nama_prodi' => 'S1 Keperawatan',
            'jenjang' => 'S1',
            'fakultas' => 'STIKes Panti Waluya Malang',
            'kaprodi_nama' => 'Ns. Felisitas A. Sri S., M.Kep.',
            'kaprodi_nip' => '198004122005012001',
            'gelar_lulusan' => 'S.Kep.',
            'format_sk_prefix' => 'SK-YUD/STIKES-PW/KEP/',
            'min_bimbingan_acc' => 8,
        ]);

        $prodiFar = Prodi::create([
            'kode_prodi' => 'FAR',
            'nama_prodi' => 'S1 Farmasi',
            'jenjang' => 'S1',
            'fakultas' => 'STIKes Panti Waluya Malang',
            'kaprodi_nama' => 'apt. Yustina Sri Hartini, M.Sc.',
            'kaprodi_nip' => '198408222008122002',
            'gelar_lulusan' => 'S.Farm.',
            'format_sk_prefix' => 'SK-YUD/STIKES-PW/FAR/',
            'min_bimbingan_acc' => 8,
        ]);

        $prodiMik = Prodi::create([
            'kode_prodi' => 'MIK',
            'nama_prodi' => 'D4 Manajemen Informasi Kesehatan (Rekam Medis)',
            'jenjang' => 'D4',
            'fakultas' => 'STIKes Panti Waluya Malang',
            'kaprodi_nama' => 'Stefanus Supriyanto, S.KM., M.Kes.',
            'kaprodi_nip' => '198611152010011003',
            'gelar_lulusan' => 'S.Tr.Kes.',
            'format_sk_prefix' => 'SK-YUD/STIKES-PW/MIK/',
            'min_bimbingan_acc' => 8,
        ]);

        // 2. Periode Akademik
        $periodeAktif = PeriodeAkademik::create([
            'nama_periode' => '2026/2027 Ganjil',
            'tahun_ajaran' => '2026/2027',
            'semester' => 'ganjil',
            'is_aktif' => true,
        ]);

        // 3. User Admin Tunggal (Administrator Sistem)
        $userAdmin = User::create([
            'name' => 'Administrator STIKes Panti Waluya',
            'username' => 'admin',
            'email' => 'admin@stikespantiwaluya.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'prodi_id' => $prodiKep->id,
            'phone' => '0341369003',
            'is_active' => true,
        ]);

        // 4. User Dosen 1 (Keperawatan) & Dosen 2 (Farmasi)
        $userDosen1 = User::create([
            'name' => 'Ns. Felisitas A. Sri S., M.Kep.',
            'username' => '0712048001',
            'email' => 'dosen1@stikespantiwaluya.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'phone' => '081233445501',
            'is_active' => true,
        ]);

        $dosen1 = Dosen::create([
            'user_id' => $userDosen1->id,
            'prodi_id' => $prodiKep->id,
            'nidn' => '0712048001',
            'nip' => '198004122005012001',
            'nama_lengkap' => 'Ns. Felisitas A. Sri S.',
            'gelar' => 'M.Kep.',
            'bidang_keahlian' => 'Keperawatan Medikal Bedah & Manajemen Nyeri',
            'kuota_bimbingan' => 10,
            'no_hp' => '081233445501',
        ]);

        $userDosen2 = User::create([
            'name' => 'apt. Yustina Sri Hartini, M.Sc.',
            'username' => '0722088402',
            'email' => 'dosen2@stikespantiwaluya.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'phone' => '081233445502',
            'is_active' => true,
        ]);

        $dosen2 = Dosen::create([
            'user_id' => $userDosen2->id,
            'prodi_id' => $prodiFar->id,
            'nidn' => '0722088402',
            'nip' => '198408222008122002',
            'nama_lengkap' => 'apt. Yustina Sri Hartini',
            'gelar' => 'M.Sc.',
            'bidang_keahlian' => 'Farmakologi Klinis & Farmakoterapi Herbal',
            'kuota_bimbingan' => 8,
            'no_hp' => '081233445502',
        ]);

        // 5. User Mahasiswa STIKes Panti Waluya
        $userMhs = User::create([
            'name' => 'Maria Fransiska',
            'username' => '220101001',
            'email' => 'mahasiswa@stikespantiwaluya.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'phone' => '089876543210',
            'is_active' => true,
        ]);

        $mhs = Mahasiswa::create([
            'user_id' => $userMhs->id,
            'prodi_id' => $prodiKep->id,
            'nim' => '220101001',
            'nama_lengkap' => 'Maria Fransiska',
            'angkatan' => '2022',
            'semester' => 8,
            'ipk' => 3.92,
            'total_sks' => 146,
            'no_hp' => '089876543210',
            'alamat' => 'Jl. Yulius Usman No. 62, Kasin, Klojen, Kota Malang',
        ]);

        // 6. Tugas Akhir / Skripsi Kesehatan Mahasiswa
        $ta = TugasAkhir::create([
            'mahasiswa_id' => $mhs->id,
            'periode_akademik_id' => $periodeAktif->id,
            'pembimbing1_id' => $dosen1->id,
            'pembimbing2_id' => $dosen2->id,
            'judul' => 'Pengaruh Terapi Relaksasi Benson dan Aromaterapi Lavender Terhadap Skala Nyeri Pasien Post-Operasi Bedah Mayor di RS Panti Waluya Sawahan Malang',
            'bidang_kajian' => 'Keperawatan Medikal Bedah & Terapi Komplementer',
            'abstrak' => 'Penelitian ini bertujuan untuk menganalisis efektivitas kombinasi teknik relaksasi benson dan inhalasi aromaterapi lavender dalam menurunkan intensitas nyeri pada pasien pasca operasi bedah mayor di ruang rawat inap Rumah Sakit Panti Waluya Sawahan Malang.',
            'status' => 'lulus_sidang',
            'tgl_pengajuan' => now()->subMonths(4),
            'tgl_disetujui' => now()->subMonths(4)->addDays(2),
            'catatan_prodi' => 'Protokol penelitian dan uji etik klinis disetujui, pembimbing telah diplot.',
        ]);

        // 7. Logbook Bimbingan Mahasiswa
        Bimbingan::create([
            'tugas_akhir_id' => $ta->id,
            'dosen_id' => $dosen1->id,
            'tgl_bimbingan' => now()->subMonths(3),
            'bab' => 'Bab 1 - Pendahuluan',
            'topik_bimbingan' => 'Pembahasan Latar Belakang & Data Prevalensi Nyeri Post-Operasi',
            'uraian_mahasiswa' => 'Menambahkan data rekam medis prevalensi pasien bedah mayor di RS Panti Waluya Malang serta jurnal pendukung terapi benson.',
            'catatan_dosen' => 'Latar belakang sudah komprehensif, lanjutkan penyusunan kuesioner skala nyeri NRS dan instrumen uji etik.',
            'status' => 'acc',
        ]);

        Bimbingan::create([
            'tugas_akhir_id' => $ta->id,
            'dosen_id' => $dosen1->id,
            'tgl_bimbingan' => now()->subMonths(2),
            'bab' => 'Bab 2 & 3',
            'topik_bimbingan' => 'Metodologi Penelitian Quasi Experiment & Desain Sampling',
            'uraian_mahasiswa' => 'Menyusun prosedur operasional standar (SOP) intervensi relaksasi benson dan aromaterapi lavender serta kriteria inklusi eksklusi responden.',
            'catatan_dosen' => 'SOP intervensi sudah sesuai standar keperawatan klinis. Lanjut pengambilan data di bangsal rawat inap.',
            'status' => 'acc',
        ]);

        Bimbingan::create([
            'tugas_akhir_id' => $ta->id,
            'dosen_id' => $dosen2->id,
            'tgl_bimbingan' => now()->subWeeks(3),
            'bab' => 'Bab 4 & 5',
            'topik_bimbingan' => 'Analisis Data Uji Paired T-Test & Pembahasan Klinis',
            'uraian_mahasiswa' => 'Menampilkan hasil uji statistik SPSS penurunan rata-rata skor nyeri dari 6.8 (nyeri sedang-berat) menjadi 2.4 (nyeri ringan) dengan p-value 0.001 (signifikan).',
            'catatan_dosen' => 'Analisis data sangat baik dan sistematis. Skripsi disetujui (ACC) untuk maju Sidang Akhir Skripsi.',
            'status' => 'acc',
        ]);

        // 8. Sidang Skripsi (Lulus Sidang)
        $sidang = Sidang::create([
            'tugas_akhir_id' => $ta->id,
            'jenis' => 'sidang_akhir',
            'tgl_sidang' => now()->subWeeks(2)->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:30:00',
            'ruangan' => 'Ruang Sidang Lt. 2 Gedung STIKes Panti Waluya',
            'penguji1_id' => $dosen1->id,
            'penguji2_id' => $dosen2->id,
            'status' => 'lulus',
            'berita_acara' => 'Mahasiswa menguasai konsep asuhan keperawatan dan hasil analisis klinis dengan sangat memuaskan.',
            'nilai_akhir' => 94.20,
            'grade_huruf' => 'A',
        ]);

        NilaiSidang::create([
            'sidang_id' => $sidang->id,
            'dosen_id' => $dosen1->id,
            'peran' => 'penguji1',
            'nilai_presentasi' => 95.0,
            'nilai_materi' => 94.0,
            'nilai_tanya_jawab' => 93.0,
            'total_nilai' => 93.85,
            'catatan' => 'Pemaparan hasil uji intervensi klinis sangat jelas.',
        ]);

        NilaiSidang::create([
            'sidang_id' => $sidang->id,
            'dosen_id' => $dosen2->id,
            'peran' => 'penguji2',
            'nilai_presentasi' => 94.0,
            'nilai_materi' => 95.0,
            'nilai_tanya_jawab' => 95.0,
            'total_nilai' => 94.75,
            'catatan' => 'Saran penambahan pembahasan efek aromaterapi pada sistem limbik.',
        ]);

        // 9. Master Syarat Bebas Tanggungan Yudisium
        $syaratPerpus = SyaratYudisium::create([
            'nama_syarat' => 'Surat Bebas Pustaka & Penyerahan Naskah Skripsi ke Perpustakaan STIKes',
            'kode_syarat' => 'BEBAS_PERPUS',
            'kategori' => 'perpustakaan',
            'deskripsi' => 'Unggah scan surat keterangan bebas pinjaman buku dan bukti upload softcopy skripsi ke repositori STIKes Panti Waluya Malang.',
            'is_wajib' => true,
        ]);

        $syaratKeuangan = SyaratYudisium::create([
            'nama_syarat' => 'Surat Bebas Tunggakan SPP & Biaya Pendidikan (BAAK / Keuangan)',
            'kode_syarat' => 'BEBAS_KEUANGAN',
            'kategori' => 'keuangan',
            'deskripsi' => 'Unggah bukti pelunasan biaya kuliah, administrasi ujian, dan biaya yudisium dari bagian keuangan.',
            'is_wajib' => true,
        ]);

        $syaratLab = SyaratYudisium::create([
            'nama_syarat' => 'Surat Bebas Tanggungan Alat Laboratorium Keperawatan / Farmasi',
            'kode_syarat' => 'BEBAS_LAB',
            'kategori' => 'laboratorium',
            'deskripsi' => 'Unggah bukti bebas peminjaman alat praktik dan penggantian inventaris lab.',
            'is_wajib' => true,
        ]);

        $syaratToefl = SyaratYudisium::create([
            'nama_syarat' => 'Sertifikat TOEFL / English Proficiency Test (Min. Skor 450)',
            'kode_syarat' => 'SERTIFIKAT_TOEFL',
            'kategori' => 'akademik',
            'deskripsi' => 'Unggah scan sertifikat TOEFL resmi dari Language Center kampus atau lembaga terakreditasi.',
            'is_wajib' => true,
        ]);

        // 10. Periode Yudisium Aktif
        $periodeYudisium = PeriodeYudisium::create([
            'nama_periode' => 'Yudisium Sarjana & Diploma Gelombang I TA 2026/2027',
            'tahun_akademik' => '2026/2027',
            'tgl_buka' => now()->subMonth(),
            'tgl_tutup' => now()->addMonth(),
            'tgl_pelaksanaan' => now()->addMonth()->addDays(7),
            'kuota' => 150,
            'is_aktif' => true,
        ]);

        // 11. Pendaftaran Yudisium Mahasiswa (Lulus Yudisium & Terbit SKL)
        $pendaftaranYudisium = PendaftaranYudisium::create([
            'mahasiswa_id' => $mhs->id,
            'periode_yudisium_id' => $periodeYudisium->id,
            'tugas_akhir_id' => $ta->id,
            'ipk_final' => 3.92,
            'status' => 'lulus',
            'nomor_sk' => 'SK-YUD/STIKES-PW/KEP/2026/08/042',
            'tanggal_sk' => now()->subDays(2),
            'tgl_lulus' => now()->subDays(2),
            'predikat' => 'Dengan Pujian',
            'skl_token' => Str::random(32),
            'catatan_kelulusan' => 'Dinyatakan Lulus Yudisium Sarjana Keperawatan (S.Kep.) dengan predikat Dengan Pujian (Cum Laude).',
        ]);

        BerkasYudisium::create([
            'pendaftaran_yudisium_id' => $pendaftaranYudisium->id,
            'syarat_yudisium_id' => $syaratPerpus->id,
            'file_path' => 'uploads/yudisium/sample_perpus.pdf',
            'status' => 'valid',
            'catatan_validator' => 'Bebas pustaka terverifikasi oleh Perpustakaan STIKes.',
            'verified_by' => $userAdmin->id,
            'verified_at' => now()->subDays(3),
        ]);

        BerkasYudisium::create([
            'pendaftaran_yudisium_id' => $pendaftaranYudisium->id,
            'syarat_yudisium_id' => $syaratKeuangan->id,
            'file_path' => 'uploads/yudisium/sample_keuangan.pdf',
            'status' => 'valid',
            'catatan_validator' => 'Biaya pendidikan dan administrasi yudisium lunas.',
            'verified_by' => $userAdmin->id,
            'verified_at' => now()->subDays(3),
        ]);

        BerkasYudisium::create([
            'pendaftaran_yudisium_id' => $pendaftaranYudisium->id,
            'syarat_yudisium_id' => $syaratLab->id,
            'file_path' => 'uploads/yudisium/sample_lab.pdf',
            'status' => 'valid',
            'catatan_validator' => 'Bebas tanggungan alat lab keperawatan & phantom klinik.',
            'verified_by' => $userAdmin->id,
            'verified_at' => now()->subDays(3),
        ]);

        BerkasYudisium::create([
            'pendaftaran_yudisium_id' => $pendaftaranYudisium->id,
            'syarat_yudisium_id' => $syaratToefl->id,
            'file_path' => 'uploads/yudisium/sample_toefl.pdf',
            'status' => 'valid',
            'catatan_validator' => 'Skor TOEFL 525 (Memenuhi Standar).',
            'verified_by' => $userAdmin->id,
            'verified_at' => now()->subDays(3),
        ]);

        // 12. Pengumuman Resmi STIKes Panti Waluya Malang
        Pengumuman::create([
            'user_id' => $userAdmin->id,
            'judul' => 'Pendaftaran Yudisium Gelombang I TA 2026/2027 STIKes Panti Waluya Telah Dibuka',
            'konten' => 'Diberitahukan kepada mahasiswa yang telah menyelesaikan ujian sidang skripsi untuk segera mengunggah berkas bebas laboratorium dan perpustakaan.',
            'kategori' => 'Yudisium',
            'target_role' => 'mahasiswa',
            'is_pinned' => true,
        ]);

        Pengumuman::create([
            'user_id' => $userAdmin->id,
            'judul' => 'Jadwal Pengambilan Sumpah Profesi & Kelengkapan Berkas Ijazah',
            'konten' => 'Bagi calon wisudawan yang telah lulus yudisium, mohon memeriksa kebenaran data nama dan NIK pada Surat Keterangan Lulus (SKL) digital.',
            'kategori' => 'Umum',
            'target_role' => 'all',
            'is_pinned' => false,
        ]);
    }
}
