<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['guru']);
$title = 'Catat Pelanggaran'; $section='guru';
include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p>Form pencatatan pelanggaran dapat dikembangkan menggunakan data siswa dan jenis pelanggaran dari database.</p><p>Data tersimpan pada tabel <code>t_pelanggaran_siswa</code>.</p>
<?php include '../includes/footer.php'; ?>
