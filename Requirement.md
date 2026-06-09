# Pengenalan
Anggap anda adalah seorang programmer Laravel profesional dengan pengalaman lebih dari 10 tahun, lalu dalam aplikasi yang akan saya buat ini menggunakan laravel versi 12 dan filament versi 5, tolong dalam pembuatan aplikasi ini anda buat berdasarkan best practice dari Laravel versi 12 dan filament versi 5. 

Oke jadi saya ingin membuat aplikasi konseling atau bimbingan siswa, dengan requirement seperti di bawah ini :

# REQUIREMENT SYSTEM GURU BK
1. [x] Warna harus ada warna Ungu `#2597C`
2. [x] Tampilan web desktop dan bisa web mobile
3. [x] 3 Role login: 
   1. Guru Bk 
   2. Kepala Sekolah (Read only data laporan siswa),
   3. Siswa (Dibuatkan akun oleh guru bk).
4. [x] Laravel terbaru version 12, PHP 8.4, Filament v5
5. [x] Login dengan menggunakan NIP & Password (buat guru bk dan kepsek), NISN & password (siswa) 


# GURU BK : 
1. [x] Dashboard Grafik data siswa yang bermasalah
2. [x] Fitur yg dibutuhkan  : 
   1. [x] Jadwal Konseling (Dengan Isi Dari Form pengisian Konseling yang diisi oleh siswa, dan memasukan data dari hasil rekap absensi siswa yang gak masuk)
      1. [x] Ada jadwal konseling guru bk untuk siswa
      2. [x] Ada jadwal konseling siswa untuk guru bk
   2. [x] Rekap Absensi Siswa Perkelas
   3. [x] ⁠Daftar Akun Siswa
   4. [x] ⁠Data Kelas dan jurusan Untuk kelas : Total Kelas dimana masing2 siswa perkelas sekitar 40 siswa (kelas 10 = 12 kelas, Kelas 11 = 12 Kelas, Kelas 12 = 12 kelas)
   5. [x] ⁠Curhat Siswa
   6. [x] ⁠Report Laporan
   7. [x] Dashboard guru bk dan kepsek, tampilkan diagram pelanggaran siswa


# Sistem kerja GURU BK
1. [ ] Guru BK mengisi laporan Form konseling. Isi laporan: 
   1. [x] Jenis masalah
   2. [x] Ringkasan konseling
   3. [x] Solusi 
   4. [x] Catatan : 
      1. [x] ini biasanya hanya bisa dilihat guru BK                
   5. [x] Bagian Input Pelanggaran, 
      1. [x] Jenis pelanggaran (Contoh: Terlambat, Tidak pakai seragam, Bolos),
      2. [x] Point pelanggaran,
         1. [ ] Jika point tinggi → otomatis masuk daftar konseling.
      3. [x] Riwayat pelanggaran
2. [x] Laporan & Statistik (masuk ke point 1 dashboardnya)
3. [x] Notifikasi Sistem : Agar sistem terasa modern, via whatsapp
4. [x] Fitur absensi 
   1. [x] Fitur absensi yang dimana guru bk memasukan absensi siswa perkelas, 
   2. [x] Setelah itu dibuat report perbulan siapa aja yang jarang masuk perkelas itu, dan banyak alpa/izin, lalu siswa itu perlu tindakan bimbingan dengan guru bk.
5. [x] (Untuk Siswa) Fitur curhatan siswa (namanya dibuat menarik agar si siswa mau ngeluarin unek2nya), yg dmna nnti kaya dibuat percakapan seperti wa gitu antara satu siswa dengan guru bk (gunakan email).
6. [x] Fitur Report laporan : 
   1. [x] laporan semua siswa yang bermasalah 
   2. [x] bisa dilihat oleh kepsek 
   3. [x] ada fitur export PDF, Excel
7. [x] Fitur Tambah akun siswa untuk Login
8. [x] Pada fitur curhat threads, adakan notifikasi pada siswa dan guru, sehingga dapat saling berbalas pesan
9.  [x] Di profile siswanya ada coloumn alamat siswa trsbt sama nomer orang tua kak cuma yg edit siswa sama bk aja kak aksesnya
10. [x] Pada halaman konseling siswa tampilkan data2 riwayat pelanggaran sebelumnya
11. [x] Ada jadwal konseling siswa untuk guru bk


# Dashboard SISWA
1. Dashboard Siswa 
   1. [x] Profile siswa,
   2. [x] Ajukan konseling, 
   3. [x] Jadwal konseling, 
   4. [x] Riwayat konseling
