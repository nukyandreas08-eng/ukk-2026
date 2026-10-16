<?php
include 'includes/cek_session.php';
$role = strtolower(trim((string)($_SESSION['role'] ?? '')));
$namaPengguna = $_SESSION['name'] ?? 'Pengguna';

if (isset($_GET['embed']) && $_GET['embed'] === '1') {
    include 'config/koneksi.php';
    function jumlahDataDashboard($koneksi, $tabel) {
        $allowed = ['t_siswa','t_guru','t_kelas','t_pelanggaran'];
        if (!in_array($tabel, $allowed, true)) return 0;
        $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM `$tabel`");
        if (!$result) return 0;
        return (int)(mysqli_fetch_assoc($result)['total'] ?? 0);
    }
    $siswa = jumlahDataDashboard($koneksi, 't_siswa');
    $guru = jumlahDataDashboard($koneksi, 't_guru');
    $kelas = jumlahDataDashboard($koneksi, 't_kelas');
    $pelanggaran = jumlahDataDashboard($koneksi, 't_pelanggaran');
    ?>
    <!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><title>Dashboard</title></head>
    <body class="bg-light"><main class="container-fluid p-0"><div class="mb-4"><h2 class="fw-bold">Dashboard</h2><p class="text-secondary mb-0">Selamat datang, <?= htmlspecialchars($namaPengguna, ENT_QUOTES, 'UTF-8') ?>. Berikut ringkasan Sistem Informasi Pelanggaran Siswa.</p></div><div class="row g-3"><div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary">Data Siswa</div><div class="display-6 fw-semibold text-primary"><?= $siswa ?></div></div></div></div><div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary">Data Guru</div><div class="display-6 fw-semibold text-success"><?= $guru ?></div></div></div></div><div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary">Data Kelas</div><div class="display-6 fw-semibold text-warning"><?= $kelas ?></div></div></div></div><div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary">Catatan Pelanggaran</div><div class="display-6 fw-semibold text-danger"><?= $pelanggaran ?></div></div></div></div></div><div class="card border-0 shadow-sm mt-4"><div class="card-body"><h5 class="fw-semibold">Alur penggunaan</h5><p class="mb-0 text-secondary">Pilih menu di sidebar untuk mengelola data, mencatat pelanggaran, melihat riwayat, serta mencetak laporan sesuai hak akses akun.</p></div></div></main></body></html>
    <?php exit;
}

