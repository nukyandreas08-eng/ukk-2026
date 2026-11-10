<?php
/**
 * ============================================================
 * SIDEBAR BERDASARKAN HAK AKSES
 * ============================================================
 * Sidebar ini dipakai oleh halaman yang menampilkan layout penuh
 * (di luar iframe dashboard). Daftar menu diambil dari
 * includes/cek_akses.php sehingga sama persis dengan menu yang
 * tampil pada dashboard.php.
 *
 * Cara pakai:
 *   require_once 'includes/cek_session.php';
 *   include 'includes/setbar.php';
 */

if (!function_exists('hak_menu_html')) {
    require_once __DIR__ . '/cek_akses.php';
}

$current_page = basename($_SERVER['PHP_SELF']);

// Halaman ini berada di dalam folder includes/, jadi normalkan
// agar perbandingan nama file tetap berjalan.
if (str_contains($current_page, 'setbar.php')) {
    $current_page = 'dashboard.php';
}

$role = hak_role_efektif();
$menu = hak_menu_role($role);
?>
<!-- SIDEBAR -->
<div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
    <h4 class="text-white mb-1">
        Pelanggaran Siswa
    </h4>

    <div class="text-white-50 small mb-4">
        Akses:
        <?= htmlspecialchars(hak_label_role($role), ENT_QUOTES, 'UTF-8') ?>
    </div>

    <ul class="nav nav-pills flex-column">

        <?php foreach ($menu as $item): ?>
            <?php
            $file = $item[1];
            $parameter = $item[3] ?? '';
            $aktif = ($file === $current_page) ? 'active' : 'text-white';
            $alamat = $file . ($parameter !== '' ? '?' . $parameter : '');
            ?>
            <li class="nav-item mb-2">
                <a href="<?= htmlspecialchars($alamat, ENT_QUOTES, 'UTF-8') ?>"
                   class="nav-link <?= $aktif ?>">
                    <i class="bi <?= htmlspecialchars($item[2], ENT_QUOTES, 'UTF-8') ?> me-2"></i>
                    <?= htmlspecialchars($item[0], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </li>
        <?php endforeach; ?>

        <hr class="text-secondary">

        <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </a>
        </li>

    </ul>
</div>
