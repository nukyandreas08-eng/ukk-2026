<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Akses ditolak. Login sebagai administrator.');
}
$hash = '$2y$12$XVFV7RDWOV8CARg70T71MOv8bYQeAClr0Vdg2QiP5x9jrkt5UlQHq';
mysqli_begin_transaction($koneksi);
try {
    $sql = "INSERT INTO t_users (name, username, email, password, remember_token, role, created_at, updated_at)
            SELECT TRIM(g.nama), TRIM(g.nama), CONCAT('guru',g.id,'@sekolah.local'), ?, '', 'guru', NOW(), NOW()
            FROM t_guru g
            WHERE g.status_aktif=1 AND g.nama IS NOT NULL AND TRIM(g.nama)<>''
            ON DUPLICATE KEY UPDATE name=VALUES(name), password=VALUES(password), role='guru', updated_at=NOW()";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 's', $hash);
    if (!mysqli_stmt_execute($stmt)) throw new Exception(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);

    $update = "UPDATE t_guru g JOIN t_users u ON u.username=TRIM(g.nama) AND u.role='guru'
               SET g.user_id=u.id, g.updated_at=NOW() WHERE g.status_aktif=1";
    if (!mysqli_query($koneksi, $update)) throw new Exception(mysqli_error($koneksi));
    mysqli_commit($koneksi);
    $result = mysqli_query($koneksi, "SELECT g.nama,u.username FROM t_guru g JOIN t_users u ON u.id=g.user_id WHERE g.status_aktif=1 ORDER BY g.id");
    echo '<h2>Akun guru berhasil dibuat</h2><p>Password sementara semua guru: <b>Guru2026!</b></p><table border="1" cellpadding="8"><tr><th>Nama Guru</th><th>Username</th></tr>';
    while ($r=mysqli_fetch_assoc($result)) echo '<tr><td>'.htmlspecialchars($r['nama']).'</td><td>'.htmlspecialchars($r['username']).'</td></tr>';
    echo '</table><p>Hapus file buat_akun_semua_guru.php setelah selesai.</p>';
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    http_response_code(500);
    echo 'Gagal membuat akun dan perubahan dibatalkan: '.htmlspecialchars($e->getMessage());
}
