<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

if (!in_array(strtolower($_SESSION['role']), ['admin', 'guru'])) {
    http_response_code(403);
    exit('Akses Ditolak!');
}
?>
<h2>Menu 4</h2>
<p>Menu 4 dapat diakses Admin dan Guru.</p>
<a href="dashboard.php">Kembali</a>
