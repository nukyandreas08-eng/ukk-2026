<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    exit("Anda tidak memiliki akses.");
}

require_once __DIR__ . "/config/koneksi.php";

mysqli_begin_transaction($koneksi);

try {

    $query = mysqli_query($koneksi, "
        SELECT id, nama
        FROM t_guru
        WHERE user_id IS NULL
        ORDER BY id ASC
    ");

    if (!$query) {
        throw new Exception(mysqli_error($koneksi));
    }

    $insert = mysqli_prepare($koneksi, "
        INSERT INTO t_users
        (name, email, password, role, remember_token)
        VALUES (?, ?, ?, ?, ?)
    ");

    $update = mysqli_prepare($koneksi, "
        UPDATE t_guru
        SET user_id = ?
        WHERE id = ? AND user_id IS NULL
    ");

    $cekEmail = mysqli_prepare($koneksi, "
        SELECT id FROM t_users WHERE email = ?
    ");

    $hasil = [];

    while ($guru = mysqli_fetch_assoc($query)) {

        $id = (int)$guru['id'];
        $nama = $guru['nama'];

        $email = "guru" . $id . "@ukk.local";
        $password = "Guru@2026#" . $id;
        $role = "guru";
        $remember_token = "";

        mysqli_stmt_bind_param($cekEmail, "s", $email);
        mysqli_stmt_execute($cekEmail);
        mysqli_stmt_store_result($cekEmail);

        if (mysqli_stmt_num_rows($cekEmail) > 0) {
            throw new Exception("Email $email sudah digunakan.");
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param(
            $insert,
            "sssss",
            $nama,
            $email,
            $hash,
            $role,
            $remember_token
        );

        if (!mysqli_stmt_execute($insert)) {
            throw new Exception(mysqli_stmt_error($insert));
        }

        $user_id = mysqli_insert_id($koneksi);

        mysqli_stmt_bind_param(
            $update,
            "ii",
            $user_id,
            $id
        );

        if (!mysqli_stmt_execute($update)) {
            throw new Exception(mysqli_stmt_error($update));
        }

        $hasil[] = [
            'id' => $id,
            'nama' => $nama,
            'email' => $email,
            'password' => $password
        ];
    }

    mysqli_commit($koneksi);

} catch (Exception $e) {

    mysqli_rollback($koneksi);
    exit("Gagal membuat akun: " . htmlspecialchars($e->getMessage()));

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container-fluid">

    <h3 class="mb-3">Daftar Akun Guru</h3>

    <?php if (count($hasil) > 0): ?>

        <div class="alert alert-success">
            Berhasil membuat <?= count($hasil) ?> akun guru.
            Simpan daftar akun dan password ini.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID Guru</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hasil as $akun): ?>
                        <tr>
                            <td><?= $akun['id'] ?></td>
                            <td><?= htmlspecialchars($akun['nama']) ?></td>
                            <td><?= htmlspecialchars($akun['email']) ?></td>
                            <td><?= htmlspecialchars($akun['password']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>

        <div class="alert alert-info">
            Semua guru sudah memiliki akun.
        </div>

    <?php endif; ?>

</div>

</body>
</html>