# Requirements

## 🧱 Database / Schema (Migrasi + Seeder)
1. Users ✅ (ditambahkan role, nip, nisn, kelas_id)
2. Kelas ✅ (nama, grade, jurusan, kapasitas, slug, dsb)
3. Konseling Reports ✅ (problem, summary, solution, status, schedule, points)
4. Absences ✅ (absensi per siswa/kriteria + unik per hari)
5. Curhat Threads & Messages ✅ (konsep percakapan siswa ↔ guru)

## ✅ Seeder (akun awal sudah otomatis dibuat)
Terdapat 3 akun awal:
1. Guru BK
   1. email: guru.bk@sekolah.test
   2. NIP: 198001012019031001
   3. password: password
2. Kepala Sekolah
   1. email: kepala.sekolah@sekolah.test
   2. NIP: 197501012019031002
   3. password: password
3. Siswa
   1. email: siswa@sekolah.test
   2. NISN: 1234567890
   3. password: password
   4. 🧑‍💻 Authentication / Login
   5. ✅ Login sekarang menggunakan NIP/NISN + password (sesuai requirement):
4. Teacher (Guru BK / Kepala Sekolah) login pakai NIP
   1. Siswa login pakai NISN
   2. LoginController.php sudah dibuat dan terhubung di route.
5. 🟣 Filament Admin Panel (Admin berbasis role), Filament sudah otomatis ter-setup dengan resources untuk:
    1. ✅ Users
    2. ✅ Kelas
    3. ✅ Konseling Reports
    4. ✅ Absences
    5. ✅ Curhat Threads
6. Hak akses:
   1. Guru BK → penuh (create/edit/delete)
   2. Kepala Sekolah → baca saja (read-only) untuk report / data siswa
   3. Akses admin hanya untuk guru/kepsek
7. 🖥️ Dashboard Siswa (Basic)
   1. Sudah dibuat tampilan dashboard dasar untuk siswa:
      1. /dashboard → dashboard siswa
      2. /dashboard/profile → profil siswa
      3. /dashboard/konseling → placeholder (bisa dikembangkan lebih lanjut)
      4. Tampilan menggunakan Tailwind (simplenya sudah responsif).
8. ✅ Perubahan Warna (Tema Ungu)
   1. Filament sudah diatur agar primary color mendekati tema ungu, sesuai permintaan.
9. File yang diubah:
   1. AdminPanelProvider.php
10. Cara Menjalankan (yang dilakukan & selanjutnya)
   1. Jalankan server
   2. Buka aplikasi
      1. Website: http://localhost:8000
      2. Admin (Filament): http://localhost:8000/login
      3. Login (contoh)
         1. Guru BK (admin):
            1. NIP: 198001012019031001
            2. pass: password
         2. Siswa:
            1. NISN: 1234567890
            2. pass: password
11. 📌 File/Folder Utama yang Diperbarui/DITAMBAH
    1.  Migrations: database/migrations/* (role + tabel baru)
    2.  Models: app/Models/* (relasi + fillable)
    3.  Filament Resources: app/Filament/Resources/*
    4.  Login + Dashboard: LoginController.php + DashboardController.php
    5.  Views: login.blade.php, resources/views/student/*
    6.  Middleware: EnsureFilamentUserIsTeacher.php, CheckRole.php
12. ✅ Next Recommended Step (opsional)
    1. Kalau ingin fitur `Curhat seperti WhatsApp` benar-benar interaktif:
    2. Buat front-end Livewire/Alpine untuk chat realtime
    3. Implementasi polling/websocket (Livewire + Pusher atau Socket)
    4. Kalau kamu mau, saya bisa lanjutkan fitur Curhat kompletnya (UI + backend chat realtime + notifikasi).
    5. Kalau ada bagian yang ingin kamu tambahkan/diperdalam (misal: laporan PDF, notifikasi WA, export Excel, UI detail konseling, dsb), tinggal bilang saja.

