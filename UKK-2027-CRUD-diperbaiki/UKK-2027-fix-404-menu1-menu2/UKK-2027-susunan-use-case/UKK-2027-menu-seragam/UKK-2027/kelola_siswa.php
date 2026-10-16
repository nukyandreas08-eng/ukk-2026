<?php
require_once __DIR__.'/includes/cek_session.php';
require_once __DIR__.'/config/koneksi.php';
$embed = (($_GET['embed'] ?? '') === '1');
$query = mysqli_query($koneksi, "SELECT id, nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif FROM t_siswa ORDER BY id DESC");
if (!$query) { http_response_code(500); die('Gagal mengambil data siswa. Periksa struktur tabel t_siswa.'); }
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<?php if (!$embed): ?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Kelola Siswa</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><?php endif; ?>
<div class="container-fluid p-3 p-md-4 bg-white">
<h2 class="fw-bold mb-3">Kelola Siswa</h2><hr>
<?php if (!empty($_SESSION['pesan'])): ?><div class="alert alert-info alert-dismissible fade show" role="alert"><?=e($_SESSION['pesan'])?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div><?php unset($_SESSION['pesan']); endif; ?>
<h5 class="fw-bold mb-3">Tambah Data Siswa</h5>
<form action="proses_siswa.php" method="post" class="mb-4"><input type="hidden" name="aksi" value="tambah"><div class="row g-3">
<div class="col-12 col-md-6"><label for="nis" class="form-label">NIS</label><input id="nis" name="nis" class="form-control" maxlength="30" required></div>
<div class="col-12 col-md-6"><label for="nisn" class="form-label">NISN</label><input id="nisn" name="nisn" class="form-control" maxlength="30" required></div>
<div class="col-12 col-md-6"><label for="nama" class="form-label">Nama Siswa</label><input id="nama" name="nama" class="form-control" maxlength="150" required></div>
<div class="col-12 col-md-6"><label for="jenis_kelamin" class="form-label">Jenis Kelamin</label><select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required><option value="">-- Pilih Jenis Kelamin --</option><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
<div class="col-12 col-md-6"><label for="tanggal_lahir" class="form-label">Tanggal Lahir</label><input id="tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" required></div>
<div class="col-12 col-md-6"><label for="status_aktif" class="form-label">Status Aktif</label><select id="status_aktif" name="status_aktif" class="form-select" required><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select></div>
<div class="col-12"><label for="alamat" class="form-label">Alamat</label><textarea id="alamat" name="alamat" class="form-control" rows="2" maxlength="1000" required></textarea></div>
<div class="col-12"><button class="btn btn-primary" type="submit">+ Tambah Siswa</button> <a class="btn btn-secondary" href="dashboard.php?embed=1">Kembali ke Dashboard</a></div>
</div></form><hr><h5 class="fw-bold mb-4">Data Siswa</h5>
<div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead class="table-light"><tr><th>ID</th><th>NIS</th><th>NISN</th><th>Nama</th><th>Jenis Kelamin</th><th>Tanggal Lahir</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php if (mysqli_num_rows($query)): while($s=mysqli_fetch_assoc($query)): ?><tr><td><?= (int)$s['id'] ?></td><td><?=e($s['nis'])?></td><td><?=e($s['nisn'])?></td><td><?=e($s['nama'])?></td><td><?= $s['jenis_kelamin']==='L'?'Laki-laki':($s['jenis_kelamin']==='P'?'Perempuan':'-') ?></td><td><?=e($s['tanggal_lahir'])?></td><td><?=e($s['alamat'])?></td><td><span class="badge text-bg-<?=((int)$s['status_aktif']===1?'success':'secondary')?>"><?=((int)$s['status_aktif']===1?'Aktif':'Tidak Aktif')?></span></td><td><div class="d-flex gap-2"><a class="btn btn-primary btn-sm" href="edit_siswa.php?id=<?=(int)$s['id']?>&amp;embed=1">Edit</a><a class="btn btn-danger btn-sm" href="hapus_siswa.php?id=<?=(int)$s['id']?>&amp;embed=1" onclick="return confirm('Yakin ingin menghapus data siswa ini?')">Hapus</a></div></td></tr><?php endwhile; else: ?><tr><td colspan="9" class="text-center text-muted py-4">Belum ada data siswa.</td></tr><?php endif; ?>
</tbody></table></div></div>
<?php if (!$embed): ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php endif; ?>
