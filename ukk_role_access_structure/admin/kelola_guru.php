<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Kelola Guru'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p><a href="#">+ Tambah Guru</a></p><table cellpadding="6"><tr><th>No</th><th>NIP</th><th>Nama</th><th>Email</th><th>Status</th></tr><?php $rows=$pdo->query("SELECT nip,nama,email,status_aktif FROM t_guru ORDER BY id DESC")->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['nip']) ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['email']) ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
