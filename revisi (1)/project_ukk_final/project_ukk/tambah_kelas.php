<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Mengambil daftar tahun ajaran dari menu Tahun Ajaran.
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $status_aktif = filter_var(
        $_POST['status_aktif'] ?? null,
        FILTER_VALIDATE_INT
    );

    // Memastikan seluruh pilihan valid.
    if (
        $nama === '' ||
        !in_array($tingkat, ['X', 'XI', 'XII'], true) ||
        $jurusan === '' ||
        $tahun_ajaran_id < 1 ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        $error = 'Semua data wajib diisi dengan benar.';
    } else {
        // Memastikan tahun ajaran memang tersedia di database.
        $cek_tahun = mysqli_prepare(
            $koneksi,
            'SELECT id FROM t_tahun_ajaran WHERE id = ?'
        );
        mysqli_stmt_bind_param($cek_tahun, 'i', $tahun_ajaran_id);
        mysqli_stmt_execute($cek_tahun);
        $tahun_ada = mysqli_num_rows(mysqli_stmt_get_result($cek_tahun)) > 0;
        mysqli_stmt_close($cek_tahun);

        if (!$tahun_ada) {
            $error = 'Tahun ajaran yang dipilih tidak ditemukan.';
        } else {
            // Menyimpan kelas beserta relasi tahun ajarannya.
            $stmt = mysqli_prepare(
                $koneksi,
                'INSERT INTO t_kelas
                    (nama, tingkat, jurusan, tahun_ajaran_id, status_aktif, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, NOW(), NOW())'
            );

            mysqli_stmt_bind_param(
                $stmt,
                'sssii',
                $nama,
                $tingkat,
                $jurusan,
                $tahun_ajaran_id,
                $status_aktif
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: kelola_kelas.php?pesan=tambah_berhasil' . ($embed ? '&embed=1' : ''));
                exit;
            }

            $error = 'Gagal menyimpan data kelas: ' . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

if (!$query_tahun) {
    die('Gagal mengambil data tahun ajaran: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2>Tambah Kelas</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">
        <table cellpadding="8">
            <tr>
                <td>Nama Kelas</td>
                <td>:</td>
                <td><input type="text" name="nama" maxlength="100" required></td>
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
                <td>Tahun Ajaran</td>
                <td>:</td>
                <td>
                    <select name="tahun_ajaran_id" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        <?php while ($tahun = mysqli_fetch_assoc($query_tahun)): ?>
                            <option value="<?= (int) $tahun['id'] ?>">
                                <?= htmlspecialchars($tahun['nama'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
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
