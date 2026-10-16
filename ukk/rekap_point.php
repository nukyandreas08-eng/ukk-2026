<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        s.id,
        s.nis,
        s.nama,
        COUNT(ps.id) AS jumlah_pelanggaran,
        COALESCE(SUM(ps.poin), 0) AS total_poin
    FROM t_siswa s
    LEFT JOIN t_pelanggaran_siswa ps
        ON ps.siswa_id = s.id
    GROUP BY s.id, s.nis, s.nama
    ORDER BY total_poin DESC, s.nama ASC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Query gagal: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Poin Pelanggaran Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Rekap Poin Pelanggaran Siswa</h2>

<a href="dashboard.php?embed=1" onclick="if(window.parent!==window){try{var f=window.parent.document.getElementById('contentFrame');if(f){window.parent.document.querySelectorAll('.sidebar .nav-link').forEach(function(a){a.classList.remove('active')});var d=window.parent.document.querySelector('.sidebar .nav-link[href*=\'dashboard\']');if(d)d.classList.add('active');f.src='dashboard.php?embed=1';return false;}}catch(e){}}"
>Kembali ke Dashboard</a>
<button type="button" onclick="window.print()">Cetak Laporan</button>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Jumlah Pelanggaran</th>
            <th>Total Poin</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nis'] ?? ''); ?></td>
            <td><?= htmlspecialchars($data['nama'] ?? ''); ?></td>
            <td><?= $data['jumlah_pelanggaran']; ?></td>
            <td><?= $data['total_poin']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>