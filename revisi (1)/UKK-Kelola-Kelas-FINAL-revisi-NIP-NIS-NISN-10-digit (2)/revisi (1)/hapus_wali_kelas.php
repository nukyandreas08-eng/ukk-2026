<?php
require_once __DIR__.'/includes/cek_session.php';require_once __DIR__.'/config/koneksi.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if(!$id||$id<1){$_SESSION['pesan']='ID wali kelas tidak valid.';header('Location: kelola_wali_kelas.php');exit;}
$stmt=mysqli_prepare($koneksi,'DELETE FROM t_wali_kelas WHERE id=?');mysqli_stmt_bind_param($stmt,'i',$id);
if(mysqli_stmt_execute($stmt)){$_SESSION['pesan']=mysqli_stmt_affected_rows($stmt)>0?'Data wali kelas berhasil dihapus.':'Data wali kelas tidak ditemukan.';}else{$_SESSION['pesan']='Gagal menghapus data wali kelas: '.mysqli_stmt_error($stmt);}
header('Location: kelola_wali_kelas.php');exit;
?>