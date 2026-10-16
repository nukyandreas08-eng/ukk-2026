<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        ks.id,
        s.nis,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        ta.nama AS tahun_ajaran
    FROM t_kelas_siswa ks
    LEFT JOIN t_siswa s ON ks.siswa_id = s.id
    LEFT JOIN t_kelas k ON ks.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    ORDER BY ks.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penempatan Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Penempatan Siswa</h2>
<p>Kelola penempatan siswa berdasarkan kelas dan tahun ajaran.</p>

<a href="dashboard.php?embed=1">Kembali ke Dashboard</a> |
<a href="tambah_penempatan_siswa.php">Tambah Penempatan Siswa</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Tahun Ajaran</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $no = 1;

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>
            <td>
                <a href="hapus_penempatan_siswa.php?id=<?= (int) $row['id'] ?>"
                   onclick="return confirm('Yakin ingin menghapus penempatan siswa ini?')">
                    Hapus
                </a>
            </td>
        </tr>
    <?php
        }
    } else {
    ?>
        <tr>
            <td colspan="6">Belum ada data penempatan siswa.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>