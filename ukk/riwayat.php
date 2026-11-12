<?php
include 'includes/cek_session.php'; include 'config/koneksi.php';
$embed=(($_GET['embed']??'')==='1');
$role=hak_role_efektif();
$sql="SELECT id,tanggal,nama_siswa,(SELECT nis FROM t_siswa WHERE t_siswa.id=t_pelanggaran_siswa.siswa_id) AS nis,nama_kelas,nama_pelanggaran,poin,keterangan,tindakan,status,nama_guru FROM t_pelanggaran_siswa";
if($role===HAK_WALI_KELAS){
 // Wali kelas melihat catatan untuk kelas yang menjadi tanggung jawabnya.
 $sql.=" WHERE EXISTS (SELECT 1 FROM t_wali_kelas wk INNER JOIN t_guru g ON g.id=wk.guru_id WHERE g.user_id=? AND wk.status_aktif=1 AND wk.kelas_id=t_pelanggaran_siswa.kelas_id AND (wk.tahun_ajaran_id=t_pelanggaran_siswa.tahun_ajaran_id OR t_pelanggaran_siswa.tahun_ajaran_id IS NULL))";
 $sql.=" ORDER BY tanggal DESC,id DESC";
 $st=mysqli_prepare($koneksi,$sql);$uid=(int)($_SESSION['id']??0);mysqli_stmt_bind_param($st,'i',$uid);mysqli_stmt_execute($st);$q=mysqli_stmt_get_result($st);
}elseif($role===HAK_GURU){
 // Guru biasa hanya melihat catatan yang dibuat oleh akun guru yang sedang login.
 $sql.=" WHERE EXISTS (SELECT 1 FROM t_guru g WHERE g.id=t_pelanggaran_siswa.guru_id AND g.user_id=? AND g.status_aktif=1)";
 $sql.=" ORDER BY tanggal DESC,id DESC";
 $st=mysqli_prepare($koneksi,$sql);$uid=(int)($_SESSION['id']??0);mysqli_stmt_bind_param($st,'i',$uid);mysqli_stmt_execute($st);$q=mysqli_stmt_get_result($st);
}else{$sql.=" ORDER BY tanggal DESC,id DESC";$q=mysqli_query($koneksi,$sql);}
if(!$q){die('Gagal mengambil riwayat: '.htmlspecialchars(mysqli_error($koneksi)));}
function eR($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<?php if(!$embed):?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Riwayat Pelanggaran</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><?php endif;?>
<main class="container-fluid p-3 p-md-4 bg-white"><h2 class="fw-bold">Riwayat Pelanggaran Siswa</h2><p class="text-secondary">Riwayat catatan yang sudah tersimpan di sistem.</p><div class="mb-3"><input type="search" id="cariRiwayat" class="form-control" placeholder="Cari siswa berdasarkan NIS/nama, kelas, atau pelanggaran..."></div><div class="table-responsive"><table id="tabelRiwayat" class="table table-bordered table-striped align-middle"><thead class="table-light"><tr><th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Pelanggaran</th><th>Poin</th><th>Keterangan</th><th>Tindakan</th><th>Status</th><th>Guru</th></tr></thead><tbody><?php $no=1;while($r=mysqli_fetch_assoc($q)):?><tr><td><?=$no++?></td><td><?=eR($r['tanggal'])?></td><td><?=eR($r['nama_siswa'])?><br><small class="text-muted">NIS: <?=eR($r['nis']??'')?></small></td><td><?=eR($r['nama_kelas'])?></td><td><?=eR($r['nama_pelanggaran'])?></td><td><?=(int)$r['poin']?></td><td><?=eR($r['keterangan'])?></td><td><?=eR($r['tindakan'])?></td><td><?=eR($r['status'])?></td><td><?=eR($r['nama_guru'])?></td></tr><?php endwhile;?><?php if(mysqli_num_rows($q)===0):?><tr><td colspan="10" class="text-center">Belum ada riwayat pelanggaran.</td></tr><?php endif;?></tbody></table></div></main>
<script>
document.getElementById('cariRiwayat')?.addEventListener('input',function(){const k=this.value.toLocaleLowerCase('id');document.querySelectorAll('#tabelRiwayat tbody tr').forEach(tr=>tr.hidden=!tr.textContent.toLocaleLowerCase('id').includes(k));});
</script><?php if(!$embed):?></body></html><?php endif;?>
