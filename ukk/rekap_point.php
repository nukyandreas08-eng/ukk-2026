<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
include 'includes/tahun_ajaran.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

// Filter tahun ajaran dan tanggal. Tanggal otomatis mengikuti tahun ajaran
// yang dipilih, tetapi tetap dapat diubah manual oleh pengguna.
$tahun_ajaran_id = isset($_GET['tahun_ajaran_id'])
    ? (int) $_GET['tahun_ajaran_id']
    : tahun_ajaran_aktif_id($koneksi);

$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama, tanggal_mulai, tanggal_selesai
     FROM t_tahun_ajaran
     ORDER BY status_aktif DESC, tanggal_mulai DESC, id DESC"
);
if (!$query_tahun) {
    die('Gagal mengambil data tahun ajaran: ' . mysqli_error($koneksi));
}

$daftarTahun = [];
$tahunTerpilih = null;
while ($ta = mysqli_fetch_assoc($query_tahun)) {
    $daftarTahun[] = $ta;
    if ((int)$ta['id'] === $tahun_ajaran_id) {
        $tahunTerpilih = $ta;
    }
}

// Jika ID tahun ajaran tidak ditemukan, gunakan tahun aktif yang tersedia.
if (!$tahunTerpilih && !isset($_GET['tahun_ajaran_id'])) {
    foreach ($daftarTahun as $ta) {
        if ((int)$ta['id'] === $tahun_ajaran_id) {
            $tahunTerpilih = $ta;
            break;
        }
    }
}

function tanggal_valid($tanggal) {
    if (!$tanggal || !preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $tanggal)) {
        return false;
    }
    $d = DateTime::createFromFormat('Y-m-d', $tanggal);
    return $d && $d->format('Y-m-d') === $tanggal;
}

$tanggal_mulai = isset($_GET['tanggal_mulai'])
    ? trim($_GET['tanggal_mulai'])
    : ($tahunTerpilih['tanggal_mulai'] ?? '');
$tanggal_selesai = isset($_GET['tanggal_selesai'])
    ? trim($_GET['tanggal_selesai'])
    : ($tahunTerpilih['tanggal_selesai'] ?? '');

if (!tanggal_valid($tanggal_mulai)) $tanggal_mulai = '';
if (!tanggal_valid($tanggal_selesai)) $tanggal_selesai = '';
if ($tanggal_mulai && $tanggal_selesai && $tanggal_mulai > $tanggal_selesai) {
    $tanggal_mulai = '';
    $tanggal_selesai = '';
}

$role = hak_role_efektif();
$filterWali = '';
if ($role === HAK_WALI_KELAS) {
    $filterWali = " WHERE EXISTS (
        SELECT 1 FROM t_kelas_siswa ks
        INNER JOIN t_wali_kelas wk ON wk.kelas_id = ks.kelas_id AND wk.tahun_ajaran_id = ks.tahun_ajaran_id AND wk.status_aktif = 1
        INNER JOIN t_guru g ON g.id = wk.guru_id
        WHERE ks.siswa_id = s.id AND g.user_id = " . (int)($_SESSION['id'] ?? 0) . " AND ks.status_aktif = 1
    )";
}

$filterTanggal = '';
if ($tanggal_mulai !== '') {
    $filterTanggal .= " AND ps.tanggal >= '" . mysqli_real_escape_string($koneksi, $tanggal_mulai) . "'";
}
if ($tanggal_selesai !== '') {
    $filterTanggal .= " AND ps.tanggal <= '" . mysqli_real_escape_string($koneksi, $tanggal_selesai) . "'";
}

