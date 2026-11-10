<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? '') === '1');
$kembali = 'kelola_kelas.php' . ($embed ? '?embed=1' : '');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: ' . $kembali . '?pesan=id_tidak_valid');
    exit;
}

$cek = mysqli_prepare($koneksi, 'SELECT id FROM t_kelas WHERE id = ?');

if (!$cek) {
    header('Location: ' . $kembali . '?pesan=hapus_gagal');
    exit;
}

mysqli_stmt_bind_param($cek, 'i', $id);
mysqli_stmt_execute($cek);

$ada = mysqli_num_rows(mysqli_stmt_get_result($cek)) > 0;

mysqli_stmt_close($cek);

if (!$ada) {
    header('Location: ' . $kembali . '?pesan=data_tidak_ditemukan');
    exit;
}

$hapus = mysqli_prepare($koneksi, 'DELETE FROM t_kelas WHERE id = ?');
mysqli_stmt_bind_param($hapus, 'i', $id);

if (mysqli_stmt_execute($hapus)) {
    $_SESSION['pesan'] = 'Data kelas berhasil dihapus.';
} else {
    $_SESSION['pesan'] = 'Data kelas gagal dihapus karena masih digunakan data lain.';
}

mysqli_stmt_close($hapus);

header('Location: ' . $kembali);
exit;
