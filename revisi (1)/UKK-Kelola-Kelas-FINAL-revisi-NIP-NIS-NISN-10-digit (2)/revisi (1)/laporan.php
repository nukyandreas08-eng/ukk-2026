<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

$tahun_ajaran_id = (int) ($_GET['tahun_ajaran_id'] ?? 0);
$kelas_id = (int) ($_GET['kelas_id'] ?? 0);

$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

if (!$query_tahun) {
    die("Gagal mengambil data tahun ajaran: " . mysqli_error($koneksi));
}

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat, jurusan, nama"
);

if (!$query_kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

$sql = "
    SELECT
        s.nis,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        k.tingkat,
        k.jurusan,
        ta.nama AS tahun_ajaran
    FROM t_kelas_siswa ks
    JOIN t_siswa s ON ks.siswa_id = s.id
    JOIN t_kelas k ON ks.kelas_id = k.id
    JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    WHERE 1 = 1
";

if ($tahun_ajaran_id > 0) {
    $sql .= " AND ks.tahun_ajaran_id = $tahun_ajaran_id";
}

if ($kelas_id > 0) {
    $sql .= " AND ks.kelas_id = $kelas_id";
}

$sql .= " ORDER BY k.nama ASC, s.nama ASC";

$query_laporan = mysqli_query($koneksi, $sql);

if (!$query_laporan) {
    die("Gagal mengambil laporan: " . mysqli_error($koneksi));
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
    <title>Laporan Penempatan Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Laporan Penempatan Siswa</h2>
    <p>Laporan data siswa berdasarkan kelas dan tahun ajaran.</p>
    <hr>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <button type="button" class="btn btn-success" onclick="cetakLaporan()">
            Cetak Laporan
        </button>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Filter Laporan</h5>

            <form method="GET" action="laporan.php">
                <?php if ($embed): ?>
                    <input type="hidden" name="embed" value="1">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-12 col-md-5">
                        <label for="tahun_ajaran_id" class="form-label">
                            Tahun Ajaran
                        </label>

                        <select id="tahun_ajaran_id"
                                name="tahun_ajaran_id"
                                class="form-select">
                            <option value="0">Semua Tahun Ajaran</option>

                            <?php while ($ta = mysqli_fetch_assoc($query_tahun)): ?>
                                <option
                                    value="<?= (int) $ta['id'] ?>"
                                    <?= $tahun_ajaran_id === (int) $ta['id'] ? 'selected' : '' ?>>
                                    <?= e($ta['nama']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-5">
                        <label for="kelas_id" class="form-label">Kelas</label>

                        <select id="kelas_id"
                                name="kelas_id"
                                class="form-select">
                            <option value="0">Semua Kelas</option>

                            <?php while ($kelas = mysqli_fetch_assoc($query_kelas)): ?>
                                <option
                                    value="<?= (int) $kelas['id'] ?>"
                                    <?= $kelas_id === (int) $kelas['id'] ? 'selected' : '' ?>>
                                    <?= e(
                                        $kelas['nama'] . ' - ' .
                                        $kelas['tingkat'] . ' - ' .
                                        $kelas['jurusan']
                                    ) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            Tampilkan
                        </button>

                        <a href="laporan.php<?= $embed ? '?embed=1' : '' ?>"
                           class="btn btn-outline-secondary">
                            Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Data Penempatan Siswa</h4>

    <div class="table-responsive">
        <table id="tabelLaporan"
               class="table table-bordered table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Tahun Ajaran</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query_laporan) > 0):
                    while ($row = mysqli_fetch_assoc($query_laporan)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($row['nis'] ?? '') ?></td>
                        <td><?= e($row['nama_siswa'] ?? '') ?></td>
                        <td><?= e($row['nama_kelas'] ?? '') ?></td>
                        <td><?= e($row['tingkat'] ?? '') ?></td>
                        <td><?= e($row['jurusan'] ?? '') ?></td>
                        <td><?= e($row['tahun_ajaran'] ?? '') ?></td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak ada data penempatan siswa.
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
    const tabel = document.getElementById('tabelLaporan');

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
                    emptyTable: 'Tidak ada data penempatan siswa',
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
        // Gunakan tautan dashboard biasa jika tidak berada di dalam iframe.
    }

    return true;
}
</script>

<style>
@media print {
    .btn,
    form,
    .dt-search,
    .dt-length,
    .dt-paging,
    .dt-info {
        display: none !important;
    }

    body,
    .container-fluid,
    .card {
        background: #fff !important;
        border: none !important;
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