<?php
require '../includes/auth.php'; require '../config.php'; requireRole(['admin']);
$title = 'Kelola Wali Kelas'; $section='admin';

include '../includes/header.php';
?>
<h1><?= htmlspecialchars($title) ?></h1>
<table cellpadding="6"><tr><th>No</th><th>Guru</th><th>Kelas</th><th>Tahun Ajaran</th><th>Status</th></tr><?php $sql="SELECT wk.id,g.nama AS guru,k.nama AS kelas,ta.nama AS tahun,wk.status_aktif FROM t_wali_kelas wk JOIN t_guru g ON g.id=wk.guru_id JOIN t_kelas k ON k.id=wk.kelas_id JOIN t_tahun_ajaran ta ON ta.id=wk.tahun_ajaran_id ORDER BY wk.id DESC"; $rows=$pdo->query($sql)->fetchAll(); foreach($rows as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($r['guru']) ?></td><td><?= htmlspecialchars($r['kelas']) ?></td><td><?= htmlspecialchars($r['tahun']) ?></td><td><?= $r['status_aktif']?'Aktif':'Tidak Aktif' ?></td></tr><?php endforeach; ?></table>
<?php include '../includes/footer.php'; ?>
