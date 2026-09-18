# JARA — Advanced To-Do List

## Deskripsi

JARA adalah aplikasi web advanced to-do list untuk mengelola tugas pribadi maupun tim. Pengguna dapat membuat daftar atau proyek, mengelompokkan tugas, menetapkan prioritas dan tenggat waktu, menandai tugas sebagai selesai, berkolaborasi dengan pengguna lain, serta memantau progres penyelesaian tugas. Admin bertanggung jawab menambah dan menghapus akun pengguna dalam sistem.

## Fitur Utama

- Membuat dan mengelompokkan tugas ke dalam beberapa daftar atau proyek
- Menetapkan prioritas dan tenggat waktu pada tugas
- Menandai tugas sebagai selesai
- Menambahkan pengguna lain ke dalam daftar/proyek untuk dikerjakan bersama
- Memantau progres penyelesaian tugas
- Manajemen akun pengguna oleh admin
- Pembuat daftar otomatis menjadi pemiliknya (auto-owner)
- Menghapus daftar beserta seluruh tugas, sub-tugas, dan keanggotaannya secara aman
- Operasi pembuatan/penghapusan daftar berjalan atomic (all-or-nothing)
- Otorisasi berlapis, validasi input server-side, dan query prepared statement (anti SQL injection)

## Aktor Sistem

| Aktor | Deskripsi |
|---|---|
| Admin | Mengelola akun pengguna di dalam sistem (tambah/hapus) |
| User (Pemilik Daftar/Proyek) | Membuat daftar/proyek, tugas, dan mengundang anggota |
| User (Anggota/Kolaborator) | Mengerjakan tugas yang dibagikan kepadanya |

## Alur Penggunaan Singkat

1. Pengguna mendaftar dan login ke sistem.
2. Pengguna membuat daftar atau proyek baru dan **otomatis menjadi pemiliknya**.
3. Pengguna menambahkan tugas ke dalam daftar/proyek tersebut, lengkap dengan prioritas dan tenggat waktu.
4. Pemilik dapat mengundang pengguna lain untuk mengerjakan tugas bersama.
5. Setiap tugas dapat ditandai selesai setelah dikerjakan.
6. Progres penyelesaian tugas dapat dipantau melalui dashboard.
7. Pemilik dapat menghapus daftar miliknya; seluruh tugas, sub-tugas, dan keanggotaan ikut terhapus dalam satu transaksi yang atomic.

---

## Software Requirements Specification (SRS)

### Modul Autentikasi & Manajemen Akun (Admin)

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

### Modul Manajemen Tugas & Daftar/Proyek

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

### Modul Kolaborasi & Kepemilikan

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

### Modul Monitoring Progres & Dashboard

| ID | Kebutuhan Fungsional |
|---|---|
| FR-27 | Sistem harus menampilkan dashboard ringkasan progres tugas per daftar/proyek |
| FR-28 | Sistem harus menampilkan persentase penyelesaian tugas (progress bar) |
| FR-29 | Sistem harus menampilkan jumlah tugas berdasarkan status (belum, sedang berjalan, selesai) |
| FR-30 | Pemilik proyek dapat melihat progres kerja tiap anggota tim dalam proyeknya |
| FR-31 | Sistem harus menyediakan filter progres berdasarkan rentang waktu (mingguan/bulanan) |
| FR-32 | Sistem harus menampilkan visualisasi progres (grafik/chart) untuk memudahkan pemantauan |
| FR-33 | Sistem dapat mengekspor laporan progres (opsional: PDF/Excel) |

---

## SRS Increment 2 — Pembuatan & Penghapusan Daftar Tugas yang Aman dan Atomik

**Versi:** 1.1 · **Tanggal:** 18 September 2026 · **Dokumen lengkap:** [`SRS.md`](./SRS.md)
Increment ini melengkapi SRS v1.0 di atas dengan dua kapabilitas inti pengelolaan daftar tugas.

### User Story Baru

| ID | User Story | Prioritas |
|---|---|---|
| **US-01** | Sebagai **User**, saya ingin membuat daftar tugas baru dan **otomatis menjadi pemiliknya**, agar saya langsung dapat mengelola daftar tersebut. | Must |
| **US-02** | Sebagai **User (Pemilik)**, saya ingin menghapus daftar milik saya **beserta seluruh tugas dan keanggotaannya**, agar tidak ada data yatim yang tertinggal. | Must |
| **US-03** | Sebagai **Pengembang Sistem**, saya ingin setiap proses multi-tabel berjalan **atomic**, agar bila satu langkah gagal, seluruh perubahan dibatalkan. | Must |
| **US-04** | Sebagai **Pemilik Sistem**, saya ingin permintaan **tidak berwenang ditolak**, seluruh **input divalidasi**, dan akses database memakai **prepared statement**, agar aman dari akses ilegal dan SQL injection. | Must |

