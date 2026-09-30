<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Kelola Jenis Pelanggaran'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p><a href="#">+ Tambah Jenis Pelanggaran</a></p><table cellpadding="6"><tr><th>No</th><th>Kode</th><th>Nama</th><th>Poin</th><th>Status</th></tr><?php $rows=$pdo->query("SELECT kode,nama,poin,status_aktif FROM t_pelanggaran ORDER BY id")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['kode']) ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= (int)$r['poin'] ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
