<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$error = '';
$nama = trim($_POST['nama'] ?? '');
$tingkat = $_POST['tingkat'] ?? '';
$jurusan = trim($_POST['jurusan'] ?? '');
$status = filter_var($_POST['status_aktif'] ?? '1', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($nama === '' ||
        !in_array($tingkat, ['X', 'XI', 'XII'], true) ||
        !in_array(strtoupper($jurusan), ['RPL', 'TKJ', 'TSM', 'TKR', 'BDPM'], true) ||
        !in_array($status, [0, 1], true)) {
        $error = 'Lengkapi semua data kelas dengan benar.';
    } else {
        // Tahun ajaran tidak lagi dipilih atau disimpan dari form kelas.
        $stmt = mysqli_prepare(
            $koneksi,
            'INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif, created_at, updated_at)
             VALUES (?, ?, ?, ?, NOW(), NOW())'
        );

        if (!$stmt) {
            $error = 'Gagal menyiapkan penyimpanan: ' . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, 'sssi', $nama, $tingkat, $jurusan, $status);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: kelola_kelas.php?pesan=tambah_berhasil' . ($embed ? '&embed=1' : ''));
                exit;
            }

            $error = 'Gagal menyimpan kelas: ' . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

function e_tambah_kelas($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tambah Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Tambah Kelas</h4>
        </div>
        <div class="card-body">
            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= e_tambah_kelas($error) ?></div>
            <?php endif; ?>

            <form method="post" class="row g-3">
                <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

                <div class="col-md-6">
                    <label class="form-label" for="nama">Nama Kelas</label>
                    <input class="form-control" id="nama" name="nama"
                           maxlength="100" value="<?= e_tambah_kelas($nama) ?>"
                           placeholder="Contoh: RPL 1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tingkat">Tingkat</label>
                    <select class="form-select" id="tingkat" name="tingkat" required>
                        <option value="">Pilih Tingkat</option>
                        <?php foreach (['X', 'XI', 'XII'] as $t): ?>
                            <option value="<?= $t ?>" <?= $tingkat === $t ? 'selected' : '' ?>>
                                <?= $t ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="jurusan">Jurusan</label>
                    <select class="form-select" id="jurusan" name="jurusan" required>
                        <option value="">Pilih Jurusan</option>
                        <?php foreach (['RPL', 'TKJ', 'TSM', 'TKR', 'BDPM'] as $j): ?>
                            <option value="<?= $j ?>" <?= strtoupper($jurusan) === $j ? 'selected' : '' ?>>
                                <?= $j ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status_aktif">Status</label>
                    <select class="form-select" id="status_aktif" name="status_aktif">
                        <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= $status === 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-secondary" href="kelola_kelas.php<?= $embed ? '?embed=1' : '' ?>">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>