### Kebutuhan Fungsional (FR-34 – FR-45)

| ID | Kebutuhan Fungsional |
|---|---|
| FR-34 | Sistem harus menyediakan endpoint pembuatan daftar (`POST /task-lists`) yang hanya dapat diakses user terautentikasi |
| FR-35 | Saat daftar dibuat, sistem wajib menetapkan user yang login sebagai pemilik (`task_lists.user_id`) secara otomatis |
| FR-36 | Atribut kepemilikan (`user_id`) tidak boleh dapat di-set dari input request |
| FR-36a | Sistem mencatat baris keanggotaan pemilik pada `task_list_members` (`role = owner`) dalam transaksi yang sama |
| FR-37 | Pemilik daftar dapat menghapus daftarnya melalui `DELETE /task-lists/{taskList}` |
| FR-38 | Penghapusan daftar harus ikut menghapus seluruh tugas (`tasks`) yang berelasi |
| FR-39 | Penghapusan daftar harus ikut menghapus seluruh sub-tugas (`subtasks`) dari tugas yang terhapus |
| FR-40 | Penghapusan daftar harus ikut menghapus seluruh baris keanggotaan (`task_list_members`) terkait |
| FR-40a | Setelah penghapusan tidak boleh ada data yatim yang masih merujuk ke daftar terhapus |
| FR-41 | Proses pembuatan dan penghapusan daftar harus dibungkus dalam satu database transaction |
| FR-41a | Jika salah satu langkah gagal, sistem melakukan rollback seluruh perubahan (tidak ada state setengah jadi) |
| FR-41b | Transaction hanya boleh di-commit bila semua langkah berhasil |
| FR-42 | Permintaan tidak berwenang ditolak tanpa efek samping: guest → 401/redirect login; non-pemilik → 403 Forbidden |
| FR-42a | Pemeriksaan wewenang dilakukan sebelum operasi tulis apa pun (fail closed) via Policy/FormRequest `authorize()` |
| FR-43 | Seluruh input pengguna divalidasi di sisi server sebelum diproses |
| FR-43a | Aturan minimal: `name` required\|string\|max:255; `description` nullable\|string\|max:2000; input tidak valid → 422 tanpa perubahan data |
| FR-44 | Seluruh akses database memakai prepared statement / parameter binding; dilarang interpolasi string mentah dari input |
| FR-44a | Raw SQL (bila terpaksa) wajib memakai binding dan tidak boleh memakai identifier yang dikendalikan pengguna |
| FR-45 | Sistem tidak boleh membocorkan detail error database ke pengguna; error dicatat ke log server |

### Kebutuhan Non-Fungsional Tambahan (NFR-06 – NFR-10)

| ID | Kebutuhan |
|---|---|
| NFR-06 | Atomicity (ACID) — operasi create/delete bersifat all-or-nothing |
| NFR-07 | Authorization berlapis — middleware `auth` + Policy + `authorize()` |
| NFR-08 | Ketahanan SQL Injection (selaras OWASP A03:2021 Injection) |
| NFR-09 | Validasi input server-side untuk semua input pada endpoint terkait |
| NFR-10 | Auditability — create/delete daftar tercatat di `activity_logs` |

### Pembagian Tugas Increment 2 untuk 4 Anggota

| Anggota | Fokus Modul | FR Utama | Output |
|---|---|---|---|
| **Anggota 1** | Pembuatan daftar & kepemilikan otomatis | FR-34, FR-35, FR-36, FR-36a | Endpoint `POST /task-lists`, form create, auto-owner |
| **Anggota 2** | Penghapusan daftar, cascade & transaksi atomic | FR-37–FR-41b | Endpoint `DELETE /task-lists/{id}`, migrasi & model `task_list_members`, hapus cascade + rollback |
| **Anggota 3** | Otorisasi & penolakan akses | FR-42, FR-42a | Policy `TaskList`, guard route, penolakan 401/403 |
| **Anggota 4** | Validasi, anti SQL injection & jaminan kualitas | FR-43–FR-45 | Audit prepared statement, penanganan error aman, test suite keamanan & atomicity |

> Detail lengkap (acceptance criteria, batas transaksi, kontrak antar-anggota, Definition of Done, matriks traceability, dan skenario uji) tersedia di [`SRS.md`](./SRS.md).





