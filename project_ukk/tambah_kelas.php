<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    if ($nama === '' || $tingkat === '' || $jurusan === '') {
        $error = "Semua data wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nama,
            $tingkat,
            $jurusan,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: kelola_kelas.php');
            exit;
        }

        $error = "Gagal menyimpan data kelas.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2>Tambah Kelas</h2>

    <?php if (isset($error)) { ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php } ?>

    <form method="POST">
        <table cellpadding="8">
            <tr>
                <td>Nama Kelas</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama" required>
                </td>
            </tr>

            <tr>
                <td>Tingkat</td>
                <td>:</td>
                <td>
                    <select name="tingkat" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Jurusan</td>
                <td>:</td>
                <td>
                    <select name="jurusan" required>
                        <option value="">Pilih Jurusan</option>
                        <option value="RPL">RPL</option>
                        <option value="TKJ">TKJ</option>
                        <option value="TKR">TKR</option>
                        <option value="BD">BD</option>
                        <option value="TSM">TSM</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Status</td>
                <td>:</td>
                <td>
                    <select name="status_aktif" required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <button type="submit">Simpan</button>
                    <a href="kelola_kelas.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>

</body>
</html>