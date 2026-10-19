<?php
session_start();
require_once 'includes/cek_session.php';
require_once 'config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_penempatan_siswa.php'); exit;
}
$id = (int)($_POST['id'] ?? 0);
if ($id < 1) {
    $_SESSION['pesan_error'] = 'ID penempatan tidak valid.';
} else {
    $stmt = mysqli_prepare($koneksi, 'DELETE FROM t_kelas_siswa WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = mysqli_stmt_affected_rows($stmt) > 0
            ? 'Penempatan siswa berhasil dihapus.' : 'Data penempatan tidak ditemukan.';
    } else {
        $_SESSION['pesan_error'] = 'Gagal menghapus penempatan siswa. Data mungkin sedang digunakan.';
    }
    mysqli_stmt_close($stmt);
}
$embed = isset($_POST['embed']) && $_POST['embed'] === '1';
header('Location: kelola_penempatan_siswa.php' . ($embed ? '?embed=1' : '')); exit;
