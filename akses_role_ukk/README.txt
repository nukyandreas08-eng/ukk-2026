FILE HAK AKSES ADMIN & GURU

Struktur:
- dashboard.php
- cek_akses.php
- menu1.php
- menu2.php
- menu3.php
- menu4.php

Aturan:
ADMIN -> Menu 1, Menu 2, Menu 3, Menu 4
GURU  -> Menu 3, Menu 4

PENTING:
Login harus menyimpan role ke session:
$_SESSION['role'] = $data_user['role'];

Jika nama session role di project kamu berbeda, ubah bagian $_SESSION['role'] pada file-file ini.
