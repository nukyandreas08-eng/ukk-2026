<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$kembali = 'kelola_tahun_ajaran.php' . ($embed ? '?embed=1' : '');

if (!$id || $id < 1) {
    $_SESSION['pesan'] = 'ID tahun ajaran tidak valid.';
    header('Location: ' . $kembali);
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    'SELECT id, nama, tanggal_mulai, tanggal_selesai, status_aktif
     FROM t_tahun_ajaran WHERE id = ?'
);

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

mysqli_stmt_close($stmt);

if (!$data) {
    $_SESSION['pesan'] = 'Data tahun ajaran tidak ditemukan.';
    header('Location: ' . $kembali);
    exit;
}

function e($v)
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $mulai = $_POST['tanggal_mulai'] ?? '';
    $selesai = $_POST['tanggal_selesai'] ?? '';
    $status = filter_var($_POST['status_aktif'] ?? null, FILTER_VALIDATE_INT);

    $embedParam = $embed ? '&embed=1' : '';
    $kembaliEdit = 'edit_tahun_ajaran.php?id=' . $id . $embedParam;

    if (
        $nama === '' ||
        $mulai === '' ||
        $selesai === '' ||
        $mulai > $selesai ||
        !in_array($status, [0, 1], true)
    ) {
        $_SESSION['pesan'] = 'Data belum lengkap atau tanggal tidak valid.';
        header('Location: ' . $kembaliEdit);
        exit;
    }

    if ($status === 1) {
        $cekAktif = mysqli_prepare(
            $koneksi,
            'SELECT COUNT(*) AS total FROM t_tahun_ajaran
             WHERE status_aktif = 1 AND id <> ?'
        );

        mysqli_stmt_bind_param($cekAktif, 'i', $id);
        mysqli_stmt_execute($cekAktif);

        $jumlahAktif = (int) (mysqli_fetch_assoc(
            mysqli_stmt_get_result($cekAktif)
        )['total'] ?? 0);

        mysqli_stmt_close($cekAktif);

        if ($jumlahAktif > 0) {
            $_SESSION['pesan'] = 'Tidak dapat mengaktifkan tahun ajaran ini karena '
                . 'tahun ajaran lain masih aktif. Nonaktifkan terlebih dahulu.';
            header('Location: ' . $kembaliEdit);
            exit;
        }
    }

    $q = mysqli_prepare(
        $koneksi,
        'UPDATE t_tahun_ajaran
         SET nama = ?, tanggal_mulai = ?, tanggal_selesai = ?, status_aktif = ?
         WHERE id = ?'
    );

    mysqli_stmt_bind_param($q, 'sssii', $nama, $mulai, $selesai, $status, $id);

    if (mysqli_stmt_execute($q)) {
        $_SESSION['pesan'] = 'Data tahun ajaran berhasil diperbarui.';
        header('Location: ' . $kembali);
        exit;
    }

    $error = 'Gagal memperbarui data: ' . mysqli_stmt_error($q);

    mysqli_stmt_close($q);
}
?>

<?php if (!$embed): ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Tahun Ajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php endif; ?>

<main class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Edit Tahun Ajaran</h2>
    <hr>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="edit_tahun_ajaran.php?id=<?= (int) $id ?>" class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-12">
            <label class="form-label">Nama Tahun Ajaran</label>
            <input name="nama" class="form-control" maxlength="100" value="<?= e($data['nama']) ?>" required>
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
