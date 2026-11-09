<?php
include 'includes/cek_session.php'; include 'config/koneksi.php';
$embed=(($_GET['embed']??'')==='1');
$sql="SELECT id,tanggal,nama_siswa,nama_kelas,nama_pelanggaran,poin,keterangan,tindakan,status,nama_guru FROM t_pelanggaran_siswa ORDER BY tanggal DESC,id DESC";
$q=mysqli_query($koneksi,$sql);if(!$q){die('Gagal mengambil riwayat: '.htmlspecialchars(mysqli_error($koneksi)));}
function eR($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<?php if(!$embed):?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Riwayat Pelanggaran</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><?php endif;?>
<main class="container-fluid p-3 p-md-4 bg-white"><h2 class="fw-bold">Riwayat Pelanggaran Siswa</h2><p class="text-secondary">Riwayat catatan yang sudah tersimpan di sistem.</p><div class="table-responsive"><table class="table table-bordered table-striped align-middle"><thead class="table-light"><tr><th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Pelanggaran</th><th>Poin</th><th>Keterangan</th><th>Tindakan</th><th>Status</th><th>Guru</th></tr></thead><tbody><?php $no=1;while($r=mysqli_fetch_assoc($q)):?><tr><td><?=$no++?></td><td><?=eR($r['tanggal'])?></td><td><?=eR($r['nama_siswa'])?></td><td><?=eR($r['nama_kelas'])?></td><td><?=eR($r['nama_pelanggaran'])?></td><td><?=(int)$r['poin']?></td><td><?=eR($r['keterangan'])?></td><td><?=eR($r['tindakan'])?></td><td><?=eR($r['status'])?></td><td><?=eR($r['nama_guru'])?></td></tr><?php endwhile;?><?php if(mysqli_num_rows(mysqli_query($koneksi,"SELECT id FROM t_pelanggaran_siswa"))===0):?><tr><td colspan="10" class="text-center">Belum ada riwayat pelanggaran.</td></tr><?php endif;?></tbody></table></div></main>
<?php if(!$embed):?></body></html><?php endif;?>
