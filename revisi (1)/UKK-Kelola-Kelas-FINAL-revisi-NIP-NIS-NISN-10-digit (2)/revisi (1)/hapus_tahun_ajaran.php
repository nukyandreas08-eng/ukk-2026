<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    $_SESSION['pesan'] = 'ID tahun ajaran tidak valid.';
    header('Location: kelola_tahun_ajaran.php'); exit;
}
// Tahun ajaran yang masih dipakai kelas tidak boleh dihapus agar relasi tetap terjaga.
$cek = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS jumlah FROM t_kelas WHERE tahun_ajaran_id = ?');
if (!$cek) { $_SESSION['pesan'] = 'Gagal memeriksa relasi tahun ajaran dengan kelas.'; header('Location: kelola_tahun_ajaran.php'); exit; }
mysqli_stmt_bind_param($cek, 'i', $id);
mysqli_stmt_execute($cek);
$jumlah = (int)(mysqli_fetch_assoc(mysqli_stmt_get_result($cek))['jumlah'] ?? 0);
mysqli_stmt_close($cek);
if ($jumlah > 0) {
    $_SESSION['pesan'] = 'Tahun ajaran tidak dapat dihapus karena masih digunakan oleh ' . $jumlah . ' data kelas. Ubah relasi kelas terlebih dahulu.';
    header('Location: kelola_tahun_ajaran.php'); exit;
}
$hapus = mysqli_prepare($koneksi, 'DELETE FROM t_tahun_ajaran WHERE id = ?');
mysqli_stmt_bind_param($hapus, 'i', $id);
if (mysqli_stmt_execute($hapus)) {
    $_SESSION['pesan'] = mysqli_stmt_affected_rows($hapus) > 0 ? 'Data tahun ajaran berhasil dihapus.' : 'Data tahun ajaran tidak ditemukan.';
} else {
    $_SESSION['pesan'] = 'Gagal menghapus tahun ajaran. Data ini mungkin masih digunakan oleh penempatan siswa atau wali kelas.';
}
mysqli_stmt_close($hapus);
header('Location: kelola_tahun_ajaran.php'); exit;
?>
