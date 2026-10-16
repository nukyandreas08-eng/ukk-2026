<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$query = mysqli_query($koneksi, "
    SELECT * FROM t_kelas
    ORDER BY id DESC
");

if (!$query) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

// Jika halaman dibuka melalui iframe dashboard,
// tampilkan konten saja tanpa navbar kedua.
$embed = isset($_GET['embed']) && $_GET['embed'] === '1';
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-0">

    <h1 class="mb-2">Kelola Kelas</h1>
    <hr>

    <p>Halaman untuk mengelola data kelas.</p>

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="dashboard.php?embed=1" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>

        <a href="tambah_kelas.php" class="btn btn-primary">
            + Tambah Kelas
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0) {
                    while ($kelas = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($kelas['nama']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($kelas['tingkat']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($kelas['jurusan']) ?>
                        </td>

                        <td>
                            <?php if ($kelas['status_aktif']) { ?>
                                <span class="badge text-bg-success">Aktif</span>
                            <?php } else { ?>
                                <span class="badge text-bg-secondary">
                                    Tidak Aktif
                                </span>
                            <?php } ?>
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="edit_kelas.php?id=<?= $kelas['id'] ?>"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_kelas.php?id=<?= $kelas['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data kelas.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>