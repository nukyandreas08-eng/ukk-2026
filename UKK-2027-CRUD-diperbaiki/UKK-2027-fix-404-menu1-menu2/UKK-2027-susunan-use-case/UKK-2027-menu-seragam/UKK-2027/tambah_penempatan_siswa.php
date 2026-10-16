<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {
        $error = 'Silakan lengkapi semua pilihan.';
    } else {
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_kelas_siswa
             WHERE siswa_id = ? AND tahun_ajaran_id = ?"
        );

        mysqli_stmt_bind_param(
            $cek,
            "ii",
            $siswa_id,
            $tahun_ajaran_id
        );

        mysqli_stmt_execute($cek);
        $hasil = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil) > 0) {
            $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';
        } else {
            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_kelas_siswa
                 (siswa_id, kelas_id, tahun_ajaran_id)
                 VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iii",
                $siswa_id,
                $kelas_id,
                $tahun_ajaran_id
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: kelola_penempatan_siswa.php');
                exit;
            }

            $error = 'Gagal menyimpan penempatan siswa: '
                . mysqli_error($koneksi);
        }
    }
}

$query_siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC"
);

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas ORDER BY tingkat, jurusan, nama"
);

$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

if (!$query_siswa || !$query_kelas || !$query_tahun) {
    die('Gagal mengambil pilihan data. Periksa struktur tabel database.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penempatan Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Tambah Penempatan Siswa</h2>

<?php if ($error !== '') { ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php } ?>

<form method="POST">
    <table cellpadding="8">
        <tr>
            <td>Siswa</td>
            <td>:</td>
            <td>
                <select name="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php while ($siswa = mysqli_fetch_assoc($query_siswa)) { ?>
                        <option value="<?= (int) $siswa['id'] ?>">
                            <?= htmlspecialchars($siswa['nis'] . ' - ' . $siswa['nama']) ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>
                <select name="kelas_id" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>
                        <option value="<?= (int) $kelas['id'] ?>">
                            <?= htmlspecialchars(
                                $kelas['nama'] . ' - ' .
                                $kelas['tingkat'] . ' - ' .
                                $kelas['jurusan']
                            ) ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>
                <select name="tahun_ajaran_id" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>
                        <option value="<?= (int) $ta['id'] ?>">
                            <?= htmlspecialchars($ta['nama']) ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <button type="submit">Simpan Penempatan</button>
                <a href="kelola_penempatan_siswa.php">Batal</a>
            </td>
        </tr>
    </table>
</form>

</body>
</html>