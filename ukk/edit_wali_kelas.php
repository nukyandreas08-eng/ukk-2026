<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/tahun_ajaran.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$kembali = 'kelola_wali_kelas.php' . ($embed ? '?embed=1' : '');

if (!$id || $id < 1) {
    $_SESSION['pesan'] = 'ID wali kelas tidak valid.';
    header('Location: ' . $kembali);
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT * FROM t_wali_kelas WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

mysqli_stmt_close($stmt);

if (!$data) {
    $_SESSION['pesan'] = 'Data wali kelas tidak ditemukan.';
    header('Location: ' . $kembali);
    exit;
}

function e($v)
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ta = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas = (int) ($_POST['kelas_id'] ?? 0);
    $guru = (int) ($_POST['guru_id'] ?? 0);
    $mulai = $_POST['tanggal_mulai'] ?? '';
    $selesai = $_POST['tanggal_selesai'] ?? '';
    $status = filter_var($_POST['status_aktif'] ?? null, FILTER_VALIDATE_INT);

    $embedParam = $embed ? '&embed=1' : '';
    $kembaliEdit = 'edit_wali_kelas.php?id=' . $id . $embedParam;

    if (
        !$ta ||
        !$kelas ||
        !$guru ||
        !$mulai ||
        !$selesai ||
        $mulai > $selesai ||
        !in_array($status, [0, 1], true)
    ) {
        $error = 'Semua kolom wajib diisi dan tanggal harus valid.';
    } else {

        $cek = mysqli_prepare(
            $koneksi,
            'SELECT id FROM t_wali_kelas
             WHERE tahun_ajaran_id = ? AND kelas_id = ? AND id <> ?'
        );

        mysqli_stmt_bind_param($cek, 'iii', $ta, $kelas, $id);
        mysqli_stmt_execute($cek);

        $kelasSudahAda = mysqli_num_rows(mysqli_stmt_get_result($cek)) > 0;

        mysqli_stmt_close($cek);

        if ($kelasSudahAda) {
            $error = 'Kelas tersebut sudah memiliki wali kelas pada tahun ajaran ini.';
        } else {

            $q = mysqli_prepare(
                $koneksi,
                'UPDATE t_wali_kelas
                 SET tahun_ajaran_id = ?, kelas_id = ?, guru_id = ?,
                     tanggal_mulai = ?, tanggal_selesai = ?, status_aktif = ?
                 WHERE id = ?'
            );

            mysqli_stmt_bind_param(
                $q,
                'iiissii',
                $ta,
                $kelas,
                $guru,
                $mulai,
                $selesai,
                $status,
                $id
            );

            if (mysqli_stmt_execute($q)) {
                $_SESSION['pesan'] = 'Data wali kelas berhasil diperbarui.';
                header('Location: ' . $kembali);
                exit;
            }

            $error = 'Gagal memperbarui data wali kelas: ' . mysqli_stmt_error($q);

            mysqli_stmt_close($q);
        }
    }
}

// Daftar tahun ajaran: tahun aktif ditampilkan lebih dulu,
// dan nilai yang tersimpan tetap terpilih.
$tahun = mysqli_query(
    $koneksi,
    'SELECT id, nama FROM t_tahun_ajaran
     WHERE status_aktif = 1 OR id = ' . (int) $data['tahun_ajaran_id'] . '
     ORDER BY status_aktif DESC, id DESC'
);

$kelasq = mysqli_query(
    $koneksi,
    'SELECT k.id, k.nama, k.tingkat, k.jurusan
     FROM t_kelas k
     INNER JOIN t_tahun_ajaran ta ON ta.id = k.tahun_ajaran_id
     WHERE (ta.status_aktif = 1 OR k.id = ' . (int) $data['kelas_id'] . ')
       AND NOT EXISTS (
            SELECT 1 FROM t_wali_kelas wk
            WHERE wk.kelas_id = k.id
              AND wk.tahun_ajaran_id = ta.id
              AND wk.id <> ' . (int) $id . '
       )
     ORDER BY k.tingkat, k.jurusan, k.nama'
);

$guruq = mysqli_query(
    $koneksi,
    'SELECT g.id, g.nama FROM t_guru g
     WHERE NOT EXISTS (
        SELECT 1 FROM t_wali_kelas wk WHERE wk.guru_id = g.id AND wk.id <> ' . (int) $id . '
     )
     ORDER BY g.nama'
);
?>

<?php if (!$embed): ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Wali Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php endif; ?>

<main class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Edit Wali Kelas</h2>
    <hr>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post"
          action="edit_wali_kelas.php?id=<?= (int) $id ?>"
          class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-md-4">
            <label class="form-label">Tahun Ajaran</label>
            <select class="form-select" name="tahun_ajaran_id" required>
                <?php while ($r = mysqli_fetch_assoc($tahun)): ?>
                    <option value="<?= (int) $r['id'] ?>"
                        <?= (int) $data['tahun_ajaran_id'] === (int) $r['id'] ? 'selected' : '' ?>>
                        <?= e($r['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Kelas</label>
            <select class="form-select" name="kelas_id" required>
                <?php while ($r = mysqli_fetch_assoc($kelasq)): ?>
                    <option value="<?= (int) $r['id'] ?>"
                        <?= (int) $data['kelas_id'] === (int) $r['id'] ? 'selected' : '' ?>>
                        <?= e($r['nama'] . ' - ' . $r['tingkat'] . ' - ' . $r['jurusan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Guru</label>
            <select class="form-select" name="guru_id" required>
                <?php while ($r = mysqli_fetch_assoc($guruq)): ?>
                    <option value="<?= (int) $r['id'] ?>"
                        <?= (int) $data['guru_id'] === (int) $r['id'] ? 'selected' : '' ?>>
                        <?= e($r['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="<?= e($data['tanggal_mulai']) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="<?= e($data['tanggal_selesai']) ?>" required>
        </div>

        <div class="col-12">
            <label class="form-label">Status</label>
            <select name="status_aktif" class="form-select">
                <option value="1" <?= (int) $data['status_aktif'] === 1 ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= (int) $data['status_aktif'] === 0 ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a class="btn btn-secondary" href="<?= e($kembali) ?>">Batal</a>
        </div>
    </form>

</main>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
