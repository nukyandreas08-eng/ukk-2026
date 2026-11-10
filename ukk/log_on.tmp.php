<?php
/**
 * Mengaktifkan general log MySQL sementara agar setiap SQL
 * yang masuk ke server tercatat. Dipakai untuk melacak
 * penghapusan data t_kelas.
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

mysqli_query($db, "SET GLOBAL log_output = 'TABLE'");
mysqli_query($db, 'SET GLOBAL general_log = ON');

echo 'general_log = ' .mysqli_fetch_assoc(
    mysqli_query($db, "SHOW VARIABLES LIKE 'general_log'")
)['Value'] . PHP_EOL;

echo 'log_output = ' . mysqli_fetch_assoc(
    mysqli_query($db, "SHOW VARIABLES LIKE 'log_output'")
)['Value'] . PHP_EOL;

echo PHP_EOL . 'Tabel log: mysql.general_log' . PHP_EOL;
echo 'Query log yang tercatat: ' . mysqli_fetch_assoc(
    mysqli_query($db, 'SELECT COUNT(*) AS c FROM mysql.general_log')
)['c'] . PHP_EOL;
