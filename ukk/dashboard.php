<?php
// Memeriksa sesi login dan hak akses
include 'includes/cek_session.php';

// Mengambil informasi pengguna
$role = hak_role_efektif();
$roleLabel = hak_label_role($role);
$namaPengguna = $_SESSION['name'] ?? 'Pengguna';


// ========================================
// FUNGSI MENGHITUNG JUMLAH DATA
// ========================================
function jumlahDataDashboard($koneksi, $tabel)
{
    // Tabel yang boleh dihitung
    $daftarTabel = [
        't_siswa',
        't_guru',
        't_kelas',
        't_pelanggaran'
    ];

    if (!in_array($tabel, $daftarTabel, true)) {
        return 0;
    }

    $query = mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM `$tabel`"
    );

    if (!$query) {
        return 0;
    }

    $data = mysqli_fetch_assoc($query);

    return (int) ($data['total'] ?? 0);
}


// ========================================
// STATISTIK SESUAI HAK AKSES
// ========================================
function statistikDashboard($koneksi, string $role): array
{
    $statistik = [];

    if ($role === HAK_ADMIN) {
        // Statistik sesuai menu admin: siswa, guru, kelas, dan
        // jenis pelanggaran.
        $statistik = [
            ['Data Siswa', jumlahDataDashboard($koneksi, 't_siswa'), 'primary'],
            ['Data Guru', jumlahDataDashboard($koneksi, 't_guru'), 'success'],
            ['Data Kelas', jumlahDataDashboard($koneksi, 't_kelas'), 'warning'],
            ['Jenis Pelanggaran', jumlahDataDashboard($koneksi, 't_pelanggaran'), 'danger'],
        ];

        return $statistik;
    }

    // Guru dan wali kelas: fokus pada catatan pelanggaran siswa.
    $totalCatatan = 0;
    $totalPoin = 0;
    $siswaBermasalah = 0;
    $totalSiswa = 0;

    $query = mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total_catatan,
                COALESCE(SUM(poin), 0) AS total_poin,
                COUNT(DISTINCT siswa_id) AS siswa_bermasalah
         FROM t_pelanggaran_siswa"
    );

    if ($query) {
        $data = mysqli_fetch_assoc($query);

        $totalCatatan = (int) ($data['total_catatan'] ?? 0);
        $totalPoin = (int) ($data['total_poin'] ?? 0);
        $siswaBermasalah = (int) ($data['siswa_bermasalah'] ?? 0);
    }

    $querySiswa = mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM t_siswa WHERE status_aktif = 1"
    );

    if ($querySiswa) {
        $data = mysqli_fetch_assoc($querySiswa);

        $totalSiswa = (int) ($data['total'] ?? 0);
    }

    $statistik = [
        ['Catatan Pelanggaran', $totalCatatan, 'danger'],
        ['Total Poin', $totalPoin, 'primary'],
        ['Siswa Bermasalah', $siswaBermasalah, 'warning'],
        ['Siswa Aktif', $totalSiswa, 'success'],
    ];

    return $statistik;
}


