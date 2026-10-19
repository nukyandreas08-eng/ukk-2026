<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan data dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_guru.php?embed=1");
    exit;
}

// Ambil data dari form
$id = (int)($_POST['id'] ?? 0);
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$user_id = (int)($_POST['user_id'] ?? 0);
$status_aktif = (int)($_POST['status_aktif'] ?? 0);

// Validasi data
if (
    $id <= 0 ||
    $nip === '' ||
    $nama === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $user_id <= 0 ||
    !in_array($status_aktif, [0, 1], true)
) {
    $_SESSION['pesan'] = "Data guru tidak valid!";
    header("Location: kelola_guru.php?embed=1");
    exit;
}

// Periksa apakah data guru tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_guru WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['pesan'] = "Data guru tidak ditemukan!";
    header("Location: kelola_guru.php?embed=1");
    exit;
}

// Periksa NIP agar tidak duplikat
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_guru WHERE nip = ? AND id != ?"
);

mysqli_stmt_bind_param($stmt, "si", $nip, $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['pesan'] = "NIP sudah digunakan guru lain!";
    header("Location: edit_guru.php?id=" . $id . "&embed=1");
    exit;
}

// Pastikan akun yang dipilih memiliki role guru
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_users WHERE id = ? AND role = 'guru'"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['pesan'] = "Akun guru tidak valid!";
    header("Location: edit_guru.php?id=" . $id . "&embed=1");
    exit;
}

// Periksa apakah akun sudah digunakan guru lain
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_guru WHERE user_id = ? AND id != ?"
);

mysqli_stmt_bind_param($stmt, "ii", $user_id, $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['pesan'] = "Akun tersebut sudah digunakan guru lain!";
    header("Location: edit_guru.php?id=" . $id . "&embed=1");
    exit;
}

// Proses update data guru
$sql = "UPDATE t_guru
        SET nip = ?,
            nama = ?,
            email = ?,
            user_id = ?,
            status_aktif = ?,
            updated_at = NOW()
        WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssiii",
    $nip,
    $nama,
    $email,
    $user_id,
    $status_aktif,
    $id
);

// Jalankan query
if (mysqli_stmt_execute($stmt)) {

    $_SESSION['pesan'] = "Data guru berhasil diperbarui!";

} else {

    $_SESSION['pesan'] = "Gagal memperbarui data guru: " . mysqli_error($koneksi);

}

// Kembali ke halaman kelola guru
header("Location: kelola_guru.php?embed=1");
exit;
?>