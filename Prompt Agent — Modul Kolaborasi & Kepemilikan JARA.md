# TASK: Implementasi Modul Kolaborasi & Kepemilikan JARA

Kamu adalah coding agent yang mengerjakan project **JARA — Advanced To-Do List**.

Kerjakan modul **Kolaborasi & Kepemilikan** berdasarkan kebutuhan fungsional **FR-19 sampai FR-26**.

## 1. WAJIB CEK PROJECT TERLEBIH DAHULU

Sebelum mengubah kode:

1. Baca struktur folder project.
2. Cari dan baca folder/file **Figma** yang sudah tersedia di dalam project.
3. Identifikasi halaman, component, asset, style, warna, typography, icon, dan layout yang sudah dibuat/desain untuk fitur kolaborasi.
4. Cek implementasi fitur yang sudah ada agar tidak membuat ulang component yang sebenarnya sudah tersedia.
5. Ikuti struktur dan pola coding yang sudah digunakan project.
6. Jangan mengubah bagian project lain yang tidak berkaitan dengan task ini.

**PENTING:** Jangan membuat desain UI baru yang berbeda dari Figma. Gunakan desain Figma yang sudah ada sebagai acuan utama.

---

# 2. BATASAN UTAMA

### Tidak menggunakan database

Implementasi task ini **HARUS tanpa database**.

Gunakan data sementara/mock/in-memory/local state yang sesuai dengan arsitektur project saat ini.

Contoh:

- mock data
- state management yang sudah digunakan project
- local state
- localStorage jika memang sesuai dengan pola project

Jangan:

- membuat migration;
- membuat tabel database;
- membuat model database baru;
- menambahkan koneksi database;
- mengubah `.env` untuk database;
- membuat API database hanya untuk menyelesaikan task ini.

Tujuannya adalah membuat **functional prototype** yang bisa mendemonstrasikan seluruh flow FR-19 sampai FR-26.

---

# 3. FITUR YANG HARUS DIIMPLEMENTASIKAN

## FR-19 — Mengundang / Menambahkan Anggota

Pemilik proyek dapat mengundang pengguna lain ke proyek.

Flow:

```text
Owner membuka proyek
        ↓
Membuka daftar anggota
        ↓
Klik "Tambah Anggota" / "Invite"
        ↓
Pilih/cari user
        ↓
Kirim undangan
        ↓
Status invitation = Pending
        ↓
User menerima notifikasi
```

Validasi:

- hanya Owner yang dapat mengundang;
- user yang diundang harus tersedia pada mock user;
- Owner tidak dapat mengundang dirinya sendiri;
- user yang sudah menjadi member tidak dapat diundang lagi;
- user yang sudah memiliki invitation `Pending` tidak dapat menerima undangan duplikat.

---

## FR-20 — Menghapus Anggota

Owner dapat menghapus anggota dari proyek.

Flow:

```text
Owner
 ↓
Daftar anggota
 ↓
Pilih member
 ↓
Remove Member
 ↓
Confirmation
 ↓
Member dihapus
```

Aturan:

- hanya Owner yang dapat menghapus member;
- Owner tidak dapat menghapus dirinya sendiri;
- setelah dihapus, user kehilangan akses sebagai member;
- tugas milik member tersebut tidak ikut dihapus;
- assignment tugas tersebut menjadi `Unassigned` jika diperlukan oleh implementasi.

---

## FR-21 — Menerima / Menolak Undangan

User yang menerima undangan dapat:

- Accept
- Reject

Status invitation:

```text
Pending
   ├── Accept → Accepted → menjadi Member
   └── Reject → Rejected → tidak menjadi Member
```

Aturan:

- hanya user yang menerima invitation yang dapat merespons;
- invitation `Accepted` atau `Rejected` tidak dapat diproses kembali;
- setelah Accept, user otomatis masuk daftar member proyek;
- setelah Reject, user tidak mendapatkan akses proyek.

---

## FR-22 — Assignment Tugas

Owner dapat memberikan tugas kepada member tertentu.

Flow:

```text
Owner
 ↓
Task
 ↓
Assign Member
 ↓
Pilih Member
 ↓
Simpan Assignee
 ↓
Kirim Notification
```

Aturan:

- assignee harus merupakan member aktif proyek;
- user yang bukan member tidak dapat dijadikan assignee;
- satu task memiliki maksimal satu assignee;
- assignment dapat diubah oleh Owner;
- jika member dihapus dari proyek, assignment-nya harus ditangani agar tidak tetap menunjuk member yang sudah tidak memiliki akses.

---

## FR-23 — Member Mengubah Status Tugas

Member dapat mengubah status tugas yang ditugaskan kepadanya.

Status minimal:

```text
Belum Selesai
Selesai
```

Aturan:

