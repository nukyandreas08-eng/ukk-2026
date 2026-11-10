<?php
/**
 * Reproduksi dengan general log aktif untuk menemukan query
 * yang menghapus banyak baris t_kelas.
 */

$base = 'http://localhost/ukk-2026/ukk/';

function jar(string $n): string
{
    return sys_get_temp_dir() . '/ukk_repro_' . $n . '.txt';
}

function req(string $url, ?string $j, array $post = null): array
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER => true,
        CURLOPT_COOKIEJAR => $j,
        CURLOPT_COOKIEFILE => $j,
        CURLOPT_TIMEOUT => 30,
    ]);

    if ($post !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }

    $raw = curl_exec($ch);
    $hs = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $st = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $loc = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

    curl_close($ch);

    return ['s' => $st, 'l' => $loc, 'b' => substr((string) $raw, $hs)];
}

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

$j = jar('a');

@unlink($j);

req($base . 'login.php', $j);
req($base . 'proses_login.php', $j, [
    'login' => '1', 'username' => 'administrator', 'password' => 'admin123',
]);

function hitungKelas($db)
{
    $r = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) AS c FROM t_kelas'));

    return (int) $r['c'];
}

echo '=== SEBELUM ===' . PHP_EOL;
echo 'kelas: ' . hitungKelas($db) . PHP_EOL;

mysqli_query($db, 'TRUNCATE TABLE mysql.general_log');

// Reproduksi semua halaman yangGlyang ada di menu admin.
$halaman = [
    'dashboard.php', 'kelola_guru.php', 'kelola_siswa.php', 'kelola_kelas.php',
    'kelola_tahun_ajaran.php', 'kelola_penempatan_siswa.php',
    'kelola_wali_kelas.php', 'kelola_kategori_pelanggaran.php',
    'kelola_jenis_pelanggaran.php', 'laporan.php',
    'tambah_penempatan_siswa.php', 'tambah_wali_kelas.php',
    'tambah_kategori_pelanggaran.php', 'tambah_jenis_pelanggaran.php',
    'tambah_tahun_ajaran.php', 'tambah_kelas.php',
    'edit_kelas.php?id=1', 'edit_tahun_ajaran.php?id=25',
    'edit_wali_kelas.php?id=1', 'edit_penempatan_siswa.php?id=1',
];

foreach ($halaman as $f) {
    $res = req($base . $f . '?embed=1', $j);

    echo '  ' . str_pad($f, 34) . ' status ' . $res['s'] . PHP_EOL;
}

echo PHP_EOL . '=== SESUDAH ===' . PHP_EOL;
echo 'kelas: ' . hitungKelas($db) . PHP_EOL;

echo PHP_EOL . '=== DELETE TERCATAT ===' . PHP_EOL;

$r = mysqli_query(
    $db,
    "SELECT event_time, argument FROM mysql.general_log
     WHERE command_type = 'Query' AND argument LIKE 'DELETE%'
     ORDER BY event_time"
);

$n = 0;

while ($x = mysqli_fetch_assoc($r)) {
    echo '  [' . $x['event_time'] . '] ' . substr(preg_replace('/\s+/', ' ', $x['argument']), 0, 200) . PHP_EOL;
    $n++;
}

if ($n === 0) {
    echo '  (tidak ada DELETE)' . PHP_EOL;
}

@unlink($j);
