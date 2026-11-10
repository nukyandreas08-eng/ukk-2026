<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

// Catatan: pakai $_GET, bukan filter_input(), karena filter_input()
// tidak selalu membaca $_GET pada konfigurasi PHP tertentu.
$id = (int) ($_GET['id'] ?? 0);
$embed = (($_GET['embed'] ?? '') === '1');
$kembali = 'kelola_siswa.php' . ($embed ? '?embed=1' : '');

if ($id <= 0) {
    $_SESSION['pesan'] = 'ID siswa tidak ditemukan atau tidak valid.';
    header('Location: ' . $kembali);
    exit;
}

$stmt = mysqli_prepare($koneksi, 'DELETE FROM t_siswa WHERE id = ?');

if (!$stmt) {
    $_SESSION['pesan'] = 'Gagal menyiapkan penghapusan: ' . mysqli_error($koneksi);
    header('Location: ' . $kembali);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $id);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['pesan'] = 'Data siswa gagal dihapus. Pastikan data siswa tidak masih '
        . 'dipakai pada penempatan atau catatan pelanggaran. Detail: '
        . mysqli_stmt_error($stmt);
} elseif (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['pesan'] = 'Data siswa berhasil dihapus.';
} else {
    $_SESSION['pesan'] = 'Data siswa tidak ditemukan.';
}

mysqli_stmt_close($stmt);

header('Location: ' . $kembali);
exit;
