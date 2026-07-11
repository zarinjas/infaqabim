Berikut ialah gabungan kesemua dokumen yang telah disusun semula, diperbaiki alirannya, dan disatukan ke dalam satu fail `project.md` untuk memudahkan rujukan pembangunan projek anda:

```markdown
# Dokumentasi Projek INFAQABIM (project.md)

Dokumen ini merupakan sumber rujukan tunggal (single source of truth) untuk keseluruhan projek INFAQABIM. AI dan pembangun wajib membaca dan memahami keseluruhan fail ini sebelum membuat sebarang perubahan kod.

## 1. Gambaran Keseluruhan Projek
INFAQABIM ialah sebuah laman web pendaratan (landing page) derma ringkas yang dibina menggunakan Laravel dan Vue. 
**Matlamat Utama:** Laman web ini disasarkan untuk menjadi pantas, kemas, mudah diselenggara, dan mempunyai kebergantungan (dependencies) yang sangat minimum.
**Skop Versi 1 (V1):** Laman pendaratan awam, senarai kempen, borang derma (dengan muat naik resit secara manual), panel admin, serta pengurusan sepanduk, kempen, dan penderma.

## 2. Seni Bina & Teknologi (Tech Stack)
Projek ini menggunakan seni bina aplikasi Laravel tunggal (single Laravel application) dengan aliran: Laman Web Awam ↓ Borang Derma ↓ Pangkalan Data ↓ Panel Admin. Pemversian REST API atau perkhidmatan mikro (microservices) tidak digunakan.

*   **Bahagian Belakang (Backend):** Laravel 12.
*   **Bahagian Hadapan (Frontend):** Vue 3, Vite, Tailwind CSS, Pinia, dan Axios.
*   **Pangkalan Data:** SQLite (untuk persekitaran pembangunan) dan MySQL (untuk pengeluaran).
*   **Pakej DILARANG:** Pembangun tidak dibenarkan menggunakan Filament, Nova, Backpack, Livewire, Inertia, Docker, Sail, dan Spatie Permission.

## 3. Spesifikasi Ciri & Panduan UI

**Gaya Antara Muka (UI):**
Reka bentuk perlulah minimal, moden, dan responsif (berasaskan *Mobile first*). Jarak susun atur mesti konsisten dan diinspirasikan oleh rekaan Apple. Warna utama yang digunakan adalah *Teal*, disokong oleh warna neutral *Putih* dan *Kelabu*. Komponen asas merangkumi Pengepala (Header), Pengaki (Footer), Bahagian (Section), Kad, Butang, Lencana (Badge), dan Bar Progres.

**Ciri-Ciri Awam (Public):**
*   **Halaman Utama:** Memaparkan Sepanduk Utama (Hero Banner), Kempen Pilihan, Senarai Kempen, Butiran Kempen, Tentang Kami, dan Pengaki.
*   **Modul Kempen:** Menyokong dua jenis kempen iaitu berasaskan "Progres" dan "Sekali Harung" (One-Off).
*   **Borang Derma:** Memerlukan ruangan untuk Nama, Telefon, E-mel, Jumlah derma, dan Muat Naik Resit (Nombor Rujukan adalah pilihan).

**Panel Admin:**
*   **Navigasi Bar Sisi (Sidebar):** Akses kepada Papan Pemuka, Sepanduk, Kempen, Derma, Penderma, Tetapan, dan fungsi Log Keluar.
*   **Papan Pemuka (Dashboard):** Memaparkan kad maklumat ringkas untuk Jumlah Kempen, Jumlah Derma, Derma Tertunda, dan Derma Selesai.
*   **Fungsi Admin:** Merangkumi sistem Log masuk, operasi CRUD untuk Sepanduk dan Kempen, Semakan Derma, Senarai Penderma, dan panel Tetapan.

## 4. Skema Pangkalan Data
Hanya jadual pangkalan data berikut yang wujud dan dibenarkan pada masa ini:
1.  `admins` (Medan: id, name, email, password, remember_token, timestamps).
2.  `banners`.
3.  `campaigns`.
4.  `donations`.
5.  `settings`.
*Nota: Jadual baharu hanya boleh dicipta jika terdapat perubahan rasmi pada spesifikasi ciri projek.*

## 5. Peraturan Pembangunan
1.  Jangan pasang pakej luaran melainkan diarahkan.
2.  Kekalkan kod agar sentiasa ringkas dan guna semula komponen (reuse components) semaksimum mungkin.
3.  Jangan ubah seni bina (architecture) projek atau menambah ciri di luar spesifikasi yang ditetapkan.
4.  Sentiasa bertanya jika kurang pasti; elakkan membuat andaian sendiri.
5.  Setiap perubahan mesti direkodkan di dalam senarai *Changelog*, dan status tugas (*Task Progress*) wajib dikemas kini selepas kerja selesai.

## 6. Pelan Hala Tuju (Roadmap) & Kemajuan Tugas

*   **Fasa 1: Bootstrap**
    *   [ ] Dokumentasi, [ ] Laravel, [ ] Vue, [ ] Tailwind, [ ] Git.
*   **Fasa 2: Laman Utama (Homepage)**
    *   [ ] Susun Atur (Layout), [ ] Sepanduk, [ ] Kad Kempen.
*   **Fasa 3: Modul Kempen**.
*   **Fasa 4: Derma**.
*   **Fasa 5: Panel Admin**
    *   [ ] Log masuk, [ ] Papan Pemuka, [ ] CRUD.
*   **Fasa 6: Pengeluaran (Production)**.

## 7. Rekod Perubahan (Changelog)
*   **v1.0.0:** Dokumentasi awal projek telah dicipta. 
*(Perhatian: Sebarang perubahan pada masa akan datang perlulah ditambah di bawah bahagian ini mengikut susunan kronologi)*.
```

## AI Instructions

- Keep solutions simple.
- Do not over-engineer.
- Do not install packages unless requested.
- Reuse existing components.
- Minimize output.
- Return only the requested result.