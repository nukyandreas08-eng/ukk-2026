<?php
/**
 * ============================================================
 * DEFINISI TUNGGU HAK AKSES & MENU SIDEBAR
 * ============================================================
 * File ini adalah satu-satunya sumber kebenaran hak akses.
 *
 * - Semua halaman membaca aturan dari sini (dilewatkan oleh
 *   includes/cek_session.php).
 * - Sidebar juga membaca menu dari sini, sehingga menu yang
 *   tampil dijamin sama dengan hak akses yang benar-benar berlaku.
 *
 * Peran yang dikenali:
 * - admin       : administrator sekolah (akses penuh)
 * - wali_kelas  : guru yang ditunjuk sebagai wali kelas
 * - guru        : guru biasa
 */

require_once __DIR__ . '/../config/koneksi.php';

// Seluruh definisi dibungkus agar file ini aman di-include lebih dari
// sekali. Fungsi di dalam blok `if` baru dideklarasikan saat blok
// tersebut dieksekusi, sehingga tidak terjadi fatal error redeclare.
if (!function_exists('hak_daftar_grup')) {

    define('HAK_ADMIN', 'admin');
    define('HAK_WALI_KELAS', 'wali_kelas');
    define('HAK_GURU', 'guru');


    /**
     * ============================================================
     * DAFTAR GRUP HAK AKSES
     * ============================================================
     * Setiap grup menjelaskan siapa saja yang boleh membuka halaman
     * dengan grup tersebut.
     */
    function hak_daftar_grup(): array
    {
        return [
            // Halaman umum.
            'umum' => [HAK_ADMIN, HAK_WALI_KELAS, HAK_GURU],

            // Halaman profil-developer (hanya yang punya menu About Me).
            'about_me' => [HAK_ADMIN],

            // Master data (khusus administrator).
            'master.siswa' => [HAK_ADMIN],
            'master.guru' => [HAK_ADMIN],
            'master.kelas' => [HAK_ADMIN],
            'master.tahun_ajaran' => [HAK_ADMIN],
            'master.wali_kelas' => [HAK_ADMIN],
            'master.penempatan' => [HAK_ADMIN],
            'master.kategori' => [HAK_ADMIN],
            'master.jenis' => [HAK_ADMIN],

            // Manajemen akun login: tidak ada di menu sidebar, sehingga
            // dikunci untuk semua peran.
            'master.akun' => [],

            // Modul pelanggaran.
            // Daftar ini mengikuti menu sidebar masing-masing peran,
            // sehingga menu yang tampil dan halaman yang dapat dibuka
            // selalu sama.
            'catat_pelanggaran' => [HAK_GURU],
            'tindakan' => [HAK_GURU],
            'riwayat' => [HAK_WALI_KELAS, HAK_GURU],
            'rekap_poin' => [HAK_WALI_KELAS, HAK_GURU],
            'laporan' => [HAK_ADMIN, HAK_WALI_KELAS, HAK_GURU],
        ];
    }


    /**
     * ============================================================
     * PETA HALAMAN -> GRUP HAK AKSES
     * ============================================================
     * Semua file yang dapat diakses langsung melalui URL harus
     * terdaftar di sini, termasuk halaman tambah/edit/hapus/proses.
     */
    function hak_peta_halaman(): array
    {
        return [
            // Umum.
            'dashboard.php' => 'umum',
            'about_me.php' => 'about_me',

            // Master data siswa.
            'kelola_siswa.php' => 'master.siswa',
            'edit_siswa.php' => 'master.siswa',
            'hapus_siswa.php' => 'master.siswa',
            'proses_siswa.php' => 'master.siswa',
            'proses_edit_siswa.php' => 'master.siswa',

            // Master data guru.
            'kelola_guru.php' => 'master.guru',
            'edit_guru.php' => 'master.guru',
            'proses_guru.php' => 'master.guru',
            'proses_edit_guru.php' => 'master.guru',

            // Master data kelas.
            'kelola_kelas.php' => 'master.kelas',
            'tambah_kelas.php' => 'master.kelas',
            'edit_kelas.php' => 'master.kelas',
            'hapus_kelas.php' => 'master.kelas',

            // Master data tahun ajaran.
            'kelola_tahun_ajaran.php' => 'master.tahun_ajaran',
            'tambah_tahun_ajaran.php' => 'master.tahun_ajaran',
            'edit_tahun_ajaran.php' => 'master.tahun_ajaran',
            'hapus_tahun_ajaran.php' => 'master.tahun_ajaran',

            // Master data wali kelas.
            'kelola_wali_kelas.php' => 'master.wali_kelas',
            'tambah_wali_kelas.php' => 'master.wali_kelas',
            'edit_wali_kelas.php' => 'master.wali_kelas',
            'hapus_wali_kelas.php' => 'master.wali_kelas',

            // Master data penempatan siswa.
            'kelola_penempatan_siswa.php' => 'master.penempatan',
            'tambah_penempatan_siswa.php' => 'master.penempatan',
            'edit_penempatan_siswa.php' => 'master.penempatan',
            'hapus_penempatan_siswa.php' => 'master.penempatan',

            // Master data kategori pelanggaran.
            'kelola_kategori_pelanggaran.php' => 'master.kategori',
            'tambah_kategori_pelanggaran.php' => 'master.kategori',
            'edit_kategori_pelanggaran.php' => 'master.kategori',
            'hapus_kategori_pelanggaran.php' => 'master.kategori',

            // Master data jenis pelanggaran.
            'kelola_jenis_pelanggaran.php' => 'master.jenis',
            'tambah_jenis_pelanggaran.php' => 'master.jenis',
            'edit_pelanggaran.php' => 'master.jenis',
            'proses_pelanggaran.php' => 'master.jenis',

            // Manajemen akun login.
            'akun.guru.php' => 'master.akun',
            'buat_user_guru.php' => 'master.akun',

            // Modul pelanggaran.
            'catatan_pelanggaran.php' => 'catat_pelanggaran',
            'simpan_catatan_pelanggaran.php' => 'catat_pelanggaran',
            'tindakan.php' => 'tindakan',
            'proses_tindakan.php' => 'tindakan',
            'riwayat.php' => 'riwayat',
            'rekap_point.php' => 'rekap_poin',
            'laporan.php' => 'laporan',
        ];
    }


    /**
     * ============================================================
     * MENU SIDEBAR PER PERAN
     * ============================================================
     * Struktur: [label, file, ikon bootstrap-icons, parameter_opsional]
     *
     * - File pada menu harus sama dengan kunci pada hak_peta_halaman()
     *   supaya hak akses menu selalu sinkron dengan halaman tujuan.
     * - Parameter opsional ditambahkan ke alamat, misalnya 'cetak=1'.
     */
    function hak_daftar_menu(): array
    {
        return [
            HAK_ADMIN => [
                ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
                ['Kelola Guru', 'kelola_guru.php', 'bi-person-badge'],
                ['Kelola Siswa', 'kelola_siswa.php', 'bi-people'],
                ['Kelola Kelas', 'kelola_kelas.php', 'bi-building'],
                ['Kelola Tahun Ajaran', 'kelola_tahun_ajaran.php', 'bi-calendar3'],
                ['Penempatan Siswa', 'kelola_penempatan_siswa.php', 'bi-diagram-3'],
                ['Wali Kelas', 'kelola_wali_kelas.php', 'bi-person-workspace'],
                ['Kategori Pelanggaran', 'kelola_kategori_pelanggaran.php', 'bi-tags'],
                ['Jenis Pelanggaran', 'kelola_jenis_pelanggaran.php', 'bi-list-check'],
                ['Laporan', 'laporan.php', 'bi-file-earmark-text'],
                ['Cetak / Export', 'laporan.php', 'bi-printer', 'cetak=1'],
                ['About Me', 'about_me.php', 'bi-person-circle'],
            ],

            HAK_WALI_KELAS => [
                ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
                ['Riwayat Pelanggaran', 'riwayat.php', 'bi-clock-history'],
                ['Rekap Poin', 'rekap_point.php', 'bi-bar-chart'],
                ['Laporan', 'laporan.php', 'bi-file-earmark-text'],
            ],

            HAK_GURU => [
                ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
                ['Catat Pelanggaran', 'catatan_pelanggaran.php', 'bi-journal-plus'],
                ['Tindakan', 'tindakan.php', 'bi-clipboard-check'],
                ['Riwayat Pelanggaran', 'riwayat.php', 'bi-clock-history'],
                ['Laporan', 'laporan.php', 'bi-file-earmark-text'],
                ['Rekap Poin', 'rekap_point.php', 'bi-bar-chart'],
            ],
        ];
    }


    /**
     * ============================================================
     * MENCARI HAK AKSES
     * ============================================================
     */

    /**
     * Menentukan apakah akun yang sedang login adalah wali kelas.
     * Status wali kelas diambil dari relasi t_wali_kelas -> t_guru,
     * bukan dari kolom role pada t_users.
     */
    function hak_adalah_wali_kelas(): bool
    {
        $userId = (int) ($_SESSION['id'] ?? 0);

        if ($userId <= 0) {
            return false;
        }

        static $cache = [];

        if (isset($cache[$userId])) {
            return $cache[$userId];
        }

        $db = $GLOBALS['koneksi'] ?? null;

        if (!($db instanceof mysqli)) {
            return $cache[$userId] = false;
        }

        $stmt = mysqli_prepare(
            $db,
            "SELECT 1
             FROM t_wali_kelas wk
             INNER JOIN t_guru g ON g.id = wk.guru_id
             WHERE g.user_id = ? AND wk.status_aktif = 1
             LIMIT 1"
        );

        if (!$stmt) {
            return $cache[$userId] = false;
        }

        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);

        $ada = mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;

        mysqli_stmt_close($stmt);

        return $cache[$userId] = $ada;
    }


    /**
     * Peran efektif pengguna yang sedang login.
     */
    function hak_role_efektif(): string
    {
        $role = strtolower(trim((string) ($_SESSION['role'] ?? '')));

        if (in_array($role, ['admin', 'administrator', 'superadmin'], true)) {
            return HAK_ADMIN;
        }

        if (in_array($role, ['wali_kelas', 'wali kelas', 'walikelas', 'wali-kelas'], true)) {
            return HAK_WALI_KELAS;
        }

        if (hak_adalah_wali_kelas()) {
            return HAK_WALI_KELAS;
        }

        return HAK_GURU;
    }


    /**
     * Label peran untuk ditampilkan pada sidebar.
     */
    function hak_label_role(?string $role = null): string
    {
        $role = $role ?? hak_role_efektif();

        switch ($role) {
            case HAK_ADMIN:
                return 'Administrator';

            case HAK_WALI_KELAS:
                return 'Wali Kelas';

            default:
                return 'Guru';
        }
    }


    /**
     * Menu sidebar sesuai peran pengguna.
     */
    function hak_menu_role(?string $role = null): array
    {
        $role = $role ?? hak_role_efektif();
        $semuaMenu = hak_daftar_menu();

        return $semuaMenu[$role] ?? $semuaMenu[HAK_GURU];
    }


    /**
     * Apakah peran tertentu boleh membuka halaman/file tertentu?
     */
    function hak_halaman_boleh(string $file, ?string $role = null): bool
    {
        $role = $role ?? hak_role_efektif();
        $peta = hak_peta_halaman();

        // File yang tidak terdaftar tetap boleh dibuka (mis. halaman login).
        if (!isset($peta[$file])) {
            return true;
        }

        $grup = hak_daftar_grup()[$peta[$file]] ?? [];

        return in_array($role, $grup, true);
    }


    /**
     * ============================================================
     * PENERAPAN HAK AKSES UNTUK PERMINTAAN SAAT INI
     * ============================================================
     * Dipanggil otomatis oleh includes/cek_session.php.
     */
    function hak_cek_permintaan(): void
    {
        $file = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($file === '' || $file === 'cek_akses.php') {
            return;
        }

        if (hak_halaman_boleh($file)) {
            return;
        }

        $_SESSION['pesan_akses'] =
            'Hak akses Anda tidak mencukupi untuk membuka halaman tersebut.';

        $embed = (($_GET['embed'] ?? '') === '1') ? '?embed=1' : '';

        header('Location: dashboard.php' . $embed);
        exit;
    }


    /**
     * ============================================================
     * MENU HTML
     * ============================================================
     * Menghasilkan daftar tautan sidebar. Dipakai oleh dashboard.php
     * dan includes/setbar.php sehingga keduanya selalu sama.
     */
    function hak_menu_html(?array $menu = null, ?string $fileAktif = null): string
    {
        $menu = $menu ?? hak_menu_role();
        $fileAktif = $fileAktif ?? 'dashboard.php';

        $html = '';

        foreach ($menu as $item) {
            [$label, $file, $ikon] = [$item[0], $item[1], $item[2]];

            // Parameter opsional, misalnya 'cetak=1'.
            $parameter = $item[3] ?? '';

            $aktif = ($file === $fileAktif) ? ' active' : '';

            $alamat = $file . '?embed=1'
                . ($parameter !== '' ? '&' . $parameter : '');

            // Kunci unik untuk penanda menu aktif. Menu "Laporan" dan
            // "Cetak / Export" menunjuk file yang sama, sehingga perlu
            // dibedakan lewat parameter tambahan.
            $kunci = $file . ($parameter !== '' ? '&' . $parameter : '');

            $html .= '<a class="nav-link' . $aktif . '"'
                . ' href="' . htmlspecialchars($alamat, ENT_QUOTES, 'UTF-8') . '"'
                . ' data-file="' . htmlspecialchars($kunci, ENT_QUOTES, 'UTF-8') . '"'
                . ' target="contentFrame">'
                . '<i class="bi ' . htmlspecialchars($ikon, ENT_QUOTES, 'UTF-8') . '"></i>'
                . '<span>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>'
                . '</a>';
        }

        return $html;
    }
}
