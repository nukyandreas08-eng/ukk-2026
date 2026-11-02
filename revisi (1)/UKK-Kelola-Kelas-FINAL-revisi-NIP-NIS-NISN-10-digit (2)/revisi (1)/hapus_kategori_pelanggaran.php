<?php
session_start(); require_once 'includes/cek_session.php'; require_once 'config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: kelola_kategori_pelanggaran.php'); exit; }
$id=(int)($_POST['id']??0); $embed=(($_POST['embed']??'')==='1');
if($id<1) $_SESSION['pesan']='ID kategori tidak valid.';
else {
 $st=mysqli_prepare($koneksi,'DELETE FROM t_pelanggaran_kategori WHERE id=?');
 mysqli_stmt_bind_param($st,'i',$id);
 if(mysqli_stmt_execute($st)) $_SESSION['pesan']=mysqli_stmt_affected_rows($st)>0?'Kategori berhasil dihapus.':'Kategori tidak ditemukan.';
 else $_SESSION['pesan']='Kategori tidak dapat dihapus karena masih digunakan oleh data pelanggaran.';
 mysqli_stmt_close($st);
}
header('Location: kelola_kategori_pelanggaran.php'.($embed?'?embed=1':'')); exit;
