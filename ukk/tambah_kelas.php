<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$error = '';
$nama = trim($_POST['nama'] ?? '');
$tingkat = $_POST['tingkat'] ?? '';
$jurusan = trim($_POST['jurusan'] ?? '');
$tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
$status = filter_var($_POST['status_aktif'] ?? '1', FILTER_VALIDATE_INT);
$queryTahun = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC");
if (!$queryTahun) die('Gagal mengambil tahun ajaran: ' . htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
$jumlahTahun = mysqli_num_rows($queryTahun);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($nama === '' || !in_array($tingkat, ['X','XI','XII'], true) || $jurusan === '' || $tahun_ajaran_id < 1 || !in_array($status, [0,1], true)) {
        $error = 'Lengkapi semua data kelas dengan benar.';
    } else {
        $cek = mysqli_prepare($koneksi, 'SELECT id FROM t_tahun_ajaran WHERE id = ?');
        mysqli_stmt_bind_param($cek, 'i', $tahun_ajaran_id);
        mysqli_stmt_execute($cek);
        $validTahun = mysqli_stmt_get_result($cek);
        if (!$validTahun || mysqli_num_rows($validTahun) !== 1) {
            $error = 'Tahun ajaran tidak ditemukan. Pilih tahun ajaran dari menu Kelola Tahun Ajaran.';
        } else {
            $stmt = mysqli_prepare($koneksi, 'INSERT INTO t_kelas (nama, tingkat, jurusan, tahun_ajaran_id, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
            if (!$stmt) $error = 'Gagal menyiapkan penyimpanan: ' . mysqli_error($koneksi);
            else {
                mysqli_stmt_bind_param($stmt, 'sssii', $nama, $tingkat, $jurusan, $tahun_ajaran_id, $status);
                if (mysqli_stmt_execute($stmt)) {
                    header('Location: kelola_kelas.php?pesan=tambah_berhasil' . ($embed ? '&embed=1' : '')); exit;
                }
                $error = 'Gagal menyimpan kelas: ' . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_stmt_close($cek);
    }
}
function e_tambah_kelas($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Tambah Kelas</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><main class="container py-4"><div class="card shadow-sm"><div class="card-header"><h4 class="mb-0">Tambah Kelas</h4></div><div class="card-body">
<?php if($error!==''): ?><div class="alert alert-danger"><?=e_tambah_kelas($error)?></div><?php endif; ?>
<?php if($jumlahTahun===0): ?><div class="alert alert-warning">Belum ada Tahun Ajaran. Tambahkan terlebih dahulu pada menu Kelola Tahun Ajaran.</div><a class="btn btn-primary" href="tambah_tahun_ajaran.php<?= $embed?'?embed=1':'' ?>">Tambah Tahun Ajaran</a>
<?php else: ?>
<form method="post" class="row g-3"><input type="hidden" name="embed" value="<?=$embed?'1':'0'?>">
<div class="col-md-6"><label class="form-label" for="nama">Nama Kelas</label><input class="form-control" id="nama" name="nama" maxlength="100" value="<?=e_tambah_kelas($nama)?>" placeholder="Contoh: RPL 1" required></div>
<div class="col-md-6"><label class="form-label" for="tingkat">Tingkat</label><select class="form-select" id="tingkat" name="tingkat" required><option value="">Pilih Tingkat</option><?php foreach(['X','XI','XII'] as $t): ?><option value="<?=$t?>" <?=$tingkat===$t?'selected':''?>><?=$t?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label" for="jurusan">Jurusan</label><input class="form-control" id="jurusan" name="jurusan" maxlength="100" value="<?=e_tambah_kelas($jurusan)?>" placeholder="Contoh: RPL" required></div>
<div class="col-md-6"><label class="form-label" for="tahun_ajaran_id">Tahun Ajaran</label><select class="form-select" id="tahun_ajaran_id" name="tahun_ajaran_id" required><option value="">Pilih dari menu Tahun Ajaran</option><?php while($ta=mysqli_fetch_assoc($queryTahun)): ?><option value="<?=(int)$ta['id']?>" <?=(int)$ta['id']===$tahun_ajaran_id?'selected':''?>><?=e_tambah_kelas($ta['nama'])?></option><?php endwhile; ?></select><div class="form-text">Pilihan ini otomatis diambil dari data menu Kelola Tahun Ajaran.</div></div>
<div class="col-md-6"><label class="form-label" for="status_aktif">Status</label><select class="form-select" id="status_aktif" name="status_aktif"><option value="1" <?=$status===1?'selected':''?>>Aktif</option><option value="0" <?=$status===0?'selected':''?>>Tidak Aktif</option></select></div>
<div class="col-12"><button class="btn btn-primary" type="submit">Simpan</button> <a class="btn btn-secondary" href="kelola_kelas.php<?= $embed?'?embed=1':'' ?>">Batal</a></div></form><?php endif; ?>
</div></div></main></body></html>
