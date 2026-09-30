<?php
// Gunakan setelah session_start()
function cekRole($roleDiizinkan) {
    if (!isset($_SESSION['role'])) {
        header('Location: login.php');
        exit;
    }

    if ($_SESSION['role'] !== $roleDiizinkan) {
        http_response_code(403);
        echo '<h2>Akses Ditolak!</h2>';
        echo '<p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>';
        echo '<a href="dashboard.php">Kembali ke Dashboard</a>';
        exit;
    }
}
?>
