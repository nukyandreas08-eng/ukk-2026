<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $guru_id = (int) ($_POST['guru_id'] ?? 0);
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    if (
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $guru_id <= 0 ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';
    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';
    } else {
        // Pastikan kelas belum memiliki penugasan pada tahun ajaran yang sama.
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_wali_kelas
             WHERE tahun_ajaran_id = ? AND kelas_id = ?"
        );
        mysqli_stmt_bind_param(
            $cek,
            "ii",
            $tahun_ajaran_id,
            $kelas_id
        );
        mysqli_stmt_execute($cek);
        $hasil_cek = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil_cek) > 0) {
            $error = 'Kelas tersebut sudah memiliki data wali kelas pada tahun ajaran yang dipilih.';
        } else {
            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_wali_kelas
                (
                    tahun_ajaran_id,
                    kelas_id,
                    guru_id,
                    tanggal_mulai,
                    tanggal_selesai,
                    status_aktif
                )
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iiissi",
                $tahun_ajaran_id,
                $kelas_id,
                $guru_id,
                $tanggal_mulai,
                $tanggal_selesai,
                $status_aktif
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: kelola_wali_kelas.php');
                exit;
            } else {
                $error = 'Gagal menyimpan data wali kelas: '
                    . mysqli_error($koneksi);
            }
        }
    }
}

// Ambil data pilihan dari database.
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat, jurusan, nama"
);

$query_guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);

if (!$query_tahun || !$query_kelas || !$query_guru) {
    die('Gagal mengambil data pilihan. Periksa struktur tabel tahun ajaran, kelas, dan guru.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2>Tambah Wali Kelas</h2>

    <?php if ($error !== '') { ?>
        <p style="color: red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php } ?>

    <form method="POST" action="">
        <table cellpadding="8">
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
                <td>Guru Wali Kelas</td>
                <td>:</td>
                <td>
                    <select name="guru_id" required>
                        <option value="">-- Pilih Guru --</option>

                        <?php while ($guru = mysqli_fetch_assoc($query_guru)) { ?>
                            <option value="<?= (int) $guru['id'] ?>">
                                <?= htmlspecialchars($guru['nama']) ?>
                            </option>
                        <?php } ?>
                    </select>
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
                    <a href="kelola_wali_kelas.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>

</body>
</html>