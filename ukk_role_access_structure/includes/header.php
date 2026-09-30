<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = strtolower($_SESSION['role'] ?? '');
$name = $_SESSION['name'] ?? '';
$section = $section ?? 'root';
function menuUrl(string $path): string {
    global $section;
    if ($section === 'root') return $path;
    if ($section === 'admin' || $section === 'guru' || $section === 'wali_kelas') return '../' . $path;
    return $path;
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
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title><?= htmlspecialchars($title ?? 'UKK 2026') ?></title></head>
<body>
<table width="100%" cellpadding="8"><tr>
<td valign="top" width="230">
<h2>Justice Admin</h2>
<p><strong><?= htmlspecialchars(strtoupper($role)) ?></strong></p>
<p><a href="<?= menuUrl('dashboard.php') ?>">Dashboard</a></p>
<?php if ($role === 'admin'): ?>
<p><strong>ADMIN</strong></p>
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
<p><strong>PELANGGARAN</strong></p>
<p><a href="<?= sameSectionUrl('catat_pelanggaran.php') ?>">Catat Pelanggaran</a></p>
<p><a href="<?= sameSectionUrl('tindakan.php') ?>">Tindakan</a></p>
<p><a href="<?= sameSectionUrl('laporan.php') ?>">Laporan</a></p>
<p><a href="<?= sameSectionUrl('riwayat.php') ?>">Riwayat</a></p>
<p><a href="<?= sameSectionUrl('rekap_poin.php') ?>">Rekap Poin</a></p>
<?php endif; ?>
<?php if ($role === 'wali_kelas'): ?>
<p><strong>WALI KELAS</strong></p>
<p><a href="<?= sameSectionUrl('laporan.php') ?>">Laporan</a></p>
<p><a href="<?= sameSectionUrl('riwayat.php') ?>">Riwayat</a></p>
<p><a href="<?= sameSectionUrl('rekap_poin.php') ?>">Rekap Poin</a></p>
<?php endif; ?>
<p><a href="<?= menuUrl('logout.php') ?>">Logout</a></p>
</td>
<td valign="top">
<p>Login sebagai: <strong><?= htmlspecialchars($name) ?></strong> (<?= htmlspecialchars(strtoupper($role)) ?>)</p><hr>
