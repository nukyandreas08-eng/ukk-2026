<?php
session_start(); require_once 'includes/cek_session.php'; require_once 'config/koneksi.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: catatan_pelanggaran.php');exit;}
$aksi=$_POST['aksi']??'tambah'; $embed=(($_POST['embed']??'')==='1');
function kembaliPelanggaran($embed){header('Location: catatan_pelanggaran.php'.($embed?'?embed=1':''));exit;}
if($aksi==='hapus'){
 $id=(int)($_POST['id']??0);
 if($id<1) $_SESSION['pesan']='ID pelanggaran tidak valid.';
 else {$st=mysqli_prepare($GLOBALS['koneksi'],'DELETE FROM t_pelanggaran WHERE id=?');mysqli_stmt_bind_param($st,'i',$id);
 if(mysqli_stmt_execute($st)) $_SESSION['pesan']=mysqli_stmt_affected_rows($st)>0?'Jenis pelanggaran berhasil dihapus.':'Data tidak ditemukan.';
 else $_SESSION['pesan']='Tidak dapat menghapus jenis pelanggaran karena masih dipakai.';
 mysqli_stmt_close($st);}
 kembaliPelanggaran($embed);
}
$id=(int)($_POST['id']??0); $kategori=(int)($_POST['pelanggaran_kategori_id']??0); $kode=trim($_POST['kode']??''); $nama=trim($_POST['nama']??''); $poin=(int)($_POST['poin']??0); $deskripsi=trim($_POST['deskripsi']??''); $status=(int)($_POST['status_aktif']??0);
if($kategori<1||$kode===''||$nama===''||$poin<0||$deskripsi===''){$_SESSION['pesan']='Semua kolom wajib diisi dengan benar.';kembaliPelanggaran($embed);}
if($aksi==='edit' && $id>0){
 $st=mysqli_prepare($koneksi,'UPDATE t_pelanggaran SET pelanggaran_kategori_id=?,kode=?,nama=?,poin=?,deskripsi=?,status_aktif=?,updated_at=NOW() WHERE id=?');
 mysqli_stmt_bind_param($st,'issisii',$kategori,$kode,$nama,$poin,$deskripsi,$status,$id);
}else{
 $st=mysqli_prepare($koneksi,'INSERT INTO t_pelanggaran (pelanggaran_kategori_id,kode,nama,poin,deskripsi,status_aktif) VALUES (?,?,?,?,?,?)');
 mysqli_stmt_bind_param($st,'issisi',$kategori,$kode,$nama,$poin,$deskripsi,$status);
}
if(mysqli_stmt_execute($st)){$_SESSION['pesan']=$aksi==='edit'?'Jenis pelanggaran berhasil diperbarui.':'Jenis pelanggaran berhasil ditambahkan.';}else{$_SESSION['pesan']='Gagal menyimpan data: '.mysqli_stmt_error($st);}
mysqli_stmt_close($st);kembaliPelanggaran($embed);
