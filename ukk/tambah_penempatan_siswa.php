<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/tahun_ajaran.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$kembali = 'kelola_penempatan_siswa.php' . ($embed ? '?embed=1' : '');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    $cekAktif = mysqli_prepare(
        $koneksi,
        'SELECT id FROM t_tahun_ajaran WHERE id = ? AND status_aktif = 1'
    );

    mysqli_stmt_bind_param($cekAktif, 'i', $tahun_ajaran_id);
    mysqli_stmt_execute($cekAktif);

    $tahunAktifValid = mysqli_num_rows(mysqli_stmt_get_result($cekAktif)) > 0;

    mysqli_stmt_close($cekAktif);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0 || !$tahunAktifValid) {
        $error = 'Silakan lengkapi semua pilihan.';
    } else {

        // Pastikan kelas memang terdaftar pada tahun ajaran yang dipilih.
        $cekKelas = mysqli_prepare(
            $koneksi,
            'SELECT id FROM t_kelas WHERE id = ? AND tahun_ajaran_id = ?'
        );

        mysqli_stmt_bind_param($cekKelas, 'ii', $kelas_id, $tahun_ajaran_id);
        mysqli_stmt_execute($cekKelas);

        $kelasSesuaiTahun = mysqli_num_rows(mysqli_stmt_get_result($cekKelas)) > 0;

        mysqli_stmt_close($cekKelas);

        if (!$kelasSesuaiTahun) {
            $error = 'Kelas yang dipilih tidak terdaftar pada tahun ajaran tersebut. '
                . 'Periksa data di menu Kelola Kelas.';
        } else {

            $cek = mysqli_prepare(
                $koneksi,
                'SELECT id FROM t_kelas_siswa
                 WHERE siswa_id = ? AND tahun_ajaran_id = ?'
            );

            mysqli_stmt_bind_param($cek, 'ii', $siswa_id, $tahun_ajaran_id);
            mysqli_stmt_execute($cek);

            $hasil = mysqli_stmt_get_result($cek);

            $sudahDitempatkan = mysqli_num_rows($hasil) > 0;

            mysqli_stmt_close($cek);

            if ($sudahDitempatkan) {
                $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';
            } else {

                $stmt = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_kelas_siswa
                     (siswa_id, kelas_id, tahun_ajaran_id, tanggal_mulai, tanggal_selesai, status_aktif)
                     VALUES (?, ?, ?, CURDATE(), '2099-12-31', 1)"
                );

                mysqli_stmt_bind_param($stmt, 'iii', $siswa_id, $kelas_id, $tahun_ajaran_id);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);

                    $_SESSION['pesan'] = 'Penempatan siswa berhasil disimpan.';

                    header('Location: ' . $kembali);
                    exit;
                }

                $error = 'Gagal menyimpan penempatan siswa: ' . mysqli_error($koneksi);

                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Tahun ajaran yang sedang aktif otomatis terpilih.
$idTahunAktif = tahun_ajaran_aktif_id($koneksi);

$daftarTahun = tahun_ajaran_aktif_daftar($koneksi);

// Siswa yang belum ditempatkan pada tahun ajaran aktif.
$stmt_siswa = mysqli_prepare(
    $koneksi,
    "SELECT s.id, s.nis, s.nama FROM t_siswa s
     WHERE NOT EXISTS (
        SELECT 1 FROM t_kelas_siswa ks
        WHERE ks.siswa_id = s.id
          AND ks.tahun_ajaran_id = ?
     )
     ORDER BY s.nama ASC"
);

mysqli_stmt_bind_param($stmt_siswa, 'i', $idTahunAktif);
mysqli_stmt_execute($stmt_siswa);

$query_siswa = mysqli_stmt_get_result($stmt_siswa);

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT k.id, k.nama, k.tingkat, k.jurusan
     FROM t_kelas k
     INNER JOIN t_tahun_ajaran ta ON ta.id = k.tahun_ajaran_id AND ta.status_aktif = 1
     ORDER BY k.tingkat, k.jurusan, k.nama"
);

if (!$query_siswa || !$query_kelas) {
    die('Gagal mengambil pilihan data. Periksa struktur tabel database.');
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Penempatan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Tambah Penempatan Siswa</h2>
    <hr>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form method="POST" action="tambah_penempatan_siswa.php" class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-12">
            <label class="form-label">Siswa</label>
            <select name="siswa_id" class="form-select" required>
                <option value="">-- Pilih Siswa --</option>
                <?php while ($siswa = mysqli_fetch_assoc($query_siswa)) { ?>
                    <option value="<?= (int) $siswa['id'] ?>">
                        <?= htmlspecialchars($siswa['nis'] . ' - ' . $siswa['nama']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" class="form-select" required>
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
        </div>

        <div class="col-12">
            <label class="form-label">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="form-select" required>
                <?php if (!$daftarTahun): ?>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                <?php endif; ?>

                <?php foreach ($daftarTahun as $ta): ?>
                    <option value="<?= (int) $ta['id'] ?>"
                        <?= $ta['terpilih'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ta['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (count($daftarTahun) === 1): ?>
                <div class="form-text">
                    Tahun ajaran aktif saat ini otomatis dipilih.
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Simpan Penempatan</button>
            <a href="<?= htmlspecialchars($kembali) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>

</div>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
