# 🎓 SIMTA & Yudisium - STIKes Panti Waluya Malang

Sistem Informasi Manajemen Tugas Akhir, Seminar/Sidang Skripsi, dan Yudisium Terpadu dengan Verifikasi SKL Digital Berbasis QR-Code untuk **Sekolah Tinggi Ilmu Kesehatan (STIKes) Panti Waluya Malang**.

---

## 🌟 Fitur Utama

- **Multi-Prodi Terintegrasi (3 Program Studi):**
  - S1 Keperawatan (`KEP` - Gelar `S.Kep.`)
  - S1 Farmasi (`FAR` - Gelar `S.Farm.`)
  - D4 Manajemen Informasi Kesehatan / Rekam Medis (`MIK` - Gelar `S.Tr.Kes.`)
- **Pemisahan Peran & Wewenang (RBAC):**
  - **Admin IT / Pusat (`admin_it`):** Akses master konfigurasi 3 prodi, setting sistem, dan filter global scope switcher.
  - **Admin Program Studi (`admin_prodi`):** Mengelola review proposal TA/Skripsi, plotting dosen pembimbing, penjadwalan sidang, verifikasi berkas bebas tanggungan, dan yudisium khusus prodinya.
  - **Dosen Pembimbing & Penguji (`dosen`):** Bimbingan online, logbook catatan, persetujuan (ACC), dan penilaian seminar/sidang.
  - **Mahasiswa (`mahasiswa`):** Pengajuan judul, logbook bimbingan mandiri, pendaftaran sidang, cetak kartu kendali bimbingan, pendaftaran yudisium, dan cetak SKL resmi.
- **Kartu Kendali Bimbingan Digital:** Cetak kartu bimbingan siap print/PDF lengkap dengan paraf digital.
- **Surat Keterangan Lulus (SKL) Digital Ber-QR Code:** Penerbitan SKL ber-token kriptografi.
- **Public Verification Portal:** Rumah Sakit / Instansi luar dapat memindai QR-Code untuk memverifikasi keaslian kelulusan secara realtime.

---

## 🚀 Panduan Instalasi & Menjalankan

### 1. Prasyarat
- PHP >= 8.2
- MySQL / MariaDB (XAMPP / Laragon)
- Composer

### 2. Langkah Setup

```bash
# 1. Clone repository
git clone https://github.com/YonathanTobias/Sistem-Tugas-Akhir.git
cd Sistem-Tugas-Akhir

# 2. Salin environment file
cp .env.example .env

# 3. Install dependencies
composer install

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
# Pastikan database 'simta_yudisium' telah dibuat di MySQL

# 6. Migrasi database dan Seed data awal
php artisan migrate:fresh --seed

# 7. Jalankan web server lokal
php artisan serve
```

---

## 🔑 Akun Demo Siap Pakai *(Password: `password`)*

| Role | Email Login | Username | Keterangan |
| :--- | :--- | :--- | :--- |
| **👑 Admin IT / Pusat** | `it@stikespantiwaluya.ac.id` | `admin_it` | Akses Super Admin & Global Scope |
| **🩺 Admin S1 Keperawatan** | `admin.kep@stikespantiwaluya.ac.id` | `admin_kep` | Terkunci khusus Prodi S1 Keperawatan |
| **💊 Admin S1 Farmasi** | `admin.far@stikespantiwaluya.ac.id` | `admin_far` | Terkunci khusus Prodi S1 Farmasi |
| **📋 Admin D4 MIK** | `admin.mik@stikespantiwaluya.ac.id` | `admin_mik` | Terkunci khusus Prodi D4 Rekam Medis |
| **👨‍🏫 Dosen Pembimbing** | `dosen1@stikespantiwaluya.ac.id` | `0712048001` | Ns. Felisitas A. Sri S., M.Kep. |
| **🎓 Mahasiswa Demo** | `mahasiswa@stikespantiwaluya.ac.id` | `220101001` | Maria Fransiska (NIM: 220101001) |

---

## 📄 Lisensi

Dikembangkan untuk keperluan akademik dan manajemen tugas akhir STIKes Panti Waluya Malang.
