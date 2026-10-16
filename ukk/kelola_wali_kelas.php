<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        wk.id,
        ta.nama AS tahun_ajaran,
        k.nama AS nama_kelas,
        g.nama AS nama_guru,
        wk.tanggal_mulai,
        wk.tanggal_selesai,
        wk.status_aktif
    FROM t_wali_kelas wk
    LEFT JOIN t_tahun_ajaran ta
        ON wk.tahun_ajaran_id = ta.id
    LEFT JOIN t_kelas k
        ON wk.kelas_id = k.id
    LEFT JOIN t_guru g
        ON wk.guru_id = g.id
    ORDER BY wk.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data wali kelas: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2>Kelola Wali Kelas</h2>
    <?php if (!empty($_SESSION['pesan'])) { echo '<div class="alert alert-info">'.htmlspecialchars($_SESSION['pesan'], ENT_QUOTES, 'UTF-8').'</div>'; unset($_SESSION['pesan']); } ?>
    <p>Halaman pengelolaan data wali kelas sekolah.</p>

    <a href="dashboard.php?embed=1" onclick="if(window.parent!==window){try{var f=window.parent.document.getElementById('contentFrame');if(f){window.parent.document.querySelectorAll('.sidebar .nav-link').forEach(function(a){a.classList.remove('active')});var d=window.parent.document.querySelector('.sidebar .nav-link[href*=\'dashboard\']');if(d)d.classList.add('active');f.src='dashboard.php?embed=1';return false;}}catch(e){}}"
>Kembali ke Dashboard</a>
    |
    <a href="tambah_wali_kelas.php">Tambah Wali Kelas</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun Ajaran</th>
                <th>Kelas</th>
                <th>Nama Guru</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
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

                    <td>
                        <?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_guru'] ?? '-') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tanggal_mulai']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tanggal_selesai']) ?>
                    </td>

                    <td>
                        <?= $row['status_aktif'] == 1
                            ? 'Aktif'
                            : 'Tidak Aktif' ?>
                    </td>

                    <td>
                        <a class="btn btn-sm btn-primary" href="edit_wali_kelas.php?id=<?= (int) $row['id'] ?>">
                            Edit
                        </a>
                        |
                        <a
                            href="hapus_wali_kelas.php?id=<?= (int) $row['id'] ?>"
                            onclick="return confirm('Yakin ingin menghapus data wali kelas ini?')"
                        >
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="8">Belum ada data wali kelas.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>