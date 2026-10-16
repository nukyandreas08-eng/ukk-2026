<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: kelola_kelas.php?pesan=id_tidak_valid');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT id, nama, tingkat, jurusan, status_aktif FROM t_kelas WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kelas = mysqli_fetch_assoc($result);
if (!$kelas) {
    header('Location: kelola_kelas.php?pesan=data_tidak_ditemukan');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $status = isset($_POST['status_aktif']) ? (int) $_POST['status_aktif'] : -1;

    if ($nama === '' || !in_array($tingkat, ['X', 'XI', 'XII'], true) || $jurusan === '' || !in_array($status, [0, 1], true)) {
        $error = 'Mohon lengkapi semua data dengan benar.';
        $kelas = array_merge($kelas, ['nama' => $nama, 'tingkat' => $tingkat, 'jurusan' => $jurusan, 'status_aktif' => $status]);
    } else {
        $update = mysqli_prepare($koneksi, 'UPDATE t_kelas SET nama = ?, tingkat = ?, jurusan = ?, status_aktif = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        mysqli_stmt_bind_param($update, 'sssii', $nama, $tingkat, $jurusan, $status, $id);
        if (mysqli_stmt_execute($update)) {
            header('Location: kelola_kelas.php?pesan=edit_berhasil');
            exit;
        }
        $error = 'Gagal mengubah data kelas: ' . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Kelas</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4"><div class="card shadow-sm"><div class="card-header"><h4 class="mb-0">Edit Kelas</h4></div><div class="card-body">
<?php if ($error !== ''): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label" for="nama">Nama Kelas</label><input class="form-control" id="nama" name="nama" value="<?= htmlspecialchars((string)$kelas['nama']) ?>" required></div>
<div class="mb-3"><label class="form-label" for="tingkat">Tingkat</label><select class="form-select" id="tingkat" name="tingkat" required><option value="">Pilih Tingkat</option><?php foreach (['X','XI','XII'] as $t): ?><option value="<?= $t ?>" <?= $kelas['tingkat'] === $t ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?></select></div>
<div class="mb-3"><label class="form-label" for="jurusan">Jurusan</label><input class="form-control" id="jurusan" name="jurusan" value="<?= htmlspecialchars((string)$kelas['jurusan']) ?>" required></div>
<div class="mb-3"><label class="form-label" for="status_aktif">Status</label><select class="form-select" id="status_aktif" name="status_aktif" required><option value="1" <?= (int)$kelas['status_aktif'] === 1 ? 'selected' : '' ?>>Aktif</option><option value="0" <?= (int)$kelas['status_aktif'] === 0 ? 'selected' : '' ?>>Tidak Aktif</option></select></div>
<button class="btn btn-primary" type="submit">Simpan Perubahan</button> <a class="btn btn-secondary" href="kelola_kelas.php">Batal</a>
</form></div></div></div></body></html>
