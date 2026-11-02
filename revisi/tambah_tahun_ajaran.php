<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    if (
        $nama === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';
    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_tahun_ajaran
            (nama, tanggal_mulai, tanggal_selesai, status_aktif)
            VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nama,
            $tanggal_mulai,
            $tanggal_selesai,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: kelola_tahun_ajaran.php');
            exit;
        } else {
            $error = 'Gagal menyimpan data tahun ajaran.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tahun Ajaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2>Tambah Tahun Ajaran</h2>

    <?php if ($error !== '') { ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php } ?>

    <form method="POST">
        <table cellpadding="8">
            <tr>
                <td>Nama Tahun Ajaran</td>
                <td>:</td>
                <td>
                    <input
                        type="text"
                        name="nama"
                        placeholder="Contoh: 2026/2027"
                        required
                    >
                </td>
            </tr>

            <tr>
                <td>Tanggal Mulai</td>
                <td>:</td>
                <td>
                    <input type="date" name="tanggal_mulai" required>
                </td>
            </tr>

            <tr>
                <td>Tanggal Selesai</td>
                <td>:</td>
                <td>
                    <input type="date" name="tanggal_selesai" required>
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
                    <a href="kelola_tahun_ajaran.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>

</body>
</html>