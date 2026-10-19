<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
$embed = isset($_GET['embed']) && $_GET['embed'] === '1';
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { $_SESSION['pesan'] = 'ID guru tidak valid.'; header('Location: kelola_guru.php?embed=1'); exit; }
$stmt = mysqli_prepare($koneksi, 'SELECT * FROM t_guru WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt);
$guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$guru) { $_SESSION['pesan'] = 'Data guru tidak ditemukan.'; header('Location: kelola_guru.php?embed=1'); exit; }
$users = mysqli_query($koneksi, "SELECT id, name, email FROM t_users WHERE role = 'guru' ORDER BY name ASC");
if (!$users) { die('Gagal mengambil akun guru: ' . mysqli_error($koneksi)); }
?>
<?php if (!$embed): ?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Data Guru</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<?php endif; ?>
<div class="container-fluid p-3">
  <h1 class="mb-3">Edit Data Guru</h1><hr>
  <?php if (isset($_SESSION['pesan'])): ?><div class="alert alert-info"><?= htmlspecialchars($_SESSION['pesan']) ?></div><?php unset($_SESSION['pesan']); endif; ?>
  <form action="proses_edit_guru.php" method="POST" class="row g-3">
    <input type="hidden" name="id" value="<?= (int)$guru['id'] ?>">
    <input type="hidden" name="embed" value="1">
    <div class="col-12 col-md-6"><label class="form-label">NIP</label><input class="form-control" name="nip" value="<?= htmlspecialchars($guru['nip'] ?? '') ?>" required></div>
    <div class="col-12 col-md-6"><label class="form-label">Nama Guru</label><input class="form-control" name="nama" value="<?= htmlspecialchars($guru['nama'] ?? '') ?>" required></div>
    <div class="col-12 col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= htmlspecialchars($guru['email'] ?? '') ?>" required></div>
    <div class="col-12 col-md-6"><label class="form-label">Akun Guru</label><select name="user_id" class="form-select" required><option value="">-- Pilih akun guru --</option><?php while ($u = mysqli_fetch_assoc($users)): ?><option value="<?= (int)$u['id'] ?>" <?= ((int)$guru['user_id'] === (int)$u['id']) ? 'selected' : '' ?>><?= htmlspecialchars($u['name'] . ' - ' . ($u['email'] ?? '')) ?></option><?php endwhile; ?></select></div>
    <div class="col-12 col-md-6"><label class="form-label">Status Aktif</label><select name="status_aktif" class="form-select" required><option value="1" <?= ((int)$guru['status_aktif'] === 1) ? 'selected' : '' ?>>Aktif</option><option value="0" <?= ((int)$guru['status_aktif'] === 0) ? 'selected' : '' ?>>Tidak Aktif</option></select></div>
    <div class="col-12"><button type="submit" class="btn btn-primary">Simpan Perubahan</button> <a href="kelola_guru.php?embed=1" class="btn btn-secondary">Batal</a></div>
  </form>
</div>
<?php if (!$embed): ?></body></html><?php endif; ?>
