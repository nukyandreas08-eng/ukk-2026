<?php
/**
 * Menelusuri apakah tabel anak (t_kelas_siswa / t_wali_kelas) pernah
 * berisi data kelas yang memicu CASCADE.
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

echo '=== t_kelas_siswa ===' . PHP_EOL;

$r = mysqli_query($db, 'SELECT * FROM t_kelas_siswa');

echo '  jumlah baris: ' . mysqli_num_rows($r) . PHP_EOL;

while ($x = mysqli_fetch_assoc($r)) {
    echo '  ' . implode(' | ', $x) . PHP_EOL;
}

echo PHP_EOL . '=== t_wali_kelas ===' . PHP_EOL;

$r = mysqli_query($db, 'SELECT * FROM t_wali_kelas');

echo '  jumlah baris: ' . mysqli_num_rows($r) . PHP_EOL;

while ($x = mysqli_fetch_assoc($r)) {
    echo '  ' . implode(' | ', $x) . PHP_EOL;
}

echo PHP_EOL . '=== AUTO_INCREMENT t_kelas_siswa / t_wali_kelas ===' . PHP_EOL;

foreach (['t_kelas_siswa', 't_wali_kelas'] as $t) {
    $r = mysqli_query($db, "SHOW TABLE STATUS LIKE '$t'");

    $x = mysqli_fetch_assoc($r);

    echo '  ' . str_pad($t, 18) . ' auto_increment=' . $x['Auto_increment']
        . ' update_time=' . $x['Update_time'] . PHP_EOL;
}

echo PHP_EOL . '=== KELAS: WAKTU CREATE ===' . PHP_EOL;

$r = mysqli_query($db, 'SELECT id, nama, created_at FROM t_kelas ORDER BY id');

while ($x = mysqli_fetch_assoc($r)) {
    echo '  ' . str_pad($x['id'], 4) . str_pad($x['nama'], 18) . $x['created_at'] . PHP_EOL;
}
