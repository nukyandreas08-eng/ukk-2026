<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    $_SESSION['pesan'] = 'ID siswa tidak ditemukan atau tidak valid.';
    header('Location: kelola_siswa.php?embed=1'); exit;
}
$stmt = mysqli_prepare($koneksi, 'DELETE FROM t_siswa WHERE id = ?');
if (!$stmt) {
    $_SESSION['pesan'] = 'Gagal menyiapkan penghapusan: ' . mysqli_error($koneksi);
    header('Location: kelola_siswa.php?embed=1'); exit;
}
mysqli_stmt_bind_param($stmt, 'i', $id);
if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['pesan'] = 'Data siswa gagal dihapus. Pastikan data siswa tidak masih dipakai pada penempatan atau catatan pelanggaran. Detail: ' . mysqli_stmt_error($stmt);
} elseif (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['pesan'] = 'Data siswa berhasil dihapus.';
} else {
    $_SESSION['pesan'] = 'Data siswa tidak ditemukan.';
}
header('Location: kelola_siswa.php?embed=1'); exit;
?>
