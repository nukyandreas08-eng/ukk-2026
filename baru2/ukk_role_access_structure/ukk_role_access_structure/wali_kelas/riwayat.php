<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['wali_kelas']);
$title = 'Riwayat'; $section='wali_kelas';
include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<table><tr><th>No</th><th>Tanggal</th><th>Siswa</th><th>Pelanggaran</th><th>Poin</th><th>Tindakan</th></tr><?php $rows=$pdo->query("SELECT tanggal,nama_siswa,nama_pelanggaran,poin,tindakan FROM t_pelanggaran_siswa ORDER BY tanggal DESC,id DESC LIMIT 100")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['tanggal']) ?></td><td><?= htmlspecialchars($r['nama_siswa']) ?></td><td><?= htmlspecialchars($r['nama_pelanggaran']) ?></td><td><?= (int)$r['poin'] ?></td><td><?= htmlspecialchars($r['tindakan']) ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
