<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';
$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { header('Location: kelola_kelas.php?pesan=id_tidak_valid'.($embed?'&embed=1':'')); exit; }
$stmt = mysqli_prepare($koneksi, 'SELECT id,nama,tingkat,jurusan,tahun_ajaran_id,status_aktif FROM t_kelas WHERE id=?');
if (!$stmt) die('Gagal menyiapkan data kelas: '.htmlspecialchars(mysqli_error($koneksi),ENT_QUOTES,'UTF-8'));
mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt);
$kelas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt);
if (!$kelas) { header('Location: kelola_kelas.php?pesan=data_tidak_ditemukan'.($embed?'&embed=1':'')); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nama=trim($_POST['nama']??''); $tingkat=$_POST['tingkat']??''; $jurusan=trim($_POST['jurusan']??'');
    $tahun_id=(int)($_POST['tahun_ajaran_id']??0); $status=filter_var($_POST['status_aktif']??null,FILTER_VALIDATE_INT);
    if ($nama==='' || !in_array($tingkat,['X','XI','XII'],true) || $jurusan==='' || $tahun_id<1 || !in_array($status,[0,1],true)) {
        $error='Lengkapi semua data kelas dengan benar.';
    } else {
        $cek=mysqli_prepare($koneksi,'SELECT id FROM t_tahun_ajaran WHERE id=?');
        mysqli_stmt_bind_param($cek,'i',$tahun_id); mysqli_stmt_execute($cek);
        $valid=mysqli_num_rows(mysqli_stmt_get_result($cek))===1; mysqli_stmt_close($cek);
        if (!$valid) $error='Tahun ajaran yang dipilih tidak tersedia. Periksa menu Kelola Tahun Ajaran.';
        else {
            $up=mysqli_prepare($koneksi,'UPDATE t_kelas SET nama=?,tingkat=?,jurusan=?,tahun_ajaran_id=?,status_aktif=?,updated_at=NOW() WHERE id=?');
            if (!$up) $error='Gagal menyiapkan perubahan: '.mysqli_error($koneksi);
            else {
                mysqli_stmt_bind_param($up,'sssiii',$nama,$tingkat,$jurusan,$tahun_id,$status,$id);
                if (mysqli_stmt_execute($up)) { mysqli_stmt_close($up); header('Location: kelola_kelas.php?pesan=edit_berhasil'.($embed?'&embed=1':'')); exit; }
                $error='Gagal memperbarui kelas: '.mysqli_stmt_error($up); mysqli_stmt_close($up);
            }
        }
    }
    $kelas=array_merge($kelas,['nama'=>$nama,'tingkat'=>$tingkat,'jurusan'=>$jurusan,'tahun_ajaran_id'=>$tahun_id,'status_aktif'=>$status]);
}
$queryTahun=mysqli_query($koneksi,'SELECT id,nama FROM t_tahun_ajaran ORDER BY id DESC');
if (!$queryTahun) die('Gagal mengambil tahun ajaran: '.htmlspecialchars(mysqli_error($koneksi),ENT_QUOTES,'UTF-8'));
function e_edit_kelas($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Kelas</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><main class="container py-4"><div class="card shadow-sm"><div class="card-header"><h4 class="mb-0">Edit Kelas</h4></div><div class="card-body">
<?php if($error!==''): ?><div class="alert alert-danger"><?=e_edit_kelas($error)?></div><?php endif; ?>
<form method="post" class="row g-3"><input type="hidden" name="embed" value="<?=$embed?'1':'0'?>">
<div class="col-md-6"><label class="form-label" for="nama">Nama Kelas</label><input class="form-control" id="nama" name="nama" maxlength="100" value="<?=e_edit_kelas($kelas['nama'])?>" required></div>
<div class="col-md-6"><label class="form-label" for="tingkat">Tingkat</label><select class="form-select" id="tingkat" name="tingkat" required><option value="">Pilih Tingkat</option><?php foreach(['X','XI','XII'] as $t): ?><option value="<?=$t?>" <?=$kelas['tingkat']===$t?'selected':''?>><?=$t?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label" for="jurusan">Jurusan</label><input class="form-control" id="jurusan" name="jurusan" value="<?=e_edit_kelas($kelas['jurusan'])?>" required></div>
<div class="col-md-6"><label class="form-label" for="tahun_ajaran_id">Tahun Ajaran</label><select class="form-select" id="tahun_ajaran_id" name="tahun_ajaran_id" required><option value="">Pilih Tahun Ajaran</option><?php while($ta=mysqli_fetch_assoc($queryTahun)): ?><option value="<?=(int)$ta['id']?>" <?=(int)$kelas['tahun_ajaran_id']===(int)$ta['id']?'selected':''?>><?=e_edit_kelas($ta['nama'])?></option><?php endwhile; ?></select></div>
<div class="col-md-6"><label class="form-label" for="status_aktif">Status</label><select class="form-select" id="status_aktif" name="status_aktif"><option value="1" <?=(int)$kelas['status_aktif']===1?'selected':''?>>Aktif</option><option value="0" <?=(int)$kelas['status_aktif']===0?'selected':''?>>Tidak Aktif</option></select></div>
<div class="col-12"><button class="btn btn-primary" type="submit">Simpan Perubahan</button> <a class="btn btn-secondary" href="kelola_kelas.php<?= $embed?'?embed=1':'' ?>">Batal</a></div>
</form></div></div></main></body></html>
