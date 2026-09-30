<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Kelola Siswa'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p><a href="#">+ Tambah Siswa</a></p><table cellpadding="6"><tr><th>No</th><th>NISN</th><th>Nama</th><th>Jenis Kelamin</th><th>Status</th></tr><?php $rows=$pdo->query("SELECT id,nisn,nama,jenis_kelamin,status_aktif FROM t_siswa ORDER BY id DESC")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['nisn']) ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['jenis_kelamin']) ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
