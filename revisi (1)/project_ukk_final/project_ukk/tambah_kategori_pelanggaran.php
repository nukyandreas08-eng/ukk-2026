
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

    if ($nama === '') {
        $pesan = 'Nama kategori wajib diisi.';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_pelanggaran_kategori
            (nama, deskripsi, status_aktif)
            VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $nama,
            $deskripsi,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: kelola_kategori_pelanggaran.php');
            exit;
        } else {
            $pesan = 'Gagal menyimpan kategori.';
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori Pelanggaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Tambah Kategori Pelanggaran</h2>

<a href="kelola_kategori_pelanggaran.php">Kembali</a>

<br><br>

<?php if ($pesan !== '') { ?>
    <p><?= htmlspecialchars($pesan); ?></p>
<?php } ?>

<form method="POST">
    <table>
        <tr>
            <td>Nama Kategori</td>
            <td>
                <input type="text" name="nama" required>
            </td>
        </tr>
        <tr>
            <td>Deskripsi</td>
            <td>
                <textarea name="deskripsi" rows="4" cols="30"></textarea>
            </td>
        </tr>
        <tr>
            <td>Status Aktif</td>
            <td>
                <input type="checkbox" name="status_aktif" value="1" checked>
                Aktif
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Simpan</button>
                <a href="kelola_kategori_pelanggaran.php">Batal</a>
            </td>
        </tr>
    </table>
</form>

</body>
</html>