- member hanya dapat mengubah status task yang ditugaskan kepadanya;
- member tidak dapat mengubah status task milik member lain;
- Owner dapat mengubah status task di proyeknya;
- perubahan status langsung tercermin pada UI.

---

## FR-24 — Role & Access Control

Implementasikan minimal dua role:

```text
Owner
Member
```

Gunakan role tersebut untuk membatasi action.

| Action | Owner | Member |
|---|---:|---:|
| Melihat proyek | ✓ | ✓ |
| Melihat member | ✓ | ✓ |
| Invite member | ✓ | ✗ |
| Remove member | ✓ | ✗ |
| Membuat task | ✓ | sesuai implementasi existing |
| Assign task | ✓ | ✗ |
| Mengubah status task sendiri | ✓ | ✓ |
| Mengubah status task orang lain | ✓ | ✗ |
| Menghapus proyek | ✓ | ✗ |

**Jangan hanya menyembunyikan button.**

Action juga harus divalidasi pada logic/function handler sehingga Member tidak dapat menjalankan action Owner melalui UI maupun event yang tersedia.

---

## FR-25 — Daftar Anggota & Role

Tampilkan daftar anggota proyek.

Minimal informasi:

- avatar/inisial;
- nama;
- email jika sudah digunakan project;
- role;
- status;
- action yang tersedia berdasarkan role.

Contoh:

```text
Members

┌─────────────────────────────┐
│ Avatar  Khanza       Owner  │
│         khanza@email.com    │
├─────────────────────────────┤
│ Avatar  User A       Member │
│         usera@email.com     │
├─────────────────────────────┤
│ Avatar  User B       Member │
│         userb@email.com     │
└─────────────────────────────┘
```

Ikuti **UI Figma yang sudah ada**, bukan contoh layout di atas secara literal.

---

## FR-26 — Notification

Buat notification untuk event:

1. user mendapatkan invitation;
2. invitation diterima;
3. invitation ditolak;
4. user mendapatkan assignment task;
5. member dihapus dari project.

Contoh:

```text
You have been invited to "Project A"
Task "Design Login Page" was assigned to you
You were removed from "Project A"
```

Gunakan sistem notification yang sudah tersedia di project jika ada.

Jika belum ada, buat mock/in-memory notification sederhana tanpa database.

---

# 4. DATA MOCK

Gunakan data mock yang cukup untuk mendemonstrasikan seluruh flow.

Minimal:

### Users

```text
Owner
Member A
Member B
Member C
```

### Project

```text
Project A
Owner: Owner
Members: Member A, Member B
```

### Tasks

```text
Task 1 → Member A
Task 2 → Member B
Task 3 → Unassigned
```

### Invitations

Sediakan minimal satu invitation `Pending` untuk testing Accept/Reject.

Jangan membuat data mock yang terlalu kompleks jika project sudah mempunyai mock data/state sendiri.

---

# 5. UI / FIGMA

**Prioritas utama adalah mengikuti folder Figma yang ada di project.**

Sebelum coding:

```text
Inspect existing Figma/design files
        ↓
Identifikasi component
        ↓
Identifikasi halaman
        ↓
Identifikasi styling
        ↓
Implementasi sesuai design
```

Jangan:

- mengganti color palette;
- mengganti typography;
- membuat dashboard baru;
- membuat layout baru;
- menggunakan component library lain hanya karena lebih mudah;
- membuat UI yang terlihat seperti template AI;
- menghapus component existing;
- melakukan redesign.

Jika Figma sudah menyediakan component tertentu, gunakan kembali component tersebut.

Jika suatu bagian belum tersedia di Figma, buat component yang **secara visual konsisten dengan design existing**.

---

# 6. ARSITEKTUR KODE

Ikuti arsitektur project yang sudah ada.

Sebelum membuat file baru:

1. cari apakah component/function serupa sudah ada;
2. reuse component existing jika memungkinkan;
3. jangan membuat duplicate component;
4. jangan melakukan refactor besar yang tidak diperlukan;
5. jangan mengubah naming convention project tanpa alasan.

Perubahan harus seminimal mungkin tetapi tetap menghasilkan fitur yang berfungsi.

---

# 7. ERROR & EMPTY STATE

Pastikan UI menangani kondisi:

- belum ada member;
- tidak ada user yang dapat diundang;
- invitation sudah pernah dikirim;
- invitation sudah diterima;
- invitation sudah ditolak;
- task belum memiliki assignee;
- member sudah dihapus;
- user mencoba melakukan action tanpa permission.

Gunakan feedback UI yang sudah mengikuti pola project.

---

# 8. TESTING MANUAL

Setelah implementasi, lakukan pengecekan minimal:

### Owner