$sql = "
    SELECT
        s.id,
        s.nis,
        s.nama,
        COUNT(ps.id) AS jumlah_pelanggaran,
        COALESCE(SUM(ps.poin), 0) AS total_poin,
        COALESCE(GROUP_CONCAT(DISTINCT ta.nama ORDER BY ta.nama SEPARATOR ', '), '-') AS tahun_ajaran
    FROM t_siswa s
    LEFT JOIN t_pelanggaran_siswa ps
        ON ps.siswa_id = s.id " . $filterTanggal . "
    LEFT JOIN t_tahun_ajaran ta ON ta.id = ps.tahun_ajaran_id
    " . $filterWali . "
    GROUP BY s.id, s.nis, s.nama
    ORDER BY total_poin DESC, s.nama ASC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Query gagal: ' . mysqli_error($koneksi));
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Poin Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Rekap Poin Pelanggaran Siswa</h2>
    <hr>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <button type="button"
                class="btn btn-success"
                onclick="cetakLaporan()">
            Cetak Laporan
        </button>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Filter Rekap Poin</h5>
            <form method="GET" action="rekap_point.php">
                <?php if ($embed): ?>
                    <input type="hidden" name="embed" value="1">
                <?php endif; ?>
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-6">
                        <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" class="form-select">
                            <option value="0" <?= $tahun_ajaran_id === 0 ? 'selected' : '' ?>>Semua Tahun Ajaran</option>
                            <?php foreach ($daftarTahun as $ta): ?>
                                <option value="<?= (int)$ta['id'] ?>"
                                    data-mulai="<?= e($ta['tanggal_mulai']) ?>"
                                    data-selesai="<?= e($ta['tanggal_selesai']) ?>"
                                    <?= $tahun_ajaran_id === (int)$ta['id'] ? 'selected' : '' ?>>
                                    <?= e($ta['nama']) ?> (<?= e($ta['tanggal_mulai']) ?> s.d. <?= e($ta['tanggal_selesai']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Awal</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                               class="form-control" value="<?= e($tanggal_mulai) ?>">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                               class="form-control" value="<?= e($tanggal_selesai) ?>" min="<?= e($tanggal_mulai) ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                        <a href="rekap_point.php<?= $embed ? '?embed=1' : '' ?>" class="btn btn-outline-secondary">Reset Filter</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tabelRekapPoin"
               class="table table-bordered table-hover align-middle w-100">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jumlah Pelanggaran</th>
                    <th>Total Poin</th>
                    <th>Tahun Ajaran</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0):
                    while ($data = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($data['nis'] ?? '') ?></td>
                        <td><?= e($data['nama'] ?? '') ?></td>
                        <td><?= (int) $data['jumlah_pelanggaran'] ?></td>
                        <td>
                            <span class="badge text-bg-primary">
                                <?= (int) $data['total_poin'] ?>
                            </span>
                        </td>
                        <td><?= e($data['tahun_ajaran'] ?? '-') ?></td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data rekap poin pelanggaran siswa.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabel = document.getElementById('tabelRekapPoin');

    if (
        tabel &&
        typeof DataTable !== 'undefined' &&
        !DataTable.isDataTable(tabel)
    ) {
        const baris = tabel.querySelectorAll('tbody tr');
        const pesanKosong = baris.length === 1 &&
            baris[0].querySelectorAll('td').length === 1;

        if (!pesanKosong) {
            new DataTable(tabel, {
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [],
                scrollX: true,
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Data tidak ditemukan',
                    emptyTable: 'Belum ada data rekap poin',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });
        }
    }
});

// Saat tahun ajaran diganti, tanggal awal dan akhir otomatis ikut berubah.
document.addEventListener('DOMContentLoaded', function () {
    const pilihTahun = document.getElementById('tahun_ajaran_id');
    const inputMulai = document.getElementById('tanggal_mulai');
    const inputSelesai = document.getElementById('tanggal_selesai');

    if (pilihTahun && inputMulai && inputSelesai) {
        pilihTahun.addEventListener('change', function () {
            const opsi = pilihTahun.options[pilihTahun.selectedIndex];
            if (pilihTahun.value === '0') {
                inputMulai.value = '';
                inputSelesai.value = '';
            } else {
                inputMulai.value = opsi.dataset.mulai || '';
                inputSelesai.value = opsi.dataset.selesai || '';
            }
        });
    }
});

function cetakLaporan() {
    window.print();
}

function kembaliDashboard(event) {
    try {
        if (window.parent !== window) {
            const frame = window.parent.document.getElementById('contentFrame');

            if (frame) {
                window.parent.document
                    .querySelectorAll('.sidebar .nav-link')
                    .forEach(function (link) {
                        link.classList.remove('active');
                    });

                const dashboardLink = window.parent.document.querySelector(
                    '.sidebar .nav-link[href*="dashboard"]'
                );

                if (dashboardLink) {
                    dashboardLink.classList.add('active');
                }

                frame.src = 'dashboard.php?embed=1';
                event.preventDefault();
                return false;
            }
        }
    } catch (error) {
        // Gunakan tautan dashboard biasa jika tidak berada dalam iframe.
    }

    return true;
}
</script>

<style>
@media print {
    .btn,
    .dt-search,
    .dt-length,
    .dt-paging,
    .dt-info,\n    .card {
        display: none !important;
    }

    body,
    .container-fluid {
        background: #fff !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    table {
        width: 100% !important;
    }
}
</style>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>