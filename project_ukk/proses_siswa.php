<?php
require_once __DIR__.'/includes/cek_session.php'; require_once __DIR__.'/config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['aksi'] ?? '') !== 'tambah') { header('Location: kelola_siswa.php?embed=1'); exit; }
$nis=trim($_POST['nis']??''); $nisn=trim($_POST['nisn']??''); $nama=trim($_POST['nama']??''); $jk=$_POST['jenis_kelamin']??''; $tanggal=$_POST['tanggal_lahir']??''; $alamat=trim($_POST['alamat']??''); $status=filter_var($_POST['status_aktif']??null,FILTER_VALIDATE_INT);
if($nis===''||$nisn===''||$nama===''||!in_array($jk,['L','P'],true)||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$tanggal)||!checkdate((int)substr($tanggal,5,2),(int)substr($tanggal,8,2),(int)substr($tanggal,0,4))||$alamat===''||!in_array($status,[0,1],true)){$_SESSION['pesan']='Data belum lengkap atau tidak valid.';header('Location: kelola_siswa.php?embed=1');exit;}
$cek=mysqli_prepare($koneksi,'SELECT id FROM t_siswa WHERE nis=? OR nisn=?'); mysqli_stmt_bind_param($cek,'ss',$nis,$nisn); mysqli_stmt_execute($cek); $res=mysqli_stmt_get_result($cek); if(mysqli_num_rows($res)>0){$_SESSION['pesan']='NIS atau NISN sudah digunakan siswa lain.';header('Location: kelola_siswa.php?embed=1');exit;}
$stmt=mysqli_prepare($koneksi,'INSERT INTO t_siswa (nis,nisn,nama,jenis_kelamin,tanggal_lahir,alamat,status_aktif,created_at,updated_at) VALUES (?,?,?,?,?,?,?,NOW(),NOW())');
if(!$stmt){$_SESSION['pesan']='Gagal menyiapkan penyimpanan data.';header('Location: kelola_siswa.php?embed=1');exit;} mysqli_stmt_bind_param($stmt,'ssssssi',$nis,$nisn,$nama,$jk,$tanggal,$alamat,$status);
$_SESSION['pesan']=mysqli_stmt_execute($stmt)?'Data siswa berhasil ditambahkan.':'Gagal menambahkan data. Pastikan NIS dan NISN belum digunakan.'; header('Location: kelola_siswa.php?embed=1'); exit;
?>