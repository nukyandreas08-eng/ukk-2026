<?php
require 'includes/auth.php'; require 'config.php';
$title = 'Dashboard'; $pagePrefix = '';
$tables = ['t_siswa'=>'Siswa','t_guru'=>'Guru','t_kelas'=>'Kelas','t_pelanggaran'=>'Jenis Pelanggaran','t_pelanggaran_siswa'=>'Catatan Pelanggaran'];
$counts=[]; foreach($tables as $t=>$label){ $counts[$label]=(int)$pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn(); }
include 'includes/header.php';
?>
<h1>Dashboard</h1>
<p>Selamat datang di sistem pencatatan pelanggaran siswa.</p>
<table cellpadding="10"><tr><th>Siswa</th><th>Guru</th><th>Kelas</th><th>Jenis Pelanggaran</th><th>Catatan Pelanggaran</th></tr>
<tr><td align="center"><?= $counts['Siswa'] ?></td><td align="center"><?= $counts['Guru'] ?></td><td align="center"><?= $counts['Kelas'] ?></td><td align="center"><?= $counts['Jenis Pelanggaran'] ?></td><td align="center"><?= $counts['Catatan Pelanggaran'] ?></td></tr></table>
<?php include 'includes/footer.php'; ?>
