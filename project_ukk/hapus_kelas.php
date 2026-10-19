<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: kelola_kelas.php?pesan=id_tidak_valid');
    exit;
}

// Pastikan data kelas memang ada sebelum dihapus.
$cek = mysqli_prepare($koneksi, 'SELECT id FROM t_kelas WHERE id = ?');
mysqli_stmt_bind_param($cek, 'i', $id);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);
if (!mysqli_fetch_assoc($hasil)) {
    header('Location: kelola_kelas.php?pesan=data_tidak_ditemukan');
    exit;
}

// Relasi t_kelas_siswa pada skema project menggunakan ON DELETE CASCADE.
$hapus = mysqli_prepare($koneksi, 'DELETE FROM t_kelas WHERE id = ?');
mysqli_stmt_bind_param($hapus, 'i', $id);
if (mysqli_stmt_execute($hapus)) {
    header('Location: kelola_kelas.php?pesan=hapus_berhasil');
    exit;
}
header('Location: kelola_kelas.php?pesan=hapus_gagal');
exit;
