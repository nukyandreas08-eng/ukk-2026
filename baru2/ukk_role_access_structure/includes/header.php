<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = strtolower($_SESSION['role'] ?? '');
$name = $_SESSION['name'] ?? '';
$section = $section ?? 'root';
function menuUrl(string $path): string {
    global $section;
    if ($section === 'root') return $path;
    return '../' . $path;
}
function sameSectionUrl(string $path): string {
    global $section, $role;
    if ($section === 'root') {
        if ($role === 'admin') return 'admin/' . $path;
        if ($role === 'guru') return 'guru/' . $path;
        if ($role === 'wali_kelas') return 'wali_kelas/' . $path;
    }
    return $path;
}
$theme = [
    'admin' => ['name'=>'ADMINISTRATOR','icon'=>'⚙','desc'=>'Manajemen Sistem'],
    'guru' => ['name'=>'GURU','icon'=>'👨‍🏫','desc'=>'Pencatatan & Monitoring'],
    'wali_kelas' => ['name'=>'WALI KELAS','icon'=>'👨‍💼','desc'=>'Monitoring Siswa'],
];
$t = $theme[$role] ?? ['name'=>'USER','icon'=>'👤','desc'=>'Sistem'];
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title><?= htmlspecialchars($title ?? 'UKK 2026') ?></title></head>
<body>
<table>
<tr>
<td>
    <table>
    <tr><td>
        <h2>Justice Admin</h2>
        <h3><?= $t['icon'] ?> <?= htmlspecialchars($t['name']) ?></h3>
        <?= htmlspecialchars($t['desc']) ?>
        <hr>
        <a href="<?= menuUrl('dashboard.php') ?>">Dashboard</a>
        <?php if ($role === 'admin'): ?>
        <h4>DATA MASTER</h4>
        <p><a href="<?= sameSectionUrl('kelola_siswa.php') ?>">Kelola Siswa</a></p>
        <p><a href="<?= sameSectionUrl('kelola_guru.php') ?>">Kelola Guru</a></p>
        <p><a href="<?= sameSectionUrl('kelola_kelas.php') ?>">Kelola Kelas</a></p>
        <p><a href="<?= sameSectionUrl('tahun_ajaran.php') ?>">Kelola Tahun Ajaran</a></p>
        <p><a href="<?= sameSectionUrl('penempatan_siswa.php') ?>">Penempatan Siswa</a></p>
        <p><a href="<?= sameSectionUrl('wali_kelas.php') ?>">Kelola Wali Kelas</a></p>
        <p><a href="<?= sameSectionUrl('kategori_pelanggaran.php') ?>">Kategori Pelanggaran</a></p>
        <p><a href="<?= sameSectionUrl('jenis_pelanggaran.php') ?>">Jenis Pelanggaran</a></p>
        <p><a href="<?= sameSectionUrl('cetak_export.php') ?>">Cetak / Export</a></p>
        <?php endif; ?>
        <?php if (in_array($role, ['admin','guru'], true)): ?>
        <h4>PELANGGARAN</h4>
        <p><a href="<?= sameSectionUrl('catat_pelanggaran.php') ?>">Catat Pelanggaran</a></p>
        <p><a href="<?= sameSectionUrl('tindakan.php') ?>">Tindakan</a></p>
        <p><a href="<?= sameSectionUrl('laporan.php') ?>">Laporan</a></p>
        <p><a href="<?= sameSectionUrl('riwayat.php') ?>">Riwayat</a></p>
        <p><a href="<?= sameSectionUrl('rekap_poin.php') ?>">Rekap Poin</a></p>
        <?php endif; ?>
        <?php if ($role === 'wali_kelas'): ?>
        <h4>MONITORING</h4>
        <p><a href="<?= sameSectionUrl('laporan.php') ?>">Laporan</a></p>
        <p><a href="<?= sameSectionUrl('riwayat.php') ?>">Riwayat</a></p>
        <p><a href="<?= sameSectionUrl('rekap_poin.php') ?>">Rekap Poin</a></p>
        <?php endif; ?>
        <hr>
        <p><a href="<?= menuUrl('logout.php') ?>">Logout</a></p>
    </td></tr></table>
</td>
<td>
<table>
<tr><td>
<strong><?= $t['icon'] ?> <?= htmlspecialchars($t['name']) ?></strong><br>
Login sebagai: <strong><?= htmlspecialchars($name) ?></strong>
</td></tr>
</table>
<table><tr><td>
