<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
$embed = (($_GET['embed'] ?? '') === '1');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: kelola_kelas.php?pesan=id_tidak_valid'.($embed?'&embed=1':'')); exit;
}
$cek = mysqli_prepare($koneksi, 'SELECT id FROM t_kelas WHERE id=?');
if (!$cek) { header('Location: kelola_kelas.php?pesan=hapus_gagal'.($embed?'&embed=1':'')); exit; }
mysqli_stmt_bind_param($cek, 'i', $id); mysqli_stmt_execute($cek);
$ada = mysqli_num_rows(mysqli_stmt_get_result($cek)) > 0; mysqli_stmt_close($cek);
if (!$ada) { header('Location: kelola_kelas.php?pesan=data_tidak_ditemukan'.($embed?'&embed=1':'')); exit; }
$hapus = mysqli_prepare($koneksi, 'DELETE FROM t_kelas WHERE id=?');
mysqli_stmt_bind_param($hapus, 'i', $id);
if (mysqli_stmt_execute($hapus)) {
    header('Location: kelola_kelas.php?pesan=hapus_berhasil'.($embed?'&embed=1':''));
} else {
    header('Location: kelola_kelas.php?pesan=hapus_gagal'.($embed?'&embed=1':''));
}
mysqli_stmt_close($hapus); exit;
?>
