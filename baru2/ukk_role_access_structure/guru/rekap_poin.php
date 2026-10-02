<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['guru']);
$title = 'Rekap Poin'; $section='guru';
include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<table><tr><th>No</th><th>Siswa</th><th>Kelas</th><th>Total Poin</th></tr><?php $rows=$pdo->query("SELECT nama_siswa,nama_kelas,SUM(poin) total_poin FROM t_pelanggaran_siswa GROUP BY siswa_id,nama_siswa,nama_kelas ORDER BY total_poin DESC")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['nama_siswa']) ?></td><td><?= htmlspecialchars($r['nama_kelas']) ?></td><td><?= (int)$r['total_poin'] ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
