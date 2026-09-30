<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Kelola Tahun Ajaran'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p><a href="#">+ Tambah Tahun Ajaran</a></p><table cellpadding="6"><tr><th>No</th><th>Nama</th><th>Mulai</th><th>Selesai</th><th>Status</th></tr><?php $rows=$pdo->query("SELECT nama,tanggal_mulai,tanggal_selesai,status_aktif FROM t_tahun_ajaran ORDER BY id DESC")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['tanggal_mulai']) ?></td><td><?= htmlspecialchars($r['tanggal_selesai']) ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
