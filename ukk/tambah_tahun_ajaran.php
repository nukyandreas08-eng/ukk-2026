<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$kembali = 'kelola_tahun_ajaran.php' . ($embed ? '?embed=1' : '');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    if (
        $nama === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';
    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';
    } elseif (!in_array($status_aktif, [0, 1], true)) {
        $error = 'Status tahun ajaran tidak valid.';
    } elseif (
        $status_aktif === 1 &&
        (int) (mysqli_fetch_assoc(mysqli_query(
            $koneksi,
            'SELECT COUNT(*) AS total FROM t_tahun_ajaran WHERE status_aktif = 1'
        ))['total'] ?? 0) > 0
    ) {
        $error = 'Tidak dapat mengaktifkan tahun ajaran baru karena masih ada '
            . 'tahun ajaran lain yang aktif. Nonaktifkan terlebih dahulu.';
    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            'INSERT INTO t_tahun_ajaran
             (nama, tanggal_mulai, tanggal_selesai, status_aktif)
             VALUES (?, ?, ?, ?)'
        );

        mysqli_stmt_bind_param(
            $stmt,
            'sssi',
            $nama,
            $tanggal_mulai,
            $tanggal_selesai,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            $_SESSION['pesan'] = 'Data tahun ajaran berhasil ditambahkan.';

            header('Location: ' . $kembali);
            exit;
        }

        $error = 'Gagal menyimpan data tahun ajaran.';

        mysqli_stmt_close($stmt);
    }
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Tahun Ajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Tambah Tahun Ajaran</h2>
    <hr>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form method="POST" action="tambah_tahun_ajaran.php" class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-12">
            <label class="form-label">Nama Tahun Ajaran</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   maxlength="100"
                   placeholder="Contoh: 2026/2027"
                   required>
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

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
