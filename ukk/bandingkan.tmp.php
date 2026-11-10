<?php
/**
 * Membandingkan isi database live dengan dump SQL proyek,
 * untuk menentukan sumber data yang benar.
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

$sql = file_get_contents(__DIR__ . '/db_ukk_2026 (3).sql');

echo '=== PERBANDINGAN LIVE vs DUMP ===' . PHP_EOL;
echo str_pad('Tabel', 26) . str_pad('Live', 8) . str_pad('Dump', 8) . ' Status' . PHP_EOL;

$tabel = [
    't_siswa', 't_guru', 't_kelas', 't_kelas_siswa', 't_wali_kelas',
    't_tahun_ajaran', 't_pelanggaran', 't_pelanggaran_kategori',
    't_pelanggaran_siswa', 't_users',
];

foreach ($tabel as $t) {
    $r = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) AS c FROM `$t`"));
    $live = (int) $r['c'];

    $m = preg_match("/INSERT INTO ``$t``.*?VALUES\s*(.*?);\s*\n/s", $sql, $mm);

    if ($m) {
        $dump = substr_count($mm[1], "),\r\n") + substr_count($mm[1], "),\n") + 1;
    } else {
        $dump = 0;
    }

    $status = $live === $dump ? 'SAMA' : 'BEDA';

    echo str_pad($t, 26) . str_pad($live, 8) . str_pad($dump, 8) . ' ' . $status . PHP_EOL;
}

echo PHP_EOL . '=== CONTOH DATA SISWA (live vs dump) ===' . PHP_EOL;

$r = mysqli_query($db, 'SELECT id, nis, nama FROM t_siswa ORDER BY id LIMIT 3');

while ($x = mysqli_fetch_assoc($r)) {
    echo '  live : ' . implode(' | ', $x) . PHP_EOL;
}

if (preg_match("/INSERT INTO ``t_siswa``.*?VALUES\s*(.*?);\s*\n/s", $sql, $m)) {
    $baris = array_values(array_filter(array_map('trim', explode("\n", $m[1]))));

    foreach (array_slice($baris, 0, 3) as $b) {
        echo '  dump : ' . $b . PHP_EOL;
    }
}

echo PHP_EOL . '=== CONTOH DATA GURU (live vs dump) ===' . PHP_EOL;

$r = mysqli_query($db, 'SELECT id, nip, nama FROM t_guru ORDER BY id LIMIT 2');

while ($x = mysqli_fetch_assoc($r)) {
    echo '  live : ' . implode(' | ', $x) . PHP_EOL;
}

if (preg_match("/INSERT INTO ``t_guru``.*?VALUES\s*(.*?);\s*\n/s", $sql, $m)) {
    $baris = array_values(array_filter(array_map('trim', explode("\n", $m[1]))));

    foreach (array_slice($baris, 0, 2) as $b) {
        echo '  dump : ' . $b . PHP_EOL;
    }
}

echo PHP_EOL . '=== TAHUN AJARAN YANG DIPAKAI t_kelas_siswa DI DUMP ===' . PHP_EOL;

if (preg_match("/INSERT INTO ``t_kelas_siswa``.*?VALUES\s*(.*?);\s*\n/s", $sql, $m)) {
    $baris = array_values(array_filter(array_map('trim', explode("\n", $m[1]))));

    $ids = [];

    foreach ($baris as $b) {
        if (preg_match('/^\((\d+), (\d+), (\d+), (\d+),/', $b, $d)) {
            $ids[(int) $d[4]] = ($ids[(int) $d[4]] ?? 0) + 1;
        }
    }

    foreach ($ids as $id => $jml) {
        $r = mysqli_fetch_assoc(mysqli_query(
            $db,
            'SELECT nama, status_aktif FROM t_tahun_ajaran WHERE id = ' . $id
        ));

        echo '  tahun_ajaran_id=' . $id . ' (' . $jml . ' baris) -> '
            . ($r['nama'] ?? 'TIDAK ADA') . ' status=' . ($r['status_aktif'] ?? '-') . PHP_EOL;
    }
}

echo PHP_EOL . '=== TAHUN AJARAN YANG DIPAKAI t_kelas DI DUMP ===' . PHP_EOL;

echo '  (t_kelas di dump tidak punya kolom tahun_ajaran_id - kolomnya ditambahkan lewat MIGRASI)' . PHP_EOL;