$menuAdmin = [
 ['Dashboard','dashboard.php?embed=1','bi-speedometer2'],
 ['Kelola Siswa','kelola_siswa.php?embed=1','bi-people'],
 ['Kelola Guru','kelola_guru.php?embed=1','bi-person-badge'],
 ['Kelola Kelas','kelola_kelas.php?embed=1','bi-building'],
 ['Tahun Ajaran','kelola_tahun_ajaran.php?embed=1','bi-calendar3'],
 ['Wali Kelas','kelola_wali_kelas.php?embed=1','bi-person-workspace'],
 ['Penempatan Siswa','kelola_penempatan_siswa.php?embed=1','bi-diagram-3'],
 ['Kategori Pelanggaran','kelola_kategori_pelanggaran.php?embed=1','bi-tags'],
 ['Jenis Pelanggaran','kelola_jenis_pelanggaran.php?embed=1','bi-list-check'],
 ['Catat Pelanggaran','catatan_pelanggaran.php?embed=1','bi-journal-plus'],
 ['Tindakan','tindakan.php?embed=1','bi-clipboard-check'],
 ['Riwayat Pelanggaran','riwayat.php?embed=1','bi-clock-history'],
 ['Rekap Poin','rekap_point.php?embed=1','bi-bar-chart'],
 ['Laporan','laporan.php?embed=1','bi-file-earmark-text'],
 ['Cetak / Export','laporan.php?embed=1&cetak=1','bi-printer'],
 ['About Me','about_me.php?embed=1','bi-person-circle'],
];
$menuGuru = [
 ['Dashboard','dashboard.php?embed=1','bi-speedometer2'],
 ['Catat Pelanggaran','catatan_pelanggaran.php?embed=1','bi-journal-plus'],
 ['Tindakan','tindakan.php?embed=1','bi-clipboard-check'],
 ['Riwayat Pelanggaran','riwayat.php?embed=1','bi-clock-history'],
 ['Laporan','laporan.php?embed=1','bi-file-earmark-text'],
 ['Rekap Poin','rekap_point.php?embed=1','bi-bar-chart'],
 ['About Me','about_me.php?embed=1','bi-person-circle'],
];
$menuWali = [
 ['Dashboard','dashboard.php?embed=1','bi-speedometer2'],
 ['Riwayat Pelanggaran','riwayat.php?embed=1','bi-clock-history'],
 ['Rekap Poin','rekap_point.php?embed=1','bi-bar-chart'],
 ['Laporan','laporan.php?embed=1','bi-file-earmark-text'],
 ['About Me','about_me.php?embed=1','bi-person-circle'],
];
if (in_array($role, ['admin','administrator'], true)) { $menu = $menuAdmin; $roleLabel = 'Administrator'; }
elseif (in_array($role, ['wali kelas','wali_kelas','walikelas','wali-kelas'], true)) { $menu = $menuWali; $roleLabel = 'Wali Kelas'; }
else { $menu = $menuGuru; $roleLabel = 'Guru'; }
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sistem Informasi Pelanggaran Siswa</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><style>html,body{margin:0;min-height:100%;font-family:Arial,sans-serif;background:#f4f6f9}.sidebar{background:#212529;min-height:100vh;padding:18px 12px}.brand{color:#fff;font-weight:700;font-size:1.05rem;line-height:1.35;padding:4px 10px 20px}.role-pill{font-size:.75rem;color:#adb5bd;padding:0 10px 12px}.sidebar .nav-link{display:flex;gap:10px;align-items:center;color:#e9ecef;border-radius:7px;padding:10px 12px;margin-bottom:4px;font-size:.92rem}.sidebar .nav-link:hover{background:#343a40;color:#fff}.sidebar .nav-link.active{background:#0d6efd;color:#fff}.sidebar .logout{color:#ff8d96}.main-content{padding:22px;min-width:0}#contentFrame{display:block;width:100%;height:calc(100vh - 44px);min-height:500px;border:0;background:#fff;border-radius:10px;box-shadow:0 1px 8px #0000000d}@media(max-width:767px){.sidebar{min-height:auto}.main-content{padding:12px}#contentFrame{height:75vh;min-height:400px}}</style></head><body><div class="container-fluid"><div class="row g-0"><aside class="col-12 col-md-3 col-lg-2 sidebar"><div class="brand"><i class="bi bi-shield-check me-2"></i>Sistem Informasi<br>Pelanggaran Siswa</div><div class="role-pill">Akses: <?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8') ?></div><nav class="nav nav-pills flex-column" aria-label="Navigasi utama"><?php foreach($menu as $i=>$item): ?><a class="nav-link <?= $i===0?'active':'' ?>" href="<?= htmlspecialchars($item[1],ENT_QUOTES,'UTF-8') ?>" target="contentFrame"><i class="bi <?= htmlspecialchars($item[2],ENT_QUOTES,'UTF-8') ?>"></i><span><?= htmlspecialchars($item[0],ENT_QUOTES,'UTF-8') ?></span></a><?php endforeach; ?><hr class="text-secondary"><a class="nav-link logout" href="logout.php"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a></nav></aside><main class="col-12 col-md-9 col-lg-10 main-content"><iframe id="contentFrame" name="contentFrame" src="dashboard.php?embed=1" title="Konten halaman"></iframe></main></div></div><script>
(function(){const frame=document.getElementById('contentFrame');const links=document.querySelectorAll('.sidebar a[target="contentFrame"]');links.forEach(a=>a.addEventListener('click',function(){links.forEach(x=>x.classList.remove('active'));this.classList.add('active')}));frame.addEventListener('load',function(){try{const d=frame.contentDocument;if(!d||!d.head)return;if(!d.querySelector('link[data-parent-bootstrap]')){const l=d.createElement('link');l.rel='stylesheet';l.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';l.setAttribute('data-parent-bootstrap','1');d.head.appendChild(l)}if(!d.getElementById('iframe-page-fix')){const s=d.createElement('style');s.id='iframe-page-fix';s.textContent='body{padding:16px!important;margin:0!important;background:#fff!important;font-family:Arial,sans-serif;color:#212529}.sidebar,aside,nav[aria-label="Navigasi utama"]{display:none!important}body>.container-fluid,.container-fluid{max-width:100%!important}h1{font-size:1.8rem;font-weight:600;margin-bottom:1rem}h2{font-size:1.45rem;font-weight:600;margin-top:.5rem}h3{font-size:1.2rem;font-weight:600}table{width:100%;border-collapse:collapse;margin:1rem 0}th,td{padding:.65rem!important;border:1px solid #dee2e6!important;vertical-align:middle}thead th{background:#f8f9fa!important;font-weight:600}input:not([type=hidden]),select,textarea{max-width:100%;padding:.45rem .7rem;border:1px solid #ced4da;border-radius:.375rem}button,input[type=submit],input[type=button]{padding:.45rem .8rem;border-radius:.375rem;border:1px solid #0d6efd;background:#0d6efd;color:#fff}a.btn{display:inline-block;text-decoration:none}';d.head.appendChild(s)}}catch(e){}})})();
</script></body></html>
