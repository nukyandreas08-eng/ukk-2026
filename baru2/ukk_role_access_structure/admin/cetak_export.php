<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Cetak / Export'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p>Gunakan tombol cetak browser untuk mencetak laporan.</p><p><a href="laporan.php" target="_blank">Buka Laporan</a></p><button type="button" onclick="window.print()">Cetak Halaman</button>
<?php include '../includes/footer.php'; ?>
