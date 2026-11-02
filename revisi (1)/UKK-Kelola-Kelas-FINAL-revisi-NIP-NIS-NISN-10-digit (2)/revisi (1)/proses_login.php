<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

// Pastikan halaman hanya menerima pengiriman form POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    header('Location: login.php');
    exit;
}

$loginInput = trim($_POST['username'] ?? '');
$passwordInput = $_POST['password'] ?? '';

if ($loginInput === '' || $passwordInput === '') {
    $_SESSION['pesan_error'] = 'Username/email dan password wajib diisi.';
    header('Location: login.php');
    exit;
}

// Login dapat menggunakan kolom name (username) atau email.
$stmt = mysqli_prepare(
    $koneksi,
    'SELECT id, name, email, password, role FROM t_users WHERE name = ? OR email = ? LIMIT 1'
);

if (!$stmt) {
    error_log('Gagal menyiapkan query login: ' . mysqli_error($koneksi));
    $_SESSION['pesan_error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: login.php');
    exit;
}

mysqli_stmt_bind_param($stmt, 'ss', $loginInput, $loginInput);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

if ($user && !empty($user['password']) && password_verify($passwordInput, $user['password'])) {
    session_regenerate_id(true);

    $_SESSION['login'] = true;
    $_SESSION['id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    header('Location: dashboard.php');
    exit;
}

$_SESSION['pesan_error'] = 'Username/email atau password salah.';
header('Location: login.php');
exit;
?>