2. Curhat/chatan dengan guru bk
   1. Ini fitur utama.
      1. Siswa bisa:
         1. [x] Ajukan konseling, 
         2. [x] Jadwal Konseling, 
         3. [x] Pilih jenis masalah (Form Pilihan Scrol bawah) bimbingan tentang :
            1. Bimbingan pribadi,
            2. Bimbingan belajar,
            3. bimbingan sosial,
            4. bimbingan karir,
            5. bimbingan konseling,ada Keterangan/Isi keluhan
      2. [x] Form Curhatan Siswa
      3. [x] ⁠Status Konseling: Pending, Dijadwalkan, Selesai, Ditindaklanjuti
      4. [x] Database notifikasi pada Guru BK terpilih, jika ada siswa mengajukan konseling

Tolong buat aplikasi ini mudah dimengerti dan juga cepat dalam hal memproses data dan load pada halamannya


# New Request (WA)
1. [ ] Klo emailnya di pke untuk ngirim report pemanggilan nya kak, kya file pdf pemanggilan org tua
2. [x] Bagian login ditambahkan logo sekolah
   1. [x] Sama paling bagian laravelnya ganti sama logo sman 1 batujaya sama tulisan sman 1 batujaya aja kak
3. [x] Bagian atas dashboard yang tulisan laravel di ganti
4. [x] Buat di tampilan yang kepala sekola bagian curhatnya thread nya kepsek jangan di tampilin aja kak hapus aja, yg curhatnya hanya antara siswa sama guru bk aja kak
5. [ ] Iya kak,bisa nggk pas buat new usernya , klo pilih role nya bk trs nisn nya kunci kosongin aja kak langsung isi nip bk bgian klasnya pas add kosongin jg (CEK LAGI REQUEST INI DI WA, ADA REPLY KE IMAGE)
6. [ ] Login akun kepsek nampilin laporan data siswa yang bermasalah, sama persetujuan ttd kepseknya kak buat di pdf pemanggilan siswa nya
7. [ ] Yang bagian File pdf pemanggilan siswa untu di kirim ke email siswa nya udah blm kak?
8. [x] Guru bk ada 3 guru,sama kepsek satu
9. [x] Halaman daftar absen siswa
   1. [x] sama yang absensinya dibuatnya pernama tapi dibuat absen langsung kaya full bulanan gtu bisa nggk ka ?
10. [x] konseling maksudnya digabung kak, di ui si siswa ngelakuin bimbingan ke guru bk, fieldnya 
   1. [x] masalah bimbingan (pribadi,belajar,sosial,karir,dan konseling),
   2. [x] terus di siswa nya nampilin jg pemanggilan dari guru bk terkait masalah pemanggilan kesalahan siswa mulai dari jarang masuk ke sekolah, yang di tampilan bk nampilin field yg kaka tulis itu, nah field dri guru bk sama siswa itu saling masuk gtu ka
11. [x] Halaman Konseling Guru BK
   1. [x] sama yang konseling gru bknya ini gak ush kak, yang bimbingannya konselingnya cuma siswa aja, paling fitur curhatan, itu ada di tampilan siswa sama guru bk yang saling terhubung seperti bot chat gtu kak
12. [ ] bagian dashboard, widget filament dan logoutnya di hilangkan, ganti dengan widget statistik
13. [x] Yg di dasbord siswa harusnya munculin yg bagian pengajuan konseling siswa trsbt sama curhatan aja kak
14. [x] Tampilan full fitur hanya untuk role guru bk
15. [x] Fitur curhat
    1.  [x] Siswa hanya bisa mengobrol dengan guru
    2.  [x] Field
        1.  [x] field nama siswa
        2.  [x] field kelas
        3.  [x] field nama guru konseling
        4.  [x] field form curhat
16. [x] Absen per daftar siswa, terus di masing2 siswa bisa ditampilkan absen detail-nya, dan di absen detail ini bisa ditampilkan per bulan...
17. [ ] Kak di dashboard bk sama kepseknya munculin diagram bisa nggk kak kaya gini? Ngambil data dari pelanggaran siswa yg udah masuk perbulannya ka?, referensi seperti gambar public/assets/images/contoh-diagram.jpeg


GURU BK
=============================
1. [x] Modul Absensi Siswa (create, edit, delete absensi)
2. [x] Modul Report Absensi
3. [x] Modul Data Siswa (create, edit, delete siswa), read absensi per siswa per hari/minggu/bulan/tahun
4. [x] Modul Report pelanggaran siswa
5. [x] Modul Ruang Kelas (create, edit, delete kelas)
6. [x] Modul Curhat Siswa
7. [x] Modul konseling guru bk
8. [x] Modul konseling siswa



SISWA
==============================
1. Modul Konseling (create, edit, delete konseling) (field2 : masalah bimbingan (pribadi,belajar,sosial,karir,dan konseling))
2. Modul Curhat Siswa (siswa hanya mengobrol dengan guru bk, field2 nya nama siswa, kelas, nama guru yang di ajak curhat, form curhat)
3. Modul konseling siswa
4. Modul konseling guru bk



# PR SAAT INI
1. Buat mailing sistem
2. Resource buat guru bk
3. cari keyword 'belum'