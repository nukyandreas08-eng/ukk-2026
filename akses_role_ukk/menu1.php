<?php
session_start();
require_once 'cek_akses.php';
cekRole('admin');
?>
<h2>Menu 1</h2>
<p>Halaman Menu 1 hanya dapat diakses Admin.</p>
<a href="dashboard.php">Kembali</a>
