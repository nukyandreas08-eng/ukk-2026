<?php
include 'includes/cek_session.php'; include 'config/koneksi.php';
$id=(int)($_GET['id']??$_POST['id']??0); $embed=(($_GET['embed']??$_POST['embed']??'')==='1'); $error='';
$st=mysqli_prepare($koneksi,'SELECT * FROM t_pelanggaran_kategori WHERE id=?'); mysqli_stmt_bind_param($st,'i',$id); mysqli_stmt_execute($st); $data=mysqli_fetch_assoc(mysqli_stmt_get_result($st)); mysqli_stmt_close($st);
if(!$data){http_response_code(404);exit('Kategori tidak ditemukan.');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nama=trim($_POST['nama']??''); $deskripsi=trim($_POST['deskripsi']??''); $status=isset($_POST['status_aktif'])?1:0;
 if($nama==='') $error='Nama kategori wajib diisi.';
 else {$up=mysqli_prepare($koneksi,'UPDATE t_pelanggaran_kategori SET nama=?,deskripsi=?,status_aktif=?,updated_at=NOW() WHERE id=?'); mysqli_stmt_bind_param($up,'ssii',$nama,$deskripsi,$status,$id);
 if(mysqli_stmt_execute($up)){mysqli_stmt_close($up);header('Location: kelola_kategori_pelanggaran.php'.($embed?'?embed=1':''));exit;} $error='Gagal memperbarui kategori.';mysqli_stmt_close($up);}
 $data['nama']=$nama;$data['deskripsi']=$deskripsi;$data['status_aktif']=$status;
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Kategori</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><main class="container py-4"><div class="card"><div class="card-header">Edit Kategori Pelanggaran</div><div class="card-body"><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif;?>
<form method="post"><input type="hidden" name="id" value="<?=$id?>"><input type="hidden" name="embed" value="<?=$embed?'1':'0'?>"><label class="form-label">Nama Kategori</label><input class="form-control mb-3" name="nama" required value="<?=htmlspecialchars($data['nama'])?>"><label class="form-label">Deskripsi</label><textarea class="form-control mb-3" name="deskripsi" rows="3"><?=htmlspecialchars($data['deskripsi'])?></textarea><div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="status_aktif" id="status" value="1" <?=$data['status_aktif']?'checked':''?>><label class="form-check-label" for="status">Aktif</label></div><button class="btn btn-primary">Simpan Perubahan</button> <a class="btn btn-secondary" href="kelola_kategori_pelanggaran.php<?=$embed?'?embed=1':''?>">Batal</a></form></div></div></main></body></html>
