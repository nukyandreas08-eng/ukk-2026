<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Penempatan Siswa'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<table cellpadding="6"><tr><th>No</th><th>Siswa</th><th>Kelas</th><th>Tahun Ajaran</th><th>Status</th></tr><?php $sql="SELECT ks.id,s.nama,k.nama AS kelas,ta.nama AS tahun,ks.status_aktif FROM t_kelas_siswa ks JOIN t_siswa s ON s.id=ks.siswa_id JOIN t_kelas k ON k.id=ks.kelas_id JOIN t_tahun_ajaran ta ON ta.id=ks.tahun_ajaran_id ORDER BY ks.id DESC"; $rows=$pdo->query($sql)->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['kelas']) ?></td><td><?= htmlspecialchars($r['tahun']) ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
