<?php
include 'includes/cek_session.php';
$embed = isset($_GET['embed']) && $_GET['embed'] === '1';
if (!$embed) { echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><title>Kelola Jenis Pelanggaran</title></head><body class="p-3">'; }
?>
<div class="container-fluid p-0"><h1 class="h3 mb-2">Kelola Jenis Pelanggaran</h1><p class="text-secondary">Kelola kategori dan jenis pelanggaran yang dapat dicatat oleh guru.</p><div class="alert alert-info">Gunakan menu yang tersedia untuk mengelola dan meninjau data terkait.</div><a class="btn btn-outline-secondary" href="dashboard.php?embed=1" onclick="if(window.parent!==window){try{var f=window.parent.document.getElementById('contentFrame');if(f){window.parent.document.querySelectorAll('.sidebar .nav-link').forEach(function(a){a.classList.remove('active')});var d=window.parent.document.querySelector('.sidebar .nav-link[href*=\'dashboard\']');if(d)d.classList.add('active');f.src='dashboard.php?embed=1';return false;}}catch(e){}}"
>Kembali ke Dashboard</a><a class="btn btn-primary ms-2" href="kelola_kategori_pelanggaran.php?embed=1">Buka data terkait</a></div>
<?php if (!$embed) echo '</body></html>'; ?>