- [ ] dapat melihat member;
- [ ] dapat invite user;
- [ ] invitation menjadi Pending;
- [ ] dapat remove member;
- [ ] dapat assign task;
- [ ] dapat mengubah status task;
- [ ] dapat melihat notification.

### Member

- [ ] dapat melihat project;
- [ ] dapat melihat member;
- [ ] dapat menerima invitation;
- [ ] dapat menolak invitation;
- [ ] dapat melihat task yang ditugaskan;
- [ ] dapat mengubah status task miliknya;
- [ ] tidak dapat invite member;
- [ ] tidak dapat remove member;
- [ ] tidak dapat assign task;
- [ ] tidak dapat menghapus project;
- [ ] tidak dapat mengubah status task milik member lain.

### Invitation

- [ ] Pending → Accept berhasil;
- [ ] Pending → Reject berhasil;
- [ ] Accepted tidak dapat di-accept ulang;
- [ ] Rejected tidak dapat di-reject ulang.

---

# 9. JANGAN MERUSAK FITUR EXISTING

Sebelum selesai:

- cek `git diff`;
- cek file yang berubah;
- pastikan tidak ada perubahan tidak berkaitan;
- jangan menghapus fitur existing;
- jangan mengubah konfigurasi yang tidak diperlukan;
- jangan melakukan mass formatting terhadap seluruh project.

Jika ada perubahan yang tidak berkaitan dengan task, jangan ikut commit.

---

# 10. GIT WORKFLOW

Ikuti aturan pada:

https://github.com/Doctor3131/ppk-pertemuan-1/blob/main/git%20%26%20github%20101.md

Gunakan **feature branch**, bukan langsung mengerjakan di `main`.

Branch:

```bash
feature/collaboration-ownership
```

Sebelum membuat branch, cek kondisi repository:

```bash
git status
git branch
```

Jangan menghapus atau menimpa pekerjaan existing yang belum di-commit.

---

# 11. ATURAN COMMIT

Gunakan **Conventional Commits**.

Format:

```text
<type>(<scope>): <deskripsi>
```

Gunakan bahasa yang konsisten dengan project.

Commit harus:

- atomic;
- satu commit = satu perubahan logis;
- tidak mencampur `feat`, `fix`, `refactor`, dll. dalam satu commit;
- deskripsi imperative;
- huruf kecil;
- tidak menggunakan titik di akhir;
- singkat.

Contoh commit yang sesuai:

```bash
git add <file terkait invitation>
git commit -m "feat(collaboration): tambah alur undangan anggota"
```

Kemudian:

```bash
git add <file terkait member management>
git commit -m "feat(collaboration): tambah pengelolaan anggota proyek"
```

Kemudian:

```bash
git add <file terkait assignment>
git commit -m "feat(collaboration): tambah assignment tugas"
```

Kemudian:

```bash
git add <file terkait notification>
git commit -m "feat(notification): tambah notifikasi kolaborasi"
```

**Jangan membuat satu commit besar untuk seluruh task jika perubahan dapat dipisahkan secara logis.**

---

# 12. FILE YANG DILARANG DI-COMMIT

Pastikan tidak memasukkan:

```text
.env
.env.*
node_modules/
dist/
*.log
```

Ikuti `.gitignore` existing project.

Jangan menjalankan:

```bash
git add .
```

secara membabi buta jika terdapat file yang tidak berhubungan dengan task.

Lebih baik stage file yang memang berkaitan dengan perubahan.

---

# 13. SEBELUM COMMIT

Wajib jalankan:

```bash
git status
git diff
```

Review:

- apakah hanya file yang relevan yang berubah;
- apakah ada secret;
- apakah ada file generated;
- apakah ada debug code;
- apakah ada console.log yang tidak diperlukan;
- apakah ada perubahan Figma/style yang tidak sengaja;
- apakah feature existing tetap berjalan.

Kemudian jalankan test/lint/build yang tersedia di project.

Jika project memiliki command seperti:

```bash
npm run lint
npm run build
npm test
```

gunakan command yang memang tersedia. Jangan mengarang command baru.

---

# 14. HASIL AKHIR

Setelah selesai, tampilkan ringkasan:

```text
## Implemented

- FR-19: ...
- FR-20: ...
- FR-21: ...
- FR-22: ...
- FR-23: ...
- FR-24: ...
- FR-25: ...
- FR-26: ...

## Files Changed

- ...
- ...
- ...

## Testing

- ...
- ...

## Git

Branch:
feature/collaboration-ownership

Commits:
- ...
- ...
- ...

Working tree:
clean / terdapat perubahan lain yang bukan bagian task
```

**Jangan melakukan push ke remote kecuali secara eksplisit diminta.**

Fokus task ini hanya pada **Modul Kolaborasi & Kepemilikan FR-19 sampai FR-26**, tanpa database dan dengan UI mengikuti folder Figma yang sudah ada di project.