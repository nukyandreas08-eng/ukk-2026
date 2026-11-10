<?php
/**
 * Memulihkan data t_kelas yang hilang dari dump SQL proyek.
 * Hanya kelas yang belum ada (berdasarkan id) yang dipulihkan.
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

// Ambil data kelas dari dump SQL.
$sql = file_get_contents(__DIR__ . '/db_ukk_2026 (3).sql');

if (!preg_match('/INSERT INTO `t_kelas`.*?VALUES\s*(.*?);\s*\n/s', $sql, $m)) {
    echo 'Gagal membaca data kelas dari dump SQL.' . PHP_EOL;
    exit(1);
}

$baris = [];

foreach (explode("\n", $m[1]) as $b) {
    $b = trim($b);

    if ($b === '' || !preg_match('/^\((\d+),/', $b, $id)) {
        continue;
    }

    // Format: (id, 'nama', 'tingkat', 'jurusan', status, created, updated)
    if (!preg_match("/^\((\d+), '((?:[^'\\\\]|\\\\.)*)', '([^']*)', '([^']*)', (\d+), '([^']*)', '([^']*)'\)/", $b, $d)) {
        continue;
    }

    $baris[] = [
        'id' => (int) $d[1],
        'nama' => stripcslashes($d[2]),
        'tingkat' => $d[3],
        'jurusan' => $d[4],
        'status_aktif' => (int) $d[5],
        'created_at' => $d[6],
        'updated_at' => $d[7],
    ];
}

echo 'Kelas ditemukan di dump SQL: ' . count($baris) . PHP_EOL;

// Cek yang sudah ada.
$ada = [];

$r = mysqli_query($db, 'SELECT id FROM t_kelas');

while ($x = mysqli_fetch_assoc($r)) {
    $ada[(int) $x['id']] = true;
}

echo 'Sudah ada di database   : ' . count($ada) . PHP_EOL;

// Tahun ajaran aktif untuk ditautkan.
$r = mysqli_fetch_assoc(mysqli_query(
    $db,
    "SELECT id FROM t_tahun_ajaran WHERE status_aktif = 1
     ORDER BY (tanggal_mulai <= CURDATE() AND tanggal_selesai >= CURDATE()) DESC, id DESC
     LIMIT 1"
));

$idTahunAktif = (int) ($r['id'] ?? 0);

echo 'Tahun ajaran aktif       : ' . $idTahunAktif . PHP_EOL . PHP_EOL;

// Pulihkan yang hilang.
$stmt = mysqli_prepare(
    $db,
    'INSERT INTO t_kelas
     (id, nama, tingkat, jurusan, tahun_ajaran_id, status_aktif, created_at, updated_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);

$dipulihkan = 0;

foreach ($baris as $k) {
    if (isset($ada[$k['id']])) {
        continue;
    }

    mysqli_stmt_bind_param(
        $stmt,
        'isssiiss',
        $k['id'],
        $k['nama'],
        $k['tingkat'],
        $k['jurusan'],
        $idTahunAktif,
        $k['status_aktif'],
        $k['created_at'],
        $k['updated_at']
    );

    if (mysqli_stmt_execute($stmt)) {
        $dipulihkan++;
    } else {
        echo '  Gagal memulihkan id=' . $k['id'] . ': ' . mysqli_stmt_error($stmt) . PHP_EOL;
    }
}

mysqli_stmt_close($stmt);

echo 'Kelas dipulihkan: ' . $dipulihkan . PHP_EOL . PHP_EOL;

$r = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) AS c FROM t_kelas'));
echo 'Total kelas sekarang: ' . $r['c'] . PHP_EOL;

$r = mysqli_fetch_assoc(mysqli_query(
    $db,
    "SELECT COUNT(*) AS c FROM t_kelas k
     INNER JOIN t_tahun_ajaran ta ON ta.id = k.tahun_ajaran_id AND ta.status_aktif = 1"
));

echo 'Kelas muncul di dropdown penempatan: ' . $r['c'] . PHP_EOL;
