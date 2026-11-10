<?php
/**
 * Melihat seluruh DELETE yang pernah tercatat di general log.
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

$r = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) AS c FROM mysql.general_log'));

echo 'Total baris log: ' . $r['c'] . PHP_EOL . PHP_EOL;

echo '=== SEMUA DELETE ===' . PHP_EOL;

$r = mysqli_query(
    $db,
    "SELECT event_time, argument
     FROM mysql.general_log
     WHERE command_type = 'Query' AND argument LIKE 'DELETE%'
     ORDER BY event_time"
);

$n = 0;

while ($x = mysqli_fetch_assoc($r)) {
    echo '  [' . $x['event_time'] . '] '
        . substr(preg_replace('/\s+/', ' ', $x['argument']), 0, 200) . PHP_EOL;

    $n++;
}

if ($n === 0) {
    echo '  (tidak ada)' . PHP_EOL;
}

echo PHP_EOL . '=== QUERY YANG MENYENTUH t_kelas (selain SELECT/INSERT) ===' . PHP_EOL;

$r = mysqli_query(
    $db,
    "SELECT event_time, command_type, argument
     FROM mysql.general_log
     WHERE argument LIKE '%t_kelas%'
       AND command_type = 'Execute'
     ORDER BY event_time"
);

$n = 0;

while ($x = mysqli_fetch_assoc($r)) {
    echo '  [' . $x['event_time'] . '] '
        . substr(preg_replace('/\s+/', ' ', $x['argument']), 0, 200) . PHP_EOL;

    $n++;
}

if ($n === 0) {
    echo '  (tidak ada)' . PHP_EOL;
}
