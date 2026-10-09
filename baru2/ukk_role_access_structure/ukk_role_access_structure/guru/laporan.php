<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['guru']);
$title = 'Laporan'; $section='guru';
include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<table><tr><th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Pelanggaran</th><th>Poin</th><th>Guru</th></tr><?php $rows=$pdo->query("SELECT tanggal,nama_siswa,nama_kelas,nama_pelanggaran,poin,nama_guru FROM t_pelanggaran_siswa ORDER BY tanggal DESC,id DESC LIMIT 100")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['tanggal']) ?></td><td><?= htmlspecialchars($r['nama_siswa']) ?></td><td><?= htmlspecialchars($r['nama_kelas']) ?></td><td><?= htmlspecialchars($r['nama_pelanggaran']) ?></td><td><?= (int)$r['poin'] ?></td><td><?= htmlspecialchars($r['nama_guru']) ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
