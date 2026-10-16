
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori Pelanggaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Kelola Kategori Pelanggaran</h2>

<a href="dashboard.php?embed=1">Kembali ke Dashboard</a>
<br><br>

<a href="tambah_kategori_pelanggaran.php">Tambah Kategori Pelanggaran</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
            <th>Status Aktif</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nama']); ?></td>
            <td><?= htmlspecialchars($data['deskripsi'] ?? ''); ?></td>
            <td>
                <?= $data['status_aktif'] ? 'Aktif' : 'Tidak Aktif'; ?>
            </td>
            <td>
                <a href="edit_kategori_pelanggaran.php?id=<?= $data['id']; ?>">Edit</a>
                |
                <a href="hapus_kategori_pelanggaran.php?id=<?= $data['id']; ?>"
                   onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                    Hapus
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>