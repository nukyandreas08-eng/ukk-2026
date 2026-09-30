<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Kelola Kelas'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p><a href="#">+ Tambah Kelas</a></p><table cellpadding="6"><tr><th>No</th><th>Nama</th><th>Tingkat</th><th>Jurusan</th><th>Status</th></tr><?php $rows=$pdo->query("SELECT nama,tingkat,jurusan,status_aktif FROM t_kelas ORDER BY id")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['tingkat']) ?></td><td><?= htmlspecialchars($r['jurusan']) ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
