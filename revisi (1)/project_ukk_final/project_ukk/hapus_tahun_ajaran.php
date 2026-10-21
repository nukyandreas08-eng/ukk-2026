<?php
require_once __DIR__.'/includes/cek_session.php'; require_once __DIR__.'/config/koneksi.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id||$id<1){$_SESSION['pesan']='ID tahun ajaran tidak valid.';header('Location: kelola_tahun_ajaran.php');exit;}
$stmt=mysqli_prepare($koneksi,'DELETE FROM t_tahun_ajaran WHERE id=?');mysqli_stmt_bind_param($stmt,'i',$id);
if(mysqli_stmt_execute($stmt)){$_SESSION['pesan']=mysqli_stmt_affected_rows($stmt)>0?'Data tahun ajaran berhasil dihapus.':'Data tahun ajaran tidak ditemukan.';}else{$_SESSION['pesan']='Gagal menghapus tahun ajaran. Pastikan data ini tidak digunakan pada wali kelas.';}
header('Location: kelola_tahun_ajaran.php');exit;
?>