<?php
/**
 * Memulihkan data t_kelas ASLI user dari backup miliknya:
 * C:\Users\ACER16-RPL\Downloads\db_ukk_2026.sql
 */

include __DIR__ . '/config/koneksi.php';

$db = $GLOBALS['koneksi'];

$backup = 'C:/Users/ACER16-RPL/Downloads/db_ukk_2026.sql';

echo '=== MEMULIHKAN t_kelas DARI BACKUP USER ===' . PHP_EOL;

$sql = file_get_contents($backup);

// Catatan: tanda backtick harus ditulis apa adanya di dalam pola.
$pattern = '#INSERT INTO `t_kelas`.*?VALUES\s*(.*?);#s';

if (!preg_match($pattern, $sql, $m)) {
    echo 'GAGAL: pola tidak cocok.' . PHP_EOL;
    exit(1);
}

echo 'Data kelas di backup: ' . strlen($m[1]) . ' karakter' . PHP_EOL;

$baris = [];

foreach (preg_split('/\r?\n/', $m[1]) as $b) {
    $b = trim($b);

    if ($b === '') {
        continue;
    }

    // Pisahkan per-kolom dengan menghormati tanda kutip.
    // Pola: (id, 'nama', 'tingkat', 'jurusan', tahun_ajaran_id, status, created, updated)
    $pola = "/^\(\s*(\d+)\s*,\s*'((?:[^'\\\\]|\\\\.)*)'\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*(NULL|\d+)\s*,\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)$/";

    if (!preg_match($pola, $b, $d)) {
        // Coba pola alternatif: nama tanpa backslash.
        $pola2 = "/^\(\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*(NULL|\d+)\s*,\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)$/";

        if (!preg_match($pola2, $b, $d)) {
            echo '  GAGAL parse: ' . substr($b, 0, 90) . PHP_EOL;
            continue;
        }
    }

    $baris[] = [
        'id' => (int) $d[1],
        'nama' => stripcslashes($d[2]),
        'tingkat' => $d[3],
        'jurusan' => $d[4],
        'tahun_ajaran_id' => ($d[5] === 'NULL') ? null : (int) $d[5],
        'status_aktif' => (int) $d[6],
        'created_at' => $d[7],
        'updated_at' => $d[8],
    ];
}

echo 'Kelas terbaca: ' . count($baris) . PHP_EOL;

if (count($baris) === 0) {
    echo 'GAGAL: tidak ada baris kelas yang terbaca.' . PHP_EOL;
    exit(1);
}

// Tahun ajaran aktif.
$row = mysqli_fetch_assoc(mysqli_query(
    $db,
    "SELECT id FROM t_tahun_ajaran WHERE status_aktif = 1
     ORDER BY (tanggal_mulai <= CURDATE() AND tanggal_selesai >= CURDATE()) DESC, id DESC
     LIMIT 1"
));

$idTahunAktif = (int) ($row['id'] ?? 0);

echo 'Tahun ajaran aktif: ' . $idTahunAktif . PHP_EOL;

// Hapus kelas hasil restore dump proyek.
mysqli_query($db, 'DELETE FROM t_kelas');

echo 'Kelas hasil restore dump proyek dihapus.' . PHP_EOL;

// Masukkan kembali kelas asli.
$stmt = mysqli_prepare(
    $db,
    'INSERT INTO t_kelas
     (id, nama, tingkat, jurusan, tahun_ajaran_id, status_aktif, created_at, updated_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);

$masuk = 0;
$gagal = 0;

foreach ($baris as $k) {
    $tahun = $idTahunAktif > 0 ? $idTahunAktif : $k['tahun_ajaran_id'];

    mysqli_stmt_bind_param(
        $stmt,
        'isssiiss',
        $k['id'],
        $k['nama'],
        $k['tingkat'],
        $k['jurusan'],
        $tahun,
        $k['status_aktif'],
        $k['created_at'],
        $k['updated_at']
    );

    if (mysqli_stmt_execute($stmt)) {
        $masuk++;
    } else {
        $gagal++;
        echo '  Gagal id=' . $k['id'] . ': ' . mysqli_stmt_error($stmt) . PHP_EOL;
    }
}

mysqli_stmt_close($stmt);

echo 'Kelas dipulihkan: ' . $masuk . ($gagal ? ' (gagal: ' . $gagal . ')' : '') . PHP_EOL;

echo PHP_EOL . '=== VERIFIKASI ===' . PHP_EOL;

$row = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) AS c FROM t_kelas'));
echo 'Total kelas          : ' . $row['c'] . PHP_EOL;

$row = mysqli_fetch_assoc(mysqli_query(
    $db,
    "SELECT COUNT(*) AS c FROM t_kelas k
     INNER JOIN t_tahun_ajaran ta ON ta.id = k.tahun_ajaran_id AND ta.status_aktif = 1"
));
echo 'Muncul di dropdown   : ' . $row['c'] . PHP_EOL;

echo PHP_EOL . 'Contoh data:' . PHP_EOL;

$r = mysqli_query($db, 'SELECT id, nama, tingkat, jurusan FROM t_kelas ORDER BY id LIMIT 6');

while ($x = mysqli_fetch_assoc($r)) {
    echo '  ' . implode(' | ', $x) . PHP_EOL;
}

echo PHP_EOL . 'Data lain (harus tetap utuh):' . PHP_EOL;

foreach (['t_siswa', 't_guru', 't_tahun_ajaran', 't_pelanggaran', 't_pelanggaran_kategori', 't_users'] as $t) {
    $row = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) AS c FROM `$t`"));
    echo '  ' . str_pad($t, 26) . $row['c'] . PHP_EOL;
}
