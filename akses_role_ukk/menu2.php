<?php
session_start();
require_once 'cek_akses.php';
cekRole('admin');
?>
<h2>Menu 2</h2>
<p>Halaman Menu 2 hanya dapat diakses Admin.</p>
<a href="dashboard.php">Kembali</a>
