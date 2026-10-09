PROJECT UKK ROLE ACCESS - SESUAI STRUKTUR USE CASE

Teknologi: PHP Native + PDO + MySQL. Tidak menggunakan CSS.

ROLE & MENU
ADMIN:
- Dashboard
- Kelola Siswa
- Kelola Guru
- Kelola Kelas
- Kelola Tahun Ajaran
- Penempatan Siswa
- Kelola Wali Kelas
- Kategori Pelanggaran
- Jenis Pelanggaran
- Catat Pelanggaran
- Tindakan
- Laporan
- Riwayat
- Rekap Poin
- Cetak / Export
- Logout

GURU:
- Dashboard
- Catat Pelanggaran
- Tindakan
- Laporan
- Riwayat
- Rekap Poin
- Logout

WALI KELAS:
- Dashboard
- Laporan
- Riwayat
- Rekap Poin
- Logout

KEAMANAN
Setiap halaman memeriksa role menggunakan requireRole(). Jadi Guru yang mengetik URL admin secara langsung akan diarahkan ke halaman Akses Ditolak.

INSTALASI
1. Extract folder ke C:\xampp\htdocs\
2. Buat database db_ukk_2026 di phpMyAdmin.
3. Import db_ukk_2026.sql.
4. Jalankan setup_role.sql.
5. Buka http://localhost/ukk_role_access_structure/

LOGIN DEMO
Admin: admin@gmail.com / admin123
Guru: guru@gmail.com / guru123
Wali Kelas: wali@ukk2026.sch.id / guru123
