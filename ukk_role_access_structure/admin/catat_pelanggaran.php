<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Catat Pelanggaran'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p>Halaman pencatatan pelanggaran. Data tersimpan pada tabel <code>t_pelanggaran_siswa</code>.</p><p><a href="laporan.php">Lihat Laporan</a> | <a href="tindakan.php">Kelola Tindakan</a></p>
<?php include '../includes/footer.php'; ?>