// ========================================
// HALAMAN DASHBOARD EMBED
// ========================================
if (isset($_GET['embed']) && $_GET['embed'] === '1') {

    include 'config/koneksi.php';

    $statistik = statistikDashboard($koneksi, $role);
    ?>

    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >

        <title>Dashboard</title>
    </head>

    <body class="bg-light">
        <main class="container-fluid p-0">

            <?php if (!empty($_SESSION['pesan_akses'])): ?>
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <?= htmlspecialchars($_SESSION['pesan_akses'], ENT_QUOTES, 'UTF-8') ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Tutup"></button>
                </div>
                <?php unset($_SESSION['pesan_akses']); ?>
            <?php endif; ?>

            <div class="mb-4">
                <h2 class="fw-bold">Dashboard</h2>

                <p class="text-secondary mb-0">
                    Selamat datang,
                    <?= htmlspecialchars($namaPengguna, ENT_QUOTES, 'UTF-8') ?>.
                    Berikut ringkasan Sistem Informasi Pelanggaran Siswa.
                </p>

                <p class="text-secondary mb-0">
                    Hak akses:
                    <span class="badge text-bg-secondary">
                        <?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </p>
            </div>

            <div class="row g-3">

                <?php foreach ($statistik as $item): ?>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-secondary"><?= htmlspecialchars($item[0], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="display-6 fw-semibold text-<?= htmlspecialchars($item[2], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= (int) $item[1] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="fw-semibold">Alur penggunaan</h5>

                    <p class="mb-0 text-secondary">
                        Pilih menu di sidebar untuk mengelola data,
                        mencatat pelanggaran, melihat riwayat, serta
                        mencetak laporan sesuai hak akses akun.
                    </p>
                </div>
            </div>

        </main>
    </body>
    </html>

    <?php
    exit;
}


// ========================================
// MENU SIDEBAR BERDASARKAN HAK AKSES
// ========================================
// Menu diambil dari includes/cek_akses.php agar menu sidebar
// selalu sama dengan halaman yang boleh dibuka oleh pengguna.
$menu = hak_menu_role($role);
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Informasi Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        html, body {
            margin: 0;
            min-height: 100%;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .sidebar {
            background: #212529;
            min-height: 100vh;
            padding: 18px 12px;
        }

        .brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1.35;
            padding: 4px 10px 20px;
        }

        .role-pill {
            font-size: .75rem;
            color: #adb5bd;
            padding: 0 10px 12px;
        }

        .sidebar .nav-link {
            display: flex;
            gap: 10px;
            align-items: center;
            color: #e9ecef;
            border-radius: 7px;
            padding: 10px 12px;
            margin-bottom: 4px;
            font-size: .92rem;
        }

        .sidebar .nav-link:hover {
            background: #343a40;
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: #0d6efd;
            color: #fff;
        }

        .sidebar .logout {
            color: #ff8d96;
        }

        .main-content {
            padding: 22px;
            min-width: 0;
        }

        #contentFrame {
            display: block;
            width: 100%;
            height: calc(100vh - 44px);
            min-height: 500px;
            border: 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 8px #0000000d;
        }

        @media (max-width: 767px) {
            .sidebar {
                min-height: auto;
            }

            .main-content {
                padding: 12px;
            }

            #contentFrame {
                height: 75vh;
                min-height: 400px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row g-0">

        <!-- SIDEBAR -->
        <aside class="col-12 col-md-3 col-lg-2 sidebar">

            <div class="brand">
                <i class="bi bi-shield-check me-2"></i>
                Sistem Informasi<br>
                Pelanggaran Siswa
            </div>

            <div class="role-pill">
                Akses:
                <strong><?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <nav class="nav nav-pills flex-column" aria-label="Navigasi utama">

                <?= hak_menu_html($menu, 'dashboard.php') ?>

                <hr class="text-secondary">

                <a class="nav-link logout" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>

            </nav>
        </aside>


        <!-- KONTEN UTAMA -->
        <main class="col-12 col-md-9 col-lg-10 main-content">

            <iframe
                id="contentFrame"
                name="contentFrame"
                src="dashboard.php?embed=1"
                title="Konten halaman"
            ></iframe>

        </main>

    </div>
</div>


<!-- JAVASCRIPT UNTUK NAVIGASI DAN TAMPILAN IFRAME -->
<script>
const frame = document.getElementById('contentFrame');

const links = document.querySelectorAll('.sidebar a[target="contentFrame"]');


// Mengubah warna menu yang dipilih
links.forEach(function(link) {

    link.addEventListener('click', function() {

        links.forEach(function(item) {
            item.classList.remove('active');
        });

        this.classList.add('active');

    });

});


// Menyorot menu sesuai halaman yang sedang dimuat di iframe
function aktifkanMenuSesuaiFrame() {

    let sumber = frame.src || frame.getAttribute('src') || '';
    try { sumber = frame.contentWindow.location.href || sumber; } catch (e) {}
    try { sumber = new URL(sumber, window.location.href).pathname.split('/').pop() + new URL(sumber, window.location.href).search; } catch (e) {}

    const bagian = sumber.split('?');

    const namaFile = bagian[0].split('/').pop();

    if (!namaFile) {
        return;
    }

    // Parameter selain "embed" dipakai untuk membedakan menu yang
    // menunjuk file sama, misalnya Laporan dan Cetak / Export.
    const parameter = (bagian[1] || '')
        .split('&')
        .filter(function(item) {
            return item && item.indexOf('embed=') === -1;
        })
        .join('&');

    const kunci = namaFile + (parameter ? '&' + parameter : '');

    links.forEach(function(item) {

        item.classList.remove('active');

        if (item.getAttribute('data-file') === kunci) {
            item.classList.add('active');
        }

    });

}


// Menyesuaikan tampilan halaman di dalam iframe
frame.addEventListener('load', function() {

    aktifkanMenuSesuaiFrame();

    try {

        const halaman = frame.contentDocument;

        if (!halaman || !halaman.head) {
            return;
        }

        // Semua tautan internal dan formulir dari halaman konten tetap
        // dibuka di iframe, bukan mengganti halaman dashboard utama.
        halaman.querySelectorAll('a[href]').forEach(function (a) {
            const href = a.getAttribute('href') || '';
            if (href && !href.startsWith('#') && !/^(https?:|mailto:|tel:|javascript:)/i.test(href)) {
                a.setAttribute('target', 'contentFrame');
            }
        });
        halaman.querySelectorAll('form').forEach(function (form) {
            if (!form.hasAttribute('target')) form.setAttribute('target', 'contentFrame');
        });


        // Menambahkan Bootstrap jika belum tersedia
        if (!halaman.querySelector('link[data-parent-bootstrap]')) {

            const bootstrap = halaman.createElement('link');

            bootstrap.rel = 'stylesheet';

            bootstrap.href =
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';

            bootstrap.setAttribute('data-parent-bootstrap', '1');

            halaman.head.appendChild(bootstrap);

        }


        // Menambahkan penyesuaian tampilan satu kali
        if (!halaman.getElementById('iframe-page-fix')) {

            const style = halaman.createElement('style');

            style.id = 'iframe-page-fix';

            style.textContent = `
                body {
                    padding: 16px !important;
                    margin: 0 !important;
                    background: #fff !important;
                    font-family: Arial, sans-serif;
                    color: #212529;
                }

                .sidebar,
                aside,
                nav[aria-label="Navigasi utama"] {
                    display: none !important;
                }

                body > .container-fluid,
                .container-fluid {
                    max-width: 100% !important;
                }

                h1 {
                    font-size: 1.8rem;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }

                h2 {
                    font-size: 1.45rem;
                    font-weight: 600;
                    margin-top: .5rem;
                }

                h3 {
                    font-size: 1.2rem;
                    font-weight: 600;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 1rem 0;
                }

                th, td {
                    padding: .65rem !important;
                    border: 1px solid #dee2e6 !important;
                    vertical-align: middle;
                }

                thead th {
                    background: #f8f9fa !important;
                    font-weight: 600;
                }

                input:not([type=hidden]),
                select,
                textarea {
                    max-width: 100%;
                    padding: .45rem .7rem;
                    border: 1px solid #ced4da;
                    border-radius: .375rem;
                }

                button,
                input[type=submit],
                input[type=button] {
                    padding: .45rem .8rem;
                    border-radius: .375rem;
                    border: 1px solid #0d6efd;
                    background: #0d6efd;
                    color: #fff;
                }

                a.btn {
                    display: inline-block;
                    text-decoration: none;
                }
            `;

            halaman.head.appendChild(style);

        }

    } catch (error) {

        console.log('Penyesuaian iframe tidak dapat diterapkan.');

    }

});
</script>

</body>
</html>