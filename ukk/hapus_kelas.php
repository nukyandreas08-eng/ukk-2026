<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? '') === '1');
$kembali = 'kelola_kelas.php' . ($embed ? '?embed=1' : '');

// Catatan: pakai $_GET, bukan filter_input(), karena filter_input()
// tidak membaca $_GET pada sebagian konfigurasi PHP.
$id = (int) ($_GET['id'] ?? 0);

if ($id < 1) {
    $_SESSION['pesan'] = 'ID kelas tidak valid.';
    header('Location: ' . $kembali);
    exit;
}

// Pastikan kelas ada dan tidak sedang dipakai data lain.
$cek = mysqli_prepare($koneksi, 'SELECT id FROM t_kelas WHERE id = ?');

if (!$cek) {
    $_SESSION['pesan'] = 'Gagal memeriksa data kelas.';
    header('Location: ' . $kembali);
    exit;
}

mysqli_stmt_bind_param($cek, 'i', $id);
mysqli_stmt_execute($cek);

$ada = mysqli_num_rows(mysqli_stmt_get_result($cek)) > 0;

mysqli_stmt_close($cek);

if (!$ada) {
    $_SESSION['pesan'] = 'Data kelas tidak ditemukan.';
    header('Location: ' . $kembali);
    exit;
}

// Kunci baris agar tidak ada dua request hapus bersamaan.
$hapus = mysqli_prepare($koneksi, 'DELETE FROM t_kelas WHERE id = ?');

if (!$hapus) {
    $_SESSION['pesan'] = 'Gagal menyiapkan penghapusan: ' . mysqli_error($koneksi);
    header('Location: ' . $kembali);
    exit;
}

mysqli_stmt_bind_param($hapus, 'i', $id);

if (mysqli_stmt_execute($hapus)) {
    if (mysqli_stmt_affected_rows($hapus) > 0) {
        $_SESSION['pesan'] = 'Data kelas berhasil dihapus.';
    } else {
        $_SESSION['pesan'] = 'Data kelas tidak ditemukan.';
    }
} else {
    $_SESSION['pesan'] = 'Data kelas gagal dihapus karena masih digunakan data lain.';
}

mysqli_stmt_close($hapus);

header('Location: ' . $kembali);
exit;
