<?php
require_once __DIR__.'/includes/cek_session.php'; require_once __DIR__.'/config/koneksi.php';
$embed=(($_GET['embed']??'')==='1');$id=filter_var($_GET['id']??null,FILTER_VALIDATE_INT);
if(!$id||$id<1){$_SESSION['pesan']='ID siswa tidak valid.';header('Location: kelola_siswa.php?embed=1');exit;}
$stmt=mysqli_prepare($koneksi,'SELECT * FROM t_siswa WHERE id=?');mysqli_stmt_bind_param($stmt,'i',$id);mysqli_stmt_execute($stmt);$siswa=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if(!$siswa){$_SESSION['pesan']='Data siswa tidak ditemukan.';header('Location: kelola_siswa.php?embed=1');exit;}
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<?php if(!$embed):?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Data Siswa</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><?php endif;?>
<div class="container-fluid p-3 p-md-4 bg-white"><h2 class="fw-bold">Edit Data Siswa</h2><hr><?php if(!empty($_SESSION['pesan'])):?><div class="alert alert-info"><?=e($_SESSION['pesan'])?></div><?php unset($_SESSION['pesan']);endif;?>
<form action="proses_edit_siswa.php" method="post" class="row g-3"><input type="hidden" name="id" value="<?=(int)$siswa['id']?>"><input type="hidden" name="embed" value="1">
<div class="col-12 col-md-6"><label class="form-label" for="nis">NIS</label><input id="nis" class="form-control" name="nis" maxlength="30" value="<?=e($siswa['nis'])?>" required></div>
<div class="col-12 col-md-6"><label class="form-label" for="nisn">NISN</label><input id="nisn" class="form-control" name="nisn" maxlength="30" value="<?=e($siswa['nisn'])?>" required></div>
<div class="col-12 col-md-6"><label class="form-label" for="nama">Nama Siswa</label><input id="nama" class="form-control" name="nama" maxlength="150" value="<?=e($siswa['nama'])?>" required></div>
<div class="col-12 col-md-6"><label class="form-label" for="jenis_kelamin">Jenis Kelamin</label><select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required><option value="">-- Pilih Jenis Kelamin --</option><option value="L" <?=$siswa['jenis_kelamin']==='L'?'selected':''?>>Laki-laki</option><option value="P" <?=$siswa['jenis_kelamin']==='P'?'selected':''?>>Perempuan</option></select></div>
<div class="col-12 col-md-6"><label class="form-label" for="tanggal_lahir">Tanggal Lahir</label><input id="tanggal_lahir" type="date" class="form-control" name="tanggal_lahir" value="<?=e($siswa['tanggal_lahir'])?>" required></div>
<div class="col-12 col-md-6"><label class="form-label" for="status_aktif">Status Aktif</label><select id="status_aktif" name="status_aktif" class="form-select" required><option value="1" <?=(int)$siswa['status_aktif']===1?'selected':''?>>Aktif</option><option value="0" <?=(int)$siswa['status_aktif']===0?'selected':''?>>Tidak Aktif</option></select></div>
<div class="col-12"><label class="form-label" for="alamat">Alamat</label><textarea id="alamat" name="alamat" class="form-control" rows="2" required><?=e($siswa['alamat'])?></textarea></div><div class="col-12"><button class="btn btn-primary" type="submit">Simpan Perubahan</button> <a class="btn btn-secondary" href="kelola_siswa.php?embed=1">Batal</a></div></form></div>
<?php if(!$embed):?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php endif;?>
