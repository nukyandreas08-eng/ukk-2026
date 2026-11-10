<?php
/**
 * ============================================================
 * PEMBUATAN AKUN ADMIN AWAL
 * ============================================================
 * Script ini hanya boleh dijalankan pada saat belum ada akun
 * administrator di database (bootstrap). Jika akun admin sudah
 * ada, script hanya boleh dijalankan oleh administrator yang
 * sudah login.
 */

require_once __DIR__ . '/config/koneksi.php';

$cekAdmin = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_users WHERE role = 'admin'"
);

$jumlahAdmin = $cekAdmin
    ? (int) (mysqli_fetch_assoc($cekAdmin)['total'] ?? 0)
    : 1;

// Jika admin sudah tersedia, wajib login sebagai admin.
if ($jumlahAdmin > 0) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (
        !isset($_SESSION['login']) ||
        $_SESSION['login'] !== true ||
        strtolower(trim((string) ($_SESSION['role'] ?? ''))) !== 'admin'
    ) {
        http_response_code(403);
        exit('Anda tidak memiliki akses.');
    }
}

$name = 'administrator';
$email = 'admin@gmail.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

$sql = "INSERT INTO t_users (name, email, password, role)
        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $name,
    $email,
    $password,
    $role
);

if (mysqli_stmt_execute($stmt)) {
    echo "User admin berhasil dibuat.";
    echo "<br>Email: admin@gmail.com";
    echo "<br>Password: admin123";
} else {
    echo "Gagal membuat user: " . mysqli_error($koneksi);
}
