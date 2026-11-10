<?php
/**
 * Menguji apakah menghapus t_tahun_ajaran ikut menghapus t_kelas.
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

mysqli_query($db, "SET GLOBAL general_log = ON");
mysqli_query($db, "SET GLOBAL log_output = 'TABLE'");
mysqli_query($db, 'TRUNCATE TABLE mysql.general_log');

echo '=== SETUP UJI ===' . PHP_EOL;

mysqli_query($db, "DELETE FROM t_tahun_ajaran WHERE nama = 'UJI CASCADE'");

mysqli_query($db, "INSERT INTO t_tahun_ajaran
    (nama, tanggal_mulai, tanggal_selesai, status_aktif)
    VALUES ('UJI CASCADE', '2026-07-01', '2027-06-30', 0)");

$idTahun = mysqli_insert_id($db);

echo 'tahun ajaran uji id = ' . $idTahun . PHP_EOL;

for ($i = 1; $i <= 3; $i++) {
    mysqli_query($db, "INSERT INTO t_kelas
        (nama, tingkat, jurusan, tahun_ajaran_id, status_aktif)
        VALUES ('UJI KELAS $i', 'XI', 'RPL', $idTahun, 1)");
}

$r = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) AS c FROM t_kelas WHERE tahun_ajaran_id = ' . $idTahun));

echo 'kelas uji sebelum hapus: ' . $r['c'] . PHP_EOL;

echo PHP_EOL . '=== MENGHAPUS TAHUN AJARAN UJI ===' . PHP_EOL;

mysqli_query($db, 'DELETE FROM t_tahun_ajaran WHERE id = ' . $idTahun);

$r = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) AS c FROM t_kelas WHERE tahun_ajaran_id = ' . $idTahun));

echo 'kelas dengan tahun_ajaran_id=' . $idTahun . ' sesudah: ' . $r['c'] . PHP_EOL;

$r = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) AS c FROM t_kelas WHERE nama LIKE 'UJI KELAS%'"));

echo 'total kelas uji tersisa: ' . $r['c'] . PHP_EOL;

if ((int) $r['c'] === 0) {
    echo PHP_EOL . '>>> HASIL: t_kelas IKUT TERHAPUS (CASCADE) <<<' . PHP_EOL;
} else {
    echo PHP_EOL . '>>> HASIL: t_kelas TETAP ADA (SET NULL) <<<' . PHP_EOL;

    $r = mysqli_fetch_assoc(mysqli_query($db, "SELECT tahun_ajaran_id FROM t_kelas WHERE nama LIKE 'UJI KELAS%' LIMIT 1"));

    echo '    tahun_ajaran_id sekarang: ' . var_export($r['tahun_ajaran_id'] ?? null, true) . PHP_EOL;
}

mysqli_query($db, "DELETE FROM t_kelas WHERE nama LIKE 'UJI KELAS%'");

echo PHP_EOL . 'data uji dibersihkan' . PHP_EOL;
