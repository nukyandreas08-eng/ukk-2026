<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/tahun_ajaran.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$kembali = 'kelola_wali_kelas.php' . ($embed ? '?embed=1' : '');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $guru_id = (int) ($_POST['guru_id'] ?? 0);
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    $cekTahunAktif = mysqli_prepare(
        $koneksi,
        'SELECT id FROM t_tahun_ajaran WHERE id = ? AND status_aktif = 1'
    );

    mysqli_stmt_bind_param($cekTahunAktif, 'i', $tahun_ajaran_id);
    mysqli_stmt_execute($cekTahunAktif);

    $tahunAktifValid = mysqli_num_rows(mysqli_stmt_get_result($cekTahunAktif)) > 0;

    mysqli_stmt_close($cekTahunAktif);

    if (!$tahunAktifValid) {
        $error = 'Pilih tahun ajaran yang berstatus aktif.';
    }

    if (
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $guru_id <= 0 ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';
    } elseif ($error !== '') {
        // Tahun ajaran yang dipilih bukan tahun aktif.
    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';
    } else {

        // Pastikan kelas belum memiliki penugasan pada tahun ajaran yang sama.
        $cek = mysqli_prepare(
            $koneksi,
            'SELECT id FROM t_wali_kelas
             WHERE tahun_ajaran_id = ? AND kelas_id = ?'
        );

        mysqli_stmt_bind_param($cek, 'ii', $tahun_ajaran_id, $kelas_id);
        mysqli_stmt_execute($cek);

        $hasil_cek = mysqli_stmt_get_result($cek);

        $kelasSudahAda = mysqli_num_rows($hasil_cek) > 0;

        mysqli_stmt_close($cek);

        if ($kelasSudahAda) {
            $error = 'Kelas tersebut sudah memiliki data wali kelas pada tahun ajaran yang dipilih.';
        } else {

            // Satu guru hanya boleh menjadi wali kelas untuk satu kelas.
            $cekGuru = mysqli_prepare(
                $koneksi,
                'SELECT id FROM t_wali_kelas WHERE guru_id = ?'
            );

            mysqli_stmt_bind_param($cekGuru, 'i', $guru_id);
            mysqli_stmt_execute($cekGuru);

            $guruSudahWali = mysqli_num_rows(mysqli_stmt_get_result($cekGuru)) > 0;

            mysqli_stmt_close($cekGuru);

            if ($guruSudahWali) {
                $error = 'Guru tersebut sudah menjadi wali kelas.';
            }
        }

        if ($error === '') {

            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_wali_kelas
                 (tahun_ajaran_id, kelas_id, guru_id, tanggal_mulai, tanggal_selesai, status_aktif)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                'iiissi',
                $tahun_ajaran_id,
                $kelas_id,
                $guru_id,
                $tanggal_mulai,
                $tanggal_selesai,
                $status_aktif
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                $_SESSION['pesan'] = 'Data wali kelas berhasil ditambahkan.';

                header('Location: ' . $kembali);
                exit;
            }

            $error = 'Gagal menyimpan data wali kelas: ' . mysqli_error($koneksi);

            mysqli_stmt_close($stmt);
        }
    }
}

// Ambil data pilihan dari database.
// Tahun ajaran yang sedang aktif otomatis terpilih.
$daftarTahun = tahun_ajaran_aktif_daftar($koneksi);

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT k.id, k.nama, k.tingkat, k.jurusan
     FROM t_kelas k
     INNER JOIN t_tahun_ajaran ta ON ta.id = k.tahun_ajaran_id AND ta.status_aktif = 1
     WHERE NOT EXISTS (
         SELECT 1 FROM t_wali_kelas wk
         WHERE wk.kelas_id = k.id AND wk.tahun_ajaran_id = ta.id
     )
     ORDER BY k.tingkat, k.jurusan, k.nama"
);

$query_guru = mysqli_query(
    $koneksi,
    'SELECT g.id, g.nama FROM t_guru g
     WHERE NOT EXISTS (SELECT 1 FROM t_wali_kelas wk WHERE wk.guru_id = g.id)
     ORDER BY g.nama ASC'
);

if (!$query_kelas || !$query_guru) {
    die('Gagal mengambil data pilihan. Periksa struktur tabel tahun ajaran, kelas, dan guru.');
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Wali Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Tambah Wali Kelas</h2>
    <hr>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form method="POST" action="tambah_wali_kelas.php" class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-md-4">
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

        <div class="col-md-4">
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

        <div class="col-md-4">
            <label class="form-label">Guru Wali Kelas</label>
            <select name="guru_id" class="form-select" required>
                <option value="">-- Pilih Guru --</option>
                <?php while ($guru = mysqli_fetch_assoc($query_guru)) { ?>
                    <option value="<?= (int) $guru['id'] ?>">
                        <?= htmlspecialchars($guru['nama']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label">Status</label>
            <select name="status_aktif" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= htmlspecialchars($kembali) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>

</div>

<script src="assets/siswa-search.js"></script>
<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
