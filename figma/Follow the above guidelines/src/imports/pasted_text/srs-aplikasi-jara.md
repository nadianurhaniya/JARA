# Software Requirements Specification (SRS)
## Aplikasi JARA — Advanced To-Do List

**Versi:** 1.0
**Tanggal:** 11 September 2026
**Deskripsi Singkat:** JARA adalah aplikasi web untuk mengelola tugas pribadi maupun tim. Pengguna dapat membuat daftar/proyek, mengatur prioritas dan tenggat waktu, berkolaborasi dengan pengguna lain, serta memantau progres penyelesaian tugas.

---

## Aktor Sistem

| Aktor | Deskripsi |
|---|---|
| **Admin** | Mengelola akun pengguna di dalam sistem (tambah/hapus) |
| **User (Pemilik Daftar/Proyek)** | Membuat daftar/proyek, tugas, dan mengundang anggota |
| **User (Anggota/Kolaborator)** | Mengerjakan tugas yang dibagikan kepadanya |

---

## Pembagian Modul untuk 4 Anggota Tim

---

### 👤 Orang 1 — Modul Autentikasi & Manajemen Akun (Admin)

| ID | Kebutuhan Fungsional |
|---|---|
| FR-01 | Sistem harus menyediakan fitur registrasi akun pengguna baru (email, password, nama) |
| FR-02 | Sistem harus menyediakan fitur login/logout dengan autentikasi (email & password) |
| FR-03 | Sistem harus menyediakan fitur reset password |
| FR-04 | Admin dapat menambahkan akun pengguna baru ke dalam sistem |
| FR-05 | Admin dapat menghapus (menonaktifkan) akun pengguna dari sistem |
| FR-06 | Admin dapat melihat daftar seluruh pengguna terdaftar beserta statusnya (aktif/nonaktif) |
| FR-07 | Sistem harus membedakan hak akses (role) antara Admin dan User biasa |
| FR-08 | Sistem harus mencatat log aktivitas admin terkait manajemen akun (opsional, untuk audit) |

**Output yang diharapkan:** modul login/register, halaman admin panel untuk manajemen pengguna, sistem role & permission (RBAC).

---

### 👤 Orang 2 — Modul Manajemen Tugas & Daftar/Proyek

| ID | Kebutuhan Fungsional |
|---|---|
| FR-09 | User dapat membuat daftar/proyek baru untuk mengelompokkan tugas |
| FR-10 | User dapat mengedit atau menghapus daftar/proyek yang dimilikinya |
| FR-11 | User dapat menambahkan tugas baru ke dalam suatu daftar/proyek |
| FR-12 | User dapat mengedit detail tugas (judul, deskripsi, sub-tugas) |
| FR-13 | User dapat menghapus tugas |
| FR-14 | User dapat menetapkan prioritas tugas (misal: Tinggi/Sedang/Rendah) |
| FR-15 | User dapat menetapkan tenggat waktu (deadline) pada tugas |
| FR-16 | User dapat menandai tugas sebagai "selesai" atau "belum selesai" |
| FR-17 | Sistem harus menampilkan tugas terurut berdasarkan prioritas dan/atau tenggat waktu |
| FR-18 | Sistem harus memberi notifikasi/pengingat saat tenggat waktu tugas mendekat (opsional) |

**Output yang diharapkan:** CRUD daftar/proyek dan tugas, fitur sorting/filtering, fitur reminder.

---

### 👤 Orang 3 — Modul Kolaborasi & Kepemilikan

| ID | Kebutuhan Fungsional |
|---|---|
| FR-19 | Pemilik daftar/proyek dapat mengundang/menambahkan pengguna lain ke dalam daftar tersebut |
| FR-20 | Pemilik dapat menghapus anggota dari daftar/proyeknya |
| FR-21 | Anggota yang diundang dapat menerima/menolak undangan kolaborasi |
| FR-22 | Sistem harus mendukung penugasan tugas spesifik ke anggota tertentu (assignee) |
| FR-23 | Anggota kolaborator dapat mengubah status tugas yang ditugaskan kepadanya (selesai/belum) |
| FR-24 | Sistem harus membatasi hak akses anggota (misal: tidak dapat menghapus daftar/proyek, hanya pemilik yang bisa) |
| FR-25 | Sistem harus menampilkan daftar anggota beserta perannya dalam suatu proyek |
| FR-26 | Sistem harus mengirim notifikasi saat pengguna ditambahkan/ditugaskan ke suatu tugas |

**Output yang diharapkan:** fitur invite/share proyek, sistem assignment tugas, manajemen hak akses kolaborator.

---

### 👤 Orang 4 — Modul Monitoring Progres & Dashboard

| ID | Kebutuhan Fungsional |
|---|---|
| FR-27 | Sistem harus menampilkan dashboard ringkasan progres tugas per daftar/proyek |
| FR-28 | Sistem harus menampilkan persentase penyelesaian tugas (progress bar) |
| FR-29 | Sistem harus menampilkan jumlah tugas berdasarkan status (belum, sedang berjalan, selesai) |
| FR-30 | Pemilik proyek dapat melihat progres kerja tiap anggota tim dalam proyeknya |
| FR-31 | Sistem harus menyediakan filter progres berdasarkan rentang waktu (mingguan/bulanan) |
| FR-32 | Sistem harus menampilkan visualisasi progres (grafik/chart) untuk memudahkan pemantauan |
| FR-33 | Sistem dapat mengekspor laporan progres (opsional: PDF/Excel) |

**Output yang diharapkan:** dashboard analitik, grafik progres, laporan ringkasan tugas per user/proyek.

---

## Kebutuhan Non-Fungsional (Referensi Bersama, berlaku untuk semua modul)

| ID | Kebutuhan |
|---|---|
| NFR-01 | Aplikasi berbasis web, responsif untuk desktop dan mobile browser |
| NFR-02 | Waktu respon sistem maksimal 2 detik untuk operasi umum |
| NFR-03 | Data pengguna harus terenkripsi (password hashing) |
| NFR-04 | Sistem harus mendukung minimal 100 pengguna aktif secara bersamaan |
| NFR-05 | Sistem harus memiliki backup data berkala |

---

## Catatan Koordinasi Tim
- **Orang 1 & Orang 3** perlu selaras soal struktur tabel `users` dan `role/permission`, karena kolaborasi bergantung pada data akun.
- **Orang 2 & Orang 3** perlu selaras soal struktur tabel `tasks` dan `list/project`, karena assignment tugas ke anggota menempel pada entitas ini.
- **Orang 4** bergantung pada data dari modul 2 dan 3 (status tugas & assignment) untuk membangun dashboard progres — sebaiknya dikerjakan setelah struktur data modul 2 & 3 disepakati.