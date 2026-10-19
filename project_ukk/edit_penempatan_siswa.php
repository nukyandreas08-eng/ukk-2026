<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');
$error = '';
$stmt = mysqli_prepare($koneksi, 'SELECT * FROM t_kelas_siswa WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$data) { http_response_code(404); exit('Data penempatan tidak ditemukan.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id=(int)($_POST['siswa_id']??0); $kelas_id=(int)($_POST['kelas_id']??0); $tahun_id=(int)($_POST['tahun_ajaran_id']??0);
    if ($siswa_id<1 || $kelas_id<1 || $tahun_id<1) $error='Semua pilihan wajib diisi.';
    else {
        $cek=mysqli_prepare($koneksi,'SELECT id FROM t_kelas_siswa WHERE siswa_id=? AND tahun_ajaran_id=? AND id<>?');
        mysqli_stmt_bind_param($cek,'iii',$siswa_id,$tahun_id,$id); mysqli_stmt_execute($cek);
        $exists=mysqli_num_rows(mysqli_stmt_get_result($cek))>0; mysqli_stmt_close($cek);
        if ($exists) $error='Siswa sudah ditempatkan pada tahun ajaran tersebut.';
        else {
            $up=mysqli_prepare($koneksi,'UPDATE t_kelas_siswa SET siswa_id=?, kelas_id=?, tahun_ajaran_id=?, updated_at=NOW() WHERE id=?');
            mysqli_stmt_bind_param($up,'iiii',$siswa_id,$kelas_id,$tahun_id,$id);
            if(mysqli_stmt_execute($up)){ mysqli_stmt_close($up); header('Location: kelola_penempatan_siswa.php'.($embed?'?embed=1':'')); exit; }
            $error='Gagal memperbarui penempatan.'; mysqli_stmt_close($up);
        }
    }
}
$siswa=mysqli_query($koneksi,'SELECT id,nis,nama FROM t_siswa ORDER BY nama');
$kelas=mysqli_query($koneksi,'SELECT id,nama,tingkat,jurusan FROM t_kelas ORDER BY tingkat,jurusan,nama');
$tahun=mysqli_query($koneksi,'SELECT id,nama FROM t_tahun_ajaran ORDER BY id DESC');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Penempatan Siswa</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><main class="container py-4"><div class="card"><div class="card-header">Edit Penempatan Siswa</div><div class="card-body">
<?php if($error!==''): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="id" value="<?=$id?>"><input type="hidden" name="embed" value="<?=$embed?'1':'0'?>">
<label class="form-label">Siswa</label><select class="form-select mb-3" name="siswa_id" required><?php while($r=mysqli_fetch_assoc($siswa)): ?><option value="<?=$r['id']?>" <?=$r['id']==$data['siswa_id']?'selected':''?>><?=htmlspecialchars($r['nis'].' - '.$r['nama'])?></option><?php endwhile; ?></select>
<label class="form-label">Kelas</label><select class="form-select mb-3" name="kelas_id" required><?php while($r=mysqli_fetch_assoc($kelas)): ?><option value="<?=$r['id']?>" <?=$r['id']==$data['kelas_id']?'selected':''?>><?=htmlspecialchars($r['nama'].' - '.$r['tingkat'].' - '.$r['jurusan'])?></option><?php endwhile; ?></select>
<label class="form-label">Tahun Ajaran</label><select class="form-select mb-3" name="tahun_ajaran_id" required><?php while($r=mysqli_fetch_assoc($tahun)): ?><option value="<?=$r['id']?>" <?=$r['id']==$data['tahun_ajaran_id']?'selected':''?>><?=htmlspecialchars($r['nama'])?></option><?php endwhile; ?></select>
<button class="btn btn-primary">Simpan Perubahan</button> <a class="btn btn-secondary" href="kelola_penempatan_siswa.php<?=$embed?'?embed=1':''?>">Batal</a></form></div></div></main></body></html>
