<?php
session_start();

include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: catatan_pelanggaran.php");
    exit;
}

$siswa_id = (int) ($_POST['siswa_id'] ?? 0);
$jenis_pelanggaran = trim($_POST['jenis_pelanggaran'] ?? '');
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
$keterangan = trim($_POST['keterangan'] ?? '');

$guru_id = (int) ($_SESSION['id'] ?? $_SESSION['id_user'] ?? 0);

if (
    $siswa_id <= 0 ||
    $jenis_pelanggaran === '' ||
    $tanggal === ''
) {
    echo "Data wajib belum lengkap. <a href='catatan_pelanggaran.php'>Kembali</a>";
    exit;
}

if ($guru_id <= 0) {
    echo "Sesi login tidak ditemukan. Silakan login kembali.";
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO t_pelanggaran
    (siswa_id, guru_id, jenis_pelanggaran, keterangan)
    VALUES (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iisss",
    $siswa_id,
    $guru_id,
    $jenis_pelanggaran,
    $tanggal,
    $keterangan
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: catatan_pelanggaran.php?pesan=berhasil");
    exit;
}

echo "Gagal menyimpan pelanggaran: " . htmlspecialchars(mysqli_stmt_error($stmt));
mysqli_stmt_close($stmt);
?>