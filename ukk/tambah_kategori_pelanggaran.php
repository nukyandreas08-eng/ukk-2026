<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$kembali = 'kelola_kategori_pelanggaran.php' . ($embed ? '?embed=1' : '');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

    if ($nama === '') {
        $error = 'Nama kategori wajib diisi.';
    } else {

        // Nama kategori harus unik agar tidak terjadi data kembar.
        $cek = mysqli_prepare(
            $koneksi,
            'SELECT id FROM t_pelanggaran_kategori WHERE nama = ?'
        );

        mysqli_stmt_bind_param($cek, 's', $nama);
        mysqli_stmt_execute($cek);

        $kodeTerpakai = mysqli_num_rows(mysqli_stmt_get_result($cek)) > 0;

        mysqli_stmt_close($cek);

        if ($kodeTerpakai) {
            $error = 'Nama kategori sudah digunakan. Gunakan nama lain.';
        } else {

            $stmt = mysqli_prepare(
                $koneksi,
                'INSERT INTO t_pelanggaran_kategori
                 (nama, deskripsi, status_aktif)
                 VALUES (?, ?, ?)'
            );

            mysqli_stmt_bind_param($stmt, 'ssi', $nama, $deskripsi, $status_aktif);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                $_SESSION['pesan'] = 'Kategori pelanggaran berhasil ditambahkan.';

                header('Location: ' . $kembali);
                exit;
            }

            $error = 'Gagal menyimpan kategori.';

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kategori Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Tambah Kategori Pelanggaran</h2>
    <hr>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form method="POST" action="tambah_kategori_pelanggaran.php" class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-12">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama" class="form-control" maxlength="100" required>
        </div>

        <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"></textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Status Aktif</label>
            <select name="status_aktif" class="form-select">
                <option value="1" selected>Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= htmlspecialchars($kembali) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>

</div>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
